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
 * Interface implemented by lexer classes.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_LexerInterface
{
    /**
     * Segmenta el código fuente.
     *
     * @param string $code     The source code
     * @param string $filename A unique identifier for the source code
     *
     * @return Twig_TokenStream A token stream instance
     */
    function tokenize($code, $filename = null);
}
