``constant``
============

``constant`` devuelve el valor constante de una determinada cadena:

.. code-block:: jinja

    {{ some_date|date(constant('DATE_W3C')) }}
    {{ constant('Namespace\\Classname::CONSTANT_NAME') }}
