Extendiendo *Twig*
==================

*Twig* se puede extender en muchos aspectos; puedes añadir etiquetas adicionales, filtros, pruebas, operadores, variables globales y funciones. Incluso puedes extender el propio analizador con visitantes de nodo.

.. note::

    Este capítulo describe cómo extender *Twig* fácilmente. Si deseas reutilizar tus cambios en diferentes proyectos o si quieres compartirlos con los demás, entonces, debes crear una extensión tal como se describe en el siguiente capítulo.

Antes de extender *Twig*, debes entender las diferencias entre todos los diferentes puntos de extensión posibles y cuándo utilizarlos.

En primer lugar, recuerda que el lenguaje de *Twig* tiene dos construcciones principales:

* ``{{ }}``: Utilizada para imprimir el resultado de la evaluación de la expresión;

* ``{% %}``: Utilizada para ejecutar declaraciones.

Para entender por qué *Twig* expone tantos puntos de extensión, vamos a ver cómo implementar un generador *Lorem ipsum* (este necesita saber el número de palabras a generar).

Puedes utilizar una *etiqueta* ``Lipsum``:

.. code-block:: jinja

    {% lipsum 40 %}

Eso funciona, pero usar una etiqueta para ``lipsum`` no es una buena idea por al menos tres razones principales:

* ``lipsum`` no es una construcción del lenguaje;
* La etiqueta produce algo;
* La etiqueta no es flexible ya que no la puedes utilizar en una expresión:

  .. code-block:: jinja

      {{ 'algún texto' ~ {% lipsum 40 %} ~ 'algo más de texto' }}

De hecho, rara vez es necesario crear etiquetas; y es una muy buena noticia porque las etiquetas son el punto de extensión más complejo de *Twig*.

Ahora, vamos a utilizar un *filtro* ``lipsum``:

.. code-block:: jinja

    {{ 40|lipsum }}

Una vez más, funciona, pero se ve raro. Un filtro transforma el valor que se le pasa a alguna otra cosa, pero aquí utilizamos el valor para indicar el número de palabras a generar.

En seguida, vamos a utilizar una *función* ``lipsum``:

.. code-block:: jinja

    {{ lipsum(40) }}

Aquí vamos. Para este ejemplo concreto, la creación de una función es el punto de extensión a usar. Y la puedes usar en cualquier lugar en que se acepte una expresión:

.. code-block:: jinja

    {{ 'algún texto' ~ lipsum(40) ~ 'algo más de texto' }}

    {% set lipsum = lipsum(40) %}

Por último pero no menos importante, también puedes utilizar un objeto *global* con un método capaz de generar texto *Lorem Ipsum*:

.. code-block:: jinja

    {{ text.lipsum(40) }}

Como regla general, utiliza funciones para las características más utilizadas y objetos globales para todo lo demás.

Ten en cuenta lo siguiente cuando desees extender *Twig*:

=========== ================================= ===================== =============================
¿Qué?       ¿dificultad para implementación?  ¿Con qué frecuencia?  ¿Cuándo?
=========== ================================= ===================== =============================
*macro*     trivial                           frecuente             Generación de contenido
*global*    trivial                           frecuente             Objeto ayudante
*function*  trivial                           frecuente             Generación de contenido
*filter*    trivial                           frecuente             Transformación de valor
*tag*       complejo                          raro                  Constructor del lenguaje *DSL*
*test*      trivial                           raro                  Decisión booleana
*operator*  trivial                           raro                  Transformación de valores
=========== ================================= ===================== =============================

Globales
--------

Una variable global es como cualquier otra variable de plantilla, excepto que está disponible en todas las plantillas y macros::

    $twig = new Twig_Environment($loader);
    $twig->addGlobal('text', new Text());

Entonces puedes utilizar la variable ``text`` en cualquier parte de una plantilla:

.. code-block:: jinja

    {{ text.lipsum(40) }}

Filtros
-------

Un filtro es una función *PHP* regular o un método de objeto que toma el lado izquierdo del filtro (antes del tubo ``|``) como primer argumento y los argumentos adicionales pasados ​​al filtro (entre paréntesis ``()``) como argumentos adicionales.

La definición de un filtro es tan fácil como asociar el nombre del filtro con un ejecutable de *PHP*. Por ejemplo, digamos que tienes el siguiente código en una plantilla:

.. code-block:: jinja

    {{ 'TWIG'|lower }}

