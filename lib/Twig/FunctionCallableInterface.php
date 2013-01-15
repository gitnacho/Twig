<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2012 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Represents a callable template function.
 *
 * Use Twig_SimpleFunction instead.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_FunctionCallableInterface
{
    public function getCallable();
}
