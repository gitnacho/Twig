``embed``
=========

.. versionadded:: 1.8
    La etiqueta ``embed`` se añadió en *Twig 1.8*.

La etiqueta ``embed`` combina el comportamiento de :doc:`include <include>` y
:doc:`extends <extends>`.
Esta te permite incluir contenido de otras plantillas, tal cómo lo hace ``include``. Pero también te permite reemplazar cualquier bloque definido en la
plantilla incluida, como cuando extiendes una plantilla.

Piensa en una plantilla integrada como un esqueleto del "microdiseño".

.. code-block:: jinja

    {% embed "esqueleto_tentativo.twig" %}
        {# Estos bloques están definidos en "esqueleto_tentativo.twig" #}
        {# Y los sustituimos aquí:                    #}
        {% block left_teaser %}
            Algún contenido para la caja de prueba izquierda
        {% endblock %}
        {% block right_teaser %}
            Algún contenido para la caja de prueba derecha
        {% endblock %}
    {% endembed %}

La etiqueta ``embed`` lleva la idea de la herencia de plantillas a nivel de
fragmentos de contenido. Si bien la herencia de plantillas posibilita los "esqueletos de documento",
que están llenos de vida por las plantillas hijas, la etiqueta ``embed`` te permite crear "esqueletos" para las más pequeñas unidades de contenido y reutilizarlas y llenarlas en cualquier lugar que quieras.

Debido al caso de uso puede no ser tan obvio, veamos un ejemplo simplificado.
Imagina una plantilla base compartida por varias páginas *HTML*, la cual define un solo bloque llamado "contenido":

.. code-block:: text

    ┌─── diseño de página ────────────────┐
    │                                     │
    │        ┌── bloque "contenido" ──┐   │
    │        │                        │   │
    │        │                        │   │
    │        │ (plantilla hija aquí   │   │
    │        │  lleva el contenido)   │   │
    │        │                        │   │
    │        │                        │   │
    │        └────────────────────────┘   │
    │                                     │
    └─────────────────────────────────────┘

Algunas páginas ("foo" y "bar") comparten la misma estructura del contenido ---
dos cajas apiladas verticalmente:

.. code-block:: text

    ┌─── diseño de página ────────────────┐
    │                                     │
    │        ┌── bloque "contenido" ──┐   │
    │        │ ┌─ bloque "sup" ─────┐ │   │
    │        │ │                    │ │   │
    │        │ └────────────────────┘ │   │
    │        │ ┌─ bloque "inf" ─────┐ │   │
    │        │ │                    │ │   │
    │        │ └────────────────────┘ │   │
    │        └────────────────────────┘   │
    │                                     │
    └─────────────────────────────────────┘

mientras otras páginas ("boom" y "baz") comparten una estructura de contenido diferente --- dos cajas lado a lado:

.. code-block:: text

    ┌─── diseño de página ────────────────┐
    │                                     │
    │        ┌── bloque "contenido" ──┐   │
    │        │                        │   │    
    │        │ ┌ bloque ┐ ┌ bloque ┐  │   │
    │        │ │ "izq"  │ │ "der"  │  │   │
    │        │ │        │ │        │  │   │
    │        │ │        │ │        │  │   │
    │        │ └────────┘ └────────┘  │   │
    │        └────────────────────────┘   │
    │                                     │
    └─────────────────────────────────────┘

Sin la etiqueta ``embed``, tienes dos maneras de diseñar tus plantillas:

 * Crear dos plantillas base "intermedias" que extiendan a la plantilla del diseño principal: una con cajas apiladas verticalmente usada por las páginas "foo" y "bar" y otra con cajas lado a lado para las páginas "boom" y "baz".

 * Integrar el marcado para las cajas sup/inf e izq/der en cada plantilla de página directamente.

Estas dos soluciones no se adaptan bien porque cada una tiene un gran inconveniente:

 * La primer solución en verdad puede trabajar para este ejemplo simplificado. Pero imagina que añadimos una barra lateral, la cual a su vez contiene diferentes, estructuras de contenido recurrente. Ahora tendríamos que crear plantillas base intermedias para todas las combinaciones que produzcan estructuras de contenido y estructura de la barra lateral... y así sucesivamente.

 * La segunda solución consiste en duplicar código común con todos sus efectos y consecuencias negativas: cualquier cambio implica encontrar y editar todas las copias afectadas por la estructura, la corrección se tiene que verificar en cada copia, las copias pueden estar fuera de sincronía por modificaciones descuidadas, etc.

En tal situación, la etiqueta ``embed`` viene muy bien. El código del diseño común puede vivir en una sola plantilla base, y las dos estructuras de contenido diferentes, vamos a llamarlas "microdiseños" tendremos plantillas separadas, que serán integradas conforme sea necesario:

Plantilla de la página ``foo.twig``:

.. code-block:: jinja

    {% extends "layout_skeleton.twig" %}

    {% block content %}
        {% embed "vertical_boxes_skeleton.twig" %}
            {% block top %}
                Algún contenido para la caja de prueba superior
            {% endblock %}

            {% block bottom %}
                Algún contenido para la caja de prueba inferior
            {% endblock %}
        {% endembed %}
    {% endblock %}

Y aquí está el código para ``vertical_boxes_skeleton.twig``:

.. code-block:: html+jinja

    <div class="top_box">
        {% block top %}
            Contenido predefinido para la caja superior
        {% endblock %}
    </div>

    <div class="bottom_box">
        {% block bottom %}
            Contenido predefinido para la caja inferior
        {% endblock %}
    </div>

El objetivo de la plantilla ``vertical_boxes_skeleton.twig`` es el de eliminar el marcado *HTML* para las cajas.

La etiqueta ``embed`` toma exactamente los mismos argumentos que la etiqueta ``include``:

.. code-block:: jinja

    {% embed "base" with {'foo': 'bar'} %}
        ...
    {% endembed %}

    {% embed "base" with {'foo': 'bar'} only %}
        ...
    {% endembed %}

    {% embed "base" ignore missing %}
        ...
    {% endembed %}

.. warning::

    Debido a que las plantillas integradas no tienen "nombres", las estrategias de autoescape basadas en el :file:`"nombre de archivo"` de la plantilla no funcionan como se espera si cambias el contexto (por ejemplo, si integras una plantilla *CSS*/*JavaScript* en un archivo *HTML*). En ese caso, establece explícitamente el valor predefinido para la estrategia de escape automático con la etiqueta ``autoescape``.

.. seealso:: :doc:`include <../tags/include>`
