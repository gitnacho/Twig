# -*- coding: utf-8 -*-
#
# Archivo de configuración para construir la documentación de Twig-es
# creado por sphinx-quickstart el Dom 31 de Jul 2011 08:24:32.
#
# Este archivo es ejecutado en el directorio actual fijado al directorio
# que lo contiene.
#
# Ten en cuenta que no todos los valores de configuración posibles están
# presentes en este archivo autogenerado.
#
# Todos los valores de configuración tienen un predeterminado; aquellos
# valores que están comentados sirven para mostrar el predefinido.

import sys, os
from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer
lexers['php'] = PhpLexer(startinline=True)
lexers['php-annotations'] = PhpLexer(startinline=True)
primary_domain = "php"

# Si las extensiones (o módulos a documentar con autodoc) están en otro
# directorio, agrega estos directorios a sys.path aquí. Si el directorio
# es relativo a la raíz de la documentación, usa os.path.abspath para
# hacerlo absoluto, como se muestra aquí.
#sys.path.insert(0, os.path.abspath('.'))
sys.path.append(os.path.abspath('../../../../../../_exts'))

# -- Configuración general ---------------------------------------------

# Si tu documentación necesita una versión mínima de Sphinx, ponla aquí.
#needs_sphinx = '1.0'

# Aquí añade cualquier nombre de módulo de extensión Sphinx, como
# cadenas. Pueden ser extensiones que vienen con Sphinx (llamadas
# 'sphinx.ext.'') o las tuyas personalizadas.
extensions = ['sphinxcontrib.phpdomain', 'configurationblock']

# Aquí, añade cualesquier ruta que contenga plantillas, relativa a este
# directorio.
templates_path = ['_templates']

# El sufijo de los archivos fuente.
source_suffix = '.rst'

# La codificación de los archivos fuente.
#source_encoding = 'utf-8-sig'

# El árbol maestro de temas.
master_doc = 'index'

# Información general sobre el proyecto.
project = u'Twig'
copyright = u'2011-2012, Traducido por Nacho Pacheco'

# Información de la versión del proyecto que estás documentando, actúa
# como reemplazo para |version| y |release|, además se utiliza en varias
# otras partes mientras se construyen los documentos.
#
# La versión X.Y corta.
version = '1.9'
# La versión completa, incluyendo etiquetas alpha/beta/rc.
release = '1.9.0'

# El idioma para el contenido autogenerado por Sphinx. Consulta la
# documentación para una lista de idiomas apoyados.
language = 'es'

# Hay dos opciones para reemplazar |today|: bien, fijas today a algún
# valor distinto de false, luego usas esto:
# today = ''
# si no, se utiliza today_fmt como el formato para una llamada a
# strftime.
today_fmt = '%B %d, %Y'

# Lista de patrones, relativos al directorio fuente, que coinciden con
# archivos y directorios a ignorar cuando se busquen archivos fuente.
exclude_patterns = ['_build']

# El rol reST predeterminado (usado para el marcado: `text`) para usar
# en todos los documentos.
#default_role = None

# Si es true, debes añadir '()' a :func: etc. texto de referencias
# cruzadas.
#add_function_parentheses = True

# Si es true, el nombre del módulo actual será prefijado a todos los
# títulos descriptivos de unidades tal cómo .. function::).
#add_module_names = True

# Si es true, las directivas sectionauthor y moduleauthor se mostrarán
# en el resultado. Estas, de manera predeterminada se omiten.
#show_authors = False

# El nombre del estilo de Pygments (resaltado de sintaxis) a usar.
pygments_style = 'native'

# El lenguaje predefinido a resaltar
highlight_language = 'php'

# Una lista de prefijos ignorados para ordenar el módulo index.
#modindex_common_prefix = []


# -- Opciones para salida HTML -----------------------------------------

# El tema a usar para las página HTML y HTML de ayuda.  Ve la
# documentación para una lista de temas integrados.
html_theme = 'tnp'

# Opciones del tema son específicas al tema y personalizan más la
# apariencia de un tema.  Para ver una lista de opciones disponibles
# para cada tema, consulta la documentación.
#html_theme_options = {}


# Aquí añade cualquier ruta que contenga temas personalizados, relativa
# a este directorio.
html_theme_path = ['../../../../../../_themes']

# El nombre para este conjunto de documentos Sphinx.  Si no existe, el
# predefinido es: "<proyecto> v<ersión> documentation".
html_title = u'Manual de Twig en Español'

# Un título corto para la barra de navegación.  Por omisión es el mismo
# que html_title.
html_short_title = u'Twig en Español'

# El nombre del archivo de imagen (relativo a este directorio) para
# colocarlo en la parte superior de la barra lateral.
#html_logo = None

# El nombre de un archivo de imagen (la ruta estática) a usar como
# ``favicon`` de los documentos.  Este archivo debe ser un icono de
# Windows (.ico) de 16x16 o 32x32 píxeles.
html_favicon = 'icotnp.ico'

