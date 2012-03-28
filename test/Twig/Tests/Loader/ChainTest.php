<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Loader_ChainTest extends PHPUnit_Framework_TestCase
{
    public function testGetSource()
    {
        $loader = new Twig_Loader_Chain(array(
            new Twig_Loader_Array(array('foo' => 'bar')),
            new Twig_Loader_Array(array('foo' => 'foobar', 'bar' => 'foo')),
        ));

        $this->assertEquals('bar', $loader->getSource('foo'));
        $this->assertEquals('foo', $loader->getSource('bar'));
    }

    /**
     * @expectedException Twig_Error_Loader
     */
    public function testGetSourceWhenTemplateDoesNotExist()
    {
        $loader = new Twig_Loader_Chain(array());

        $loader->getSource('foo');
    }

    public function testGetCacheKey()
    {
        $loader = new Twig_Loader_Chain(array(
            new Twig_Loader_Array(array('foo' => 'bar')),
            new Twig_Loader_Array(array('foo' => 'foobar', 'bar' => 'foo')),
        ));

        $this->assertEquals('bar', $loader->getCacheKey('foo'));
        $this->assertEquals('foo', $loader->getCacheKey('bar'));
    }

    /**
     * @expectedException Twig_Error_Loader
     */
    public function testGetCacheKeyWhenTemplateDoesNotExist()
    {
        $loader = new Twig_Loader_Chain(array());

        $loader->getCacheKey('foo');
    }

    public function testAddLoader()
    {
        $loader = new Twig_Loader_Chain();
        $loader->addLoader(new Twig_Loader_Array(array('foo' => 'bar')));

        $this->assertEquals('bar', $loader->getSource('foo'));
    }
}
