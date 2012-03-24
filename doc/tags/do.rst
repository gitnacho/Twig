``do``
======

.. versionadded:: 1.5
    La etiqueta ``do`` se agregó en *Twig* 1.5.

La etiqueta ``do`` trabaja exactamente como la variable expresión regular (``{{ ... }}``) solo que no imprime nada:

.. code-block:: jinja

    {% do 1 + 2 %}
