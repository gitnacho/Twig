``slice``
===========

.. versionadded:: 1.6
    El filtro ``slice`` se añadió en *Twig* 1.6.

El filtro ``slice`` extrae un segmento de una secuencia, una matriz asociativa, o una cadena:

.. code-block:: jinja

    {% for i in [1, 2, 3, 4]|slice(1, 2) %}
        {# iterará en 2 y 3 #}
    {% endfor %}

    {{ '1234'|slice(1, 2) }}

    {# produce 23 #}

Puedes usar cualquier expresión válida tanto para ``start`` como para ``length``:

.. code-block:: jinja

    {% for i in [1, 2, 3, 4]|slice(start, length) %}
        {# ... #}
    {% endfor %}

Como azúcar sintáctica, también puedes utilizar la notación ``[]``:

.. code-block:: jinja

    {% for i in [1, 2, 3, 4][start:length] %}
        {# ... #}
    {% endfor %}

    {{ '1234'[1:2] }}

El filtro ``slice`` trabaja como la función ``array_slice`` de *PHP* para las matrices y
`substr`_ para las cadenas.

Si ``start`` no es negativo, la secuencia iniciará en ``start`` en la variable. Si ``start`` es negativo, la secuencia comenzará en esa posición desde el final de la variable.

Si se especifica ``length`` y es positivo, entonces la secuencia tendrá hasta tantos elementos en ella. Si la variable es más corta que la longitud, entonces, sólo los elementos disponibles en la variable estarán presentes. Si se especifica ``length`` y es negativo, entonces la secuencia se detendrá hasta tantos elementos a partir del final de la variable. Si se omite, la secuencia tendrá todo desde el desplazamiento hasta el final de la variable.

.. note::

    Además trabaja con objetos que implementan la interfaz `Traversable`_.

.. _`Traversable`: http://php.net/manual/en/class.traversable.php
.. _`array_slice`: http://php.net/array_slice
.. _`substr`:      http://php.net/substr
