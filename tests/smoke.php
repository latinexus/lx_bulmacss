<?php
/**
 * Página de muestra: todos los componentes con los estilos de Bulma cargados.
 * Uso: http://localhost/cp_bulmacss/tests/smoke.php
 */
require __DIR__ . '/../demo/bootstrap.php';

use Lib\Grids\GridBulma;

$g = new GridBulma();

$form = $g->bul_input("Nombre", "nombre", [[
        "required" => true,
        "iconLeft" => "user",
        "placeholder" => "Tu nombre",
        "color" => "is-success",
        "iconRight" => "check",
        "help" => "El nombre está disponible",
        "helpColor" => "is-success",
    ]])
    . $g->bul_input("Edad", "edad", [["type" => "number", "min" => 1, "required" => true, "iconLeft" => "user"], ["class" => "is-inline"]])
    . $g->bul_select("Pais", "pais", [1 => "Ecuador", 2 => "Peru"], [["selected" => "2"]])
    . $g->bul_textarea("Bio", "bio")
    . $g->bul_check("Acepto", "acepto", [["checked" => true]])
    . $g->bul_check("Nada", "nada")
    . $g->bul_radio("Si", "x")
    . $g->bul_button("Guardar", [["icono" => "save", "extra" => 'onclick="return false;" data-x="1"']]);

$contenido = $g->bul_notification("Página de muestra de GridBulma con Bulma CSS cargado.", "is-info", true)
    . $g->row(
        $g->col($g->bul_card("Formulario", $form, [["nowrap" => true]]), ["class" => ["is-half"]])
        . $g->col(
            $g->bul_progress(40, "is-primary")
            . "<p class=\"mt-4\">" . $g->bul_tag("tag", "is-link", "is-medium", true) . "</p>"
            . $g->bul_box("Box de ejemplo")
            . $g->bul_table([["Ana", 28], ["Luis", 34]], ["Nombre", "Edad"])
        )
    );

echo $g->page($contenido, "GridBulma - smoke test");
