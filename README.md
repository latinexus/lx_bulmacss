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
Demo visual: `php -S localhost:8080 -t demo` y abrir <http://localhost:8080>.

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

Todos los helpers aceptan un array `$datos` con dos claves:

* `e` — opciones del elemento: `value`, `id`, `class`, `placeholder`, `type`, `required`, `readonly`, `disabled`, `iconLeft`, `iconRight`, `nowrap`, `min`, `max`, `minlength`, `maxlength`, etc.
* `w` — clases extra del envoltorio (`field`), por ejemplo `"w" => ["is-grouped"]`.

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

## Licencia

MIT