# Aquí agrega cualquier ruta que contenga archivos estáticos
# personalizados (tal como hojas de estilo), relativas a este
# directorio. Estas, más adelante, se copian junto a los archivos
# estáticos integrados, por tanto un archivo llamado "default.css"
# sustituirá al "default.css" integrado.
html_static_path = ['../../../../../../tnp/_static']

# Si no es '', en la parte baja de cada página se inserta un mensaje
# 'Actualizado por última vez el:' usando el formato strftime
# proporcionado.
html_last_updated_fmt = '%b %d, %Y'

# Si es true, utilizará SmartyPants para convertir comillas y guiones a
# las entidades tipográficamente correctas.
#html_use_smartypants = True

# Plantilla personalizada para la barra lateral, asocia nombres de
# documento a nombres de plantilla.
#html_sidebars = {}

# Plantillas adicionales que reproducirán páginas, asocian nombres de
# página a nombres de plantilla.
#html_additional_pages = {}

# Si es 'false', no se genera el módulo index.
#html_domain_indices = True

# Si es 'false', no se genera índice.
#html_use_index = True

# Si es 'true', el índice se divide en páginas individuales para cada
# letra.
#html_split_index = False

# Si es true, se añaden enlaces a los archivos reST fuente en las
# páginas.
html_show_sourcelink = False

# Si es true, muestra "Creado usando Sphinx" en el pié de la página
# HTML. El predeterminado es True.
#html_show_sphinx = True

# Si es true, muestra "(C) Copyright ..." en el pié de la página
# HTML. El predeterminado es True.
#html_show_copyright = True

# Si es 'true', se emite un archivo de descripción OpenSearch, y todas
# las páginas contendrán una etiqueta <link> refiriéndose a él.  El
# valor de esta opción debe ser la URL base desde la cual se sirve el
# código HTML final.
#html_use_opensearch = ''

# Este es el sufijo del nombre de archivo para los archivos HTML (por
# ejemplo ".xhtml").
#html_file_suffix = None

# Nombre base para el archivo de salida producido por el constructor de
# ayuda HTML.
htmlhelp_basename = 'Twig-es'


# -- Opciones para la salida LaTeX -------------------------------------

latex_elements = {
    # El tamaño del papel ('letter' o 'a4').
    'papersize': 'letterpaper',

    # El tamaño del tipo de letra ('10pt', '11pt' o '12pt').
    'pointsize': '10pt',

    # Cosas adicionales para el preambulo LaTeX.
    #'preamble': '',
}

# Agrupa el árbol del documento en archivos LaTeX. Lista de tuplas
# (archivo fuente inicial, nombre del destino, título, autor,
# documentclass [howto/manual]).
latex_documents = [
  ('index', 'twig-es.tex', u'Twig-es',
   u'Traducido por Nacho Pacheco', 'manual'),
]

# El nombre de un archivo de imagen (relativo a este directorio) para
# colocarlo en la parte superior de la página del título.
#latex_logo = None

# Para documentos "manual", si esta es 'true', entonces las cabeceras de
# nivel superior son partes, no capítulos.
#latex_use_parts = False
latex_use_parts = True

# Si es 'true', muestra las referencias a página después de los enlaces
# internos.
#latex_show_pagerefs = False
latex_show_pagerefs = True

# Si es 'true', muestra las direcciones URL después de los enlaces
# externos.
#latex_show_urls = False

# Documentos a anexar como apéndices a todos los manuales.
#latex_appendices = ['glossary']

# Si es 'false', no se genera el módulo index.
#latex_domain_indices = True


# -- Opciones para las páginas del manual producidas -------------------

# Una entrada por página del manual. Lista de tuplas (archivo fuente
# inicial, nombre, descripción, autor, sección del manual).
man_pages = [
    ('index', 'twig-es', u'Twig-es',
     [u'Traducido por Nacho Pacheco'], 1)
]

# Si es 'true', muestra las direcciones URL despu´´es de los enlaces
# exyternos.
#man_show_urls = False


# -- Opciones para la salida Texinfo -----------------------------------

# Agrupa la estructura del documento en archivos Texinfo. Lista de
# tuplas (archivo fuente inicial, nombre destino, título, autor, entrada
# en el menú dir, descripción, categoría)

texinfo_documents = [
  ('index', 'Twig-es', u'Manual de Twig',
   u'Traducido por Nacho Pacheco', 'Twig-es',
   'Traducción al Español del Manual de Twig.',
   'Miscellaneous'),
]

# Documentos a añadir como a´péndice a todos los manuales.
#texinfo_appendices = []

# Si es 'false', no se generan índices de módulos.
#texinfo_domain_indices = True

# Cómo mostrar las direcciones URL: 'footnote', 'no', o 'inline'.
#texinfo_show_urls = 'footnote'
