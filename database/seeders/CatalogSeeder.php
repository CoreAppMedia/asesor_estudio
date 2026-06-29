<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\Semestre;
use App\Models\Unidad;
use App\Models\Tema;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Materia Principal
        $materia = Materia::create([
            'nombre' => 'Matemáticas CCH',
            'descripcion' => 'Plan de estudios oficial de matemáticas para el Colegio de Ciencias y Humanidades (UNAM).'
        ]);

        // 2. Semestres (1ro al 4to)
        $semestresData = [
            1 => [
                'nombre' => 'Matemáticas I',
                'unidades' => [
                    [
                        'numero' => 1,
                        'nombre' => 'Números y operaciones',
                        'descripcion' => 'Transición a enteros/reales, jerarquía de operaciones y lenguaje algebraico.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Transición a enteros y reales'],
                            ['numero' => 2, 'nombre' => 'Jerarquía de operaciones'],
                            ['numero' => 3, 'nombre' => 'Lenguaje algebraico']
                        ]
                    ],
                    [
                        'numero' => 2,
                        'nombre' => 'Variación lineal',
                        'descripcion' => 'Proporcionalidad, tablas, gráficas y y = mx + b.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Proporcionalidad directa'],
                            ['numero' => 2, 'nombre' => 'Representación en tablas y gráficas'],
                            ['numero' => 3, 'nombre' => 'Función lineal y = mx + b']
                        ]
                    ],
                    [
                        'numero' => 3,
                        'nombre' => 'Ecuaciones lineales',
                        'descripcion' => 'Despejes, igualdad y modelado de problemas.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Concepto de igualdad y despejes'],
                            ['numero' => 2, 'nombre' => 'Modelado y resolución de problemas']
                        ]
                    ],
                    [
                        'numero' => 4,
                        'nombre' => 'Sistemas 2x2',
                        'descripcion' => 'Métodos (sustitución, reducción, gráfico) y resolución.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Método de sustitución'],
                            ['numero' => 2, 'nombre' => 'Método de reducción (suma y resta)'],
                            ['numero' => 3, 'nombre' => 'Método gráfico y resolución de problemas']
                        ]
                    ]
                ]
            ],
            2 => [
                'nombre' => 'Matemáticas II',
                'unidades' => [
                    [
                        'numero' => 1,
                        'nombre' => 'Ecuaciones cuadráticas',
                        'descripcion' => 'Factorización, fórmula general y análisis del discriminante.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Método de factorización'],
                            ['numero' => 2, 'nombre' => 'Fórmula general'],
                            ['numero' => 3, 'nombre' => 'Análisis del discriminante']
                        ]
                    ],
                    [
                        'numero' => 2,
                        'nombre' => 'Funciones cuadráticas',
                        'descripcion' => 'Parábolas, vértices, optimización básica.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'La parábola y sus propiedades'],
                            ['numero' => 2, 'nombre' => 'Cálculo del vértice'],
                            ['numero' => 3, 'nombre' => 'Problemas de optimización básica']
                        ]
                    ],
                    [
                        'numero' => 3,
                        'nombre' => 'Geometría plana',
                        'descripcion' => 'Ángulos, triángulos, perímetros y áreas.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Ángulos y su clasificación'],
                            ['numero' => 2, 'nombre' => 'Triángulos y propiedades de sus lados y ángulos'],
                            ['numero' => 3, 'nombre' => 'Cálculo de perímetros y áreas']
                        ]
                    ],
                    [
                        'numero' => 4,
                        'nombre' => 'Congruencia y semejanza',
                        'descripcion' => 'Criterios de triángulos, Teorema de Tales y Pitágoras.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Criterios de congruencia y semejanza de triángulos'],
                            ['numero' => 2, 'nombre' => 'Teorema de Tales'],
                            ['numero' => 3, 'nombre' => 'Teorema de Pitágoras y aplicaciones']
                        ]
                    ]
                ]
            ],
            3 => [
                'nombre' => 'Matemáticas III',
                'unidades' => [
                    [
                        'numero' => 1,
                        'nombre' => 'Trigonometría',
                        'descripcion' => 'Razones en triángulo rectángulo, Leyes de Senos/Cosenos.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Razones trigonométricas en triángulo rectángulo'],
                            ['numero' => 2, 'nombre' => 'Ley de Senos'],
                            ['numero' => 3, 'nombre' => 'Ley de Cosenos']
                        ]
                    ],
                    [
                        'numero' => 2,
                        'nombre' => 'Geometría analítica',
                        'descripcion' => 'Plano cartesiano, distancia, pendiente.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'El plano cartesiano'],
                            ['numero' => 2, 'nombre' => 'Fórmula de distancia entre dos puntos'],
                            ['numero' => 3, 'nombre' => 'Pendiente y ángulo de inclinación']
                        ]
                    ],
                    [
                        'numero' => 3,
                        'nombre' => 'La recta',
                        'descripcion' => 'Ecuaciones (punto-pendiente, general), paralelismo/perpendicularidad.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Ecuación punto-pendiente de la recta'],
                            ['numero' => 2, 'nombre' => 'Ecuación general y ordinaria de la recta'],
                            ['numero' => 3, 'nombre' => 'Condiciones de paralelismo y perpendicularidad']
                        ]
                    ],
                    [
                        'numero' => 4,
                        'nombre' => 'Parábola',
                        'descripcion' => 'Lugar geométrico, vértice (h, k), ecuación general.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'La parábola como lugar geométrico'],
                            ['numero' => 2, 'nombre' => 'Ecuaciones ordinaria y general con vértice en (h, k)'],
                            ['numero' => 3, 'nombre' => 'Elementos de la parábola (foco, directriz, lado recto)']
                        ]
                    ],
                    [
                        'numero' => 5,
                        'nombre' => 'Circunferencia y Elipse',
                        'descripcion' => 'Ecuaciones ordinarias y generales.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'La circunferencia: ecuaciones ordinaria y general'],
                            ['numero' => 2, 'nombre' => 'La elipse: ecuaciones ordinaria y general, elementos']
                        ]
                    ]
                ]
            ],
            4 => [
                'nombre' => 'Matemáticas IV',
                'unidades' => [
                    [
                        'numero' => 1,
                        'nombre' => 'Funciones polinomiales',
                        'descripcion' => 'Dominio/rango, operaciones, raíces y factorización.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Dominio y rango de funciones polinomiales'],
                            ['numero' => 2, 'nombre' => 'Operaciones con funciones polinomiales'],
                            ['numero' => 3, 'nombre' => 'Raíces, teorema del residuo/factor y factorización']
                        ]
                    ],
                    [
                        'numero' => 2,
                        'nombre' => 'Racionales y radicales',
                        'descripcion' => 'Asíntotas, dominio y gráficas.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Funciones racionales: dominio, rango y asíntotas horizontales/verticales'],
                            ['numero' => 2, 'nombre' => 'Funciones con radicales: dominio y gráficas']
                        ]
                    ],
                    [
                        'numero' => 3,
                        'nombre' => 'Exponenciales y logarítmicas',
                        'descripcion' => 'Crecimiento, propiedades y ecuaciones aplicadas.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'Crecimiento y decrecimiento exponencial'],
                            ['numero' => 2, 'nombre' => 'Propiedades de los logaritmos'],
                            ['numero' => 3, 'nombre' => 'Resolución de ecuaciones exponenciales y logarítmicas']
                        ]
                    ],
                    [
                        'numero' => 4,
                        'nombre' => 'Funciones trigonométricas',
                        'descripcion' => 'Círculo unitario, radianes, gráficas y fenómenos periódicos.',
                        'temas' => [
                            ['numero' => 1, 'nombre' => 'El círculo unitario y medición en radianes'],
                            ['numero' => 2, 'nombre' => 'Gráficas de las funciones seno, coseno y tangente'],
                            ['numero' => 3, 'nombre' => 'Modelado de fenómenos periódicos']
                        ]
                    ]
                ]
            ]
        ];

        foreach ($semestresData as $numeroSemestre => $semestreInfo) {
            $semestre = Semestre::create([
                'materia_id' => $materia->id,
                'numero' => $numeroSemestre,
                'descripcion' => $semestreInfo['nombre']
            ]);

            foreach ($semestreInfo['unidades'] as $unidadInfo) {
                $unidad = Unidad::create([
                    'semestre_id' => $semestre->id,
                    'numero' => $unidadInfo['numero'],
                    'nombre' => $unidadInfo['nombre'],
                    'descripcion' => $unidadInfo['descripcion']
                ]);

                foreach ($unidadInfo['temas'] as $temaInfo) {
                    Tema::create([
                        'unidad_id' => $unidad->id,
                        'numero' => $temaInfo['numero'],
                        'nombre' => $temaInfo['nombre']
                    ]);
                }
            }
        }
    }
}
