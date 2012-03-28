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
 * Marks a content as safe.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Markup implements Countable
{
    protected $content;
    protected $charset;

    public function __construct($content, $charset)
    {
        $this->content = (string) $content;
        $this->charset = $charset;
    }

    public function __toString()
    {
        return $this->content;
    }

    public function count()
    {
        return function_exists('mb_get_info') ? mb_strlen($this->content, $this->charset) : strlen($this->content);
    }
}
