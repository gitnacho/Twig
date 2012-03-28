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
 * Imports blocks defined in another template into the current template.
 *
 * <pre>
 * {% extends "base.html" %}
 *
 * {% use "bloques.html" %}
 *
 * {% block title %}{% endblock %}
 * {% block content %}{% endblock %}
 * </pre>
 *
 * @see http://www.twig-project.org/doc/templates.html#horizontal-reuse for details.
 */
class Twig_TokenParser_Use extends Twig_TokenParser
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
        $template = $this->parser->getExpressionParser()->parseExpression();

        if (!$template instanceof Twig_Node_Expression_Constant) {
            throw new Twig_Error_Syntax('The template references in a "use" statement must be a string.', $token->getLine());
        }

        $stream = $this->parser->getStream();

        $targets = array();
        if ($stream->test('with')) {
            $stream->next();

            do {
                $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();

                $alias = $name;
                if ($stream->test('as')) {
                    $stream->next();

                    $alias = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
                }

                $targets[$name] = new Twig_Node_Expression_Constant($alias, -1);

                if (!$stream->test(Twig_Token::PUNCTUATION_TYPE, ',')) {
                    break;
                }

                $stream->next();
            } while (true);
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        $this->parser->addTrait(new Twig_Node(array('template' => $template, 'targets' => new Twig_Node($targets))));

        return null;
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'use';
    }
}
