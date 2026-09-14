# NEXORA — rediseño

Segunda pasada: identidad visual, movimiento y contenido. La base funcional
de la pasada anterior se conserva íntegra.

---

## 1. Bootstrap: fuera del sitio público, se queda en el panel

**Lo que pasaba.** Bootstrap se cargaba en una sola página (`carrito.php`) y
solo la hoja CSS, nunca el JavaScript. Pero las clases de Bootstrap estaban
regadas por todo el sitio: `btn`, `btn-primary`, `alert alert-warning`,
`form-control`, `text-danger`, `modal fade`. En `login.php`, `registro.php` e
`index.php` esas clases no tenían ninguna regla detrás, así que los botones
salían como texto plano y las alertas sin fondo. Eso era exactamente lo que se
veía raro. Y el menú desplegable de sesión usaba `data-bs-toggle`, que necesita
el JS de Bootstrap, así que nunca abría.

**Lo que se hizo.** El sitio público es ahora HTML + CSS + JavaScript propio,
sin ningún framework. Nada se hereda a medias y todo lo que se ve tiene una
regla escrita a propósito.

**Por qué el panel sí lo conserva.** `/admin` está construido sobre la
plantilla SB Admin, que es Bootstrap de arriba abajo: rejilla, tarjetas,
barra lateral, tablas. Ahí Bootstrap sí se carga completo y el resultado es
coherente. Reescribirlo sería tirar trabajo que ya funciona, y el panel solo
lo ves tú, no el cliente. La mezcla era el problema; Bootstrap por sí solo no.

---

## 2. La idea: el nexo

El logotipo son dos aros entrelazados. Ese cruce es el nombre de la marca
—el nexo entre la persona y su estilo— y es lo único que NEXORA tiene que
nadie más tiene, así que se convirtió en el dispositivo estructural de toda
la página en lugar de quedarse como un adorno en la esquina.

- **La portada** es el logotipo a escala gigante: el aro rojo relleno y el aro
  negro de contorno se cruzan, y dentro se asoma una pieza real del catálogo.
- **Cada producto** se apoya sobre un plato circular. Al pasar el puntero, el
  plato se recorre y un segundo aro entra a encimarse: se arma el logotipo con
  el producto adentro.
- **Las categorías** son discos, no tarjetas rectangulares.
- **La pieza destacada** repite el disco rojo sobre fondo negro.

Es un solo gesto repetido a tres escalas. Todo lo demás se mantiene callado
para que ese gesto se note.

## 3. Paleta y tipografía

Las tres del manual, sin inventar nada:

| Token | Valor | Uso |
|---|---|---|
| `--nexo` | `#D81B4A` | acentos, botones, platos, precios rebajados |
| `--tinta` | `#1A1A1A` | texto, franjas oscuras, aros de contorno |
| `--papel` | `#FAFAFA` | fondo general |

Dos derivados para lo que la paleta base no cubría: `--nexo-hondo` `#A81139`
para el estado presionado de los botones, y `--rubor` `#F7DCE4` para los platos
en reposo. Los dos salen del mismo rojo, así que la página sigue teniendo un
solo tono de color.

El rojo aparece poco a propósito. Si todo fuera rojo, nada destacaría: se
reserva para lo que quieres que el cliente toque.

Jost 700 para títulos y Inter 400/500 para lectura, como pide el manual.
Ambas ahora se sirven desde el propio sitio.

## 4. Movimiento

La regla fue: **el movimiento explica algo o no va.**

Hay un solo momento de animación que ocurre sin que el usuario haga nada: al
cargar, las dos líneas del título suben desde abajo y los aros entran a escala.
Es el saludo de la página y no se repite.

Todo lo demás responde a una acción:

- Las secciones y las piezas aparecen al llegar a ellas al desplazar, en grupos
  escalonados, con `IntersectionObserver`.
- El encabezado pasa de transparente a sólido con desenfoque al bajar.
- Al pasar el puntero por una pieza, el aro entra y se encima sobre el plato.
- Al agregar al carrito sale un aviso abajo a la derecha con el nombre del
  producto, y el contador del carrito da un salto.
- Al cambiar una cantidad, el subtotal parpadea en rojo para señalar
  exactamente qué importe cambió.
- Los filtros ocultan y revelan piezas sin recargar la página.

Todo respeta `prefers-reduced-motion`: si el sistema del visitante pide menos
movimiento, las animaciones se apagan y el contenido queda visible.

## 5. Estructura nueva de la portada

```
┌──────────────────────────────────────────┐
│ encabezado fijo                          │
├────────────────────┬─────────────────────┤
│ Tu estilo          │      ◯◯             │  portada
│ empieza aquí       │   dos aros + pieza  │
│ [Ver catálogo]     │                     │
├────────────────────┴─────────────────────┤
│  ◯        ◯        ◯     tres categorías │
├──────────────────────────────────────────┤
│ [Todo][Ropa][Accesorios][Joyería]        │  filtros
│ ▢ ▢ ▢ ▢                                  │
│ ▢ ▢ ▢ ▢   rejilla de 12 piezas           │
│ ▢ ▢ ▢ ▢                                  │
├──────────────────────────────────────────┤
│ franja negra: la pieza con más descuento │
├──────────────────────────────────────────┤
│ envíos · cambios · pago protegido        │
├──────────────────────────────────────────┤
│ pie                                      │
└──────────────────────────────────────────┘
```

