<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2010 Arnaud Le Blanc
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Represents a function template function.
 *
 * @package    twig
 * @author     Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
class Twig_Function_Function extends Twig_Function
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
