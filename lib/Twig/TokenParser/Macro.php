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
 * Defines a macro.
 *
 * <pre>
 * {% macro input(name, value, type, size) %}
 *    <input type="{{ type|default('text') }}"
                 name="{{ name }}"
                 value="{{ value|e }}"
                 size="{{ size|default(20) }}" />
 * {% endmacro %}
 * </pre>
 */
class Twig_TokenParser_Macro extends Twig_TokenParser
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
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();

        $arguments = $this->parser->getExpressionParser()->parseArguments(true, true);

        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $this->parser->pushLocalScope();
        $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
        if ($stream->test(Twig_Token::NAME_TYPE)) {
            $value = $stream->next()->getValue();

            if ($value != $name) {
                throw new Twig_Error_Syntax(sprintf("Expected endmacro for macro '$name' (but %s given)", $value), $stream->getCurrent()->getLine(), $stream->getFilename());
            }
        }
        $this->parser->popLocalScope();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        $this->parser->setMacro($name, new Twig_Node_Macro($name, new Twig_Node_Body(array($body)), $arguments, $lineno, $this->getTag()));

        return null;
    }

    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endmacro');
    }

    /**
     * Recupera el nombre de la etiqueta asociada con el analizador
     * de este fragmento.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'macro';
    }
}
