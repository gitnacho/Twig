<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Compila un nodo a código PHP.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Compiler implements Twig_CompilerInterface
{
    protected $lastLine;
    protected $source;
    protected $indentation;
    protected $env;
    protected $debugInfo;
    protected $sourceOffset;
    protected $sourceLine;
    protected $filename;

    /**
     * Constructor.
     *
     * @param Twig_Environment $env La instancia del environment twig
     */
    public function __construct(Twig_Environment $env)
    {
        $this->env = $env;
        $this->debugInfo = array();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Devuelve la instancia del environment relacionada a este compilador.
     *
     * @return Twig_Environment La instancia de environment
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * Obtiene el código PHP actual después de la compilación.
     *
     * @return string El código PHP
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Compila un nodo.
     *
     * @param Twig_NodeInterface $node        El nodo a compilar
     * @param integer            $indentation La sangría actual
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function compile(Twig_NodeInterface $node, $indentation = 0)
    {
        $this->lastLine = null;
        $this->source = '';
        $this->sourceOffset = 0;
        // source code starts at 1 (as we then increment it when we encounter new lines)
        $this->sourceLine = 1;
        $this->indentation = $indentation;

        if ($node instanceof Twig_Node_Module) {
            $this->filename = $node->getAttribute('filename');
        }

        $node->compile($this);

        return $this;
    }

    public function subcompile(Twig_NodeInterface $node, $raw = true)
    {
        if (false === $raw) {
            $this->addIndentation();
        }

        $node->compile($this);

        return $this;
    }

    /**
     * Añade una cadena cruda al código compilado.
     *
     * @param string $string La cadena
     *
     * @return Twig_Compiler La instancia actual del compilador
     */
    public function raw($string)
    {
        $this->source .= $string;

        return $this;
    }

    /**
     * Escribe una cadena al código compilado añadiéndole sangría.
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function write()
    {
        $strings = func_get_args();
        foreach ($strings as $string) {
            $this->addIndentation();
            $this->source .= $string;
        }

        return $this;
    }

    /**
     * Agrega sangría al código *PHP* actual después de compilarlo.
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function addIndentation()
    {
        $this->source .= str_repeat(' ', $this->indentation * 4);

        return $this;
    }

    /**
     * Añade un cadena entrecomillada al código compilado.
     *
     * @param string $value La cadena
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function string($value)
    {
        $this->source .= sprintf('"%s"', addcslashes($value, "\0\t\"\$\\"));

        return $this;
    }

    /**
     * Devuelve una representación PHP de un determinado valor.
     *
     * @param mixed $value El valor a convertir
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function repr($value)
    {
        if (is_int($value) || is_float($value)) {
            if (false !== $locale = setlocale(LC_NUMERIC, 0)) {
                setlocale(LC_NUMERIC, 'C');
            }

            $this->raw($value);

            if (false !== $locale) {
                setlocale(LC_NUMERIC, $locale);
            }
        } elseif (null === $value) {
            $this->raw('null');
        } elseif (is_bool($value)) {
            $this->raw($value ? 'true' : 'false');
        } elseif (is_array($value)) {
            $this->raw('array(');
            $i = 0;
            foreach ($value as $key => $value) {
                if ($i++) {
                    $this->raw(', ');
                }
                $this->repr($key);
                $this->raw(' => ');
                $this->repr($value);
            }
            $this->raw(')');
        } else {
            $this->string($value);
        }

        return $this;
    }

    /**
     * Añade información para depuración.
     *
     * @param Twig_NodeInterface $node El nodo twig relacionado
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function addDebugInfo(Twig_NodeInterface $node)
    {
        if ($node->getLine() != $this->lastLine) {
            $this->write("// line {$node->getLine()}\n");

            // cuando mbstring.func_overload se fija a 2
            // mb_substr_count() sustituye a substr_count()
            // ¡pero estas tienen diferentes firmas!
            if (((int) ini_get('mbstring.func_overload')) & 2) {
                // esto es mucho más lento que la versión "directa"
                $this->sourceLine += mb_substr_count(mb_substr($this->source, $this->sourceOffset), "\n");
            } else {
                $this->sourceLine += substr_count($this->source, "\n", $this->sourceOffset);
            }
            $this->sourceOffset = strlen($this->source);
            $this->debugInfo[$this->sourceLine] = $node->getLine();

            $this->lastLine = $node->getLine();
        }

        return $this;
    }

    public function getDebugInfo()
    {
        return $this->debugInfo;
    }

    /**
     * Sangra el código generado.
     *
     * @param integer $step La cantidad de espacios por añadir a la sangría
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function indent($step = 1)
    {
        $this->indentation += $step;

        return $this;
    }

    /**
     * Aplica formato al código generado.
     *
     * @param integer $step La cantidad de espacios a quitar de la sangría
     *
     * @return Twig_Compiler La instancia del compilador actual
     */
    public function outdent($step = 1)
    {
        // can't outdent by more steps than the current indentation level
        if ($this->indentation < $step) {
            throw new LogicException('Unable to call outdent() as the indentation would become negative');
        }

        $this->indentation -= $step;

        return $this;
    }
}
