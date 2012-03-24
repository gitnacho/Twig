``spaceless``
=============

Usa la etiqueta ``spaceless`` para eliminar espacios en blanco *entre las etiquetas HTML*, no espacios en blanco en las etiquetas *HTML* o en el texto simple:

.. code-block:: jinja

    {% spaceless %}
        <div>
            <strong>foo</strong>
        </div>
    {% endspaceless %}

    {# Producirá <div><strong>foo</strong></div> #}

Esta etiqueta no tiene la intención de "optimizar" el tamaño del contenido *HTML* generado, sino simplemente eliminar espacios en blanco extra entre las etiquetas *HTML* para evitar la representación caprichosa en navegadores bajo algunas circunstancias.

.. tip::

    Si deseas optimizar el tamaño del contenido *HTML* generado, en su lugar comprime el resultado con ``gzip``.

.. tip::

    Si deseas crear una etiqueta que retire todos los espacios en blanco extra en una cadena *HTML*, te advertimos que esto no es tan fácil como parece (piensa en etiquetas ``textarea`` o ``pre``, por ejemplo). Usar una biblioteca de terceros, como ``Tidy`` probablemente es una mejor idea.

.. tip::

    Para más información sobre el control de los espacios en blanco, lee la :doc:`sección dedicada </templates>` de la documentación y también aprende cómo puedes utilizar el modificador del control de espacios en blanco en tus etiquetas.
