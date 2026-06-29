import React, { useEffect, useState, useRef } from 'react';
import AdminLayout from '../../components/AdminLayout';
import { Plus, Upload, Trash2, Calendar, FileSpreadsheet, Users, ChevronRight, Info, QrCode, Copy } from 'lucide-react';

export default function Grupos() {
    const token = localStorage.getItem('admin_token');

    // States
    const [generaciones, setGeneraciones] = useState([]);
    const [grupos, setGrupos] = useState([]);
    const [selectedGrupo, setSelectedGrupo] = useState(null);
    const [alumnos, setAlumnos] = useState([]);

    // Forms
    const [newGenStart, setNewGenStart] = useState('');
    const [newGenEnd, setNewGenEnd] = useState('');
    const [newGrupoNombre, setNewGrupoNombre] = useState('');
    const [newGrupoGenId, setNewGrupoGenId] = useState('');

    // UI States
    const [showGenModal, setShowGenModal] = useState(false);
    const [showGrupoModal, setShowGrupoModal] = useState(false);
    const [loadingAlumnos, setLoadingAlumnos] = useState(false);
    const [csvFile, setCsvFile] = useState(null);
    const [importing, setImporting] = useState(false);
    const [message, setMessage] = useState(null);

    // Alumno manual form states
    const [showAlumnoModal, setShowAlumnoModal] = useState(false);
    const [alumnoNombre, setAlumnoNombre] = useState('');
    const [alumnoPaterno, setAlumnoPaterno] = useState('');
    const [alumnoMaterno, setAlumnoMaterno] = useState('');
    const [alumnoEdad, setAlumnoEdad] = useState('');
    const [alumnoCelular, setAlumnoCelular] = useState('');
    const [alumnoNumeroLista, setAlumnoNumeroLista] = useState('');

    // QR Modal state
    const [showQrModal, setShowQrModal] = useState(false);
    const [copied, setCopied] = useState(false);

    const fileInputRef = useRef(null);

    // Load Generaciones and Grupos
    const loadData = async () => {
        try {
            const [genRes, grupRes] = await Promise.all([
                fetch('/api/generaciones', { headers: { 'Authorization': `Bearer ${token}` } }),
                fetch('/api/grupos', { headers: { 'Authorization': `Bearer ${token}` } }),
            ]);

            if (genRes.ok && grupRes.ok) {
                const gens = await genRes.json();
                const grups = await grupRes.json();
                setGeneraciones(gens);
                setGrupos(grups);

                if (gens.length > 0 && !newGrupoGenId) {
                    setNewGrupoGenId(gens[0].id.toString());
                }
            }
        } catch (e) {
            console.error('Error loading administrative data', e);
        }
    };

    useEffect(() => {
        loadData();
    }, []);

    // Load Alumnos for selected group
    const loadAlumnos = async (grupo) => {
        setLoadingAlumnos(true);
        setSelectedGrupo(grupo);
        setCsvFile(null);
        setMessage(null);
        try {
            const res = await fetch(`/api/grupos/${grupo.id}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (res.ok) {
                const data = await res.json();
                setAlumnos(data.alumnos || []);
            }
        } catch (e) {
            console.error('Error loading students', e);
        } finally {
            setLoadingAlumnos(false);
        }
    };

    // Create Generación
    const handleCreateGen = async (e) => {
        e.preventDefault();
        try {
            const res = await fetch('/api/generaciones', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    anio_inicio: parseInt(newGenStart),
                    anio_fin: parseInt(newGenEnd),
                })
            });

            if (res.ok) {
                setNewGenStart('');
                setNewGenEnd('');
                setShowGenModal(false);
                loadData();
            } else {
                const data = await res.json();
                alert(data.message || 'Error al crear generación.');
            }
        } catch (e) {
            console.error(e);
        }
    };

    // Create Grupo
    const handleCreateGrupo = async (e) => {
        e.preventDefault();
        try {
            const res = await fetch('/api/grupos', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    generacion_id: parseInt(newGrupoGenId),
                    nombre: newGrupoNombre,
                })
            });

            if (res.ok) {
                setNewGrupoNombre('');
                setShowGrupoModal(false);
                loadData();
            } else {
                const data = await res.json();
                alert(data.message || 'Error al crear grupo.');
            }
        } catch (e) {
            console.error(e);
        }
    };

    // CSV Import
    const handleImportCSV = async (e) => {
        e.preventDefault();
        if (!csvFile || !selectedGrupo) return;

        setImporting(true);
        setMessage(null);

        const formData = new FormData();
        formData.append('file', csvFile);

        try {
            const res = await fetch(`/api/grupos/${selectedGrupo.id}/importar-alumnos`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await res.json();

            if (res.ok) {
                setMessage({ type: 'success', text: `${data.message} (${data.imported_count} alumnos).` });
                setCsvFile(null);
                loadAlumnos(selectedGrupo);
            } else {
                setMessage({ type: 'error', text: data.message || 'Error al importar archivo CSV.' });
            }
        } catch (e) {
            setMessage({ type: 'error', text: 'Error de red al importar alumnos.' });
        } finally {
            setImporting(false);
        }
    };

    // Delete student
    const handleDeleteAlumno = async (id) => {
        if (!confirm('¿Estás seguro de eliminar a este alumno?')) return;

        try {
            const res = await fetch(`/api/alumnos/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                }
            });

            if (res.ok) {
                loadAlumnos(selectedGrupo);
            }
        } catch (e) {
            console.error('Error deleting student', e);
        }
    };

    const handleCreateAlumno = async (e) => {
        e.preventDefault();
        try {
            const res = await fetch('/api/alumnos', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    grupo_id: selectedGrupo.id,
                    numero_lista: parseInt(alumnoNumeroLista),
                    nombre: alumnoNombre,
                    apellido_paterno: alumnoPaterno,
                    apellido_materno: alumnoMaterno || null,
                    edad: alumnoEdad ? parseInt(alumnoEdad) : null,
                    numero_celular: alumnoCelular || null,
                })
            });

            const data = await res.json();

            if (res.ok) {
                setAlumnoNombre('');
                setAlumnoPaterno('');
                setAlumnoMaterno('');
                setAlumnoEdad('');
                setAlumnoCelular('');
                setAlumnoNumeroLista('');
                setShowAlumnoModal(false);
                loadAlumnos(selectedGrupo);
            } else {
                alert(data.message || 'Error al agregar alumno.');
            }
        } catch (e) {
            console.error(e);
        }
    };

    const openAddAlumnoModal = () => {
        const nextListNum = alumnos.length > 0 ? Math.max(...alumnos.map(a => a.numero_lista || 0)) + 1 : 1;
        setAlumnoNumeroLista(nextListNum.toString());
        setShowAlumnoModal(true);
    };

    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-4xl font-black tracking-tight mb-2">Grupos y Alumnos</h2>
                        <p className="text-gray-600 font-medium">Administra las generaciones, los grupos escolares y sus alumnos.</p>
                    </div>
                    <div className="flex gap-3">
                        <button
                            onClick={() => setShowGenModal(true)}
                            className="flex items-center gap-2 px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            <Calendar className="w-4 h-4" />
                            Nueva Generación
                        </button>
                        <button
                            onClick={() => setShowGrupoModal(true)}
                            className="flex items-center gap-2 px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#c5b0f4] hover:bg-purple-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            <Plus className="w-4 h-4" />
                            Nuevo Grupo
                        </button>
                    </div>
                </div>

                {/* Main Split Layout */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    {/* Sidebar / Groups List */}
                    <div className="lg:col-span-5 bg-white border-2 border-black rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                        <div className="bg-[#f7f7f5] p-5 border-b-2 border-black flex items-center justify-between">
                            <span className="font-black uppercase tracking-wider text-sm flex items-center gap-2">
                                <Users className="w-4 h-4" /> Grupos Registrados
                            </span>
                        </div>
                        {generaciones.length === 0 ? (
                            <div className="p-8 text-center text-gray-500 font-bold">
                                No hay generaciones ni grupos creados. Comienza haciendo clic en "Nueva Generación".
                            </div>
                        ) : (
                            <div className="divide-y-2 divide-black">
                                {generaciones.map((gen) => {
                                    const genGroups = grupos.filter(g => g.generacion_id === gen.id);
                                    return (
                                        <div key={gen.id} className="p-5 space-y-3">
                                            <h4 className="font-black text-sm text-gray-500 uppercase tracking-widest">
                                                Generación {gen.anio_inicio} - {gen.anio_fin}
                                            </h4>
                                            {genGroups.length === 0 ? (
                                                <p className="text-xs text-gray-400 italic font-semibold">Sin grupos registrados.</p>
                                            ) : (
                                                <div className="grid grid-cols-1 gap-2">
                                                    {genGroups.map((g) => {
                                                        const isSelected = selectedGrupo && selectedGrupo.id === g.id;
                                                        return (
                                                            <button
                                                                key={g.id}
                                                                onClick={() => loadAlumnos(g)}
                                                                className={`flex items-center justify-between p-3 rounded-xl border-2 font-bold text-left transition-all ${
                                                                    isSelected
                                                                        ? 'bg-[#dceeb1] border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-black'
                                                                        : 'border-transparent text-gray-700 hover:text-black hover:border-black hover:bg-gray-50'
                                                                }`}
                                                            >
                                                                <span className="truncate">{g.nombre}</span>
                                                                <ChevronRight className="w-4 h-4 shrink-0" />
                                                            </button>
                                                        );
                                                    })}
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>

                    {/* Content Detail / Student List */}
                    <div className="lg:col-span-7 space-y-8">
                        {selectedGrupo ? (
                            <div className="bg-white border-2 border-black rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                                <div className="bg-[#dceeb1] p-5 border-b-2 border-black flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <h3 className="text-xl font-black">{selectedGrupo.nombre}</h3>
                                        <p className="text-xs font-bold text-gray-700 uppercase tracking-wide">
                                            Generación {selectedGrupo.generacion?.anio_inicio} - {selectedGrupo.generacion?.anio_fin}
                                        </p>
                                    </div>
                                    <div className="flex flex-wrap items-center gap-3">
                                        <button
                                            onClick={() => setShowQrModal(true)}
                                            className="flex items-center gap-1.5 px-3 py-1.5 border-2 border-black rounded-lg font-bold bg-[#c5b0f4] hover:bg-purple-200 text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5"
                                        >
                                            <QrCode className="w-3.5 h-3.5" />
                                            QR Inscripción
                                        </button>
                                        <button
                                            onClick={openAddAlumnoModal}
                                            className="flex items-center gap-1.5 px-3 py-1.5 border-2 border-black rounded-lg font-bold bg-[#f4ecd6] hover:bg-amber-100 text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5"
                                        >
                                            <Plus className="w-3.5 h-3.5" />
                                            Agregar Alumno
                                        </button>
                                        <div className="text-sm font-black bg-white px-3 py-1 border-2 border-black rounded-lg">
                                            {alumnos.length} Alumnos
                                        </div>
                                    </div>
                                </div>

                                <div className="p-6 space-y-8">
                                    {/* CSV Upload */}
                                    <div className="bg-white border-2 border-dashed border-black p-6 rounded-2xl text-center space-y-4">
                                        <div className="w-12 h-12 bg-[#f4ecd6] flex items-center justify-center rounded-xl mx-auto border-2 border-black">
                                            <FileSpreadsheet className="text-black w-6 h-6" />
                                        </div>
                                        <div>
                                            <h4 className="font-extrabold text-sm uppercase">Cargar Lista de Alumnos (CSV)</h4>
                                            <p className="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                                                Sube un archivo delimitado por comas con las columnas: <strong>numero_lista, nombre, apellido</strong>.
                                            </p>
                                        </div>

                                        <form onSubmit={handleImportCSV} className="flex flex-col sm:flex-row items-center justify-center gap-3">
                                            <input
                                                type="file"
                                                accept=".csv,.txt"
                                                ref={fileInputRef}
                                                onChange={(e) => setCsvFile(e.target.files[0])}
                                                className="hidden"
                                            />
                                            <button
                                                type="button"
                                                onClick={() => fileInputRef.current.click()}
                                                className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all text-sm shrink-0"
                                            >
                                                {csvFile ? 'Cambiar archivo' : 'Seleccionar CSV'}
                                            </button>
                                            {csvFile && (
                                                <div className="flex items-center gap-2">
                                                    <span className="text-xs font-bold truncate max-w-[150px] bg-[#c8e6cd] px-2 py-1 rounded border border-black">
                                                        {csvFile.name}
                                                    </span>
                                                    <button
                                                        type="submit"
                                                        disabled={importing}
                                                        className="flex items-center gap-1 px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#c5b0f4] hover:bg-purple-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all text-sm disabled:opacity-50 shrink-0"
                                                    >
                                                        <Upload className="w-4 h-4" />
                                                        {importing ? 'Importando...' : 'Cargar'}
                                                    </button>
                                                </div>
                                            )}
                                        </form>
                                    </div>

                                    {/* Notifications */}
                                    {message && (
                                        <div className={`p-4 rounded-xl border-2 border-black font-bold text-sm ${
                                            message.type === 'success' ? 'bg-[#c8e6cd] text-green-950' : 'bg-[#efd4d4] text-red-950'
                                        }`}>
                                            {message.text}
                                        </div>
                                    )}

                                    {/* Students List */}
                                    <div className="space-y-4">
                                        <h4 className="font-black text-sm uppercase tracking-wider text-gray-700">Listado de Asistencia</h4>
                                        {loadingAlumnos ? (
                                            <p className="text-center py-6 text-gray-500 font-bold">Cargando lista de alumnos...</p>
                                        ) : alumnos.length === 0 ? (
                                            <div className="bg-[#f7f7f5] p-6 rounded-2xl border-2 border-black text-center font-bold text-gray-500">
                                                No hay alumnos registrados en este grupo. Sube una lista en CSV.
                                            </div>
                                        ) : (
                                            <div className="border-2 border-black rounded-2xl overflow-hidden divide-y-2 divide-black">
                                                {alumnos.map((alumno) => (
                                                    <div key={alumno.id} className="flex items-center justify-between p-4 hover:bg-gray-50 bg-white">
                                                        <div className="flex items-center gap-4">
                                                            <span className="w-8 h-8 flex items-center justify-center font-black text-sm bg-[#f4ecd6] border-2 border-black rounded-lg">
                                                                {alumno.numero_lista}
                                                            </span>
                                                            <div>
                                                                <div className="font-bold">
                                                                    {alumno.nombre} {alumno.apellido_paterno} {alumno.apellido_materno}
                                                                </div>
                                                                {(alumno.numero_celular || alumno.edad) && (
                                                                    <div className="text-[11px] font-semibold text-gray-500 mt-0.5">
                                                                        {alumno.numero_celular && <span>Celular: {alumno.numero_celular}</span>}
                                                                        {alumno.numero_celular && alumno.edad && <span className="mx-1.5">|</span>}
                                                                        {alumno.edad && <span>Edad: {alumno.edad} años</span>}
                                                                    </div>
                                                                )}
                                                            </div>
                                                        </div>
                                                        <button
                                                            onClick={() => handleDeleteAlumno(alumno.id)}
                                                            className="p-2 text-gray-500 hover:text-red-600 hover:bg-[#efd4d4] hover:border-black border-2 border-transparent rounded-lg transition-all"
                                                        >
                                                            <Trash2 className="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="bg-[#f7f7f5] border-2 border-black border-dashed p-12 rounded-2xl text-center space-y-4">
                                <div className="w-12 h-12 bg-white flex items-center justify-center rounded-xl mx-auto border-2 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                                    <Info className="text-gray-400 w-6 h-6" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-black">Selecciona un Grupo</h3>
                                    <p className="text-gray-500 font-medium text-sm mt-1 max-w-xs mx-auto">
                                        Selecciona un grupo de la lista de la izquierda para ver su información y gestionar sus alumnos.
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Modals */}
                {/* Generation Modal */}
                {showGenModal && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
                        <div className="bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] max-w-sm w-full overflow-hidden">
                            <div className="bg-[#f4ecd6] p-5 border-b-2 border-black font-black uppercase tracking-wider text-sm">
                                Registrar Nueva Generación
                            </div>
                            <form onSubmit={handleCreateGen} className="p-6 space-y-4">
                                <div className="space-y-2">
                                    <label className="block text-xs font-black uppercase text-gray-600">Año de Inicio</label>
                                    <input
                                        type="number"
                                        required
                                        min="2020"
                                        max="2100"
                                        placeholder="Ej. 2026"
                                        value={newGenStart}
                                        onChange={(e) => setNewGenStart(e.target.value)}
                                        className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="block text-xs font-black uppercase text-gray-600">Año de Cierre</label>
                                    <input
                                        type="number"
                                        required
                                        min="2020"
                                        max="2100"
                                        placeholder="Ej. 2027"
                                        value={newGenEnd}
                                        onChange={(e) => setNewGenEnd(e.target.value)}
                                        className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                    />
                                </div>
                                <div className="flex gap-3 justify-end pt-2">
                                    <button
                                        type="button"
                                        onClick={() => setShowGenModal(false)}
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Crear
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}

                {/* Grupo Modal */}
                {showGrupoModal && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
                        <div className="bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] max-w-sm w-full overflow-hidden">
                            <div className="bg-[#c5b0f4] p-5 border-b-2 border-black font-black uppercase tracking-wider text-sm">
                                Registrar Nuevo Grupo
                            </div>
                            <form onSubmit={handleCreateGrupo} className="p-6 space-y-4">
                                <div className="space-y-2">
                                    <label className="block text-xs font-black uppercase text-gray-600">Generación del Grupo</label>
                                    <select
                                        value={newGrupoGenId}
                                        onChange={(e) => setNewGrupoGenId(e.target.value)}
                                        className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                    >
                                        {generaciones.map((gen) => (
                                            <option key={gen.id} value={gen.id}>
                                                {gen.anio_inicio} - {gen.anio_fin}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="space-y-2">
                                    <label className="block text-xs font-black uppercase text-gray-600">Nombre del Grupo</label>
                                    <input
                                        type="text"
                                        required
                                        maxLength="20"
                                        placeholder="Ej. Grupo 401"
                                        value={newGrupoNombre}
                                        onChange={(e) => setNewGrupoNombre(e.target.value)}
                                        className="w-full p-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                    />
                                </div>
                                <div className="flex gap-3 justify-end pt-2">
                                    <button
                                        type="button"
                                        onClick={() => setShowGrupoModal(false)}
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Crear
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}

                {/* Alumno Manual Modal */}
                {showAlumnoModal && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
                        <div className="bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] max-w-md w-full overflow-hidden">
                            <div className="bg-[#f4ecd6] p-5 border-b-2 border-black font-black uppercase tracking-wider text-sm">
                                Registrar Alumno Manualmente
                            </div>
                            <form onSubmit={handleCreateAlumno} className="p-6 space-y-4">
                                <div className="grid grid-cols-3 gap-3">
                                    <div className="col-span-1 space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">N° Lista</label>
                                        <input
                                            type="number"
                                            required
                                            min="1"
                                            value={alumnoNumeroLista}
                                            onChange={(e) => setAlumnoNumeroLista(e.target.value)}
                                            className="w-full p-2.5 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50 text-center"
                                        />
                                    </div>
                                    <div className="col-span-2 space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Nombre(s)</label>
                                        <input
                                            type="text"
                                            required
                                            value={alumnoNombre}
                                            onChange={(e) => setAlumnoNombre(e.target.value)}
                                            className="w-full p-2.5 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-3">
                                    <div className="space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Ap. Paterno</label>
                                        <input
                                            type="text"
                                            required
                                            value={alumnoPaterno}
                                            onChange={(e) => setAlumnoPaterno(e.target.value)}
                                            className="w-full p-2.5 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Ap. Materno</label>
                                        <input
                                            type="text"
                                            value={alumnoMaterno}
                                            onChange={(e) => setAlumnoMaterno(e.target.value)}
                                            className="w-full p-2.5 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-3 gap-3">
                                    <div className="col-span-1 space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Edad</label>
                                        <input
                                            type="number"
                                            min="5"
                                            max="120"
                                            value={alumnoEdad}
                                            onChange={(e) => setAlumnoEdad(e.target.value)}
                                            className="w-full p-2.5 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50 text-center"
                                        />
                                    </div>
                                    <div className="col-span-2 space-y-2">
                                        <label className="block text-xs font-black uppercase text-gray-600">Número de Celular</label>
                                        <input
                                            type="tel"
                                            value={alumnoCelular}
                                            onChange={(e) => setAlumnoCelular(e.target.value)}
                                            placeholder="10 dígitos"
                                            className="w-full p-2.5 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                        />
                                    </div>
                                </div>

                                <div className="flex gap-3 justify-end pt-2">
                                    <button
                                        type="button"
                                        onClick={() => setShowAlumnoModal(false)}
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                    >
                                        Guardar Alumno
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}

                {/* QR Projection Modal */}
                {showQrModal && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 animate-fade-in">
                        <div className="bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] max-w-sm w-full overflow-hidden text-center p-6 space-y-6">
                            <div className="space-y-1">
                                <h3 className="text-xl font-black">QR de Autoinscripción</h3>
                                <p className="text-xs font-bold text-gray-500 uppercase">Grupo {selectedGrupo.nombre}</p>
                            </div>

                            <div className="bg-white border-2 border-black p-4 rounded-2xl inline-block shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                                <img
                                    src={`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(
                                        window.location.origin + "/grupos/" + selectedGrupo.id + "/inscribirse"
                                    )}`}
                                    alt="Código QR de Inscripción"
                                    className="w-48 h-48 mx-auto"
                                />
                            </div>

                            <div className="space-y-2">
                                <p className="text-xs font-bold text-gray-600">
                                    Los alumnos pueden escanear este código con su celular para darse de alta e inscribirse al grupo.
                                </p>
                                <div className="flex items-center gap-2 bg-gray-50 border border-gray-200 p-2 rounded-lg text-xs font-bold text-gray-500 overflow-hidden">
                                    <span className="truncate flex-grow">{window.location.origin + "/grupos/" + selectedGrupo.id + "/inscribirse"}</span>
                                    <button
                                        onClick={() => {
                                            navigator.clipboard.writeText(window.location.origin + "/grupos/" + selectedGrupo.id + "/inscribirse");
                                            setCopied(true);
                                            setTimeout(() => setCopied(false), 2000);
                                        }}
                                        className="p-1.5 border border-black rounded hover:bg-gray-100 text-black shrink-0"
                                    >
                                        <Copy className="w-3.5 h-3.5" />
                                    </button>
                                </div>
                                {copied && <p className="text-[10px] text-emerald-600 font-extrabold">¡Enlace copiado al portapapeles!</p>}
                            </div>

                            <div className="pt-2">
                                <button
                                    onClick={() => setShowQrModal(false)}
                                    className="w-full py-2.5 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm"
                                >
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
