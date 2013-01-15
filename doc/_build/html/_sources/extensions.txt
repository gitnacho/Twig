Creando una extensión *Twig*
============================

La principal motivación para escribir una extensión es mover el código usado frecuentemente a una clase reutilizable como agregar apoyo para la internacionalización. Una extensión puede definir etiquetas, filtros, pruebas, operadores, variables globales, funciones y visitantes de nodo.

La creación de una extensión también hace una mejor separación del código que se ejecuta en tiempo de compilación y el código necesario en tiempo de ejecución. Por lo tanto, hace que tu código sea más rápido.

La mayoría de las veces, es útil crear una extensión para tu proyecto, para acoger todas las etiquetas y filtros específicos que deseas agregar a *Twig*.

.. note::

    Antes de escribir tus propias extensiones, echa un vistazo al repositorio de extensiones oficial de *Twig*: http://github.com/fabpot/Twig-extensions.

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
--------

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
---------

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
-------

Para agregar un filtro a una extensión, es necesario sustituir el método ``getFilters()``. Este método debe devolver una matriz de filtros para añadir al entorno *Twig*::

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

Como puedes ver en el código anterior, el método ``getFilters()`` devuelve una matriz donde las claves son el nombre de los filtros (``rot13``) y los valores de la definición del filtro (``new Twig_Filter_Function('str_rot13')``).

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
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Si algunos filtros predeterminados del núcleo no se ajustan a tus necesidades, fácilmente puedes sustituirlos creando tu propia extensión del núcleo. Por supuesto, no es necesario copiar y pegar el código del núcleo en toda tu extensión de *Twig*. En lugar de eso la puedes extender y sustituir los filtros que deseas reemplazando el método ``getFilters()``::

    class MyCoreExtension extends Twig_Extension_Core
    {
        public function getFilters()
        {
            return array_merge(parent::getFilters(), array(
                'date' => new Twig_Filter_Method($this, 'dateFilter'),
                // ...
            ));
        }

        public function dateFilter($timestamp, $format = 'F j, Y H:i')
        {
            return '...'.twig_date_format_filter($timestamp, $format);
        }

        // ...
    }

Aquí, reemplazamos el filtro ``date`` con uno personalizado. Usar esta nueva extensión del núcleo es tan simple como registrar la extensión ``MyCoreExtension`` llamando al método ``addExtension()`` en la instancia del entorno::

    $twig = new Twig_Environment($loader);
    $twig->addExtension(new MyCoreExtension());

Pero ya puedo escuchar a algunas personas preguntando cómo pueden hacer que la extensión del núcleo se cargue por omisión. Eso es cierto, pero el truco es que ambas extensiones comparten el mismo identificador único (``core`` - definido en el método ``getName()``). Al registrar una extensión con el mismo nombre que una ya existente, realmente sustituyes la predeterminada, incluso si ya está registrada::

    $twig->addExtension(new Twig_Extension_Core());
    $twig->addExtension(new MyCoreExtension());

Etiquetas
---------

Puedes agregar una etiqueta en una extensión reemplazando el método ``getTokenParsers()``. Este método debe devolver una matriz de etiquetas para añadir al entorno *Twig*::

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
----------

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
-------

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

.. _`spl_autoload_register()`: http://www.php.net/spl_autoload_register
