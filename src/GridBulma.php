<?php
/**
 * Creator: Eric Larrea
 * E-mail: elapez@gmail.com
 * From: www.latinex.us
 * Date: 17/11/26
 * Time: 12:28 PM
 * Proyecto: lx_easyca.lat
 */

namespace Lib\Grids;

use Latinexus\Html\HtmlTag;

/**
 * Creator: Eric Larrea
 * E-mail: elapez@gmail.com
 * From: www.latinex.us
 * Date: 17/11/26
 * Time: 17:50 H
 * Class GridBulma
 * @package Lib\Grids
 *
 * Lo que se quiere con esta clase es crear desde PHP bloques HTML con estilos de Bulma CSS incluidos
 *
 * $bul = new GridBulma()
 *
 * echo $bul->input("Nombre","nombre")
 *
 * debe generar el siguiente HTML
 * `<div class="field">
 * <label class="label">Nombre</label>
 * <div class="control">
 * <input class="input" type="text"  name="nombre" />
 * </div>
 * </div>`
 *
 */
class GridBulma
{
    public function __construct()
    {

    }

    /**
     * Devuelve el atributo class completo a partir de un array o string
     */
    private function wrapperClass(string $base, $w): string
    {
        if (empty($w)) {
            return $base;
        }

        $extra = is_array($w) ? implode(" ", $w) : (string) $w;

        return trim($base . " " . $extra);
    }

    /**
     * Normaliza el valor de "checked" (bool, "checked", 1, etc.)
     */
    private function isChecked($checked): bool
    {
        return in_array($checked, [true, 1, "1", "checked", "true"], true);
    }

    private function resolveVistaUrl(array $datos = []): string
    {
        if (defined('E_URL') && E_URL !== '') {
            $baseRaw = (string) E_URL;
        } else {
            $scheme = 'http';
            if (!empty($_SERVER['REQUEST_SCHEME'])) {
                $scheme = $_SERVER['REQUEST_SCHEME'];
            } elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
                $scheme = 'https';
            }
            $host = $_SERVER['SERVER_NAME'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
            $baseRaw = $scheme . '://' . $host . '/';
        }

        $baseRaw = trim($baseRaw);
        $base = $baseRaw === '' ? '' : rtrim($baseRaw, '/') . '/';

        if (array_key_exists('vista', $datos) && $datos['vista'] !== '') {
            $rawVista = (string) $datos['vista'];
        } elseif (defined('E_VIEW') && E_VIEW !== '') {
            $rawVista = (string) E_VIEW;
        } else {
            $rawVista = '';
        }

        $rawVista = trim($rawVista);

        if ($rawVista !== '' && preg_match('#^(https?:)?//#i', $rawVista)) {
            return $rawVista;
        }

        $vista = $rawVista === '' ? '' : preg_replace('/[^A-Za-z0-9_\/.-]/', '', ltrim($rawVista, '/'));

        if ($base === '' && $vista === '') {
            return '';
        }

        if ($base === '') {
            return $vista;
        }

        if ($vista === '') {
            return rtrim($base, '/');
        }

        return $base . $vista;
    }

    private function altImg(string $argumento = ""): string
    {
        if (!empty($argumento)) {
            $argumentoNew = strtr($argumento, [
                chr(10) => "",
                chr(13) => ""
            ]);
            return ' alt="' . htmlentities($argumentoNew, ENT_QUOTES) . '" title="' . htmlentities($argumentoNew, ENT_QUOTES) . '" ';
        }
        return "";
    }

    public function col($contenido, $params = ["class" => [], "style" => "", "id" => ""], $tag = "div")
    {
        $b = new HtmlTag();
        $attrs = [];

        if (!empty($params["class"])) {
            if (is_array($params["class"])) {
                $attrs["class"] = "column " . implode(" ", $params["class"]);
            } else {
                $attrs["class"] = "column " . $params["class"];
            }
        } else {
            $attrs["class"] = "column";
        }

        if (!empty($params["style"])) {
            $attrs["style"] = $params["style"];
        }

        if (!empty($params["id"])) {
            $attrs["id"] = $params["id"];
        }

        return $b->blk($contenido, $attrs, $tag);
    }

