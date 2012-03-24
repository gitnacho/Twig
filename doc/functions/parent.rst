``parent``
==========

Cuando una plantilla utiliza herencia, es posible reproducir el contenido del
bloque padre cuando reemplaces un bloque usando la función ``parent``:

.. code-block:: jinja

    {% extends "base.html" %}

    {% block sidebar %}
        <h3>Table Of Contents</h3>
        ...
        {{ parent() }}
    {% endblock %}

La llamada a ``parent()`` devolverá el contenido del bloque ``sidebar`` como lo definimos en la plantilla :file:`base.html`.

.. seealso:: :doc:`extends<../tags/extends>`, :doc:`block<../functions/block>`, :doc:`block<../tags/block>`
