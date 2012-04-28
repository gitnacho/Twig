``use``
=======

.. versionadded:: 1.1
    La reutilización horizontal se añadió en *Twig* 1.1.

.. note::

    La reutilización horizontal es una característica avanzada de *Twig* que casi nunca es necesaria en plantillas regulares. La utilizan principalmente proyectos que tienen que reutilizar bloques de plantilla sin utilizar herencia.

La herencia de plantillas es una de las más poderosas características de *Twig*, pero está limitada a herencia simple; una plantilla sólo puede extender a una plantilla más.
Esta limitación facilita el entendimiento y depuración de la herencia de plantillas:

.. code-block:: jinja

    {% extends "base.html" %}

    {% block title %}{% endblock %}
    {% block content %}{% endblock %}

La reutilización horizontal es una forma de conseguir el mismo objetivo que la herencia múltiple, pero sin la complejidad asociada:

.. code-block:: jinja

    {% extends "base.html" %}

    {% use "bloques.html" %}

    {% block title %}{% endblock %}
    {% block content %}{% endblock %}

La declaración ``use`` dice a *Twig* que importe los bloques definidos en :file:`bloques.html` a la plantilla actual (es como las macros, pero para bloques):

.. code-block:: jinja

    {# bloques.html #}
    {% block sidebar %}{% endblock %}

En este ejemplo, la declaración ``use`` importa la declaración del bloque ``sidebar`` en la plantilla principal. El código ---en su mayoría--- es equivalente a lo siguiente (los bloques importados no se generan automáticamente):

.. code-block:: jinja

    {% extends "base.html" %}

    {% block sidebar %}{% endblock %}
    {% block title %}{% endblock %}
    {% block content %}{% endblock %}

.. note::

    La etiqueta ``use`` sólo importa una plantilla si esta:

  * no extiende a otra plantilla
  * no define macros, y
  * si el cuerpo está vacío. Pero puedes *usar* otras plantillas.

.. note::

    Debido a que las declaraciones ``use`` se resuelven independientemente del contexto pasado a la plantilla, la referencia de la plantilla no puede ser una expresión.

La plantilla principal también puede sustituir cualquier bloque importado. Si la plantilla ya define el bloque ``sidebar``, entonces, se ignora el definido en :file:`bloques.html`. Para evitar conflictos de nombre, puedes cambiar el nombre de los bloques importados:

.. code-block:: jinja

    {% extends "base.html" %}

    {% use "bloques.html" with sidebar as base_sidebar %}

    {% block sidebar %}{% endblock %}
    {% block title %}{% endblock %}
    {% block content %}{% endblock %}

.. versionadded:: 1.3
    El apoyo a ``parent()`` se añadió en *Twig 1.3*.

La función ``parent()`` determina automáticamente el árbol de herencia correcto, por lo tanto lo puedes utilizar cuando reemplaces un bloque definido en una plantilla importada:

.. code-block:: jinja

    {% extends "base.html" %}

    {% use "bloques.html" %}

    {% block sidebar %}
        {{ parent() }}
    {% endblock %}

    {% block title %}{% endblock %}
    {% block content %}{% endblock %}

En este ejemplo, el ``parent()`` correctamente llama al bloque ``sidebar`` de la plantilla :file:`blocks.html`.

.. tip::

    En *Twig 1.2*, el cambio de nombre te permite simular la herencia llamando al bloque "padre":

    .. code-block:: jinja

        {% extends "base.html" %}

        {% use "bloques.html" with sidebar as parent_sidebar %}

        {% block sidebar %}
            {{ block('parent_sidebar') }}
        {% endblock %}

.. note::

    Puedes utilizar tantas instrucciones ``use`` como quieras en cualquier plantilla determinada.
    Si dos plantillas importadas definen el mismo bloque, la última gana.
