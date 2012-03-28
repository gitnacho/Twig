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
 * Represents a function template filter.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Filter_Function extends Twig_Filter
{
    protected $function;

    public function __construct($function, array $options = array())
    {
        parent::__construct($options);

        $this->function = $function;
    }

    public function compile()
    {
        return $this->function;
    }
}
