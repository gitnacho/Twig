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
 * Represents a method template function.
 *
 * @package    twig
 * @author     Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
class Twig_Function_Method extends Twig_Function
{
    protected $extension;
    protected $method;

    public function __construct(Twig_ExtensionInterface $extension, $method, array $options = array())
    {
        parent::__construct($options);

        $this->extension = $extension;
        $this->method = $metodo;
    }

    public function compile()
    {
        return sprintf('$this->env->getExtension(\'%s\')->%s', $this->extension->getName(), $this->method);
    }
}
