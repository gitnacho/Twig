``block``
=========

Cuando una plantilla utiliza herencia y si deseas imprimir un bloque varias
veces, usa la función ``block``:

.. code-block:: jinja

    <title>{% block title %}{% endblock %}</title>

    <h1>{{ block('title') }}</h1>

    {% block body %}{% endblock %}

.. seealso:: :doc:`extends<../tags/extends>`, :doc:`parent<../functions/parent>`
