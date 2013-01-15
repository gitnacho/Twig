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
 * Use Twig_SimpleFunction instead.
 *
 * @package    twig
 * @author     Arnaud Le Blanc <arnaud.lb@gmail.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_FunctionInterface
{
    /**
     * Compiles a function.
     *
     * @return string The PHP code for the function
     */
    public function compile();

    public function needsEnvironment();

    public function needsContext();

    public function getSafe(Twig_Node $filterArgs);

    public function setArguments($arguments);

    public function getArguments();
}
