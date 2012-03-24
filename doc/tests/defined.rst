``defined``
===========

``defined`` comprueba si una variable está definida en el contexto actual. Esto es muy útil si utilizas la opción ``strict_variables``:

.. code-block:: jinja

    {# defined trabaja con nombres de variable #}
    {% if foo is defined %}
        ...
    {% endif %}

    {# y atributos en nombres de variables #}
    {% if foo.bar is defined %}
        ...
    {% endif %}

    {% if foo['bar'] is defined %}
        ...
    {% endif %}

Cuando uses la prueba ``defined`` en una expresión que usa variables en alguna llamada a método, primero asegúrate de haberlas definido:

.. code-block:: jinja

    {% if var is defined and foo.method(var) is defined %}
        ...
    {% endif %}
