``include``
===========

La declaración ``include`` inserta una plantilla y devuelve el contenido presentado por ese archivo en el espacio de nombres actual:

.. code-block:: jinja

    {% include 'header.html' %}
        Body
    {% include 'footer.html' %}

Las plantillas incluidas tienen acceso a las variables del contexto activo.

Si estás utilizando el cargador del sistema de archivos, las plantillas se buscan en la ruta definida por este.

Puedes añadir variables adicionales pasándolas después de la palabra clave ``with``:

.. code-block:: jinja

    {# la plantilla foo tendrá acceso a las variables del contexto
       actual y al de foo #}
    {% include 'foo' with {'foo': 'bar'} %}

    {% set vars = {'foo': 'bar'} %}
    {% include 'foo' with vars %}

Puedes desactivar el acceso al contexto añadiendo la palabra clave ``only``:

.. code-block:: jinja

    {# únicamente la variable foo será accesible #}
    {% include 'foo' with {'foo': 'bar'} only %}

.. code-block:: jinja

    {# ninguna variable será accesible #}
    {% include 'foo' only %}

.. tip::

    Cuando incluyes una plantilla creada por un usuario final, debes considerar supervisarla. Más información en el capítulo :doc:`Twig para Desarrolladores <../api>`.

El nombre de la plantilla puede ser cualquier expresión *Twig* válida:

.. code-block:: jinja

    {% include some_var %}
    {% include ajax ? 'ajax.html' : 'not_ajax.html' %}

Y si la expresión evalúa como un objeto ``Twig_Template``, *Twig* la usará directamente::

    // {% include template %}

    $template = $twig->loadTemplate('some_template.twig');

    $twig->loadTemplate('template.twig')
         ->display(array('template' => $template));

.. versionadded:: 1.2
    La característica ``ignore missing`` se añadió en *Twig* 1.2.

Puedes marcar un ``include`` con ``ignore missing`` en cuyo caso *Twig* omitirá la declaración si la plantilla a ignorar no existe. Se tiene que colocar justo después del nombre de la plantilla. He aquí algunos ejemplos válidos:

.. code-block:: jinja

    {% include "sidebar.html" ignore missing %}
    {% include "sidebar.html" ignore missing with {'foo': 'bar} %}
    {% include "sidebar.html" ignore missing only %}

.. versionadded:: 1.2
    La posibilidad de pasar un arreglo de plantillas se añadió en *Twig* 1.2.

También puedes proporcionar una lista de plantillas para comprobar su existencia antes de la inclusión. La primer plantilla existente será incluida:

.. code-block:: jinja

    {% include ['page_detailed.html', 'page.html'] %}

Si se le da ``ignore missing``, caerá de nuevo en reproducir nada si ninguna de las plantillas existe, de lo contrario se producirá una excepción.
