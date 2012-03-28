<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Loads a template from an array.
 *
 * When using this loader with a cache mechanism, you should know that a new cache
 * key is generated each time a template content "changes" (the cache key being the
 * source code of the template). If you don't want to see your cache grows out of
 * control, you need to take care of clearing the old cache file by yourself.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Loader_Array implements Twig_LoaderInterface
{
    protected $templates;

    /**
     * Constructor.
     *
     * @param array $templates An array of templates (keys are the names, and values are the source code)
     *
     * @see Twig_Loader
     */
    public function __construct(array $templates)
    {
        $this->templates = array();
        foreach ($templates as $name => $template) {
            $this->templates[$name] = $template;
        }
    }

    /**
     * Adds or overrides a template.
     *
     * @param string $name     The template name
     * @param string $template The template source
     */
    public function setTemplate($name, $template)
    {
        $this->templates[(string) $name] = $template;
    }

    /**
     * Obtiene el código fuente de una plantilla, del nombre dado.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The template source code
     */
    public function getSource($name)
    {
        $name = (string) $name;
        if (!isset($this->templates[$name])) {
            throw new Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
        }

        return $this->templates[$name];
    }

    /**
     * Obtiene la clave de la caché para usarla en un nombre de plantilla dado.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        $name = (string) $name;
        if (!isset($this->templates[$name])) {
            throw new Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
        }

        return $this->templates[$name];
    }

    /**
     * Devuelve true si la plantilla aún está fresca.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        $name = (string) $name;
        if (!isset($this->templates[$name])) {
            throw new Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
        }

        return true;
    }
}
