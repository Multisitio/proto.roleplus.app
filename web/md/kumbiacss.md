Principios de KumbiaCSS

1. Idless & Classless
    Usamos la C de cascada, le damos estilos a los hijos desde la selección del padre.
2. No Divitis
    Usamos div solo para establecer un contendor de columnas.
3. JSless
    Usamos el marco kumbia.js para usar JS vía [data-*].
4. CSS clicks
    Para hacer clicks usamos target e input checked de CSS.
5. KISS & DRY
    El código resutlante debe ser de máximo nivel de simplicidad, claridad y reutilizable.
6. No Style en línea
    Solo usamos en línea style="display:none" para ocultar elementos, el resto de estilos deben estar en sus respectivos archivos CSS y carpetas para ser minimizados.
7. Autominimizado
    Todo el CSS debe estar en archivos CSS dentro de carpetas para ser minimizados, fuera de estas creamos un fichero con el mismo nombre que la carpeta con la extensión .min.css como convención para el automatismo de minimizado.
    Ejemplo: web/css/default/5-terminal.css -> web/css/default.min.css
    El número que precede al nombre del archivo es para establecer el orden de carga, de menor a mayor.
8.  https://css.kumbia.php
    Todos nuestros estilos parten de aplicar y modificar esta base de estilos. Accede a https://css.kumbia.php para ver la documentación de su uso.
9.  kucss.php extend _html
    Es una clase que extiende de _html y nos ayuda a mantener la misma coherencia en todas las partes del proyecto. Si no existe un método para generar un elemento de KumbiaCSS, lo generamos y lo usamos en adelante.
10. Minimalismo
    Las vistas y los controladores deben ser estrictamente minimalistas.
11. No supongamos cosas
    Si no sabemos algo, preguntemos. Nada de chapuzas.