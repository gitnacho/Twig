*Twig* para diseñadores de plantillas
=====================================

Este documento describe la sintaxis y semántica del motor de plantillas y será muy útil como referencia para quién esté creando plantillas *Twig*.

Sinopsis
--------

Una plantilla simplemente es un archivo de texto. Esta puede generar cualquier formato basado en texto (*HTML*, *XML*, *CSV*, *LaTeX*, etc.) No tiene una extensión específica, :file:`.html` o :file:`.xml` están muy bien.

Una plantilla contiene **variables** o **expresiones**, las cuales se reemplazan por valores cuando se evalúa la plantilla, y las **etiquetas**, controlan la lógica de la plantilla.

A continuación mostramos una plantilla mínima que ilustra algunos conceptos básicos. Veremos los detalles más adelante en este documento:

.. code-block:: html+jinja

    <!DOCTYPE html>
    <html>
        <head>
            <title>My Webpage</title>
        </head>
        <body>
            <ul id="navigation">
            {% for item in navigation %}
                <li><a href="{{ item.href }}">{{ item.caption }}</a></li>
            {% endfor %}
            </ul>

            <h1>My Webpage</h1>
            {{ a_variable }}
        </body>
    </html>

Hay dos tipos de delimitadores: ``{% ... %}`` y ``{{ ... }}``. El primero se utiliza para ejecutar declaraciones como bucles ``for``, el último imprime en la plantilla el resultado de una expresión.

Integrando con *IDEs*
---------------------

Los *IDEs* modernos son compatibles con el resaltado de sintaxis y autocompletado en una amplia gama de lenguajes.

* *Textmate* vía el `paquete Twig`_
* *Vim* vía el `complemento de sintaxis Jinja`_
* *Netbeans* vía el `complemento de sintaxis Twig`_
* *PhpStorm* (nativo desde la versión 2.1)
* *Eclipse* vía el `complemento Twig`_
* *Sublime Text* vía el `paquete Twig`_
* *GtkSourceView* vía el `Twig language definition`_ (usado por *gedit* y otros proyectos)
* *Coda* y *SubEthaEdit* vía el `Twig syntax mode`_

Variables
---------

La aplicación pasa variables a las plantillas para que puedas combinarlas en la plantilla. Las variables pueden tener atributos o elementos en ellas a los cuales puedes acceder también. Cómo se ve una variable, en gran medida, depende de la aplicación que la proporcione.

Puedes utilizar un punto (``.``) para acceder a los atributos de una variable (métodos o propiedades de un objeto *PHP*, o elementos de una matriz *PHP*), o la así llamada sintaxis de "subíndice" (``[]``).

.. code-block:: jinja

    {{ foo.bar }}
    {{ foo['bar'] }}

.. note::

    Es importante saber que las llaves no son parte de la variable, sino de la declaración de impresión. Si accedes a variables dentro de las etiquetas no las envuelvas con llaves.

Si no existe una variable o atributo, recibirás un valor ``nulo`` cuando la opción ``strict_variables`` está ajustada a ``false``, de lo contrario *Twig* lanzará un error (consulta las :ref:`opciones de entorno <environment_options>`).

.. sidebar:: Implementación

    Por razones de conveniencia :file:`foo.bar` hace lo siguiente en la capa *PHP*:

    * Comprueba si ``foo`` es una matriz y ``bar`` un elemento válido;
    * si no, y si ``foo`` es un objeto, comprueba que ``bar`` es una propiedad válida;
    * si no, y si ``foo`` es un objeto, comprueba que ``bar`` es un método válido (incluso si ``bar`` es el constructor --- usa ``__construct()`` en su lugar);
    * si no, y si ``foo`` es un objeto, comprueba que ``getBar`` es un método válido;
    * si no, y si ``foo`` es un objeto, comprueba que ``isBar`` es un método válido;
    * si no, devuelve un valor ``null``.

    ``foo['bar']`` por el contrario sólo trabaja con matrices *PHP*:

    * Comprueba si ``foo`` es una matriz y ``bar`` un elemento válido;
    * si no, devuelve un valor ``null``.

.. note::

    Si deseas obtener un atributo dinámico en una variable, utiliza la función :doc:`attribute <functions/attribute>` en su lugar.

