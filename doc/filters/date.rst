``date``
========

.. versionadded:: 1.1
    La compatibilidad con la zona horaria se añadió en *Twig* 1.1.

.. versionadded:: 1.5
    La compatibilidad para el formato de fecha predefinido se añadió en *Twig* 1.5.

.. versionadded:: 1.6.1
    El soporte para la zona horaria predeterminada se añadió en *Twig* 1.6.1

El filtro ``date`` es capaz de formatear una fecha con una forma suministrada explícitamente:

.. code-block:: jinja

    {{ post.published_at|date("m/d/Y") }}

El filtro ``date`` acepta cadenas (estas deben ser compatibles con los formatos de la función `date`_) e instancias de `DateTime`_, o instancias de `DateInterval`_. Por ejemplo, para mostrar la fecha actual, filtra la palabra ``"now"``:

.. code-block:: jinja

    {{ "now"|date("m/d/Y") }}

Para escapar palabras y caracteres en el formato de fecha usa ``\\`` al frente de cada carácter:

.. code-block:: jinja

    {{ post.published_at|date("F jS \\a\\t g:ia") }}

También puedes especificar una zona horaria:

.. code-block:: jinja

    {{ post.published_at|date("m/d/Y", "Europe/Paris") }}

Si no proporcionas un formato, *Twig* utilizará el formato predefinido: ``F j, Y H:i``. Puedes cambiar fácilmente el predefinido llamando al método ``setDateFormat()`` en la instancia de la extensión ``core``. El primer argumento es el formato predefinido para fechas y el segundo es el formato predeterminado para los intervalos de fecha:

.. code-block:: php

    $twig = new Twig_Environment($loader);
    $twig->getExtension('core')->setDateFormat('d/m/Y', '%d days');

Puedes fijar globalmente la zona horaria predefinida llamando a ``setTimezone()``:

.. code-block:: php

    $twig = new Twig_Environment($loader);
    $twig->getExtension('core')->setTimezone('Europe/Paris');

.. _`date`:         http://www.php.net/date
.. _`DateTime`:     http://www.php.net/DateTime
.. _`DateInterval`: http://www.php.net/DateInterval

Si el valor pasado al filtro ``date`` es ``null``, por omisión devolverá la fecha actual.
Si en lugar de la fecha actual quieres una cadena vacía, utiliza un operador ternario:

.. code-block:: jinja

    {{ post.published_at is empty ? "" : post.published_at|date("m/d/Y") }}
