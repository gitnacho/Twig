<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */
class Twig_Extension_Optimizer extends Twig_Extension
{
    protected $optimizers;

    public function __construct($optimizers = -1)
    {
        $this->optimizers = $optimizers;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return array(new Twig_NodeVisitor_Optimizer($this->optimizers));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'optimizer';
    }
}
