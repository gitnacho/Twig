``random``
==========

.. versionadded:: 1.5
    La función ``random`` se agregó en *Twig* 1.5.

.. versionadded:: 1.6
    Se añadió la manipulación de ``string`` e ``integer`` en *Twig* 1.6.

La función ``random`` devuelve un valor al azar dependiendo del tipo de parámetro suministrado:

* un elemento al azar de una secuencia;
* un carácter aleatorio de una cadena;
* un entero al azar entre 0 y el parámetro entero (inclusive).

.. code-block:: jinja

    {{ random(['apple',
               'orange',
               'citrus']) }} {# ejemplo de salida: orange #}
    {{ random('ABC') }}      {# ejemplo de salida: C #}
    {{ random() }}           {# ejemplo de salida: 15386094
                                (trabaja como la función
                                `mt_rand`_ nativa de PHP) #}
    {{ random(5) }}          {# ejemplo de salida: 3 #}

Argumentos
----------

 * ``values``: Los valores

.. _`mt_rand`: http://mx.php.net/mt_rand
