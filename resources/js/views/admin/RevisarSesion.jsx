import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import AdminLayout from '../../components/AdminLayout';
import { ArrowLeft, Users, CheckCircle2, XCircle, AlertCircle, RefreshCw, MessageSquare, Download, Clipboard, BarChart3, HelpCircle, Award, Hourglass, Clock, PenLine, Lock } from 'lucide-react';
import MathView from '../../components/MathView';

export default function RevisarSesion() {
    const { sesionId } = useParams();
    const token = localStorage.getItem('admin_token');

    // Navigation Tabs
    const [activeTab, setActiveTab] = useState('calificaciones'); // 'calificaciones' | 'estadisticas'

    // States
    const [sesion, setSesion] = useState(null);
    const [alumnosIntentos, setAlumnosIntentos] = useState([]);
    const [stats, setStats] = useState(null);
    const [loading, setLoading] = useState(true);
    const [loadingStats, setLoadingStats] = useState(false);
    const [errorMsg, setErrorMsg] = useState(null);

    // Active review states
    const [selectedIntento, setSelectedIntento] = useState(null);
    const [loadingDetalle, setLoadingDetalle] = useState(false);
    const [reviewRespuestas, setReviewRespuestas] = useState({}); // { resultado_id: { es_correcta: true, puntaje: 1.00, feedback_profesor: '' } }
    const [savingGrading, setSavingGrading] = useState(false);

    // Load list of attempts and statistics
    const loadSessionData = async () => {
        setLoading(true);
        setErrorMsg(null);
        try {
            // Cargar info de la sesión desde el catálogo
            const sesRes = await fetch(`/api/sesiones`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (sesRes.ok) {
                const list = await sesRes.json();
                const currentSes = list.find(s => s.id.toString() === sesionId);
                if (currentSes) setSesion(currentSes);
            }

            // Cargar intentos
            const attemptsRes = await fetch(`/api/sesiones/${sesionId}/intentos`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (attemptsRes.ok) {
                const data = await attemptsRes.json();
                setAlumnosIntentos(data);
            } else {
                setErrorMsg('Error al cargar la lista de alumnos e intentos.');
            }
        } catch (e) {
            console.error(e);
            setErrorMsg('Error al conectar con el servidor.');
        } finally {
            setLoading(false);
        }
    };

    // Load statistics separately when changing tabs or refreshing
    const loadStatisticsData = async () => {
        setLoadingStats(true);
        try {
            const res = await fetch(`/api/sesiones/${sesionId}/estadisticas`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (res.ok) {
                const data = await res.json();
                setStats(data);
            }
        } catch (e) {
            console.error('Error al cargar estadísticas', e);
        } finally {
            setLoadingStats(false);
        }
    };

    useEffect(() => {
        loadSessionData();
    }, [sesionId]);

    useEffect(() => {
        if (activeTab === 'estadisticas') {
            loadStatisticsData();
        }
    }, [activeTab]);

    // Load attempt details for manual grading
    const loadIntentoDetalle = async (intentoId) => {
        setLoadingDetalle(true);
        setErrorMsg(null);
        try {
            const res = await fetch(`/api/sesiones/${sesionId}/intentos/${intentoId}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.ok) {
                const data = await res.json();
                setSelectedIntento(data);

                // Inicializar estados de calificación
                // Para preguntas OM: bloqueadas (calificación automática), solo feedback editable
                // Para preguntas RA: editable por el profesor
                const initGrading = {};
                data.resultados.forEach(resItem => {
                    const esOM = resItem.pregunta?.tipo_pregunta === 'opcion_multiple';
                    initGrading[resItem.id] = {
                        id: resItem.id,
                        tipo: resItem.pregunta?.tipo_pregunta || 'opcion_multiple',
                        bloqueada: esOM, // Las OM están bloqueadas
                        es_correcta: resItem.es_correcta !== null ? resItem.es_correcta : null,
                        puntaje: resItem.puntaje !== null ? parseFloat(resItem.puntaje) : null,
                        feedback_profesor: resItem.feedback_profesor || ''
                    };
                });
                setReviewRespuestas(initGrading);
            } else {
                setErrorMsg('No se pudo cargar el detalle del examen del alumno.');
            }
        } catch (e) {
            console.error(e);
            setErrorMsg('Error al cargar detalle.');
        } finally {
            setLoadingDetalle(false);
        }
    };

    const handleGradingChange = (resultadoId, field, value) => {
        setReviewRespuestas(prev => {
            const current = { ...prev[resultadoId] };
            if (field === 'es_correcta') {
                current.es_correcta = value;
                current.puntaje = value ? 1.00 : 0.00;
            } else if (field === 'puntaje') {
                current.puntaje = parseFloat(value) || 0;
            } else {
                current[field] = value;
            }
            return { ...prev, [resultadoId]: current };
        });
    };

    // Save grades
    const handleSaveCalificaciones = async (e) => {
        e.preventDefault();

        // Verificar que todas las preguntas abiertas hayan sido evaluadas
        const abiertas = Object.values(reviewRespuestas).filter(r => r.tipo === 'respuesta_abierta');
        const sinCalificar = abiertas.filter(r => r.puntaje === null || r.puntaje === '');
        if (sinCalificar.length > 0) {
            if (!window.confirm(`Aún tienes ${sinCalificar.length} pregunta(s) abierta(s) sin calificar. ¿Deseas guardar de todas formas con puntaje 0?`)) {
                return;
            }
            // Asignar 0 a las no evaluadas
            sinCalificar.forEach(r => {
                setReviewRespuestas(prev => ({
                    ...prev,
                    [r.id]: { ...prev[r.id], es_correcta: false, puntaje: 0 }
                }));
            });
        }

        setSavingGrading(true);
        setErrorMsg(null);

        // Construir payload: para OM solo feedback, para RA la calificación completa
        const payload = {
            resultados: Object.values(reviewRespuestas).map(r => ({
                id: r.id,
                es_correcta: r.bloqueada ? (r.es_correcta ?? false) : (r.es_correcta ?? false),
                puntaje: r.bloqueada ? (r.puntaje ?? 0) : (r.puntaje ?? 0),
                feedback_profesor: r.feedback_profesor || null
            }))
        };

        try {
            const res = await fetch(`/api/intentos/${selectedIntento.id}/calificar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                setSelectedIntento(null);
                loadSessionData();
            } else {
                const data = await res.json();
                setErrorMsg(data.message || 'Error al guardar la calificación.');
            }
        } catch (err) {
            console.error(err);
            setErrorMsg('Error de red al calificar.');
        } finally {
            setSavingGrading(false);
        }
    };

    // Download CSV list of grades
    const handleExportCSV = async () => {
        try {
            const res = await fetch(`/api/sesiones/${sesionId}/exportar`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (res.ok) {
                const blob = await res.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `calificaciones_grupo_${sesion?.grupo?.nombre || 'grupo'}_sesion_${sesionId}.csv`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            } else {
                alert('No se pudo generar la exportación de calificaciones.');
            }
        } catch (e) {
            console.error(e);
            alert('Error de red al exportar CSV.');
        }
    };

    // SVG Bar Chart Variables calculation
    const getSvgChartBars = () => {
        if (!stats || !stats.distribucion) return [];
        const dist = stats.distribucion;
        const maxVal = Math.max(dist.excelente, dist.bueno, dist.regular, dist.suficiente, dist.insuficiente, 1);
        
        return [
            { label: 'Ex. [9-10]', value: dist.excelente, color: '#dceeb1' },
            { label: 'B. [8-9)', value: dist.bueno, color: '#f4ecd6' },
            { label: 'R. [7-8)', value: dist.regular, color: '#c5b0f4' },
            { label: 'S. [6-7)', value: dist.suficiente, color: '#93c5fd' },
            { label: 'I. <6', value: dist.insuficiente, color: '#fb7185' }
        ].map((item, idx) => {
            const barHeight = (item.value / maxVal) * 160;
            return {
                ...item,
                x: 60 + idx * 80,
                y: 200 - barHeight,
                height: barHeight,
                width: 45
            };
        });
    };

    return (
        <AdminLayout>
            <div className="space-y-8 py-4 animate-fade-in">
                {/* Back button & title */}
                <div className="space-y-2">
                    <Link
                        to="/admin/sesiones"
                        className="inline-flex items-center gap-1 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-black transition-colors underline"
                    >
                        <ArrowLeft className="w-3.5 h-3.5" /> Volver a Sesiones
                    </Link>
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 className="text-4xl font-black tracking-tight text-black">
                                {sesion ? sesion.evaluacion?.nombre : 'Revisión de Examen'}
                            </h2>
                            <p className="text-gray-600 font-medium">
                                Grupo: <strong className="text-black">{sesion?.grupo?.nombre}</strong> | Unidad: {sesion?.evaluacion?.unidad?.nombre}
                            </p>
                        </div>
                        <div className="flex gap-2">
                            <button
                                onClick={activeTab === 'calificaciones' ? loadSessionData : loadStatisticsData}
                                className="flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] transition-all text-sm"
                            >
                                <RefreshCw className="w-4 h-4" /> Actualizar Datos
                            </button>
                        </div>
                    </div>
                </div>

                {/* Tab Switcher */}
                <div className="flex border-b-4 border-black">
                    <button
                        onClick={() => {
                            setActiveTab('calificaciones');
                            setSelectedIntento(null);
                        }}
                        className={`px-6 py-3 font-black text-sm uppercase tracking-wider border-t-2 border-x-2 border-black rounded-t-xl transition-all -mb-[4px] ${
                            activeTab === 'calificaciones'
                                ? 'bg-white border-b-transparent text-black z-10'
                                : 'bg-gray-100 border-b-black text-gray-500 hover:text-black'
                        }`}
                    >
                        Calificaciones
                    </button>
                    <button
                        onClick={() => setActiveTab('estadisticas')}
                        className={`px-6 py-3 font-black text-sm uppercase tracking-wider border-t-2 border-x-2 border-black rounded-t-xl transition-all -mb-[4px] ${
                            activeTab === 'estadisticas'
                                ? 'bg-white border-b-transparent text-black z-10'
                                : 'bg-gray-100 border-b-black text-gray-500 hover:text-black'
                        }`}
                    >
                        Estadísticas de Grupo
                    </button>
                </div>

                {errorMsg && (
                    <div className="bg-rose-50 border-2 border-rose-500 text-rose-700 p-4 rounded-2xl font-bold text-sm flex items-center gap-3">
                        <AlertCircle className="w-5 h-5 shrink-0" />
                        <span>{errorMsg}</span>
                    </div>
                )}

                {/* Tab 1: List and grading of student exams */}
                {activeTab === 'calificaciones' && (
                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        {/* Left: Students Table */}
                        <div className="lg:col-span-5 bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                            <div className="bg-[#f7f7f5] p-4 border-b-2 border-black flex items-center justify-between">
                                <span className="font-black uppercase tracking-wider text-xs flex items-center gap-2">
                                    <Users className="w-4 h-4" /> Alumnos e Intentos
                                </span>
                            </div>

                            {loading ? (
                                <div className="p-8 text-center font-bold text-gray-500">Cargando lista...</div>
                            ) : alumnosIntentos.length === 0 ? (
                                <div className="p-8 text-center text-gray-500 font-bold">No hay alumnos registrados en este grupo.</div>
                            ) : (
                                <div className="divide-y-2 divide-black max-h-[600px] overflow-y-auto">
                                    {alumnosIntentos.map((item) => {
                                        const hasIntento = !!item.intento;
                                        const isSelected = selectedIntento && selectedIntento.id === item.intento?.id;
                                        const necesitaRevision = hasIntento &&
                                            item.intento.estado === 'Finalizado' &&
                                            item.intento.tiene_abiertas_pendientes;

                                        return (
                                            <div
                                                key={item.alumno_id}
                                                className={`p-4 flex items-center justify-between gap-4 transition-colors ${
                                                    isSelected ? 'bg-yellow-50' : 'bg-white'
                                                }`}
                                            >
                                                <div className="space-y-1">
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-xs font-black text-gray-400">[{item.numero_lista}]</span>
                                                        <span className="font-black text-sm text-black">{item.nombre}</span>
                                                    </div>

                                                    {/* Attempt info badges */}
                                                    <div className="flex items-center gap-2 flex-wrap">
                                                        {!hasIntento ? (
                                                            <span className="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                                Sin iniciar
                                                            </span>
                                                        ) : item.intento.estado === 'En curso' ? (
                                                            <span className="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 animate-pulse">
                                                                En curso
                                                            </span>
                                                        ) : (
                                                            <div className="flex items-center gap-1.5 flex-wrap">
                                                                <span className="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                                    Entregado
                                                                </span>
                                                                {necesitaRevision ? (
                                                                    <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                                        ⚠️ Revisión requerida ({item.intento.abiertas_pendientes})
                                                                    </span>
                                                                ) : (
                                                                    <span className={`inline-block px-2 py-0.5 rounded text-[10px] font-bold border ${
                                                                        item.intento.calificado
                                                                            ? 'bg-[#dceeb1] text-black border-black/20'
                                                                            : 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                                                    }`}>
                                                                        {item.intento.calificado ? `Nota: ${item.intento.score}` : 'Por calificar'}
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>

                                                {hasIntento && item.intento.estado === 'Finalizado' && (
                                                    <button
                                                        onClick={() => loadIntentoDetalle(item.intento.id)}
                                                        className={`px-3 py-1.5 border-2 border-black rounded-lg font-bold text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 transition-all shrink-0 ${
                                                            necesitaRevision
                                                                ? 'bg-amber-300 hover:bg-amber-400'
                                                                : 'bg-[#c5b0f4] hover:bg-purple-200'
                                                        }`}
                                                    >
                                                        {necesitaRevision ? '⚠️ Revisar' : 'Ver'}
                                                    </button>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                        </div>

                        {/* Right: Detailed grading sheet */}
                        <div className="lg:col-span-7">
                            {loadingDetalle ? (
                                <div className="bg-white border-2 border-black rounded-2xl p-12 text-center font-bold text-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                    <RefreshCw className="w-8 h-8 animate-spin mx-auto text-purple-600 mb-4" />
                                    Cargando respuestas del alumno...
                                </div>
                            ) : !selectedIntento ? (
                                <div className="bg-[#fcfcfa] border-2 border-dashed border-black/30 rounded-2xl p-12 text-center text-gray-500 font-bold">
                                    Selecciona un alumno con entrega en la lista de la izquierda para revisar y calificar sus respuestas.
                                </div>
                            ) : (
                                <form
                                    onSubmit={handleSaveCalificaciones}
                                    className="bg-white border-4 border-black rounded-3xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] overflow-hidden space-y-6 p-6 animate-slide-up"
                                >
                                    {/* Grading Sheet Header */}
                                    <div className="border-b-2 border-black pb-4 flex items-center justify-between">
                                        <div>
                                            <h3 className="text-xl font-black text-black">
                                                Revisión: {selectedIntento.alumno?.nombre} {selectedIntento.alumno?.apellido_paterno}
                                            </h3>
                                            <p className="text-xs text-gray-500 font-semibold mt-1">
                                                Entregado el: {new Date(selectedIntento.finalizado_at).toLocaleString()}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() => setSelectedIntento(null)}
                                            className="px-3 py-1 border-2 border-black rounded-lg bg-gray-50 hover:bg-gray-100 font-bold text-xs"
                                        >
                                            Cerrar vista
                                        </button>
                                    </div>

                                    {/* Banner: preguntas abiertas pendientes */}
                                    {(() => {
                                        const pendientes = Object.values(reviewRespuestas).filter(
                                            r => r.tipo === 'respuesta_abierta' && r.puntaje === null
                                        ).length;
                                        return pendientes > 0 ? (
                                            <div className="flex items-center gap-3 bg-amber-50 border-2 border-amber-400 rounded-2xl px-5 py-3">
                                                <span className="text-2xl">⚠️</span>
                                                <div>
                                                    <p className="font-black text-amber-900 text-sm">
                                                        {pendientes} pregunta{pendientes > 1 ? 's' : ''} abierta{pendientes > 1 ? 's' : ''} pendiente{pendientes > 1 ? 's' : ''} de revisión
                                                    </p>
                                                    <p className="text-xs font-semibold text-amber-700">
                                                        Califica todas las preguntas de respuesta abierta antes de consolidar la nota.
                                                    </p>
                                                </div>
                                            </div>
                                        ) : null;
                                    })()}

                                    {/* Answers breakdown */}
                                    <div className="space-y-6 max-h-[520px] overflow-y-auto pr-2">
                                        {selectedIntento.resultados.map((resItem, idx) => {
                                            const pre = resItem.pregunta;
                                            const grad = reviewRespuestas[resItem.id] || {};
                                            const esOM = pre?.tipo_pregunta === 'opcion_multiple';
                                            const esAbierta = pre?.tipo_pregunta === 'respuesta_abierta';
                                            const pendiente = esAbierta && grad.puntaje === null;

                                            return (
                                                <div
                                                    key={resItem.id}
                                                    className={`space-y-3 border-2 rounded-2xl p-5 ${
                                                        esAbierta && pendiente
                                                            ? 'border-amber-300 bg-amber-50/40'
                                                            : esOM
                                                                ? 'border-gray-200 bg-gray-50/50'
                                                                : 'border-emerald-200 bg-emerald-50/30'
                                                    }`}
                                                >
                                                    {/* Question header */}
                                                    <div className="flex items-center justify-between gap-2">
                                                        <span className={`font-black text-xs uppercase tracking-wider px-2.5 py-1 rounded-full border ${
                                                            esOM
                                                                ? 'text-gray-600 bg-gray-100 border-gray-300'
                                                                : 'text-amber-800 bg-amber-100 border-amber-300'
                                                        }`}>
                                                            {esOM ? '🔒 Opción Múltiple (Auto)' : '✏️ Respuesta Abierta (Revisión manual)'}
                                                        </span>
                                                        <span className="text-xs font-bold text-gray-400">Pregunta {idx + 1}</span>
                                                    </div>

                                                    {/* Question Text */}
                                                    <div className="bg-white border-2 border-black/10 p-4 rounded-xl text-sm font-semibold">
                                                        <MathView text={pre?.texto_pregunta} />
                                                    </div>

                                                    {/* Student Answer */}
                                                    <div className="p-3 bg-white border border-black/10 rounded-xl space-y-1">
                                                        <span className="text-[10px] font-black uppercase text-gray-400">Respuesta del Alumno:</span>
                                                        <div className="font-bold text-sm text-black">
                                                            {esOM ? (
                                                                <div className="flex items-center gap-3">
                                                                    <span className="w-6 h-6 bg-black text-white text-xs font-black rounded flex items-center justify-center">
                                                                        {resItem.respuesta_alumno || '—'}
                                                                    </span>
                                                                    {resItem.es_correcta ? (
                                                                        <span className="text-emerald-700 font-black text-xs flex items-center gap-1">
                                                                            <CheckCircle2 className="w-4 h-4" /> Correcta
                                                                        </span>
                                                                    ) : (
                                                                        <span className="text-rose-700 font-black text-xs flex items-center gap-1">
                                                                            <XCircle className="w-4 h-4" /> Incorrecta — Esperada: <strong>{pre?.respuesta_correcta}</strong>
                                                                        </span>
                                                                    )}
                                                                </div>
                                                            ) : (
                                                                <p className="whitespace-pre-wrap leading-relaxed text-sm">
                                                                    {resItem.respuesta_alumno
                                                                        ? resItem.respuesta_alumno
                                                                        : <span className="italic text-gray-400">No respondió</span>
                                                                    }
                                                                </p>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Para preguntas abiertas: mostrar respuesta esperada */}
                                                    {esAbierta && pre?.respuesta_correcta && (
                                                        <div className="p-3 bg-emerald-50 border-2 border-emerald-300 rounded-xl space-y-1">
                                                            <span className="text-[10px] font-black uppercase text-emerald-700">Respuesta esperada / referencia:</span>
                                                            <p className="font-semibold text-sm text-emerald-900 whitespace-pre-wrap leading-relaxed">
                                                                {pre.respuesta_correcta}
                                                            </p>
                                                        </div>
                                                    )}

                                                    {/* Grading Controller */}
                                                    {esOM ? (
                                                        // Preguntas OM: solo feedback, calificación bloqueada
                                                        <div className="bg-gray-100 border-2 border-gray-200 p-4 rounded-2xl space-y-3">
                                                            <div className="flex items-center gap-2 text-xs font-black text-gray-500 uppercase tracking-wider">
                                                                <span>🔒</span> Calificación automática — {resItem.puntaje !== null ? `${resItem.puntaje === '1.00' || resItem.puntaje == 1 ? '1.00' : '0.00'} pts` : 'Sin puntaje'}
                                                            </div>
                                                            <div className="space-y-1">
                                                                <label className="text-[10px] font-black uppercase text-gray-500 flex items-center gap-1">
                                                                    <MessageSquare className="w-3.5 h-3.5" /> Retroalimentación del Docente (opcional)
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    value={grad.feedback_profesor || ''}
                                                                    onChange={(e) => handleGradingChange(resItem.id, 'feedback_profesor', e.target.value)}
                                                                    placeholder="Ej: Revisa la propiedad conmutativa..."
                                                                    className="w-full border-2 border-gray-300 rounded-lg p-2 text-xs font-bold focus:outline-none focus:border-black"
                                                                />
                                                            </div>
                                                        </div>
                                                    ) : (
                                                        // Preguntas abiertas: calificación manual
                                                        <div className={`border-2 p-4 rounded-2xl space-y-4 ${
                                                            pendiente
                                                                ? 'bg-amber-50 border-amber-400'
                                                                : 'bg-emerald-50 border-emerald-300'
                                                        }`}>
                                                            <p className={`text-xs font-black uppercase tracking-wider ${
                                                                pendiente ? 'text-amber-700' : 'text-emerald-700'
                                                            }`}>
                                                                {pendiente ? '⚠️ Pendiente de calificar' : '✅ Calificada'}
                                                            </p>

                                                            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                                <div className="flex gap-2">
                                                                    <button
                                                                        type="button"
                                                                        onClick={() => handleGradingChange(resItem.id, 'es_correcta', true)}
                                                                        className={`px-3 py-1.5 border-2 rounded-lg font-bold text-xs flex items-center gap-1.5 transition-all ${
                                                                            grad.es_correcta === true
                                                                                ? 'bg-[#dceeb1] border-black text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]'
                                                                                : 'bg-white border-gray-300 text-gray-400 hover:border-black'
                                                                        }`}
                                                                    >
                                                                        <CheckCircle2 className="w-4 h-4" /> Correcta / Similar
                                                                    </button>
                                                                    <button
                                                                        type="button"
                                                                        onClick={() => handleGradingChange(resItem.id, 'es_correcta', false)}
                                                                        className={`px-3 py-1.5 border-2 rounded-lg font-bold text-xs flex items-center gap-1.5 transition-all ${
                                                                            grad.es_correcta === false && grad.puntaje !== null
                                                                                ? 'bg-[#fb7185] border-black text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]'
                                                                                : 'bg-white border-gray-300 text-gray-400 hover:border-black'
                                                                        }`}
                                                                    >
                                                                        <XCircle className="w-4 h-4" /> Incorrecta
                                                                    </button>
                                                                </div>

                                                                <div className="flex items-center gap-2">
                                                                    <span className="text-xs font-black uppercase text-gray-600">Puntaje parcial (0 – 1):</span>
                                                                    <input
                                                                        type="number"
                                                                        step="0.1"
                                                                        min="0"
                                                                        max="1"
                                                                        value={grad.puntaje !== null && grad.puntaje !== undefined ? grad.puntaje : ''}
                                                                        onChange={(e) => handleGradingChange(resItem.id, 'puntaje', e.target.value)}
                                                                        placeholder="0.0"
                                                                        className="w-16 border-2 border-black rounded-lg p-1.5 text-center font-black text-sm"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div className="space-y-1">
                                                                <label className="text-[10px] font-black uppercase text-gray-500 flex items-center gap-1">
                                                                    <MessageSquare className="w-3.5 h-3.5" /> Retroalimentación del Docente
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    value={grad.feedback_profesor || ''}
                                                                    onChange={(e) => handleGradingChange(resItem.id, 'feedback_profesor', e.target.value)}
                                                                    placeholder="Ej. Respuesta similar a la esperada, buen razonamiento."
                                                                    className="w-full border-2 border-black rounded-lg p-2 text-xs font-bold focus:outline-none focus:border-black"
                                                                />
                                                            </div>
                                                        </div>
                                                    )}
                                                </div>
                                            );
                                        })}
                                    </div>

                                    {/* Save Button */}
                                    <div className="border-t-2 border-black pt-4 flex justify-end gap-3">
                                        <button
                                            type="button"
                                            onClick={() => setSelectedIntento(null)}
                                            className="px-5 py-2.5 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 active:translate-y-0.5 transition-all text-sm"
                                        >
                                            Cancelar
                                        </button>
                                        <button
                                            type="submit"
                                            disabled={savingGrading}
                                            className="px-5 py-2.5 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm disabled:opacity-50"
                                        >
                                            {savingGrading ? 'Guardando...' : 'Consolidar Calificación'}
                                        </button>
                                    </div>
                                </form>
                            )}
                        </div>
                    </div>
                )}

                {/* Tab 2: Group statistics and SVG reports */}
                {activeTab === 'estadisticas' && (
                    <div className="space-y-8 animate-fade-in">
                        {loadingStats ? (
                            <div className="bg-white border-2 border-black rounded-2xl p-12 text-center font-bold text-gray-500 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                <RefreshCw className="w-8 h-8 animate-spin mx-auto text-purple-600 mb-4" />
                                Cargando analíticas de grupo...
                            </div>
                        ) : !stats ? (
                            <div className="bg-white border-2 border-black rounded-2xl p-12 text-center text-gray-500 font-bold">
                                No hay datos analíticos para esta sesión.
                            </div>
                        ) : (
                            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                                {/* Left Side: Summary metrics & Custom SVG Chart */}
                                <div className="lg:col-span-6 space-y-6">
                                    {/* Action Box: CSV Download */}
                                    <div className="bg-[#c5b0f4] border-2 border-black rounded-2xl p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-between gap-4">
                                        <div>
                                            <h4 className="text-xl font-black">Acta de Calificaciones</h4>
                                            <p className="text-xs font-semibold text-gray-700 mt-1">Exporta la planilla oficial compatible con las hojas de cálculo escolar.</p>
                                        </div>
                                        <button
                                            onClick={handleExportCSV}
                                            className="flex items-center gap-2 px-4 py-2.5 border-2 border-black rounded-xl font-black bg-white hover:bg-gray-50 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] transition-all text-xs shrink-0"
                                        >
                                            <Download className="w-4 h-4" />
                                            Descargar CSV
                                        </button>
                                    </div>

                                    {/* Metric Card blocks */}
                                    <div className="grid grid-cols-3 gap-4">
                                        <div className="bg-white border-2 border-black rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-center">
                                            <span className="text-[10px] font-black uppercase text-gray-400 block mb-1">Promedio</span>
                                            <span className="text-3xl font-black text-black">{stats.promedio_grupal}</span>
                                            <span className="text-[9px] font-bold text-gray-500 block mt-1">escala de 10</span>
                                        </div>
                                        <div className="bg-white border-2 border-black rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-center">
                                            <span className="text-[10px] font-black uppercase text-gray-400 block mb-1">Entregados</span>
                                            <span className="text-3xl font-black text-emerald-600">{stats.resumen_intentos.finalizado}</span>
                                            <span className="text-[9px] font-bold text-gray-500 block mt-1">de {stats.resumen_intentos.total_alumnos} alumnos</span>
                                        </div>
                                        <div className="bg-white border-2 border-black rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-center">
                                            <span className="text-[10px] font-black uppercase text-gray-400 block mb-1">En curso</span>
                                            <span className="text-3xl font-black text-sky-600">{stats.resumen_intentos.en_curso}</span>
                                            <span className="text-[9px] font-bold text-gray-500 block mt-1">activos en el aula</span>
                                        </div>
                                    </div>

                                    {/* Score distribution SVG Chart */}
                                    <div className="bg-white border-2 border-black rounded-2xl p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] space-y-4">
                                        <h4 className="font-black text-sm uppercase tracking-widest text-gray-400 flex items-center gap-1">
                                            <BarChart3 className="w-4 h-4" /> Distribución de Calificaciones
                                        </h4>

                                        {/* Neumorphic flat SVG bar Chart */}
                                        <div className="w-full flex justify-center">
                                            <svg width="450" height="250" className="border border-black/10 rounded-xl overflow-visible">
                                                {/* Grid lines */}
                                                <line x1="50" y1="40" x2="420" y2="40" stroke="#f3f4f6" strokeWidth="1" />
                                                <line x1="50" y1="120" x2="420" y2="120" stroke="#f3f4f6" strokeWidth="1" />
                                                <line x1="50" y1="200" x2="420" y2="200" stroke="black" strokeWidth="2" />
                                                
                                                {/* Left axis line */}
                                                <line x1="50" y1="30" x2="50" y2="200" stroke="black" strokeWidth="2" />

                                                {/* Render Bars */}
                                                {getSvgChartBars().map((bar, idx) => (
                                                    <g key={idx} className="group">
                                                        {/* Bar Rectangle */}
                                                        {bar.height > 0 && (
                                                            <rect
                                                                x={bar.x}
                                                                y={bar.y}
                                                                width={bar.width}
                                                                height={bar.height}
                                                                fill={bar.color}
                                                                stroke="black"
                                                                strokeWidth="2"
                                                                rx="4"
                                                                className="transition-all hover:opacity-90 cursor-pointer"
                                                            />
                                                        )}
                                                        {/* Value text above bar */}
                                                        <text
                                                            x={bar.x + bar.width / 2}
                                                            y={bar.y - 8}
                                                            textAnchor="middle"
                                                            className="text-xs font-black fill-black"
                                                        >
                                                            {bar.value}
                                                        </text>
                                                        {/* X Label beneath axis */}
                                                        <text
                                                            x={bar.x + bar.width / 2}
                                                            y="222"
                                                            textAnchor="middle"
                                                            className="text-[10px] font-black fill-gray-500"
                                                        >
                                                            {bar.label.split(' ')[0]}
                                                        </text>
                                                        <text
                                                            x={bar.x + bar.width / 2}
                                                            y="235"
                                                            textAnchor="middle"
                                                            className="text-[9px] font-bold fill-gray-400"
                                                        >
                                                            {bar.label.split(' ')[1]}
                                                        </text>
                                                    </g>
                                                ))}
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {/* Right Side: Error Rates list per Question */}
                                <div className="lg:col-span-6 bg-white border-2 border-black rounded-2xl p-6 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] space-y-6">
                                    <div>
                                        <h4 className="font-black text-sm uppercase tracking-widest text-gray-400 flex items-center gap-1.5 mb-1">
                                            <HelpCircle className="w-4 h-4" /> Diagnóstico de Errores por Pregunta
                                        </h4>
                                        <p className="text-xs font-bold text-gray-500">Reactivos del examen ordenados de mayor a menor tasa de fallo grupal.</p>
                                    </div>

                                    <div className="space-y-4 max-h-[500px] overflow-y-auto pr-2 divide-y divide-gray-100">
                                        {stats.preguntas_estadisticas.map((item, idx) => (
                                            <div key={item.pregunta_id} className="pt-4 first:pt-0 space-y-3">
                                                {/* Question index and rates */}
                                                <div className="flex items-center justify-between text-xs">
                                                    <span className="font-black text-purple-700 uppercase">Reactivo {idx + 1}</span>
                                                    <span className={`px-2.5 py-0.5 rounded-full border font-black ${
                                                        item.tasa_error > 50
                                                            ? 'bg-rose-100 text-rose-700 border-rose-300'
                                                            : item.tasa_error > 25
                                                                ? 'bg-amber-100 text-amber-700 border-amber-300'
                                                                : 'bg-emerald-100 text-emerald-700 border-emerald-300'
                                                    }`}>
                                                        {item.tasa_error}% Error
                                                    </span>
                                                </div>

                                                {/* LaTeX Formula View */}
                                                <div className="bg-[#fcfcfa] border border-black/10 p-3 rounded-lg text-xs font-semibold max-h-24 overflow-y-auto leading-relaxed">
                                                    <MathView text={item.texto_pregunta} />
                                                </div>

                                                {/* Progress Bar rate */}
                                                <div className="space-y-1">
                                                    <div className="w-full bg-gray-100 border border-black/10 h-3 rounded-full overflow-hidden">
                                                        <div
                                                            className={`h-full border-r border-black/20 ${
                                                                item.tasa_error > 50
                                                                    ? 'bg-[#fb7185]'
                                                                    : 'bg-[#f4ecd6]'
                                                            }`}
                                                            style={{ width: `${item.tasa_error}%` }}
                                                        ></div>
                                                    </div>
                                                    <div className="flex justify-between text-[10px] font-bold text-gray-400">
                                                        <span>Respuestas: {item.total_respuestas}</span>
                                                        <span>Incorrectas: {item.incorrectas} | Correctas: {item.correctas}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
