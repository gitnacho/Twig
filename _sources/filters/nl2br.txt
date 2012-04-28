``nl2br``
=========

.. versionadded:: 1.5
    El filtro ``nl2br`` se añadió en *Twig* 1.5.

El filtro ``nl2br`` inserta saltos de línea *HTML* antes de todas las nuevas líneas en una cadena:

.. code-block:: jinja

    {{ "I like Twig.\nYou will like it too."|nl2br }}
    {# produce

        I like Twig.<br />
        You will like it too.

    #}

.. note::

    El filtro ``nl2br`` primero escapa la entrada antes de aplicar la transformación.
