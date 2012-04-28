<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Marks a section of a template as untrusted code that must be evaluated in the sandbox mode.
 *
 * <pre>
 * {% sandbox %}
 *     {% include 'user.html' %}
 * {% endsandbox %}
 * </pre>
 *
 * @see http://www.twig-project.org/doc/api.html#sandbox-extension for details
 */
class Twig_TokenParser_Sandbox extends Twig_TokenParser
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
        $this->parser->getStream()
                         ->expect(Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
        $this->parser->getStream()
                         ->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_Node_Sandbox($body, $token->getLine(), $this->getTag());
    }

    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endsandbox');
    }

    /**
     * Recupera el nombre de la etiqueta asociada con el analizador
     * de este fragmento.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'sandbox';
    }
}