Al compilar esta plantilla para *PHP*, *Twig* busca el ejecutable *PHP* asociado con el filtro ``lower``. El filtro ``lower`` es un filtro integrado en *Twig*, y simplemente se asigna a la función *PHP* ``strtolower()``. Después de la compilación, el código generado por *PHP* es más o menos equivalente a:

.. code-block:: html+php

    <?php echo strtolower('TWIG') ?>

Como puedes ver, la cadena ``'TWIG'`` se pasa como primer argumento a la función de *PHP*.

Un filtro también puede tomar argumentos adicionales como en el siguiente ejemplo:

.. code-block:: jinja

    {{ now|date('d/m/Y') }}

En este caso, los argumentos adicionales son pasados​ a la función después del argumento principal, y el código compilado es equivalente a:

.. code-block:: html+php

    <?php echo twig_date_format_filter($now, 'd/m/Y') ?>

Vamos a ver cómo crear un nuevo filtro.

En esta sección, vamos a crear un filtro ``rot13``, el cual debe devolver la transformación `rot13`_ de una cadena. Aquí está un ejemplo de su uso y los resultados esperados:

.. code-block:: jinja

    {{ "Twig"|rot13 }}

    {# debería mostrar Gjvt #}

Agregar un filtro es tan sencillo como llamar al método ``addFilter()`` en la instancia de ``Twig_Environment``::

    $twig = new Twig_Environment($loader);
    $twig->addFilter('rot13', new Twig_Filter_Function('str_rot13'));

El segundo argumento de ``addFilter()`` es una instancia de ``Twig_Filter``.
Aquí, utilizamos ``Twig_Filter_Function`` puesto que el filtro es una función *PHP*. El primer argumento pasado al constructor ``Twig_Filter_Function`` es el nombre de la función *PHP* a llamar, aquí ``str_rot13``, una función nativa de *PHP*.

Digamos que ahora deseas poder añadir un prefijo antes de la cadena convertida:

.. code-block:: jinja

    {{ "Twig"|rot13('prefijo_') }}

    {# debe mostrar prefijo_Gjvt #}

Como la función ``str_rot13()`` de *PHP* no es compatible con este requisito, vamos a crear una nueva función *PHP*::

    function project_compute_rot13($string, $prefix = '')
    {
        return $prefix.str_rot13($string);
    }

Como puedes ver, el argumento ``prefix`` del filtro se pasa como un argumento adicional a la función ``project_compute_rot13()``.

La adición de este filtro es tan fácil como antes::

    $twig->addFilter( 'rot13',
                      new Twig_Filter_Function('project_compute_rot13'
                    ));

Para una mejor encapsulación, también puedes definir un filtro como un método estático de una clase. También puedes utilizar la clase ``Twig_Filter_Function`` para registrar métodos estáticos, tal como filtros::

    $twig->addFilter( 'rot13',
                      new Twig_Filter_Function('SomeClass::rot13Filter'
                    ));

.. tip::

    En una extensión, también puedes definir un filtro como un método estático de la clase extendida.

Entorno consciente de filtros
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

La clase ``Twig_Filter`` toma opciones como su último argumento. Por ejemplo, si deseas acceder a la instancia del entorno actual en tu filtro, establece la opción ``needs_environment`` a ``true``::

    $filter = new Twig_Filter_Function(  'str_rot13',
                                         array(  'needs_environment' => true
                                      ));

*Twig* entonces pasará el entorno actual como primer argumento al invocar el filtro::

    function twig_compute_rot13(Twig_Environment $env, $string)
    {
        // obtiene el juego de caracteres actual, por ejemplo
        $charset = $env->getCharset();

        return str_rot13($string);
    }

Escapando automáticamente
~~~~~~~~~~~~~~~~~~~~~~~~~

Si está habilitado el escape automático, puedes escapar la salida del filtro antes de imprimir. Si tu filtro actúa como un escapista (o explícitamente produce código *html* o *javascript*), desearás que se imprima la salida cruda. En tal caso, establece la opción ``is_safe``::

    $filter = new Twig_Filter_Function(  'nl2br',
                                         array('is_safe' => array('html')
                                      ));

Algunos filtros posiblemente tengan que trabajar en valores ya escapados o seguros. En tal caso, establece la opción ``pre_escape``::

    $filter = new Twig_Filter_Function(  'somefilter',
                                         array( 'pre_escape' => 'html',
                                                'is_safe' => array('html')
                                      ));

Filtros dinámicos
~~~~~~~~~~~~~~~~~

.. versionadded:: 1.5
    El apoyo a los filtros dinámicos se añadió en *Twig* 1.5.

Un nombre de filtro que contiene el carácter especial ``*`` es un filtro dinámico debido a que el ``*`` puede ser cualquier cadena::

    $twig->addFilter('*_path', new Twig_Filter_Function('twig_path'));

    function twig_path($name, $arguments)
    {
        // ...
    }

Los siguientes filtros deben corresponder con el filtro dinámico definido anteriormente:

* ``product_path``
* ``category_path``

Un filtro dinámico puede definir más de una parte dinámica::

    $twig->addFilter('*_path_*', new Twig_Filter_Function('twig_path'));

    function twig_path($name, $suffix, $arguments)
    {
        // ...
    }

El filtro debe recibir todos los valores de las partes dinámicas antes de los argumentos normales de los filtros. Por ejemplo, una llamada a ``'foo'|a_path_b()`` resultará en la siguiente llamada *PHP*: ``twig_path('a', 'b', 'foo')``.

Funciones
---------

Una función es una función *PHP* regular o un método de objeto que puedes llamar desde las plantillas.

.. code-block:: jinja

    {{ constant("DATE_W3C") }}

Al compilar esta plantilla para *PHP*, *Twig* busca el *PHP* ejecutable asociado con la función ``constant``. La función ``constant`` está integrada en las funciones *Twig*, asignada simplemente a la función ``constant()`` de *PHP*. Después de la compilación, el código generado por *PHP* es más o menos equivalente a:

.. code-block:: html+php

    <?php echo constant('DATE_W3C') ?>

Agregar una función es similar a agregar un filtro. Esto se puede hacer llamando al método ``addFunction()`` en la instancia de ``Twig_Environment``::

    $twig = new Twig_Environment($loader);
    $twig->addFunction('functionName', new Twig_Function_Function('someFunction'));

También puedes exponer los métodos de extensión como funciones en tus plantillas::

    // $this es un objeto que implementa a Twig_ExtensionInterface.
    $twig = new Twig_Environment($loader);
    $twig->addFunction('otherFunction', new Twig_Function_Method(  $this,
                                                                   'someMethod'
                                                                ));

Las funciones también son compatibles con los parámetros ``needs_environment`` e ``is_safe``.

Funciones dinámicas
~~~~~~~~~~~~~~~~~~~

.. versionadded:: 1.5
    La compatibilidad con las funciones dinámicas se añadió en *Twig* 1.5.

Un nombre de función que contiene el carácter especial ``*`` es una función dinámica debido a que el ``*`` puede ser cualquier cadena::

    $twig->addFunction('*_path', new Twig_Function_Function('twig_path'));

    function twig_path($name, $arguments)
    {
        // ...
    }

Las siguientes funciones deben corresponder con la función dinámica definida anteriormente:

* ``product_path``
* ``category_path``

Una función dinámica puede definir más de una parte dinámica::

    $twig->addFilter('*_path_*', new Twig_Filter_Function('twig_path'));

    function twig_path($name, $suffix, $arguments)
    {
        // ...
    }

La función debe recibir todos los valores de las partes dinámicas antes de los argumentos normales de las funciones. Por ejemplo, una llamada a ``a_path_b('foo')`` resultará en la siguiente llamada *PHP*: ``twig_path('a', 'b', 'foo')``.

Etiquetas
---------

Una de las características más interesantes de un motor de plantillas como *Twig* es la posibilidad de definir nuevas construcciones del lenguaje. Esta también es la característica más compleja que necesitas comprender de cómo trabaja *Twig* internamente.

Vamos a crear una simple etiqueta ``set`` que te permita definir variables simples dentro de una plantilla. Puedes utilizar la etiqueta de la siguiente manera:

.. code-block:: jinja

    {% set name = "value" %}

    {{ name }}

    {# debe producir value #}

.. note::

    La etiqueta ``set`` es parte de la extensión ``core`` y como tal siempre está disponible. La versión integrada es un poco más potente y de manera predeterminada es compatible con múltiples asignaciones (consulta el capítulo :doc:`Twig para diseñadores de plantillas <templates>` para más información).

para definir una nueva etiqueta son necesarios tres pasos:

* Definir una clase para analizar segmentos (responsable de analizar el código de la plantilla);

* Definir una clase Nodo (responsable de convertir el código analizado a *PHP*);

* Registrar la etiqueta.

Registrando una nueva etiqueta
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Agregar una etiqueta es tan simple como una llamada al método ``addTokenParser`` en la instancia de ``Twig_Environment``::

    $twig = new Twig_Environment($loader);
    $twig->addTokenParser(new Project_Set_TokenParser());

Definiendo un analizador de fragmentos
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Ahora, vamos a ver el código real de esta clase::

    class Project_Set_TokenParser extends Twig_TokenParser
    {
        public function parse(Twig_Token $token)
        {
            $lineno = $token->getLine();
            $name = $this->parser
                          ->getStream()
                          ->expect(Twig_Token::NAME_TYPE)
                          ->getValue();
            $this->parser->getExpressionParser()
                         ->expect(Twig_Token::OPERATOR_TYPE, '=');
            $value = $this->parser
                          ->getExpressionParser()
                          ->parseExpression();

            $this->parser->getStream()
                         ->expect(Twig_Token::BLOCK_END_TYPE);

            return new Project_Set_Node(  $name,
                                          $value,
                                          $lineno,
                                          $this->getTag()
                                       );
        }

        public function getTag()
        {
            return 'set';
        }
    }

El método ``getTag()`` debe devolver la etiqueta que queremos analizar, aquí ``set``.

El método ``parse()`` se invoca cada vez que el analizador encuentra una etiqueta ``set``. Este debe devolver una instancia de ``Twig_Node`` que representa el nodo (la llamada para la creación del ``Project_Set_Node`` se explica en la siguiente sección).

El proceso de análisis se simplifica gracias a un montón de métodos que se pueden llamar desde el fragmento del flujo (``$this->parser->getStream()``):

* ``getCurrent()``: Obtiene el segmento actual del flujo.

* ``next()``: Mueve al siguiente segmento en la secuencia, *pero devuelve el antiguo*.

* ``test($type)``, ``test($value)`` o ``test($type, $value)``: Determina si el segmento actual es de un tipo o valor particular (o ambos). El valor puede ser una matriz de varios posibles valores.

* ``expect($type[, $value[, $message]])``: Si el segmento actual no es del tipo/valor dado lanza un error de sintaxis. De lo contrario, si el tipo y valor son correctos, devuelve el segmento y mueve el flujo al siguiente segmento.

* ``look()``: Busca el siguiente segmento sin consumirlo.

Las expresiones de análisis se llevan a cabo llamando a ``parseExpression()`` como lo hicimos para la etiqueta ``set``.

.. tip::

    Leer las clases ``TokenParser`` existentes es la mejor manera de aprender todos los detalles esenciales del proceso de análisis.

Definiendo un nodo
~~~~~~~~~~~~~~~~~~

La clase ``Project_Set_Node`` en sí misma es bastante simple::

    class Project_Set_Node extends Twig_Node
    {
        public function __construct(  $name,
                                      Twig_Node_Expression $value,
                                      $lineno,
                                      $tag = null
                                   )
        {
            parent::__construct(  array( 'value' => $value ),
                                  array( 'name'  => $name  ),
                                  $lineno,
                                  $tag
                               );
        }

        public function compile(Twig_Compiler $compiler)
        {
            $compiler
                ->addDebugInfo($this)
                ->write('$context[\''.$this->getAttribute('name').'\'] = ')
                ->subcompile($this->getNode('value'))
                ->raw(";\n")
            ;
        }
    }

El compilador implementa una interfaz fluida y proporciona métodos que ayudan a los desarrolladores a generar código *PHP* hermoso y fácil de leer:

* ``subcompile()``: Compila un nodo.

* ``raw()``: Escribe la cadena dada tal cual.

* ``write()``: Escribe la cadena dada añadiendo sangría al principio de cada línea.

* ``string()``: Escribe una cadena entre comillas.

* ``repr()``: Escribe una representación *PHP* de un valor dado (consulta ``Twig_Node_For`` para un ejemplo real).

* ``addDebugInfo()``: Agrega como comentario la línea del archivo de plantilla original relacionado con el nodo actual.

* ``indent()``: Aplica sangrías el código generado (consulta ``Twig_Node_Block`` para un ejemplo real).

* ``outdent()``: Quita la sangría el código generado (consulta ``Twig_Node_Block`` para un ejemplo real).

.. _`rot13`: http://www.php.net/manual/en/function.str-rot13.php
