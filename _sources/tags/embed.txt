``embed``
=========

.. versionadded:: 1.8
    La etiqueta ``embed`` se añadió en *Twig 1.8*.

La declaración ``embed`` te permite intercalar una plantilla en lugar de incluirla desde un archivo externo (como con la declaración ``include``):

.. code-block:: jinja

    {% embed "sidebar.twig" %}
        {% block content %}
            Algún contenido para la barra lateral
        {% endblock %}
    {% endembed %}

Como no es fácil de entender en qué circunstancias puede ser útil, vamos a poner un ejemplo; imagina una plantilla base compartida por muchas páginas con un solo bloque:

.. code-block:: text

    ┌─── Página n ────────────────────────────┐
    │                                         │
    │           ┌─────────────────────────┐   │
    │           │                         │   │
    │           │                         │   │
    │           │                         │   │
    │           │                         │   │
    │           │                         │   │
    │           │                         │   │
    │           └─────────────────────────┘   │
    │                                         │
    └─────────────────────────────────────────┘

Algunas páginas (página 1, 2, ...) comparten la misma estructura para el bloque:

.. code-block:: text

    ┌─── Páginas 1 y 2 ───────────────────────┐
    │                                         │
    │           ┌── Base A ───────────────┐   │
    │           │ ┌── contenido1 ───────┐ │   │
    │           │ │ contenido para p1   │ │   │
    │           │ └─────────────────────┘ │   │
    │           │ ┌── contenido2 ───────┐ │   │
    │           │ │ contenido para p1   │ │   │
    │           │ └─────────────────────┘ │   │
    │           └─────────────────────────┘   │
    │                                         │
    └─────────────────────────────────────────┘

Mientras que otras páginas (página a, b, ...) comparten una estructura diferente para el bloque:

.. code-block:: text

    ┌─── Pagina a, b ─────────────────────────┐
    │                                         │
    │           ┌── Base B ───────────────┐   │
    │           │ ┌─────────┐ ┌─────────┐ │   │
    │           │ │         │ │         │ │   │
    │           │ │contenido│ │contenido│ │   │
    │           │ │a, ...   │ │b, ...   │ │   │
    │           │ │         │ │         │ │   │
    │           │ └─────────┘ └─────────┘ │   │
    │           └─────────────────────────┘   │
    │                                         │
    └─────────────────────────────────────────┘

Sin la etiqueta ``embed``, tienes dos maneras de diseñar tus plantillas:

 * Crear dos plantillas base (una para los bloques 1, 2, ... y otra para los bloques a, b, ...) con el fin de eliminar el código de plantilla común, entonces una plantilla para cada página que hereda de una de las plantilla base;

 * Insertar el contenido personalizado de cada página directamente en cada página sin usar ningún tipo de plantillas externas (necesitarías repetir el código común de todas las plantillas).

Estas dos soluciones no se adaptan bien porque cada una tiene un gran inconveniente:

 * La primera solución te hace crear muchos archivos externos (que no vas a volver a utilizar en ningún otro lugar) y por lo tanto no puedes mantener tu plantilla legible (mucho del código y contenido están fuera de contexto);

 * La segunda te solución permite duplicar algún código común desde una plantilla a otra (por lo que no obedece el principio de "No repitas").

En tal situación, la etiqueta de `embed`` soluciona todos estos problemas. El código común se puede crear fuera de las plantillas base (como en la solución 1), y el contenido personalizado se mantiene en cada página (como en la solución 2):

.. code-block:: jinja

    {# plantilla para las páginas 1, 2, ... #}

    {% extends page %}

    {% block base %}
        {% embed "base_A.twig" %}
            {% block contenido1 %}
                Contenido 1 de la página 2
            {% endblock %}

            {% block contenido2 %}
                Contenido 2 de la página 2
            {% endblock %}
        {% endembed %}
    {% endblock %}

Y aquí está el código para ``base_A.twig``:

.. code-block:: jinja

    Algún código

    {% block contenido1 %}
        Algún contenido predefinido
    {% endblock %}

    Algún otro código

    {% block contenido2 %}
        Algún contenido predefinido
    {% endblock %}

    Todavía, algún otro código

El objetivo de la plantilla base ``base_a.twig`` es eliminar las partes ``Algún código``, ``Algún otro código``, y ``Todavía, algún otro código``.

La etiqueta ``embed`` toma exactamente los mismos argumentos que la etiqueta ``include``:

.. code-block:: jinja

    {% embed "base" with {'foo': 'bar'} %}
        ...
    {% endembed %}

    {% embed "base" with {'foo': 'bar'} only %}
        ...
    {% endembed %}

    {% embed "base" ignore missing %}
        ...
    {% endembed %}

.. warning::

    Debido a que las plantillas incrustadas no tienen "nombres", las estrategias de autoescape basadas en el ::file:`"nombre de archivo"` de la plantilla no funcionan como se espera si cambias el contexto (por ejemplo, si incrustas una plantilla *CSS*/*JavaScript* en un archivo *HTML*). En ese caso, establece explícitamente el valor predeterminado para la estrategia de autoescape con la etiqueta ``autoescape``.

.. seealso:: :doc:`include <../tags/include>`