Variables globales
~~~~~~~~~~~~~~~~~~

Las siguientes variables siempre están disponibles en las plantillas:

* ``_self``: hace referencia a la plantilla actual;
* ``_context``: hace referencia al contexto actual;
* ``_charset``: hace referencia al juego de caracteres actual.

Definiendo variables
~~~~~~~~~~~~~~~~~~~~

Puedes asignar valores a las variables dentro de los bloques de código. Las asignaciones usan la etiqueta :doc:`set <tags/set>`:

.. code-block:: jinja

    {% set foo = 'foo' %}
    {% set foo = [1, 2] %}
    {% set foo = {'foo': 'bar'} %}

Filtros
-------

Los **filtros** pueden modificar variables. Los filtros están separados de la variable por un símbolo de tubo (``|``) y pueden tener argumentos opcionales entre paréntesis. Puedes encadenar múltiples filtros. La salida de un filtro se aplica al siguiente.

El siguiente ejemplo elimina todas las etiquetas *HTML* del ``name`` y lo formatea como nombre propio:

.. code-block:: jinja

    {{ name|striptags|title }}

Los filtros que aceptan argumentos llevan paréntesis en torno a los argumentos. Este ejemplo unirá una lista con comas:

.. code-block:: jinja

    {{ list|join(', ') }}

Para aplicar un filtro en una sección de código, envuélvelo con la etiqueta :doc:`filter <tags/filter>`:

.. code-block:: jinja

    {% filter upper %}
      Este texto cambia a mayúsculas
    {% endfilter %}

Ve a la página de :doc:`filtros <filters/index>` para aprender más acerca de los filtros incorporados.

Funciones
---------

Las funciones se pueden llamar para generar contenido. Las funciones son llamadas por su nombre seguido de paréntesis (``()``) y pueden tener argumentos.

Por ejemplo, la función ``range`` devuelve una lista que contiene una progresión aritmética de números enteros:

.. code-block:: jinja

    {% for i in range(0, 3) %}
        {{ i }},
    {% endfor %}

Ve a la página :doc:`funciones <functions/index>` para aprender más acerca de las funciones incorporadas.

Estructuras de control
----------------------

Una estructura de control se refiere a todas esas cosas que controlan el flujo de un programa --- condicionales (es decir, ``if``/``elseif``/``else``), bucles ``for``, así como cosas tales como bloques. Las estructuras de control aparecen dentro de bloques ``{% ... %}``.

Por ejemplo, para mostrar una lista de usuarios provista en una variable llamada ``users``, usa la etiqueta :doc:`for <tags/for>`:

.. code-block:: jinja

    <h1>Members</h1>
    <ul>
        {% for user in users %}
            <li>{{ user.username|e }}</li>
        {% endfor %}
    </ul>

Puedes utilizar la etiqueta :doc:`if <tags/if>` para probar una expresión:

.. code-block:: jinja

    {% if users|length > 0 %}
        <ul>
            {% for user in users %}
                <li>{{ user.username|e }}</li>
            {% endfor %}
        </ul>
    {% endif %}

Ve a la página :doc:`etiquetas <tags/index>` para aprender más acerca de las etiquetas incorporadas.

Comentarios
-----------

