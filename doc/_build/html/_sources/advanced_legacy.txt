Extendiendo *Twig*
==================

.. caution::

    Esta sección describe cómo extender *Twig* para las versiones **anteriores a la 1.12**. Si estás usando una versión más reciente, en su lugar lee el capítulo :doc:`más reciente <advanced>`.

*Twig* se puede extender en muchos aspectos; puedes añadir etiquetas adicionales, filtros, pruebas, operadores, variables globales y funciones. Incluso puedes extender el propio analizador con visitantes de nodo.

.. note::

    La primer sección de este capítulo describe la forma de extender *Twig* fácilmente. Si deseas volver a utilizar tus cambios en diferentes proyectos o si deseas compartirlos con los demás, entonces, debes crear una extensión tal como se describe en la siguiente sección.

.. caution::

    Al extender *Twig* llamando a los métodos del entorno de la instancia de *Twig*, *Twig* no podrá volver a compilar tus plantillas al actualizar el código *PHP*. Para ver los cambios en tiempo real, o bien deshabilita la caché de plantillas o empaqueta el código en una extensión (ve la siguiente sección de este capítulo).

Antes de extender *Twig*, debes entender las diferencias entre todos los diferentes puntos de extensión posibles y cuándo utilizarlos.

En primer lugar, recuerda que el lenguaje de *Twig* tiene dos construcciones principales:

* ``{{ }}``: Utilizada para imprimir el resultado de la evaluación de la expresión;

* ``{% %}``: Utilizada para ejecutar instrucciones.

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

Una vez más, funciona, pero se ve raro. Un filtro transforma el valor pasado a algo más pero aquí utilizamos el valor para indicar el número de palabras a generar (así que, ``40`` es un argumento del filtro, no el valor que se va a transformar).

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

Ten en cuenta lo siguiente cuando quieras extender *Twig*:

=========== ================================= ===================== =============================
¿Qué?       ¿dificultad para implementarlo?   ¿Con qué frecuencia?   ¿Cuándo?
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

Algunos filtros posiblemente tengan que trabajar en entradas que ya se escaparon o son seguras, por ejemplo, al agregar etiquetas *HTML* (seguras) inicialmente inseguras para salida. En tal caso, establece la opción ``pre_escape`` para escapar los datos entrantes antes de pasarlos por tu filtro::

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

* ``test($type)``, ``test($value)`` o ``test($type, $value)``: Determina si el segmento actual es de un tipo o valor particular (o ambos). El valor puede ser un arreglo de varios posibles valores.

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

* ``indent()``: Aplica sangrías al código generado (consulta ``Twig_Node_Block`` para un ejemplo real).

* ``outdent()``: Quita la sangría al código generado (consulta ``Twig_Node_Block`` para un ejemplo real).

.. _creating_extensions:

Creando una extensión
---------------------

La principal motivación para escribir una extensión es mover el código usado frecuentemente a una clase reutilizable como agregar apoyo para la internacionalización. Una extensión puede definir etiquetas, filtros, pruebas, operadores, variables globales, funciones y visitantes de nodo.

La creación de una extensión también hace una mejor separación del código que se ejecuta en tiempo de compilación y el código necesario en tiempo de ejecución. Por lo tanto, hace que tu código sea más rápido.

La mayoría de las veces, es útil crear una extensión para tu proyecto, para acoger todas las etiquetas y filtros específicos que deseas agregar a *Twig*.

.. tip::

    Al empaquetar tu código en una extensión, *Twig* es lo suficientemente inteligente como para volver a compilar tus plantillas cada vez que hagas algún cambio en ella (cuando ``auto_reload`` está habilitado).

.. note::

    Antes de escribir tus propias extensiones, échale un vistazo al repositorio de extensiones oficial de *Twig*: http://github.com/fabpot/Twig-extensions.

