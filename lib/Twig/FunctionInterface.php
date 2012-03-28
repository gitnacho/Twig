<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2010 Fabien Potencier
 * (c) 2010 Arnaud Le Blanc
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Represents a template function.
 *
 * @package    twig
 * @author     Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
interface Twig_FunctionInterface
{
    /**
     * Compiles a function.
     *
     * @return string The PHP code for the function
     */
    function compile();

    function needsEnvironment();

    function needsContext();

    function getSafe(Twig_Node $filterArgs);

    function setArguments($arguments);

    function getArguments();
}
