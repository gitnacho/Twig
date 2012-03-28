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
 * Interfaces that all security policy classes must implements.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_Sandbox_SecurityPolicyInterface
{
    function checkSecurity($tags, $filters, $functions);

    function checkMethodAllowed($obj, $method);

    function checkPropertyAllowed($obj, $method);
}
