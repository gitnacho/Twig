``flush``
=========

.. versionadded:: 1.5
    La etiqueta ``flush`` se añadió en *Twig* 1.5.

La etiqueta ``flush`` le dice a *Twig* que vacíe el contenido de la memoria intermedia:

.. code-block:: jinja

    {% flush %}

.. note::

    Internamente, *Twig* usa la función `flush`_ de *PHP*.

.. _`flush`: http://php.net/flush
