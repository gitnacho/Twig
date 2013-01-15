<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_SandboxTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_Sandbox::__construct
     */
    public function testConstructor()
    {
        $body = new Twig_Node_Text('foo', 1);
        $node = new Twig_Node_Sandbox($body, 1);

        $this->assertEquals($body, $node->getNode('body'));
    }

    /**
     * @covers Twig_Node_Sandbox::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $tests = array();

        $body = new Twig_Node_Text('foo', 1);
        $node = new Twig_Node_Sandbox($body, 1);

        $tests[] = array($node, <<<EOF
// line 1
\$sandbox = \$this->env->getExtension('sandbox');
if (!\$alreadySandboxed = \$sandbox->isSandboxed()) {
    \$sandbox->enableSandbox();
}
echo "foo";
if (!\$alreadySandboxed) {
    \$sandbox->disableSandbox();
}
EOF
        );

        return $tests;
    }
}
