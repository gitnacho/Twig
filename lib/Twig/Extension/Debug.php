<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2011 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */
class Twig_Extension_Debug extends Twig_Extension
{
    /**
     * Devuelve una lista de funciones globales para añadirla a la lista existente.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        // dump is safe if var_dump is overriden by xdebug
        $isDumpOutputHtmlSafe = extension_loaded('xdebug') && (false === get_cfg_var('xdebug.overload_var_dump') || get_cfg_var('xdebug.overload_var_dump')) && get_cfg_var('html_errors');

        return array(
            'dump' => new Twig_Function_Function('twig_var_dump', array('is_safe' => $isDumpOutputHtmlSafe ? array('html') : array(), 'needs_context' => true, 'needs_environment' => true)),
        );
    }

    /**
     * Devuelve el nombre de la extensión.
     *
     * @return string El nombre de la extensión
     */
    public function getName()
    {
        return 'debug';
    }
}

function twig_var_dump(Twig_Environment $env, $context)
{
    if (!$env->isDebug()) {
        return;
    }

    ob_start();

    $count = func_num_args();
    if (2 === $count) {
        $vars = array();
        foreach ($context as $key => $value) {
            if (!$value instanceof Twig_Template) {
                $vars[$key] = $value;
            }
        }

        var_dump($vars);
    } else {
        for ($i = 2; $i < $count; $i++) {
            var_dump(func_get_arg($i));
        }
    }

    return ob_get_clean();
}
