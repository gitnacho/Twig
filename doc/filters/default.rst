``default``
===========

El filtro ``default`` devuelve el valor pasado como predeterminado si el valor no está definido o está vacío, de lo contrario devuelve el valor de la variable:

.. code-block:: jinja

    {{ var|default('var no está definido') }}

    {{ var.foo|default('el elemento foo en var no está definido') }}

    {{ var['foo']|default('el elemento foo en var no está definido') }}

    {{ ''|default('la variable pasada está vacía')  }}

Cuando usas el filtro ``default`` en una expresión que usa variables en alguna llamada a método, asegúrate de usar el filtro ``default`` cuando no se haya definido una variable:

.. code-block:: jinja

    {{ var.method(foo|default('foo'))|default('foo') }}

.. note::

    Lee más adelante la documentación de las pruebas :doc:`defined <../tests/defined>` y :doc:`empty <../tests/empty>` para aprender más acerca de su semántica.

Argumentos
----------

 * ``default``: El valor predefinido