Para comentar parte de una línea en una plantilla, utiliza la sintaxis de comentario ``{# ... #}``. Esta es útil para depuración o para agregar información para los diseñadores de otra plantilla o para ti mismo:

.. code-block:: jinja

    {# nota: inhabilitado en la plantilla porque ya no se utiliza
        {% for user in users %}
            ...
        {% endfor %}
    #}

Incluyendo otras plantillas
---------------------------

La etiqueta :doc:`include <tags/include>` es útil para incluir una plantilla y devolver el contenido reproducido de esa plantilla a la actual:

.. code-block:: jinja

    {% include 'sidebar.html' %}

De manera predeterminada se pasa el contexto actual a las plantillas incluidas.

El contexto que se pasa a la plantilla incluida incorpora las variables definidas en la plantilla:

.. code-block:: jinja

    {% for box in boxes %}
        {% include "render_box.html" %}
    {% endfor %}

La plantilla incluida :file:`render_box.html` es capaz de acceder a ``box``.

El nombre de archivo de la plantilla depende del gestor de plantillas. Por ejemplo, el ``Twig_Loader_Filesystem`` te permite acceder a otras plantillas, dando el nombre del archivo. Puedes acceder a plantillas en subdirectorios con una barra inclinada:

.. code-block:: jinja

    {% include "sections/articles/sidebar.html" %}

Este comportamiento depende de la aplicación en que integres *Twig*.

Herencia en plantillas
----------------------

La parte más poderosa de *Twig* es la herencia entre plantillas. La herencia de plantillas te permite crear un "esqueleto" de plantilla base que contenga todos los elementos comunes de tu sitio y define los **bloques** que las plantillas descendientes pueden sustituir.

Suena complicado pero es muy básico. Es más fácil entenderlo si comenzamos con un ejemplo.

Vamos a definir una plantilla base, :file:`base.html`, la cual define el esqueleto de un documento *HTML* simple que puedes usar para una sencilla página de dos columnas:

.. code-block:: html+jinja

    <!DOCTYPE html>
    <html>
        <head>
            {% block head %}
                <link rel="stylesheet" href="style.css" />
                <title>{% block title %}{% endblock %} - My Webpage</title>
            {% endblock %}
        </head>
        <body>
            <div id="content">{% block content %}{% endblock %}</div>
            <div id="footer">
                {% block footer %}
                    &copy; Copyright 2011 by <a href="http://dominio.invalido/">
                                                 tú
                                             </a>.
                {% endblock %}
            </div>
        </body>
    </html>

En este ejemplo, las etiquetas :doc:`block <tags/block>` definen cuatro bloques que las plantillas herederas pueden rellenar. Todas las etiquetas ``block`` le dicen al motor de plantillas que una plantilla heredera puede sustituir esas porciones de la plantilla.

Una plantilla hija podría tener este aspecto:

.. code-block:: jinja

    {% extends "base.html" %}

    {% block title %}Index{% endblock %}
    {% block head %}
        {{ parent() }}
        <style type="text/css">
            .important { color: #336699; }
        </style>
    {% endblock %}
    {% block content %}
        <h1>Index</h1>
        <p class="important">
            Welcome on my awesome homepage.
        </p>
    {% endblock %}

Aquí, la clave es la etiqueta :doc:`extends <tags/extends>`. Esta le dice al motor de plantillas que esta plantilla "extiende" otra plantilla. Cuando el sistema de plantillas evalúa esta plantilla, en primer lugar busca la plantilla padre. La etiqueta ``extends`` debe ser la primera etiqueta en la plantilla.

Ten en cuenta que debido a que la plantilla heredera no define el bloque ``footer``, en su lugar se utiliza el valor de la plantilla padre.

Es posible reproducir el contenido del bloque padre usando la función :doc:`parent <../functions/parent>`. Esta devuelve el resultado del bloque padre:

.. code-block:: jinja

    {% block sidebar %}
        <h3>Table Of Contents</h3>
        ...
        {{ parent() }}
    {% endblock %}

.. tip::

    La página de documentación para la etiqueta :doc:`extends <tags/extends>` describe características más avanzadas como el anidamiento de bloques, ámbito, herencia dinámica, y herencia condicional.

.. note::

    *Twig* también es compatible con herencia múltiple por medio del así llamado reuso horizontal con la ayuda de la etiqueta :doc:`use <tags/use>`. Esta es una característica que casi nunca se necesita en las plantillas normales.

Escapando *HTML*
----------------

Cuando generas *HTML* desde plantillas, siempre existe el riesgo de que una variable incluya caracteres que afecten el *HTML* resultante. Hay dos enfoques: escapar cada variable manualmente o de manera predeterminada escapar todo automáticamente.

*Twig* apoya ambos, el escape automático está habilitado por omisión.

.. note::

    El escape automático sólo se admite si has habilitado la extensión *escaper* (el cual es el valor predeterminado).

Trabajando con el escape manual
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Si está habilitado el escape manual es **tu** responsabilidad escapar las variables si es necesario. ¿Qué escapar? Si tienes una variable que *puede* incluir cualquiera de los siguientes caracteres (``>``, ``<``, ``&`` o ``"``) **tienes** que escaparla a menos que la variable contenga *HTML* bien formado y sea de confianza. El escape trabaja *entubando* la variable a través del filtro ``|e``:

.. code-block:: jinja

    {{ user.username|e }}
    {{ user.username|e('js') }}

Trabajando con escape automático
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Ya sea que el escape automático esté habilitado o no, puedes marcar una sección de una plantilla para que sea escapada o no utilizando la etiqueta :doc:`autoescape <tags/autoescape>`:

.. code-block:: jinja

    {% autoescape true %}
        Todo en este bloque se va a escapar automáticamente
    {% endautoescape %}

Escapando
---------

A veces es deseable e incluso necesario contar con que *Twig* omita partes que de lo contrario manejaría como variables o bloques. Por ejemplo, si utilizas la sintaxis predeterminada y deseas utilizar ``{{`` como cadena sin procesar en la plantilla y no iniciar una variable, tienes que usar un truco.

La forma más sencilla es extraer la variable del delimitador (``{{``) usando una expresión variable:

.. code-block:: jinja

    {{ '{{' }}

Para secciones mayores tiene sentido marcar un bloque como :doc:`raw <tags/raw>`.

Macros
------

Las macros son comparables con funciones en lenguajes de programación regulares. Son útiles para poner modismos *HTML* utilizados frecuentemente en elementos reutilizables para no repetirlos.

Una macro se define a través de la etiqueta :doc:`macro <tags/macro>`. He aquí un pequeño ejemplo de una macro que reproduce un elemento de formulario:

.. code-block:: jinja

    {% macro input(name, value, type, size) %}
        <input type="{{ type|default('text') }}"
                 name="{{ name }}"
                 value="{{ value|e }}"
                 size="{{ size|default(20) }}" />
    {% endmacro %}

Las macros se pueden definir en cualquier plantilla, y es necesario "importarlas", antes de utilizarlas usando la etiqueta :doc:`import <../tags/import>`:

.. code-block:: jinja

    {% import "formularios.html" as forms %}

    <p>{{ forms.input('username') }}</p>

Alternativamente, puedes importar nombres desde la plantilla al espacio de nombres actual vía la etiqueta :doc:`from <tags/from>`:

.. code-block:: jinja

    {% from 'formularios.html' import input as campo_input, textarea %}

    <dl>
        <dt>Username</dt>
        <dd>{{ input_field('username') }}</dd>
        <dt>Password</dt>
        <dd>{{ input_field('password', type='password') }}</dd>
    </dl>
    <p>{{ textarea('comment') }}</p>

Expresiones
-----------

*Twig* acepta expresiones en cualquier parte. Estas funcionan de manera muy similar a *PHP* regular e incluso si no estás trabajando con *PHP* te debes sentir cómodo con estas.

.. note::

    La precedencia de los operadores es la siguiente, mostrando los operadores de menor precedencia en primer lugar: ``&``, ``^``, ``|``, ``or``, ``and``, ``==``,
    ``!=``, ``<``, ``>``, ``>=``, ``<=``, ``in``, ``..``, ``+``, ``-``, ``~``,
    ``*``, ``/``, ``//``, ``%``, ``is``, y ``**``.

Literales
~~~~~~~~~

.. versionadded:: 1.5
    El soporte para codificar claves como nombres y expresiones se añadió en *Twig* 1.5.

La forma más simple de las expresiones son literales. Los literales son representaciones para tipos *PHP*, tal como cadenas, números y matrices. Existen los siguientes literales:

* ``"Hello World"``: Todo lo que esté entre comillas simples o dobles es una cadena. Son útiles cuando necesitas una cadena en la plantilla (por ejemplo, como argumentos para llamadas a función, filtros o simplemente para extender o incluir una plantilla).

* ``42`` / ``42.23``: Números enteros y números en coma flotante se crean tan sólo escribiendo el número. Si está presente un punto es un número en coma flotante, de lo contrario es un número entero.

* ``["foo", "bar"]``: Las matrices se definen por medio de una secuencia de expresiones separadas por una coma (``,``) y envueltas entre paréntesis cuadrados (``[]``).

* ``{"foo": "bar"}``: Los valores ``hash`` se definen con una lista de claves y valores separados por una coma (``,``) y envueltos entre llaves (``{}``).

  .. code-block:: jinja

    {# claves como cadena #}
    { 'foo': 'foo', 'bar': 'bar' }

    {# claves como nombres (equivalente al hash anterior) -- a partir
       de Twig 1.5 #}
    { foo: 'foo', bar: 'bar' }

    {# keys as integer #}
    { 2: 'foo', 4: 'bar' }

    {# claves como expresiones (la expresión se debe encerrar entre
       paréntesis) -- a partir de Twig 1.5 #}
    { (1 + 1): 'foo', (a ~ 'b'): 'bar' }

* ``true`` / ``false``: ``true`` representa el valor verdadero, ``false`` representa el valor falso.

* ``null``: ``null`` no representa un valor específico. Este es el valor devuelto cuando una variable no existe. ``none`` es un alias para ``null``.

Los arreglos y ``hashes`` se pueden anidar:

.. code-block:: jinja

    {% set foo = [1, {"foo": "bar"}] %}

Matemáticas
~~~~~~~~~~~

*Twig* te permite calcular valores. Esto no suele ser útil en las plantillas, pero existe por el bien de la integridad. Admite los siguientes operadores:

* ``+``: Suma dos objetos (los operandos se convierten a números). ``{{
  1 + 1 }}`` is ``2``.

* ``-``: Sustrae el segundo número del primero. ``{{ 3 - 2 }}`` es ``1``.

* ``/``: Divide dos números. El valor devuelto será un número en coma flotante. ``{{ 1 / 2 }}`` es ``{{ 0.5 }}``.

* ``%``: Calcula el residuo de una división entera. ``{{ 11 % 7 }}`` es ``4``.

* ``//``: Divide dos números y devuelve el resultado entero truncado. ``{{
  20 // 7 }}`` is ``2``.

* ``*``: Multiplica el operando de la izquierda con el de la derecha. ``{{ 2 * 2 }}`` devolverá ``4``.

* ``**``: Eleva el operando izquierdo a la potencia del operando derecho. ``{{ 2 ** 3 }}`` would return ``8``.

Lógica
~~~~~~

Puedes combinar varias expresiones con los siguientes operadores:

* ``and``: Devuelve ``true`` si ambos operandos izquierdo y derecho son ``true``.

* ``or``: Devuelve ``true`` si el operando izquierdo o derecho es ``true``.

* ``not``: Niega una declaración.

* ``(expr)``: Agrupa una expresión.

Comparaciones
~~~~~~~~~~~~~

Los siguientes operadores de comparación son compatibles con cualquier expresión: ``==``,
``!=``, ``<``, ``>``, ``>=``, y ``<=``.

Operador de contención
~~~~~~~~~~~~~~~~~~~~~~

El operador ``in`` realiza la prueba de contención.

Esta devuelve ``true`` si el operando de la izquierda figura entre los de la derecha:

.. code-block:: jinja

    {# devuelve true #}

    {{ 1 in [1, 2, 3] }}

    {{ 'cd' in 'abcde' }}

.. tip::

    Puedes utilizar este filtro para realizar una prueba de contención en cadenas, arreglos u objetos que implementan la interfaz ``Traversable``.

Para llevar a cabo una prueba negativa, utiliza el operador ``not in``:

.. code-block:: jinja

    {% if 1 not in [1, 2, 3] %}

    {# es equivalente a #}
    {% if not (1 in [1, 2, 3]) %}

Operador de prueba
~~~~~~~~~~~~~~~~~~

El operador ``is`` realiza pruebas. Puedes utilizar las pruebas para comprobar una variable con una expresión común. El operando de la derecha es el nombre de la prueba:

.. code-block:: jinja

    {# averigua si una variable es impar #}

    {{ nombre is odd }}

Las pruebas también pueden aceptar argumentos:

.. code-block:: jinja

    {% if loop.index is divisibleby(3) %}

Puedes negar las pruebas usando el operador ``is not``:

.. code-block:: jinja

    {% if loop.index is not divisibleby(3) %}

    {# es equivalente a #}
    {% if not (loop.index is divisibleby(3)) %}

Ve a la página :doc:`Probando <tests/index>` para aprender más sobre las pruebas integradas.

Otros operadores
~~~~~~~~~~~~~~~~

Los siguientes operadores son muy útiles pero no encajan en ninguna de las otras dos categorías:

* ``..``: Crea una secuencia basada en el operando antes y después del operador (esta sólo es pura azúcar sintáctica para la función :doc:`range <functions/range>`).

* ``|``: Aplica un filtro.

* ``~``: Convierte todos los operandos en cadenas y los concatena. ``{{ "Hello " ~ name ~ "!" }}`` debería devolver (suponiendo que ``name`` es ``'John'``) ``Hello John!``.

* ``.``, ``[]``: Obtiene un atributo de un objeto.

* ``?:``: El operador ternario de *PHP*: ``{{ foo ? 'yes' : 'no' }}``

Interpolando cadenas
~~~~~~~~~~~~~~~~~~~~

.. versionadded:: 1.5
    La interpolación de cadenas se añadió en *Twig* 1.5.

La interpolación de cadena (`#{expresión}`) permite que cualquier expresión válida aparezca
dentro de una cadena. El resultado de la evaluación esa expresión se inserta en la cadena:

.. code-block:: jinja

    {{ "foo #{bar} baz" }}
    {{ "foo #{1 + 2} baz" }}

Controlando el espacio en blanco
--------------------------------

.. versionadded:: 1.1
    La etiqueta para controlar el nivel de los espacios en blanco se añadió en la *Twig* 1.1.

La primer nueva línea después de una etiqueta de plantilla se elimina automáticamente (como en *PHP*). El motor de plantillas no modifica el espacio en blanco, por lo tanto cada espacio en blanco (espacios, tabuladores, nuevas líneas, etc.) se devuelve sin cambios.

Utiliza la etiqueta ``spaceless`` para quitar los espacios en blanco entre las etiquetas *HTML*:

.. code-block:: jinja

    {% spaceless %}
        <div>
            <strong>foo</strong>
        </div>
    {% endspaceless %}

    {# Producirá <div><strong>foo</strong></div> #}

Además de la etiqueta ``spaceless`` también puedes controlar los espacios en blanco a nivel de etiquetas. Utilizando el modificador de control de los espacios en blanco en tus etiquetas, puedes recortar los espacios en blanco en ambos extremos:

.. code-block:: jinja

    {% set value = 'no spaces' %}
    {#- No deja espacios en blanco en ambos extremos -#}
    {%- if true -%}
        {{- value -}}
    {%- endif -%}

    {# produce 'sin espacios' #}

El ejemplo anterior muestra el modificador de control de espacios en blanco predeterminado, y cómo lo puedes utilizar para quitar los espacios en blanco alrededor de las etiquetas.  Recortar el espacio debe consumir todos los espacios en blanco a ese lado de la etiqueta.  Es posible utilizar el recorte de espacios en blanco en un lado de una etiqueta:

.. code-block:: jinja

    {% set value = 'no spaces' %}
    <li>    {{- value }}    </li>

    {# produce '<li>no spaces    </li>' #}

Extendiendo
-----------

Puedes extender *Twig* fácilmente.

Si estás buscando nuevas etiquetas, filtros, o funciones, echa un vistazo al `repositorio de extensiones oficial de Twig`_.

Si deseas crear una propia, lee el capítulo :doc:`Creando una extensión <creating_extensions>`.

.. _`paquete Twig`:              https://github.com/Anomareh/PHP-Twig.tmbundle
.. _`complemento de sintaxis Jinja`:      http://jinja.pocoo.org/2/documentation/integration
.. _`complemento de sintaxis Twig`:       http://plugins.netbeans.org/plugin/37069/php-twig
.. _`complemento Twig`:              https://github.com/pulse00/Twig-Eclipse-Plugin
.. _`Twig language definition`: https://github.com/gabrielcorpse/gedit-twig-template-language
.. _`repositorio de extensiones oficial de Twig`:     http://github.com/fabpot/Twig-extensions
.. _`Twig syntax mode`:         https://github.com/bobthecow/Twig-HTML.mode
