``divisibleby``
===============

``divisibleby`` comprueba si una variable es divisible por un número:

.. code-block:: jinja

    {% if loop.index is divisibleby(3) %}
        ...
    {% endif %}
