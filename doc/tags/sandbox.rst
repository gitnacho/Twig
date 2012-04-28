``sandbox``
===========

La etiqueta ``sandbox`` se puede utilizar para activar el modo de recinto de seguridad para una plantilla incluida, cuando no está habilitado globalmente el modo de recinto de seguridad para el entorno *Twig*:

.. code-block:: jinja

    {% sandbox %}
        {% include 'user.html' %}
    {% endsandbox %}

.. warning::

    La etiqueta ``sandbox`` sólo está disponible cuando está habilitada la extensión ``sandbox`` (ve el capítulo :doc:`Twig para desarrolladores <../api>`).

.. note::

    La etiqueta ``sandboc`` sólo se puede utilizar para asegurar una etiqueta ``include`` y no se puede utilizar para proteger una sección de una plantilla. Por ejemplo, el siguiente ejemplo no funcionará:

    .. code-block:: jinja

        {% sandbox %}
            {% for i in 1..2 %}
                {{ i }}
            {% endfor %}
        {% endsandbox %}
