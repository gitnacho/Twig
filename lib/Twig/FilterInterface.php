<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Represents a template filter.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_FilterInterface
{
    /**
     * Compiles a filter.
     *
     * @return string The PHP code for the filter
     */
    function compile();

    function needsEnvironment();

    function needsContext();

    function getSafe(Twig_Node $filterArgs);

    function getPreEscape();

    function setArguments($arguments);

    function getArguments();
}
