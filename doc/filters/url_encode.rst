``url_encode``
==============

El filtro ``url_encode`` produce una cadena *URL* codificada.

.. code-block:: jinja

    {{ data|url_encode() }}

.. note::

    Internamente, *Twig* utiliza la función `urlencode`_ de *PHP*.

.. _`urlencode`: http://mx2.php.net/manual/es/function.urlencode.php
