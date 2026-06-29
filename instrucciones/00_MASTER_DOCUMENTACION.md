# MASTER_DOCUMENTACION.md

# Documento Maestro de Referencia

## Plataforma de Aprendizaje de Matemáticas CCH - UNAM

Este documento funciona como el punto de entrada para toda la
documentación del proyecto. Ningún documento debe considerarse aislado;
todos forman parte de una única especificación.

------------------------------------------------------------------------

# Orden de lectura recomendado

## 1. Plan Maestro

**Archivo:** `01_PLAN_MAESTRO_PLATAFORMA_MATEMATICAS_CCH.md`

Describe la visión general, objetivos, alcance y filosofía del proyecto.

------------------------------------------------------------------------

## 2. Arquitectura

**Archivo:** `02_ARQUITECTURA.md`

Define la arquitectura del sistema, módulos, tecnologías, organización
del backend, frontend y estructura general.

Depende de: - 01 Plan Maestro

------------------------------------------------------------------------

## 3. Base de Datos

**Archivo:** `03_BASE_DE_DATOS.md`

Contiene el diseño lógico de la base de datos, entidades, relaciones,
catálogos y estructura de almacenamiento.

Depende de: - 01 Plan Maestro - 02 Arquitectura

------------------------------------------------------------------------

## 4. Reglas de Negocio

**Archivo:** `04_REGLAS_DE_NEGOCIO.md`

Define todas las reglas funcionales de la plataforma.

Depende de: - 01 - 02 - 03

------------------------------------------------------------------------

## 5. UI / UX

**Archivo:** `05_UI_UX.md`

Describe el diseño visual, navegación, experiencia de usuario y
componentes.

Depende de: - 01 - 02 - 04

------------------------------------------------------------------------

## 6. Contenido Didáctico

**Archivo:** `06_CONTENIDO_DIDACTICO.md`

Especifica la estructura pedagógica de todas las unidades.

Depende de: - 01 - 04 - 05

------------------------------------------------------------------------

## 7. API

**Archivo:** `07_API.md`

Documentación de todos los endpoints.

Depende de: - 02 - 03 - 04

------------------------------------------------------------------------

## 8. Roadmap

**Archivo:** `08_ROADMAP.md`

Cronograma y fases de desarrollo.

Depende de todos los documentos anteriores.

------------------------------------------------------------------------

## 9. Guía de Desarrollo

**Archivo:** `09_GUIA_DESARROLLO.md`

Convenciones de programación, estructura del proyecto y estándares.

Depende de: - 02 - 03 - 07

------------------------------------------------------------------------

## 10. Especificación Funcional

**Archivo:** `10_ESPECIFICACION_FUNCIONAL.md`

Documento funcional que integra casos de uso, pantallas, procesos y
reglas.

Depende de TODOS los documentos anteriores.

------------------------------------------------------------------------

## 11. Despliegue y Producción

**Archivo:** `11_DESPLIEGUE_Y_PRODUCCION.md`

Guía técnica detallada sobre los requisitos, configuraciones de servidor, base de datos y optimización en producción.

Depende de: - 02 Arquitectura - 03 Base de Datos - 09 Guía de Desarrollo

------------------------------------------------------------------------

# Dependencias

01 ↓ 02 ↓ 03 ↓ 04 ├──05 ├──06 └──07 ↓ 08 09 10 11

------------------------------------------------------------------------

# Regla Principal

Toda modificación al proyecto deberá reflejarse primero en el documento
correspondiente antes de implementarse en código.

------------------------------------------------------------------------

# Convención de Versiones

-   v0.x → Planeación
-   v1.x → Desarrollo
-   v2.x → Producción
-   v3.x → Nuevas funcionalidades

------------------------------------------------------------------------

# Objetivo

Mantener una única fuente de verdad para el proyecto, garantizando
consistencia entre arquitectura, desarrollo, contenido didáctico y
documentación.

------------------------------------------------------------------------

# Temario de Matemáticas CCH

A continuación se detalla el temario oficial de matemáticas para la plataforma, organizado en 4 niveles correspondientes a cada semestre (del 1ro al 4to).

## Matemáticas I (Primer Semestre)

| Unidad | Título | Temas Clave |
| :--- | :--- | :--- |
| **U1** | Números y operaciones | Transición a enteros/reales, jerarquía de operaciones y lenguaje algebraico. |
| **U2** | Variación lineal | Proporcionalidad, tablas, gráficas y \(y = mx + b\). |
| **U3** | Ecuaciones lineales | Despejes, igualdad y modelado de problemas. |
| **U4** | Sistemas \(2 \times 2\) | Métodos (sustitución, reducción, gráfico) y resolución. |

## Matemáticas II (Segundo Semestre)

| Unidad | Título | Temas Clave |
| :--- | :--- | :--- |
| **U1** | Ecuaciones cuadráticas | Factorización, fórmula general y análisis del discriminante. |
| **U2** | Funciones cuadráticas | Parábolas, vértices, optimización básica. |
| **U3** | Geometría plana | Ángulos, triángulos, perímetros y áreas. |
| **U4** | Congruencia y semejanza | Criterios de triángulos, Teorema de Tales y Pitágoras. |

## Matemáticas III (Tercer Semestre)

| Unidad | Título | Temas Clave |
| :--- | :--- | :--- |
| **U1** | Trigonometría | Razones en triángulo rectángulo, Leyes de Senos/Cosenos. |
| **U2** | Geometría analítica | Plano cartesiano, distancia, pendiente. |
| **U3** | La recta | Ecuaciones (punto-pendiente, general), paralelismo/perpendicularidad. |
| **U4** | Parábola | Lugar geométrico, vértice \((h, k)\), ecuación general. |
| **U5** | Circunferencia y Elipse | Ecuaciones ordinarias y generales. |

## Matemáticas IV (Cuarto Semestre)

| Unidad | Título | Temas Clave |
| :--- | :--- | :--- |
| **U1** | Funciones polinomiales | Dominio/rango, operaciones, raíces y factorización. |
| **U2** | Racionales y radicales | Asíntotas, dominio y gráficas. |
| **U3** | Exponenciales y logarítmicas | Crecimiento, propiedades y ecuaciones aplicadas. |
| **U4** | Funciones trigonométricas | Círculo unitario, radianes, gráficas y fenómenos periódicos. |
