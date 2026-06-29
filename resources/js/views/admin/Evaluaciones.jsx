import React, { useEffect, useState } from 'react';
import AdminLayout from '../../components/AdminLayout';
import { Plus, Trash2, Edit, Clipboard, Clock, BookOpen, AlertCircle } from 'lucide-react';

export default function Evaluaciones() {
    const token = localStorage.getItem('admin_token');

    // States
    const [evaluaciones, setEvaluaciones] = useState([]);
    const [cursos, setCursos] = useState([]);
    const [loading, setLoading] = useState(false);
    const [showModal, setShowModal] = useState(false);
    const [editingEvaluacion, setEditingEvaluacion] = useState(null);
    const [errorMessage, setErrorMessage] = useState(null);

    // Form fields
    const [nombre, setNombre] = useState('');
    const [selSemestreId, setSelSemestreId] = useState('');
    const [selUnidadId, setSelUnidadId] = useState('');
    const [totalPreguntas, setTotalPreguntas] = useState('10');
    const [tiempoLimite, setTiempoLimite] = useState('30');

    // Load Data
    const loadData = async () => {
        setLoading(true);
        setErrorMessage(null);
        try {
            const [evalRes, curRes] = await Promise.all([
                fetch('/api/evaluaciones', { headers: { 'Authorization': `Bearer ${token}` } }),
                fetch('/api/public/cursos')
            ]);

            if (evalRes.ok && curRes.ok) {
                const evalData = await evalRes.json();
                const curData = await curRes.json();
                setEvaluaciones(evalData);
                setCursos(curData);
            } else {
                setErrorMessage('Error al cargar datos del servidor.');
            }
        } catch (e) {
            console.error(e);
            setErrorMessage('Ocurrió un error al cargar la información.');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, []);

    // Filter units based on selected semester
    const getSemestreUnidades = () => {
        if (!cursos.length || !selSemestreId) return [];
        for (const materia of cursos) {
            const sem = materia.semestres.find(s => s.id.toString() === selSemestreId);
            if (sem) return sem.unidades || [];
        }
        return [];
    };

    // Open Modal for Create
    const openCreateModal = () => {
        setEditingEvaluacion(null);
        setNombre('');
        setSelSemestreId('');
        setSelUnidadId('');
        setTotalPreguntas('10');
        setTiempoLimite('30');
        setErrorMessage(null);
        setShowModal(true);
    };

    // Open Modal for Edit
    const openEditModal = (evaluacion) => {
        setEditingEvaluacion(evaluacion);
        setNombre(evaluacion.nombre);
        // Find Semester ID for this unit
        let foundSemId = '';
        for (const materia of cursos) {
            for (const sem of materia.semestres) {
                const hasUnidad = sem.unidades.some(u => u.id === evaluacion.unidad_id);
                if (hasUnidad) {
                    foundSemId = sem.id.toString();
                    break;
                }
            }
        }
        setSelSemestreId(foundSemId);
        setSelUnidadId(evaluacion.unidad_id.toString());
        setTotalPreguntas(evaluacion.total_preguntas.toString());
        setTiempoLimite(evaluacion.tiempo_limite_minutos.toString());
        setErrorMessage(null);
        setShowModal(true);
    };

    // Handle Submit Create/Edit
    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrorMessage(null);

        if (!nombre || !selUnidadId || !totalPreguntas || !tiempoLimite) {
            setErrorMessage('Todos los campos son obligatorios.');
            return;
        }

        const payload = {
            nombre,
            unidad_id: parseInt(selUnidadId),
            total_preguntas: parseInt(totalPreguntas),
            tiempo_limite_minutos: parseInt(tiempoLimite)
        };

        try {
            const url = editingEvaluacion 
                ? `/api/evaluaciones/${editingEvaluacion.id}` 
                : '/api/evaluaciones';
            const method = editingEvaluacion ? 'PUT' : 'POST';

            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                setShowModal(false);
                loadData();
            } else {
                const data = await res.json();
                setErrorMessage(data.message || 'Error al guardar la evaluación.');
            }
        } catch (err) {
            console.error(err);
            setErrorMessage('Error de red al conectar con el servidor.');
        }
    };

    // Handle Delete
    const handleDelete = async (id) => {
        if (!confirm('¿Estás seguro de que deseas eliminar esta evaluación?')) return;
        try {
            const res = await fetch(`/api/evaluaciones/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.ok) {
                loadData();
            } else {
                alert('No se pudo eliminar la evaluación.');
            }
        } catch (e) {
            console.error(e);
            alert('Error de red al eliminar.');
        }
    };

    return (
        <AdminLayout>
            <div className="space-y-8 py-4">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-4xl font-black tracking-tight mb-2">Evaluaciones</h2>
                        <p className="text-gray-600 font-medium">Configura exámenes temáticos para aplicar en tus grupos.</p>
                    </div>
                    <div>
                        <button
                            onClick={openCreateModal}
                            className="flex items-center gap-2 px-5 py-3 border-2 border-black rounded-xl font-bold bg-[#c5b0f4] hover:bg-purple-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            <Plus className="w-5 h-5" />
                            Nueva Evaluación
                        </button>
                    </div>
                </div>

                {/* List of evaluations */}
                {loading ? (
                    <div className="text-center py-12 font-bold text-gray-500">Cargando evaluaciones...</div>
                ) : evaluaciones.length === 0 ? (
                    <div className="bg-[#fcfcfa] border-2 border-black rounded-2xl p-12 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <Clipboard className="w-12 h-12 mx-auto text-gray-400 mb-4" />
                        <h3 className="text-xl font-bold mb-2">No hay evaluaciones registradas</h3>
                        <p className="text-gray-500 max-w-md mx-auto mb-6">Crea una evaluación estableciendo el número de reactivos y el tiempo límite para empezar.</p>
                        <button
                            onClick={openCreateModal}
                            className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                        >
                            Crear mi primera evaluación
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {evaluaciones.map((eva) => (
                            <div
                                key={eva.id}
                                className="bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden flex flex-col justify-between hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all"
                            >
                                <div className="p-6 space-y-4">
                                    <div className="flex justify-between items-start gap-2">
                                        <h3 className="text-xl font-black leading-tight text-black">{eva.nombre}</h3>
                                        <div className="flex gap-1">
                                            <button
                                                onClick={() => openEditModal(eva)}
                                                className="p-2 border-2 border-black rounded-lg bg-[#dceeb1] hover:bg-lime-200 active:translate-y-0.5 transition-all"
                                                title="Editar"
                                            >
                                                <Edit className="w-4 h-4" />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(eva.id)}
                                                className="p-2 border-2 border-black rounded-lg bg-[#fb7185] hover:bg-rose-200 active:translate-y-0.5 transition-all text-white"
                                                title="Eliminar"
                                            >
                                                <Trash2 className="w-4 h-4 text-black" />
                                            </button>
                                        </div>
                                    </div>

                                    <div className="border-t-2 border-black my-2"></div>

                                    <div className="space-y-2 text-sm font-bold text-gray-700">
                                        <div className="flex items-center gap-2">
                                            <BookOpen className="w-4 h-4 text-gray-500" />
                                            <span>
                                                {eva.unidad?.semestre?.materia?.nombre || 'Matemáticas'} - Semestre {eva.unidad?.semestre?.numero || ''}
                                            </span>
                                        </div>
                                        <div className="bg-[#f0f9ff] text-[#0369a1] border border-sky-300 p-2 rounded-lg text-xs font-semibold">
                                            Unidad {eva.unidad?.numero}: {eva.unidad?.nombre}
                                        </div>
                                        <div className="flex items-center gap-2 pt-2">
                                            <Clipboard className="w-4 h-4 text-gray-500" />
                                            <span>Reactivos: <span className="underline decoration-2">{eva.total_preguntas} preguntas</span></span>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <Clock className="w-4 h-4 text-gray-500" />
                                            <span>Tiempo: <span className="underline decoration-2">{eva.tiempo_limite_minutos} minutos</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}

                {/* Create/Edit Modal */}
                {showModal && (
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
                        <div className="bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] w-full max-w-lg overflow-hidden animate-slide-up">
                            <div className="bg-[#c5b0f4] p-6 border-b-4 border-black">
                                <h3 className="text-2xl font-black">
                                    {editingEvaluacion ? 'Editar Evaluación' : 'Crear Evaluación'}
                                </h3>
                            </div>
                            
                            <form onSubmit={handleSubmit} className="p-6 space-y-4">
                                {errorMessage && (
                                    <div className="bg-rose-50 border-2 border-rose-500 text-rose-700 p-3 rounded-xl font-bold text-sm flex items-center gap-2">
                                        <AlertCircle className="w-4 h-4 shrink-0" />
                                        <span>{errorMessage}</span>
                                    </div>
                                )}

                                <div className="space-y-1">
                                    <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Nombre del Examen</label>
                                    <input
                                        type="text"
                                        value={nombre}
                                        onChange={(e) => setNombre(e.target.value)}
                                        placeholder="Ej. Primer Examen Parcial"
                                        className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors"
                                    />
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Semestre</label>
                                        <select
                                            value={selSemestreId}
                                            onChange={(e) => {
                                                setSelSemestreId(e.target.value);
                                                setSelUnidadId('');
                                            }}
                                            className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors"
                                        >
                                            <option value="">Selecciona semestre</option>
                                            {cursos.map(m => 
                                                m.semestres.map(s => (
                                                    <option key={s.id} value={s.id}>
                                                        {m.nombre} - Sem. {s.numero}
                                                    </option>
                                                ))
                                            )}
                                        </select>
                                    </div>

                                    <div className="space-y-1">
                                        <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Unidad</label>
                                        <select
                                            value={selUnidadId}
                                            disabled={!selSemestreId}
                                            onChange={(e) => setSelUnidadId(e.target.value)}
                                            className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        >
                                            <option value="">Selecciona unidad</option>
                                            {getSemestreUnidades().map(u => (
                                                <option key={u.id} value={u.id}>
                                                    U{u.numero}. {u.nombre}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-1">
                                        <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Total Preguntas</label>
                                        <input
                                            type="number"
                                            value={totalPreguntas}
                                            onChange={(e) => setTotalPreguntas(e.target.value)}
                                            min="1"
                                            max="50"
                                            className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors"
                                        />
                                    </div>

                                    <div className="space-y-1">
                                        <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Tiempo Límite (min)</label>
                                        <input
                                            type="number"
                                            value={tiempoLimite}
                                            onChange={(e) => setTiempoLimite(e.target.value)}
                                            min="1"
                                            max="180"
                                            className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors"
                                        />
                                    </div>
                                </div>

                                <div className="flex justify-end gap-3 pt-4 border-t-2 border-gray-100">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-5 py-2.5 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 active:translate-y-0.5 transition-all"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-5 py-2.5 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 active:translate-y-0.5 transition-all"
                                    >
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
