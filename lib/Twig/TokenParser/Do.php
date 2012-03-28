<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2011 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Evaluates an expression, disgarding the returned value.
 */
class Twig_TokenParser_Do extends Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param Twig_Token $token A Twig_Token instance
     *
     * @return Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        $this->parser->getStream()
                         ->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_Node_Do($expr, $token->getLine(), $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'do';
    }
}
