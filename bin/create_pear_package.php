<?php

if (!isset($argv[1]))
{
    die('Debes proporcionar la versión(1.0.0)');
}

if (!isset($argv[2]))
{
    die('Debes proporcionar la estabilidad (alfa, beta o estable)');
}

$context = array(
    'date'          => date('Y-m-d'),
    'time'          => date('H:m:00'),
    'version'       => $argv[1],
    'api_version'   => $argv[1],
    'stability'     => $argv[2],
    'api_stability' => $argv[2],
);

$context['files'] = '';
$path = realpath(dirname(__FILE__).'/../lib/Twig');
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
    if (preg_match('/\.php$/', $file))
    {
        $name = str_replace($path.'/', '', $file);
        $context['files'] .= '        <file install-as="Twig/'.$name.'" name="'.$name.'" role="php" />'."\n";
    }
}

$template = file_get_contents(dirname(__FILE__).'/../package.xml.tpl');
$content = preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', 'replace_parameters', $template);
file_put_contents(dirname(__FILE__).'/../package.xml', $content);

function replace_parameters($matches)
{
    global $context;

    return isset($context[$matches[1]]) ? $context[$matches[1]] : null;
}
