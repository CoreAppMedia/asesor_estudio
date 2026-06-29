import React, { useEffect, useState } from 'react';
import AdminLayout from '../../components/AdminLayout';
import { useNavigate } from 'react-router-dom';
import { Plus, Trash2, Edit, HelpCircle, CheckCircle, AlertCircle } from 'lucide-react';
import MathView from '../../components/MathView';

export default function Preguntas() {
    const token = localStorage.getItem('admin_token');
    const navigate = useNavigate();

    // Catálogos
    const [cursos, setCursos] = useState([]);
    const [selectedUnidad, setSelectedUnidad] = useState(null);
    const [preguntas, setPreguntas] = useState([]);

    // Modals & States
    const [showModal, setShowModal] = useState(false);
    const [editingPregunta, setEditingPregunta] = useState(null);
    const [loading, setLoading] = useState(false);
    const [loadError, setLoadError] = useState(null);

    // Form fields
    const [textoPregunta, setTextoPregunta] = useState('');
    const [tipoPregunta, setTipoPregunta] = useState('opcion_multiple');
    const [opcionA, setOpcionA] = useState('');
    const [opcionB, setOpcionB] = useState('');
    const [opcionC, setOpcionC] = useState('');
    const [opcionD, setOpcionD] = useState('');
    const [respuestaCorrecta, setRespuestaCorrecta] = useState('A');

    // Navigation selectors
    const [selSemestreId, setSelSemestreId] = useState('');
    const [selUnidadId, setSelUnidadId] = useState('');

    // Load Cursos
    useEffect(() => {
        const loadCursos = async () => {
            try {
                const res = await fetch('/api/public/cursos');
                if (res.ok) {
                    const data = await res.json();
                    setCursos(data);
                }
            } catch (e) {
                console.error(e);
            }
        };
        loadCursos();
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

    // Load Preguntas for selected unit
    const loadPreguntas = async (unidadId) => {
        setLoading(true);
        setLoadError(null);
        try {
            const res = await fetch(`/api/preguntas?unidad_id=${unidadId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                }
            });

            if (res.status === 401 || res.status === 403) {
                // Sesión expirada — redirigir al login
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                navigate('/login');
                return;
            }

            if (res.ok) {
                const data = await res.json();
                setPreguntas(data);
            } else {
                const err = await res.json().catch(() => ({}));
                setLoadError(err.message || `Error ${res.status}: No se pudieron cargar las preguntas.`);
                setPreguntas([]);
            }
        } catch (e) {
            console.error(e);
            setLoadError('Error de red: no se pudo conectar al servidor.');
            setPreguntas([]);
        } finally {
            setLoading(false);
        }
    };

    const handleSelectUnidad = (e) => {
        const uId = e.target.value;
        setSelUnidadId(uId);
        if (uId) {
            const unidades = getSemestreUnidades();
            const u = unidades.find(un => un.id.toString() === uId);
            setSelectedUnidad(u);
            loadPreguntas(uId);
        } else {
            setSelectedUnidad(null);
            setPreguntas([]);
        }
    };

    // Open Modal for Create
    const openCreateModal = () => {
        setEditingPregunta(null);
        setTextoPregunta('');
        setTipoPregunta('opcion_multiple');
        setOpcionA('');
        setOpcionB('');
        setOpcionC('');
        setOpcionD('');
        setRespuestaCorrecta('A');
        setShowModal(true);
    };

    // Open Modal for Edit
    const openEditModal = (p) => {
        setEditingPregunta(p);
        setTextoPregunta(p.texto_pregunta);
        setTipoPregunta(p.tipo_pregunta);
        if (p.tipo_pregunta === 'opcion_multiple' && p.opciones_json) {
            setOpcionA(p.opciones_json.A || '');
            setOpcionB(p.opciones_json.B || '');
            setOpcionC(p.opciones_json.C || '');
            setOpcionD(p.opciones_json.D || '');
        } else {
            setOpcionA('');
            setOpcionB('');
            setOpcionC('');
            setOpcionD('');
        }
        setRespuestaCorrecta(p.respuesta_correcta);
        setShowModal(true);
    };

    // Save Question
    const handleSavePregunta = async (e) => {
        e.preventDefault();
        const opciones = tipoPregunta === 'opcion_multiple' ? { A: opcionA, B: opcionB, C: opcionC, D: opcionD } : null;

        const body = {
            unidad_id: parseInt(selUnidadId),
            texto_pregunta: textoPregunta,
            tipo_pregunta: tipoPregunta,
            opciones_json: opciones,
            respuesta_correcta: respuestaCorrecta,
        };

        const url = editingPregunta ? `/api/preguntas/${editingPregunta.id}` : '/api/preguntas';
        const method = editingPregunta ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body)
            });

            if (res.ok) {
                setShowModal(false);
                loadPreguntas(selUnidadId);
            } else {
                const err = await res.json();
                alert(err.message || 'Error al guardar la pregunta.');
            }
        } catch (e) {
            console.error(e);
        }
    };

    // Delete Question
    const handleDeletePregunta = async (id) => {
        if (!confirm('¿Estás seguro de eliminar esta pregunta del banco?')) return;
        try {
            const res = await fetch(`/api/preguntas/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                }
            });

            if (res.ok) {
                loadPreguntas(selUnidadId);
            }
        } catch (e) {
            console.error(e);
        }
    };

    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-4xl font-black tracking-tight mb-2">Banco de Preguntas</h2>
                        <p className="text-gray-600 font-medium">Gestiona los reactivos y exámenes de autoevaluación por unidad.</p>
                    </div>
                    {selectedUnidad && (
                        <button
                            onClick={openCreateModal}
                            className="flex items-center gap-2 px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            <Plus className="w-4 h-4" />
                            Agregar Pregunta
                        </button>
                    )}
                </div>

                {/* Filters Row */}
                <div className="bg-[#f7f7f5] border-2 border-black p-6 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-2">
                        <label className="block text-xs font-black uppercase text-gray-700">Materia y Semestre</label>
                        <select
                            value={selSemestreId}
                            onChange={(e) => {
                                setSelSemestreId(e.target.value);
                                setSelUnidadId('');
                                setSelectedUnidad(null);
                                setPreguntas([]);
                                setLoadError(null);
                            }}
                            className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                        >
                            <option value="">Selecciona materia/semestre...</option>
                            {cursos.length > 0 && cursos[0].semestres.map(s => (
                                <option key={s.id} value={s.id}>{s.descripcion} (Semestre {s.numero})</option>
                            ))}
                        </select>
                    </div>

                    <div className="space-y-2">
                        <label className="block text-xs font-black uppercase text-gray-700">Unidad de Aprendizaje</label>
                        <select
                            value={selUnidadId}
                            onChange={handleSelectUnidad}
                            disabled={!selSemestreId}
                            className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50 disabled:opacity-50"
                        >
                            <option value="">Selecciona unidad...</option>
                            {getSemestreUnidades().map(u => (
                                <option key={u.id} value={u.id}>Unidad {u.numero}: {u.nombre}</option>
                            ))}
                        </select>
                    </div>
                </div>

                {/* Questions List */}
                <div className="space-y-4">
                    {selectedUnidad ? (
                        <>
                            <div className="flex items-center justify-between border-b-2 border-black pb-3">
                                <h3 className="text-xl font-black flex items-center gap-2">
                                    <HelpCircle className="w-5 h-5 text-gray-600" /> Reactivos en {selectedUnidad.nombre}
                                </h3>
                                <span className="text-xs font-black uppercase tracking-wider bg-[#c5b0f4] px-3 py-1 border-2 border-black rounded-lg">
                                    {preguntas.length} Preguntas
                                </span>
                            </div>

                            {loading ? (
                                <p className="text-center py-10 font-bold text-gray-500">Cargando reactivos...</p>
                            ) : loadError ? (
                                <div className="bg-[#efd4d4] border-2 border-black p-6 rounded-2xl flex items-center gap-3 font-bold text-red-950">
                                    <AlertCircle className="w-5 h-5 shrink-0" />
                                    <span>{loadError}</span>
                                </div>
                            ) : preguntas.length === 0 ? (
                                <div className="bg-white border-2 border-black border-dashed p-10 rounded-2xl text-center text-gray-500 font-bold">
                                    No hay preguntas registradas en esta unidad. Haz clic en "Agregar Pregunta" para iniciar el banco.
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    {preguntas.map((p, idx) => (
                                        <div key={p.id} className="bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] p-6 space-y-4">
                                            <div className="flex justify-between items-start gap-4">
                                                <div className="flex items-start gap-3">
                                                    <span className="w-8 h-8 flex items-center justify-center font-black text-sm bg-[#f4ecd6] border-2 border-black rounded-lg shrink-0">
                                                        {idx + 1}
                                                    </span>
                                                    <div className="font-extrabold text-base leading-relaxed pt-1">
                                                        <MathView text={p.texto_pregunta} />
                                                    </div>
                                                </div>
                                                <div className="flex gap-2 shrink-0">
                                                    <button
                                                        onClick={() => openEditModal(p)}
                                                        className="p-2 text-gray-500 hover:text-black hover:bg-gray-100 border-2 border-transparent hover:border-black rounded-lg transition-all"
                                                    >
                                                        <Edit className="w-4 h-4" />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDeletePregunta(p.id)}
                                                        className="p-2 text-gray-500 hover:text-red-600 hover:bg-[#efd4d4] border-2 border-transparent hover:border-black rounded-lg transition-all"
                                                    >
                                                        <Trash2 className="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </div>

                                            {/* Options details */}
                                            {p.tipo_pregunta === 'opcion_multiple' && p.opciones_json && (
                                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-11">
                                                    {Object.entries(p.opciones_json).map(([key, val]) => {
                                                        const isCorrect = key === p.respuesta_correcta;
                                                        return (
                                                            <div
                                                                key={key}
                                                                className={`p-3 border-2 rounded-xl text-sm font-semibold flex items-center gap-2 ${
                                                                    isCorrect 
                                                                        ? 'bg-[#c8e6cd]/30 border-green-600 text-green-950 font-bold' 
                                                                        : 'border-gray-200 text-gray-600'
                                                                }`}
                                                            >
                                                                <span className="font-black">{key})</span> <MathView text={val} />
                                                                {isCorrect && <CheckCircle className="w-4 h-4 text-green-600 shrink-0 ml-auto" />}
                                                            </div>
                                                        );
                                                    })}
                                                </div>
                                            )}

                                            {p.tipo_pregunta === 'respuesta_abierta' && (
                                                <div className="pl-11 flex items-center gap-2 text-sm font-semibold">
                                                    <span className="text-gray-500">Respuesta correcta:</span>
                                                    <span className="bg-[#c8e6cd] px-3 py-1 border border-black rounded-lg font-black text-green-950">
                                                        <MathView text={p.respuesta_correcta} />
                                                    </span>
                                                </div>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            )}
                        </>
                    ) : (
                        <div className="bg-[#f7f7f5] border-2 border-black border-dashed p-12 rounded-2xl text-center text-gray-500 font-bold">
                            Selecciona una materia y una unidad académica para consultar o crear preguntas.
                        </div>
                    )}
                </div>

                {/* Modal Form */}
                {showModal && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
                        <div className="bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] max-w-xl w-full overflow-hidden max-h-[90vh] flex flex-col">
                            <div className="bg-[#c5b0f4] p-5 border-b-2 border-black font-black uppercase tracking-wider text-sm">
                                {editingPregunta ? 'Editar Pregunta' : 'Agregar Nueva Pregunta'}
                            </div>

                            <form onSubmit={handleSavePregunta} className="p-6 space-y-4 overflow-y-auto flex-1">
                                <div className="space-y-2">
                                    <label className="block text-xs font-black uppercase text-gray-600">Enunciado / Pregunta (Soporta LaTeX)</label>
                                    <textarea
                                        required
                                        rows="3"
                                        placeholder="Ej. Evalúa la derivada de \(f(x) = x^2\)..."
                                        value={textoPregunta}
                                        onChange={(e) => setTextoPregunta(e.target.value)}
                                        className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                    />
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Tipo de Pregunta</label>
                                        <select
                                            value={tipoPregunta}
                                            onChange={(e) => {
                                                setTipoPregunta(e.target.value);
                                                if (e.target.value === 'respuesta_abierta') setRespuestaCorrecta('');
                                                else setRespuestaCorrecta('A');
                                            }}
                                            className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                        >
                                            <option value="opcion_multiple">Opción Múltiple</option>
                                            <option value="respuesta_abierta">Respuesta Abierta</option>
                                        </select>
                                    </div>

                                    <div className="space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Respuesta Correcta</label>
                                        {tipoPregunta === 'opcion_multiple' ? (
                                            <select
                                                value={respuestaCorrecta}
                                                onChange={(e) => setRespuestaCorrecta(e.target.value)}
                                                className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                            >
                                                <option value="A">Opción A</option>
                                                <option value="B">Opción B</option>
                                                <option value="C">Opción C</option>
                                                <option value="D">Opción D</option>
                                            </select>
                                        ) : (
                                            <input
                                                type="text"
                                                required
                                                placeholder="Ej. 15"
                                                value={respuestaCorrecta}
                                                onChange={(e) => setRespuestaCorrecta(e.target.value)}
                                                className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                            />
                                        )}
                                    </div>
                                </div>

                                {tipoPregunta === 'opcion_multiple' && (
                                    <div className="space-y-3 pt-2 border-t border-black/10">
                                        <h5 className="font-extrabold text-xs uppercase text-gray-500">Opciones de respuesta</h5>
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div className="space-y-1">
                                                <label className="block text-[10px] font-black text-gray-500 uppercase">Opción A</label>
                                                <input
                                                    type="text"
                                                    required
                                                    placeholder="Respuesta A"
                                                    value={opcionA}
                                                    onChange={(e) => setOpcionA(e.target.value)}
                                                    className="w-full p-3 border-2 border-black rounded-xl font-semibold bg-white focus:outline-none focus:bg-gray-50"
                                                />
                                            </div>
                                            <div className="space-y-1">
                                                <label className="block text-[10px] font-black text-gray-500 uppercase">Opción B</label>
                                                <input
                                                    type="text"
                                                    required
                                                    placeholder="Respuesta B"
                                                    value={opcionB}
                                                    onChange={(e) => setOpcionB(e.target.value)}
                                                    className="w-full p-3 border-2 border-black rounded-xl font-semibold bg-white focus:outline-none focus:bg-gray-50"
                                                />
                                            </div>
                                            <div className="space-y-1">
                                                <label className="block text-[10px] font-black text-gray-500 uppercase">Opción C</label>
                                                <input
                                                    type="text"
                                                    required
                                                    placeholder="Respuesta C"
                                                    value={opcionC}
                                                    onChange={(e) => setOpcionC(e.target.value)}
                                                    className="w-full p-3 border-2 border-black rounded-xl font-semibold bg-white focus:outline-none focus:bg-gray-50"
                                                />
                                            </div>
                                            <div className="space-y-1">
                                                <label className="block text-[10px] font-black text-gray-500 uppercase">Opción D</label>
                                                <input
                                                    type="text"
                                                    required
                                                    placeholder="Respuesta D"
                                                    value={opcionD}
                                                    onChange={(e) => setOpcionD(e.target.value)}
                                                    className="w-full p-3 border-2 border-black rounded-xl font-semibold bg-white focus:outline-none focus:bg-gray-50"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                )}

                                <div className="flex gap-3 justify-end pt-4 border-t-2 border-black">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
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
