Mejorando *Twig*
================

*Twig* es muy extensible y lo puedes mejorar fácilmente. Ten en cuenta que probablemente deberías tratar de crear una extensión antes de sumergirte en el núcleo, puesto que la mayoría de las características y mejoras se pueden hacer con extensiones. Este capítulo también es útil para personas que quieren entender cómo funciona *Twig* debajo del capó.

¿Cómo funciona *Twig*?
----------------------

La reproducción de una plantilla *Twig* se puede resumir en cuatro pasos fundamentales:

* **Cargar** la plantilla: Si la plantilla ya está compilada, la carga y va al paso *evaluación*, de lo contrario:

  * En primer lugar, el **analizador léxico** reduce el código fuente de la plantilla a pequeñas piezas para facilitar su procesamiento;
  * A continuación, el **analizador** convierte el flujo del segmento en un árbol de nodos significativo (el árbol de sintaxis abstracta);
  * Eventualmente, el *compilador* transforma el árbol de sintaxis abstracta en código *PHP*;

* **Evaluar** la plantilla: Básicamente significa llamar al método ``display()`` de la plantilla compilada adjuntando el contexto.

El analizador léxico
--------------------

El objetivo del analizador léxico de *Twig* es dividir el código fuente en un flujo de segmentos (donde cada segmento es de la clase ``token``, y el flujo es una instancia de ``Twig_TokenStream``). El analizador léxico predeterminado reconoce nueve diferentes tipos de segmentos:

* ``Twig_Token::TEXT_TYPE``
* ``Twig_Token::BLOCK_START_TYPE``
* ``Twig_Token::VAR_START_TYPE``
* ``Twig_Token::BLOCK_END_TYPE``
* ``Twig_Token::VAR_END_TYPE``
* ``Twig_Token::NAME_TYPE``
* ``Twig_Token::NUMBER_TYPE``
* ``Twig_Token::STRING_TYPE``
* ``Twig_Token::OPERATOR_TYPE``
* ``Twig_Token::EOF_TYPE``

Puedes convertir manualmente un código fuente en un flujo de segmentos llamando al método ``tokenize()`` de un entorno::

    $stream = $twig->tokenize($source, $identifier);

Dado que el flujo tiene un método ``__toString()``, puedes tener una representación textual del mismo haciendo eco del objeto::

    echo $stream."\n";

Aquí está la salida para la plantilla ``Hello {{ name }}``:

.. code-block:: text

    TEXT_TYPE(Hello )
    VAR_START_TYPE()
    NAME_TYPE(name)
    VAR_END_TYPE()
    EOF_TYPE()

Puedes cambiar el analizador léxico predeterminado que usa *Twig* (``Twig_Lexer``) llamando al método ``setLexer()``::

    $twig->setLexer($lexer);

Las clases Lexer deben implementar a ``Twig_LexerInterface``::

    interface Twig_LexerInterface
    {
        /**
         * Segmenta el código fuente.
         *
         * @param  string $code     El código fuente
         * @param string $filename Un identificador único para el
         *                                          código fuente
         *
         * @return Twig_TokenStream Una muestra de la instancia del
         *                          flujo
         */
        function tokenize($code, $filename = 'n/a');
    }

El analizador sintáctico
------------------------

El analizador convierte el flujo de segmentos en un ``ASA`` (árbol de sintaxis abstracta), o un árbol de nodos (de clase ``Twig_Node_Module``). La extensión del núcleo define los nodos básicos como: ``for``, ``if``, ... y la expresión nodos.

Puedes convertir manualmente un flujo de segmentos en un nodo del árbol llamando al método ``parse()`` de un entorno::

    $nodes = $twig->parse($stream);

Al hacer eco del objeto nodo te da una buena representación del árbol::

    echo $nodes."\n";

Aquí está la salida para la plantilla ``Hello {{ name }}``:

.. code-block:: text

    Twig_Node_Module(
      Twig_Node_Text(Hello )
      Twig_Node_Print(
        Twig_Node_Expression_Name(name)
      )
    )

El analizador predeterminado (``Twig_TokenParser``) también se puede cambiar mediante una llamada al método ``setParser()``::

    $twig->setParser($analizador);

Todos los analizadores de *Twig* deben implementar a ``Twig_ParserInterface``::

    interface Twig_ParserInterface
    {
        /**
         * Convierte un flujo de segmentos en un árbol de nodos.
         *
         * @param Twig_TokenStream $stream Una instancia de una
         *                                 muestra del flujo
         *
         * @return Twig_Node_Module Un nodo del árbol
         */
        function parser(Twig_TokenStream $code);
    }

El compilador
-------------

El último paso lo lleva a cabo el compilador. Este necesita un árbol de nodos como entrada y genera código *PHP* que se puede emplear para ejecutar las plantillas en tiempo de ejecución. El compilador predeterminado genera las clases *PHP* para facilitar la implementación de la herencia de plantillas.

Puedes llamar al compilador manualmente con el método ``compile()`` de un entorno::

    $php = $twig->compile($nodes);

El método ``compile()`` devuelve el código fuente *PHP* que representa al nodo.

La plantilla generada por un patrón ``Hello {{ name }}`` es la siguiente::

    /* Hello {{ name }} */
    class __TwigTemplate_1121b6f109fe93ebe8c6e22e3712bceb extends Twig_Template
    {
        public function display($context)
        {
            $this->env->initRuntime();

            // line 1
            echo "Hello ";
            echo (isset($context['name']) ? $context['name'] : null);
        }
    }

En cuanto a los analizadores léxico y sintáctico, el compilador predeterminado (``Twig_Compiler``) se puede cambiar mediante una llamada al método ``setCompiler()``::

    $twig->setCompiler($compilador);

Todos los compiladores de *Twig* deben implementar a ``Twig_CompilerInterface``::

    interface Twig_CompilerInterface
    {
        /**
         * Compila un nodo.
         *
         * @param  Twig_Node $node El nodo a compilar
         *
         * @return Twig_Compiler La instancia actual del compilador
         */
        function compile(Twig_Node $node);

        /**
         * Obtiene el código PHP actual después de la compilación.
         *
         * @return string The PHP code
         */
        function getSource();
    }
