<?php
/**
 * Arranque común para las páginas de ejemplo:
 * usa vendor/autoload.php si existe, o los archivos directamente.
 */
$autoload = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoload)) {
    require $autoload;
}

require_once __DIR__ . '/../src/BulmaCSS.php';
require_once __DIR__ . '/demo-htmltag.php';
