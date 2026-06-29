<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\Semestre;
use App\Models\Unidad;
use App\Models\Pregunta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PreguntaSeeder extends Seeder
{
    public function run(): void
    {
        $materia = Materia::where('nombre', 'Matemáticas CCH')->first();
        if (!$materia) {
            return;
        }

        $semestres = Semestre::where('materia_id', $materia->id)->get()->keyBy('numero');

        $questionsData = [
            // === SEMESTRE 1 (Matemáticas I) ===
            1 => [
                // Unidad 1: Números y operaciones
                1 => [
                    [
                        'texto' => '¿Cuál es el resultado de simplificar la siguiente expresión aritmética? \\( -8 + 2(5 - 12) \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '-22', 'B' => '-14', 'C' => '6', 'D' => '-20'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Resuelve la siguiente operación respetando la jerarquía correspondiente: \\( 3^2 - 2 \\times 5 + 4 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '3', 'B' => '-1', 'C' => '1', 'D' => '7'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Expresa en lenguaje algebraico el siguiente enunciado: "La diferencia del doble de un número y su mitad".',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( 2x - \\frac{x}{2} \\)',
                            'B' => '\\( 2(x - \\frac{x}{2}) \\)',
                            'C' => '\\( \\frac{2x - x}{2} \\)',
                            'D' => '\\( 2x - 2x \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Identifica a qué conjunto numérico pertenece el número real \\( \\sqrt{3} \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Irracionales', 'B' => 'Racionales', 'C' => 'Enteros', 'D' => 'Naturales'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el resultado de la siguiente suma de fracciones: \\( \\frac{2}{3} + \\frac{1}{4} - \\frac{1}{6} \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{3}{4} \\)',
                            'B' => '\\( \\frac{7}{12} \\)',
                            'C' => '\\( \\frac{5}{6} \\)',
                            'D' => '\\( \\frac{1}{2} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Expresa algebraicamente: "El cuadrado de la suma de dos números cualesquiera".',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (x+y)^2 \\)',
                            'B' => '\\( x^2+y^2 \\)',
                            'C' => '\\( 2(x+y) \\)',
                            'D' => '\\( (xy)^2 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Simplifica la expresión utilizando leyes de exponentes: \\( \\frac{x^7 \\cdot x^{-3}}{x^2} \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( x^2 \\)', 'B' => '\\( x^3 \\)', 'C' => '\\( x^5 \\)', 'D' => '\\( x \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el valor absoluto de la expresión \\( -5.75 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( 5.75 \\)', 'B' => '\\( -5.75 \\)', 'C' => '\\( 0 \\)', 'D' => '\\( 5.7 \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica la diferencia entre un número racional y uno irracional, dando un ejemplo representativo de cada uno.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Los números racionales se pueden expresar como el cociente de dos enteros (ej. 1/2) y tienen decimales finitos o periódicos. Los irracionales no se pueden expresar como fracción (ej. pi, raíz de 2) y tienen decimales infinitos no periódicos.'
                    ],
                    [
                        'texto' => 'Traduce al lenguaje algebraico la siguiente expresión: "El cociente de la suma de dos números entre el producto de los mismos".',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => '\\( \\frac{x+y}{xy} \\)'
                    ]
                ],
                // Unidad 2: Variación lineal
                2 => [
                    [
                        'texto' => 'Si 5 cuadernos escolares tienen un costo de $125 pesos, ¿cuánto se pagará por 8 cuadernos del mismo tipo?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '$200', 'B' => '$175', 'C' => '$225', 'D' => '$250'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En la ecuación general de la variación lineal \\( y = mx + b \\), la constante \\( b \\) representa físicamente:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'La ordenada al origen (intersección con el eje Y)',
                            'B' => 'La pendiente de la recta',
                            'C' => 'La intersección con el eje X',
                            'D' => 'La variable independiente'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra la pendiente (m) de la línea recta que pasa por los puntos cartesiano \\( (2, 5) \\) y \\( (5, 11) \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '2', 'B' => '-2', 'C' => '3', 'D' => '\\( \\frac{1}{2} \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Identifica la ordenada al origen de la recta cuya ecuación está dada por \\( y = -4x - 7 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '-7', 'B' => '-4', 'C' => '7', 'D' => '4'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Un tinaco se llena a velocidad constante de 15 litros por minuto. Si inicialmente contenía 100 litros, ¿cuál ecuación representa el volumen \\( V \\) tras \\( t \\) minutos?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( V = 15t + 100 \\)',
                            'B' => '\\( V = 100t + 15 \\)',
                            'C' => '\\( V = 15t - 100 \\)',
                            'D' => '\\( V = 15 - 100t \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si una línea recta es completamente vertical en el plano, se dice que su pendiente es:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Indefinida o infinita', 'B' => 'Cero', 'C' => 'Positiva', 'D' => 'Negativa'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Escribe la ecuación lineal de la recta que posee pendiente \\( m = -3 \\) y ordenada al origen \\( b = 2 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( y = -3x + 2 \\)',
                            'B' => '\\( y = 2x - 3 \\)',
                            'C' => '\\( y = -3x - 2 \\)',
                            'D' => '\\( y = 3x + 2 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Qué característica gráfica describe a una relación de proporcionalidad directa?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Es una línea recta que pasa estrictamente por el origen \\( (0,0) \\)',
                            'B' => 'Es una curva parabólica simétrica',
                            'C' => 'Es una recta horizontal paralela al eje X',
                            'D' => 'Es una hipérbola que decrece asintóticamente'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Define qué es la pendiente de una recta en términos geométricos y escribe la fórmula para obtenerla dados dos puntos.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Representa la inclinación de la recta con respecto al eje horizontal. Es la razón de cambio vertical sobre el cambio horizontal y se calcula como: m = (y2 - y1) / (x2 - x1).'
                    ],
                    [
                        'texto' => 'Si un automóvil recorre 150 km a velocidad constante durante 2 horas, escribe la ecuación que modela la distancia recorrida (d) en kilómetros según el tiempo (t) en horas.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'd = 75t'
                    ]
                ],
                // Unidad 3: Ecuaciones lineales
                3 => [
                    [
                        'texto' => 'Resuelve la siguiente ecuación de primer grado: \\( 3x - 5 = 16 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '7', 'B' => '5', 'C' => '9', 'D' => '6'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Resuelve la ecuación con paréntesis aplicando la propiedad distributiva: \\( 2(x + 3) = 14 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '4', 'B' => '5', 'C' => '8', 'D' => '3'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Despeja y resuelve para \\( y \\) en la siguiente igualdad: \\( 5y + 3 = 2y - 9 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '-4', 'B' => '-2', 'C' => '4', 'D' => '2'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'El triple de la edad de Ana aumentado en 8 años es igual a 32. ¿Qué edad tiene Ana actualmente?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '8 años', 'B' => '10 años', 'C' => '12 años', 'D' => '6 años'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Despeja la variable \\( x \\) de la siguiente fórmula geométrica: \\( A = \\frac{x + y}{2} \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 2A - y \\)',
                            'B' => '\\( x = A - 2y \\)',
                            'C' => '\\( x = 2A + y \\)',
                            'D' => '\\( x = \\frac{A - y}{2} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el valor que satisface la ecuación fraccionaria: \\( \\frac{x}{3} + 2 = 5 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '9', 'B' => '6', 'C' => '12', 'D' => '15'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Resuelve agrupando términos semejantes en la ecuación: \\( 4x - 7 = 2x + 5 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '6', 'B' => '3', 'C' => '1', 'D' => '-6'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si sumamos 12 a la mitad de un número desconocido, obtenemos 20. ¿De qué número se trata?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '16', 'B' => '8', 'C' => '24', 'D' => '32'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica brevemente el concepto de igualdad matemática y la regla general de realizar operaciones inversas en ambos lados al despejar.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Una igualdad es una relación de equivalencia entre dos expresiones. Al despejar, para mantener la balanza o igualdad, cualquier operación matemática aplicada a un lado debe aplicarse exactamente igual al otro lado.'
                    ],
                    [
                        'texto' => 'El perímetro de un rectángulo mide 40 cm. Si sabemos que la base mide el triple que su altura, plantea la ecuación lineal y resuelve para hallar sus dimensiones.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Ecuación: 2(3h + h) = 40 (o bien 8h = 40). Altura h = 5 cm, base b = 15 cm.'
                    ]
                ],
                // Unidad 4: Sistemas 2x2
                4 => [
                    [
                        'texto' => 'Resuelve el siguiente sistema lineal básico por el método que prefieras: \\( \\begin{cases} x + y = 10 \\\\ x - y = 4 \\end{cases} \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 7, y = 3 \\)',
                            'B' => '\\( x = 6, y = 4 \\)',
                            'C' => '\\( x = 8, y = 2 \\)',
                            'D' => '\\( x = 5, y = 5 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Al usar el método de sustitución en el sistema \\( \\begin{cases} y = 2x + 1 \\\\ 3x + y = 11 \\end{cases} \\), ¿qué ecuación lineal de una sola variable resulta?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( 3x + (2x + 1) = 11 \\)',
                            'B' => '\\( 3x + 2x = 11 \\)',
                            'C' => '\\( 3x - (2x + 1) = 11 \\)',
                            'D' => '\\( 3(2x + 1) + y = 11 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra las soluciones del sistema de ecuaciones simultáneas: \\( \\begin{cases} 2x + 3y = 12 \\\\ x - y = 1 \\end{cases} \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 3, y = 2 \\)',
                            'B' => '\\( x = 2, y = 3 \\)',
                            'C' => '\\( x = 4, y = 1 \\)',
                            'D' => '\\( x = 1, y = 0 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En un corral hay gallinas y conejos. Se cuentan en total 15 cabezas y 44 patas. ¿Cuántas gallinas y conejos hay respectivamente?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '8 gallinas y 7 conejos',
                            'B' => '9 gallinas y 6 conejos',
                            'C' => '10 gallinas y 5 conejos',
                            'D' => '7 gallinas y 8 conejos'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si representamos gráficamente un sistema de ecuaciones y las dos rectas resultan ser paralelas distintas, se dice que el sistema:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'No tiene solución (incompatible)',
                            'B' => 'Tiene una solución única',
                            'C' => 'Tiene infinitas soluciones',
                            'D' => 'Tiene dos soluciones reales'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si dos rectas de un sistema 2x2 son coincidentes (es decir, una recta se encuentra sobre la otra en todos sus puntos), el sistema posee:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Infinitas soluciones',
                            'B' => 'Una única solución',
                            'C' => 'Ninguna solución',
                            'D' => 'Dos soluciones'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Resuelve por el método de reducción (suma y resta): \\( \\begin{cases} 3x + 2y = 8 \\\\ 2x - 2y = 2 \\end{cases} \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 2, y = 1 \\)',
                            'B' => '\\( x = 1, y = 2.5 \\)',
                            'C' => '\\( x = 3, y = -0.5 \\)',
                            'D' => '\\( x = 0, y = 4 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál de las siguientes coordenadas representa la solución gráfica del sistema: \\( \\begin{cases} y = x \\\\ y = -x + 4 \\end{cases} \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (2, 2) \\)',
                            'B' => '\\( (0, 0) \\)',
                            'C' => '\\( (1, 3) \\)',
                            'D' => '\\( (3, 1) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica paso a paso cómo se aplica el método gráfico para encontrar la solución de un sistema de ecuaciones lineales 2x2.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Consiste en despejar la variable "y" en ambas ecuaciones, tabular algunos puntos, graficar las dos rectas resultantes en el plano cartesiano y localizar la coordenada de intersección, la cual representa la solución única del sistema.'
                    ],
                    [
                        'texto' => 'Un padre tiene el triple de la edad de su hijo. Si la suma de sus edades actuales es de 48 años, plantea el sistema de ecuaciones e indica las edades de ambos.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Sistema: p = 3h; p + h = 48. Sustituyendo: 3h + h = 48 -> 4h = 48 -> Hijo h = 12 años, Padre p = 36 años.'
                    ]
                ]
            ],

            // === SEMESTRE 2 (Matemáticas II) ===
            2 => [
                // Unidad 1: Ecuaciones cuadráticas
                1 => [
                    [
                        'texto' => 'Resuelve la siguiente ecuación cuadrática incompleta pura: \\( x^2 - 9 = 0 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = \\pm 3 \\)',
                            'B' => '\\( x = 3 \\)',
                            'C' => '\\( x = -3 \\)',
                            'D' => '\\( x = \\pm 9 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Factoriza y resuelve la ecuación de segundo grado: \\( x^2 + 5x + 6 = 0 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = -2, x = -3 \\)',
                            'B' => '\\( x = 2, x = 3 \\)',
                            'C' => '\\( x = -1, x = -6 \\)',
                            'D' => '\\( x = 1, x = 6 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el discriminante \\( D = b^2 - 4ac \\) de la siguiente ecuación: \\( x^2 - 4x + 4 = 0 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '0', 'B' => '8', 'C' => '-8', 'D' => '16'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si el discriminante de una ecuación cuadrática es menor que cero \\( (D < 0) \\), se concluye que sus raíces son:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Dos soluciones complejas conjugadas (no reales)',
                            'B' => 'Dos soluciones reales distintas',
                            'C' => 'Una única solución real (raíz doble)',
                            'D' => 'No se puede definir'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Resuelve aplicando la fórmula general: \\( x^2 - 5x + 6 = 0 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 2, x = 3 \\)',
                            'B' => '\\( x = -2, x = -3 \\)',
                            'C' => '\\( x = 1, x = 5 \\)',
                            'D' => '\\( x = 0, x = 6 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra las soluciones de la ecuación incompleta mixta: \\( 3x^2 - 12x = 0 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 0, x = 4 \\)',
                            'B' => '\\( x = 0, x = -4 \\)',
                            'C' => '\\( x = 2, x = -2 \\)',
                            'D' => '\\( x = 3, x = 12 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Determina las raíces de la ecuación cuadrática: \\( x^2 - 2x - 8 = 0 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x = 4, x = -2 \\)',
                            'B' => '\\( x = -4, x = 2 \\)',
                            'C' => '\\( x = 8, x = -1 \\)',
                            'D' => '\\( x = 2, x = 4 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Qué término constante \\( c \\) se debe sumar a la expresión \\( x^2 + 6x \\) para completar un trinomio cuadrado perfecto?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '9', 'B' => '6', 'C' => '3', 'D' => '36'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica qué información aporta el discriminante de una ecuación cuadrática en términos de su representación gráfica (la parábola y el eje X).',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Si D > 0, la parábola corta al eje X en dos puntos. Si D = 0, la parábola toca al eje X en un solo punto (su vértice). Si D < 0, la parábola no corta ni toca al eje X.'
                    ],
                    [
                        'texto' => 'Resuelve por factorización la ecuación cuadrática \\( 2x^2 + 5x - 3 = 0 \\) y detalla tu procedimiento.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Buscamos factorizar en binomios: (2x - 1)(x + 3) = 0. Igualando a cero cada término: 2x - 1 = 0 -> x = 1/2; y x + 3 = 0 -> x = -3. Las raíces son x = 1/2 y x = -3.'
                    ]
                ],
                // Unidad 2: Funciones cuadráticas
                2 => [
                    [
                        'texto' => 'Determina hacia dónde abre la parábola correspondiente a la función: \\( y = -2x^2 + 4x + 1 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Hacia abajo', 'B' => 'Hacia arriba', 'C' => 'A la derecha', 'D' => 'A la izquierda'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el valor de la coordenada X de la parábola dada por \\( y = x^2 - 4x + 5 \\) usando la relación del vértice \\( h = -\\frac{b}{2a} \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '2', 'B' => '-2', 'C' => '4', 'D' => '-4'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra las coordenadas completas del vértice \\( (h, k) \\) para la parábola: \\( y = x^2 - 6x + 8 \\)',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (3, -1) \\)',
                            'B' => '\\( (-3, 1) \\)',
                            'C' => '\\( (3, 8) \\)',
                            'D' => '\\( (0, 8) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Identifica si la función cuadrática \\( y = -x^2 + 2x + 3 \\) posee un valor máximo o mínimo y calcúlalo.',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Tiene un máximo en \\( y = 4 \\)',
                            'B' => 'Tiene un mínimo en \\( y = 4 \\)',
                            'C' => 'Tiene un máximo en \\( y = 3 \\)',
                            'D' => 'Tiene un mínimo en \\( y = 3 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿En qué punto cruza al eje Y (ordenada al origen) la gráfica de la función cuadrática \\( y = 3x^2 - 5x + 7 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (0, 7) \\)',
                            'B' => '\\( (0, 3) \\)',
                            'C' => '\\( (7, 0) \\)',
                            'D' => '\\( (0, -5) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el dominio de cualquier función cuadrática general del tipo \\( f(x) = ax^2 + bx + c \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Todos los números reales \\( (-\\infty, \\infty) \\)',
                            'B' => 'Solo los números reales positivos \\( (0, \\infty) \\)',
                            'C' => 'El intervalo desde el vértice hasta infinito',
                            'D' => 'Depende de las intersecciones con el eje X'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si una parábola tiene vértice en \\( (1, -4) \\) y abre hacia arriba, ¿cuál es su rango o imagen?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( [-4, \\infty) \\)',
                            'B' => '\\( (-\\infty, -4] \\)',
                            'C' => 'Todos los números reales',
                            'D' => '\\( [1, \\infty) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'La altura de un proyectil disparado verticalmente está dada por \\( h(t) = -5t^2 + 20t \\). ¿En qué segundo alcanza su altura máxima?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( t = 2 \\text{ s} \\)',
                            'B' => '\\( t = 4 \\text{ s} \\)',
                            'C' => '\\( t = 10 \\text{ s} \\)',
                            'D' => '\\( t = 1 \\text{ s} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica cómo determinar si la parábola asociada a una función cuadrática abre hacia arriba o hacia abajo basándote únicamente en su ecuación.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Depende exclusivamente del signo del coeficiente del término cuadrático "a". Si a > 0, la parábola se abre hacia arriba. Si a < 0, se abre hacia abajo.'
                    ],
                    [
                        'texto' => 'Encuentra las intersecciones con el eje X y las coordenadas del vértice de la parábola \\( y = x^2 - 2x - 3 \\).',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Vértice en (1, -4). Las intersecciones con el eje X ocurren en y = 0, factorizando: (x-3)(x+1) = 0, por lo que cruza en x = 3 y x = -1.'
                    ]
                ],
                // Unidad 3: Geometría plana
                3 => [
                    [
                        'texto' => 'Si dos ángulos son suplementarios y uno de ellos mide \\( 65^\\circ \\), ¿cuánto mide el otro ángulo?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( 115^\\circ \\)', 'B' => '\\( 25^\\circ \\)', 'C' => '\\( 90^\\circ \\)', 'D' => '\\( 180^\\circ \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la suma de los ángulos interiores de cualquier triángulo en geometría plana?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( 180^\\circ \\)', 'B' => '\\( 360^\\circ \\)', 'C' => '\\( 90^\\circ \\)', 'D' => '\\( 270^\\circ \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En un triángulo rectángulo, un ángulo agudo mide \\( 35^\\circ \\). ¿Cuánto mide el otro ángulo agudo?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( 55^\\circ \\)', 'B' => '\\( 65^\\circ \\)', 'C' => '\\( 45^\\circ \\)', 'D' => '\\( 90^\\circ \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el área de un triángulo equilátero que tiene un perímetro de 18 cm y una altura de \\( 3\\sqrt{3} \\text{ cm} \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( 9\\sqrt{3} \\text{ cm}^2 \\)',
                            'B' => '\\( 18\\sqrt{3} \\text{ cm}^2 \\)',
                            'C' => '\\( 27 \\text{ cm}^2 \\)',
                            'D' => '\\( 6\\sqrt{3} \\text{ cm}^2 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la clasificación de un triángulo que posee tres lados con medidas completamente distintas?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Escaleno', 'B' => 'Isósceles', 'C' => 'Equilátero', 'D' => 'Rectángulo'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el perímetro de un rectángulo que tiene un área de \\( 48 \\text{ cm}^2 \\) y cuya base mide 8 cm.',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '28 cm', 'B' => '14 cm', 'C' => '20 cm', 'D' => '32 cm'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la fórmula correspondiente para calcular el área de un trapecio?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( A = \\frac{(B + b) \\cdot h}{2} \\)',
                            'B' => '\\( A = b \\cdot h \\)',
                            'C' => '\\( A = \\frac{b \\cdot h}{2} \\)',
                            'D' => '\\( A = P \\cdot a \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si el radio de un círculo mide 5 cm, ¿cuál es su área aproximada utilizando \\( \\pi \\approx 3.1416 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '78.54 cm²', 'B' => '31.42 cm²', 'C' => '15.71 cm²', 'D' => '25.00 cm²'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica detalladamente la diferencia fundamental entre los conceptos de perímetro y área en una figura geométrica bidimensional.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'El perímetro es la medida de la longitud del contorno o límite de la figura en una dimensión (unidades lineales), mientras que el área mide la extensión de la superficie encerrada en dos dimensiones (unidades cuadradas).'
                    ],
                    [
                        'texto' => 'Encuentra el área y perímetro de un rombo cuyas diagonales miden 12 cm y 16 cm respectivamente, sabiendo que su lado mide 10 cm.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Área: A = (D * d) / 2 = (16 * 12) / 2 = 96 cm². Perímetro: P = 4 * lado = 4 * 10 = 40 cm.'
                    ]
                ],
                // Unidad 4: Congruencia y semejanza
                4 => [
                    [
                        'texto' => '¿Cuál de las siguientes opciones describe el criterio de congruencia de triángulos LAL?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Dos lados iguales y el ángulo comprendido entre ellos también igual',
                            'B' => 'Dos ángulos iguales y el lado comprendido entre ellos también igual',
                            'C' => 'Tres lados proporcionales',
                            'D' => 'Lado, Ángulo y Lado consecutivos en cualquier orden'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si dos triángulos semejantes tienen una razón de semejanza de 1:3, ¿cuál es la relación correspondiente entre sus áreas?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Las áreas están en razón 1:9',
                            'B' => 'Las áreas están en razón 1:3',
                            'C' => 'Las áreas están en razón 1:6',
                            'D' => 'Las áreas son exactamente iguales'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En un triángulo rectángulo, los catetos miden 6 cm y 8 cm. ¿Cuánto mide su hipotenusa según el Teorema de Pitágoras?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '10 cm', 'B' => '14 cm', 'C' => '9 cm', 'D' => '12 cm'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'El Teorema de Tales establece principalmente que si trazamos una línea paralela a uno de los lados de un triángulo:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Obtenemos un nuevo triángulo semejante al original',
                            'B' => 'Se duplica el perímetro de la figura original',
                            'C' => 'Obtenemos un triángulo congruente al original',
                            'D' => 'Se genera un ángulo recto'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Un poste de 3 metros proyecta una sombra de 1.5 metros. Al mismo tiempo, un edificio proyecta una sombra de 12 metros. ¿Qué altura tiene el edificio?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '24 metros', 'B' => '6 metros', 'C' => '18 metros', 'D' => '36 metros'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En un triángulo rectángulo, la hipotenusa mide 13 cm y uno de sus catetos mide 5 cm. ¿Cuál es la longitud del otro cateto?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '12 cm', 'B' => '8 cm', 'C' => '18 cm', 'D' => '15 cm'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál de los siguientes no es un criterio de semejanza de triángulos?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'ALA (Ángulo-Lado-Ángulo con lados proporcionales)',
                            'B' => 'AA (Dos ángulos respectivamente iguales)',
                            'C' => 'LLL (Tres lados proporcionales)',
                            'D' => 'LAL (Dos lados proporcionales y el ángulo entre ellos igual)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si los lados de un triángulo miden 5 cm, 12 cm y 13 cm, se puede comprobar que el triángulo es:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Rectángulo', 'B' => 'Acutángulo', 'C' => 'Obtusángulo', 'D' => 'Equilátero'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Enuncia la diferencia conceptual principal entre la congruencia y la semejanza de figuras planas.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Dos figuras son congruentes si tienen exactamente la misma forma y el mismo tamaño (lados e inclinaciones iguales). Son semejantes si tienen la misma forma (ángulos iguales) pero distinto tamaño (lados proporcionales).'
                    ],
                    [
                        'texto' => 'Un árbol proyecta una sombra de 6 metros. Cerca de él, un bastón vertical de 1 metro colocado en el suelo proyecta una sombra de 0.75 metros. Calcula la altura del árbol.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Por semejanza de triángulos: Altura / 6 = 1 / 0.75 -> Altura = 6 / 0.75 = 8 metros.'
                    ]
                ]
            ],

            // === SEMESTRE 3 (Matemáticas III) ===
            3 => [
                // Unidad 1: Trigonometría
                1 => [
                    [
                        'texto' => 'En un triángulo rectángulo, la razón trigonométrica que relaciona el cateto opuesto con la hipotenusa se define como:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Seno', 'B' => 'Coseno', 'C' => 'Tangente', 'D' => 'Secante'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si en un triángulo rectángulo el cateto opuesto a un ángulo \\( \\theta \\) mide 3 cm y la hipotenusa mide 5 cm, ¿cuánto vale la tangente de \\( \\theta \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{3}{4} \\)',
                            'B' => '\\( \\frac{3}{5} \\)',
                            'C' => '\\( \\frac{4}{5} \\)',
                            'D' => '\\( \\frac{4}{3} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Bajo qué condición general es útil aplicar la Ley de Senos en la resolución de triángulos oblicuángulos?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Cuando conocemos dos ángulos y cualquier lado, o dos lados y el ángulo opuesto a uno de ellos',
                            'B' => 'Únicamente cuando conocemos los tres lados del triángulo',
                            'C' => 'Cuando conocemos dos lados y el ángulo comprendido entre ellos',
                            'D' => 'Solo si el triángulo posee un ángulo recto'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'La Ley de Cosenos se representa algebraicamente para el cálculo de un lado \\( a \\) mediante la fórmula:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( a^2 = b^2 + c^2 - 2bc \\cos(A) \\)',
                            'B' => '\\( a^2 = b^2 + c^2 + 2bc \\cos(A) \\)',
                            'C' => '\\( a^2 = b^2 - c^2 - 2bc \\sin(A) \\)',
                            'D' => '\\( a = b + c - \\cos(A) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En un triángulo oblicuángulo, conocemos los lados \\( b = 8 \\), \\( c = 10 \\) y el ángulo comprendido \\( A = 60^\\circ \\). ¿Qué método es el apropiado para calcular el lado \\( a \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Ley de Cosenos', 'B' => 'Ley de Senos', 'C' => 'Teorema de Pitágoras', 'D' => 'Razones trigonométricas básicas'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el valor exacto de la función \\( \\cos(60^\\circ) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{1}{2} \\)',
                            'B' => '\\( \\frac{\\sqrt{3}}{2} \\)',
                            'C' => '\\( \\frac{\\sqrt{2}}{2} \\)',
                            'D' => '1'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si en un triángulo rectángulo la tangente de un ángulo es igual a 1, significa que:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'El triángulo es isósceles y el ángulo agudo es de \\( 45^\\circ \\)',
                            'B' => 'El cateto adyacente mide el doble que el opuesto',
                            'C' => 'La hipotenusa mide igual que uno de los catetos',
                            'D' => 'El ángulo es de \\( 90^\\circ \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el valor del lado \\( a \\) en un triángulo oblicuángulo si conocemos que \\( b = 10 \\), \\( \\sin(B) = 0.5 \\) y \\( \\sin(A) = 0.8 \\) mediante Ley de Senos.',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '16', 'B' => '8', 'C' => '12.5', 'D' => '6.25'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Define brevemente las seis razones trigonométricas en un triángulo rectángulo indicando qué catetos o lados se relacionan en cada una.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Sen=CO/H, Cos=CA/H, Tan=CO/CA, Csc=H/CO, Sec=H/CA, Cot=CA/CO (donde CO es cateto opuesto, CA cateto adyacente y H la hipotenusa).'
                    ],
                    [
                        'texto' => 'Escribe las tres fórmulas generales de la Ley de Cosenos para calcular el cuadrado de cualquiera de los tres lados (a, b y c) de un triángulo oblicuángulo.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => '1) a² = b² + c² - 2bc*cos(A); 2) b² = a² + c² - 2ac*cos(B); 3) c² = a² + b² - 2ab*cos(C).'
                    ]
                ],
                // Unidad 2: Geometría analítica
                2 => [
                    [
                        'texto' => 'En el plano cartesiano, un punto ubicado en el segundo cuadrante (II) posee las coordenadas del signo:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '(-, +)', 'B' => '(+, -)', 'C' => '(+, +)', 'D' => '(-, -)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula la distancia entre los puntos en el plano cartesiano \\( A(1, 2) \\) y \\( B(4, 6) \\) usando la fórmula de distancia.',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '5', 'B' => '7', 'C' => '\\( \\sqrt{7} \\)', 'D' => '25'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra la pendiente de la recta que pasa por las coordenadas \\( P_1(-1, 3) \\) y \\( P_2(2, 9) \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '2', 'B' => '-2', 'C' => '3', 'D' => '\\( \\frac{1}{2} \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál de las siguientes fórmulas se utiliza para hallar el punto medio entre dos coordenadas \\( (x_1, y_1) \\) y \\( (x_2, y_2) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( P_m = \\left(\\frac{x_1 + x_2}{2}, \\frac{y_1 + y_2}{2}\\right) \\)',
                            'B' => '\\( P_m = (x_1 - x_2, y_1 - y_2) \\)',
                            'C' => '\\( P_m = \\left(\\frac{x_1 - x_2}{2}, \\frac{y_1 - y_2}{2}\\right) \\)',
                            'D' => '\\( P_m = (x_1 \\cdot x_2, y_1 \\cdot y_2) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si la pendiente de una recta es positiva, se puede asegurar que su ángulo de inclinación es:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Agudo (menor a \\( 90^\\circ \\))',
                            'B' => 'Obtuso (mayor a \\( 90^\\circ \\))',
                            'C' => 'Recto (igual a \\( 90^\\circ \\))',
                            'D' => 'Nulo (igual a \\( 0^\\circ \\))'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula las coordenadas del punto medio del segmento comprendido entre \\( A(-2, 4) \\) y \\( B(6, 8) \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (2, 6) \\)',
                            'B' => '\\( (4, 12) \\)',
                            'C' => '\\( (2, 12) \\)',
                            'D' => '\\( (4, 6) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si una recta tiene un ángulo de inclinación de \\( 45^\\circ \\), su pendiente vale:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '1', 'B' => '0', 'C' => '\\( \\sqrt{3} \\)', 'D' => 'Indefinida'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Determina la pendiente de una recta horizontal en el plano cartesiano.',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Cero', 'B' => '1', 'C' => 'Infinita', 'D' => '-1'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Escribe la fórmula general para calcular la distancia entre dos puntos cualquiera del plano cartesiano, derivándola conceptualmente del Teorema de Pitágoras.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'd = raíz((x2 - x1)² + (y2 - y1)²). Proviene de ver la distancia como la hipotenusa de un triángulo rectángulo cuyos catetos miden los incrementos en X y en Y.'
                    ],
                    [
                        'texto' => 'Encuentra las coordenadas del punto medio del segmento determinado por los puntos A(-3, 5) y B(7, -1).',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'x_m = (-3 + 7) / 2 = 2; y_m = (5 + -1) / 2 = 2. El punto medio es P_m(2, 2).'
                    ]
                ],
                // Unidad 3: La recta
                3 => [
                    [
                        'texto' => 'La ecuación de una recta escrita en la forma punto-pendiente está dada por:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( y - y_1 = m(x - x_1) \\)',
                            'B' => '\\( y = mx + b \\)',
                            'C' => '\\( Ax + By + C = 0 \\)',
                            'D' => '\\( \\frac{x}{a} + \\frac{y}{b} = 1 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la forma general establecida para la ecuación de una recta?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( Ax + By + C = 0 \\)',
                            'B' => '\\( y = mx + b \\)',
                            'C' => '\\( y - y_1 = m(x - x_1) \\)',
                            'D' => '\\( x + y = d \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si dos líneas rectas en el plano cartesiano son paralelas, ¿qué relación matemática cumplen sus pendientes \\( m_1 \\) y \\( m_2 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( m_1 = m_2 \\)',
                            'B' => '\\( m_1 \\cdot m_2 = -1 \\)',
                            'C' => '\\( m_1 = -m_2 \\)',
                            'D' => '\\( m_1 \\cdot m_2 = 1 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si dos rectas son perpendiculares en el plano, sus pendientes cumplen con la condición:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( m_1 \\cdot m_2 = -1 \\)',
                            'B' => '\\( m_1 = m_2 \\)',
                            'C' => '\\( m_1 + m_2 = 0 \\)',
                            'D' => '\\( m_1 = -m_2 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra la ecuación general de la recta que pasa por \\( P(0, 3) \\) y posee pendiente \\( m = -2 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( 2x + y - 3 = 0 \\)',
                            'B' => '\\( 2x - y + 3 = 0 \\)',
                            'C' => '\\( 2x + y + 3 = 0 \\)',
                            'D' => '\\( x + 2y - 6 = 0 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Determina la pendiente de la recta escrita en su ecuación general: \\( 3x - 4y + 8 = 0 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{3}{4} \\)',
                            'B' => '\\( -\\frac{3}{4} \\)',
                            'C' => '\\( \\frac{4}{3} \\)',
                            'D' => '3'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la ecuación ordinaria de la recta que corta al eje Y en 5 y tiene pendiente \\( m = 4 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( y = 4x + 5 \\)',
                            'B' => '\\( y = 5x + 4 \\)',
                            'C' => '\\( y = -4x + 5 \\)',
                            'D' => '\\( y = 4x - 5 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si una recta pasa por los puntos \\( A(1, 1) \\) y \\( B(3, 5) \\), su ecuación ordinaria es:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( y = 2x - 1 \\)',
                            'B' => '\\( y = 2x + 1 \\)',
                            'C' => '\\( y = -2x + 3 \\)',
                            'D' => '\\( y = x + 1 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Enuncia las condiciones analíticas de paralelismo y perpendicularidad de dos rectas basándote en la relación de sus pendientes.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Paralelas: m1 = m2 (mismas pendientes). Perpendiculares: m1 * m2 = -1 (pendientes recíprocas y opuestas).'
                    ],
                    [
                        'texto' => 'Determina la ecuación general de la recta que pasa por el punto A(2, -3) y que es perpendicular a la recta y = 2x + 1.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'La pendiente perpendicular es m = -1/2. Sustituyendo punto-pendiente: y - (-3) = -1/2 (x - 2) -> 2(y + 3) = -x + 2 -> x + 2y + 4 = 0.'
                    ]
                ],
                // Unidad 4: Parábola
                4 => [
                    [
                        'texto' => 'Una parábola se define geométricamente como el lugar geométrico de los puntos que equidistan de:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Un punto fijo llamado Foco y una recta fija llamada Directriz',
                            'B' => 'Dos puntos fijos llamados Focos',
                            'C' => 'Un punto central llamado Centro',
                            'D' => 'Las asíntotas y el eje transverso'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la ecuación ordinaria de una parábola horizontal con vértice en el origen \\( (0, 0) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( y^2 = 4px \\)',
                            'B' => '\\( x^2 = 4py \\)',
                            'C' => '\\( (y-k)^2 = 4p(x-h) \\)',
                            'D' => '\\( x^2 + y^2 = r^2 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Encuentra las coordenadas del foco de la parábola vertical: \\( x^2 = 8y \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (0, 2) \\)',
                            'B' => '\\( (2, 0) \\)',
                            'C' => '\\( (0, -2) \\)',
                            'D' => '\\( (0, 4) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la ecuación de la directriz de la parábola vertical \\( x^2 = -12y \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( y = 3 \\)', 'B' => '\\( y = -3 \\)', 'C' => '\\( x = 3 \\)', 'D' => '\\( y = 0 \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'La longitud del Lado Recto (LR) de cualquier parábola se calcula mediante el valor absoluto de:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( |4p| \\)', 'B' => '\\( |2p| \\)', 'C' => '\\( |p| \\)', 'D' => '\\( |\\frac{p}{2}| \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Identifica la ecuación ordinaria de la parábola con vértice en \\( (h, k) \\) y eje focal paralelo al eje X.',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (y - k)^2 = 4p(x - h) \\)',
                            'B' => '\\( (x - h)^2 = 4p(y - k) \\)',
                            'C' => '\\( y^2 = 4px \\)',
                            'D' => '\\( (x + h)^2 = 4py \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si una parábola tiene la ecuación \\( (x - 2)^2 = 16(y - 1) \\), ¿dónde se ubica su vértice?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (2, 1) \\)',
                            'B' => '\\( (-2, -1) \\)',
                            'C' => '\\( (1, 2) \\)',
                            'D' => '\\( (2, -1) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si el parámetro \\( p \\) de una parábola vertical con vértice en el origen es negativo (\\( p < 0 \\)), la curva se abre hacia:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Abajo', 'B' => 'Arriba', 'C' => 'La derecha', 'D' => 'La izquierda'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Define cuáles son los elementos fundamentales de una parábola (vértice, foco, directriz, lado recto, eje focal) y dibuja o explica sus relaciones geométricas.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'El Vértice es el punto máximo/mínimo. El Foco se ubica en el interior a distancia p del vértice. La Directriz es perpendicular al eje a distancia p del vértice (fuera de la curva). El Lado Recto mide 4p y cruza por el foco.'
                    ],
                    [
                        'texto' => 'Determina las coordenadas del foco, ecuación de la directriz y longitud del lado recto para la parábola: (y - 3)² = -12(x - 1).',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Vértice V(1, 3). Parámetro 4p = -12 -> p = -3 (abre a la izquierda). Foco: F(1 - 3, 3) = F(-2, 3). Directriz: x = 1 - (-3) -> x = 4. Lado Recto: |4p| = 12.'
                    ]
                ],
                // Unidad 5: Circunferencia y Elipse
                5 => [
                    [
                        'texto' => '¿Cuál es la ecuación ordinaria de una circunferencia con centro en el origen y radio \\( r \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( x^2 + y^2 = r^2 \\)',
                            'B' => '\\( (x-h)^2 + (y-k)^2 = r^2 \\)',
                            'C' => '\\( x^2 - y^2 = r^2 \\)',
                            'D' => '\\( Ax^2 + By^2 + C = 0 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Determina el centro \\( (h, k) \\) y el radio \\( r \\) de la circunferencia descrita por: \\( (x - 3)^2 + (y + 4)^2 = 25 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Centro \\( (3, -4) \\), radio \\( r = 5 \\)',
                            'B' => 'Centro \\( (-3, 4) \\), radio \\( r = 5 \\)',
                            'C' => 'Centro \\( (3, -4) \\), radio \\( r = 25 \\)',
                            'D' => 'Centro \\( (-3, 4) \\), radio \\( r = 25 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'La elipse se define como el lugar geométrico donde la suma de las distancias a dos puntos fijos llamados focos es siempre:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Constante', 'B' => 'Cero', 'C' => 'Proporcional', 'D' => 'Variable'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la ecuación ordinaria de una elipse horizontal con centro en el origen y semiejes \\( a \\) (mayor) y \\( b \\) (menor)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{x^2}{a^2} + \\frac{y^2}{b^2} = 1 \\)',
                            'B' => '\\( \\frac{x^2}{b^2} + \\frac{y^2}{a^2} = 1 \\)',
                            'C' => '\\( x^2 + y^2 = r^2 \\)',
                            'D' => '\\( \\frac{x^2}{a^2} - \\frac{y^2}{b^2} = 1 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En una elipse, la relación entre los semiejes \\( a \\) (mayor), \\( b \\) (menor) y la distancia focal \\( c \\) se expresa como:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( a^2 = b^2 + c^2 \\)',
                            'B' => '\\( c^2 = a^2 + b^2 \\)',
                            'C' => '\\( b^2 = a^2 + c^2 \\)',
                            'D' => '\\( a = b + c \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'La excentricidad \\( e \\) de una elipse mide su nivel de aplanamiento y se calcula mediante:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( e = \\frac{c}{a} \\) (donde siempre \\( e < 1 \\))',
                            'B' => '\\( e = \\frac{a}{c} \\) (donde siempre \\( e > 1 \\))',
                            'C' => '\\( e = a \\cdot c \\)',
                            'D' => '\\( e = c - a \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Dada la ecuación de la elipse \\( \\frac{x^2}{25} + \\frac{y^2}{9} = 1 \\), calcula la longitud del semieje mayor \\( a \\) y menor \\( b \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( a = 5, b = 3 \\)',
                            'B' => '\\( a = 25, b = 9 \\)',
                            'C' => '\\( a = 3, b = 5 \\)',
                            'D' => '\\( a = 5, b = 4 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si el centro de una elipse se ubica en \\( (h, k) \\), su ecuación ordinaria horizontal es:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{(x - h)^2}{a^2} + \\frac{(y - k)^2}{b^2} = 1 \\)',
                            'B' => '\\( \\frac{(x - h)^2}{b^2} + \\frac{(y - k)^2}{a^2} = 1 \\)',
                            'C' => '\\( (x - h)^2 + (y - k)^2 = 1 \\)',
                            'D' => '\\( \\frac{x^2}{a^2} + \\frac{y^2}{b^2} = 1 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica cómo transformar la ecuación ordinaria de una circunferencia \\( (x - h)^2 + (y - k)^2 = r^2 \\) a su correspondiente forma general.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Se desarrollan los binomios al cuadrado (x² - 2hx + h² + y² - 2ky + k² = r²), se iguala a cero y se agrupan los coeficientes en la forma clásica: x² + y² + Dx + Ey + F = 0.'
                    ],
                    [
                        'texto' => 'Dada la elipse con ecuación x²/16 + y²/25 = 1, indica si es horizontal o vertical, las coordenadas de sus vértices principales y sus focos.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Es vertical porque el denominador mayor está bajo la Y. Semiejes: a = 5, b = 4. Distancia focal: c = raíz(25 - 16) = 3. Vértices principales: V1(0, 5) y V2(0, -5). Focos: F1(0, 3) y F2(0, -3).'
                    ]
                ]
            ],

            // === SEMESTRE 4 (Matemáticas IV) ===
            4 => [
                // Unidad 1: Funciones polinomiales
                1 => [
                    [
                        'texto' => '¿Cuál es el dominio de cualquier función polinomial general del tipo \\( f(x) = a_n x^n + ... + a_0 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Todos los números reales \\( (-\\infty, \\infty) \\)',
                            'B' => 'Solo los reales positivos \\( [0, \\infty) \\)',
                            'C' => 'Los reales excepto donde el polinomio se hace cero',
                            'D' => 'Depende del grado del polinomio'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si \\( f(x) = 2x + 3 \\) y \\( g(x) = x^2 - 1 \\), calcula la función compuesta \\( (g \\circ f)(x) \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( 4x^2 + 12x + 8 \\)',
                            'B' => '\\( 2x^2 + 1 \\)',
                            'C' => '\\( 4x^2 + 8 \\)',
                            'D' => '\\( 4x^2 - 12x + 8 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'El teorema del residuo establece que si dividimos un polinomio \\( P(x) \\) entre \\( x - c \\), el residuo es igual a:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( P(c) \\)', 'B' => '0', 'C' => '\\( c \\)', 'D' => '\\( -P(c) \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál de los siguientes valores es una raíz real de la función polinomial \\( f(x) = x^3 - 3x^2 + 2x \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '1', 'B' => '-1', 'C' => '3', 'D' => '-2'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si el residuo de dividir \\( P(x) \\) entre \\( x - 2 \\) es cero, entonces según el teorema del factor:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( (x - 2) \\) es un factor lineal de \\( P(x) \\)',
                            'B' => '\\( 2 \\) no es una raíz de \\( P(x) \\)',
                            'C' => '\\( (x + 2) \\) es un factor de \\( P(x) \\)',
                            'D' => '\\( P(2) = 2 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el grado de la función polinomial \\( f(x) = (x^2 - 1)(x - 3)^2 \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '4', 'B' => '2', 'C' => '3', 'D' => '6'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Dada la función polinomial \\( f(x) = x^2 - 4 \\) y \\( g(x) = x + 2 \\), calcula \\( (f/g)(x) \\) para \\( x \\neq -2 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( x - 2 \\)', 'B' => '\\( x + 2 \\)', 'C' => '\\( x^2 - x - 6 \\)', 'D' => '1'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuántas raíces reales o complejas (contando multiplicidad) tiene un polinomio de grado 4 según el Teorema Fundamental del Álgebra?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Exactamente 4', 'B' => 'Máximo 4', 'C' => 'Mínimo 4', 'D' => 'Depende de los coeficientes'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Enuncia detalladamente el Teorema del Residuo y el Teorema del Factor para polinomios, describiendo su utilidad práctica.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'El Teorema del Residuo dice que el sobrante de dividir P(x) entre (x-c) es P(c). El Teorema del Factor añade que si P(c)=0, entonces (x-c) es un divisor exacto de P(x), permitiendo factorizarlo.'
                    ],
                    [
                        'texto' => 'Encuentra todas las raíces de la función polinomial f(x) = x³ - 6x² + 11x - 6.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Por evaluación o división sintética con divisores de -6: x = 1 es raíz. Dividiendo queda x²-5x+6=0, que se factoriza como (x-2)(x-3)=0. Las raíces reales son x = 1, x = 2 y x = 3.'
                    ]
                ],
                // Unidad 2: Racionales y radicales
                2 => [
                    [
                        'texto' => 'Para la función racional \\( f(x) = \\frac{1}{x - 3} \\), ¿qué valor de \\( x \\) se excluye de su dominio?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '3', 'B' => '-3', 'C' => '0', 'D' => '1'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la ecuación de la asíntota vertical de la función racional \\( f(x) = \\frac{2x + 1}{x + 5} \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( x = -5 \\)', 'B' => '\\( x = 5 \\)', 'C' => '\\( x = 2 \\)', 'D' => '\\( y = 2 \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Identifica la ecuación de la asíntota horizontal de la función \\( f(x) = \\frac{3x - 2}{x + 1} \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( y = 3 \\)', 'B' => '\\( y = -1 \\)', 'C' => '\\( x = -1 \\)', 'D' => 'No tiene asíntota horizontal'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el dominio de la función con radical \\( f(x) = \\sqrt{x - 4} \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( [4, \\infty) \\)',
                            'B' => '\\( (-\\infty, 4] \\)',
                            'C' => '\\( (4, \\infty) \\)',
                            'D' => 'Todos los números reales'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Para que exista una asíntota horizontal en una función racional, el grado del polinomio del numerador debe ser:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Menor o igual al grado del denominador',
                            'B' => 'Estrictamente mayor al grado del denominador',
                            'C' => 'Siempre igual a 1',
                            'D' => 'Múltiplo del grado del denominador'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el dominio de la función radical con índice impar \\( f(x) = \\sqrt[3]{x + 2} \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Todos los números reales \\( (-\\infty, \\infty) \\)',
                            'B' => '\\( [-2, \\infty) \\)',
                            'C' => 'Todos los reales excepto \\( x = -2 \\)',
                            'D' => '\\( [0, \\infty) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál de las siguientes condiciones define las asíntotas verticales de una función racional simplificada?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Los valores de X que hacen cero el denominador',
                            'B' => 'Los valores de X que hacen cero el numerador',
                            'C' => 'La razón de los coeficientes principales',
                            'D' => 'Los cortes con el eje Y'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Calcula el dominio de la función racional: \\( f(x) = \\frac{x - 1}{x^2 - 16} \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Todos los reales excepto \\( x = \\pm 4 \\)',
                            'B' => 'Todos los reales excepto \\( x = 4 \\)',
                            'C' => 'El intervalo \\( [16, \\infty) \\)',
                            'D' => 'Todos los reales excepto \\( x = 1 \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica el comportamiento asintótico horizontal de una función racional cuando el grado del numerador es menor que el del denominador, y cuando ambos grados son iguales.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Si el grado del numerador es menor, la asíntota es y = 0. Si los grados son iguales, la asíntota es la recta y = a/b, donde a y b son los coeficientes principales.'
                    ],
                    [
                        'texto' => 'Determina el dominio y las asíntotas (verticales y horizontales) de la función racional f(x) = (2x - 4) / (x - 3).',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Dominio: R - {3}. Asíntota Vertical: x = 3 (donde el denominador es 0). Asíntota Horizontal: y = 2/1 = 2 (grados iguales).'
                    ]
                ],
                // Unidad 3: Exponenciales y logarítmicas
                3 => [
                    [
                        'texto' => '¿Cuál es la función inversa de la función exponencial \\( f(x) = a^x \\) (donde \\( a > 0, a \\neq 1 \\))?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Función logarítmica \\( g(x) = \\log_a(x) \\)',
                            'B' => 'Función cuadrática \\( g(x) = x^a \\)',
                            'C' => 'Función racional \\( g(x) = \\frac{a}{x} \\)',
                            'D' => 'Función radical \\( g(x) = \\sqrt[a]{x} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'De acuerdo a las propiedades de los logaritmos, la expresión \\( \\log_b(xy) \\) es equivalente a:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\log_b(x) + \\log_b(y) \\)',
                            'B' => '\\( \\log_b(x) \\cdot \\log_b(y) \\)',
                            'C' => '\\( y \\cdot \\log_b(x) \\)',
                            'D' => '\\( \\log_b(x) - \\log_b(y) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Resuelve la siguiente ecuación exponencial simple: \\( 2^{x-1} = 8 \\).',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '4', 'B' => '3', 'C' => '2', 'D' => '5'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿A cuánto equivale el logaritmo base 10 de 1000 (\\( \\log_{10}(1000) \\))?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '3', 'B' => '10', 'C' => '2', 'D' => '4'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Según propiedades de logaritmos, la expresión \\( \\log_b(x^k) \\) es igual a:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( k \\cdot \\log_b(x) \\)',
                            'B' => '\\( \\log_b(x)^k \\)',
                            'C' => '\\( k + \\log_b(x) \\)',
                            'D' => '\\( \\frac{\\log_b(x)}{k} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Una población bacteriana se duplica cada hora. Si la población inicial es de 50 bacterias, ¿cuál ecuación modela la cantidad \\( P \\) tras \\( t \\) horas?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( P(t) = 50 \\cdot 2^t \\)',
                            'B' => '\\( P(t) = 50 \\cdot 2t \\)',
                            'C' => '\\( P(t) = 2 \\cdot 50^t \\)',
                            'D' => '\\( P(t) = 50 + 2^t \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el valor de \\( \\ln(e) \\) (logaritmo natural de la constante de Euler)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '1', 'B' => '0', 'C' => '\\( e \\)', 'D' => '10'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el dominio de la función logarítmica básica \\( f(x) = \\log_{10}(x) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Los números reales positivos \\( (0, \\infty) \\)',
                            'B' => 'Todos los números reales',
                            'C' => 'El intervalo cerrado \\( [0, \\infty) \\)',
                            'D' => 'Los reales excepto el cero'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Escribe las tres propiedades fundamentales de los logaritmos (del producto, del cociente y de la potencia).',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => '1) log(xy) = log(x) + log(y); 2) log(x/y) = log(x) - log(y); 3) log(x^k) = k * log(x).'
                    ],
                    [
                        'texto' => 'Resuelve para x la ecuación logarítmica: log_2(x + 2) = 3.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Pasando a forma exponencial: x + 2 = 2³ -> x + 2 = 8 -> x = 6.'
                    ]
                ],
                // Unidad 4: Funciones trigonométricas
                4 => [
                    [
                        'texto' => '¿A cuántos grados sexagesimales equivale una medida angular de \\( \\pi \\text{ radianes} \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '\\( 180^\\circ \\)', 'B' => '\\( 360^\\circ \\)', 'C' => '\\( 90^\\circ \\)', 'D' => '\\( 270^\\circ \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'En el círculo unitario, la coordenada \\( y \\) de un punto asociado a un ángulo \\( \\theta \\) representa el valor de:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => 'Seno', 'B' => 'Coseno', 'C' => 'Tangente', 'D' => 'Secante'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el periodo estándar de las funciones trigonométricas fundamentales seno y coseno?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( 2\\pi \\text{ radianes} \\) (o \\( 360^\\circ \\))',
                            'B' => '\\( \\pi \\text{ radianes} \\) (o \\( 180^\\circ \\))',
                            'C' => '\\( \\frac{\\pi}{2} \\text{ radianes} \\)',
                            'D' => '\\( 4\\pi \\text{ radianes} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el valor del periodo de la función tangente básica \\( f(x) = \\tan(x) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\pi \\text{ radianes} \\) (o \\( 180^\\circ \\))',
                            'B' => '\\( 2\\pi \\text{ radianes} \\)',
                            'C' => '\\( \\frac{\\pi}{2} \\text{ radianes} \\)',
                            'D' => 'Infinito'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es el rango o conjunto de valores de salida de la función básica \\( f(x) = \\sin(x) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( [-1, 1] \\)',
                            'B' => '\\( (-\\infty, \\infty) \\)',
                            'C' => '\\( [0, 1] \\)',
                            'D' => '\\( (-1, 1) \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Si convertimos una medida de \\( 60^\\circ \\) sexagesimales a radianes, obtenemos:',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => '\\( \\frac{\\pi}{3} \\text{ rad} \\)',
                            'B' => '\\( \\frac{\\pi}{6} \\text{ rad} \\)',
                            'C' => '\\( \\frac{\\pi}{2} \\text{ rad} \\)',
                            'D' => '\\( \\frac{2\\pi}{3} \\text{ rad} \\)'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Para qué valores del ángulo \\( x \\) (en radianes) la función tangente no está definida?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => [
                            'A' => 'Múltiplos impares de \\( \\frac{\\pi}{2} \\) (como \\( \\pm\\frac{\\pi}{2}, \\pm\\frac{3\\pi}{2} \\))',
                            'B' => 'Múltiplos enteros de \\( \\pi \\)',
                            'C' => 'Únicamente en el origen \\( x = 0 \\)',
                            'D' => 'Siempre está definida'
                        ],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => '¿Cuál es la amplitud de la función trigonométrica modificada \\( y = 3 \\cos(2x) \\)?',
                        'tipo' => 'opcion_multiple',
                        'opciones' => ['A' => '3', 'B' => '2', 'C' => '\\( 2\\pi \\)', 'D' => '\\( \\frac{3}{2} \\)'],
                        'correcta' => 'A'
                    ],
                    [
                        'texto' => 'Explica conceptualmente cómo se definen las funciones trigonométricas seno y coseno utilizando las coordenadas de un punto sobre la circunferencia unitaria.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'En el círculo de radio r=1, para un ángulo central dado, la proyección en el eje horizontal X representa el Coseno del ángulo y la proyección en el eje vertical Y representa el Seno del ángulo.'
                    ],
                    [
                        'texto' => 'Convierte 135° sexagesimales a radianes simplificando la fracción y expresa el resultado en términos de pi.',
                        'tipo' => 'respuesta_abierta',
                        'opciones' => null,
                        'correcta' => 'Radianes = 135 * (pi / 180) = (135/180)*pi = (27/36)*pi = 3/4 * pi radianes.'
                    ]
                ]
            ]
        ];

        // Insertar las preguntas en la base de datos
        foreach ($questionsData as $semestreNumero => $unidadesData) {
            $semestre = $semestres->get($semestreNumero);
            if (!$semestre) {
                continue;
            }

            $unidades = Unidad::where('semestre_id', $semestre->id)->get()->keyBy('numero');

            foreach ($unidadesData as $unidadNumero => $preguntasList) {
                $unidad = $unidades->get($unidadNumero);
                if (!$unidad) {
                    continue;
                }

                // Borrar preguntas existentes para evitar duplicación
                Pregunta::where('unidad_id', $unidad->id)->delete();

                foreach ($preguntasList as $pData) {
                    Pregunta::create([
                        'unidad_id' => $unidad->id,
                        'texto_pregunta' => $pData['texto'],
                        'tipo_pregunta' => $pData['tipo'],
                        'opciones_json' => $pData['opciones'],
                        'respuesta_correcta' => $pData['correcta']
                    ]);
                }
            }
        }
    }
}
