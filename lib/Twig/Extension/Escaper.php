<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */
class Twig_Extension_Escaper extends Twig_Extension
{
    protected $autoescape;

    public function __construct($autoescape = true)
    {
        $this->autoescape = $autoescape;
    }

    /**
     * Devuelve instancias del analizador de segmentos para añadirlos a
     * la lista existente.
     *
     * @return array Una matriz de instancias de Twig_TokenParserInterface
     *               o Twig_TokenParserBrokerInterface
     */
    public function getTokenParsers();
    {
        return array(new Twig_TokenParser_AutoEscape());
    }

    /**
     * Devuelve instancias del visitante de nodos para añadirlas a la
         * lista existente.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return array(new Twig_NodeVisitor_Escaper());
    }

    /**
     * Devuelve una lista de filtros para añadirla a la lista
     * existente.
     *
     * @return array Una matriz de filtros
     */
    public function getFilters()
    {
        return array(
            'raw' => new Twig_Filter_Function('twig_raw_filter', array('is_safe' => array('all'))),
        );
    }

    public function isGlobal()
    {
        return $this->autoescape;
    }

    /**
     * Devuelve el nombre de la extensión.
     *
     * @return string El nombre de la extensión
     */
    public function getName()
    {
        return 'escaper';
    }
}

/**
 * Marks a variable as being safe.
 *
 * @param string $string A PHP variable
 */
function twig_raw_filter($string)
{
    return $string;
}

