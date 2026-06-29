import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import AdminLayout from '../../components/AdminLayout';
import { Plus, Trash2, Power, QrCode, Group, CheckCircle, XCircle, Users, Play, Square, Award, AlertCircle } from 'lucide-react';

export default function Sesiones() {
    const token = localStorage.getItem('admin_token');
    const navigate = useNavigate();

    // States
    const [sesiones, setSesiones] = useState([]);
    const [evaluaciones, setEvaluaciones] = useState([]);
    const [grupos, setGrupos] = useState([]);
    const [loading, setLoading] = useState(false);
    const [showModal, setShowModal] = useState(false);
    const [showQrModal, setShowQrModal] = useState(false);
    const [selectedSesionQr, setSelectedSesionQr] = useState(null);
    const [errorMessage, setErrorMessage] = useState(null);

    // Form fields
    const [selGrupoId, setSelGrupoId] = useState('');
    const [selEvaluacionId, setSelEvaluacionId] = useState('');

    const loadData = async () => {
        setLoading(true);
        setErrorMessage(null);
        try {
            const [sesRes, evalRes, grupRes] = await Promise.all([
                fetch('/api/sesiones', { headers: { 'Authorization': `Bearer ${token}` } }),
                fetch('/api/evaluaciones', { headers: { 'Authorization': `Bearer ${token}` } }),
                fetch('/api/grupos', { headers: { 'Authorization': `Bearer ${token}` } })
            ]);

            if (sesRes.ok && evalRes.ok && grupRes.ok) {
                setSesiones(await sesRes.json());
                setEvaluaciones(await evalRes.json());
                setGrupos(await grupRes.json());
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

    const openCreateModal = () => {
        setSelGrupoId('');
        setSelEvaluacionId('');
        setErrorMessage(null);
        setShowModal(true);
    };

    const handleCreateSesion = async (e) => {
        e.preventDefault();
        setErrorMessage(null);

        if (!selGrupoId || !selEvaluacionId) {
            setErrorMessage('Debes seleccionar un grupo y una evaluación.');
            return;
        }

        try {
            const res = await fetch('/api/sesiones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    grupo_id: parseInt(selGrupoId),
                    evaluacion_id: parseInt(selEvaluacionId)
                })
            });

            if (res.ok) {
                setShowModal(false);
                loadData();
            } else {
                const data = await res.json();
                setErrorMessage(data.message || 'Error al crear la sesión.');
            }
        } catch (err) {
            console.error(err);
            setErrorMessage('Error de red al conectar con el servidor.');
        }
    };

    const handleToggleActive = async (id) => {
        try {
            const res = await fetch(`/api/sesiones/${id}/toggle`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.ok) {
                loadData();
            } else {
                alert('No se pudo modificar el estado de la sesión.');
            }
        } catch (e) {
            console.error(e);
            alert('Error de red.');
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('¿Estás seguro de que deseas eliminar esta sesión? Todos los intentos de examen vinculados se perderán.')) return;
        try {
            const res = await fetch(`/api/sesiones/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.ok) {
                loadData();
            } else {
                alert('No se pudo eliminar la sesión.');
            }
        } catch (e) {
            console.error(e);
            alert('Error de red al eliminar.');
        }
    };

    const openQrModal = (sesion) => {
        setSelectedSesionQr(sesion);
        setShowQrModal(true);
    };

    // Calculate QR Code Link
    const getStudentLink = (sesion) => {
        if (!sesion) return '';
        return `${window.location.origin}/acceso?code=${sesion.codigo_acceso}`;
    };

    const getQrImageSrc = (sesion) => {
        if (!sesion) return '';
        const link = getStudentLink(sesion);
        return `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(link)}`;
    };

    return (
        <AdminLayout>
            <div className="space-y-8 py-4">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-4xl font-black tracking-tight mb-2">Sesiones de Clase</h2>
                        <p className="text-gray-600 font-medium">Abre sesiones de evaluación y proyecta el código QR para tus alumnos.</p>
                    </div>
                    <div>
                        <button
                            onClick={openCreateModal}
                            className="flex items-center gap-2 px-5 py-3 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            <Plus className="w-5 h-5" />
                            Iniciar Sesión QR
                        </button>
                    </div>
                </div>

                {/* List of sessions */}
                {loading ? (
                    <div className="text-center py-12 font-bold text-gray-500">Cargando sesiones...</div>
                ) : sesiones.length === 0 ? (
                    <div className="bg-[#fcfcfa] border-2 border-black rounded-2xl p-12 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <QrCode className="w-12 h-12 mx-auto text-gray-400 mb-4" />
                        <h3 className="text-xl font-bold mb-2">No hay sesiones activas ni anteriores</h3>
                        <p className="text-gray-500 max-w-md mx-auto mb-6">Abre una sesión de examen asignando un grupo a una evaluación de tu catálogo.</p>
                        <button
                            onClick={openCreateModal}
                            className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                        >
                            Crear mi primera sesión
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {sesiones.map((ses) => (
                            <div
                                key={ses.id}
                                className={`bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden flex flex-col justify-between hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all ${
                                    ses.activa ? 'ring-4 ring-[#dceeb1]/40' : ''
                                }`}
                            >
                                <div className="p-6 space-y-4">
                                    <div className="flex justify-between items-start gap-2">
                                        <div>
                                            <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border-2 border-black ${
                                                ses.activa 
                                                    ? 'bg-[#dceeb1] text-black' 
                                                    : 'bg-[#fb7185] text-black'
                                            }`}>
                                                {ses.activa ? (
                                                    <span className="flex items-center gap-1">
                                                        <span className="w-2 h-2 rounded-full bg-green-600 animate-ping inline-block"></span>
                                                        Activa
                                                    </span>
                                                ) : 'Cerrada'}
                                            </span>
                                            <h3 className="text-xl font-black leading-tight text-black mt-2">
                                                {ses.evaluacion?.nombre || 'Examen'}
                                            </h3>
                                        </div>
                                        <button
                                            onClick={() => handleDelete(ses.id)}
                                            className="p-2 border-2 border-black rounded-lg bg-gray-50 hover:bg-[#fb7185] hover:text-black active:translate-y-0.5 transition-all text-gray-400"
                                            title="Eliminar sesión"
                                        >
                                            <Trash2 className="w-4 h-4 text-black" />
                                        </button>
                                    </div>

                                    {/* Code display */}
                                    <div className="bg-gray-50 border-2 border-black rounded-xl p-3 flex items-center justify-between">
                                        <div className="space-y-0.5">
                                            <span className="text-[10px] font-black uppercase tracking-widest text-gray-400">Código de Acceso</span>
                                            <p className="text-3xl font-black font-mono tracking-wider text-black">{ses.codigo_acceso}</p>
                                        </div>
                                        {ses.activa && (
                                            <button
                                                onClick={() => openQrModal(ses)}
                                                className="p-2.5 border-2 border-black rounded-xl bg-[#c5b0f4] hover:bg-purple-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] transition-all"
                                                title="Mostrar Código QR"
                                            >
                                                <QrCode className="w-5 h-5 text-black" />
                                            </button>
                                        )}
                                    </div>

                                    <div className="space-y-2 text-xs font-bold text-gray-700">
                                        <div className="flex items-center justify-between">
                                            <span>Grupo escolar:</span>
                                            <span className="text-black bg-gray-100 px-2 py-0.5 rounded border border-black/10">
                                                {ses.grupo?.nombre} ({ses.grupo?.generacion?.anio_inicio}-{ses.grupo?.generacion?.anio_fin})
                                            </span>
                                        </div>
                                        <div className="flex items-center justify-between">
                                            <span>Intentos totales:</span>
                                            <span className="text-black font-black">{ses.total_intentos || 0}</span>
                                        </div>
                                        <div className="flex items-center justify-between">
                                            <span>Exámenes finalizados:</span>
                                            <span className="text-emerald-600 font-black">{ses.intentos_completados || 0}</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-4 bg-gray-50 border-t-2 border-black flex gap-2">
                                    <button
                                        onClick={() => handleToggleActive(ses.id)}
                                        className={`w-1/2 flex items-center justify-center gap-1.5 py-2 border-2 border-black rounded-xl font-bold transition-all shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] ${
                                            ses.activa 
                                                ? 'bg-[#fb7185] hover:bg-rose-300' 
                                                : 'bg-[#dceeb1] hover:bg-lime-200'
                                        }`}
                                    >
                                        {ses.activa ? (
                                            <>
                                                <Square className="w-3.5 h-3.5 text-black fill-black" />
                                                Cerrar
                                            </>
                                        ) : (
                                            <>
                                                <Play className="w-3.5 h-3.5 text-black fill-black" />
                                                Abrir
                                            </>
                                        )}
                                    </button>
                                    <button
                                        onClick={() => navigate(`/admin/sesiones/${ses.id}/revision`)}
                                        className="w-1/2 flex items-center justify-center gap-1.5 py-2 border-2 border-black rounded-xl font-bold bg-[#c5b0f4] hover:bg-purple-200 transition-all shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] text-black"
                                    >
                                        <Award className="w-3.5 h-3.5 text-black" />
                                        Revisar
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}

                {/* Create Session Modal */}
                {showModal && (
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
                        <div className="bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] w-full max-w-md overflow-hidden animate-slide-up">
                            <div className="bg-[#dceeb1] p-6 border-b-4 border-black">
                                <h3 className="text-2xl font-black">Abrir Evaluación QR</h3>
                            </div>
                            
                            <form onSubmit={handleCreateSesion} className="p-6 space-y-4">
                                {errorMessage && (
                                    <div className="bg-rose-50 border-2 border-rose-500 text-rose-700 p-3 rounded-xl font-bold text-sm flex items-center gap-2">
                                        <AlertCircle className="w-4 h-4 shrink-0" />
                                        <span>{errorMessage}</span>
                                    </div>
                                )}

                                <div className="space-y-1">
                                    <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Grupo Escolar</label>
                                    <select
                                        value={selGrupoId}
                                        onChange={(e) => setSelGrupoId(e.target.value)}
                                        className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors"
                                    >
                                        <option value="">Selecciona un grupo</option>
                                        {grupos.map(g => (
                                            <option key={g.id} value={g.id}>
                                                Grupo {g.nombre} (Gen {g.generacion?.anio_inicio}-{g.generacion?.anio_fin})
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="space-y-1">
                                    <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Evaluación a Aplicar</label>
                                    <select
                                        value={selEvaluacionId}
                                        onChange={(e) => setSelEvaluacionId(e.target.value)}
                                        className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 transition-colors"
                                    >
                                        <option value="">Selecciona evaluación</option>
                                        {evaluaciones.map(ev => (
                                            <option key={ev.id} value={ev.id}>
                                                {ev.nombre} ({ev.total_preguntas} preguntas, {ev.tiempo_limite_minutos} min)
                                            </option>
                                        ))}
                                    </select>
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
                                        Abrir Sesión
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}

                {/* QR Projection Modal */}
                {showQrModal && selectedSesionQr && (
                    <div className="fixed inset-0 bg-black/75 backdrop-blur-md flex items-center justify-center p-4 z-50 animate-fade-in">
                        <div className="bg-white border-4 border-black rounded-3xl shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] w-full max-w-lg overflow-hidden animate-slide-up text-center">
                            <div className="bg-[#c5b0f4] p-6 border-b-4 border-black flex justify-between items-center">
                                <h3 className="text-2xl font-black text-left">Proyectar Examen</h3>
                                <button
                                    onClick={() => setShowQrModal(false)}
                                    className="px-3 py-1 border-2 border-black rounded-lg bg-white hover:bg-gray-100 font-black text-sm"
                                >
                                    Cerrar
                                </button>
                            </div>
                            
                            <div className="p-8 space-y-6">
                                <div className="space-y-1">
                                    <h4 className="text-3xl font-black tracking-tight text-black">{selectedSesionQr.evaluacion?.nombre}</h4>
                                    <p className="text-sm font-bold text-gray-500">Grupo: {selectedSesionQr.grupo?.nombre} | Unidad: {selectedSesionQr.evaluacion?.unidad?.nombre}</p>
                                </div>

                                {/* QR Code Container */}
                                <div className="bg-white border-4 border-black p-4 rounded-2xl inline-block shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                    <img
                                        src={getQrImageSrc(selectedSesionQr)}
                                        alt="Código QR de ingreso"
                                        className="w-64 h-64 mx-auto"
                                    />
                                </div>

                                <div className="space-y-2">
                                    <p className="text-sm font-bold text-gray-600">Escanea el código QR anterior con tu celular o ingresa a:</p>
                                    <div className="bg-[#f0f9ff] border-2 border-black p-3 rounded-xl font-mono text-xs font-black break-all text-sky-800">
                                        {getStudentLink(selectedSesionQr)}
                                    </div>
                                </div>

                                <div className="border-t-2 border-black pt-4">
                                    <p className="text-xs font-black uppercase tracking-widest text-gray-400">Código de Acceso Manual</p>
                                    <p className="text-5xl font-black font-mono tracking-widest text-black mt-1 bg-yellow-100 border-2 border-black inline-block px-6 py-2 rounded-2xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                                        {selectedSesionQr.codigo_acceso}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