La ficha de producto ahora tiene migas de pan, galería, etiqueta de descuento
y una sección de piezas relacionadas de la misma categoría. El carrito pasó de
tabla a renglones con miniatura. Login y registro tienen formularios propios.

## 6. Catálogo propio

Los nueve productos Nike, Adidas y Air Jordan se sustituyeron por doce piezas
de NEXORA repartidas en las tres categorías: cuatro de ropa, cuatro de
accesorios y cuatro de joyería, con precios en pesos y descripciones escritas
en el tono del manual —de tú, frases cortas, sin vender de más.

**Las imágenes son ilustraciones, no fotos.** Siluetas de una sola tinta con un
acento en rojo, generadas con `generar_productos.py`. Un sistema consistente se
lee como una decisión de marca; fotos de banco mal combinadas se leen como
relleno. Cuando tengas fotos reales, sustituye
`image/productos/<id>/principal.webp` y todo sigue funcionando.

## 7. Sin dependencias externas

Antes el sitio pedía Jost a Google Fonts y los iconos a un CDN. Si el día de la
defensa no hay internet, o la red de la escuela bloquea esos dominios, la página
se veía con la tipografía equivocada y sin iconos.

Jost, Inter y Boxicons ahora viven en `css/vendor/`. El sitio no hace ni una
sola petición fuera de tu servidor.

## 8. Accesibilidad

- Contorno de foco visible en todo lo navegable con teclado.
- El menú de sesión abre con Enter y con la barra espaciadora.
- El diálogo de confirmación cierra con Escape.
- Etiquetas ocultas en los campos de cantidad del carrito.
- `aria-label` en los enlaces que solo llevan icono.
- El aviso emergente es `aria-live` para que lo anuncie un lector de pantalla.

## 9. Qué se probó

- **PHP 8.3 + MariaDB corriendo**: las 15 rutas responden, 0 warnings.
- **Cruce de clases**: todas las clases del HTML tienen regla en el CSS.
- **JavaScript contra un DOM real** (jsdom): los cuatro filtros dejan
  exactamente las piezas que deben, el encabezado se fija al desplazar, el menú
  móvil abre, y el respaldo sin `IntersectionObserver` deja todo visible.
  Esa prueba encontró una llamada suelta a `matchMedia` que habría tirado el
  archivo entero en navegadores sin soporte, el mismo fallo del código original.
- **Flujo completo**: alta de producto desde el panel → aparece en la tienda →
  se agrega al carrito → subtotales correctos → resumen de pago.

No pude tomar capturas de pantalla: Chromium no arranca en este entorno.
La revisión visual final queda de tu lado.

## 10. Archivos

**Nuevos**
`back/cabeza.php` (bloque `<head>` compartido) · `back/piezas.php` (tarjeta de
producto reutilizable) · `catalogo_nexora.sql` · `generar_productos.py` ·
`css/vendor/` · `image/productos/1..12/` · `DISENO.md`

**Reescritos**
`css/styles.css` · `js/script.js` · `index.php` · `detalles.php` ·
`carrito.php` · `realizarpago.php` · `login.php` · `registro.php` ·
`header.php` · `footer.php`

**Respaldo**
`css/styles-anterior.css.bak` guarda la hoja original por si quieres comparar.

## 11. Para instalarlo

```sql
SOURCE migracion_nexora.sql;   -- solo si no la corriste antes
SOURCE catalogo_nexora.sql;    -- reemplaza el catálogo
```

Panel: `admin` / `nexora2026`.

## 12. Caché del navegador

Todos los recursos que pueden cambiar conservando su nombre —`styles.css`,
`script.js`, el logo y las fotos de producto— salen con la fecha del archivo
como parámetro: `styles.css?v=1789345222`.

Sin esto, después de actualizar el sitio el navegador sigue sirviendo el CSS
y las imágenes anteriores, y la página se ve sin estilos y con las fotos
viejas. Ahora, cada vez que modifiques un archivo, la dirección cambia sola y
el navegador lo vuelve a pedir. También aplica cuando sustituyas la foto de
un producto.

## 13. Cómo instalarlo sin romper nada

**Borra la carpeta anterior y extrae el zip completo**, en vez de descomprimir
encima. Sobrescribir archivo por archivo deja mezclas: basta con que uno solo
se quede en la versión vieja para que la página salga en blanco.

Si prefieres conservar la carpeta anterior, renómbrala a `nexora-store-viejo`
antes de extraer la nueva. Así puedes volver atrás si algo no cuadra.

Las funciones de apoyo (`e()`, `recurso()`, `recorta()`) ahora viven en
`back/ayudas.php` y se declaran protegidas por `function_exists`. Aunque un
archivo de configuración se quede desactualizado, el sitio sigue cargando en
lugar de morir a media cabecera.

## 14. Lo que sigue pendiente

- No hay pasarela de pago ni tabla de pedidos: `realizarpago.php` cierra en el
  resumen.
- Falta CSRF en formularios y endpoints del carrito.
- El registro genera un token de activación pero activa la cuenta de inmediato.
- `config/basededatos.php` trae las credenciales en texto plano.
- El panel no permite subir la foto del producto; hay que copiarla a mano a
  `image/productos/<id>/principal.webp`.
