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

    /** Versión de Bulma cargada por defecto por cssLinks() */
    public const BULMA_VERSION = "1.0.4";

    /**
     * BULMA CSS ( CSS / FONTAWESOME )
     * Devuelve los <link> necesarios para que los bloques tengan estilos.
     * Debe imprimirse dentro del <head> de la página.
     *
     * @param string $bulmaUrl URL del CSS de Bulma
     * @param bool $fontAwesome Incluir Font Awesome (usado por iconLeft/iconRight/icono)
     * @return string
     */
    public function cssLinks(
        string $bulmaUrl = "https://cdn.jsdelivr.net/npm/bulma@" . self::BULMA_VERSION . "/css/bulma.min.css",
        bool $fontAwesome = true
    ): string {
        $links = '<link rel="stylesheet" href="' . $bulmaUrl . '">' . PHP_EOL;

        if ($fontAwesome) {
            $links .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">' . PHP_EOL;
        }

        return $links;
    }

    /**
     * BULMA CSS ( DOCUMENTO )
     * Envuelve un contenido en un documento HTML completo con los estilos ya cargados
     *
     * @param string $contenido HTML generado por los helpers
     * @param string $titulo
     * @param string $bulmaUrl
     * @param bool $fontAwesome
     * @return string
     */
    public function page(
        string $contenido,
        string $titulo = "Bulma",
        string $bulmaUrl = "https://cdn.jsdelivr.net/npm/bulma@" . self::BULMA_VERSION . "/css/bulma.min.css",
        bool $fontAwesome = true
    ): string {
        $head = '<meta charset="utf-8">' . PHP_EOL
            . '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL
            . '<title>' . htmlentities($titulo, ENT_QUOTES) . '</title>' . PHP_EOL
            . $this->cssLinks($bulmaUrl, $fontAwesome);

        $body = '<div class="section"><div class="container">' . PHP_EOL . $contenido . PHP_EOL . '</div></div>';

        return '<!DOCTYPE html>' . PHP_EOL . '<html lang="es">' . PHP_EOL . '<head>' . PHP_EOL . $head . '</head>' . PHP_EOL
            . '<body>' . PHP_EOL . $body . PHP_EOL . '</body>' . PHP_EOL . '</html>' . PHP_EOL;
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
     * Normaliza el 3er parámetro de los helpers. Formatos aceptados:
     *  - ["e" => [], "w" => []]          formato original
     *  - [["e"], ["w"]]                  primer elemento = elemento, segundo = envoltura
     *  - [["opciones del elemento"]]     atajo cuando no se usa "nowrap"
     *
     * @return array ["e" => [], "w" => [], "nowrap" => bool]
     */
    private function resolveDatos($datos = []): array
    {
        $datos = is_array($datos) ? $datos : [];
        $esAtajo = !array_key_exists("e", $datos) && !array_key_exists("w", $datos);

        if ($esAtajo) {
            $e = is_array($datos[0] ?? null) ? $datos[0] : [];
            $w = $datos[1] ?? [];
        } else {
            $e = is_array($datos["e"] ?? null) ? $datos["e"] : [];
            $w = $datos["w"] ?? [];
        }

        $e = is_array($e) ? $e : [];
        $w = is_array($w) ? $w : [];

        return [
            "e" => $e,
            "w" => $w,
            "nowrap" => !empty($e["nowrap"]) || !empty($w["nowrap"]),
        ];
    }

    /**
     * Genera la etiqueta <label> de un campo
     */
    private function labelHtml(string $label): string
    {
        if ($label === "") {
            return "";
        }

        $b = new HtmlTag();

        return $b->blk($label, ["class" => "label"], "label");
    }

    /**
     * Genera el par de <span class="icon"> de los campos con iconos
     */
    private function iconHtml(string $posicion, string $icono): string
    {
        return '<span class="icon is-small is-' . $posicion . '"><i class="fas fa-' . $icono . '"></i></span>';
    }

    /**
     * Envuelve el elemento (label + control + help) en el contenedor.
     * Con "nowrap" devuelve solo el contenido.
     * Sin "w" el contenedor es "column" (ocupa todo el ancho disponible);
     * con "w" la clase es "field" más los modificadores indicados.
     */
    private function wrapField(string $contenido, array $info): string
    {
        if ($info["nowrap"]) {
            return $contenido;
        }

        $w = $info["w"];
        $class = empty($w) ? "column" : $this->wrapperClass("field", $w["class"] ?? $w);

        $attrs = ["class" => $class];

        if (!empty($w["style"])) {
            $attrs["style"] = $w["style"];
        }

        if (!empty($w["id"])) {
            $attrs["id"] = $w["id"];
        }

        $b = new HtmlTag();

        return $b->blk($contenido, $attrs);
    }

    /**
     * Genera la ayuda inferior del campo (<p class="help ...">)
     */
    private function helpHtml(string $help, string $clase): string
    {
        if ($help === "") {
            return "";
        }

        $b = new HtmlTag();

        return $b->blk($help, ["class" => trim("help " . $clase)], "p");
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
     * $g->bul_input("Nombre", "nombre", [["required" => true, "iconLeft" => "user", "placeholder" => "Tu nombre"]])
     *
     * Opciones del elemento ("e"):
     *  type, value, id, class, placeholder, min, max, minlength, maxlength,
     *  required, readonly, disabled, style, extra, nowrap,
     *  iconLeft, iconRight, color (is-success, is-danger, ...), help, helpColor,
     *  classR (color del asterisco de required)
     *
     * Opciones de la envoltura ("w"): class / style / id (por defecto "column")
     *
     * @param $label
     * @param $name
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_input($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];

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
        $color = $e["color"] ?? "";
        $help = (string) ($e["help"] ?? "");
        $helpColor = $e["helpColor"] ?? "";

        $required = !empty($e["required"]);

        if ($required) {
            $label .= ' <span class="' . $classRequired . '">*</span>';
        }

        $clases = ["input"];
        if (!empty($color)) {
            $clases[] = $color;
        }
        if (!empty($clase)) {
            $clases[] = $clase;
        }

        $attrs = [
            "id" => $id,
            "name" => $name,
            "class" => implode(" ", $clases),
            "type" => $type,
        ];

        if ($value !== "" && $value !== null) {
            $attrs["value"] = $value;
        }

        if (!empty($placeholder)) {
            $attrs["placeholder"] = $placeholder;
        }

        if (!empty($style)) {
            $attrs["style"] = $style;
        }

        if (!empty($e["extra"])) {
            $attrs = array_merge($attrs, $this->parseExtraAttributes($e["extra"]));
        }

        if (!empty($minlength)) {
            $attrs["minlength"] = $minlength;
        }

        if (!empty($maxlength)) {
            $attrs["maxlength"] = $maxlength;
        }

        if (in_array($type, ["number", "date", "datetime-local", "time", "range"], true)) {
            if (!empty($min)) {
                $attrs["min"] = $min;
            }
            if (!empty($max)) {
                $attrs["max"] = $max;
            }
        }

        foreach (["required", "readonly", "disabled"] as $opt) {
            if (!empty($e[$opt])) {
                $attrs[$opt] = "1";
            }
        }

        $inputHtml = $b->noBlk($attrs, "input");

        $controlClass = "control";
        if (!empty($iconLeft) || !empty($iconRight)) {
            $controlClass .= " has-icons-left has-icons-right";
        }

        $controlContent = $inputHtml;
        if (!empty($iconLeft)) {
            $controlContent .= $this->iconHtml("left", $iconLeft);
        }
        if (!empty($iconRight)) {
            $controlContent .= $this->iconHtml("right", $iconRight);
        }

        $retorno = $this->labelHtml($label)
            . $b->blk($controlContent, ["class" => $controlClass])
            . $this->helpHtml($help, $helpColor);

        return $this->wrapField($retorno, $info);
    }

    /**
     * BULMA CSS ( SELECT )
     * Devuelve un select con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $contenido [valor => texto]
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_select($label, $name, $contenido, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];

        $id = $e["id"] ?? $name;
        $selected = $e["selected"] ?? "";
        $placeholder = $e["placeholder"] ?? "Seleccionar";
        $clase = $e["class"] ?? "";
        $style = $e["style"] ?? "";
        $color = $e["color"] ?? "";
        $help = (string) ($e["help"] ?? "");
        $helpColor = $e["helpColor"] ?? "";

        $clases = ["input"];
        if (!empty($color)) {
            $clases[] = $color;
        }
        if (!empty($clase)) {
            $clases[] = $clase;
        }

        $selectAttrs = ["name" => $name, "id" => $id, "class" => implode(" ", $clases)];

        if (!empty($style)) {
            $selectAttrs["style"] = $style;
        }

        foreach (["required", "readonly", "disabled"] as $opt) {
            if (!empty($e[$opt])) {
                $selectAttrs[$opt] = "1";
            }
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

        $retorno = $this->labelHtml($label)
            . $b->blk($b->blk($opciones, $selectAttrs, "select"), ["class" => "control"])
            . $this->helpHtml($help, $helpColor);

        return $this->wrapField($retorno, $info);
    }

    /**
     * BULMA CSS ( TEXTAREA )
     * Devuelve un textarea con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_textarea($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];

        $value = $e["value"] ?? "";
        $id = $e["id"] ?? $name;
        $clase = $e["class"] ?? "";
        $placeholder = $e["placeholder"] ?? "";
        $color = $e["color"] ?? "";
        $help = (string) ($e["help"] ?? "");
        $helpColor = $e["helpColor"] ?? "";

        $clases = ["textarea"];
        if (!empty($color)) {
            $clases[] = $color;
        }
        if (!empty($clase)) {
            $clases[] = $clase;
        }

        $textareaAttrs = ["id" => $id, "name" => $name, "class" => implode(" ", $clases)];

        if (!empty($placeholder)) {
            $textareaAttrs["placeholder"] = $placeholder;
        }
        if (!empty($e["rows"])) {
            $textareaAttrs["rows"] = $e["rows"];
        }
        if (!empty($e["style"])) {
            $textareaAttrs["style"] = $e["style"];
        }
        foreach (["required", "readonly", "disabled"] as $opt) {
            if (!empty($e[$opt])) {
                $textareaAttrs[$opt] = "1";
            }
        }

        $retorno = $this->labelHtml($label)
            . $b->blk($b->blk($value, $textareaAttrs, "textarea"), ["class" => "control"])
            . $this->helpHtml($help, $helpColor);

        return $this->wrapField($retorno, $info);
    }

    /**
     * BULMA CSS ( CHECKBOX )
     * Devuelve un checkbox con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_check($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];

        $value = $e["value"] ?? 1;
        $id = $e["id"] ?? $name;
        $checked = $e["checked"] ?? false;

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

        return $this->wrapField($b->blk($lab, ["class" => "control"]), $info);
    }

    /**
     * BULMA CSS ( RADIO )
     * Devuelve un radio button con formato para Bulma CSS
     *
     * @param $label
     * @param $name
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_radio($label, $name, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];

        $value = $e["value"] ?? "1";
        $id = $e["id"] ?? $name . uniqid();
        $checked = $e["checked"] ?? false;

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

        return $this->wrapField($b->blk($lab, ["class" => "control"]), $info);
    }

    /**
     * BULMA CSS ( CARD )
     * Devuelve una card con formato para Bulma CSS
     *
     * @param $titulo
     * @param $contenido
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_card($titulo, $contenido, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];
        $w = $info["w"];

        $imagen = $e["imagen"] ?? "";
        $alt = $e["alt"] ?? "";

        $cardContent = "";

        if (!empty($imagen)) {
            $imagenUrl = $this->resolveVistaUrl(['vista' => $imagen]);
            $i = '<img src="' . $imagenUrl . '" ' . $this->altImg($alt) . ' />';
            $cardContent .= $b->blk($i, ["class" => "card-image"]);
        }

        $cuerpo = "";

        if (!empty($titulo)) {
            $cuerpo .= $b->blk($titulo, ["class" => "title is-4"], "p");
        }

        $cuerpo .= $b->blk($contenido, ["class" => "content"]);
        $cardContent .= $b->blk($cuerpo, ["class" => "card-content"]);

        if ($info["nowrap"]) {
            return $cardContent;
        }

        $cardAttrs = ["class" => $this->wrapperClass("card", $w["class"] ?? $w)];

        if (!empty($w["style"])) {
            $cardAttrs["style"] = $w["style"];
        }
        if (!empty($w["id"])) {
            $cardAttrs["id"] = $w["id"];
        }

        return $b->blk($cardContent, $cardAttrs);
    }

    /**
     * BULMA CSS ( BUTTON )
     * Devuelve un botón con formato para Bulma CSS
     *
     * @param $texto
     * @param array $datos [["e"], ["w"]] o ["e"=>[], "w"=>[]]
     * @return string
     */
    public function bul_button($texto, $datos = [])
    {
        $b = new HtmlTag();
        $info = $this->resolveDatos($datos);
        $e = $info["e"];

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