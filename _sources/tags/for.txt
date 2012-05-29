``for``
=======

Recorre cada elemento de una secuencia. Por ejemplo, para mostrar una lista de usuarios provista en una variable llamada ``usuarios``:

.. code-block:: jinja

    <h1>Members</h1>
    <ul>
        {% for user in users %}
            <li>{{ user.username|e }}</li>
        {% endfor %}
    </ul>

.. note::

    Una secuencia puede ser una matriz o un objeto que implementa la interfaz ``Traversable``.

Si necesitas iterar en una secuencia de números, el operador ``..`` es muy útil:

.. code-block:: jinja

    {% for i in 0..10 %}
        * {{ i }}
    {% endfor %}

El fragmento de código anterior debería imprimir todos los números del 0 al 10.

También lo puedes utilizar con letras:

.. code-block:: jinja

    {% for letter in 'a'..'z' %}
        * {{ letter }}
    {% endfor %}

El operador ``..`` puede tomar cualquier expresión en ambos lados:

.. code-block:: jinja

    {% for letter in 'a'|upper..'z'|upper %}
        * {{ letter }}
    {% endfor %}

.. tip:

    Si necesitas un paso diferente de 1, puedes utilizar la función ``range`` en su lugar.

La variable ``loop``
--------------------

Dentro de un bloque de bucle ``for`` puedes acceder a algunas variables especiales:

===================== ========================================================================
Variable              Descripción
===================== ========================================================================
``loop.index``        La iteración actual del bucle. (indexada en 1)
``loop.index0``       La iteración actual del bucle. (indexada en 0)
``loop.revindex``     El número de iteraciones a partir del final del bucle (indexadas en 1)
``loop.revindex0``    El número de iteraciones a partir del final del bucle (indexadas en 0)
``loop.first``        ``True`` si es la primera iteración
``loop.last``         ``True`` si es la última iteración
``loop.length``       El número de elementos en la secuencia
``loop.parent``       El contexto del padre
===================== ========================================================================

.. code-block:: jinja

    {% for user in users %}
        {{ loop.index }} - {{ user.username }}
    {% endfor %}

.. note::

    Las variables ``loop.length``, ``loop.revindex``, ``loop.revindex0`` y ``loop.last`` únicamente están disponibles para matrices *PHP*, u objetos que implementen la interfaz ``Countable``. Tampoco están disponibles cuando iteras con una condición.

.. versionadded:: 1.2
    La compatibilidad con el modificador ``if`` se añadió en *Twig* 1.2.

Añadiendo una condición
-----------------------

A diferencia de *PHP*, en un bucle no es posible usar ``break`` ni ``continue``. Sin embargo, puedes filtrar la secuencia durante la iteración, lo cual te permite omitir elementos. En el siguiente ejemplo se omiten todos los usuarios que no están activos:

.. code-block:: jinja

    <ul>
        {% for user in users if user.active %}
            <li>{{ user.username|e }}</li>
        {% endfor %}
    </ul>

La ventaja es que la variable especial ``loop`` contará correctamente, es decir, sin contar a los usuarios inactivos en la iteración. Ten en cuenta que las propiedades como ``loop.last`` no están definidas cuando usas bucles condicionales.

.. note::

    Usar la variable ``loop`` sin la condición no es recomendable debido a que no llevará a cabo lo que esperas se haga. Por ejemplo, añadir una condición como ``loop.index > 4`` no funcionará puesto que el índice únicamente se incrementa cuando la condición es cierta (por lo tanto, la condición nunca coincidirá).

La cláusula ``else``
--------------------

Si no se llevó a cabo iteración debido a que la secuencia está vacía, puedes reproducir un bloque sustituto utilizando ``else``:

.. code-block:: jinja

    <ul>
        {% for user in users %}
            <li>{{ user.username|e }}</li>
        {% else %}
            <li><em>no user found</em></li>
        {% endfor %}
    </ul>

Iterando en las claves
----------------------

De forma predeterminada, un bucle itera en los valores de la secuencia. Puedes iterar en las claves con el filtro ``keys``:

.. code-block:: jinja

    <h1>Members</h1>
    <ul>
        {% for key in users|keys %}
            <li>{{ key }}</li>
        {% endfor %}
    </ul>

Iterando en claves y valores
----------------------------

También puedes acceder tanto a las claves como a los valores:

.. code-block:: jinja

    <h1>Members</h1>
    <ul>
        {% for key, user in users %}
            <li>{{ key }}: {{ user.username|e }}</li>
        {% endfor %}
    </ul>
