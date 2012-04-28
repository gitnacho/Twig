<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */
abstract class Twig_Extension implements Twig_ExtensionInterface
{
    /**
     * Inicia el entorno en tiempo de ejecución.
     *
     * Aquí es donde puedes cargar algún archivo que contenga funciones
         * de filtro, por ejemplo.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(Twig_Environment $environment)
    {
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
        return array();
    }

    /**
     * Devuelve instancias del visitante de nodos para añadirlas a la
         * lista existente.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return array();
    }

    /**
     * Devuelve una lista de filtros para añadirla a la lista
         * existente.
     *
     * @return array Una matriz de filtros
     */
    public function getFilters()
    {
        return array();
    }

    /**
     * Devuelve una lista de pruebas para añadirla a la lista
         *  existente.
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return array();
    }

    /**
     * Devuelve una lista de funciones para añadirla a la lista
         * existente.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array();
    }

    /**
     * Devuelve una lista de operadores para añadirla a la lista
         * existente.
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return array();
    }

    /**
     * Devuelve una lista de variables globales para añadirla a la
         * lista existente.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return array();
    }
}
