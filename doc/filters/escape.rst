``escape``
==========

El filtro ``escape`` convierte los caracteres ``&``, ``<``, ``>``, ``'`` y ``"`` de cadenas a secuencias *HTML* seguras. Utiliza esta opción si necesitas mostrar texto que puede contener tales caracteres *HTML*:

.. code-block:: jinja

    {{ user.username|escape }}

Por conveniencia, el filtro ``e`` está definido como un alias:

.. code-block:: jinja

    {{ user.username|e }}

Además, puedes usar el filtro ``escape`` fuera del contexto *HTML* gracias al argumento opcional que define la estrategia de escape a usar:

.. code-block:: jinja

    {{ user.username|e }}
    {# es equivalente a #}
    {{ user.username|e('html') }}

Y aquí tienes cómo escapar variables incluidas en código *JavaScript*:

.. code-block:: jinja

    {{ user.username|escape('js') }}
    {{ user.username|e('js') }}

.. note::

    Internamente, ``escape`` utiliza la función `htmlspecialchars`_ nativa de *PHP*.

.. _`htmlspecialchars`: http://php.net/htmlspecialchars
