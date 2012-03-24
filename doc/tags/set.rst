``set``
=======

Dentro del código de los bloques también puedes asignar valores a variables. Las asignaciones utilizan la etiqueta ``set`` y puedes tener múltiples destinos:

.. code-block:: jinja

    {% set foo = 'foo' %}

    {% set foo = [1, 2] %}

    {% set foo = {'foo': 'bar'} %}

    {% set foo = 'foo' ~ 'bar' %}

    {% set foo, bar = 'foo', 'bar' %}

La etiqueta ``set`` también se puede usar ​​para "capturar" trozos de texto:

.. code-block:: jinja

    {% set foo %}
      <div id="pagination">
        ...
      </div>
    {% endset %}

.. caution::

    Si habilitas el escape automático, *Twig* sólo tendrá en cuenta el contenido seguro al capturar fragmentos de texto.
