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
 * Base class for all token parsers.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
abstract class Twig_TokenParser implements Twig_TokenParserInterface
{
    /**
     * @var Twig_Parser
     */
    protected $parser;

    /**
     * Sets the parser associated with this token parser
     *
     * @param $parser A Twig_Parser instance
     */
    public function setParser(Twig_Parser $parser)
    {
        $this->parser = $parser;
    }
}
