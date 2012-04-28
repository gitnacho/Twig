``convert_encoding``
====================

.. versionadded:: 1.4
    El filtro ``convert_encoding`` se añadió en *Twig* 1.4.

El filtro ``convert_encoding`` convierte una cadena de una codificación a otra. El primer argumento es el juego de caracteres esperado y el segundo es el juego de caracteres de entrada:

.. code-block:: jinja

    {{ data|convert_encoding('UTF-8', 'iso-2022-jp') }}

.. note::

    Este filtro está basado en la extensión `iconv`_ o `mbstring`_, por lo tanto tienes que instalar una de ellas. En caso que tengas instaladas ambas, por omisión se utiliza `iconv`_.

.. _`iconv`:    http://mx.php.net/iconv
.. _`mbstring`: http://mx2.php.net/mbstring