Una extensión es una clase que implementa la siguiente interfaz::

    interface Twig_ExtensionInterface
    {
        /**
         * Inicia el entorno en tiempo de ejecución.
         *
         * Aquí es donde puedes cargar algún archivo que contenga funciones
         * de filtro, por ejemplo.
         *
         * @param Twig_Environment $environment La instancia actual de
         *                                      Twig_Environment
         */
        function initRuntime(Twig_Environment $environment);

        /**
         * Devuelve instancias del analizador de segmentos para añadirlos a
         * la lista existente.
         *
         * @return array Un arreglo de instancias Twig_TokenParserInterface
         *               o Twig_TokenParserBrokerInterface
         */
        function getTokenParsers();

        /**
         * Devuelve instancias del visitante de nodos para añadirlas a la
         * lista existente.
         *
         * @return array Un arreglo de instancias de
         *                Twig_NodeVisitorInterface
         */
        function getNodeVisitors();

        /**
         * Devuelve una lista de filtros para añadirla a la lista
         * existente.
         *
         * @return array Un arreglo de filtros
         */
        function getFilters();

        /**
         * Devuelve una lista de pruebas para añadirla a la lista
         *  existente.
         *
         * @return array Un arreglo de pruebas
         */
        function getTests();

        /**
         * Devuelve una lista de funciones para añadirla a la lista
         * existente.
         *
         * @return array Un arreglo de funciones
         */
        function getFunctions();

        /**
         * Devuelve una lista de operadores para añadirla a la lista
         * existente.
         *
         * @return array Un arreglo de operadores
         */
        function getOperators();

        /**
         * Devuelve una lista de variables globales para añadirla a la
         * lista existente.
         *
         * @return array Un arreglo de variables globales
         */
        function getGlobals();

        /**
         * Devuelve el nombre de la extensión.
         *
         * @return string El nombre de la extensión
         */
        function getName();
    }

Para mantener tu clase de extensión limpia y ordenada, puedes heredar de la clase ``Twig_Extension`` incorporada en lugar de implementar toda la interfaz. De esta forma, sólo tienes que implementar el método ``getName()`` como el que proporcionan las implementaciones vacías de ``Twig_Extension`` para todos los otros métodos.

El método ``getName()`` debe devolver un identificador único para tu extensión.

Ahora, con esta información en mente, vamos a crear la extensión más básica posible::

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getName()
        {
            return 'project';
        }
    }

.. note::

    Por supuesto, esta extensión no hace nada por ahora. Vamos a personalizarla en las siguientes secciones.

A *Twig* no le importa dónde guardas tu extensión en el sistema de archivos, puesto que todas las extensiones se deben registrar explícitamente para estar disponibles en tus plantillas.

Puedes registrar una extensión con el método ``addExtension()`` en tu objeto ``Environment`` principal::

    $twig = new Twig_Environment($loader);
    $twig->addExtension(new Project_Twig_Extension());

Por supuesto, tienes que cargar primero el archivo de la extensión, ya sea utilizando ``require_once()`` o con un cargador automático (consulta la sección `spl_autoload_register()`_).

.. tip::

    Las extensiones integradas son grandes ejemplos de cómo trabajan las extensiones.

Globales
~~~~~~~~

Puedes registrar las variables globales en una extensión vía el método ``getGlobals()``:

.. code-block:: php

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getGlobals()
        {
            return array(
                'text' => new Text(),
            );
        }

        // ...
    }

Funciones
~~~~~~~~~

Puedes registrar funciones en una extensión vía el método ``getFunctions()``:

.. code-block:: php

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getFunctions()
        {
            return array(
                'lipsum' => new Twig_Function_Function('generate_lipsum'),
            );
        }

        // ...
    }

Filtros
~~~~~~~

Para agregar un filtro a una extensión, es necesario sustituir el método ``getFilters()``. Este método debe devolver un arreglo de filtros para añadir al entorno *Twig*::

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getFilters()
        {
            return array(
                'rot13' => new Twig_Filter_Function('str_rot13'),
            );
        }

        // ...
    }

Como puedes ver en el código anterior, el método ``getFilters()`` devuelve un arreglo donde las claves son el nombre de los filtros (``rot13``) y los valores de la definición del filtro (``new Twig_Filter_Function('str_rot13')``).

Como vimos en el capítulo anterior, también puedes definir filtros como métodos estáticos en la clase de la extensión::

    $twig->addFilter(  'rot13',
                       new Twig_Filter_Function(
                               'Project_Twig_Extension::rot13Filter'
                                                )
                    );

También puedes utilizar ``Twig_Filter_Method`` en lugar de ``Twig_Filter_Function`` cuando definas un filtro que usa un método::

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getFilters()
        {
            return array(
                'rot13' => new Twig_Filter_Method($this, 'rot13Filter'),
            );
        }

        public function rot13Filter($string)
        {
            return str_rot13($string);
        }

        // ...
    }

El primer argumento del constructor de ``Twig_Filter_Method`` siempre es ``$this``, el objeto extensión actual. El segundo es el nombre del método a llamar.

Usar métodos de filtro es una gran manera de empaquetar el filtro sin contaminar el espacio de nombres global. Esto también le da más flexibilidad al desarrollador a costa de una pequeña sobrecarga.

Sustituyendo los filtros predeterminados
........................................

