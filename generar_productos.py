"""Ilustraciones planas de producto para el catalogo NEXORA.

No son fotos: son siluetas de una sola tinta (negro carbon) con un acento en
rojo Nexora, sobre fondo transparente. Al ser un sistema consistente se leen
como una decision de marca y no como fotos de relleno mal combinadas.
"""
import cairosvg, os

INK = "#1A1A1A"
RED = "#D81B4A"

def svg(cuerpo):
    return f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="800" height="800">
<g fill="none" stroke="{INK}" stroke-width="10" stroke-linejoin="round" stroke-linecap="round">{cuerpo}</g></svg>'''

TEE = """
<path d="M145 92 L108 108 L82 156 L118 182 L140 158 L140 312 L260 312 L260 158 L282 182 L318 156 L292 108 L255 92
         C246 118 224 130 200 130 C176 130 154 118 145 92 Z" fill="#FFFFFF"/>
"""

HOODIE = """
<path d="M145 96 L104 114 L78 166 L116 192 L140 166 L140 316 L260 316 L260 166 L284 192 L322 166 L296 114 L255 96 Z" fill="#FFFFFF"/>
<path d="M138 104 C134 44 266 44 262 104 C244 134 220 146 200 146 C180 146 156 134 138 104 Z" fill="#FFFFFF"/>
<path d="M162 220 L238 220 L246 272 L154 272 Z" fill="none"/>
<path d="M180 142 L184 190" stroke="%s"/><path d="M220 142 L216 190" stroke="%s"/>
""" % (RED, RED)

CAMISA = """
<path d="M148 94 L106 112 L80 162 L118 188 L142 162 L142 316 L258 316 L258 162 L282 188 L320 162 L294 112 L252 94 Z" fill="#FFFFFF"/>
<path d="M148 94 L200 148 L252 94" fill="#FFFFFF"/>
<path d="M200 148 L200 316" stroke-width="6"/>
<circle cx="200" cy="196" r="6" fill="%s" stroke="none"/>
<circle cx="200" cy="248" r="6" fill="%s" stroke="none"/>
""" % (RED, RED)

PANTALON = """
<path d="M138 100 L262 100 L276 330 L214 330 L200 198 L186 330 L124 330 Z" fill="#FFFFFF"/>
<path d="M138 138 L262 138" stroke-width="6"/>
<path d="M236 112 L256 112" stroke="%s" stroke-width="8"/>
""" % RED

GORRA = """
<path d="M104 226 C104 172 147 128 200 128 C253 128 296 172 296 226 Z" fill="#FFFFFF"/>
<path d="M294 226 C334 228 356 244 356 258 L104 258 L104 226 Z" fill="#FFFFFF"/>
<circle cx="200" cy="176" r="20" fill="%s" stroke="none"/>
""" % RED

TOTE = """
<path d="M116 156 L284 156 L302 330 L98 330 Z" fill="#FFFFFF"/>
<path d="M156 156 C156 104 244 104 244 156" fill="none"/>
<path d="M168 226 L232 226" stroke="%s" stroke-width="12"/>
""" % RED

LENTES = """
<rect x="72" y="158" width="112" height="86" rx="30" fill="#FFFFFF"/>
<rect x="216" y="158" width="112" height="86" rx="30" fill="#FFFFFF"/>
<path d="M184 186 C194 174 206 174 216 186"/>
<path d="M72 180 L38 158"/><path d="M328 180 L362 158"/>
<path d="M96 196 L120 178" stroke="%s" stroke-width="8"/>
""" % RED

CINTURON = """
<path d="M56 186 L344 186 L344 236 L56 236 Z" fill="#FFFFFF"/>
<rect x="168" y="166" width="76" height="90" rx="12" fill="#FFFFFF"/>
<path d="M206 166 L206 256" stroke="%s" stroke-width="8"/>
<circle cx="112" cy="211" r="7" fill="%s" stroke="none"/>
""" % (RED, RED)

COLLAR = """
<path d="M118 104 C118 268 282 268 282 104" fill="none"/>
<circle cx="200" cy="254" r="34" fill="%s" stroke="none"/>
<circle cx="200" cy="254" r="34" fill="none"/>
<circle cx="200" cy="254" r="13" fill="#FFFFFF" stroke="none"/>
""" % RED

ARRACADAS = """
<circle cx="142" cy="232" r="62" fill="none" stroke-width="16"/>
<circle cx="268" cy="232" r="62" fill="none" stroke-width="16" stroke="%s"/>
<path d="M142 170 C142 130 158 122 172 128"/>
<path d="M268 170 C268 130 284 122 298 128"/>
""" % RED

ANILLO = """
<circle cx="200" cy="242" r="76" fill="none" stroke-width="24"/>
<path d="M200 104 L238 146 L200 188 L162 146 Z" fill="%s" stroke="none"/>
<path d="M200 104 L238 146 L200 188 L162 146 Z" fill="none" stroke-width="8"/>
""" % RED

PULSERA = """
<ellipse cx="200" cy="212" rx="118" ry="86" fill="none" stroke-width="16"/>
<circle cx="200" cy="126" r="20" fill="%s" stroke="none"/>
<circle cx="82" cy="212" r="12" fill="#FFFFFF"/>
<circle cx="318" cy="212" r="12" fill="#FFFFFF"/>
""" % RED

RELOJ = """
<circle cx="200" cy="200" r="72" fill="#FFFFFF" stroke-width="12"/>
<path d="M166 132 L162 66 L238 66 L234 132" fill="#FFFFFF"/>
<path d="M166 268 L162 334 L238 334 L234 268" fill="#FFFFFF"/>
<path d="M200 162 L200 200 L228 216" stroke="%s" stroke-width="10"/>
""" % RED

PRODUCTOS = [
    (1,  HOODIE),   (2,  TEE),      (3,  CAMISA),   (4,  PANTALON),
    (5,  GORRA),    (6,  TOTE),     (7,  LENTES),   (8,  CINTURON),
    (9,  COLLAR),   (10, ARRACADAS),(11, ANILLO),   (12, PULSERA),
]

destino = 'image/productos'
for pid, cuerpo in PRODUCTOS:
    carpeta = f'{destino}/{pid}'
    os.makedirs(carpeta, exist_ok=True)
    png = f'/tmp/p{pid}.png'
    cairosvg.svg2png(bytestring=svg(cuerpo).encode(), write_to=png,
                     output_width=800, output_height=800)
    from PIL import Image
    Image.open(png).save(f'{carpeta}/principal.webp', 'WEBP', quality=92)
    print('ok', pid)
