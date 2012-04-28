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
 * Filters a section of a template by applying filters.
 *
 * <pre>
 * {% filter upper %}
 *  This text becomes uppercase
 * {% endfilter %}
 * </pre>
 */
class Twig_TokenParser_Filter extends Twig_TokenParser
{
    /**
     * Analiza un fragmento y devuelve un nodo.
     *
     * @param Twig_Token $token Una instancia de Twig_Token
     *
     * @return Twig_NodeInterface Una instancia de Twig_NodeInterface
     */
    public function parse(Twig_Token $token)
    {
        $name = $this->parser->getVarName();
        $ref = new Twig_Node_Expression_BlockReference(new Twig_Node_Expression_Constant($name, $token->getLine()), true, $token->getLine(), $this->getTag());

        $filter = $this->parser->getExpressionParser()->parseFilterExpressionRaw($ref, $this->getTag());
        $this->parser->getStream()
                         ->expect(Twig_Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
        $this->parser->getStream()
                         ->expect(Twig_Token::BLOCK_END_TYPE);

        $block = new Twig_Node_Block($name, $body, $token->getLine());
        $this->parser->setBlock($name, $block);

        return new Twig_Node_Print($filter, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endfilter');
    }

    /**
     * Recupera el nombre de la etiqueta asociada con el analizador
     * de este fragmento.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'filter';
    }
}
