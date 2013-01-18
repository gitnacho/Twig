``date_modify``
===============

.. versionadded:: 1.9.0
    El filtro ``date_modify`` se añadió en *Twig* 1.9.0.

El filtro ``date_modify`` modifica una fecha con una cadena de caracteres modificadores suministrada:

.. code-block:: jinja

    {{ post.published_at|date_modify("+1 day")|date("m/d/Y") }}

El filtro ``date_modify`` acepta cadenas (deben estar en un formato compatible con la función `strtotime`_) o ser instancias de `DateTime`_. Fácilmente lo puedes combinar con el filtro :doc:`date <date>` para aplicar formato.

Argumentos
----------

 * ``modifier``: El modificador

.. _`strtotime`: http://www.php.net/strtotime
.. _`DateTime`:  http://www.php.net/DateTime
