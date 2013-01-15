``split``
=========

.. versionadded:: 1.10.3
    The split filter was added in Twig 1.10.3.

The ``split`` filter splits a string by the given delimiter and returns a list
of strings:

.. code-block:: jinja

    {{ "uno,dos,tres"|split(',') }}
    {# devuelve ['uno', 'dos', 'tres'] #}

También puedes pasar un argumento ``limit``:

 * If ``limit`` is positive, the returned array will contain a maximum of
   limit elements with the last element containing the rest of string;

 * If ``limit`` is negative, all components except the last -limit are
   returned;

 * If ``limit`` is zero, then this is treated as 1.

.. code-block:: jinja

    {{ "uno,dos,tres,cuatro,cinco"|split(',', 3) }}
    {# devuelve ['uno', 'dos', 'tres,cuatro,cinco'] #}

Si el delimitador`` es una cadena vacía, entonces el valor será partido en segmentos iguales. La longitud la determina el argumento ``limit`` (de manera predeterminada es de un carácter).

.. code-block:: jinja

    {{ "123"|split('') }}
    {# devuelve ['1', '2', '3'] #}

    {{ "aabbcc"|split('', 2) }}
    {# devuelve ['aa', 'bb', 'cc'] #}

.. note::

    Internally, Twig uses the PHP `explode`_ or `str_split`_ (if delimiter is
    empty) functions for string splitting.

Argumentos
----------

 * ``delimiter``: El delimitador
 * ``limit``:     El argumento ``limit``

.. _`explode`:   http://php.net/explode
.. _`str_split`: http://php.net/str_split
