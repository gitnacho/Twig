``extends``
===========

Puedes utilizar la etiqueta ``extends`` para extender una plantilla a partir de otra.

.. note::

    Al igual que *PHP*, *Twig* no admite la herencia múltiple. Por lo tanto sólo puedes tener una etiqueta ``extends`` por reproducción. Sin embargo, *Twig* apoya el :doc:`reuso <use>` horizontal.

Vamos a definir una plantilla base, :file:`base.html`, la cual define el esqueleto de un documento *HTML* simple:

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

En este ejemplo, las etiquetas :doc:`block <block>` definen cuatro bloques que las plantillas descendientes pueden rellenar.

Todas las etiquetas ``block`` le dicen al motor de plantillas que una plantilla derivada puede sustituir esas porciones de la plantilla.

Plantilla descendiente
~~~~~~~~~~~~~~~~~~~~~~

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

Aquí, la clave es la etiqueta ``extends``. Esta le dice al motor de plantillas que esta plantilla "extiende" otra plantilla. Cuando el sistema de plantillas evalúa esta plantilla, en primer lugar busca la plantilla padre. La etiqueta ``extends`` debe ser la primera etiqueta en la plantilla.

Ten en cuenta que debido a que la plantilla heredera no define el bloque ``footer``, en su lugar se utiliza el valor de la plantilla padre.

No puedes definir múltiples etiquetas ``block`` con el mismo nombre en la misma plantilla. Esta limitación existe porque una etiqueta de bloque trabaja en "ambas" direcciones. Es decir, una etiqueta de bloque no sólo proporciona un hueco para rellenar --- sino que también define en el *padre* el contenido que rellena el hueco. Si en una plantilla hubiera dos etiquetas ``block`` con nombres similares, la plantilla padre, no sabría cual contenido de entre esos bloques usar.

No obstante, si deseas imprimir un bloque varias veces, puedes utilizar la función ``block()``:

.. code-block:: jinja

    <title>{% block title %}{% endblock %}</title>
    <h1>{{ block('title') }}</h1>
    {% block body %}{% endblock %}

Bloques padre
-------------

Es posible reproducir el contenido del bloque padre usando la función :doc:`parent <../functions/parent>`. Esta devuelve el resultado del bloque padre:

.. code-block:: jinja

    {% block sidebar %}
        <h3>Table Of Contents</h3>
        ...
        {{ parent() }}
    {% endblock %}

Etiquetas de cierre de bloques nombrados
----------------------------------------

*Twig* te permite poner el nombre del bloque después de la etiqueta para facilitar su lectura:

.. code-block:: jinja

    {% block sidebar %}
        {% block inner_sidebar %}
            ...
        {% endblock inner_sidebar %}
    {% endblock sidebar %}

Por supuesto, el nombre después de la palabra ``endblock`` debe coincidir con el nombre del bloque.

Bloques anidados y ámbito
-------------------------

Los bloques se pueden anidar para diseños más complejos. Por omisión, los bloques tienen acceso a las variables del ámbito externo:

.. code-block:: jinja

    {% for item in seq %}
        <li>{% block loop_item %}{{ item }}{% endblock %}</li>
    {% endfor %}

Atajos de bloque
----------------

Para bloques con poco contenido, es posible utilizar una sintaxis abreviada. Las siguientes construcciones hacen exactamente lo mismo:

.. code-block:: jinja

    {% block title %}
        {{ page_title|title }}
    {% endblock %}

.. code-block:: jinja

    {% block title page_title|title %}

Herencia dinámica
-----------------

*Twig* es compatible con la herencia dinámica usando una variable como la plantilla base:

.. code-block:: jinja

    {% extends alguna_var %}

Si la variable se evalúa como un objeto ``Twig_Template``, *Twig* la utilizará como la plantilla padre::

    // {% extends base %}

    $base = $twig->loadTemplate('some_layout_template.twig');

    $twig->display('template.twig', array('base' => $base));

.. versionadded:: 1.2
    La posibilidad de pasar un arreglo de plantillas se añadió en *Twig* 1.2.

También puedes proporcionar una lista de plantillas que comprueben su existencia. La primer plantilla existente se utilizará como el padre:

.. code-block:: jinja

    {% extends ['base.html', 'base_layout.html'] %}

Herencia condicional
--------------------

Gracias a que el nombre para la plantilla padre puede ser cualquier expresión *Twig*, es posible el mecanismo de herencia condicional:

.. code-block:: jinja

    {% extends standalone ? "minimum.html" : "base.html" %}

En este ejemplo, la plantilla debe extender a la plantilla base :file:`minimum.html` si la variable ``standalone`` evalúa a ``true``, o de otra manera extiende a :file:`base.html`.

¿Cómo trabajan los bloques?
---------------------------

Un bloque proporciona una manera de cambiar ciertas partes de una plantilla al reproducirla, pero de ninguna manera interfiere con la lógica a su alrededor.

Toma el siguiente ejemplo que ilustra cómo trabaja un bloque y más importante, cómo no trabaja:

.. code-block:: jinja

    {# base.twig #}

    {% for post in posts %}
        {% block post %}
            <h1>{{ post.title }}</h1>
            <p>{{ post.body }}</p>
        {% endblock %}
    {% endfor %}

Si reproduces esta plantilla, el resultado sería exactamente igual con o sin la etiqueta ``block``. El ``block`` dentro del bucle ``for`` solo es una manera de hacerlo redefinible en una plantilla hija:

.. code-block:: jinja

    {# child.twig #}

    {% extends "base.twig" %}

    {% block post %}
        <article>
            <header>{{ post.title }}</header>
            <section>{{ post.text }}</section>
        </article>
    {% endblock %}

Ahora, al reproducir la plantilla hija, el bucle utilizará el bloque definido en la plantilla hija en vez del definido en la base; La plantilla ejecutada entonces es equivalente a lo siguiente:

.. code-block:: jinja

    {% for post in posts %}
        <article>
            <header>{{ post.title }}</header>
            <section>{{ post.text }}</section>
        </article>
    {% endfor %}

Observa otro ejemplo: un bloque incluido dentro de una declaración ``if``:

.. code-block:: jinja

    {% if posts is empty %}
        {% block head %}
            {{ parent() }}

            <meta name="robots" content="noindex, follow">
        {% endblock head %}
    {% endif %}

Contrario a lo que podrías pensar, esta plantilla no define un bloque condicionalmente; Sólo lo sustituye con una plantilla hija que produce lo que será dibujado cuándo la condición sea ``true``.

Si quieres mostrar el resultado condicionalmente, en su lugar usa lo siguiente:

.. code-block:: jinja

    {% block head %}
        {{ parent() }}

        {% if posts is empty %}
            <meta name="robots" content="noindex, follow">
        {% endif %}
    {% endblock head %}

.. seealso:: :doc:`block<../functions/block>`, :doc:`block<../tags/block>`, :doc:`parent<../functions/parent>`, :doc:`use<../tags/use>`
