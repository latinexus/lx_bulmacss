<?php
/**
 * Fallback de latinexus/lx_htmltag para ver la demo sin `composer install`.
 */
namespace Latinexus\Html;

if (!class_exists(HtmlTag::class, false)) {
    class HtmlTag
    {
        private const BOOLEAN = ["required", "readonly", "disabled", "checked", "selected"];

        public function blk($contenido = "", array $opt = [], $envoltura = "div")
        {
            return "<" . $envoltura . " " . $this->params($opt) . ">" . $contenido . "</" . $envoltura . ">";
        }

        public function noBlk(array $opt = [], $envoltura = "input")
        {
            return "<" . $envoltura . " " . $this->params($opt) . " />";
        }

        private function params(array $opt): string
        {
            $blk = [];

            foreach ($opt as $opId => $op) {
                if (strtolower($opId) === "id") {
                    $blk["id"] = 'id="' . $op . '"';
                } elseif (in_array($opId, self::BOOLEAN, true)) {
                    $blk[$opId] = $opId;
                } elseif ($op !== "" && $op !== null) {
                    $blk[$opId] = $opId . '="' . (string) $op . '"';
                }
            }

            if (!isset($blk["id"])) {
                $blk["id"] = 'id="id_' . uniqid() . '"';
            }

            return implode(" ", array_values($blk));
        }
    }
}
