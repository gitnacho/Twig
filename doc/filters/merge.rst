``merge``
=========

El filtro ``merge`` combina una matriz con otra matriz:

.. code-block:: jinja

    {% set values = [1, 2] %}

    {% set values = values|merge(['apple', 'orange']) %}

    {# values ahora contiene [1, 2, 'apple', 'orange'] #}

Los nuevos valores se añaden al final de los existentes.

El filtro ``merge`` también trabaja en ``codificaciones``:

.. code-block:: jinja

    {% set items = { 'apple': 'fruit',
                         'orange': 'fruit',
                     'peugeot': 'unknown' } %}

    {% set items = items|merge({ 'peugeot': 'car',
                                 'renault': 'car' }) %}

    {# items ahora contiene { 'apple': 'fruit',
                         'orange': 'fruit',
                     'peugeot': 'car',
                                 'renault': 'car' } #}

Para las ``codificaciones``, el proceso de combinación ocurre en las claves: si no existe la clave, se agrega, pero si la clave ya existe, su valor es reemplazado.

.. tip::

    Si te quieres asegurar de que algunos valores están definidos en una matriz (por determinados valores preestablecidos), revierte los dos elementos en la llamada:

    .. code-block:: jinja

        {% set items = { 'apple': 'fruit',
                         'orange': 'fruit' } %}

        {% set items = { 'apple': 'unknown' }|merge(items) %}

        {# items ahora contiene { 'apple': 'fruit',
                         'orange': 'fruit' } #}
