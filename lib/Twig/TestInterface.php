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
 * Represents a template test.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_TestInterface
{
    /**
     * Compiles a test.
     *
     * @return string The PHP code for the test
     */
    function compile();
}
