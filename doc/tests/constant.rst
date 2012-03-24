``constant``
============

``constant`` comprueba si una variable tiene el mismo valor exacto que una constante. Puedes utilizar cualquiera de las constantes globales o constantes de clase:

.. code-block:: jinja

    {% if post.status is constant('Post::PUBLISHED') %}
        el atributo estatus es exactamente el mismo que Post::PUBLISHED
    {% endif %}
