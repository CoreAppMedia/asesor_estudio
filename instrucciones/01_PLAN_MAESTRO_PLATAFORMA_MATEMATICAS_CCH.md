# Plan Maestro del Proyecto

## Plataforma de Aprendizaje para Matemáticas CCH - UNAM

**Versión:** 1.0\
**Estado:** Planeación Inicial

------------------------------------------------------------------------

# 1. Objetivo General

Desarrollar una plataforma web didáctica especializada en Matemáticas
del CCH (UNAM), cuyo propósito es apoyar las clases impartidas por el
profesor mediante contenido estructurado, ejercicios, cuestionarios,
evaluaciones y herramientas de seguimiento académico.

La plataforma **no sustituye al profesor**; organiza el aprendizaje,
registra el avance y facilita el control académico.

------------------------------------------------------------------------

# 2. Filosofía del Proyecto

-   El profesor es el eje del proceso de enseñanza.
-   Todo el contenido será validado manualmente antes de publicarse.
-   No se utilizarán agentes de IA para enseñar.
-   El aprendizaje será progresivo.
-   El contenido será público.
-   Las evaluaciones serán controladas por el profesor.

------------------------------------------------------------------------

# 3. Público Objetivo

## Público general

Cualquier persona podrá:

-   Consultar teoría.
-   Revisar ejemplos.
-   Resolver ejercicios de práctica.
-   Descargar material.

No requerirá crear una cuenta.

## Profesor (Administrador)

Existirá un único administrador con control total sobre:

-   Contenido
-   Evaluaciones
-   Grupos
-   Estadísticas
-   Alumnos
-   Configuración

------------------------------------------------------------------------

# 4. Arquitectura General

## Fase 1

-   Matemáticas I
-   Matemáticas II
-   Matemáticas III
-   Matemáticas IV

Diseñada para escalar posteriormente a otras materias.

------------------------------------------------------------------------

# 5. Organización Académica

Materia → Nivel (Semestre) → Unidad → Tema → Subtema → Lección →
Recursos → Ejemplos → Ejercicios → Cuestionarios → Evaluaciones

------------------------------------------------------------------------

# 6. Estructura de Cada Unidad

Cada unidad contendrá:

1.  Introducción
2.  Objetivos
3.  Conocimientos previos
4.  Explicación teórica
5.  Conceptos clave
6.  Ejemplos resueltos
7.  Aplicaciones reales
8.  Errores comunes
9.  Ejercicios guiados
10. Ejercicios independientes
11. Resumen
12. Formulario
13. Mapa conceptual
14. Recursos multimedia (opcional)
15. Material descargable (opcional)
16. Cuestionario
17. Ejercicios adicionales
18. Evaluación
19. Bibliografía

------------------------------------------------------------------------

# 7. Recursos Multimedia

Cada contenido podrá incluir opcionalmente:

-   Texto
-   Fórmulas matemáticas
-   Imágenes
-   Diagramas
-   Gráficas
-   Animaciones
-   Videos
-   Archivos PDF

------------------------------------------------------------------------

# 8. Contenido

Inicialmente se almacenará en archivos JSON estructurados.

Ventajas:

-   Fácil revisión.
-   Control de versiones.
-   Validación antes de publicarse.
-   Posteriormente podrá migrarse a un editor visual.

------------------------------------------------------------------------

# 9. Cuestionarios

-   Banco de preguntas por unidad.
-   Selección aleatoria.
-   Orden aleatorio.
-   Máximo 3 intentos por día.
-   Mensaje motivacional al alcanzar el límite diario.
-   Historial de intentos.

------------------------------------------------------------------------

# 10. Evaluaciones

Configurables por el profesor:

-   Sin límite de tiempo.
-   Con límite de tiempo.
-   Fecha de apertura.
-   Fecha de cierre.
-   Calificación automática preliminar.
-   Revisión manual del profesor antes de la calificación definitiva.

------------------------------------------------------------------------

# 11. Gestión de Alumnos

No existirán cuentas de alumno.

Modelo basado en:

Generación → Grupo → Lista → Alumno

El profesor cargará la lista de alumnos.

------------------------------------------------------------------------

# 12. Acceso a Evaluaciones

El profesor crea una sesión.

El sistema genera:

-   Código corto
-   Enlace
-   Código QR

El alumno:

1.  Escanea el QR o introduce el código.
2.  Se identifica (grupo y número de lista o datos iniciales).
3.  Responde la evaluación.
4.  Envía sus respuestas.

Sin usuarios ni contraseñas.

------------------------------------------------------------------------

# 13. Panel Administrativo

## Gestión Académica

-   Materias
-   Semestres
-   Unidades
-   Temas
-   Banco de preguntas
-   Recursos

## Gestión Escolar

-   Generaciones
-   Grupos
-   Listas
-   Sesiones

## Reportes

-   Calificaciones
-   Promedios
-   Tiempo por actividad
-   Preguntas más falladas
-   Progreso individual
-   Progreso grupal

------------------------------------------------------------------------

# 14. Dashboard

Indicadores:

-   Alumnos conectados
-   Evaluaciones activas
-   Promedio general
-   Temas con mayor dificultad
-   Avance por grupo
-   Historial académico

------------------------------------------------------------------------

# 15. Tecnologías

Backend: - Laravel 12 - API REST - MariaDB

Frontend: - React - Vite - Tailwind CSS

Contenido: - JSON

Gráficas: - Chart.js

------------------------------------------------------------------------

# 16. Roadmap

## Fase 1

-   Documento funcional.
-   Arquitectura.
-   Base de datos.

## Fase 2

-   Backend.
-   API.
-   Autenticación del administrador.

## Fase 3

-   Panel administrativo.

## Fase 4

-   Sistema de contenido.

## Fase 5

-   Banco de preguntas.

## Fase 6

-   Sistema de sesiones con QR.

## Fase 7

-   Dashboard.

## Fase 8

-   Estadísticas.

## Fase 9

-   Optimización.

## Fase 10

-   Publicación.

------------------------------------------------------------------------

# 17. Objetivo Final

Crear una plataforma educativa de alta calidad para Matemáticas del CCH
que permita:

-   Acceso libre al conocimiento.
-   Aprendizaje estructurado.
-   Evaluaciones controladas.
-   Seguimiento detallado del avance.
-   Administración sencilla sin cuentas de alumno.
-   Escalabilidad hacia otras materias y niveles educativos.