    public function row($contenido, $params = ["class" => [], "style" => "", "id" => ""], $tag = "div")
    {
        $b = new HtmlTag();
        $attrs = [];

        if (!empty($params["class"])) {
            if (is_array($params["class"])) {
                $attrs["class"] = "columns " . implode(" ", $params["class"]);
            } else {
                $attrs["class"] = "columns " . $params["class"];
            }
        } else {
            $attrs["class"] = "columns";
        }

        if (!empty($params["style"])) {
            $attrs["style"] = $params["style"];
        }

        if (!empty($params["id"])) {
            $attrs["id"] = $params["id"];
        }

        return $b->blk($contenido, $attrs, $tag);
    }

    /**
     * BULMA CSS ( INPUT )
     * Devuelve un input con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_input($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $opcionales = ["required", "readonly", "disabled"];
        $arregloOpciones = [];

        $type = $e["type"] ?? "text";
        $value = $e["value"] ?? "";
        $id = $e["id"] ?? $name;
        $clase = $e["class"] ?? "";
        $placeholder = $e["placeholder"] ?? "";
        $min = $e["min"] ?? "";
        $max = $e["max"] ?? "";
        $maxlength = $e["maxlength"] ?? "";
        $minlength = $e["minlength"] ?? "";
        $classRequired = $e["classR"] ?? "has-text-danger";
        $style = $e["style"] ?? "";
        $iconLeft = $e["iconLeft"] ?? "";
        $iconRight = $e["iconRight"] ?? "";
        $nowrap = $e["nowrap"] ?? false;

        foreach ($opcionales as $opt) {
            if (!empty($e[$opt])) {
                if ($opt == "required") {
                    $label .= ' <span class="' . $classRequired . '">*</span>';
                }
                $arregloOpciones[$opt] = "1";
            }
        }

        $clsCss = empty($clase) ? "input" : "input " . $clase;

        $arregloOpciones["id"] = $id;
        $arregloOpciones["name"] = $name;
        $arregloOpciones["class"] = $clsCss;
        $arregloOpciones["value"] = $value;
        $arregloOpciones["type"] = $type;
        $arregloOpciones["style"] = $style;

        if (!empty($placeholder)) {
            $arregloOpciones["placeholder"] = $placeholder;
        }

        if (!empty($minlength)) {
            $arregloOpciones["minlength"] = $minlength;
        }

        if (!empty($maxlength)) {
            $arregloOpciones["maxlength"] = $maxlength;
        }

        if (in_array($arregloOpciones["type"], ["number", "date", "datetime-local", "time", "range"], true)) {
            if (!empty($min)) {
                $arregloOpciones["min"] = $min;
            }
            if (!empty($max)) {
                $arregloOpciones["max"] = $max;
            }
        }

        $inputHtml = $b->noBlk($arregloOpciones, "input");

        if (!empty($iconLeft) || !empty($iconRight)) {
            $controlContent = "";
            if (!empty($iconLeft)) {
                $controlContent .= '<span class="icon is-small is-left"><i class="fas fa-' . $iconLeft . '"></i></span>';
            }
            $controlContent .= $inputHtml;
            if (!empty($iconRight)) {
                $controlContent .= '<span class="icon is-small is-right"><i class="fas fa-' . $iconRight . '"></i></span>';
            }
            $retorno = $b->blk($controlContent, ["class" => "control has-icons"]);
        } else {
            $retorno = $b->blk($inputHtml, ["class" => "control"]);
        }

        $retorno = $b->blk($label, ["class" => "label"], "label") . $retorno;

        if ($nowrap) {
            return $retorno;
        }

        return $b->blk($retorno, ["class" => $this->wrapperClass("field", $w)]);
    }

    /**
     * BULMA CSS ( SELECT )
     * Devuelve un select con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $contenido
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_select($label, $name, $contenido, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $id = $e["id"] ?? $name;
        $selected = $e["selected"] ?? "";
        $placeholder = $e["placeholder"] ?? "Seleccionar";
        $clase = $e["class"] ?? "";
        $style = $e["style"] ?? "";
        $nowrap = $e["nowrap"] ?? false;

        $clsCss = empty($clase) ? "input" : "input " . $clase;
        $selectAttrs = ["name" => $name, "id" => $id, "class" => $clsCss];
        if (!empty($style)) {
            $selectAttrs["style"] = $style;
        }
        if (!empty($e["required"])) {
            $selectAttrs["required"] = "1";
        }
        if (!empty($e["disabled"])) {
            $selectAttrs["disabled"] = "1";
        }
        if (!empty($e["extra"])) {
            $selectAttrs = array_merge($selectAttrs, $this->parseExtraAttributes($e["extra"]));
        }

        $opciones = "";

        if (is_array($contenido) && !empty($contenido)) {
            if ($selected === "") {
                $opciones .= '<option value="" disabled selected>' . $placeholder . '</option>' . PHP_EOL;
            }

            foreach ($contenido as $id_val => $val) {
                $selectedAttr = ((string) $selected === (string) $id_val) ? ' selected' : '';
                $opciones .= '<option value="' . htmlentities((string) $id_val, ENT_QUOTES) . '"' . $selectedAttr . '>' . $val . '</option>' . PHP_EOL;
            }
        } else {
            $opciones .= '<option value="" disabled selected>Sin elementos</option>' . PHP_EOL;
        }

        $retorno = $b->blk($opciones, $selectAttrs, "select");
        $retorno = $b->blk($retorno, ["class" => "control"]);
        $retorno = $b->blk($label, ["class" => "label"], "label") . $retorno;

        if ($nowrap) {
            return $retorno;
        }

        return $b->blk($retorno, ["class" => $this->wrapperClass("field", $w)]);
    }

    /**
     * BULMA CSS ( TEXTAREA )
     * Devuelve un textarea con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_textarea($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $value = $e["value"] ?? "";
        $id = $e["id"] ?? $name;
        $clase = $e["class"] ?? "";
        $placeholder = $e["placeholder"] ?? "";
        $nowrap = $e["nowrap"] ?? false;

        $clsCss = empty($clase) ? "textarea" : "textarea " . $clase;

        $textareaAttrs = ["id" => $id, "name" => $name, "class" => $clsCss];
        if (!empty($placeholder)) {
            $textareaAttrs["placeholder"] = $placeholder;
        }
        if (!empty($e["rows"])) {
            $textareaAttrs["rows"] = $e["rows"];
        }
        foreach (["required", "readonly", "disabled"] as $opt) {
            if (!empty($e[$opt])) {
                $textareaAttrs[$opt] = "1";
            }
        }

        $retorno = $b->blk($value, $textareaAttrs, "textarea");
        $retorno = $b->blk($retorno, ["class" => "control"]);
        $retorno = $b->blk($label, ["class" => "label"], "label") . $retorno;

        if ($nowrap) {
            return $retorno;
        }

        return $b->blk($retorno, ["class" => $this->wrapperClass("field", $w)]);
    }

    /**
     * BULMA CSS ( CHECKBOX )
     * Devuelve un checkbox con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_check($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $value = $e["value"] ?? 1;
        $id = $e["id"] ?? $name;
        $checked = $e["checked"] ?? false;
        $nowrap = $e["nowrap"] ?? false;

        $params = ["name" => $name, "id" => $id, "type" => "checkbox", "value" => $value];
        if ($this->isChecked($checked)) {
            $params["checked"] = "checked";
        }
        foreach (["required", "disabled"] as $opt) {
            if (!empty($e[$opt])) {
                $params[$opt] = "1";
            }
        }

        $inp = $b->noBlk($params, "input");
        $lab = $b->blk($inp . ' ' . $label, ["for" => $id], "label");

        $retorno = $b->blk($lab, ["class" => "control"]);

        if ($nowrap) {
            return $retorno;
        }

        return $b->blk($retorno, ["class" => $this->wrapperClass("field", $w)]);
    }

    /**
     * BULMA CSS ( RADIO )
     * Devuelve un radio button con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_radio($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $value = $e["value"] ?? "1";
        $id = $e["id"] ?? $name . uniqid();
        $checked = $e["checked"] ?? false;
        $nowrap = $e["nowrap"] ?? false;

        $params = ["name" => $name, "id" => $id, "type" => "radio", "value" => $value];
        if ($this->isChecked($checked)) {
            $params["checked"] = "checked";
        }
        foreach (["required", "disabled"] as $opt) {
            if (!empty($e[$opt])) {
                $params[$opt] = "1";
            }
        }

        $inp = $b->noBlk($params, "input");
        $lab = $b->blk($inp . ' ' . $label, ["for" => $id], "label");

        $retorno = $b->blk($lab, ["class" => "control"]);

        if ($nowrap) {
            return $retorno;
        }

        return $b->blk($retorno, ["class" => $this->wrapperClass("field", $w)]);
    }

    /**
     * BULMA CSS ( CARD )
     * Devuelve una card con formato para Bulma CSS
     *
     * @param $titulo
     * @param $contenido
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_card($titulo, $contenido, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $imagen = $e["imagen"] ?? "";
        $alt = $e["alt"] ?? "";

        $cardContent = "";

        if (!empty($imagen)) {
            $imgPath = $imagen;
            $imagenUrl = $this->resolveVistaUrl(['vista' => $imgPath]);
            $i = '<img src="' . $imagenUrl . '" ' . $this->altImg($alt) . ' />';
            $cardContent .= $b->blk($i, ["class" => "card-image"]);
        }

        if (!empty($titulo)) {
            $title = $b->blk($titulo, ["class" => "title is-4"], "p");
            $cardContent .= $title;
        }

        $cardContent .= $b->blk($contenido, ["class" => "content"]);

        $cardClass = !empty($w) ? "card " . implode(" ", $w) : "card";
        return $b->blk($cardContent, ["class" => $cardClass]);
    }

    /**
     * BULMA CSS ( BUTTON )
     * Devuelve un botón con formato para Bulma CSS
     *
     * @param $texto
     * @param array $datos ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_button($texto, $datos = [])
    {
        $b = new HtmlTag();
        $e = $datos["e"] ?? [];
        $w = $datos["w"] ?? [];

        $tipo = $e["tipo"] ?? "is-primary";
        $tamanio = $e["tamanio"] ?? "";
        $estado = $e["estado"] ?? "";
        $icono = $e["icono"] ?? "";
        $link = $e["link"] ?? "";
        $extra = $e["extra"] ?? "";

        $clases = ["button"];

        if (!empty($tipo)) {
            $clases[] = $tipo;
        }

        if (!empty($tamanio)) {
            $clases[] = $tamanio;
        }

        if (!empty($estado)) {
            $clases[] = $estado;
        }

        $contenido = "";

        if (!empty($icono)) {
            $contenido .= '<span class="icon"><i class="fas fa-' . $icono . '"></i></span>';
        }

        $contenido .= '<span>' . $texto . '</span>';

        $attrs = ["class" => implode(" ", $clases)];

        if (!empty($extra)) {
            $attrs = array_merge($attrs, $this->parseExtraAttributes($extra));
        }

        if (!empty($link)) {
            return $b->blk($contenido, array_merge($attrs, ["href" => $link]), "a");
        }

        return $b->blk($contenido, $attrs, "button");
    }

    /**
     * BULMA CSS ( NOTIFICATION )
     * Devuelve una notificación con formato para Bulma CSS
     *
     * @param $mensaje
     * @param string $tipo
     * @param bool $dismissible
     * @return string
     */
    public function bul_notification($mensaje, $tipo = "is-info", $dismissible = false)
    {
        $b = new HtmlTag();
        $clases = ["notification", $tipo];

        $contenido = $mensaje;

        if ($dismissible) {
            $contenido .= '<button class="delete"></button>';
        }

        return $b->blk($contenido, ["class" => implode(" ", $clases)]);
    }

