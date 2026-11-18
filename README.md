# lx_bulmacss

Constructor de bloques HTML con estilos de [Bulma CSS](https://bulma.io/) generados desde PHP.

## Instalación

```bash
composer require latinexus/lx_bulmacss
```

## Uso

Los helpers solo generan el HTML con las clases de Bulma; para que se vean los estilos hay que cargar el CSS
en el `<head>`:

```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css">
```

O hacerlo desde la clase con `cssLinks()` (también carga Font Awesome para los iconos):

```php
use Lib\Grids\GridBulma;

$bul = new GridBulma();

echo $bul->cssLinks();
echo $bul->bul_input("Nombre", "nombre");
```

Para una página completa con estilos ya incluidos: `echo $bul->page($contenido, "Título");`.
Demos visuales: `demo/index.php` y `tests/smoke.php` (se abren directo en el navegador, p. ej.
<http://localhost/cp_bulmacss/tests/smoke.php>).

```php
echo $bul->bul_input("Nombre", "nombre");
```

```html
<div class="field">
  <label class="label">Nombre</label>
  <div class="control">
    <input class="input" type="text" name="nombre" />
  </div>
</div>
```

Los helpers generan su propio contenedor, así que no hace falta envolverlos en `col()`.
El tercer parámetro `$datos` siempre es un array con dos elementos:

* `[0]` — opciones del elemento (input, select, textarea...): `value`, `id`, `class`, `placeholder`, `type`,
  `required`, `readonly`, `disabled`, `iconLeft`, `iconRight`, `color` (`is-success`, `is-danger`...),
  `help`, `helpColor`, `nowrap`, `min`, `max`, `minlength`, `maxlength`, etc.
* `[1]` — opciones de la envoltura: `class`, `style`, `id` o `nowrap`.

Si no se indica la envoltura, el contenedor es `column` y ocupa todo el ancho disponible;
si se indica, el contenedor pasa a ser `field` con los modificadores dados.

```php
// column (todo el ancho)
$g->bul_input("Nombre", "nombre", [["required" => true, "iconLeft" => "user", "placeholder" => "Tu nombre"]]);

// field is-four-fifths (dentro de un row())
$g->bul_input("Nombre", "nombre", [["required" => true], ["class" => "is-four-fifths"]]);

// sin contenedor
$g->bul_input("Nombre", "nombre", [["nowrap" => true]]);
```

Genera:

```html
<div class="column">
  <label class="label">Nombre <span class="has-text-danger">*</span></label>
  <div class="control has-icons-left has-icons-right">
    <input id="nombre" name="nombre" class="input" type="text" placeholder="Tu nombre" required />
    <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
  </div>
</div>
```

También se acepta el formato con claves `["e" => [], "w" => []]`.

## Componentes disponibles

| Método | Descripción |
| --- | --- |
| `row($contenido, $params, $tag)` | Contenedor `columns` |
| `col($contenido, $params, $tag)` | Columna `column` |
| `bul_input($label, $name, $datos)` | Input de texto, número, fecha, etc. |
| `bul_select($label, $name, $opciones, $datos)` | Select |
| `bul_textarea($label, $name, $datos)` | Textarea |
| `bul_check($label, $name, $datos)` | Checkbox |
| `bul_radio($label, $name, $datos)` | Radio |
| `bul_card($titulo, $contenido, $datos)` | Card (con imagen opcional) |
| `bul_button($texto, $datos)` | Botón o enlace con estilo de botón |
| `bul_notification($mensaje, $tipo, $dismissible)` | Notificación |
| `bul_box($contenido)` | Box |
| `bul_tag($texto, $tipo, $tamanio, $rounded, $delete)` | Tag |
| `bul_progress($valor, $tipo, $tamanio)` | Barra de progreso |
| `bul_table($filas, $encabezados, ...)` | Tabla |

## Publicar una nueva versión

```bash
# subir cambios
git add -A && git commit -m "..." && git push

# crear y subir la etiqueta (Packagist la detecta automáticamente)
git tag v1.0.1
git push origin v1.0.1
```

Packagist toma la versión del nombre del tag (`v1.0.1` → `1.0.1`).

## Licencia

MIT
