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
 * Interface implemented by parser classes.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
interface Twig_ParserInterface
{
    /**
     * Convierte un flujo de segmentos en un árbol de nodos.
     *
     * @param  Twig_TokenStream $stream A token stream instance
     *
     * @return Twig_Node_Module A node tree
     */
    function parse(Twig_TokenStream $stream);
}
