<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContentJsonSeeder extends Seeder
{
    public function run(): void
    {
        $directoryPath = storage_path('content');

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        // Definimos la información completa de las 17 unidades con 2 ejemplos y 5 ejercicios por punto de teoría
        $unidadesContent = [
            // SEMESTRE 1: Matemáticas I
            'matematicas_1_u1' => [
                'introduccion' => 'En esta unidad iniciaremos el estudio de las matemáticas del bachillerato. Analizaremos la transición de los números enteros a los números reales, y revisaremos las reglas esenciales para operar con ellos de forma exacta y coherente, utilizando el lenguaje algebraico.',
                'objetivos' => [
                    'Comprender la diferencia y relación entre los números enteros y los números reales.',
                    'Aplicar de forma rigurosa la jerarquía de las operaciones en expresiones numéricas.',
                    'Traducir problemas del lenguaje común al lenguaje algebraico.'
                ],
                'conocimientos_previos' => [
                    'Operaciones aritméticas básicas (suma, resta, multiplicación, división).',
                    'Nociones básicas de variables y constantes.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Números reales y la recta numérica',
                        'texto' => 'Los números reales \\(\\mathbb{R}\\) comprenden tanto a los números racionales \\(\\mathbb{Q}\\) como a los números irracionales. Gráficamente, a cada número real le corresponde un punto único sobre la recta numérica.'
                    ],
                    [
                        'titulo' => '2. Jerarquía de operaciones',
                        'texto' => 'Para resolver expresiones con múltiples operadores, debemos seguir un orden de precedencia estricto:\n1. Paréntesis y agrupación: \\(( ), [ ], \\{ \\}\\)\n2. Potencias y raíces: \\(x^a\\), \\(\\sqrt{x}\\)\n3. Multiplicaciones y divisiones (de izquierda a derecha)\n4. Sumas y restas (de izquierda a derecha)'
                    ],
                    [
                        'titulo' => '3. Lenguaje Algebraico',
                        'texto' => 'El lenguaje algebraico nos permite generalizar relaciones aritméticas mediante el uso de letras (variables).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Números Reales (\\(\\mathbb{R}\\))',
                        'definicion' => 'El conjunto de todos los números racionales e irracionales.'
                    ],
                    [
                        'concepto' => 'Jerarquía de Operaciones',
                        'definicion' => 'Conjunto de reglas convencionales que indican qué operaciones realizar primero.'
                    ],
                    [
                        'concepto' => 'Variable',
                        'definicion' => 'Símbolo que representa una cantidad que puede cambiar.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Clasificación de Números',
                        'explicacion' => 'Clasifica los números \\(\\frac{3}{4}\\) y \\(\\sqrt{2}\\) como racionales o irracionales.',
                        'solucion' => '1. \\(\\frac{3}{4}\\) se puede expresar como fracción de enteros, por lo tanto, es racional (\\(\\mathbb{Q}\\)).\n2. \\(\\sqrt{2} \\approx 1.4142...\\) no se puede expresar como fracción, por lo tanto, es irracional.'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Orden en la Recta Numérica',
                        'explicacion' => 'Ubica de forma relativa en la recta numérica los puntos \\(A(-1.5)\\) y \\(B(0.75)\\).',
                        'solucion' => '1. \\(A(-1.5)\\) está situado a la izquierda del origen (0) a una distancia de 1.5 unidades.\n2. \\(B(0.75)\\) está situado a la derecha del origen a una distancia de 0.75 unidades. Por lo tanto, \\(A < B\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Operación Básica',
                        'explicacion' => 'Simplifica la expresión: \\(8 + 2 \\times 5\\).',
                        'solucion' => '1. Multiplicamos primero: \\(2 \\times 5 = 10\\).\n2. Realizamos la suma: \\(8 + 10 = 18\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Operación con Paréntesis',
                        'explicacion' => 'Resuelve: \\(3 \\times (4 + 6) \\div 2\\).',
                        'solucion' => '1. Resolvemos el paréntesis: \\(4 + 6 = 10\\).\n2. Multiplicamos: \\(3 \\times 10 = 30\\).\n3. Dividimos: \\(30 \\div 2 = 15\\).'
                    ],
                    // Punto 3
                    [
                        'titulo' => 'Ejemplo 3.1: Traducción a Álgebra',
                        'explicacion' => "Traduce la frase: 'El doble de un número aumentado en tres'.",
                        'solucion' => "1. Definimos el número como \\(x\\).\n2. El doble del número es \\(2x\\).\n3. Aumentado en tres es \\(2x + 3\\)."
                    ],
                    [
                        'titulo' => 'Ejemplo 3.2: Traducción con Potencias',
                        'explicacion' => "Traduce la frase: 'La mitad del cuadrado de un número'.",
                        'solucion' => "1. Definimos el número como \\(y\\).\n2. Su cuadrado es \\(y^2\\).\n3. La mitad es \\(\\frac{y^2}{2}\\)."
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Sumar antes de multiplicar',
                        'ejemplo_incorrecto' => 'En la operación \\(2 + 3 \\times 4\\), hacer \\(5 \\times 4 = 20\\).',
                        'correccion' => 'La forma correcta es \\(2 + (3 \\times 4) = 2 + 12 = 14\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Determina si \\(\\pi\\) es racional o irracional.', 'guia' => 'Pista: Observa si tiene decimales periódicos o no.'],
                    ['instruccion' => 'Ordena de menor a mayor: \\(-3\\), \\(\\sqrt{4}\\), \\(-0.5\\).', 'guia' => 'Pista: Convierte cada uno a número decimal e identifica su posición en la recta.'],
                    ['instruccion' => 'Identifica a qué conjunto pertenece el número \\(-5\\).', 'guia' => 'Pista: Es un entero negativo, que también pertenece al conjunto de los racionales.'],
                    ['instruccion' => 'Determina si \\(0.333...\\) se puede expresar como fracción.', 'guia' => 'Pista: Sí, equivale a \\(\\frac{1}{3}\\), por lo que es racional.'],
                    ['instruccion' => 'Determina si \\(\\sqrt{9}\\) es racional.', 'guia' => 'Pista: Simplifica la raíz, \\(\\sqrt{9} = 3\\), por lo tanto es racional.'],
                    // Punto 2
                    ['instruccion' => 'Simplifica: \\(12 - 3 \\times 2\\).', 'guia' => 'Pista: Realiza primero la multiplicación \\(3 \\times 2\\).'],
                    ['instruccion' => 'Simplifica: \\((8 - 2) \\times 4\\).', 'guia' => 'Pista: Primero resuelve la resta dentro del paréntesis.'],
                    ['instruccion' => 'Simplifica: \\(5 \\times 2 + 18 \\div 3\\).', 'guia' => 'Pista: Realiza la multiplicación y división de izquierda a derecha antes de sumar.'],
                    ['instruccion' => 'Simplifica: \\(2^3 + 4 \\times 3\\).', 'guia' => 'Pista: Calcula la potencia \\(2^3 = 8\\) antes de multiplicar.'],
                    ['instruccion' => 'Simplifica: \\(15 - (6 + 3) \\times 2\\).', 'guia' => 'Pista: Primero haz el paréntesis, luego multiplica por 2 y finalmente resta.'],
                    // Punto 3
                    ['instruccion' => "Traduce: 'El triple de un número'.", 'guia' => 'Pista: Usa la variable \\(x\\) y multiplícala por 3.'],
                    ['instruccion' => "Traduce: 'Un número disminuido en siete'.", 'guia' => 'Pista: Resta 7 de la variable \\(x\\).'],
                    ['instruccion' => "Traduce: 'La suma de dos números consecutivos'.", 'guia' => 'Pista: Representa los números como \\(x\\) y \\(x+1\\).'],
                    ['instruccion' => "Traduce: 'El cuadrado de un número más uno'.", 'guia' => 'Pista: Eleva la variable \\(x\\) al cuadrado y súmale 1.'],
                    ['instruccion' => "Traduce: 'El producto de dos números'.", 'guia' => 'Pista: Usa dos variables distintas, ej. \\(a \\times b\\).']
                ],
                'resumen' => 'En esta lección consolidamos las herramientas aritméticas básicas, comprendimos la jerarquía de las operaciones y aprendimos a modelar expresiones algebraicas.',
                'bibliografia' => [
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.',
                    'Baldor, A. (2007). Álgebra. Grupo Editorial Patria.'
                ]
            ],
            'matematicas_1_u2' => [
                'introduccion' => 'La variación lineal describe relaciones donde un cambio en una variable produce un cambio proporcional y constante en otra. Analizaremos tablas, gráficas y la clásica ecuación de la recta.',
                'objetivos' => [
                    'Identificar relaciones de proporcionalidad directa.',
                    'Representar funciones lineales en tablas y gráficas.',
                    'Modelar mediante la ecuación y = mx + b.'
                ],
                'conocimientos_previos' => [
                    'Operaciones aritméticas básicas y ubicación de coordenadas.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Proporcionalidad directa y razón de cambio',
                        'texto' => 'Dos variables son directamente proporcionales si su cociente \\(\\frac{y}{x} = k\\) es constante.'
                    ],
                    [
                        'titulo' => '2. Representación algebraica: \\(y = mx + b\\)',
                        'texto' => 'Una relación lineal se escribe de la forma \\(y = mx + b\\) donde \\(m\\) es la pendiente y \\(b\\) la ordenada al origen.'
                    ],
                    [
                        'titulo' => '3. Pendiente de una recta',
                        'texto' => 'La pendiente \\(m\\) mide la inclinación de la recta y se calcula a partir de dos puntos mediante \\(m = \\frac{y_2 - y_1}{x_2 - x_1}\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Pendiente (m)',
                        'definicion' => 'Tasa de cambio constante de y respecto a x.'
                    ],
                    [
                        'concepto' => 'Ordenada al origen (b)',
                        'definicion' => 'Valor de y cuando x = 0.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Costo Proporcional',
                        'explicacion' => 'Si 3 manzanas cuestan $15, ¿cuál es la constante de proporcionalidad?',
                        'solucion' => '1. Dividimos el costo entre la cantidad: \\(\\frac{15}{3} = 5\\).\n2. La constante de proporcionalidad es \\(k = 5\\) pesos por manzana.'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Razón de Cambio',
                        'explicacion' => 'Un auto recorre 120 km en 2 horas a velocidad constante. Halla la razón de cambio.',
                        'solucion' => '1. Razón = \\(\\frac{120\\text{ km}}{2\\text{ h}} = 60\\text{ km/h}\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Identificar m y b',
                        'explicacion' => 'Identifica la pendiente y la ordenada de la recta \\(y = 2x + 3\\).',
                        'solucion' => '1. Comparamos con \\(y = mx + b\\).\n2. Obtenemos \\(m = 2\\) y \\(b = 3\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Evaluar Función Lineal',
                        'explicacion' => 'Encuentra el valor de \\(y\\) si \\(x = 4\\) en la recta \\(y = -3x + 5\\).',
                        'solucion' => '1. Sustituimos \\(x=4\\): \\(y = -3(4) + 5\\).\n2. Calculamos: \\(y = -12 + 5 = -7\\).'
                    ],
                    // Punto 3
                    [
                        'titulo' => 'Ejemplo 3.1: Pendiente Positiva',
                        'explicacion' => 'Halla la pendiente que pasa por \\(A(1, 2)\\) y \\(B(3, 6)\\).',
                        'solucion' => '1. Aplicamos la fórmula: \\(m = \\frac{6 - 2}{3 - 1} = \\frac{4}{2} = 2\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 3.2: Pendiente Negativa',
                        'explicacion' => 'Halla la pendiente que pasa por \\(C(0, 5)\\) y \\(D(2, 1)\\).',
                        'solucion' => '1. Aplicamos la fórmula: \\(m = \\frac{1 - 5}{2 - 0} = \\frac{-4}{2} = -2\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Invertir la fórmula de la pendiente',
                        'ejemplo_incorrecto' => 'Calcular la pendiente como \\(\\frac{x_2 - x_1}{y_2 - y_1}\\).',
                        'correccion' => 'La fórmula correcta es \\(m = \\frac{y_2 - y_1}{x_2 - x_1}\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Si 2 cuadernos cuestan $40, ¿cuánto cuestan 5?', 'guia' => 'Pista: Encuentra primero el precio unitario dividiendo 40 entre 2.'],
                    ['instruccion' => 'Determina la constante de proporcionalidad si \\(y=10\\) cuando \\(x=2\\).', 'guia' => 'Pista: Aplica la fórmula \\(k = \\frac{y}{x}\\).'],
                    ['instruccion' => 'Un tanque de agua de 100 litros se llena a 5 litros por minuto. ¿Cuál es la razón de cambio?', 'guia' => 'Pista: La tasa de llenado constante es la razón de cambio (5 litros/minuto).'],
                    ['instruccion' => 'Si recorres 10 metros en 2 segundos, halla la constante de velocidad.', 'guia' => 'Pista: Divide la distancia recorrida entre el tiempo empleado.'],
                    ['instruccion' => 'En una receta, 2 tazas de harina sirven para 8 galletas. ¿Cuántas galletas se hacen con 1 taza?', 'guia' => 'Pista: Simplifica la proporción dividiendo 8 entre 2.'],
                    // Punto 2
                    ['instruccion' => 'Identifica \\(m\\) y \\(b\\) en \\(y = 4x - 1\\).', 'guia' => 'Pista: El número que acompaña a la variable es \\(m\\), el independiente es \\(b\\).'],
                    ['instruccion' => 'Grafica la recta \\(y = x + 2\\) tabulando con \\(x=0\\) y \\(x=1\\).', 'guia' => 'Pista: Cuando \\(x=0\\), \\(y=2\\); cuando \\(x=1\\), \\(y=3\\).'],
                    ['instruccion' => 'Halla la ordenada al origen de \\(y = -x\\).', 'guia' => 'Pista: Al no haber término constante, la ordenada \\(b\\) es igual a 0.'],
                    ['instruccion' => 'Obtén la ecuación lineal con pendiente \\(m=3\\) y ordenada al origen \\(b=5\\).', 'guia' => 'Pista: Escribe la ecuación sustituyendo directamente en \\(y = mx + b\\).'],
                    ['instruccion' => 'Determina si la recta \\(y = -2x + 4\\) es creciente o decreciente.', 'guia' => 'Pista: Observa el signo de la pendiente \\(m\\). Al ser negativo, decrece.'],
                    // Punto 3
                    ['instruccion' => 'Calcula la pendiente que pasa por \\((2, 3)\\) y \\((4, 7)\\).', 'guia' => 'Pista: Resta las coordenadas \\(y\\) en el numerador y las \\(x\\) en el denominador.'],
                    ['instruccion' => 'Calcula la pendiente que pasa por \\((-1, 2)\\) y \\((1, 6)\\).', 'guia' => 'Pista: Ten cuidado con los signos al restar: \\(1 - (-1) = 2\\).'],
                    ['instruccion' => 'Calcula la pendiente que pasa por \\((0, 0)\\) y \\((3, 9)\\).', 'guia' => 'Pista: Aplica la fórmula estándar simplificada desde el origen.'],
                    ['instruccion' => 'Determina la pendiente si la recta es horizontal y pasa por \\((1, 4)\\) y \\((5, 4)\\).', 'guia' => 'Pista: Como el cambio en \\(y\\) es cero, la pendiente es 0.'],
                    ['instruccion' => 'Calcula la pendiente si pasa por \\((2, -3)\\) y \\((5, -9)\\).', 'guia' => 'Pista: Aplica la fórmula con valores negativos.' ]
                ],
                'resumen' => 'La variación lineal se caracteriza por una pendiente constante y se modela algebraicamente con la forma explícita \\(y = mx + b\\).',
                'bibliografia' => [
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.',
                    'Sullivan, M. (2006). Álgebra y Trigonometría. Pearson Educación.'
                ]
            ],
            'matematicas_1_u3' => [
                'introduccion' => 'Las ecuaciones lineales son la base del razonamiento algebraico aplicado a la resolución de problemas. Aprenderemos a interpretar enunciados reales, formular ecuaciones y aplicar reglas de despeje.',
                'objetivos' => [
                    'Comprender la igualdad matemática.',
                    'Despejar variables lineales aplicando operaciones inversas.',
                    'Modelar y resolver problemas mediante ecuaciones de primer grado.'
                ],
                'conocimientos_previos' => [
                    'Manejo de variables, simplificación de términos y operaciones aritméticas.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Concepto de igualdad y propiedades',
                        'texto' => 'Una ecuación es una igualdad entre dos expresiones que se mantiene al aplicar la misma operación en ambos miembros.'
                    ],
                    [
                        'titulo' => '2. Resolución de ecuaciones de primer grado',
                        'texto' => 'Consiste en aislar la incógnita utilizando operaciones inversas para encontrar su valor equivalente.'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Ecuación lineal',
                        'definicion' => 'Igualdad en la que la variable tiene exponente 1.'
                    ],
                    [
                        'concepto' => 'Despejar',
                        'definicion' => 'Aislar una variable en un miembro de la ecuación.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Ecuación Aditiva',
                        'explicacion' => 'Resuelve: \\(x + 4 = 10\\).',
                        'solucion' => '1. Restamos 4 en ambos lados: \\(x + 4 - 4 = 10 - 4\\).\n2. Obtenemos \\(x = 6\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Ecuación Multiplicativa',
                        'explicacion' => 'Resuelve: \\(3x = 12\\).',
                        'solucion' => '1. Dividimos ambos lados entre 3: \\(\\frac{3x}{3} = \\frac{12}{3}\\).\n2. Obtenemos \\(x = 4\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Ecuación con dos operaciones',
                        'explicacion' => 'Resuelve: \\(2x + 5 = 15\\).',
                        'solucion' => '1. Restamos 5: \\(2x = 10\\).\n2. Dividimos entre 2: \\(x = 5\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Incógnitas en ambos miembros',
                        'explicacion' => 'Resuelve: \\(4x - 3 = 2x + 7\\).',
                        'solucion' => '1. Restamos \\(2x\\) en ambos miembros: \\(2x - 3 = 7\\).\n2. Sumamos 3: \\(2x = 10\\).\n3. Dividimos entre 2: \\(x = 5\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Olvidar el cambio de signo',
                        'ejemplo_incorrecto' => 'Pasar \\(x + 2 = 5\\) a \\(x = 5 + 2\\).',
                        'correccion' => 'Al mover términos, se usa la operación inversa: \\(x = 5 - 2 = 3\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Resuelve: \\(x - 5 = 2\\).', 'guia' => 'Pista: Suma 5 en ambos miembros de la ecuación.'],
                    ['instruccion' => 'Resuelve: \\(2x = 10\\).', 'guia' => 'Pista: Divide ambos miembros entre 2.'],
                    ['instruccion' => 'Resuelve: \\(x + 7 = 3\\).', 'guia' => 'Pista: Resta 7 de ambos miembros de la ecuación.'],
                    ['instruccion' => 'Resuelve: \\(\\frac{x}{3} = 4\\).', 'guia' => 'Pista: Multiplica ambos miembros por 3.'],
                    ['instruccion' => 'Resuelve: \\(5 + x = 12\\).', 'guia' => 'Pista: Resta 5 de ambos miembros de la ecuación.'],
                    // Punto 2
                    ['instruccion' => 'Resuelve: \\(3x - 2 = 13\\).', 'guia' => 'Pista: Suma primero 2 en ambos lados y luego divide entre 3.'],
                    ['instruccion' => 'Resuelve: \\(5x + 4 = 24\\).', 'guia' => 'Pista: Resta 4 a ambos lados de la ecuación.'],
                    ['instruccion' => 'Resuelve: \\(2x + 9 = x + 15\\).', 'guia' => 'Pista: Resta \\(x\\) en ambos lados para agrupar variables a la izquierda.'],
                    ['instruccion' => 'Resuelve: \\(7x - 5 = 3x + 11\\).', 'guia' => 'Pista: Agrupa las \\(x\\) del lado izquierdo y los enteros del lado derecho.'],
                    ['instruccion' => 'Resuelve: \\(3(x + 2) = 18\\).', 'guia' => 'Pista: Multiplica primero el 3 por los términos dentro del paréntesis.']
                ],
                'resumen' => 'Despejar una ecuación requiere aplicar sistemáticamente operaciones inversas en ambos miembros hasta aislar la incógnita.',
                'bibliografia' => [
                    'Baldor, A. (2007). Álgebra. Grupo Editorial Patria.',
                    'Sullivan, M. (2006). Álgebra y Trigonometría. Pearson Educación.'
                ]
            ],
            'matematicas_1_u4' => [
                'introduccion' => 'Los sistemas de ecuaciones lineales de 2x2 representan situaciones donde dos condiciones distintas dependen de dos variables desconocidas simultáneamente. Resolveremos estos sistemas mediante métodos algebraicos y gráficos.',
                'objetivos' => [
                    'Comprender la interpretación gráfica de un sistema 2x2.',
                    'Resolver sistemas 2x2 por sustitución, igualación y reducción.',
                    'Modelar problemas reales con dos incógnitas.'
                ],
                'conocimientos_previos' => [
                    'Despejes algebraicos básicos y graficación de líneas rectas.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. ¿Qué es un sistema 2x2?',
                        'texto' => 'Es un conjunto de dos ecuaciones con dos incógnitas. La solución es el par ordenado \\((x, y)\\) que satisface ambas simultáneamente.'
                    ],
                    [
                        'titulo' => '2. Métodos de Resolución Algebraicos',
                        'texto' => 'Los métodos principales para resolver sistemas son la sustitución, la igualación y la reducción (suma y resta).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Intersección',
                        'definicion' => 'Punto común en el plano donde se cruzan las rectas de las ecuaciones.'
                    ],
                    [
                        'concepto' => 'Reducción',
                        'definicion' => 'Método para eliminar una variable sumando o restando las ecuaciones multiplicadas por constantes.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Comprobación de Solución',
                        'explicacion' => 'Verifica si \\((2, 3)\\) es solución del sistema: \\(x + y = 5\\) y \\(2x - y = 1\\).',
                        'solucion' => '1. Primera ecuación: \\(2 + 3 = 5\\) (Sí cumple).\n2. Segunda ecuación: \\(2(2) - 3 = 4 - 3 = 1\\) (Sí cumple). Por lo tanto, \\((2, 3)\\) es solución.'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Interpretación Gráfica',
                        'explicacion' => 'Determina el punto de intersección de las rectas \\(y = x\\) e \\(y = -x + 2\\).',
                        'solucion' => '1. Igualamos las ecuaciones: \\(x = -x + 2\\).\n2. Despejamos: \\(2x = 2 \\implies x = 1\\).\n3. Evaluamos para obtener \\(y\\): \\(y = 1\\). El punto de intersección es \\((1, 1)\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Método de Sustitución',
                        'explicacion' => 'Resuelve por sustitución: \\(x + y = 4\\), \\(y = 2x - 1\\).',
                        'solucion' => '1. Sustituimos \\(y\\) en la primera ecuación: \\(x + (2x - 1) = 4\\).\n2. Resolvemos: \\(3x - 1 = 4 \\implies 3x = 5 \\implies x = \\frac{5}{3}\\).\n3. Calculamos \\(y\\): \\(y = 2(\\frac{5}{3}) - 1 = \\frac{7}{3}\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Método de Reducción',
                        'explicacion' => 'Resuelve por reducción: \\(x + y = 3\\), \\(x - y = 1\\).',
                        'solucion' => '1. Sumamos las dos ecuaciones de forma directa: \\(2x = 4 \\implies x = 2\\).\n2. Sustituimos \\(x\\) en la primera ecuación: \\(2 + y = 3 \\implies y = 1\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'No multiplicar todos los términos de una ecuación al buscar reducción',
                        'ejemplo_incorrecto' => 'Multiplicar \\(x + y = 3\\) por 2 y escribir \\(2x + 2y = 3\\).',
                        'correccion' => 'Se debe multiplicar también el término constante: \\(2x + 2y = 6\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Verifica si \\((1, 4)\\) es solución de \\(x + y = 5\\) e \\(y = 4x\\).', 'guia' => 'Pista: Sustituye \\(x=1\\) e \\(y=4\\) en ambas ecuaciones y comprueba.'],
                    ['instruccion' => 'Si dos rectas en un sistema son paralelas, ¿cuántas soluciones tiene el sistema?', 'guia' => 'Pista: Las rectas paralelas nunca se cruzan, por lo que no hay puntos en común.'],
                    ['instruccion' => '¿Es \\((0, 2)\\) solución de \\(2x + 3y = 6\\) y \\(x - y = -2\\)?', 'guia' => 'Pista: Evalúa el punto en ambas ecuaciones y comprueba las igualdades.'],
                    ['instruccion' => 'Identifica si el sistema \\(y=x\\) e \\(y=x+2\\) tiene solución.', 'guia' => 'Pista: Al tener la misma pendiente m=1 y diferente ordenada, son paralelas.'],
                    ['instruccion' => 'Explica qué significa que un sistema tenga infinitas soluciones.', 'guia' => 'Pista: Ocurre cuando ambas ecuaciones representan gráficamente la misma recta.'],
                    // Punto 2
                    ['instruccion' => 'Resuelve por eliminación/reducción: \\(x + y = 5\\), \\(x - y = 1\\).', 'guia' => 'Pista: Suma las ecuaciones de forma directa para cancelar la variable \\(y\\).'],
                    ['instruccion' => 'Resuelve por sustitución: \\(y = 2x\\), \\(x + y = 9\\).', 'guia' => 'Pista: Sustituye la expresión \\(2x\\) en lugar de \\(y\\) en la segunda ecuación.'],
                    ['instruccion' => 'Resuelve por reducción: \\(2x + y = 8\\), \\(3x - y = 7\\).', 'guia' => 'Pista: Suma verticalmente las ecuaciones para cancelar \\(y\\).'],
                    ['instruccion' => 'Resuelve por igualación: \\(y = 3x - 1\\), \\(y = x + 3\\).', 'guia' => 'Pista: Iguala los lados derechos de ambas ecuaciones: \\(3x - 1 = x + 3\\).'],
                    ['instruccion' => 'Resuelve: \\(x + 2y = 7\\), \\(x - y = 1\\).', 'guia' => 'Pista: Resta la segunda ecuación de la primera para cancelar \\(x\\).']
                ],
                'resumen' => 'Resolver sistemas 2x2 consiste en encontrar la intersección común entre dos condiciones lineales. Los métodos algebraicos simplifican este proceso de búsqueda.',
                'bibliografia' => [
                    'Baldor, A. (2007). Álgebra. Grupo Editorial Patria.',
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.'
                ]
            ],

            // SEMESTRE 2: Matemáticas II
            'matematicas_2_u1' => [
                'introduccion' => 'Las ecuaciones cuadráticas son de segundo grado y tienen la forma general ax^2 + bx + c = 0. En esta unidad exploraremos cómo resolverlas utilizando técnicas de factorización y la fórmula general.',
                'objetivos' => [
                    'Resolver ecuaciones de segundo grado por factorización.',
                    'Utilizar la fórmula general para calcular raíces.',
                    'Interpretar el valor del discriminante.'
                ],
                'conocimientos_previos' => [
                    'Productos notables, leyes de exponentes y radicales.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Métodos de factorización',
                        'texto' => 'Para resolver por factorización buscamos reescribir \\(x^2 + bx + c = 0\\) de la forma \\((x + p)(x + q) = 0\\).'
                    ],
                    [
                        'titulo' => '2. Fórmula General',
                        'texto' => 'Las soluciones para cualquier ecuación cuadrática se pueden calcular con: \\(x = \\frac{-b \\pm \\sqrt{b^2 - 4ac}}{2a}\\).'
                    ],
                    [
                        'titulo' => '3. El Discriminante',
                        'texto' => 'El discriminante \\(D = b^2 - 4ac\\) nos indica el número y tipo de soluciones que posee la ecuación.'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Discriminante',
                        'definicion' => 'Expresión debajo de la raíz que determina el tipo de soluciones.'
                    ],
                    [
                        'concepto' => 'Raíz',
                        'definicion' => 'Valor que satisface la ecuación cuadrática.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Trinomio de la forma x^2 + bx + c',
                        'explicacion' => 'Resuelve por factorización: \\(x^2 - 5x + 6 = 0\\).',
                        'solucion' => '1. Buscamos dos números que multiplicados den 6 y sumados den -5. Estos son -2 y -3.\n2. Escribimos la ecuación como: \\((x - 2)(x - 3) = 0\\).\n3. Obtenemos las soluciones: \\(x_1 = 2\\) y \\(x_2 = 3\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Diferencia de Cuadrados',
                        'explicacion' => 'Resuelve por factorización: \\(x^2 - 9 = 0\\).',
                        'solucion' => '1. Factorizamos la diferencia de cuadrados: \\((x - 3)(x + 3) = 0\\).\n2. Las raíces son \\(x_1 = 3\\) y \\(x_2 = -3\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Fórmula general con dos soluciones reales',
                        'explicacion' => 'Resuelve: \\(x^2 - 3x + 2 = 0\\).',
                        'solucion' => '1. Identificamos: \\(a=1, b=-3, c=2\\).\n2. Aplicamos la fórmula: \\(x = \\frac{-(-3) \\pm \\sqrt{(-3)^2 - 4(1)(2)}}{2(1)} = \\frac{3 \\pm \\sqrt{9 - 8}}{2} = \\frac{3 \\pm 1}{2}\\).\n3. Soluciones: \\(x_1 = 2\\), \\(x_2 = 1\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Raíz doble',
                        'explicacion' => 'Resuelve: \\(x^2 + 4x + 4 = 0\\).',
                        'solucion' => '1. Identificamos: \\(a=1, b=4, c=4\\).\n2. Aplicamos la fórmula: \\(x = \\frac{-4 \\pm \\sqrt{16 - 16}}{2} = \\frac{-4}{2} = -2\\). La única solución es \\(x = -2\\).'
                    ],
                    // Punto 3
                    [
                        'titulo' => 'Ejemplo 3.1: Discriminante Positivo',
                        'explicacion' => 'Determina el tipo de soluciones para \\(x^2 - 4x + 3 = 0\\) usando el discriminante.',
                        'solucion' => '1. Calculamos \\(D = (-4)^2 - 4(1)(3) = 16 - 12 = 4\\).\n2. Al ser \\(D > 0\\), posee dos soluciones reales y distintas.'
                    ],
                    [
                        'titulo' => 'Ejemplo 3.2: Discriminante Negativo',
                        'explicacion' => 'Determina el tipo de soluciones para \\(x^2 + 2x + 5 = 0\\).',
                        'solucion' => '1. Calculamos \\(D = (2)^2 - 4(1)(5) = 4 - 20 = -16\\).\n2. Al ser \\(D < 0\\), no posee soluciones reales.'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Signo incorrecto al aplicar -b en la fórmula general',
                        'ejemplo_incorrecto' => 'Para \\(x^2 - 5x + 6 = 0\\), escribir \\(x = \\frac{-5 \\pm \\sqrt{25 - 24}}{2}\\).',
                        'correccion' => 'Si \\(b = -5\\), entonces \\(-b = -(-5) = 5\\). El valor correcto es positivo al inicio de la fórmula.'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Resuelve por factorización: \\(x^2 - 6x + 8 = 0\\).', 'guia' => 'Pista: Busca dos números que multiplicados den 8 y sumados den -4 y -2.'],
                    ['instruccion' => 'Resuelve por factorización: \\(x^2 + 5x + 6 = 0\\).', 'guia' => 'Pista: Los números multiplicados deben dar +6 y sumados +5.'],
                    ['instruccion' => 'Resuelve: \\(x^2 - 4 = 0\\) mediante factorización.', 'guia' => 'Pista: Es una diferencia de cuadrados perfectos: \\((x - 2)(x + 2) = 0\\).'],
                    ['instruccion' => 'Factoriza y resuelve: \\(x^2 - 2x - 3 = 0\\).', 'guia' => 'Pista: Busca números que multiplicados den -3 y cuya resta o suma sea -2.'],
                    ['instruccion' => 'Resuelve por factor común: \\(x^2 + 4x = 0\\).', 'guia' => 'Pista: Extrae la \\(x\\) como factor común: \\(x(x + 4) = 0\\).'],
                    // Punto 2
                    ['instruccion' => 'Resuelve con la fórmula general: \\(x^2 - 5x + 4 = 0\\).', 'guia' => 'Pista: Identifica \\(a=1, b=-5, c=4\\) y sustituye en la fórmula.'],
                    ['instruccion' => 'Resuelve con la fórmula general: \\(x^2 + 2x - 8 = 0\\).', 'guia' => 'Pista: Ten cuidado con el término \\(-4ac\\) ya que \\(c\\) es negativo (-8).'],
                    ['instruccion' => 'Resuelve con la fórmula general: \\(x^2 - 6x + 9 = 0\\).', 'guia' => 'Pista: El radicando será cero, lo que indica que posee una solución única.'],
                    ['instruccion' => 'Resuelve con la fórmula general: \\(2x^2 - 5x + 3 = 0\\).', 'guia' => 'Pista: Sustituye utilizando \\(a=2\\) en el denominador y coeficiente.'],
                    ['instruccion' => 'Resuelve con la fórmula general: \\(x^2 - x - 2 = 0\\).', 'guia' => 'Pista: Utiliza los valores \\(a=1, b=-1, c=-2\\).'],
                    // Punto 3
                    ['instruccion' => 'Calcula el discriminante de \\(x^2 - 5x + 6 = 0\\).', 'guia' => 'Pista: Aplica la fórmula \\(D = b^2 - 4ac\\).'],
                    ['instruccion' => 'Calcula el discriminante de \\(x^2 + 2x + 5 = 0\\).', 'guia' => 'Pista: Determina si el resultado es mayor, menor o igual a cero.'],
                    ['instruccion' => 'Determina la naturaleza de las raíces para \\(x^2 - 6x + 9 = 0\\) usando el discriminante.', 'guia' => 'Pista: Si \\(D = 0\\), las raíces son reales e iguales.'],
                    ['instruccion' => 'Calcula el discriminante de \\(2x^2 + x - 1 = 0\\).', 'guia' => 'Pista: Identifica bien los valores: \\(a=2, b=1, c=-1\\).'],
                    ['instruccion' => 'Determina cuántas soluciones reales tiene la ecuación \\(3x^2 + 2x + 1 = 0\\).', 'guia' => 'Pista: Si el discriminante es negativo, no tiene soluciones reales.']
                ],
                'resumen' => 'Las ecuaciones de segundo grado tienen dos soluciones posibles que se pueden obtener mediante factorización o usando la fórmula general. El discriminante define el tipo de raíces.',
                'bibliografia' => [
                    'Baldor, A. (2007). Álgebra. Grupo Editorial Patria.',
                    'Sullivan, M. (2006). Álgebra y Trigonometría. Pearson Educación.'
                ]
            ],
            'matematicas_2_u2' => [
                'introduccion' => 'Una función cuadrática modela curvas llamadas parábolas. Veremos cómo analizar su comportamiento, identificar sus puntos de retorno (vértices) y resolver problemas prácticos.',
                'objetivos' => [
                    'Graficar funciones cuadráticas f(x) = ax^2 + bx + c.',
                    'Calcular el vértice y el eje de simetría.',
                    'Resolver problemas de optimización básica.'
                ],
                'conocimientos_previos' => [
                    'Ubicación de puntos en el plano cartesiano y operaciones algebraicas básicas.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. La gráfica de la parábola',
                        'texto' => 'La función cuadrática \\(f(x) = ax^2 + bx + c\\) tiene como gráfica una parábola. Si \\(a > 0\\) abre hacia arriba; si \\(a < 0\\) abre hacia abajo.'
                    ],
                    [
                        'titulo' => '2. Coordenadas del Vértice',
                        'texto' => 'El vértice \\(V(h, k)\\) es el punto extremo. La coordenada horizontal es \\(h = -\\frac{b}{2a}\\) y la vertical es \\(k = f(h)\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Vértice',
                        'definicion' => 'Punto donde la parábola cambia de dirección.'
                    ],
                    [
                        'concepto' => 'Concavidad',
                        'definicion' => 'Dirección de apertura de las ramas de la parábola (hacia arriba o abajo).'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Concavidad Positiva',
                        'explicacion' => 'Determina la concavidad de la parábola \\(f(x) = 3x^2 - x\\).',
                        'solucion' => '1. Identificamos \\(a = 3\\).\n2. Dado que \\(a > 0\\), la parábola abre hacia arriba (cóncava hacia arriba).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Concavidad Negativa',
                        'explicacion' => 'Determina la concavidad de \\(f(x) = -2x^2 + 5\\).',
                        'solucion' => '1. Identificamos \\(a = -2\\).\n2. Dado que \\(a < 0\\), la parábola abre hacia abajo (cóncava hacia abajo).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Vértice de x^2 - 4x + 5',
                        'explicacion' => 'Encuentra el vértice de la función \\(f(x) = x^2 - 4x + 5\\).',
                        'solucion' => '1. Calculamos \\(h = -\\frac{-4}{2(1)} = 2\\).\n2. Evaluamos \\(k = f(2) = (2)^2 - 4(2) + 5 = 4 - 8 + 5 = 1\\).\n3. El vértice es \\(V(2, 1)\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Vértice con a negativa',
                        'explicacion' => 'Encuentra el vértice de \\(f(x) = -x^2 + 6x\\).',
                        'solucion' => '1. Calculamos \\(h = -\\frac{6}{2(-1)} = 3\\).\n2. Evaluamos \\(k = f(3) = -(3)^2 + 6(3) = -9 + 18 = 9\\).\n3. El vértice es \\(V(3, 9)\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Olvidar evaluar h en la función para hallar k',
                        'ejemplo_incorrecto' => 'Decir que el vértice de \\(x^2 - 4x + 5\\) es simplemente el punto \\(x = 2\\).',
                        'correccion' => 'El vértice es un punto ordenado en el plano \\(V(h, k)\\), por lo que debemos calcular la coordenada \\(y\\) evaluando: \\(k = f(h)\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Determina hacia dónde abre la parábola \\(f(x) = -5x^2 + 2x + 1\\).', 'guia' => 'Pista: Observa el signo del coeficiente del término \\(x^2\\).'],
                    ['instruccion' => 'Indica si la parábola \\(f(x) = x^2 - 4\\) tiene un punto mínimo o máximo.', 'guia' => 'Pista: Si abre hacia arriba (a > 0), el vértice es un punto mínimo.'],
                    ['instruccion' => 'Halla la concavidad de la parábola \\(f(x) = \\frac{1}{2}x^2\\).', 'guia' => 'Pista: El coeficiente es positivo (\\(1/2 > 0\\)).'],
                    ['instruccion' => '¿Cuál es el valor máximo o mínimo de la función \\(f(x) = (x-1)^2\\)?', 'guia' => 'Pista: Al expandirse, \\(a=1 > 0\\), por lo que tiene un mínimo.'],
                    ['instruccion' => 'Determina el eje de simetría de \\(f(x) = x^2\\).', 'guia' => 'Pista: El eje de simetría es la recta vertical \\(x = h\\). Aquí \\(h = 0\\).'],
                    // Punto 2
                    ['instruccion' => 'Calcula el vértice de \\(f(x) = x^2 - 2x + 3\\).', 'guia' => 'Pista: Usa la fórmula \\(h = -\\frac{b}{2a}\\) y luego evalúa la función.'],
                    ['instruccion' => 'Calcula el vértice de \\(f(x) = -x^2 + 6x\\).', 'guia' => 'Pista: Identifica \\(a=-1\\) y \\(b=6\\).'],
                    ['instruccion' => 'Encuentra las coordenadas del vértice de \\(f(x) = 2x^2 - 8x + 5\\).', 'guia' => 'Pista: Sustituye \\(a=2\\) y \\(b=-8\\) en la fórmula de \\(h\\).'],
                    ['instruccion' => 'Calcula el vértice de \\(f(x) = x^2 + 4x - 1\\).', 'guia' => 'Pista: La coordenada horizontal es \\(h = -2\\). Evalúa \\(f(-2)\\).'],
                    ['instruccion' => 'Encuentra las coordenadas del vértice de \\(f(x) = -3x^2 + 12x - 10\\).', 'guia' => 'Pista: Aplica las fórmulas usuales con cuidado de los signos de los coeficientes.']
                ],
                'resumen' => 'Las funciones cuadráticas representan curvas simétricas con un punto máximo o mínimo en su vértice.',
                'bibliografia' => [
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.',
                    'Sullivan, M. (2006). Álgebra y Trigonometría. Pearson Educación.'
                ]
            ],
            'matematicas_2_u3' => [
                'introduccion' => 'La geometría plana estudia las figuras en superficies bidimensionales. Repasaremos ángulos, triángulos y fórmulas para calcular perímetros y áreas.',
                'objetivos' => [
                    'Clasificar ángulos e identificar relaciones.',
                    'Comprender la suma de ángulos internos en triángulos.',
                    'Calcular perímetros y áreas de polígonos.'
                ],
                'conocimientos_previos' => [
                    'Operaciones aritméticas y álgebra básica.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Clasificación de ángulos',
                        'texto' => 'Los ángulos se clasifican por su medida en: agudo (<90°), recto (90°), obtuso (>90° y <180°) y llano (180°).'
                    ],
                    [
                        'titulo' => '2. Propiedades de los triángulos',
                        'texto' => 'La suma de los ángulos internos de cualquier triángulo siempre es igual a 180° (\\(\\angle A + \\angle B + \\angle C = 180^\\circ\\)).'
                    ],
                    [
                        'titulo' => '3. Perímetro y Área',
                        'texto' => 'El perímetro es la longitud del contorno y el área es la medida de la superficie de una figura.'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Complementarios',
                        'definicion' => 'Ángulos cuya suma es 90°.'
                    ],
                    [
                        'concepto' => 'Suplementarios',
                        'definicion' => 'Ángulos cuya suma es 180°.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Ángulo Complementario',
                        'explicacion' => 'Halla el complemento de un ángulo de \\(40^\\circ\\).',
                        'solucion' => '1. El complemento es \\(90^\\circ - 40^\\circ = 50^\\circ\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Ángulo Suplementario',
                        'explicacion' => 'Halla el suplemento de un ángulo de \\(110^\\circ\\).',
                        'solucion' => '1. El suplemento es \\(180^\\circ - 110^\\circ = 70^\\circ\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Ángulo Desconocido',
                        'explicacion' => 'Si dos ángulos de un triángulo miden \\(50^\\circ\\) y \\(70^\\circ\\), ¿cuánto mide el tercero?',
                        'solucion' => '1. Sumamos los conocidos: \\(50^\\circ + 70^\\circ = 120^\\circ\\).\n2. Restamos de 180°: \\(180^\\circ - 120^\\circ = 60^\\circ\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Ángulo en Triángulo Rectángulo',
                        'explicacion' => 'En un triángulo rectángulo, uno de los ángulos agudos mide \\(35^\\circ\\). Halla el otro.',
                        'solucion' => '1. Sabemos que el ángulo recto mide 90°.\n2. La suma de los dos agudos es 90°: \\(90^\\circ - 35^\\circ = 55^\\circ\\).'
                    ],
                    // Punto 3
                    [
                        'titulo' => 'Ejemplo 3.1: Área del Triángulo',
                        'explicacion' => 'Calcula el área de un triángulo de base 6 cm y altura 4 cm.',
                        'solucion' => '1. Aplicamos la fórmula: \\(A = \\frac{b \\times h}{2} = \\frac{6 \\times 4}{2} = 12\\text{ cm}^2\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 3.2: Área del Rectángulo',
                        'explicacion' => 'Calcula el área de un rectángulo con dimensiones 5 cm por 10 cm.',
                        'solucion' => '1. Aplicamos la fórmula: \\(A = b \\times h = 5 \\times 10 = 50\\text{ cm}^2\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Confundir complementarios con suplementarios',
                        'ejemplo_incorrecto' => 'Buscar el complemento de \\(50^\\circ\\) calculando \\(180^\\circ - 50^\\circ = 130^\\circ\\).',
                        'correccion' => 'El complemento se resta de 90° (da 40°). El suplemento se resta de 180°.'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Halla el complemento de \\(35^\\circ\\).', 'guia' => 'Pista: Resta \\(35^\\circ\\) de \\(90^\\circ\\).'],
                    ['instruccion' => 'Halla el suplemento de \\(80^\\circ\\).', 'guia' => 'Pista: Resta \\(80^\\circ\\) de \\(180^\\circ\\).'],
                    ['instruccion' => 'Clasifica un ángulo que mide \\(95^\\circ\\).', 'guia' => 'Pista: Al ser mayor a 90° y menor a 180°, se denomina obtuso.'],
                    ['instruccion' => 'Clasifica un ángulo de \\(270^\\circ\\).', 'guia' => 'Pista: Al ser mayor a 180° pero menor a 360° es un ángulo cóncavo.'],
                    ['instruccion' => 'Halla el complemento de un ángulo de \\(45^\\circ\\).', 'guia' => 'Pista: Resta \\(45^\\circ\\) de \\(90^\\circ\\).'],
                    // Punto 2
                    ['instruccion' => 'Halla el ángulo faltante si dos de ellos miden \\(30^\\circ\\) y \\(90^\\circ\\).', 'guia' => 'Pista: Suma los dos ángulos dados y resta el resultado de \\(180^\\circ\\).'],
                    ['instruccion' => 'Determina si existe un triángulo cuyos lados sean \\(5\\), \\(5\\) y \\(12\\).', 'guia' => 'Pista: Por desigualdad triangular, la suma de los dos lados menores debe ser mayor que el mayor.'],
                    ['instruccion' => '¿Cuál es la suma de los ángulos agudos de un triángulo rectángulo?', 'guia' => 'Pista: Es siempre \\(90^\\circ\\), pues el ángulo recto ya ocupa \\(90^\\circ\\) del total.'],
                    ['instruccion' => 'Calcula los ángulos internos de un triángulo equilátero.', 'guia' => 'Pista: Un triángulo equilátero tiene sus tres ángulos iguales. Divide 180 entre 3.'],
                    ['instruccion' => 'Verifica si se puede construir un triángulo con lados \\(6\\text{ cm}\\), \\(8\\text{ cm}\\) y \\(10\\text{ cm}\\).', 'guia' => 'Pista: Comprueba si \\(6 + 8 > 10\\).'],
                    // Punto 3
                    ['instruccion' => 'Calcula el área de un rectángulo de base 8 cm y altura 5 cm.', 'guia' => 'Pista: Aplica la fórmula del área \\(A = b \\times h\\).'],
                    ['instruccion' => 'Halla el perímetro de un triángulo equilátero cuyo lado mide 7 cm.', 'guia' => 'Pista: Multiplica la longitud del lado por 3.'],
                    ['instruccion' => 'Calcula el área de un triángulo con base 10 cm y altura 5 cm.', 'guia' => 'Pista: Aplica la fórmula del área del triángulo.'],
                    ['instruccion' => 'Halla el perímetro de un círculo con radio de 2 cm (usa \\(\\pi \\approx 3.14\\)).', 'guia' => 'Pista: Aplica la fórmula de la circunferencia \\(P = 2\\pi r\\).'],
                    ['instruccion' => 'Calcula el perímetro de un cuadrado de lado 4 cm.', 'guia' => 'Pista: Suma la longitud de sus cuatro lados.' ]
                ],
                'resumen' => 'La geometría plana estudia figuras en dos dimensiones, utilizando teoremas de ángulos y triángulos para resolver problemas de perímetros y áreas.',
                'bibliografia' => [
                    'Baldor, A. (2004). Geometría y Trigonometría. Grupo Editorial Patria.'
                ]
            ],
            'matematicas_2_u4' => [
                'introduccion' => 'La congruencia y la semejanza estudian la igualdad y la proporción de formas en geometría. Además, estudiaremos el Teorema de Tales y el Teorema de Pitágoras.',
                'objetivos' => [
                    'Distinguir entre figuras congruentes y semejantes.',
                    'Aplicar criterios de semejanza de triángulos.',
                    'Utilizar los teoremas de Tales y Pitágoras.'
                ],
                'conocimientos_previos' => [
                    'Propiedades de triángulos y despejes básicos.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Congruencia y Semejanza',
                        'texto' => 'Figuras congruentes son idénticas en tamaño y forma. Figuras semejantes tienen la misma forma pero sus tamaños son proporcionales.'
                    ],
                    [
                        'titulo' => '2. Teorema de Tales',
                        'texto' => 'Si se traza una línea paralela a un lado de un triángulo, se forma otro triángulo semejante al original.'
                    ],
                    [
                        'titulo' => '3. Teorema de Pitágoras',
                        'texto' => 'En todo triángulo rectángulo, el cuadrado de la hipotenusa es igual a la suma de los cuadrados de los catetos: \\(c^2 = a^2 + b^2\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Hipotenusa',
                        'definicion' => 'El lado de mayor longitud, opuesto al ángulo de 90°.'
                    ],
                    [
                        'concepto' => 'Semejanza',
                        'definicion' => 'Proporcionalidad entre los lados homólogos de dos figuras con ángulos iguales.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Identificar Semejanza',
                        'explicacion' => 'Un triángulo tiene lados 3, 4, 5 y otro tiene lados 6, 8, 10. ¿Son semejantes?',
                        'solucion' => '1. Comparamos las razones de los lados correspondientes: \\(\\frac{6}{3} = 2\\), \\(\\frac{8}{4} = 2\\), \\(\\frac{10}{5} = 2\\).\n2. Al ser las razones iguales (razón de semejanza = 2), los triángulos son semejantes.'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Triángulos Congruentes',
                        'explicacion' => 'Dos triángulos tienen los tres lados iguales a 5 cm, 6 cm y 7 cm. ¿Son congruentes?',
                        'solucion' => '1. Por el criterio Lado-Lado-Lado (LLL), al tener sus tres lados correspondientes iguales, son congruentes.'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Aplicar Teorema de Tales',
                        'explicacion' => 'Dado un triángulo con un segmento paralelo que divide los lados en segmentos \\(x\\) y 3 en un lado, y 4 y 2 en el otro. Halla \\(x\\).',
                        'solucion' => '1. Planteamos la proporción de Tales: \\(\\frac{x}{3} = \\frac{4}{2}\\).\n2. Despejamos \\(x\\): \\(x = 3 \\times 2 = 6\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Altura de un objeto',
                        'explicacion' => 'Un poste proyecta una sombra de 6 m. A la misma hora, un bastón de 1 m proyecta una sombra de 2 m. Halla la altura del poste.',
                        'solucion' => '1. Por Tales, formamos la proporción: \\(\\frac{H}{1} = \\frac{6}{2}\\).\n2. Resolvemos: \\(H = 3\\text{ m}\\).'
                    ],
                    // Punto 3
                    [
                        'titulo' => 'Ejemplo 3.1: Hallar Hipotenusa',
                        'explicacion' => 'Calcula la hipotenusa de un triángulo rectángulo cuyos catetos miden 3 cm y 4 cm.',
                        'solucion' => '1. Aplicamos Pitágoras: \\(c^2 = 3^2 + 4^2 = 9 + 16 = 25\\).\n2. Sacamos raíz cuadrada: \\(c = \\sqrt{25} = 5\\text{ cm}\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 3.2: Hallar un Cateto',
                        'explicacion' => 'Encuentra el cateto faltante si la hipotenusa mide 13 cm y el otro cateto mide 5 cm.',
                        'solucion' => '1. Despejamos el cateto: \\(b^2 = c^2 - a^2 = 13^2 - 5^2 = 169 - 25 = 144\\).\n2. Sacamos raíz cuadrada: \\(b = \\sqrt{144} = 12\\text{ cm}\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Usar el teorema de Pitágoras en triángulos oblicuángulos',
                        'ejemplo_incorrecto' => 'Aplicar \\(c^2 = a^2 + b^2\\) en un triángulo con ángulos de \\(60^\\circ\\).',
                        'correccion' => 'El Teorema de Pitágoras es de aplicación exclusiva para triángulos rectángulos (con un ángulo de 90°).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Explica el criterio Lado-Lado-Lado (LLL) de congruencia.', 'guia' => 'Pista: Establece que si los tres lados homólogos de dos triángulos miden lo mismo, los triángulos son idénticos.'],
                    ['instruccion' => 'Si la razón de semejanza entre dos triángulos es 3, y el lado menor del primero mide 4 cm, ¿cuánto mide en el segundo?', 'guia' => 'Pista: Multiplica la longitud del lado menor por la razón de semejanza (3).'],
                    ['instruccion' => 'Identifica si todos los triángulos equiláteros son semejantes.', 'guia' => 'Pista: Sí, pues todos sus ángulos internos miden siempre 60° (criterio AA).'],
                    ['instruccion' => 'Si dos triángulos tienen la misma forma pero sus perímetros son diferentes, ¿son semejantes o congruentes?', 'guia' => 'Pista: Al cambiar la escala de tamaño pero mantener la forma, son semejantes.'],
                    ['instruccion' => 'Escribe las condiciones para que dos polígonos sean congruentes.', 'guia' => 'Pista: Deben tener los mismos ángulos y la misma medida de lados correspondientes.'],
                    // Punto 2
                    ['instruccion' => 'Resuelve la proporción: \\(\\frac{x}{3} = \\frac{8}{4}\\).', 'guia' => 'Pista: Multiplica cruzado y despeja la variable \\(x\\).'],
                    ['instruccion' => 'Un árbol proyecta una sombra de 12 m. Una persona de 1.5 m de altura proyecta una sombra de 3 m. Halla la altura del árbol.', 'guia' => 'Pista: Plantea la relación \\(\\frac{\\text{Altura}}{1.5} = \\frac{12}{3}\\).'],
                    ['instruccion' => 'En un triángulo ABC, se traza una paralela a BC que corta los otros lados en D y E. Si AD=4, DB=2, AE=6, halla EC.', 'guia' => 'Pista: Plantea la proporción \\(\\frac{4}{2} = \\frac{6}{\\text{EC}}\\).'],
                    ['instruccion' => 'Si dos segmentos paralelos cortan dos rectas secantes y las dividen en proporciones, ¿qué teorema aplicamos?', 'guia' => 'Pista: Es el Teorema de Tales sobre proporcionalidad de segmentos.'],
                    ['instruccion' => 'Determina la escala de un mapa si un segmento de 5 cm representa 15 km en la realidad.', 'guia' => 'Pista: Establece la proporción de semejanza dividiendo 15 km entre 5 cm.'],
                    // Punto 3
                    ['instruccion' => 'Calcula la hipotenusa si los catetos miden 6 cm y 8 cm.', 'guia' => 'Pista: Aplica \\(c = \\sqrt{6^2 + 8^2}\\).'],
                    ['instruccion' => 'Halla un cateto si la hipotenusa es 10 cm y el otro cateto es 6 cm.', 'guia' => 'Pista: Despeja el cateto mediante \\(a = \\sqrt{c^2 - b^2}\\).'],
                    ['instruccion' => 'Halla la diagonal de un rectángulo de lados 5 cm y 12 cm.', 'guia' => 'Pista: La diagonal es la hipotenusa de los triángulos rectángulos formados por los lados del rectángulo.'],
                    ['instruccion' => 'Calcula la hipotenusa si los catetos son 5 cm y 12 cm.', 'guia' => 'Pista: Eleva al cuadrado ambos catetos, súmalos y extrae la raíz.'],
                    ['instruccion' => 'Halla el cateto de un triángulo rectángulo si la hipotenusa es 5 y el otro cateto es 3.', 'guia' => 'Pista: Aplica la fórmula despejada de Pitágoras.' ]
                ],
                'resumen' => 'Los criterios de congruencia y semejanza permiten relacionar las medidas de figuras geométricas. El Teorema de Tales y de Pitágoras son aplicaciones clave de estas relaciones.',
                'bibliografia' => [
                    'Baldor, A. (2004). Geometría y Trigonometría. Grupo Editorial Patria.'
                ]
            ],

            // SEMESTRE 3: Matemáticas III
            'matematicas_3_u1' => [
                'introduccion' => 'La trigonometría estudia las relaciones entre los lados y los ángulos de los triángulos. Analizaremos razones trigonométricas en triángulos rectángulos y las leyes de Senos y Cosenos.',
                'objetivos' => [
                    'Definir razones trigonométricas básicas.',
                    'Utilizar la Ley de Senos en triángulos oblicuángulos.',
                    'Utilizar la Ley de Cosenos para resolver triángulos.'
                ],
                'conocimientos_previos' => [
                    'Teorema de Pitágoras y suma de ángulos internos.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Razones trigonométricas',
                        'texto' => 'En un triángulo rectángulo: \\(\\text{sen}(\\theta) = \\frac{\\text{CO}}{\\text{H}}\\), \\(\\cos(\\theta) = \\frac{\\text{CA}}{\\text{H}}\\), \\(\\tan(\\theta) = \\frac{\\text{CO}}{\\text{CA}}\\).'
                    ],
                    [
                        'titulo' => '2. Ley de Senos',
                        'texto' => 'Para cualquier triángulo: \\(\\frac{a}{\\text{sen}(A)} = \\frac{b}{\\text{sen}(B)} = \\frac{c}{\\text{sen}(C)}\\).'
                    ],
                    [
                        'titulo' => '3. Ley de Cosenos',
                        'texto' => 'Para cualquier triángulo: \\(a^2 = b^2 + c^2 - 2bc \\cos(A)\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Razones',
                        'definicion' => 'Relación entre las medidas de los lados de un triángulo rectángulo respecto a sus ángulos.'
                    ],
                    [
                        'concepto' => 'Oblicuángulo',
                        'definicion' => 'Triángulo que no posee ningún ángulo de 90°.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Calcular Seno',
                        'explicacion' => 'En un triángulo rectángulo, el cateto opuesto al ángulo \\(\\theta\\) mide 3 y la hipotenusa mide 5. Calcula \\(\\text{sen}(\\theta)\\).',
                        'solucion' => '1. Aplicamos la fórmula: \\(\\text{sen}(\\theta) = \\frac{\\text{CO}}{\\text{H}}\\).\n2. Sustituimos valores: \\(\\text{sen}(\\theta) = \\frac{3}{5} = 0.6\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Calcular Tangente',
                        'explicacion' => 'Halla la tangente del ángulo si el cateto opuesto es 4 y el cateto adyacente es 3.',
                        'solucion' => '1. Aplicamos la fórmula: \\(\\tan(\\theta) = \\frac{\\text{CO}}{\\text{CA}}\\).\n2. Sustituimos: \\(\\tan(\\theta) = \\frac{4}{3} \\approx 1.33\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Ley de Senos para hallar un lado',
                        'explicacion' => 'En un triángulo, el ángulo \\(A = 30^\\circ\\), el ángulo \\(B = 45^\\circ\\) y el lado \\(a = 10\\text{ cm}\\). Halla el lado \\(b\\) (usa \\(\\text{sen}(30^\\circ) = 0.5\\), \\(\\text{sen}(45^\\circ) \\approx 0.707\\)).',
                        'solucion' => '1. Planteamos la proporción: \\(\\frac{10}{\\text{sen}(30^\\circ)} = \\frac{b}{\\text{sen}(45^\\circ)}\\).\n2. Despejamos \\(b\\): \\(b = \\frac{10 \\times 0.707}{0.5} = 14.14\\text{ cm}\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Ley de Senos para comprobar ángulos',
                        'explicacion' => 'Si \\(\\frac{a}{\\text{sen}(A)} = 20\\), y el lado \\(b = 10\\), halla \\(\\text{sen}(B)\\).',
                        'solucion' => '1. Por la ley de senos: \\(\\frac{b}{\\text{sen}(B)} = 20 \\implies \\text{sen}(B) = \\frac{b}{20}\\).\n2. Sustituimos \\(b=10\\): \\(\\text{sen}(B) = \\frac{10}{20} = 0.5\\).'
                    ],
                    // Punto 3
                    [
                        'titulo' => 'Ejemplo 3.1: Ley de Cosenos lado faltante',
                        'explicacion' => 'Halla el lado \\(a\\) si \\(b=8\\), \\(c=10\\) y \\(A=60^\\circ\\) (con \\(\\cos(60^\\circ)=0.5\\)).',
                        'solucion' => '1. Aplicamos la fórmula: \\(a^2 = 8^2 + 10^2 - 2(8)(10)(0.5)\\).\n2. Evaluamos: \\(a^2 = 64 + 100 - 80 = 84 \\implies a = \\sqrt{84} \\approx 9.17\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 3.2: Ley de Cosenos ángulo',
                        'explicacion' => 'Determina el valor de \\(\\cos(A)\\) si \\(a=7\\), \\(b=5\\), \\(c=6\\).',
                        'solucion' => '1. Despejamos la fórmula: \\(\\cos(A) = \\frac{b^2 + c^2 - a^2}{2bc}\\).\n2. Sustituimos: \\(\\cos(A) = \\frac{5^2 + 6^2 - 7^2}{2(5)(6)} = \\frac{25 + 36 - 49}{60} = \\frac{12}{60} = 0.2\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Usar razones trigonométricas directas en triángulos no rectángulos',
                        'ejemplo_incorrecto' => 'Calcular \\(\\text{sen}(\\theta) = \\frac{\\text{CO}}{\\text{H}}\\) en un triángulo oblicuángulo.',
                        'correccion' => 'Las razones trigonométricas directas solo aplican en triángulos rectángulos. En oblicuángulos se debe usar Ley de Senos o Cosenos.'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Si \\(\\cos(\\theta) = \\frac{4}{5}\\), calcula \\(\\text{sen}(\\theta)\\).', 'guia' => 'Pista: Usa la identidad trigonométrica fundamental \\(\\text{sen}^2(\\theta) + \\cos^2(\\theta) = 1\\).'],
                    ['instruccion' => 'Halla \\(\\tan(45^\\circ)\\) si el triángulo rectángulo tiene catetos de igual medida.', 'guia' => 'Pista: Si los catetos son iguales, el cociente \\(\\frac{\\text{CO}}{\\text{CA}}\\) es igual a 1.'],
                    ['instruccion' => 'En un triángulo rectángulo la hipotenusa mide 10 y el ángulo es 30°. Halla el cateto opuesto.', 'guia' => 'Pista: Despeja de \\(\\text{sen}(30^\\circ) = \\frac{\\text{CO}}{10}\\) sabiendo que \\(\\text{sen}(30^\\circ)=0.5\\).'],
                    ['instruccion' => 'Calcula la secante si \\(\\cos(\\theta) = 0.5\\).', 'guia' => 'Pista: La secante es la recíproca del coseno: \\(\\sec(\\theta) = \\frac{1}{\\cos(\\theta)}\\).'],
                    ['instruccion' => 'Encuentra la cosecante de un ángulo cuyo seno mide \\(\\frac{3}{5}\\).', 'guia' => 'Pista: La cosecante es el recíproco de la función seno.'],
                    // Punto 2
                    ['instruccion' => 'Aplica la ley de senos para hallar el ángulo B si \\(a=5\\), \\(b=10\\) y \\(\\text{sen}(A)=0.2\\).', 'guia' => 'Pista: Usa la relación \\(\\frac{a}{\\text{sen}(A)} = \\frac{b}{\\text{sen}(B)}\\).'],
                    ['instruccion' => 'En un triángulo, \\(A=45^\\circ\\) y \\(B=60^\\circ\\). Si el lado opuesto a A mide 8 cm, halla el lado opuesto a B.', 'guia' => 'Pista: Aplica la Ley de Senos con los valores dados.'],
                    ['instruccion' => 'Determina si podemos usar la ley de senos si conocemos dos lados y el ángulo comprendido.', 'guia' => 'Pista: No, en ese caso (LAL) se debe iniciar con la Ley de Cosenos.'],
                    ['instruccion' => 'Halla la proporción \\(\\frac{a}{b}\\) en un triángulo si \\(\\angle A=30^\\circ\\) y \\(\\angle B=90^\\circ\\).', 'guia' => 'Pista: Sustituye en la Ley de Senos y calcula \\(\\frac{\\text{sen}(30^\\circ)}{\\text{sen}(90^\\circ)}\\).'],
                    ['instruccion' => 'Resuelve el lado opuesto a un ángulo de 45° si el opuesto a un ángulo de 30° mide 6 cm.', 'guia' => 'Pista: Aplica la relación de la Ley de Senos.'],
                    // Punto 3
                    ['instruccion' => 'Halla el lado \\(a\\) si \\(b=3\\), \\(c=4\\) y \\(\\cos(A)=0\\).', 'guia' => 'Pista: Sustituye en la ley de cosenos. El término \\(-2bc\\cos(A)\\) se anula.'],
                    ['instruccion' => 'Calcula \\(a^2\\) si \\(b=5\\), \\(c=5\\) y el ángulo A mide 120° (con \\(\\cos(120^\\circ) = -0.5\\)).', 'guia' => 'Pista: Ten cuidado con el doble signo negativo: \\(-2(5)(5)(-0.5) = +25\\).'],
                    ['instruccion' => 'Si los tres lados de un triángulo miden 4, 5 y 6, ¿qué ley usas para hallar los ángulos?', 'guia' => 'Pista: Para el caso LLL, se aplica directamente la Ley de Cosenos.'],
                    ['instruccion' => 'Escribe la fórmula de la Ley de Cosenos para despejar el término \\(b^2\\).', 'guia' => 'Pista: La fórmula es \\(b^2 = a^2 + c^2 - 2ac\\cos(B)\\).'],
                    ['instruccion' => 'Halla el lado \\(c\\) si \\(a=8\\), \\(b=6\\) y \\(C=90^\\circ\\).', 'guia' => 'Pista: Como \\(\\cos(90^\\circ)=0\\), la Ley de Cosenos se reduce al Teorema de Pitágoras.']
                ],
                'resumen' => 'La trigonometría relaciona lados y ángulos. Empleamos razones trigonométricas en triángulos rectángulos y las leyes de Senos y Cosenos en triángulos oblicuángulos.',
                'bibliografia' => [
                    'Baldor, A. (2004). Geometría y Trigonometría. Grupo Editorial Patria.',
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.'
                ]
            ],
            'matematicas_3_u2' => [
                'introduccion' => 'La geometría analítica une el álgebra y la geometría mediante el plano cartesiano. Aprenderemos a ubicar coordenadas y calcular distancias e inclinaciones.',
                'objetivos' => [
                    'Ubicar puntos coordenados.',
                    'Aplicar la fórmula de distancia entre dos puntos.',
                    'Definir la pendiente y el ángulo de inclinación.'
                ],
                'conocimientos_previos' => [
                    'Plano cartesiano, variables y Teorema de Pitágoras.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Distancia entre dos puntos',
                        'texto' => 'La distancia entre \\(P_1(x_1, y_1)\\) y \\(P_2(x_2, y_2)\\) se calcula mediante: \\(d = \\sqrt{(x_2 - x_1)^2 + (y_2 - y_1)^2}\\).'
                    ],
                    [
                        'titulo' => '2. Pendiente y ángulo de inclinación',
                        'texto' => 'La pendiente \\(m\\) de la recta es \\(m = \\frac{y_2 - y_1}{x_2 - x_1}\\), y su ángulo satisface \\(\\theta = \\arctan(m)\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Distancia',
                        'definicion' => 'La longitud del segmento de recta que une dos puntos.'
                    ],
                    [
                        'concepto' => 'Ángulo',
                        'definicion' => 'Inclinación de la recta respecto al eje x positivo.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Distancia Básica',
                        'explicacion' => 'Calcula la distancia entre \\(A(1, 2)\\) y \\(B(4, 6)\\).',
                        'solucion' => '1. Restamos coordenadas: \\(x_2 - x_1 = 3\\) e \\(y_2 - y_1 = 4\\).\n2. Elevamos al cuadrado y sumamos: \\(3^2 + 4^2 = 9 + 16 = 25\\).\n3. Extraemos la raíz: \\(d = \\sqrt{25} = 5\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Distancia con Negativos',
                        'explicacion' => 'Halla la distancia de \\(C(-2, 3)\\) a \\(D(1, -1)\\).',
                        'solucion' => '1. \\(\Delta x = 1 - (-2) = 3\\).\n2. \\(\Delta y = -1 - 3 = -4\\).\n3. Calculamos: \\(d = \\sqrt{3^2 + (-4)^2} = \\sqrt{9 + 16} = 5\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Calcular Pendiente',
                        'explicacion' => 'Encuentra la pendiente de la recta que pasa por \\((1, 3)\\) y \\((3, 9)\\).',
                        'solucion' => '1. Aplicamos la fórmula: \\(m = \\frac{9 - 3}{3 - 1} = \\frac{6}{2} = 3\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Ángulo de Inclinación',
                        'explicacion' => 'Halla el ángulo de inclinación si la pendiente es \\(m = 1\\).',
                        'solucion' => '1. Aplicamos \\(\\theta = \\arctan(1)\\).\n2. Obtenemos \\(\\theta = 45^\\circ\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Signo incorrecto al restar coordenadas de signo negativo',
                        'ejemplo_incorrecto' => 'Restar \\(3 - (-2)\\) como \\(3 - 2 = 1\\).',
                        'correccion' => 'La resta de un número negativo se convierte en suma: \\(3 - (-2) = 3 + 2 = 5\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Halla la distancia entre \\((0, 0)\\) y \\((3, 4)\\).', 'guia' => 'Pista: Aplica la fórmula de distancia simplificada desde el origen.'],
                    ['instruccion' => 'Calcula la distancia entre \\((-1, -1)\\) y \\((2, 3)\\).', 'guia' => 'Pista: Realiza la diferencia de coordenadas teniendo cuidado con los signos negativos.'],
                    ['instruccion' => 'Halla la distancia entre \\((2, 5)\\) y \\((8, 5)\\).', 'guia' => 'Pista: Al ser horizontal, la distancia es simplemente la diferencia absoluta de las coordenadas en x.'],
                    ['instruccion' => 'Calcula la distancia entre \\((3, 2)\\) y \\((3, -4)\\).', 'guia' => 'Pista: Al ser vertical, calcula la diferencia absoluta de las coordenadas en y.'],
                    ['instruccion' => 'Determina el valor de la distancia entre \\((1, -2)\\) y \\((4, 2)\\).', 'guia' => 'Pista: Aplica la fórmula de la raíz cuadrada de la suma de diferencias al cuadrado.'],
                    // Punto 2
                    ['instruccion' => 'Calcula la pendiente entre \\((2, 4)\\) y \\((4, 10)\\).', 'guia' => 'Pista: Resta las coordenadas correspondientes en la fórmula de la pendiente.'],
                    ['instruccion' => 'Calcula el ángulo de inclinación si la pendiente mide 0.', 'guia' => 'Pista: El ángulo cuya tangente es 0 mide 0° (línea horizontal).'],
                    ['instruccion' => 'Halla la pendiente que pasa por \\((1, 5)\\) y \\((3, 1)\\).', 'guia' => 'Pista: El resultado de la división será una pendiente negativa.'],
                    ['instruccion' => 'Determina el ángulo de inclinación si \\(m = \\sqrt{3}\\).', 'guia' => 'Pista: El ángulo cuya tangente es \\(\\sqrt{3}\\) es 60°.'],
                    ['instruccion' => 'Calcula la pendiente si la recta une \\((2, -3)\\) y \\((5, 3)\\).', 'guia' => 'Pista: Aplica la fórmula estándar de la pendiente.' ]
                ],
                'resumen' => 'La geometría analítica estudia las figuras en el plano mediante sus coordenadas, fundamentándose en conceptos de distancia, punto medio y pendiente.',
                'bibliografia' => [
                    'Kindle, J. H. (1993). Geometría Analítica (Serie Schaum). McGraw-Hill.',
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.'
                ]
            ],
            'matematicas_3_u3' => [
                'introduccion' => 'La recta es el lugar geométrico más sencillo en el plano cartesiano. Deduciremos sus diferentes formas de ecuación y analizaremos el paralelismo y la perpendicularidad.',
                'objetivos' => [
                    'Obtener la ecuación de una recta.',
                    'Transformar entre formas ordinaria y general.',
                    'Identificar paralelismo y perpendicularidad.'
                ],
                'conocimientos_previos' => [
                    'Cálculo de la pendiente entre dos puntos y despejes básicos.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Formas de la ecuación de la recta',
                        'texto' => 'La recta se expresa de forma punto-pendiente: \\(y - y_1 = m(x - x_1)\\), ordinaria: \\(y = mx + b\\) o general: \\(Ax + By + C = 0\\).'
                    ],
                    [
                        'titulo' => '2. Paralelismo y Perpendicularidad',
                        'texto' => 'Dos rectas son paralelas si sus pendientes son iguales (\\(m_1 = m_2\\)). Son perpendiculares si sus pendientes son recíprocas y opuestas (\\(m_1 \\times m_2 = -1\\)).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Forma General',
                        'definicion' => 'Ecuación lineal igualada a cero (Ax + By + C = 0).'
                    ],
                    [
                        'concepto' => 'Perpendiculares',
                        'definicion' => 'Líneas rectas que forman un ángulo recto al cruzarse.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Ecuación Punto-Pendiente',
                        'explicacion' => 'Halla la ecuación ordinaria de la recta con pendiente \\(m = 2\\) que pasa por \\(P(1, 3)\\).',
                        'solucion' => '1. Usamos punto-pendiente: \\(y - 3 = 2(x - 1)\\).\n2. Expandimos: \\(y - 3 = 2x - 2\\).\n3. Despejamos: \\(y = 2x + 1\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Ecuación General',
                        'explicacion' => 'Convierte la recta \\(y = -3x + 4\\) a la forma general.',
                        'solucion' => '1. Pasamos todos los términos al miembro izquierdo: \\(3x + y - 4 = 0\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Recta Paralela',
                        'explicacion' => 'Halla la pendiente de una recta paralela a \\(y = 5x - 2\\).',
                        'solucion' => '1. La pendiente dada es \\(m_1 = 5\\).\n2. Como son paralelas, la pendiente buscada es también \\(m_2 = 5\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Recta Perpendicular',
                        'explicacion' => 'Halla la pendiente de una recta perpendicular a \\(y = 2x + 1\\).',
                        'solucion' => '1. La pendiente original es \\(m_1 = 2\\).\n2. La pendiente perpendicular es \\(m_2 = -\\frac{1}{m_1} = -\\frac{1}{2} = -0.5\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Considerar paralelas con pendiente inversa',
                        'ejemplo_incorrecto' => 'Decir que la paralela de \\(y = 3x\\) tiene pendiente \\(m = 1/3\\).',
                        'correccion' => 'Rectas paralelas tienen exactamente la misma pendiente: \\(m_2 = 3\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Halla la ecuación ordinaria si \\(m=3\\) y pasa por \\((0, 5)\\).', 'guia' => 'Pista: La ordenada al origen es \\(b=5\\), sustituye en \\(y = mx + b\\).'],
                    ['instruccion' => 'Convierte \\(y = 2x - 5\\) a su forma general.', 'guia' => 'Pista: Iguala a cero pasando todos los términos a un lado.'],
                    ['instruccion' => 'Halla la ecuación punto-pendiente si pasa por \\((2, 4)\\) con \\(m=-1\\).', 'guia' => 'Pista: Sustituye directamente en \\(y - y_1 = m(x - x_1)\\).'],
                    ['instruccion' => 'Obtén la pendiente de la recta con ecuación general \\(3x - y + 2 = 0\\).', 'guia' => 'Pista: Despeja la variable \\(y\\) para llevarla a la forma ordinaria.'],
                    ['instruccion' => 'Determina la ecuación general de la recta que pasa por \\((1, 2)\\) y \\((3, 6)\\).', 'guia' => 'Pista: Halla primero la pendiente y aplica la forma punto-pendiente.'],
                    // Punto 2
                    ['instruccion' => 'Identifica si \\(y = 2x + 1\\) e \\(y = 2x - 3\\) son paralelas.', 'guia' => 'Pista: Compara el valor de sus pendientes \\(m\\).'],
                    ['instruccion' => 'Determina la pendiente perpendicular a una recta con \\(m = -3\\).', 'guia' => 'Pista: Aplica la relación recíproca y opuesta: \\(m_2 = -\\frac{1}{m_1}\\).'],
                    ['instruccion' => 'Halla la ecuación de la recta paralela a \\(y = 4x\\) que pasa por el origen.', 'guia' => 'Pista: Al ser paralela, comparte la pendiente \\(m=4\\). Al pasar por el origen, \\(b=0\\).'],
                    ['instruccion' => 'Halla la pendiente perpendicular a una recta con \\(m = \\frac{2}{3}\\).', 'guia' => 'Pista: Invierte la fracción y cambia su signo.'],
                    ['instruccion' => 'Verifica si \\(y = -x\\) e \\(y = x\\) son perpendiculares.', 'guia' => 'Pista: Multiplica sus pendientes: \\(-1 \\times 1 = -1\\).' ]
                ],
                'resumen' => 'La recta se define por una ecuación de primer grado. Las relaciones de paralelismo y perpendicularidad dependen de la igualdad o reciprocidad de sus pendientes.',
                'bibliografia' => [
                    'Kindle, J. H. (1993). Geometría Analítica (Serie Schaum). McGraw-Hill.',
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.'
                ]
            ],
            'matematicas_3_u4' => [
                'introduccion' => 'La parábola es una sección cónica definida como el lugar geométrico de puntos que equidistan de un foco y una directriz. Estudiaremos sus ecuaciones.',
                'objetivos' => [
                    'Comprender la parábola e identificar sus componentes.',
                    'Determinar ecuaciones ordinarias y generales.',
                    'Obtener elementos de la parábola a partir de su ecuación.'
                ],
                'conocimientos_previos' => [
                    'Completar el trinomio cuadrado perfecto y distancia en el plano.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Definición y Elementos de la Parábola',
                        'texto' => 'La parábola es el lugar geométrico de puntos que equidistan de un foco \\(F\\) y una directriz \\(d\\). Sus elementos son el vértice, el foco, la directriz y el lado recto \\(LR = |4p|\\).'
                    ],
                    [
                        'titulo' => '2. Ecuación ordinaria de la parábola',
                        'texto' => 'La ecuación ordinaria vertical es \\((x - h)^2 = 4p(y - k)\\) y la horizontal es \\((y - k)^2 = 4p(x - h)\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Parámetro p',
                        'definicion' => 'Distancia con signo desde el vértice al foco.'
                    ],
                    [
                        'concepto' => 'Lado Recto',
                        'definicion' => 'Ancho focal de la parábola equivalente a |4p|.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Calcular Lado Recto',
                        'explicacion' => 'Determina el lado recto de una parábola si el parámetro \\(p = 3\\).',
                        'solucion' => '1. Usamos la fórmula: \\(LR = |4p|\\).\n2. Evaluamos: \\(LR = |4(3)| = 12\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Obtener Foco en el origen',
                        'explicacion' => 'Halla el foco de la parábola vertical \\(x^2 = 8y\\) con vértice en el origen.',
                        'solucion' => '1. Identificamos \\(4p = 8 \\implies p = 2\\).\n2. Al ser vertical, el foco se sitúa en \\(F(0, p) = F(0, 2)\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Parábola Horizontal',
                        'explicacion' => 'Encuentra la ecuación ordinaria de la parábola horizontal con vértice \\(V(2, 3)\\) y \\(p=2\\).',
                        'solucion' => '1. Usamos la forma horizontal: \\((y - k)^2 = 4p(x - h)\\).\n2. Sustituimos valores: \\((y - 3)^2 = 8(x - 2)\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Parábola Vertical abre abajo',
                        'explicacion' => 'Encuentra la ecuación ordinaria de la parábola vertical con vértice \\(V(0, 0)\\) y \\(p=-3\\).',
                        'solucion' => '1. Usamos la forma vertical: \\(x^2 = 4py\\).\n2. Sustituimos: \\(x^2 = -12y\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Intercambiar los términos cuadráticos de las orientaciones',
                        'ejemplo_incorrecto' => 'Escribir \\((x - h)^2 = 4p(y - k)\\) para una parábola horizontal.',
                        'correccion' => 'La parábola horizontal tiene el término cuadrático en \\(y\\): \\((y - k)^2 = 4p(x - h)\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Calcula el lado recto de la parábola si \\(p = -4\\).', 'guia' => 'Pista: El lado recto es siempre positivo: \\(LR = |4p|\\).'],
                    ['instruccion' => 'Halla el foco de la parábola \\(x^2 = 12y\\).', 'guia' => 'Pista: Determina \\(4p = 12\\) para calcular \\(p\\).'],
                    ['instruccion' => 'Determina la directriz de la parábola vertical \\(x^2 = 8y\\) con vértice en el origen.', 'guia' => 'Pista: La ecuación de la directriz es \\(y = -p\\).'],
                    ['instruccion' => 'Si el foco es \\(F(3, 0)\\) y el vértice es \\(V(0,0)\\), halla el parámetro \\(p\\).', 'guia' => 'Pista: El parámetro es la distancia con signo del vértice al foco, \\(p=3\\).'],
                    ['instruccion' => 'Halla el lado recto de la parábola con ecuación \\(y^2 = 20x\\).', 'guia' => 'Pista: El coeficiente del término lineal es igual a \\(4p\\).'],
                    // Punto 2
                    ['instruccion' => 'Halla la ecuación ordinaria de la parábola horizontal con \\(V(0,0)\\) y \\(p=4\\).', 'guia' => 'Pista: Aplica la ecuación \\(y^2 = 4px\\).'],
                    ['instruccion' => 'Encuentra la ecuación ordinaria con \\(V(1, 2)\\), vertical y \\(p=2\\).', 'guia' => 'Pista: Usa la forma \\((x - h)^2 = 4p(y - k)\\).'],
                    ['instruccion' => 'Halla la ecuación ordinaria de la parábola vertical con \\(V(3, -1)\\) y \\(p=-1\\).', 'guia' => 'Pista: Sustituye con cuidado los signos negativos en los binomios.'],
                    ['instruccion' => 'Convierte la ecuación \\(y^2 = 8x\\) a su representación con elementos de parábola horizontal.', 'guia' => 'Pista: Identifica que \\(h=0\\), \\(k=0\\) y \\(p=2\\).'],
                    ['instruccion' => 'Encuentra la ecuación ordinaria con \\(V(2, 2)\\), horizontal y \\(p=-2\\).', 'guia' => 'Pista: Sustituye directamente en la forma horizontal.' ]
                ],
                'resumen' => 'La parábola es determinada por la distancia constante a un foco y a una directriz. Su ecuación depende del vértice y de la orientación del eje focal.',
                'bibliografia' => [
                    'Kindle, J. H. (1993). Geometría Analítica (Serie Schaum). McGraw-Hill.',
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.'
                ]
            ],
            'matematicas_3_u5' => [
                'introduccion' => 'La circunferencia y la elipse son cónicas cerradas fundamentales. Analizaremos sus ecuaciones ordinarias y generales, comprendiendo sus elementos característicos.',
                'objetivos' => [
                    'Deducir la ecuación de la circunferencia.',
                    'Identificar los elementos de la elipse.',
                    'Transformar ecuaciones completando cuadrados.'
                ],
                'conocimientos_previos' => [
                    'Fórmula de distancia, factorización y completar cuadrados.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. La Circunferencia',
                        'texto' => 'La circunferencia es el conjunto de puntos que equidistan de un centro \\(C(h, k)\\) un radio \\(r\\). Ecuación: \\((x - h)^2 + (y - k)^2 = r^2\\).'
                    ],
                    [
                        'titulo' => '2. La Elipse',
                        'texto' => 'La elipse es el lugar geométrico donde la suma de distancias a dos focos es constante. Ecuación ordinaria horizontal: \\(\\frac{(x-h)^2}{a^2} + \\frac{(y-k)^2}{b^2} = 1\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Excentricidad',
                        'definicion' => 'Medida del achatamiento de la elipse (e = c/a).'
                    ],
                    [
                        'concepto' => 'Semieje mayor',
                        'definicion' => 'Distancia del centro al vértice de mayor extensión (a).'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Circunferencia en el origen',
                        'explicacion' => 'Halla la ecuación de la circunferencia con centro \\(C(0, 0)\\) y radio \\(r = 4\\).',
                        'solucion' => '1. Usamos la forma estándar: \\(x^2 + y^2 = r^2\\).\n2. Sustituimos \\(r=4\\): \\(x^2 + y^2 = 16\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Circunferencia fuera del origen',
                        'explicacion' => 'Halla la ecuación ordinaria con centro \\(C(2, -3)\\) y radio \\(r = 5\\).',
                        'solucion' => '1. Usamos la forma ordinaria: \\((x - h)^2 + (y - k)^2 = r^2\\).\n2. Sustituimos: \\((x - 2)^2 + (y - (-3))^2 = 5^2 \\implies (x - 2)^2 + (y + 3)^2 = 25\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Elipse Horizontal del origen',
                        'explicacion' => 'Identifica los semiejes de la elipse \\(\\frac{x^2}{25} + \\frac{y^2}{9} = 1\\).',
                        'solucion' => '1. Comparamos denominadores: el mayor es \\(a^2 = 25\\) y el menor \\(b^2 = 9\\).\n2. Obtenemos \\(a = 5\\) (semieje mayor) y \\(b = 3\\) (semieje menor).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Distancia Focal de la Elipse',
                        'explicacion' => 'Calcula la distancia focal \\(c\\) si la elipse tiene \\(a=5\\) y \\(b=4\\).',
                        'solucion' => '1. Aplicamos la relación: \\(c^2 = a^2 - b^2 = 25 - 16 = 9\\).\n2. Obtenemos \\(c = \\sqrt{9} = 3\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Considerar el término r² como el radio directo de la circunferencia',
                        'ejemplo_incorrecto' => 'Afirmar que la circunferencia \\((x - 1)^2 + (y - 2)^2 = 9\\) tiene un radio de 9.',
                        'correccion' => 'El miembro derecho representa \\(r^2\\). El radio es la raíz cuadrada: \\(r = \\sqrt{9} = 3\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Halla la ecuación de la circunferencia con centro en \\((0, 0)\\) y radio \\(r = 3\\).', 'guia' => 'Pista: Sustituye el valor del radio al cuadrado.'],
                    ['instruccion' => 'Halla la ecuación ordinaria si \\(C(1, 2)\\) y \\(r = 2\\).', 'guia' => 'Pista: Aplica la ecuación ordinaria con \\(h=1\\), \\(k=2\\) y \\(r=2\\).'],
                    ['instruccion' => 'Determina el centro y el radio de la circunferencia \\(x^2 + y^2 = 49\\).', 'guia' => 'Pista: El centro es el origen y el radio es la raíz cuadrada de 49.'],
                    ['instruccion' => 'Determina el centro de la circunferencia \\((x - 5)^2 + (y + 1)^2 = 16\\).', 'guia' => 'Pista: Cambia los signos de las coordenadas en los binomios.'],
                    ['instruccion' => 'Halla el radio de la circunferencia con ecuación \\(x^2 + y^2 - 100 = 0\\).', 'guia' => 'Pista: Despeja el término constante para obtener \\(r^2 = 100\\).'],
                    // Punto 2
                    ['instruccion' => 'Halla la longitud del semieje mayor de \\(\\frac{x^2}{16} + \\frac{y^2}{4} = 1\\).', 'guia' => 'Pista: El semieje mayor \\(a\\) es la raíz del denominador más grande.'],
                    ['instruccion' => 'Halla el semieje menor de \\(\\frac{x^2}{36} + \\frac{y^2}{25} = 1\\).', 'guia' => 'Pista: El semieje menor \\(b\\) es la raíz del denominador menor.'],
                    ['instruccion' => 'Calcula la distancia focal \\(c\\) si \\(a=10\\) y \\(b=6\\).', 'guia' => 'Pista: Aplica \\(c = \\sqrt{a^2 - b^2}\\).'],
                    ['instruccion' => 'Determina la excentricidad de la elipse con \\(a=5\\) y \\(c=3\\).', 'guia' => 'Pista: Aplica la fórmula \\(e = \\frac{c}{a}\\).'],
                    ['instruccion' => 'Encuentra los vértices sobre el eje mayor para la elipse \\(\\frac{x^2}{9} + \\frac{y^2}{4} = 1\\).', 'guia' => 'Pista: Al ser horizontal, los vértices están en \\((\\pm a, 0)\\) con \\(a=3\\).' ]
                ],
                'resumen' => 'La circunferencia es una cónica con excentricidad cero. La elipse posee dos focos y su geometría depende de sus semiejes.',
                'bibliografia' => [
                    'Kindle, J. H. (1993). Geometría Analítica (Serie Schaum). McGraw-Hill.',
                    'Swokowski, E. W., & Cole, J. A. (2011). Álgebra y Trigonometría con Geometría Analítica. Cengage Learning.'
                ]
            ],

            // SEMESTRE 4: Matemáticas IV
            'matematicas_4_u1' => [
                'introduccion' => 'Las funciones polinomiales extienden el concepto de funciones a exponentes mayores. Analizaremos dominio, rango, operaciones algebraicas y cálculo de raíces.',
                'objetivos' => [
                    'Determinar dominio y rango.',
                    'Realizar operaciones con funciones.',
                    'Encontrar raíces mediante división sintética.'
                ],
                'conocimientos_previos' => [
                    'Concepto de función, dominio y factorización.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Comportamiento y propiedades',
                        'texto' => 'Una función polinomial de grado \\(n\\) tiene como dominio todos los números reales \\(\\mathbb{R}\\).'
                    ],
                    [
                        'titulo' => '2. División sintética y Teorema del Residuo',
                        'texto' => 'La división sintética facilita la división de un polinomio entre \\(x - c\\). El Teorema del Residuo establece que el residuo de dividir \\(P(x)\\) entre \\(x - c\\) es \\(P(c)\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Raíz',
                        'definicion' => 'Valor donde la función se anula (f(c) = 0).'
                    ],
                    [
                        'concepto' => 'Grado',
                        'definicion' => 'El mayor exponente de la variable en el polinomio.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Dominio de un Polinomio',
                        'explicacion' => 'Determina el dominio de la función \\(f(x) = x^3 - 2x + 1\\).',
                        'solucion' => '1. Las funciones polinomiales no poseen restricciones como divisiones por cero o raíces negativas.\n2. Su dominio son todos los reales: \\(D_f = \\mathbb{R}\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Grado del Polinomio',
                        'explicacion' => 'Identifica el grado de la función \\(g(x) = 5x^4 - x^2 + 2\\).',
                        'solucion' => '1. El término con el mayor exponente es \\(5x^4\\).\n2. Por lo tanto, la función es de grado 4.'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Teorema del Residuo',
                        'explicacion' => 'Calcula el residuo de dividir \\(P(x) = x^2 + 3x - 4\\) entre \\(x - 2\\).',
                        'solucion' => '1. Evaluamos \\(P(2) = 2^2 + 3(2) - 4 = 4 + 6 - 4 = 6\\).\n2. El residuo de la división es 6.'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Teorema del Factor',
                        'explicacion' => 'Determina si \\(x - 1\\) es un factor de \\(P(x) = x^2 - 1\\).',
                        'solucion' => '1. Evaluamos \\(P(1) = 1^2 - 1 = 0\\).\n2. Al ser el residuo cero, \\(x - 1\\) sí es un factor.'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Considerar restricciones en el dominio de funciones polinomiales',
                        'ejemplo_incorrecto' => 'Decir que el dominio de \\(f(x) = x^2 - 4\\) excluye a 2 y -2.',
                        'correccion' => 'Las raíces son donde la función se hace cero, no restricciones de dominio. El dominio de cualquier polinomio son todos los reales.'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Halla el dominio de la función \\(f(x) = -x^4 + 2x^2\\).', 'guia' => 'Pista: Es una función polinomial, no contiene restricciones reales.'],
                    ['instruccion' => 'Identifica el grado de \\(f(x) = x^5 - 3x^2 + 4\\).', 'guia' => 'Pista: Identifica el exponente más alto de la variable.'],
                    ['instruccion' => 'Determina el comportamiento de \\(f(x) = x^3\\) cuando x tiende a infinito positivo.', 'guia' => 'Pista: Si elevas números positivos muy grandes al cubo, obtienes valores positivos infinitos.'],
                    ['instruccion' => 'Halla el término independiente de la función \\(f(x) = x^2 - 5x + 6\\).', 'guia' => 'Pista: Es el término numérico constante que no posee variable.'],
                    ['instruccion' => 'Calcula el dominio de \\(f(x) = 2x + 10\\).', 'guia' => 'Pista: Las funciones lineales de grado 1 tienen dominio real.'],
                    // Punto 2
                    ['instruccion' => 'Calcula el residuo de dividir \\(x^2 - 5x + 6\\) entre \\(x - 3\\).', 'guia' => 'Pista: Evalúa el polinomio en \\(x = 3\\).'],
                    ['instruccion' => 'Verifica si \\(x - 2\\) es factor de \\(x^2 - 4\\).', 'guia' => 'Pista: Evalúa en \\(x = 2\\) y verifica si da 0.'],
                    ['instruccion' => 'Calcula el residuo de dividir \\(x^3 - 1\\) entre \\(x - 1\\).', 'guia' => 'Pista: Aplica el Teorema del Residuo sustituyendo \\(x=1\\).'],
                    ['instruccion' => 'Determina si 1 es raíz de \\(x^2 + 2x - 3\\).', 'guia' => 'Pista: Comprueba si \\(P(1) = 0\\).'],
                    ['instruccion' => 'Usa el teorema del factor para ver si \\(x + 1\\) divide a \\(x^2 + 1\\).', 'guia' => 'Pista: Evalúa el polinomio en \\(x = -1\\). Al dar 2, no es factor.' ]
                ],
                'resumen' => 'Las funciones polinomiales son suaves y continuas con dominio real. La división sintética ayuda a encontrar sus raíces algebraicas.',
                'bibliografia' => [
                    'Larson, R., & Falvo, D. (2011). Precálculo. Cengage Learning.',
                    'Stewart, J., Redlin, L., & Watson, S. (2012). Precálculo: Matemáticas para el cálculo. Cengage Learning.'
                ]
            ],
            'matematicas_4_u2' => [
                'introduccion' => 'Las funciones racionales y con radicales presentan comportamientos particulares como las asíntotas y las restricciones de dominio.',
                'objetivos' => [
                    'Calcular el dominio y las asíntotas en funciones racionales.',
                    'Determinar el dominio de funciones con radicales.',
                    'Esbozar gráficas de funciones racionales.'
                ],
                'conocimientos_previos' => [
                    'Desigualdades de primer y segundo grado y divisiones.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Funciones racionales y asíntotas',
                        'texto' => 'Una función racional es de la forma \\(f(x) = \\frac{P(x)}{Q(x)}\\). Las asíntotas verticales ocurren donde el denominador \\(Q(x) = 0\\).'
                    ],
                    [
                        'titulo' => '2. Funciones con radicales',
                        'texto' => 'Para funciones con radicales pares \\(f(x) = \\sqrt{g(x)}\\), el dominio requiere que el radicando sea no negativo: \\(g(x) \\ge 0\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Asíntota Vertical',
                        'definicion' => 'Línea vertical x = c a la que se aproxima la función sin tocarla.'
                    ],
                    [
                        'concepto' => 'Asíntota Horizontal',
                        'definicion' => 'Línea horizontal y = k que describe el comportamiento a largo plazo.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Hallar Asíntota Vertical',
                        'explicacion' => 'Determina la asíntota vertical de \\(f(x) = \\frac{3}{x - 2}\\).',
                        'solucion' => '1. Igualamos el denominador a cero: \\(x - 2 = 0\\).\n2. La asíntota vertical está en \\(x = 2\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Hallar Asíntota Horizontal',
                        'explicacion' => 'Halla la asíntota horizontal de \\(f(x) = \\frac{2x + 1}{x - 3}\\).',
                        'solucion' => '1. Comparamos los grados (ambos de grado 1).\n2. Dividimos los coeficientes principales: \\(y = \\frac{2}{1} = 2\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Dominio de Raíz Cuadrada Básica',
                        'explicacion' => 'Calcula el dominio de \\(f(x) = \\sqrt{x - 3}\\).',
                        'solucion' => '1. Planteamos la desigualdad: \\(x - 3 \\ge 0\\).\n2. Despejamos: \\(x \\ge 3\\). El dominio es \\([3, \\infty)\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Dominio con coeficiente negativo',
                        'explicacion' => 'Calcula el dominio de \\(f(x) = \\sqrt{6 - 2x}\\).',
                        'solucion' => '1. Planteamos: \\(6 - 2x \\ge 0 \\implies 6 \\ge 2x \\implies x \\le 3\\).\n2. El dominio es \\((-\\infty, 3]\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Considerar valores que hacen cero al denominador en el dominio',
                        'ejemplo_incorrecto' => 'Decir que el dominio de \\(f(x) = \\frac{1}{x}\\) incluye al 0.',
                        'correccion' => 'La división entre cero no está definida. El dominio excluye al 0: \\(D_f = \\mathbb{R} \\setminus \\{0\\}\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Halla el dominio de \\(f(x) = \\frac{1}{x - 5}\\).', 'guia' => 'Pista: Excluye el valor de x que hace cero el denominador.'],
                    ['instruccion' => 'Determina la asíntota vertical de \\(f(x) = \\frac{2}{x + 4}\\).', 'guia' => 'Pista: Iguala el denominador a cero.'],
                    ['instruccion' => 'Halla la asíntota horizontal de \\(f(x) = \\frac{5x}{x - 2}\\).', 'guia' => 'Pista: Divide los coeficientes principales de las variables con el mayor exponente.'],
                    ['instruccion' => '¿Tiene asíntota horizontal la función \\(f(x) = \\frac{x^2}{x + 1}\\)?', 'guia' => 'Pista: No, si el grado del numerador es mayor que el del denominador, no hay AH.'],
                    ['instruccion' => 'Determina la asíntota vertical de la función \\(f(x) = \\frac{x}{x^2 - 9}\\).', 'guia' => 'Pista: El denominador tiene dos raíces, \\(x = 3\\) y \\(x = -3\\).'],
                    // Punto 2
                    ['instruccion' => 'Calcula el dominio de \\(f(x) = \\sqrt{x - 5}\\).', 'guia' => 'Pista: Plantea la condición de no negatividad del radicando.'],
                    ['instruccion' => 'Calcula el dominio de \\(f(x) = \\sqrt{2x - 8}\\).', 'guia' => 'Pista: Resuelve la desigualdad \\(2x - 8 \\ge 0\\).'],
                    ['instruccion' => 'Halla el dominio de \\(f(x) = \\sqrt{10 - x}\\).', 'guia' => 'Pista: Resuelve la desigualdad con signo negativo en la variable.'],
                    ['instruccion' => 'Determina si \\(x = 2\\) está en el dominio de \\(f(x) = \\sqrt{x - 4}\\).', 'guia' => 'Pista: Comprueba si al sustituir se obtiene una raíz de número negativo.'],
                    ['instruccion' => 'Calcula el dominio de \\(f(x) = \\sqrt{3x - 12}\\).', 'guia' => 'Pista: Resuelve la inecuación lineal.' ]
                ],
                'resumen' => 'Las funciones racionales y radicales poseen restricciones que determinan su dominio y formas de comportamiento como las asíntotas.',
                'bibliografia' => [
                    'Larson, R., & Falvo, D. (2011). Precálculo. Cengage Learning.',
                    'Stewart, J., Redlin, L., & Watson, S. (2012). Precálculo: Matemáticas para el cálculo. Cengage Learning.'
                ]
            ],
            'matematicas_4_u3' => [
                'introduccion' => 'Las funciones exponenciales y logarítmicas modelan fenómenos de crecimiento acelerado y escala logarítmica. Comprenderemos sus propiedades inversas.',
                'objetivos' => [
                    'Graficar funciones exponenciales y logarítmicas.',
                    'Aplicar las propiedades de los logaritmos.',
                    'Resolver ecuaciones exponenciales y logarítmicas.'
                ],
                'conocimientos_previos' => [
                    'Leyes de exponentes y despejes básicos.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Relación inversa y gráficas',
                        'texto' => 'La exponencial \\(f(x) = a^x\\) y la logarítmica \\(g(x) = \\log_a(x)\\) son funciones inversas y sus gráficas se reflejan respecto a \\(y = x\\).'
                    ],
                    [
                        'titulo' => '2. Propiedades de los logaritmos',
                        'texto' => 'Las propiedades principales son: producto: \\(\\log(uv) = \\log(u) + \\log(v)\\), cociente: \\(\\log(\\frac{u}{v}) = \\log(u) - \\log(v)\\) y potencia: \\(\\log(u^c) = c \\log(u)\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Logaritmo',
                        'definicion' => 'Exponente al que se eleva la base para obtener un número.'
                    ],
                    [
                        'concepto' => 'Base e',
                        'definicion' => 'Base para el logaritmo natural (ln).'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Conversión a Logaritmo',
                        'explicacion' => 'Expresa \\(2^3 = 8\\) en su forma logarítmica.',
                        'solucion' => '1. La base es 2, el exponente es 3 y el resultado es 8.\n2. Forma logarítmica: \\(\\log_2(8) = 3\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Conversión a Exponencial',
                        'explicacion' => 'Expresa \\(\\log_3(9) = 2\\) en su forma exponencial.',
                        'solucion' => '1. La base es 3, el exponente es 2 y el resultado es 9.\n2. Forma exponencial: \\(3^2 = 9\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Desarrollar con propiedades',
                        'explicacion' => 'Desarrolla usando propiedades de logaritmos: \\(\\log(x^2 y)\\).',
                        'solucion' => '1. Usamos la propiedad del producto: \\(\\log(x^2) + \\log(y)\\).\n2. Usamos la propiedad de la potencia: \\(2\\log(x) + \\log(y)\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Simplificar con propiedades',
                        'explicacion' => 'Simplifica en un solo logaritmo: \\(\\log(12) - \\log(3)\\).',
                        'solucion' => '1. Aplicamos la propiedad del cociente: \\(\\log(\\frac{12}{3})\\).\n2. Simplificamos la división: \\(\\log(4)\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Confundir el logaritmo de una suma con la suma de logaritmos',
                        'ejemplo_incorrecto' => 'Escribir \\(\\log(x + y) = \\log(x) + \\log(y)\\).',
                        'correccion' => 'No existe propiedad para la suma dentro de un logaritmo. La propiedad de suma aplica al producto: \\(\\log(x \\times y) = \\log(x) + \\log(y)\\).'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Expresa \\(5^2 = 25\\) en forma logarítmica.', 'guia' => 'Pista: La base de la potencia es la base del logaritmo.'],
                    ['instruccion' => 'Expresa \\(\\log_10(100) = 2\\) en forma exponencial.', 'guia' => 'Pista: La base elevada al resultado es igual al argumento.'],
                    ['instruccion' => '¿Cuál es el dominio de la función \\(f(x) = \\log(x)\\)?', 'guia' => 'Pista: Los logaritmos solo se definen para números reales estrictamente mayores a cero.'],
                    ['instruccion' => 'Halla el valor de \\(\\log_2(16)\\).', 'guia' => 'Pista: Piensa a qué potencia debes elevar 2 para obtener 16.'],
                    ['instruccion' => 'Calcula el valor de \\(\\ln(e)\\).', 'guia' => 'Pista: El logaritmo natural ln tiene base e. Cualquier logaritmo de su base es 1.'],
                    // Punto 2
                    ['instruccion' => 'Desarrolla: \\(\\log(ab^3)\\).', 'guia' => 'Pista: Aplica la propiedad del producto y luego la de la potencia.'],
                    ['instruccion' => 'Simplifica en un solo término: \\(3\\log(x) - \\log(y)\\).', 'guia' => 'Pista: Sube el 3 como exponente y luego aplica la propiedad de la resta como división.'],
                    ['instruccion' => 'Desarrolla: \\(\\log\\left(\\frac{x^2}{y}\\right)\\).', 'guia' => 'Pista: Aplica la propiedad del cociente y luego la de la potencia.'],
                    ['instruccion' => 'Halla el valor de \\(\\log(5) + \\log(2)\\).', 'guia' => 'Pista: Agrúpalos multiplicando: \\(\\log(5 \\times 2) = \\log(10)\\).'],
                    ['instruccion' => 'Expresa como un solo término: \\(\\log(x) + 2\\log(y)\\).', 'guia' => 'Pista: Aplica la propiedad de potencia primero en el término de y.' ]
                ],
                'resumen' => 'Las exponenciales y logaritmos son funciones inversas fundamentales para resolver ecuaciones donde las variables se encuentran en los exponentes.',
                'bibliografia' => [
                    'Larson, R., & Falvo, D. (2011). Precálculo. Cengage Learning.',
                    'Stewart, J., Redlin, L., & Watson, S. (2012). Precálculo: Matemáticas para el cálculo. Cengage Learning.'
                ]
            ],
            'matematicas_4_u4' => [
                'introduccion' => 'Las funciones trigonométricas analizan el comportamiento periódico. Utilizando el círculo unitario y radianes modelaremos ciclos recurrentes de la naturaleza.',
                'objetivos' => [
                    'Definir radianes y realizar conversiones.',
                    'Comprender el círculo unitario.',
                    'Graficar periodicidad en funciones seno y coseno.'
                ],
                'conocimientos_previos' => [
                    'Razones trigonométricas en triángulos rectángulos y geometría básica.'
                ],
                'explicacion' => [
                    [
                        'titulo' => '1. Radianes y Círculo Unitario',
                        'texto' => 'La relación entre grados y radianes es \\(180^\\circ = \\pi\\text{ rad}\\). El círculo unitario posee radio \\(r = 1\\).'
                    ],
                    [
                        'titulo' => '2. Características de las ondas trigonométricas',
                        'texto' => 'Las funciones trigonométricas seno y coseno se repiten de forma cíclica con un período estándar de \\(2\\pi\\).'
                    ]
                ],
                'conceptos_clave' => [
                    [
                        'concepto' => 'Radián',
                        'definicion' => 'Unidad de medida de ángulos basada en la longitud del arco.'
                    ],
                    [
                        'concepto' => 'Período',
                        'definicion' => 'Intervalo en el cual se repite un ciclo de la función.'
                    ]
                ],
                'ejemplos' => [
                    // Punto 1
                    [
                        'titulo' => 'Ejemplo 1.1: Conversión a Radianes',
                        'explicacion' => 'Convierte \\(90^\\circ\\) a radianes.',
                        'solucion' => '1. Multiplicamos por la fracción de conversión: \\(90^\\circ \\times \\frac{\\pi}{180^\\circ} = \\frac{90}{180}\\pi\\).\n2. Simplificamos: \\(\\frac{1}{2}\\pi\\text{ rad}\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 1.2: Conversión a Grados',
                        'explicacion' => 'Convierte \\(\\pi\\text{ rad}\\) a grados.',
                        'solucion' => '1. Sabemos por definición que \\(\\pi\\text{ rad} = 180^\\circ\\).'
                    ],
                    // Punto 2
                    [
                        'titulo' => 'Ejemplo 2.1: Identificar Amplitud',
                        'explicacion' => 'Halla la amplitud de la función trigonométrica \\(y = 3\\text{sen}(x)\\).',
                        'solucion' => '1. El coeficiente que acompaña a la función es 3.\n2. La amplitud es \\(|3| = 3\\).'
                    ],
                    [
                        'titulo' => 'Ejemplo 2.2: Período de la función',
                        'explicacion' => '¿Cuál es el período básico de \\(y = \\cos(x)\\)?',
                        'solucion' => '1. Las funciones trigonométricas básicas completan su recorrido en un círculo unitario.\n2. El período es \\(2\\pi\\).'
                    ]
                ],
                'errores_comunes' => [
                    [
                        'error' => 'Considerar el período de tangente como 2π',
                        'ejemplo_incorrecto' => 'Afirmar que la función tangente se repite cada 2π radianes.',
                        'correccion' => 'El período de la función tangente básica es \\(\\pi\\) radianes.'
                    ]
                ],
                'ejercicios_guiados' => [
                    // Punto 1
                    ['instruccion' => 'Convierte \\(45^\\circ\\) a radianes.', 'guia' => 'Pista: Multiplica por el factor \\(\\frac{\\pi}{180^\\circ}\\) y simplifica la fracción.'],
                    ['instruccion' => 'Convierte \\(\\frac{\\pi}{3}\\text{ rad}\\) a grados.', 'guia' => 'Pista: Sustituye el valor de \\(\\pi\\) por \\(180^\\circ\\).'],
                    ['instruccion' => '¿Qué coordenadas tiene el ángulo de 0° en el círculo unitario?', 'guia' => 'Pista: Está sobre el eje x a la derecha, por lo que sus coordenadas son \\((1, 0)\\).'],
                    ['instruccion' => 'Determina el seno de 90° usando el círculo unitario.', 'guia' => 'Pista: El seno es la coordenada vertical en el punto \\((0, 1)\\), es decir, 1.'],
                    ['instruccion' => 'Convierte \\(180^\\circ\\) a radianes.', 'guia' => 'Pista: Por definición, equivale directamente a \\(\\pi\\) radianes.'],
                    // Punto 2
                    ['instruccion' => 'Identifica la amplitud de \\(y = 5\\cos(x)\\).', 'guia' => 'Pista: La amplitud es el valor absoluto del coeficiente principal.'],
                    ['instruccion' => 'Determina el período de la función \\(y = \\text{sen}(2x)\\).', 'guia' => 'Pista: El período se calcula mediante \\(T = \\frac{2\\pi}{B}\\). Aquí \\(B = 2\\).'],
                    ['instruccion' => 'Halla el valor máximo que puede tomar la función \\(y = \\cos(x)\\).', 'guia' => 'Pista: En el círculo unitario, la coordenada horizontal x máxima es 1.'],
                    ['instruccion' => 'Halla el valor mínimo que puede tomar la función \\(y = \\text{sen}(x)\\).', 'guia' => 'Pista: El valor mínimo de la coordenada y es -1.'],
                    ['instruccion' => 'Calcula la amplitud de \\(y = -2\\text{sen}(x)\\).', 'guia' => 'Pista: La amplitud es siempre una magnitud positiva, calcula el valor absoluto de -2.' ]
                ],
                'resumen' => 'Las funciones trigonométricas describen movimientos cíclicos y de oscilación basados en las coordenadas del círculo unitario.',
                'bibliografia' => [
                    'Larson, R., & Falvo, D. (2011). Precálculo. Cengage Learning.',
                    'Stewart, J., Redlin, L., & Watson, S. (2012). Precálculo: Matemáticas para el cálculo. Cengage Learning.'
                ]
            ]
        ];

        // Escribimos cada archivo JSON
        foreach ($unidadesContent as $filename => $data) {
            $filePath = $directoryPath . '/' . $filename . '.json';

            // Convertimos a JSON con formato legible y caracteres no escapados
            $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            File::put($filePath, $jsonString);
            $this->command->info("Archivo generado con éxito: {$filename}.json");
        }

        $this->command->info("Seeder de contenido completado. Se generaron 17 archivos JSON de unidades.");
    }
}
