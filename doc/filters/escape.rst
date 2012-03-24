``escape``
==========

El filtro ``escape`` convierte los caracteres ``&``, ``<``, ``>``, ``'`` y ``"`` de cadenas a secuencias *HTML* seguras. Utiliza esta opción si necesitas mostrar texto que puede contener tales caracteres *HTML*:

.. code-block:: jinja

    {{ user.username|escape }}

Por conveniencia, el filtro ``e`` está definido como un alias:

.. code-block:: jinja

    {{ user.username|e }}

El filtro ``escape`` también se puede utilizar fuera del contexto *HTML*; Por ejemplo, para mostrar algo en un archivo *JavaScript*, utiliza el contexto ``js``:

.. code-block:: jinja

    {{ user.username|escape('js') }}
    {{ user.username|e('js') }}

.. note::

    Internamente, ``escape`` utiliza la función `htmlspecialchars`_ nativa de *PHP*.

.. _`htmlspecialchars`: http://php.net/htmlspecialchars
