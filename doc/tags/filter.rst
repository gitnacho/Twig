``filter``
==========

Filtrar secciones te permite aplicar filtros *Twig* regulares en un bloque de datos de la plantilla. Simplemente envuelve el código en el bloque especial ``filter``:

.. code-block:: jinja

    {% filter upper %}
        Este texto cambia a mayúsculas
    {% endfilter %}

También puedes encadenar filtros:

.. code-block:: jinja

    {% filter lower|escape %}
        <strong>ALGÚN TEXTO</strong>
    {% endfilter %}

    {# produce "&lt;strong&gt;some text&lt;/strong&gt;" #}
