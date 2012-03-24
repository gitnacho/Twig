``keys``
========

El filtro ``keys`` devuelve las claves de una matriz. Es útil cuando deseas iterar sobre las claves de una matriz:

.. code-block:: jinja

    {% for key in array|keys %}
        ...
    {% endfor %}
