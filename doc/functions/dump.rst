``dump``
========

.. versionadded:: 1.5
    La función ``dump`` se añadió en *Twig* 1.5.

La función ``dump`` vierte información sobre una variable de plantilla. Esta es útil principalmente para depurar una plantilla que no se comporta como se esperaba, permitiendo inspeccionar sus variables:

.. code-block:: jinja

    {{ dump(user) }}

.. note::

    La función ``debug`` no está activa de manera predeterminada. La debes cargar explícitamente::

        $twig = new Twig_Environment($loader, $config);
        $twig->addExtension(new Twig_Extension_Debug());

    Incluso aunque la cargues explícitamente, no hace nada si no activas la opción ``debug``.

En un contexto *HTML*, envuelve su resultado en una etiqueta ``pre`` para facilitar su lectura:

.. code-block:: jinja

    <pre>
        {{ dump(user) }}
    </pre>

.. tip::

    No es necesario usar una etiqueta ``pre`` cuando `XDebug`_ está activado y ``html_errors`` es ``on``; como bono adicional, el resultado también se mejora con ``XDebug`` activado.

Puedes depurar muchas variables pasándolas como argumentos adicionales:

.. code-block:: jinja

    {{ dump(user, categories) }}

Si no pasas ningún valor, se vierten todas las variables del contexto actual:

.. code-block:: jinja

    {{ dump() }}

.. note::

    Internamente, *Twig* usa la función `var_dump`_ de *PHP*.

.. _`XDebug`: http://xdebug.org/docs/display
.. _`var_dump`: http://php.net/var_dump
