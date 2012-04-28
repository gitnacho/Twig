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
 * Interface implemented by extension classes.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_ExtensionInterface
{
    /**
     * Inicia el entorno en tiempo de ejecución.
     *
     * Aquí es donde puedes cargar algún archivo que contenga funciones
         * de filtro, por ejemplo.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    function initRuntime(Twig_Environment $environment);

    /**
     * Devuelve instancias del analizador de segmentos para añadirlos a
         * la lista existente.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    function getTokenParsers();

    /**
     * Devuelve instancias del visitante de nodos para añadirlas a la
         * lista existente.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    function getNodeVisitors();

    /**
     * Devuelve una lista de filtros para añadirla a la lista
         * existente.
     *
     * @return array An array of filters
     */
    function getFilters();

    /**
     * Devuelve una lista de pruebas para añadirla a la lista
         *  existente.
     *
     * @return array An array of tests
     */
    function getTests();

    /**
     * Devuelve una lista de funciones para añadirla a la lista
         * existente.
     *
     * @return array An array of functions
     */
    function getFunctions();

    /**
     * Devuelve una lista de operadores para añadirla a la lista
         * existente.
     *
     * @return array An array of operators
     */
    function getOperators();

    /**
     * Devuelve una lista de variables globales para añadirla a la
         * lista existente.
     *
     * @return array An array of global variables
     */
    function getGlobals();

    /**
     * Devuelve el nombre de la extensión.
     *
     * @return string The extension name
     */
    function getName();
}
