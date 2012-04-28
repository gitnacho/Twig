*Twig* para desarrolladores
===========================

Este capítulo describe la *API* para *Twig* y no el lenguaje de plantillas. Será muy útil como referencia para aquellos que implementan la interfaz de plantillas para la aplicación y no para los que están creando plantillas *Twig*.

Fundamentos
-----------

*Twig* utiliza un objeto central llamado el **entorno** (de la clase ``Twig_Environment``). Las instancias de esta clase se utilizan para almacenar la configuración y extensiones, y se utilizan para cargar plantillas del sistema de archivos o en otros lugares.

La mayoría de las aplicaciones debe crear un objeto ``Twig_Environment`` al iniciar la aplicación y usarlo para cargar plantillas. En algunos casos, sin embargo, es útil disponer de múltiples entornos lado a lado, si estás usando distintas configuraciones.

La forma más sencilla de configurar *Twig* para cargar plantillas para tu aplicación se ve más o menos así::

    require_once '/ruta/a/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem('/ruta/a/templates');
    $twig = new Twig_Environment($loader, array(
        'cache' => '/ruta/a/compilation_cache',
    ));

Esto creará un entorno de plantillas con la configuración predeterminada y un cargador que busca las plantillas en el directorio ``/ruta/a/templates/``. Hay diferentes cargadores disponibles y también puedes escribir el tuyo si deseas cargar plantillas de una base de datos u otros recursos.

.. note::

    Ten en cuenta que el segundo argumento del entorno es una matriz de opciones.
    La opción ``cache`` es un directorio de caché de compilación, donde *Twig* memoriza las plantillas compiladas para evitar la fase de análisis de las subsiguientes peticiones. Esta es muy diferente de la caché que posiblemente desees agregar para evaluar plantillas. Para tal necesidad, puedes utilizar cualquier biblioteca de caché *PHP* disponible.

Para cargar una plantilla desde este entorno sólo tienes que llamar al método ``LoadTemplate()`` el cual devuelve una instancia de ``Twig_Template``::

    $template = $twig->loadTemplate('index.html');

Para reproducir la plantilla con algunas variables, llama al método ``render()``::

    echo $template->render(array('the' => 'variables', 'go' => 'here'));

.. note::

    El método ``display()`` es un atajo para reproducir la plantilla directamente.

También puedes exponer los métodos de extensión como funciones en tus plantillas::

    echo $twig->render(  'index.html',
                         array( 'the' => 'variables',
                                'go' => 'here'
                      ));

.. _environment_options:

Opciones del entorno
--------------------

Al crear una nueva instancia de ``Twig_Environment``, puedes pasar una matriz de opciones como segundo argumento del constructor::

    $twig = new Twig_Environment($loader, array('debug' => true));

Las siguientes opciones están disponibles:

* ``debug``: Cuando se establece en ``true``, las plantillas generadas tienen un método ``__toString()`` que puedes utilizar para mostrar los nodos generados (el predeterminado es ``false``).

* ``charset``: El juego de caracteres usado por las plantillas (por omisión es ``utf-8``).

* ``base_template_class``: La clase de plantilla base utilizada para generar plantillas (por omisión ``Twig_Template``).

* ``cache``: Una ruta absoluta donde almacenar las plantillas compiladas, o ``false`` para desactivar el almacenamiento en caché (el cual es el valor predeterminado).

* ``auto_reload``: Cuando desarrollas con *Twig*, es útil volver a compilar la plantilla cada vez que el código fuente cambia. Si no proporcionas un valor para la opción ``auto_reload``, se determinará automáticamente en función del valor ``debug``.

* ``strict_variables``: Si se establece en ``false``, *Twig* ignorará silenciosamente las variables no válidas (variables y/o atributos/métodos que no existen) y los reemplazará con un valor ``null``. Cuando se establece en ``true``, *Twig* produce una excepción en su lugar (el predeterminado es ``false``).

* ``autoescape``: Si se establece en ``true``, el escape automático será habilitado de manera predeterminada para todas las plantillas (por omisión a ``true``). A partir de *Twig 1.8*, puedes implantar la estrategia de escape para usar (``html``, ``js``, ``false`` para desactivarla, o una retrollamada PHP que tome el :file:`"nombre de archivo"` de la plantilla y devuelva la estrategia de escape a utilizar).

