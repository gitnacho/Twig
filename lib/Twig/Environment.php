<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Guarda la configuración de Twig.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Environment
{
    const VERSION = '1.8.2-DEV';

    protected $charset;
    protected $loader;
    protected $debug;
    protected $autoReload;
    protected $cache;
    protected $lexer;
    protected $parser;
    protected $compiler;
    protected $baseTemplateClass;
    protected $extensions;
    protected $parsers;
    protected $visitors;
    protected $filters;
    protected $tests;
    protected $functions;
    protected $globals;
    protected $runtimeInitialized;
    protected $loadedTemplates;
    protected $strictVariables;
    protected $unaryOperators;
    protected $binaryOperators;
    protected $templateClassPrefix = '__TwigTemplate_';
    protected $functionCallbacks;
    protected $filterCallbacks;
    protected $staging;

    /**
     * Constructor.
     *
     * Opciones disponibles:
     *
     *  * debug: When set to true, it automatically set "auto_reload" to true as
     *           well (default to false).
     *
     *  * charset: El juego de caracteres usado por las plantillas (por omisión es
     *             ``utf-8``).
     *
     *  * base_template_class: La clase plantilla base a usar para las plantillas
     *                         generadas (por omisión es Twig_Template).
     *
     *  * cache: Una ruta absoluta en dónde guardar las plantillas compiladas, o
     *           `false` para desactivar la memorización de la compilación
     *           (predefinido)

     *
     *  * auto_reload: Cuando para recargar la plantilla cambió la fuente original.
     *                 Si no provees la opción ``auto_reload``, esta se determinará
     *                 automáticamente basándose en el valor de ``debug``.
     *
     *  * strict_variables: Cuando se ignoren las variables no válidas en las
     *                      plantillas (por omisión es ``false``).

     *
     *  * autoescape: si activar el autoescape (por omisión es html);
     *                  * false: desactiva el autoescape
     *                  * true: equivalente a html
     *                  * html, js: Fija el autoescape a una de las estrategias compatibles
     *                  * PHP callback: una retrollamada PHP que devuelve una estrategia de
     * escape basándose en el "nombre de archivo" de la plantilla
     *
     *  * optimizations: Un indicador que determina cual optimización aplicar
     *                   (por omisión es -1 que significa activar todas las
     *                   para desactivarla ponla a ``0``).
     *
     * @param Twig_LoaderInterface $loader  A Twig_LoaderInterface instance
     * @param array                $options An array of options
     */
    public function __construct(Twig_LoaderInterface $loader = null, $options = array())
    {
        if (null !== $loader) {
            $this->setLoader($loader);
        }

        $options = array_merge(array(
            'debug'               => false,
            'charset'             => 'UTF-8',
            'base_template_class' => 'Twig_Template',
            'strict_variables'    => false,
            'autoescape'          => 'html',
            'cache'               => false,
            'auto_reload'         => null,
            'optimizations'       => -1,
        ), $options);

        $this->debug              = (bool) $options['debug'];
        $this->charset            = $options['charset'];
        $this->baseTemplateClass  = $options['base_template_class'];
        $this->autoReload         = null === $options['auto_reload'] ? $this->debug : (bool) $options['auto_reload'];
        $this->extensions         = array(
            'core'      => new Twig_Extension_Core(),
            'escaper'   => new Twig_Extension_Escaper($options['autoescape']),
            'optimizer' => new Twig_Extension_Optimizer($options['optimizations']),
        );
        $this->strictVariables    = (bool) $options['strict_variables'];
        $this->runtimeInitialized = false;
        $this->setCache($options['cache']);
        $this->functionCallbacks = array();
        $this->filterCallbacks = array();
        $this->staging = array(
            'functions'     => array(),
            'filters'       => array(),
            'tests'         => array(),
            'token_parsers' => array(),
            'visitors'      => array(),
            'globals'       => array(),
        );
    }

    /**
     * Recupera la clase de la plantilla base para las plantillas compiladas.
     *
     * @return string El nombre de la clase de la plantilla base
     */
    public function getBaseTemplateClass()
    {
        return $this->baseTemplateClass;
    }

    /**
     * Establece la clase de la plantilla base para las plantillas compiladas.
     *
     * @param string $class El nombre de clase de la plantilla base
     */
    public function setBaseTemplateClass($class)
    {
        $this->baseTemplateClass = $class;
    }

    /**
     * Activa el modo de depuración.
     */
    public function enableDebug()
    {
        $this->debug = true;
    }

    /**
     * Desactiva el modo de depuración.
     */
    public function disableDebug()
    {
        $this->debug = false;
    }

    /**
     * Comprueba si está activado el modo de depuración.
     *
     * @return Boolean true si el modo de depuración está activo,
     *          false en cualquier otro caso
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Activa la opción auto_reload.
     */
    public function enableAutoReload()
    {
        $this->autoReload = true;
    }

    /**
     * Desactiva la opción auto_reload.
     */
    public function disableAutoReload()
    {
        $this->autoReload = false;
    }

    /**
     * Comprueba si la opción auto_reload está activada.
     *
     * @return Boolean true si auto_reload está activada, false en cualquier otro caso
     */
    public function isAutoReload()
    {
        return $this->autoReload;
    }

    /**
     * Activa la opción strict_variables.
     */
    public function enableStrictVariables()
    {
        $this->strictVariables = true;
    }

    /**
     * Desactiva la opción strict_variables.
     */
    public function disableStrictVariables()
    {
        $this->strictVariables = false;
    }

    /**
     * Comprueba si está activada la opción strict_variables.
     *
     * @return Boolean true se strict_variables está habilitada, false en otro caso
     */
    public function isStrictVariables()
    {
        return $this->strictVariables;
    }

    /**
     * Obtiene el directorio de caché o false si está desactivada la caché.
     *
     * @return string|false
     */
    public function getCache()
    {
        return $this->cache;
    }

     /**
      * Establece el directorio de caché o false si la caché está desactivada.
      *
      * @param string|false $cache La ruta absoluta a las plantillas compiladas,
      *                            o false para desactivar la caché
      */
    public function setCache($cache)
    {
        $this->cache = $cache ? $cache : false;
    }

    /**
     * Obtiene el nombre del archivo de caché de una plantilla dada.
     *
     * @param string $name El nombre de la plantilla
     *
     * @return string El nombre del archivo de caché
     */
    public function getCacheFilename($name)
    {
        if (false === $this->cache) {
            return false;
        }

        $class = substr($this->getTemplateClass($name), strlen($this->templateClassPrefix));

        return $this->getCache().'/'.substr($class, 0, 2).'/'.substr($class, 2, 2).'/'.substr($class, 4).'.php';
    }

    /**
     * Obtiene la clase de la plantilla asociada con la cadena dada.
     *
     * @param string  $name  El nombre para el cual calcular el nombre
     *                       de clase de la plantilla
     * @param integer $index El índice si es una plantilla 'embed'
     *
     * @return string El nombre de clase de la plantilla
     */
    public function getTemplateClass($name, $index = null)
    {
        return $this->templateClassPrefix.md5($this->loader->getCacheKey($name)).(null === $index ? '' : '_'.$index);
    }

    /**
     * Obtiene el prefijo de clase de la plantilla.
     *
     * @return string El prefijo para la clase de la plantilla
     */
    public function getTemplateClassPrefix()
    {
        return $this->templateClassPrefix;
    }

    /**
     * Reproduce una plantilla.
     *
     * @param string $name    El nombre de la plantilla
     * @param array  $context Una matriz de parámetros por pasar a la plantilla
     *
     * @return string La plantilla pintada
     */
    public function render($name, array $context = array())
    {
        return $this->loadTemplate($name)->render($context);
    }

    /**
     * Muestra una plantilla.
     *
     * @param string $name    El nombre de la plantilla
     * @param array  $context Una matriz de parámetros por pasar a la plantilla
     */
    public function display($name, array $context = array())
    {
        $this->loadTemplate($name)->display($context);
    }

    /**
     * Carga una plantilla por nombre.
     *
     * @param string $name   El nombre de la plantilla
     * @param integer $index El índice si es una plantilla 'embed'
     *
     * @return Twig_TemplateInterface A template instance representing the given template name
     */
    public function loadTemplate($name, $index = null)
    {
        $cls = $this->getTemplateClass($name, $index);

        if (isset($this->loadedTemplates[$cls])) {
            return $this->loadedTemplates[$cls];
        }

        if (!class_exists($cls, false)) {
            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($this->loader->getSource($name), $name));
            } else {
                if (!is_file($cache) || ($this->isAutoReload() && !$this->isTemplateFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($this->loader->getSource($name), $name));
                }

                require_once $cache;
            }
        }

        if (!$this->runtimeInitialized) {
            $this->initRuntime();
        }

        return $this->loadedTemplates[$cls] = new $cls($this);
    }

    /**
     * Devuelve true si la plantilla aún está fresca.
     *
     * Besides checking the loader for freshness information,
     * this method also checks if the enabled extensions have
     * not changed.
     *
     * @param string    $name El nombre de la plantilla
     * @param timestamp $time The last modification time of the cached template
     *
     * @return Boolean true if the template is fresh, false otherwise
     */
    public function isTemplateFresh($name, $time)
    {
        foreach ($this->extensions as $extension) {
            $r = new ReflectionObject($extension);
            if (filemtime($r->getFileName()) > $time) {
                return false;
            }
        }

        return $this->loader->isFresh($name, $time);
    }

    public function resolveTemplate($names)
    {
        if (!is_array($names)) {
            $names = array($names);
        }

        foreach ($names as $name) {
            if ($name instanceof Twig_Template) {
                return $name;
            }

            try {
                return $this->loadTemplate($name);
            } catch (Twig_Error_Loader $e) {
            }
        }

        if (1 === count($names)) {
            throw $e;
        }

        throw new Twig_Error_Loader(sprintf('Unable to find one of the following templates: "%s".', implode('", "', $names)));
    }

    /**
     * Clears the internal template cache.
     */
    public function clearTemplateCache()
    {
        $this->loadedTemplates = array();
    }

    /**
     * Clears the template cache files on the filesystem.
     */
    public function clearCacheFiles()
    {
        if (false === $this->cache) {
            return;
        }

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->cache), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            if ($file->isFile()) {
                @unlink($file->getPathname());
            }
        }
    }

    /**
     * Gets the Lexer instance.
     *
     * @return Twig_LexerInterface A Twig_LexerInterface instance
     */
    public function getLexer()
    {
        if (null === $this->lexer) {
            $this->lexer = new Twig_Lexer($this);
        }

        return $this->lexer;
    }

    /**
     * Sets the Lexer instance.
     *
     * @param Twig_LexerInterface A Twig_LexerInterface instance
     */
    public function setLexer(Twig_LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Segmenta el código fuente.
     *
     * @param string $source The template source code
     * @param string $name   The template name
     *
     * @return Twig_TokenStream A Twig_TokenStream instance
     */
    public function tokenize($source, $name = null)
    {
        return $this->getLexer()->tokenize($source, $name);
    }

    /**
     * Gets the Parser instance.
     *
     * @return Twig_ParserInterface A Twig_ParserInterface instance
     */
    public function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new Twig_Parser($this);
        }

        return $this->parser;
    }

    /**
     * Sets the Parser instance.
     *
     * @param Twig_ParserInterface A Twig_ParserInterface instance
     */
    public function setParser(Twig_ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parses a token stream.
     *
     * @param Twig_TokenStream $tokens A Twig_TokenStream instance
     *
     * @return Twig_Node_Module A Node tree
     */
    public function parse(Twig_TokenStream $tokens)
    {
        return $this->getParser()->parse($tokens);
    }

    /**
     * Gets the Compiler instance.
     *
     * @return Twig_CompilerInterface A Twig_CompilerInterface instance
     */
    public function getCompiler()
    {
        if (null === $this->compiler) {
            $this->compiler = new Twig_Compiler($this);
        }

        return $this->compiler;
    }

    /**
     * Sets the Compiler instance.
     *
     * @param Twig_CompilerInterface $compiler A Twig_CompilerInterface instance
     */
    public function setCompiler(Twig_CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Compila un nodo.
     *
     * @param Twig_NodeInterface $node A Twig_NodeInterface instance
     *
     * @return string The compiled PHP source code
     */
    public function compile(Twig_NodeInterface $node)
    {
        return $this->getCompiler()->compile($node)->getSource();
    }

    /**
     * Compiles a template source code.
     *
     * @param string $source The template source code
     * @param string $name   The template name
     *
     * @return string The compiled PHP source code
     */
    public function compileSource($source, $name = null)
    {
        try {
            return $this->compile($this->parse($this->tokenize($source, $name)));
        } catch (Twig_Error $e) {
            $e->setTemplateFile($name);
            throw $e;
        } catch (Exception $e) {
            throw new Twig_Error_Runtime(sprintf('An exception has been thrown during the compilation of a template ("%s").', $e->getMessage()), -1, $name, $e);
        }
    }

    /**
     * Sets the Loader instance.
     *
     * @param Twig_LoaderInterface $loader A Twig_LoaderInterface instance
     */
    public function setLoader(Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Gets the Loader instance.
     *
     * @return Twig_LoaderInterface A Twig_LoaderInterface instance
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Sets the default template charset.
     *
     * @param string $charset The default charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Gets the default template charset.
     *
     * @return string The default charset
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Inicia el entorno en tiempo de ejecución.
     */
    public function initRuntime()
    {
        $this->runtimeInitialized = true;

        foreach ($this->getExtensions() as $extension) {
            $extension->initRuntime($this);
        }
    }

    /**
     * Returns true if the given extension is registered.
     *
     * @param string $name El nombre de la extensión
     *
     * @return Boolean Whether the extension is registered or not
     */
    public function hasExtension($name)
    {
        return isset($this->extensions[$name]);
    }

    /**
     * Gets an extension by name.
     *
     * @param string $name El nombre de la extensión
     *
     * @return Twig_ExtensionInterface Una instancia de Twig_ExtensionInterface
     */
    public function getExtension($name)
    {
        if (!isset($this->extensions[$name])) {
            throw new Twig_Error_Runtime(sprintf('The "%s" extension is not enabled.', $name));
        }

        return $this->extensions[$name];
    }

    /**
     * Registra una extensión.
     *
     * @param Twig_ExtensionInterface $extension A Twig_ExtensionInterface instance
     */
    public function addExtension(Twig_ExtensionInterface $extension)
    {
        $this->extensions[$extension->getName()] = $extension;
        $this->parsers = null;
        $this->visitors = null;
        $this->filters = null;
        $this->tests = null;
        $this->functions = null;
        $this->globals = null;
    }

    /**
     * Quita una extensión por nombre.
     *
     * @param string $name El nombre de la extensión
     */
    public function removeExtension($name)
    {
        unset($this->extensions[$name]);
        $this->parsers = null;
        $this->visitors = null;
        $this->filters = null;
        $this->tests = null;
        $this->functions = null;
        $this->globals = null;
    }

    /**
     * Registra una matriz de extensiones.
     *
     * @param array $extensions Una matriz de extensiones
     */
    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }

    /**
     * Devuelve todas las extensiones registradas.
     *
     * @return array Devuelve un arreglo de extensiones
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Registers a Token Parser.
     *
     * @param Twig_TokenParserInterface $parser A Twig_TokenParserInterface instance
     */
    public function addTokenParser(Twig_TokenParserInterface $parser)
    {
        $this->staging['token_parsers'][] = $parser;
        $this->parsers = null;
    }

    /**
     * Gets the registered Token Parsers.
     *
     * @return Twig_TokenParserBrokerInterface A broker containing token parsers
     */
    public function getTokenParsers()
    {
        if (null === $this->parsers) {
            $this->parsers = new Twig_TokenParserBroker();

            if (isset($this->staging['token_parsers'])) {
                foreach ($this->staging['token_parsers'] as $parser) {
                    $this->parsers->addTokenParser($parser);
                }
            }

            foreach ($this->getExtensions() as $extension) {
                $parsers = $extension->getTokenParsers();
                foreach($parsers as $parser) {
                    if ($parser instanceof Twig_TokenParserInterface) {
                        $this->parsers->addTokenParser($parser);
                    } elseif ($parser instanceof Twig_TokenParserBrokerInterface) {
                        $this->parsers->addTokenParserBroker($parser);
                    } else {
                        throw new Twig_Error_Runtime('getTokenParsers() must return an array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances');
                    }
                }
            }
        }

        return $this->parsers;
    }

    /**
     * Obtiene las etiquetas registradas.
     *
     * Be warned that this method cannot return tags defined by Twig_TokenParserBrokerInterface classes.
     *
     * @return Twig_TokenParserInterface[] An array of Twig_TokenParserInterface instances
     */
    public function getTags()
    {
        $tags = array();
        foreach ($this->getTokenParsers()->getParsers() as $parser) {
            if ($parser instanceof Twig_TokenParserInterface) {
                $tags[$parser->getTag()] = $parser;
            }
        }

        return $tags;
    }

    /**
     * Registra un visitante de nodo.
     *
     * @param Twig_NodeVisitorInterface $visitor A Twig_NodeVisitorInterface instance
     */
    public function addNodeVisitor(Twig_NodeVisitorInterface $visitor)
    {
        $this->staging['visitors'][] = $visitor;
        $this->visitors = null;
    }

    /**
     * Gets the registered Node Visitors.
     *
     * @return Twig_NodeVisitorInterface[] An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        if (null === $this->visitors) {
            foreach ($this->getExtensions() as $extension) {
                foreach ($extension->getNodeVisitors() as $visitor) {
                    $this->addNodeVisitor($visitor);
                }
            }

            $this->visitors = $this->staging['visitors'];
        }

        return $this->visitors;
    }

    /**
     * Registra un filtro.
     *
     * @param string               $name   El nombre del filtro
     * @param Twig_FilterInterface $filter Una instancia de Twig_FilterInterface
     */
    public function addFilter($name, Twig_FilterInterface $filter)
    {
        $this->staging['filters'][$name] = $filter;
        $this->filters = null;
    }

    /**
     * Recupera un filtro por nombre.
     *
     * Las subclases pueden redefinir este método y cargar filtros de manera diferente;
     * so no list of filters is available.
     *
     * @param string $name El nombre del filtro
     *
     * @return Twig_Filter|false A Twig_Filter instance or false if the filter does not exists
     */
    public function getFilter($name)
    {
        if (null === $this->filters) {
            $this->getFilters();
        }

        if (isset($this->filters[$name])) {
            return $this->filters[$name];
        }

        foreach ($this->filters as $pattern => $filter) {
            $pattern = str_replace('\\*', '(.*?)', preg_quote($pattern, '#'), $count);

            if ($count) {
                if (preg_match('#^'.$pattern.'$#', $name, $matches)) {
                    array_shift($matches);
                    $filter->setArguments($matches);

                    return $filter;
                }
            }
        }

        foreach ($this->filterCallbacks as $callback) {
            if (false !== $filter = call_user_func($callback, $name)) {
                return $filter;
            }
        }

        return false;
    }

    public function registerUndefinedFilterCallback($callable)
    {
        $this->filterCallbacks[] = $callable;
    }

    /**
     * Gets the registered Filters.
     *
     * Be warned that this method cannot return filters defined with registerUndefinedFunctionCallback.
     *
     * @return Twig_FilterInterface[] An array of Twig_FilterInterface instances
     *
     * @see registerUndefinedFilterCallback
     */
    public function getFilters()
    {
        if (null === $this->filters) {
            foreach ($this->getExtensions() as $extension) {
                foreach ($extension->getFilters() as $name => $filter) {
                    $this->addFilter($name, $filter);
                }
            }

            $this->filters = $this->staging['filters'];
        }

        return $this->filters;
    }

    /**
     * Registra una prueba.
     *
     * @param string             $name El nombre de la prueba
     * @param Twig_TestInterface $test A Twig_TestInterface instance
     */
    public function addTest($name, Twig_TestInterface $test)
    {
        $this->staging['tests'][$name] = $test;
        $this->tests = null;
    }

    /**
     * Gets the registered Tests.
     *
     * @return Twig_TestInterface[] An array of Twig_TestInterface instances
     */
    public function getTests()
    {
        if (null === $this->tests) {
            foreach ($this->getExtensions() as $extension) {
                foreach ($extension->getTests() as $name => $test) {
                    $this->addTest($name, $test);
                }
            }

            $this->tests = $this->staging['tests'];
        }

        return $this->tests;
    }

    /**
     * Registra una Función.
     *
     * @param string                 $name     El nombre de la función
     * @param Twig_FunctionInterface $function A Twig_FunctionInterface instance
     */
    public function addFunction($name, Twig_FunctionInterface $function)
    {
        $this->staging['functions'][$name] = $function;
        $this->functions = null;
    }

    /**
     * Get a function by name.
     *
     * Subclasses may override this method and load functions differently;
     * so no list of functions is available.
     *
     * @param string $name el nombre de la función
     *
     * @return Twig_Function|false A Twig_Function instance or false if the function does not exists
     */
    public function getFunction($name)
    {
        if (null === $this->functions) {
            $this->getFunctions();
        }

        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }

        foreach ($this->functions as $pattern => $function) {
            $pattern = str_replace('\\*', '(.*?)', preg_quote($pattern, '#'), $count);

            if ($count) {
                if (preg_match('#^'.$pattern.'$#', $name, $matches)) {
                    array_shift($matches);
                    $function->setArguments($matches);

                    return $function;
                }
            }
        }

        foreach ($this->functionCallbacks as $callback) {
            if (false !== $function = call_user_func($callback, $name)) {
                return $function;
            }
        }

        return false;
    }

    public function registerUndefinedFunctionCallback($callable)
    {
        $this->functionCallbacks[] = $callable;
    }

    /**
     * Gets registered functions.
     *
     * Be warned that this method cannot return functions defined with registerUndefinedFunctionCallback.
     *
     * @return Twig_FunctionInterface[] An array of Twig_FunctionInterface instances
     *
     * @see registerUndefinedFunctionCallback
     */
    public function getFunctions()
    {
        if (null === $this->functions) {
            foreach ($this->getExtensions() as $extension) {
                foreach ($extension->getFunctions() as $name => $function) {
                    $this->addFunction($name, $function);
                }
            }

            $this->functions = $this->staging['functions'];
        }

        return $this->functions;
    }

    /**
     * Registra una global.
     *
     * @param string $name  El nombre global
     * @param mixed  $value The global value
     */
    public function addGlobal($name, $value)
    {
        $this->staging['globals'][$name] = $value;
        $this->globals = null;
    }

    /**
     * Gets the registered Globals.
     *
     * @return array An array of globals
     */
    public function getGlobals()
    {
        if (null === $this->globals) {
            $this->globals = isset($this->staging['globals']) ? $this->staging['globals'] : array();
            foreach ($this->getExtensions() as $extension) {
                $this->globals = array_merge($this->globals, $extension->getGlobals());
            }
        }

        return $this->globals;
    }

    /**
     * Merges a context with the defined globals.
     *
     * @param array $context An array representing the context
     *
     * @return array The context merged with the globals
     */
    public function mergeGlobals(array $context)
    {
        // we don't use array_merge as the context being generally
        // bigger than globals, this code is faster.
        foreach ($this->getGlobals() as $key => $value) {
            if (!array_key_exists($key, $context)) {
                $context[$key] = $value;
            }
        }

        return $context;
    }

    /**
     * Gets the registered unary Operators.
     *
     * @return array An array of unary operators
     */
    public function getUnaryOperators()
    {
        if (null === $this->unaryOperators) {
            $this->initOperators();
        }

        return $this->unaryOperators;
    }

    /**
     * Obtiene los operadores binarios registrados.
     *
     * @return array Una matriz de operadores binarios
     */
    public function getBinaryOperators()
    {
        if (null === $this->binaryOperators) {
            $this->initOperators();
        }

        return $this->binaryOperators;
    }

    public function computeAlternatives($name, $items)
    {
        $alternatives = array();
        foreach ($items as $item) {
            $lev = levenshtein($name, $item);
            if ($lev <= strlen($name) / 3 || false !== strpos($item, $name)) {
                $alternatives[$item] = $lev;
            }
        }
        asort($alternatives);

        return array_keys($alternatives);
    }

    protected function initOperators()
    {
        $this->unaryOperators = array();
        $this->binaryOperators = array();
        foreach ($this->getExtensions() as $extension) {
            $operators = $extension->getOperators();

            if (!$operators) {
                continue;
            }

            if (2 !== count($operators)) {
                throw new InvalidArgumentException(sprintf('"%s::getOperators()" no devuelve un arreglo de operadores válidos.', get_class($extension)));
            }

            $this->unaryOperators = array_merge($this->unaryOperators, $operators[0]);
            $this->binaryOperators = array_merge($this->binaryOperators, $operators[1]);
        }
    }

    protected function writeCacheFile($file, $content)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException(sprintf("Imposible crear el directorio de caché (%s).", $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new RuntimeException(sprintf("Imposible escribir en el directorio de caché (%s).", $dir));
        }

        $tmpFile = tempnam(dirname($file), basename($file));
        if (false !== @file_put_contents($tmpFile, $content)) {
            // el cambio de nombre no trabaja en Win32 anterior a 5.2.6
            if (@rename($tmpFile, $file) || (@copy($tmpFile, $file) && unlink($tmpFile))) {
                @chmod($file, 0644);

                return;
            }
        }

        throw new Twig_Error_Runtime(sprintf('Fallo al escribir en caché el archivo "%s".', $file));
    }
}
