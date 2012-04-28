``iterable``
============

.. versionadded:: 1.7
    La prueba ``iterable`` se añadió en *Twig 1.7*.

``iterable`` comprueba si una variable es una matriz o un objeto transitable:

.. code-block:: jinja

    {# evalúa a true si la variable foo es iterable #}
    {% if users is iterable %}
        {% for user in users %}
            Hello {{ user }}!
        {% endfor %}
    {% else %}
        {# probablemente users sea una cadena #}
        Hello {{ users }}!
    {% endif %}