Si algunos filtros predeterminados del núcleo no se ajustan a tus necesidades, los puedes sustituir fácilmente creando tu propia extensión. Sólo utiliza los mismos nombres de los que quieras redefinir::

    class MyCoreExtension extends Twig_Extension
    {
        public function getFilters()
        {
            return array(
                'date' => new Twig_Filter_Method($this, 'dateFilter'),
                // ...
            );
        }

        public function dateFilter($timestamp, $format = 'F j, Y H:i')
        {
            return '...'.twig_date_format_filter($timestamp, $format);
        }

        public function getName()
        {
            return 'project';
        }
    }

Aquí, reemplazamos el filtro ``date`` con uno personalizado. Usar esta nueva extensión es tan simple como registrar la extensión ``MyCoreExtension`` llamando al método ``addExtension()`` en la instancia del entorno::

    $twig = new Twig_Environment($loader);
    $twig->addExtension(new MyCoreExtension());

Etiquetas
~~~~~~~~~

Puedes agregar una etiqueta en una extensión reemplazando el método ``getTokenParsers()``. Este método debe devolver un arreglo de etiquetas para añadir al entorno *Twig*::

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getTokenParsers()
        {
            return array(new Project_Set_TokenParser());
        }

        // ...
    }

En el código anterior, hemos añadido una sola etiqueta nueva, definida por la clase ``Project_Set_TokenParser``. La clase ``Project_Set_TokenParser`` es responsable de analizar la etiqueta y compilarla a *PHP*.

Operadores
~~~~~~~~~~

El método ``getOperators()`` te permite añadir nuevos operadores. Aquí tienes cómo añadir los operadores
``!``, ``||`` y ``&&``::

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getOperators()
        {
            return array(
                array(
                    '!' => array(  'precedence' => 50,
                                   'class'
                                   => 'Twig_Node_Expression_Unary_Not'
                           ),
                ),
                array(
                    '||' => array(  'precedence' => 10,
                                    'class'
                                    => 'Twig_Node_Expression_Binary_Or',
                                    'associativity'
                                    => Twig_ExpressionParser::OPERATOR_LEFT
                            ),
                    '&&' => array(  'precedence' => 15,
                                    'class'
                                    => 'Twig_Node_Expression_Binary_And',
                                    'associativity'
                                    => Twig_ExpressionParser::OPERATOR_LEFT
                                 ),
                ),
            );
        }

        // ...
    }

Pruebas
~~~~~~~

El método ``getTests()`` te permite añadir funciones de prueba::

    class Project_Twig_Extension extends Twig_Extension
    {
        public function getTests()
        {
            return array(
                'even' => new Twig_Test_Function('twig_test_even'),
            );
        }

        // ...
    }

Probando una extensión
----------------------

.. versionadded:: 1.10
    El soporte necesario para las pruebas funcionales se añadió en *Twig* 1.10.

Pruebas funcionales
~~~~~~~~~~~~~~~~~~~

Puedes crear pruebas funcionales para extensiones simplemente creando la siguiente estructura de archivos en tu directorio de pruebas (:file:`test`)::

    Fixtures/
        filters/
            foo.test
            bar.test
        functions/
            foo.test
            bar.test
        tags/
            foo.test
            bar.test
    IntegrationTest.php

El archivo :file:`IntegrationTest.php` debe tener la siguiente apariencia::

    class Project_Tests_IntegrationTest extends Twig_Test_IntegrationTestCase
    {
        public function getExtensions()
        {
            return array(
                new Project_Twig_Extension1(),
                new Project_Twig_Extension2(),
            );
        }

        public function getFixturesDir()
        {
            return dirname(__FILE__).'/Fixtures/';
        }
    }

Los accesorios de ejemplo se pueden encontrar dentro del directorio del repositorio de *Twig* `tests/Twig/Fixtures`_.

Pruebas de nodo
~~~~~~~~~~~~~~~

Probar los visitantes de nodo puede ser complejo, así que extiende tus casos de prueba de ``Twig_Test_NodeTestCase``. Puedes encontrar ejemplos en el directorio del repositorio de *Twig* `tests/Twig/Node`_.

.. _`spl_autoload_register()`: http://www.php.net/spl_autoload_register
.. _`rot13`:                   http://php.net/manual/es/function.str-rot13.php
.. _`tests/Twig/Fixtures`:     https://github.com/fabpot/Twig/tree/master/test/Twig/Tests/Fixtures
.. _`tests/Twig/Node`:         https://github.com/fabpot/Twig/tree/master/test/Twig/Tests/Node
