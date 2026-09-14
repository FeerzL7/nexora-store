-- ============================================================
-- NEXORA - catalogo propio
-- Sustituye los productos Nike / Adidas / Air Jordan heredados de la
-- plantilla original por articulos del giro real: ropa, accesorios y joyeria.
-- Requiere haber ejecutado antes migracion_nexora.sql (crea `categorias`).
-- ============================================================

DELETE FROM productos;
ALTER TABLE productos AUTO_INCREMENT = 1;

INSERT INTO productos (id, nombre, descripcion, precio, descuento, id_categoria, activo) VALUES
(1, 'Sudadera oversize con capucha',
 'Algodón grueso, caída amplia y capucha forrada. La que te vas a poner todos los días sin pensarlo.',
 749.00, 15, 1, 1),

(2, 'Camiseta de algodón peinado',
 'Corte recto, cuello reforzado y un algodón que aguanta lavada tras lavada. Base para cualquier look.',
 329.00, 0, 1, 1),

(3, 'Camisa oversize de popelina',
 'Ligera, fresca y con caída suave. Abierta sobre una camiseta o cerrada y fajada: funciona de las dos formas.',
 599.00, 0, 1, 1),

(4, 'Pantalón cargo de gabardina',
 'Seis bolsas reales, tiro medio y bastilla ajustable. Cómodo sin verse deportivo.',
 899.00, 20, 1, 1),

(5, 'Gorra de cinco paneles',
 'Visera curva, ajuste trasero metálico y bordado al frente. Le queda a cualquier cabeza y a cualquier atuendo.',
 399.00, 0, 2, 1),

(6, 'Tote bag de lona gruesa',
 'Lona de 12 onzas, costuras dobles y asas que no se estiran. Le caben la laptop y el mandado.',
 349.00, 10, 2, 1),

(7, 'Lentes de sol de montura gruesa',
 'Acetato sólido, bisagras metálicas y protección UV400. Marcan la cara sin taparla.',
 549.00, 0, 2, 1),

(8, 'Cinturón de piel con hebilla mate',
 'Piel genuina, 3.5 cm de ancho y hebilla sin brillos. El detalle que amarra todo el conjunto.',
 649.00, 0, 2, 1),

(9, 'Collar con dije circular',
 'Cadena de acero inoxidable con dije de aro. No se oxida, no se pone verde, no te lo quitas.',
 429.00, 0, 3, 1),

(10, 'Arracadas gruesas de acero',
 'Cuatro centímetros de diámetro y cierre de presión. Suficientes para que no necesites nada más.',
 289.00, 15, 3, 1),

(11, 'Anillo solitario de acero',
 'Banda ancha con piedra tallada engastada al ras. Pesa lo justo para que se sienta real.',
 899.00, 0, 3, 1),

(12, 'Pulsera de eslabones',
 'Eslabones planos con broche de seguridad. Sola se ve limpia, encimada se ve mejor.',
 379.00, 10, 3, 1);
