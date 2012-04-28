``sort``
========

El filtro ``sort`` ordena una matriz:

.. code-block:: jinja

    {% for use in users|sort %}
        ...
    {% endfor %}

.. note::

    Internamente, *Twig* utiliza la función `asort`_ de *PHP* para mantener asociado el índice.

.. _`asort`: http://mx.php.net/asort
