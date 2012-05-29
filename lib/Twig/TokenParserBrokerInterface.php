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
 * Interface implemented by token parser brokers.
 *
 * Token parser brokers allows to implement custom logic in the process of resolving a token parser for a given tag name.
 *
 * @package twig
 * @author  Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
interface Twig_TokenParserBrokerInterface
{
    /**
     * Gets a TokenParser suitable for a tag.
     *
     * @param string $tag A tag name
     *
     * @return null|Twig_TokenParserInterface A Twig_TokenParserInterface or null if no suitable TokenParser was found
     */
    function getTokenParser($tag);

    /**
     * Calls Twig_TokenParserInterface::setParser on all parsers the implementation knows of.
     *
     * @param Twig_ParserInterface $parser A Twig_ParserInterface interface
     */
    function setParser(Twig_ParserInterface $parser);

    /**
     * Gets the Twig_ParserInterface.
     *
     * @return null|Twig_ParserInterface A Twig_ParserInterface instance or null
     */
    function getParser();
}
