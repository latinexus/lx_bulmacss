<?php
require __DIR__ . '/bootstrap.php';

use Lib\Grids\GridBulma;

$g = new GridBulma();

$contenido = $g->bul_notification("Todos los componentes se ven con estilos porque page()/cssLinks() cargan Bulma desde el CDN.", "is-success", true);

$contenido .= $g->row(
    $g->col($g->bul_input("Nombre", "nombre", [["required" => true, "iconLeft" => "user", "placeholder" => "Tu nombre"]]))
    . $g->col($g->bul_input("Correo", "correo", ["e" => ["type" => "email", "iconLeft" => "envelope"]]))
);

$contenido .= $g->row(
    $g->col($g->bul_select("País", "pais", [1 => "Ecuador", 2 => "Perú", 3 => "Colombia"], ["e" => ["selected" => "1"]]))
    . $g->col($g->bul_input("Nacimiento", "nacimiento", ["e" => ["type" => "date"]]))
);

$contenido .= $g->bul_textarea("Comentario", "comentario", ["e" => ["placeholder" => "Escribe algo...", "rows" => 3]]);

$contenido .= $g->bul_check("Acepto los términos", "acepto", ["e" => ["checked" => true]]);
$contenido .= $g->bul_radio("Opción A", "opcion", ["e" => ["id" => "opA", "value" => "A", "checked" => true]]);
$contenido .= $g->bul_radio("Opción B", "opcion", ["e" => ["id" => "opB", "value" => "B"]]);

$contenido .= $g->row(
    $g->col(
        $g->bul_button("Guardar", ["e" => ["icono" => "floppy-disk"]])
        . " "
        . $g->bul_button("Cancelar", ["e" => ["tipo" => "is-light"]])
    )
);

$contenido .= $g->bul_tag("etiqueta", "is-link", "is-medium", true);
$contenido .= " " . $g->bul_progress(60, "is-primary");

$contenido .= $g->bul_table([["Ana", 28], ["Luis", 34]], ["Nombre", "Edad"]);

$contenido .= $g->bul_card(
    "Tarjeta",
    "Contenido de la card generada desde PHP con clases de Bulma.",
    ["w" => ["mt-5"]]
);

echo $g->page($contenido, "Demo lx_bulmacss");






