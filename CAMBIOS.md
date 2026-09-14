# NEXORA — correcciones funcionales

Esta pasada corrige **solo funcionamiento**. El diseño (`css/styles.css`,
`admin/css/styles.css`) y los volcados SQL originales quedaron sin tocar.

Todo lo de abajo se probó contra PHP 8.3 + MariaDB 10.11 con el sitio corriendo.
Resultado final: **0 warnings, 0 deprecated y 0 errores fatales** en todas las páginas.

---

## 1. Antes de abrir el sitio

Ejecuta la migración una sola vez, sobre la base `tienda_ropa` ya importada:

```sql
SOURCE migracion_nexora.sql;
```

Crea dos tablas que el código consultaba pero que nunca existieron.

**Acceso al panel:** `admin` / `nexora2026` — cámbialo antes de entregar.

---

## 2. Bugs que rompían el sitio

### El carrito cobraba de más

`carrito.php` y `realizarpago.php` calculaban el subtotal con `$cantidad * $precio`,
pero `$cantidad` era una variable heredada del `foreach` anterior, así que todas las
filas usaban la cantidad del último producto.

Reproducido antes del arreglo: 1 unidad de $1,500 + 2 de $2,000 daban **$7,000**
en lugar de $5,500, y ambas filas mostraban cantidad 2.

Además el carrito cobraba el precio de lista mientras que la ficha de producto
anunciaba el precio con descuento. Ahora las dos pantallas usan el precio con
descuento aplicado.

La lógica quedó centralizada en **`back/carritoLista.php`** (archivo nuevo) para que
carrito y pago no puedan volver a desincronizarse.

### El JavaScript moría en cada página

`js/script.js` registraba listeners sobre `#eliminaModal` y sobre los campos del
formulario de registro en el nivel superior del archivo. En cualquier página donde
esos elementos no existían —o sea, casi todas— lanzaba un `TypeError` y el resto del
archivo nunca se ejecutaba.

Además tenía tres errores propios:

| Estaba | Debía ser | Efecto |
|---|---|---|
| `list.lenght` | `list.length` | el bucle nunca corría |
| `parsefloat(...)` | `parseFloat(...)` | función inexistente |
| `exsisteUsuario(...)` | `existeUsuario(...)` | la validación nunca se llamaba |

También tenía `"<?php echo PRECIO; ?>"` dentro de un `.js`, que el servidor nunca
interpreta: el total se escribía con ese texto literal.

El archivo se reescribió: todo el código del DOM va dentro de `DOMContentLoaded` y
comprueba que el elemento exista antes de usarlo.

### El login del panel devolvía HTTP 500

`admin/back/adminFunciones.php` consultaba `SELECT ... FROM admin`, pero el volcado
SQL no tiene ninguna tabla `admin`. El resultado era una `PDOException` sin capturar
y una página en blanco. La migración crea la tabla y siembra un usuario.

### El panel estaba abierto a cualquiera

Ninguna página de `/admin` verificaba la sesión: escribiendo la URL a mano se entraba
a `inicio.php`, `agregar_producto.php`, `eliminar_producto.php` y
`actualizar_producto.php` sin credenciales.

Se agregó **`admin/back/sesion.php`**, que ahora encabeza todas esas páginas.

### `admin/inicio.php` imprimía un error en cada carga

El bloque que procesaba la eliminación corría siempre, no solo al recibir un POST,
así que la página abría con el texto `El campo 'id' no está definido.` encima del
`<!DOCTYPE>`. Se eliminó ese bloque, y ahora la página sí muestra la tabla de
productos que ya consultaba pero nunca imprimía.

### SQL inválido en el panel

`actualizarProductoProducto()` ejecutaba `UPDATE INTO productos (...) VALUES (...)`,
que no es SQL válido, y usaba `$id` y `$activo` sin recibirlos como parámetros.
Se reemplazó por un `UPDATE ... SET ... WHERE id = ?` correcto.

Las funciones del panel usaban `global $con`, pero `$con` era una variable local del
script que las llamaba, así que dentro de la función llegaba vacía. Ahora todas
reciben la conexión como primer parámetro.

### La ficha de producto seguía cargando sin producto

`detalles.php` no tenía rama `else` cuando el producto no existía: seguía imprimiendo
la página con `$nombre`, `$precio` y `$descripcion` sin definir. Con `?id=99` el log
mostraba cinco warnings y aun así respondía 200.

Ahora responde **404** y muestra un mensaje con enlace al catálogo.

### La galería de imágenes nunca funcionó

