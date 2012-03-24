``autoescape``
==============

Ya sea que el escape automático esté habilitado o no, puedes marcar una sección de una plantilla para que sea escapada o no utilizando la etiqueta ``autoescape``:

.. code-block:: jinja

    {% autoescape true %}
        Todo en este bloque se va a escapar automáticamente
    {% endautoescape %}

    {% autoescape false %}
        Todo en este bloque se reproducirá tal cual
    {% endautoescape %}

    {% autoescape true js %}
        Todo en este bloque se escapará automáticamente con la estrategia
        de escape js
    {% endautoescape %}

Cuando se activa el escape automático, de manera predeterminada todo será escapado, salvo los valores marcados explícitamente como seguros. Estos se pueden marcar en la plantilla usando el filtro :doc:`raw<../filters/raw>`:

.. code-block:: jinja

    {% autoescape true %}
        {{ safe_value|raw }}
    {% endautoescape %}

Las funciones que devuelven datos de la plantilla (como :doc:`macros <macro>` y :doc:`parent <../functions/parent>`) siempre devuelven marcado seguro.

.. note::

    *Twig* es lo suficientemente inteligente como para no escapar un valor que ya fue escapado por el filtro :doc:`escape <../filters/escape>`.

.. note::

    El capítulo :doc:`Twig para desarrolladores <../api>` proporciona más información acerca de cuándo y cómo se aplica el escape automático.
