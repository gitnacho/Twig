Estándares de codificación
==========================

Al escribir plantillas *Twig*, te recomendamos que sigas las siguientes normas de codificación oficiales:

* Deja un espacio (y sólo uno) después de un delimitador inicial (``{{``, ``{%``, y ``{#``) y antes del final de un delimitador (``}}``, ``%}``, y ``#}``):

  .. code-block:: jinja

    {{ foo }}
    {# comentario #}
    {% if foo %}{% endif %}

  Cuando utilices los caracteres de guión junto con el espacio en blanco, no dejes ningún espacio entre este y el delimitador:

  .. code-block:: jinja

    {{- foo -}}
    {#- comentario -#}
    {%- if foo -%}{%- endif -%}

* Deja un espacio (y sólo uno) antes y después de los siguientes operadores:
  operadores de comparación (``==``, ``!=``, ``<``, ``>``, ``>=``, ``<=``), operadores matemáticos (``+``, ``-``, ``/``, ``*``, ``%``, ``//``, ``**``), operadores lógicos (``not``, ``and``, ``or``), ``~``, ``is``, ``in``, y el operador ternario (``?:``):

  .. code-block:: jinja

     {{ 1 + 2 }}
     {{ foo ~ bar }}
     {{ true ? true : false }}

* Deja un espacio (y sólo uno) después del signo ``:`` en ``hashes`` (o ``codificaciones`` en adelante), y la ``,`` en arreglos y codificaciones:

  .. code-block:: jinja

     {{ [1, 2, 3] }}
     {{ {'foo': 'bar'} }}

* No dejes ningún espacio después de un paréntesis de apertura y antes de un paréntesis de cierre en expresiones:

  .. code-block:: jinja

    {{ 1 + (2 * 3) }}

* No dejes ningún espacio en blanco antes y después de los delimitadores de cadena:

  .. code-block:: jinja

    {{ 'foo' }}
    {{ "foo" }}

* No dejes ningún espacio en blanco antes y después de los siguientes operadores: ``|``, ``.``, ``..``, ``[]``:

  .. code-block:: jinja

    {{ foo|upper|lower }}
    {{ user.name }}
    {{ user[name] }}
    {% for i in 1..12 %}{% endfor %}

* No dejes ningún espacio en blanco antes y después de los paréntesis utilizados en filtros y llamadas a función:

  .. code-block:: jinja

     {{ foo|default('foo') }}
     {{ range(1..10) }}

* No dejes ningún espacio en blanco antes y después de la apertura de arreglos y codificaciones:

  .. code-block:: jinja

     {{ [1, 2, 3] }}
     {{ {'foo': 'bar'} }}

* Utiliza letras minúsculas y guiones bajos en nombres de variables:

  .. code-block:: jinja

     {% set foo = 'foo' %}
     {% set foo_bar = 'foo' %}

* Sangra tu código dentro de las etiquetas (usa la misma profundidad que la utilizada en el lenguaje principal del archivo):

  .. code-block:: jinja

     {% block foo %}
        {% if true %}
            true
        {% endif %}
     {% endblock %}
