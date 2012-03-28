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
 * Interface implemented by all compiled templates.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
interface Twig_TemplateInterface
{
    const ANY_CALL    = 'any';
    const ARRAY_CALL  = 'array';
    const METHOD_CALL = 'method';

    /**
     * Renders the template with the given context and returns it as string.
     *
     * @param array $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     */
    function render(array $context);

    /**
     * Displays the template with the given context.
     *
     * @param array $context An array of parameters to pass to the template
     * @param array $blocks  An array of blocks to pass to the template
     */
    function display(array $context, array $blocks = array());

    /**
     * Returns the bound environment for this template.
     *
     * @return Twig_Environment The current environment
     */
    function getEnvironment();
}
