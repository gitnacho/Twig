``striptags``
=============

El filtro ``striptags`` quita etiquetas *SGML/XML* y sustituye los espacios en blanco adyacentes por un espacio:

.. code-block:: jinja

    {% some_html|striptags %}

.. note::

    Internamente, *Twig* utiliza la función `strip_tags`_ de *PHP*.

.. _`strip_tags`: http://mx.php.net/strip_tags
