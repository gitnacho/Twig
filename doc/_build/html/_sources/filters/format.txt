``format``
==========

El filtro ``format`` filtra formatos de una cadena dada sustituyendo los marcadores de posición (los marcadores de posición siguen la notación de ``printf``):

.. code-block:: jinja

    {{ "Me gustan %s y %s."|format(foo, "bar") }}

    {# devuelve Me gustan foo y bar
       si el parámetro foo es igual a la cadena foo. #}

.. _`printf`: http://www.php.net/printf

.. seealso:: :doc:`replace<replace>`