* ``optimizations``: Una marca que indica cuales optimizaciones aplicar (por omisión a ``-1`` -- todas las optimizaciones están habilitadas; para desactivarla ponla a ``0``).

Cargadores
----------

Los cargadores son responsables de cargar las plantillas desde un recurso como el sistema de archivos.

Caché de compilación
~~~~~~~~~~~~~~~~~~~~

Todos los cargadores de plantillas en cache pueden compilar plantillas en el sistema de archivos para su futura reutilización. Esto acelera mucho cómo se compilan las plantillas *Twig* una sola vez; y el aumento del rendimiento es aún mayor si utilizas un acelerador *PHP* como *APC*.
Consulta las opciones anteriores ``cache`` y ``auto_reload`` de ``Twig_Environment`` para más información.

Cargadores integrados
~~~~~~~~~~~~~~~~~~~~~

Aquí está una lista de los cargadores incorporados de que dispone *Twig*:

* ``Twig_Loader_Filesystem``: Carga las plantillas desde el sistema de archivos. Este cargador puede encontrar plantillas en los directorios del sistema de archivos y es la manera preferida de cargarlas::

        $loader = new Twig_Loader_Filesystem($templateDir);

  También puedes buscar plantillas en una matriz de directorios::

        $loader = new Twig_Loader_Filesystem(
                                               array(  $templateDir1,
                                                       $templateDir2
                                            ));

  Con esta configuración, *Twig* buscará primero las plantillas de ``$templateDir1`` y si no existen, regresará a buscar en ``$templateDir2``.

* ``Twig_Loader_String``: Carga plantillas desde una cadena. Es un cargador silencioso que va cargando el código fuente directamente a medida que se lo vas pasando::

        $loader = new Twig_Loader_String();

* ``Twig_Loader_Array``: Carga una plantilla desde una matriz *PHP*. Se le pasa una matriz de cadenas vinculadas a los nombres de plantilla. Este cargador es útil para pruebas unitarias::

        $loader = new Twig_Loader_Array($templates);

.. tip::

    Cuando utilices los cargadores de ``matriz`` o ``cadena`` con un mecanismo de caché, debes saber que se genera una nueva clave de caché cada vez que "cambia" el contenido de una plantilla (la clave de caché es el código fuente de la plantilla). Si no deseas ver que tu caché crezca fuera de control, es necesario tener cuidado de limpiar el archivo de caché antiguo en sí mismo.

Creando tu propio cargador
~~~~~~~~~~~~~~~~~~~~~~~~~~

Todos los cargadores implementan la interfaz ``Twig_LoaderInterface``::

    interface Twig_LoaderInterface
    {
        /**
         * Obtiene el código fuente de una plantilla, del nombre dado.
         *
         * @param  string $name cadena del nombre de la plantilla a cargar
         *
         * @return string The template source code
         */
        function getSource($name);

        /**
         * Obtiene la clave de la caché para usarla en un nombre de plantilla dado.
         *
         * @param  string $name cadena del nombre de la plantilla a cargar
         *
         * @return string La clave de caché
         */
        function getCacheKey($name);

        /**
         * Devuelve true si la plantilla aún está fresca.
         *
         * @param string    $name El nombre de la plantilla
         * @param timestamp $time Hora de la última modificación de la plantilla
         *                        en caché
         */
        function isFresh($name, $time);
    }

A modo de ejemplo, esto es lo que dice el ``Twig_Loader_String`` incorporado::

    class Twig_Loader_String implements Twig_LoaderInterface
    {
        public function getSource($name)
        {
          return $name;
        }

        public function getCacheKey($name)
        {
          return $name;
        }

        public function isFresh($name, $time)
        {
          return false;
        }
    }

El método ``isFresh()`` debe devolver ``true`` si la plantilla actual en caché aún es fresca, dado el tiempo de la última modificación, o ``false`` de lo contrario.

Usando extensiones
------------------

Las extensiones *Twig* son paquetes que añaden nuevas características a *Twig*. Usar una extensión es tan simple como usar el método ``addExtension()``::

    $twig->addExtension(new Twig_Extension_Sandbox());

*Twig* viene con las siguientes extensiones:

* *Twig_Extension_Core*: Define todas las características básicas de *Twig*.

* *Twig_Extension_Escaper*: Agrega escape automático y la posibilidad de escapar/no escapar bloques de código.

