``empty``
=========

``empty`` comprueba si una variable está vacía:

.. code-block:: jinja

    {# evalúa a true si la variable foo es null, false o la
       cadena vacía #}
    {% if foo is empty %}
        ...
    {% endif %}
