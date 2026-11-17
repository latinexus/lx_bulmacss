<?php
namespace Latinexus\Html {
    class HtmlTag {
        public function blk($c = "", array $o = [], $w = "div") { return "<$w " . $this->p($o) . ">$c</$w>"; }
        public function noBlk(array $o = [], $w = "input") { return "<$w " . $this->p($o) . " />"; }
        private function p($o) { $r = []; foreach ($o as $k => $v) { $r[] = (in_array($k, ['required','readonly','disabled','checked']) || $v === '') ? "$k" : "$k=\"$v\""; } return implode(" ", $r); }
    }
}
namespace Lib\Grids {
    require __DIR__ . '/../src/GridBulma.php';
    $g = new GridBulma();
    $cases = [
        $g->bul_input("Nombre", "nombre"),
        $g->bul_input("Edad", "edad", ["e" => ["type" => "number", "min" => 1, "required" => true, "iconLeft" => "user"], "w" => ["is-inline"]]),
        $g->bul_select("Pais", "pais", [1 => "Ecuador", 2 => "Peru"], ["e" => ["selected" => "2"]]),
        $g->bul_textarea("Bio", "bio"),
        $g->bul_check("Acepto", "acepto", ["e" => ["checked" => true]]),
        $g->bul_check("Nada", "nada"),
        $g->bul_radio("Si", "x"),
        $g->bul_progress(40, "is-primary"),
        $g->bul_button("Guardar", ["e" => ["icono" => "save", "extra" => 'onclick="go()" data-x="1"']]),
        $g->bul_table([[1, 2]], ["A", "B"]),
        $g->bul_card("Titulo", "Texto"),
        $g->col("a"), $g->row("b"),
    ];
    echo implode(PHP_EOL, $cases), PHP_EOL;
}