* *Twig_Extension_Sandbox*: Agrega un modo de recinto de seguridad para el entorno predeterminado de *Twig*, en el cual es seguro evaluar código que no es de confianza.

* *Twig_Extension_Optimizer*: Optimiza el nodo del árbol antes de la compilación.

El núcleo, las extensiones del mecanismo de escape y optimización no es necesario añadirlas al entorno *Twig*, debido a que se registran de forma predeterminada. Puedes desactivar una extensión registrada::

    $twig->removeExtension('escaper');

Extensiones incorporadas
------------------------

Esta sección describe las características agregadas por las extensiones incorporadas.

.. tip::

    Lee el capítulo sobre la ampliación de *Twig* para que veas cómo crear tus propias extensiones.

Extensión ``core``
~~~~~~~~~~~~~~~~~~

La extensión ``core`` define todas las características principales de *Twig*:

* Etiquetas;

  * ``for``
  * ``if``
  * ``extends``
  * ``include``
  * ``block``
  * ``filter``
  * ``macro``
  * ``import``
  * ``from``
  * ``set``
  * ``spaceless``

* Filtros:

  * ``date``
  * ``format``
  * ``replace``
  * ``url_encode``
  * ``json_encode``
  * ``title``
  * ``capitalize``
  * ``upper``
  * ``lower``
  * ``striptags``
  * ``join``
  * ``reverse``
  * ``length``
  * ``sort``
  * ``merge``
  * ``default``
  * ``keys``
  * ``escape``
  * ``e``

* Funciones:

  * ``range``
  * ``constant``
  * ``cycle``
  * ``parent``
  * ``block``

* Pruebas:

  * ``even``
  * ``odd``
  * ``defined``
  * ``sameas``
  * ``null``
  * ``divisibleby``
  * ``constant``
  * ``empty``

Extensión ``escaper``
~~~~~~~~~~~~~~~~~~~~~

La extensión ``escaper`` añade a *Twig* el escape automático de la salida. Esta define una nueva etiqueta, ``autoescape``, y un filtro ``raw``.

Al crear la extensión ``escaper``, puedes activar o desactivar la estrategia de escape global de la salida::

    $escaper = new Twig_Extension_Escaper(true);
    $twig->addExtension($escaper);

Si la estableces a ``true``, se escapan todas las variables en las plantillas, excepto las que utilizan el filtro ``raw``:

.. code-block:: jinja

    {{ article.to_html|raw }}

También puedes cambiar el modo de escape a nivel local usando la etiqueta ``autoescape`` (consulta la documentación para la sintaxis usada por el :doc:`autoescape <tags/autoescape>` antes de la versión *1.8* de *Twig*):