Se inicializaba `$images` pero se llenaba `$imagenes`, la condición estaba duplicada
(`strpos($archivo,'webp') || strpos($archivo,'webp')`) y `strpos` devuelve `0`, que
PHP evalúa como falso. Reescrito con `scandir` + `pathinfo`, y las imágenes
secundarias ahora sí se imprimen.

### `back/validaciones.php` siempre validaba el correo

Decía `elseif ($action = 'existeEmail')` — una asignación, no una comparación, que
siempre da verdadero. Verificar si un usuario existía consultaba en realidad la
tabla de correos.

### Rutas de imagen rotas

`index.php` usaba `images/no-photo.jpg` y `detalles.php` usaba `image/no-photo.webp`.
Ninguno de los dos archivos existía. Se creó `image/no-photo.webp` y ambos usan ahora
la función `imagenProducto()`.

### Otros

- `login.php` redirigía a `checkout.php`, un archivo que no existe → ahora va a `realizarpago.php`.
- `realizarpago.php` era accesible sin sesión y con el carrito vacío → ahora redirige.
- `logout.php` llamaba `session_destroy()` sin vaciar `$_SESSION` ni borrar la cookie.
- `registro.php` tenía `requireda` en los siete campos: **ningún campo era obligatorio**.
- Los avisos "Usuario no disponible" y "Correo no disponible" aparecían escritos desde que cargaba la página.
- El registro no confirmaba nada al terminar; ahora muestra un mensaje de éxito.
- `header.php` tenía `<a href="">` en el logo y los enlaces `#trending` / `#info` no funcionaban fuera de la portada.
- El contador del carrito (`#num_cart`) salía vacío hasta agregar algo; ahora se imprime desde el servidor.
- `admin/procesar_agregar_producto.php` recibía los argumentos en distinto orden que la función (`precio` y `descripcion` invertidos), no validaba nada y no redirigía. Se eliminó y su lógica vive en `agregar_producto.php`.
- El formulario de alta pedía el `id` a mano aunque la columna es `AUTO_INCREMENT`.
- El borrado de productos era un `DELETE` físico; ahora es baja lógica (`activo = 0`).
- `<html lang="en">` en todas las páginas → `lang="es"`.
- Restos de la plantilla original: `SportZone` en títulos, en el panel y en el pie; textos en inglés en el footer.
- Faltas: "Shp Now", "Coleccion", "lista Vacia", "Cerrar Sesion".

---

## 3. Seguridad

- **XSS**: todo dato que sale de la base de datos pasa por `e()` (`htmlspecialchars`). Verificado: un `<script>` guardado como nombre de producto ahora se imprime escapado.
- **SQL**: `carrito.php` y `realizarpago.php` interpolaban `$cantidad` dentro del texto de la consulta. Ya no.
- **Validación de entrada**: `filter_input` con rangos en ids y cantidades. Un `id` inexistente ya no entra al carrito y las cantidades se limitan a 1–10 del lado del servidor, no solo en el HTML.
- **Fijación de sesión**: `session_regenerate_id(true)` al iniciar sesión en el panel.
- `session_start()` protegido con `session_status()` en los dos archivos de configuración.
- La conexión pasó de `utf8` a `utf8mb4` (soporta emoji y todos los acentos).
- Los errores de conexión van al log del servidor, no a la pantalla del visitante.

---

## 4. Archivos nuevos, modificados y eliminados

**Nuevos**
`back/carritoLista.php` · `admin/back/sesion.php` · `migracion_nexora.sql` · `image/no-photo.webp` · `CAMBIOS.md`

**Eliminado**
`admin/procesar_agregar_producto.php`

**Sin tocar**
`css/styles.css` · `admin/css/styles.css` · `tienda_ropa.sql` · `tienda_ropa.txt` · `admin/js/scripts.js` · las imágenes originales

El repositorio Git viene incluido con los cambios sin confirmar, así que puedes
correr `git diff` y revisar línea por línea antes de aceptar nada.

---

## 5. Pendiente para la siguiente pasada

- **El catálogo sigue siendo de Nike, Adidas y Air Jordan.** Son marcas registradas y además del giro equivocado: el proyecto es ropa, accesorios y joyería. Hay que sustituir los nueve productos y sus fotos.
- **La paleta del CSS usa `#EE1C47`**, no el rojo Nexora `#D81B4A`. Jost ya está cargada, pero falta Inter para el texto de lectura.
- **El flujo de pago no existe**: `realizarpago.php` solo muestra el resumen. No hay tabla de pedidos ni pasarela.
- **Falta CSRF** en los formularios y en los endpoints del carrito.
- **La activación por correo no está implementada**: `registro.php` genera un token pero marca `activacion = 1` de inmediato.
- `config/basededatos.php` trae las credenciales `root` / `root` en texto plano.
