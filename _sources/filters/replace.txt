``replace``
===========

El filtro ``reemplaza`` formatos de una cadena dada sustituyendo los marcadores de posición (los marcadores de posición son libres):

.. code-block:: jinja

    {{ "Me gustan %this% y %that%."|replace({'%this%': foo,
                                             '%that%': "bar"}) }}

    {# devuelve Me gustan foo y bar
       si el parámetro foo es igual a la cadena foo. #}

.. seealso:: :doc:`format<format>`