    /**
     * BULMA CSS ( BOX )
     * Devuelve una caja con formato para Bulma CSS
     *
     * @param $contenido
     * @return string
     */
    public function bul_box($contenido)
    {
        $b = new HtmlTag();
        return $b->blk($contenido, ["class" => "box"]);
    }

    /**
     * BULMA CSS ( TAG )
     * Devuelve un tag con formato para Bulma CSS
     *
     * @param $texto
     * @param string $tipo
     * @param string $tamanio
     * @param bool $rounded
     * @param bool $delete
     * @return string
     */
    public function bul_tag($texto, $tipo = "is-info", $tamanio = "", $rounded = false, $delete = false)
    {
        $b = new HtmlTag();
        $clases = ["tag", $tipo];

        if (!empty($tamanio)) {
            $clases[] = $tamanio;
        }

        if ($rounded) {
            $clases[] = "is-rounded";
        }

        if ($delete) {
            $clases[] = "is-delete";
        }

        return $b->blk($texto, ["class" => implode(" ", $clases)], "span");
    }

    /**
     * BULMA CSS ( PROGRESS BAR )
     * Devuelve una barra de progreso con formato para Bulma CSS
     *
     * @param $valor
     * @param string $tipo
     * @param string $tamanio
     * @return string
     */
    public function bul_progress($valor, $tipo = "", $tamanio = "")
    {
        $b = new HtmlTag();
        $clases = ["progress"];

        if (!empty($tipo)) {
            $clases[] = $tipo;
        }

        if (!empty($tamanio)) {
            $clases[] = $tamanio;
        }

        $progress = $b->blk("", ["class" => implode(" ", $clases), "value" => $valor, "max" => "100"], "progress");

        return $progress;
    }

