``reverse``
===========

.. versionadded:: 1.6
    La compatibilidad para cadenas se añadió en *Twig* 1.6.

El filtro ``reverse`` invierte una secuencia, una matriz asociativa, o una cadena:

.. code-block:: jinja

    {% for use in users|reverse %}
        ...
    {% endfor %}

    {{ '1234'|reverse }}

    {# outputs 4321 #}

.. note::

    Además trabaja con objetos que implementan la interfaz `Traversable`_.

.. _`Traversable`: http://php.net/Traversable
