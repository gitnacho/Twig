``trim``
========

.. versionadded:: 1.6.2
    El filtro ``trim`` se añadió en *Twig* 1.6.2.

El filtro ``trim`` quita los espacios en blanco (u otros caracteres) del principio
y final de una cadena:

.. code-block:: jinja

    {{ '  Me gusta Twig.  '|trim }}

    {# produce 'Me gusta Twig.' #}

    {{ '  Me gusta Twig.'|trim('.') }}

    {# produce '  Me gusta Twig' #}

.. note::

    Internamente, *Twig* usa la función `trim`_ de *PHP*.

.. _`trim`: http://php.net/trim
