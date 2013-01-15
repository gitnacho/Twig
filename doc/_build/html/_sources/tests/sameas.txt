``sameas``
==========

``sameas`` comprueba si una variable apunta a la misma dirección de memoria que otra variable:

.. code-block:: jinja

    {% if foo.attribute is sameas(false) %}
        el atributo de foo, en realidad es el valor 'false' de PHP
    {% endif %}
