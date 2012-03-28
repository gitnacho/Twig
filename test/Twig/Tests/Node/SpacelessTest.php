<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

require_once dirname(__FILE__).'/TestCase.php';

class Twig_Tests_Node_SpacelessTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_Spaceless::__construct
     */
    public function testConstructor()
    {
        $body = new Twig_Node(array(new Twig_Node_Text('<div>   <div>   foo   </div>   </div>', 0)));
        $node = new Twig_Node_Spaceless($body, 0);

        $this->assertEquals($body, $node->getNode('body'));
    }

    /**
     * @covers Twig_Node_Spaceless::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $body = new Twig_Node(array(new Twig_Node_Text('<div>   <div>   foo   </div>   </div>', 0)));
        $node = new Twig_Node_Spaceless($body, 0);

        return array(
            array($node, <<<EOF
ob_start();
echo "<div>   <div>   foo   </div>   </div>";
echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
EOF
            ),
        );
    }
}
