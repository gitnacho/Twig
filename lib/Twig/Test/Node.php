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
 * Represents a template test as a Node.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Test_Node implements Twig_TestInterface
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function compile()
    {
    }
}
