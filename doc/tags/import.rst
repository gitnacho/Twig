``import``
==========

*Twig* apoya poner en macros el código usado frecuentemente :doc:`macros <../tags/macro>`. Estas macros pueden estar en diferentes plantillas y se importan desde allí.

Hay dos formas de importar plantillas. Puedes importar la plantilla completa en una variable o solicitar macros específicas de ella.

Imaginemos que tienes un módulo auxiliar que reproduce formularios (llamado :file:`formularios.html`):

.. code-block:: jinja

    {% macro input(name, value, type, size) %}
        <input type="{{ type|default('text') }}"
                 name="{{ name }}"
                 value="{{ value|e }}"
                 size="{{ size|default(20) }}" />
    {% endmacro %}

    {% macro textarea(name, value, rows) %}
        <textarea name="{{ name }}"
              rows="{{ rows|default(10) }}"
              cols="{{ cols|default(40) }}">
              {{ value|e }}
    </textarea>
    {% endmacro %}

La forma más fácil y flexible es importar todo el módulo en una variable.
De esa manera puedes acceder a los atributos:

.. code-block:: jinja

    {% import 'formularios.html' as forms %}

    <dl>
        <dt>Username</dt>
        <dd>{{ forms.input('username') }}</dd>
        <dt>Password</dt>
        <dd>{{ forms.input('password', null, 'password') }}</dd>
    </dl>
    <p>{{ forms.textarea('comentario') }}</p>

Alternativamente, puedes importar nombres desde la plantilla al espacio de nombres actual:

.. code-block:: jinja

    {% from 'formularios.html' import input as campo_input, textarea %}

    <dl>
        <dt>Username</dt>
        <dd>{{ input_field('username') }}</dd>
        <dt>Password</dt>
        <dd>{{ input_field('password', '', 'password') }}</dd>
    </dl>
    <p>{{ textarea('comment') }}</p>

La importación no es necesaria si las macros y la plantilla están definidas en el mismo archivo; en su lugar usa la variable especial ``_self``:

.. code-block:: jinja

    {# plantilla index.html #}

    {% macro textarea(name, value, rows) %}
        <textarea name="{{ name }}"
              rows="{{ rows|default(10) }}"
              cols="{{ cols|default(40) }}">
              {{ value|e }}
    </textarea>
    {% endmacro %}

    <p>{{ _self.textarea('comentario') }}</p>

Pero sí puedes crear un alias importando la variable ``_self``:

.. code-block:: jinja

    {# plantilla index.html #}

    {% macro textarea(name, value, rows) %}
        <textarea name="{{ name }}"
              rows="{{ rows|default(10) }}"
              cols="{{ cols|default(40) }}">
              {{ value|e }}
    </textarea>
    {% endmacro %}

    {% import _self as forms %}

    <p>{{ forms.textarea('comentario') }}</p>

.. seealso:: :doc:`macro <../tags/macro>`, :doc:`from <../tags/from>`
