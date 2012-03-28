<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $this->assertFalse(class_exists('FooBarFoo'), '->autoload() does not try to load classes that does not begin with Twig');

        $autoloader = new Twig_Autoloader();
        $this->assertNull($autoloader->autoload('Foo'), '->autoload() returns false if it is not able to load a class');
    }
}
