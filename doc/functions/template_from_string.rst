``template_from_string``
========================

.. versionadded:: 1.11
    La función ``template_from_string`` se añadió en *Twig* 1.11.

La función ``template_from_string`` carga una plantilla desde una cadena:

.. code-block:: jinja

    {% include template_from_string("Hello {{ name }}") %}
    {% include template_from_string(page.template) %}

.. note::

    La función ``template_from_string`` de manera predeterminada no está disponible. Tienes que añadir explícitamente la extensión ``Twig_Extension_StringLoader`` al crear tu entorno *Twig*::

        $twig = new Twig_Environment(...);
        $twig->addExtension(new Twig_Extension_StringLoader());

.. note::

    Incluso si siempre utilizas la función ``template_from_string`` con la etiqueta ``include``, la puedes utilizar con cualquier etiqueta o funció que tome una plantilla como un argumento (tal como las etiquetas ``embed`` o ``extends``).

Argumentos
----------

 * ``template``: La plantilla