    /**
     * BULMA CSS ( TABLE )
     * Devuelve una tabla con formato para Bulma CSS
     *
     * @param $filas
     * @param array $encabezados
     * @param bool $striped
     * @param bool $hoverable
     * @param bool $fullwidth
     * @return string
     */
    public function bul_table($filas, $encabezados = [], $striped = true, $hoverable = true, $fullwidth = true)
    {
        $b = new HtmlTag();
        $clases = ["table"];

        if ($striped) {
            $clases[] = "is-striped";
        }

        if ($hoverable) {
            $clases[] = "is-hoverable";
        }

        if ($fullwidth) {
            $clases[] = "is-fullwidth";
        }

        $thead = "";
        if (!empty($encabezados)) {
            $thRow = "";
            foreach ($encabezados as $th) {
                $thRow .= $b->blk($th, [], "th");
            }
            $thead = $b->blk($thRow, [], "tr");
            $thead = $b->blk($thead, [], "thead");
        }

        $tbody = "";
        foreach ($filas as $fila) {
            $tr = "";
            if (is_array($fila)) {
                foreach ($fila as $celda) {
                    $tr .= $b->blk($celda, [], "td");
                }
            } else {
                $tr .= $b->blk($fila, [], "td");
            }
            $tbody .= $b->blk($tr, [], "tr");
        }
        $tbody = $b->blk($tbody, [], "tbody");

        $table = $thead . $tbody;

        return $b->blk($table, ["class" => implode(" ", $clases)]);
    }

    /**
     * Helper para parsear atributos extra en formato string
     */
    private function parseExtraAttributes($extra)
    {
        $attrs = [];
        if (preg_match_all('/([\w-]+)="([^"]*)"/', (string) $extra, $matches)) {
            foreach ($matches[1] as $i => $attr) {
                $attrs[$attr] = $matches[2][$i];
            }
        }
        return $attrs;
    }


}