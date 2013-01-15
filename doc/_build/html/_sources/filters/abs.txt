``abs``
=======

El filtro ``abs`` devuelve el valor absoluto.

.. code-block:: jinja

    {# numero = -5 #}
    
    {{ numero|abs }}
    
    {# produce 5 #}

.. note::

    Internamente, *Twig* usa la función `abs`_ de *PHP*.

.. _`abs`: http://www.php.net/manual/es/function.abs.php
