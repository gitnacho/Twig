``include``
===========

.. versionadded:: 1.12
    La función ``include`` se añadió en *Twig* 1.12.

The ``include`` function returns the rendered content of a template:

.. code-block:: jinja

    {{ include('template.html') }}
    {{ include(some_var) }}

Las plantillas incluidas tienen acceso a las variables del contexto activo.

Si estás utilizando el cargador del sistema de archivos, las plantillas se buscan en la ruta definida por este.

The context is passed by default to the template but you can also pass
additional variables:

.. code-block:: jinja

    {# template.html will have access to the variables from the current context and the additional ones provided #}
    {{ include('template.html', {foo: 'bar'}) }}

You can disable access to the context by setting ``with_context`` to
``false``:

.. code-block:: jinja

    {# únicamente la variable foo será accesible #}
    {{ include('template.html', {foo: 'bar'}, with_context = false) }}

.. code-block:: jinja

    {# no variables will be accessible #}
    {{ include('template.html', with_context = false) }}

Y si la expresión evalúa como un objeto ``Twig_Template``, *Twig* la usará directamente::

    // {{ include(template) }}

    $template = $twig->loadTemplate('some_template.twig');

    $twig->loadTemplate('template.twig')
         ->display(array('template' => $template));

When you set the ``ignore_missing`` flag, Twig will return an empty string if
the template does not exist:

.. code-block:: jinja

    {{ include('sidebar.html', ignore_missing = true) }}

También puedes proporcionar una lista de plantillas para comprobar su existencia antes de la inclusión. The first template that exists will be rendered:

.. code-block:: jinja

    {{ include(['page_detailed.html', 'page.html']) }}

If ``ignore_missing`` is set, it will fall back to rendering nothing if none
of the templates exist, otherwise it will throw an exception.

When including a template created by an end user, you should consider
sandboxing it:

.. code-block:: jinja

    {{ include('page.html', sandboxed = true) }}

Argumentos
----------

 * ``template``:       La plantilla a dibujar
 * ``variables``:      Las variables por pasar a la plantilla
 * ``with_context``:   Si pasar las variables del contexto actuales o no
 * ``ignore_missing``: Cuándo ignorar plantillas omitidas o no
 * ``sandboxed``:      Cuándo procesar la plantilla en el recinto de seguridad o no
