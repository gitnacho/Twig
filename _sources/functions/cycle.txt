``cycle``
=========

Puedes utilizar la función ``cycle`` para recorrer un arreglo de valores:

.. code-block:: jinja

    {% for i in 0..10 %}
        {{ cycle(['odd', 'even'], i) }}
    {% endfor %}

La matriz puede contener cualquier cantidad de valores:

.. code-block:: jinja

    {% set frutas = ['manzana', 'naranja', 'cítricos'] %}

    {% for i in 0..10 %}
        {{ cycle(frutas, i) }}
    {% endfor %}