.. code-block:: jinja

    {% autoescape 'html' %}
        {{ var }}
        {{ var|raw }}      {# var no se escapa #}
        {{ var|escape }}   {# var no se escapa doblemente #}
    {% endautoescape %}

.. warning::

    La etiqueta ``autoescape`` no tiene ningún efecto sobre los archivos incluidos.

Las reglas de escape se implementan de la siguiente manera:

* Literales (enteros, booleanos, matrices, ...) utilizados en la plantilla directamente como variables o argumentos de filtros no son escapados automáticamente:

  .. code-block:: jinja

        {{ "Twig<br />" }} {# no se escapa #}

        {% set text = "Twig<br />" %}
        {{ text }} {# será escapado #}

* Expresiones cuyo resultado siempre es un literal o una variable marcada como segura nunca serán escapadas automáticamente:

  .. code-block:: jinja

        {{ foo ? "Twig<br />" : "<br />Twig" }} {# no será escapado #}

        {% set text = "Twig<br />" %}
        {{ foo ? text : "<br />Twig" }} {# será escapado #}

        {% set text = "Twig<br />" %}
        {{ foo ? text|raw : "<br />Twig" }} {# no será escapado #}

        {% set text = "Twig<br />" %}
        {{ foo ? text|escape : "<br />Twig" }} {# el resultado de la expresión
                                                  no será escapado #}

* El escape se aplica antes de la impresión, después de haber aplicado cualquier otro filtro:

  .. code-block:: jinja

        {{ var|upper }} {# es equivalente a {{ var|upper|escape }} #}

* El filtro ``raw`` sólo se debe utilizar al final de la cadena de filtros:

  .. code-block:: jinja

        {{ var|raw|upper }} {# se deberá escapar #}

        {{ var|upper|raw }} {# no será escapado #}

* No se aplica el escape automático si el último filtro de la cadena está marcado como seguro para el contexto actual (por ejemplo, ``html`` o ``js``). ``escaper`` y ``escaper('html')`` están marcados como seguros para *html*, ``escaper('js')`` está marcado como seguro para *javascript*, ``raw`` está marcado como seguro para todo.

  .. code-block:: jinja

        {% autoescape true js %}
        {{ var|escape('html') }} {# será escapado para html y javascript #}
        {{ var }} {# será escapado para javascript #}
        {{ var|escape('js') }} {# no se escapará doblemente #}
        {% endautoescape %}

.. note::

    Ten en cuenta que el escape automático tiene algunas limitaciones puesto que el escapado se aplica en las expresiones después de su evaluación. Por ejemplo, cuando trabajas en concatenación, ``{{foo|raw ~ bar }}`` no dará el resultado esperado ya que el escape se aplica sobre el resultado de la concatenación y no en las variables individuales (por lo tanto aquí, el filtro ``raw`` no tendrá ningún efecto).

Extensión ``sandbox``
~~~~~~~~~~~~~~~~~~~~~

La extensión ``sandbox`` se puede utilizar para evaluar código no confiable. El acceso a los atributos y los métodos inseguros está prohibido. El entorno recinto de seguridad es manejado por una política de la instancia. Por omisión, *Twig* viene con una política de clase:
``Twig_Sandbox_SecurityPolicy``. Esta clase te permite agregar a la lista blanca algunas etiquetas, filtros, propiedades y métodos::

    $tags = array('if');
    $filters = array('upper');
    $methods = array( 'Article' => array(  'getTitle',
                                           'getBody'
                                        ),
    );
    $properties = array( 'Article' => array( 'title',
                                             'body'
                                           ),
    );
    $functions = array('range');
    $policy = new Twig_Sandbox_SecurityPolicy(  $tags,
                                                $filters,
                                                $methods,
                                                $properties,
                                                $functions
                                              );

Con la configuración anterior, la política de seguridad sólo te permitirá usar los filtros ``if``, ``tag`` y ``upper``. Por otra parte, las plantillas sólo podrán llamar a los métodos ``getTitle()`` y ``getBody()`` en objetos ``Article``, y a las propiedades públicas ``title`` y ``body``. Todo lo demás no está permitido y se generará una excepción ``Twig_Sandbox_SecurityError``.

El objeto política es el primer argumento del constructor del recinto de seguridad::

    $sandbox = new Twig_Extension_Sandbox($policy);
    $twig->addExtension($sandbox);

De forma predeterminada, el modo de recinto de seguridad está desactivado y se activa cuando se incluye código de plantilla que no es de confianza usando la etiqueta ``sandbox``:

.. code-block:: jinja

    {% sandbox %}
        {% include 'user.html' %}
    {% endsandbox %}

Puedes poner todas las plantillas en el recinto de seguridad pasando ``true`` como segundo argumento al constructor de la extensión::

    $sandbox = new Twig_Extension_Sandbox($policy, true);

Extensión ``optimizer``
~~~~~~~~~~~~~~~~~~~~~~~

La extensión ``optimizer`` optimiza el nodo del árbol antes de compilarlo::

    $twig->addExtension(new Twig_Extension_Optimizer());

Por omisión, todas las optimizaciones están activadas. Puedes seleccionar las que desees habilitar pasándolas al constructor::

    $optimizer = new Twig_Extension_Optimizer(
                                    Twig_NodeVisitor_Optimizer::OPTIMIZE_FOR);

    $twig->addExtension($optimizer);

Excepciones
-----------

*Twig* puede lanzar excepciones:

* ``Twig_Error``: La excepción base para todos los errores.

* ``Twig_Error_Syntax``: Lanzada para indicar al usuario que hay un problema con la sintaxis de la plantilla.

* ``Twig_Error_Runtime``: Lanzada cuando se produce un error en tiempo de ejecución (cuando un filtro no existe, por ejemplo).

* ``Twig_Error_Loader``: Se lanza al producirse un error durante la carga de la plantilla.

* ``Twig_Sandbox_SecurityError``: Lanzada cuando aparece una etiqueta, filtro, o se llama a un método no permitido en una plantilla de un recinto de seguridad.
