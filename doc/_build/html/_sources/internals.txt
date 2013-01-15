*Twig* por dentro
=================

*Twig* es muy extensible y lo puedes mejorar fácilmente. Ten en cuenta que probablemente deberías tratar de crear una extensión antes de sumergirte en el núcleo, puesto que la mayoría de las características y mejoras se pueden hacer con extensiones. Este capítulo también es útil para personas que quieren entender cómo funciona *Twig* bajo el capó.

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

El analizador léxico acorta el código fuente de una plantilla hasta una secuencia de símbolos (cada símbolo es una instancia de ``Twig_Token``, y la secuencia es una instancia de ``Twig_TokenStream``). El analizador léxico por omisión reconoce 13 diferentes tipos de símbolos:

* ``Twig_Token::BLOCK_START_TYPE``, ``Twig_Token::BLOCK_END_TYPE``: Delimitadores para bloques (``{% %}``)
* ``Twig_Token::VAR_START_TYPE``, ``Twig_Token::VAR_END_TYPE``: Delimitadores para variables (``{{ }}``)
* ``Twig_Token::TEXT_TYPE``: Un texto fuera de una expresión;
* ``Twig_Token::NAME_TYPE``: Un nombre en una expresión;
* ``Twig_Token::NUMBER_TYPE``: Un número en una expresión;
* ``Twig_Token::STRING_TYPE``: Una cadena en una expresión;
* ``Twig_Token::OPERATOR_TYPE``: Un operador;
* ``Twig_Token::PUNCTUATION_TYPE``: Un signo de puntuacion;
* ``Twig_Token::INTERPOLATION_START_TYPE``, ``Twig_Token::INTERPOLATION_END_TYPE`` (a partir de la ramita 1,5): Los delimitadores para la interpolación de cadenas;
* ``Twig_Token::EOF_TYPE``: Extremos de la plantilla.

Puedes convertir manualmente un código fuente en una secuencia de segmentos llamando al método ``tokenize()`` de un entorno::

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

.. note::

    Puedes cambiar el analizador léxico predeterminado usado por *Twig* (``Twig_Lexer``) llamando al método ``setLexer()``::

        $twig->setLexer($lexer);

El analizador sintáctico
------------------------

El analizador convierte la secuencia de símbolos en un ``ASA`` (árbol de sintaxis abstracta), o un árbol de nodos (una instancia de ``Twig_Node_Module``). La extensión del núcleo define los nodos básicos como: ``for``, ``if``, ... y la expresión nodos.

Puedes convertir manualmente una secuencia de símbolos en un nodo del árbol llamando al método ``parse()`` de un entorno::

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

.. note::

    También puedes cambiar el analizador predeterminado (``Twig_TokenParser``) llamando al método ``setParser()``::

        $twig->setParser($analizador);

El compilador
-------------

El último paso lo lleva a cabo el compilador. Este necesita un árbol de nodos como entrada y genera código *PHP* que puedes emplear para ejecutar las plantillas en tiempo de ejecución.

Puedes llamar al compilador manualmente con el método ``compile()`` de un entorno::

    $php = $twig->compile($nodes);

El método ``compile()`` devuelve el código fuente *PHP* que representa al nodo.

La plantilla generada por un patrón ``Hello {{ name }}`` es la siguiente (la salida real puede diferir dependiendo de la versión de *Twig* que estés usando)::

    /* Hello {{ name }} */
    class __TwigTemplate_1121b6f109fe93ebe8c6e22e3712bceb extends Twig_Template
    {
        protected function doDisplay(array $context, array $blocks = array())
        {
            // line 1
            echo "Hello ";
            echo twig_escape_filter($this->env, $this->getContext($context, "name"), "ndex", null, true);
        }

        // algún código adicional
    }

.. note::

    En cuanto a los analizadores léxico y sintáctico, el compilador predeterminado (``Twig_Compiler``) se puede cambiar mediante una llamada al método ``setCompiler()``::

        $twig->setCompiler($compilador);
