``number_format``
=================

.. versionadded:: 1.5
    El filtro ``number_format`` se añadió en *Twig* 1.5

El filtro ``number_format`` formatea números.  Este es una envoltura en torno a la función `number_format`_ de *PHP*:

.. code-block:: jinja

    {{ 200.35|number_format }}

Puedes controlar el número de decimales, punto decimal, y separador de miles utilizando los argumentos adicionales:

.. code-block:: jinja

    {{ 9800.333|number_format(2, '.', ',') }}

Si no proporcionas opciones de formato entonces *Twig* usará las opciones de formato predefinidas:

- 0 lugares decimales.
- ``.`` como el punto decimal.
- ``,`` como el separador de miles.

Estas opciones predefinidas se pueden cambiar fácilmente a través de la extensión del núcleo:

.. code-block:: php

    $twig = new Twig_Environment($loader);
    $twig->getExtension('core')->setNumberFormat(3, '.', ',');

Puedes ajustar las opciones predefinidas para ``number_format`` en cada llamada usando los parámetros adicionales.

Argumentos
----------

 * ``decimal``:       El número de puntos decimales a mostrar
 * ``decimal_point``: El carácter a utilizar para el punto decimal
 * ``decimal_sep``:   El carácter a utilizar para el separador de miles

.. _`number_format`: http://php.net/number_format
