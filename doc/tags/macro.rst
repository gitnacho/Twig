``macro``
=========

Las macros son comparables con funciones en lenguajes de programación regulares. Son útiles para poner modismos *HTML* utilizados frecuentemente en elementos reutilizables para no repetirlos.

He aquí un pequeño ejemplo de una macro que reproduce un elemento de formulario:

.. code-block:: jinja

    {% macro input(name, value, type, size) %}
        <input type="{{ type|default('text') }}"
                 name="{{ name }}"
                 value="{{ value|e }}"
                 size="{{ size|default(20) }}" />
    {% endmacro %}

Las macros se diferencian de las funciones *PHP* nativas en varias formas:

* Los valores predeterminados de los argumentos se definen usando el filtro ``default`` en el cuerpo de la macro;

* Los argumentos de una macro siempre son opcionales.

Pero como las funciones de *PHP*, las macros no tienen acceso a las variables de la plantilla actual.

.. tip::

    Puedes pasar todo el contexto como un argumento usando la variable especial ``_context``.

Las macros se pueden definir en cualquier plantilla, y es necesario "importarlas", antes de utilizarlas (consulta la etiqueta :doc:`import <../tags/import>` para más información):

.. code-block:: jinja

    {% import "formularios.html" as forms %}

La llamada a ``import`` anterior importa el archivo "formularios.html" (el cual puede contener macros solamente, o una plantilla y algunas macros), e importa las funciones como elementos de la variable ``forms``.

Entonces puedes llamar a la macro a voluntad:

.. code-block:: jinja

    <p>{{ forms.input('username') }}</p>
    <p>{{ forms.input('password', null, 'password') }}</p>

Si defines macros y las utilizas en la misma plantilla, puedes usar la variable especial ``_self``, sin necesidad de importarlas:

.. code-block:: jinja

    <p>{{ _self.input('nombreusuario') }}</p>

Cuando ---en el mismo archivo--- quieras utilizar una macro en otra, utiliza la variable ``_self``:

.. code-block:: jinja

    {% macro input(name, value, type, size) %}
      <input type="{{ type|default('text') }}"
                 name="{{ name }}"
                 value="{{ value|e }}"
                 size="{{ size|default(20) }}" />
    {% endmacro %}

    {% macro wrapped_input(name, value, type, size) %}
        <div class="field">
            {{ _self.input(name, value, type, size) }}
        </div>
    {% endmacro %}

Cuando la macro está definida en otro archivo, necesitas importarla:

.. code-block:: jinja

    {# formularios.html #}

    {% macro input(name, value, type, size) %}
      <input type="{{ type|default('text') }}"
                 name="{{ name }}"
                 value="{{ value|e }}"
                 size="{{ size|default(20) }}" />
    {% endmacro %}

    {# shortcuts.html #}

    {% macro wrapped_input(name, value, type, size) %}
        {% import "formularios.html" as forms %}
        <div class="field">
            {{ forms.input(name, value, type, size) }}
        </div>
    {% endmacro %}

.. seealso:: :doc:`from <../tags/from>`, :doc:`import <../tags/import>`
