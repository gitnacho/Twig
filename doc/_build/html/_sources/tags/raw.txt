``raw``
=======

La etiqueta ``raw`` marca secciones como texto seguro que no se deben analizar.
Por ejemplo, para reproducir un segmento de la sintaxis de *Twig* en una plantilla, puedes utilizar este fragmento:

.. code-block:: jinja

    {% raw %}
        <ul>
        {% for item in seq %}
            <li>{{ item }}</li>
        {% endfor %}
        </ul>
    {% endraw %}
