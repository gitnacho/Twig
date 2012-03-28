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
 * Represents a function template test.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Test_Function implements Twig_TestInterface
{
    protected $function;

    public function __construct($function)
    {
        $this->function = $function;
    }

    public function compile()
    {
        return $this->function;
    }
}
