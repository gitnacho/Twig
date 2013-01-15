``json_encode``
===============

El filtro ``json_encode`` devuelve la representación *JSON* de una cadena:

.. code-block:: jinja

    {{ data|json_encode() }}

.. note::

    Internamente, *Twig* utiliza la función `json_encode`_ de *PHP*.

Argumentos
----------

 * ``options``: Las opciones

.. _`json_encode`: http://mx.php.net/json_encode
