import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { UserPlus, CheckCircle, AlertCircle, Phone, ArrowRight, RefreshCw, KeyRound } from 'lucide-react';

export default function InscribirseGrupo() {
    const { grupoId } = useParams();

    // Group info
    const [grupoInfo, setGrupoInfo] = useState(null);
    const [loadingGrupo, setLoadingGrupo] = useState(true);

    // Form fields
    const [nombre, setNombre] = useState('');
    const [apellidoPaterno, setApellidoPaterno] = useState('');
    const [apellidoMaterno, setApellidoMaterno] = useState('');
    const [edad, setEdad] = useState('');
    const [numeroCelular, setNumeroCelular] = useState('');
    const [celularUltimosCuatro, setCelularUltimosCuatro] = useState('');

    // UI States
    const [submitting, setSubmitting] = useState(false);
    const [errorMsg, setErrorMsg] = useState(null);
    const [successData, setSuccessData] = useState(null);
    const [requiereValidacion, setRequiereValidacion] = useState(false);

    // Load group details
    useEffect(() => {
        const fetchGrupo = async () => {
            try {
                const res = await fetch(`/api/public/sesiones/buscar/dummy`); // Wait, we don't have a public get-group endpoint, but we can call a new public endpoint or load group name on login/qr. Let's make a request to a public endpoint to get group name or just search it. Wait! Let's see if we have a public group endpoint. We don't, but we can fetch it, or wait: AlumnoController inscribirPorQR is public, we can fetch group name or we can add a public endpoint or just let the student submit and get group details.
                // Let's create a public endpoint to fetch basic group info or just display "Inscripción al Grupo" and fetch it.
                // Wait! Let's check: we can fetch the group name from `/api/public/grupos/{grupoId}` or we can create a tiny public route for it.
                // Let's see if we can fetch from a public route, or we can just fetch and handle. Let's write a public route or fetch group details directly.
                // Let's call a public endpoint `/api/public/grupos/{grupoId}` to get the group details.
                const resGroup = await fetch(`/api/public/grupos/${grupoId}`);
                if (resGroup.ok) {
                    const data = await resGroup.json();
                    setGrupoInfo(data);
                } else {
                    setErrorMsg("No se pudo cargar la información del grupo.");
                }
            } catch (e) {
                console.error(e);
                setErrorMsg("Error de red al obtener detalles del grupo.");
            } finally {
                setLoadingGrupo(false);
            }
        };

        fetchGrupo();
    }, [grupoId]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrorMsg(null);
        setSubmitting(true);

        try {
            const bodyPayload = {
                nombre,
                apellido_paterno: apellidoPaterno,
                apellido_materno: apellidoMaterno || null,
                edad: edad ? parseInt(edad) : null,
                numero_celular: numeroCelular,
            };

            if (requiereValidacion) {
                bodyPayload.celular_ultimos_cuatro = celularUltimosCuatro;
            }

            const res = await fetch(`/api/public/grupos/${grupoId}/inscribirse`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(bodyPayload)
            });

            const data = await res.json();

            if (res.ok) {
                setSuccessData(data);
                setErrorMsg(null);
            } else if (res.status === 422 && data.requiere_validacion) {
                setRequiereValidacion(true);
                setErrorMsg(data.message);
            } else {
                setErrorMsg(data.message || "Error al procesar la inscripción.");
            }
        } catch (err) {
            console.error(err);
            setErrorMsg("Error de red al conectar con el servidor.");
        } finally {
            setSubmitting(false);
        }
    };

    if (loadingGrupo) {
        return (
            <div className="min-h-screen bg-[#fcfcfa] flex items-center justify-center">
                <div className="text-center space-y-4">
                    <RefreshCw className="w-10 h-10 animate-spin mx-auto text-purple-600" />
                    <p className="font-bold text-gray-500">Cargando detalles del grupo...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-[#fcfcfa] flex flex-col items-center justify-center p-4">
            {/* Logo Header */}
            <div className="text-center mb-8 space-y-2">
                <h1 className="text-3xl font-black tracking-tight text-black">Asesor de Estudios de Matemáticas</h1>
                <p className="text-gray-500 font-medium">Registro Escolar de Alumnos</p>
            </div>

            {/* Form Card */}
            <div className="w-full max-w-md bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                {/* Banner */}
                <div className="bg-[#dceeb1] p-6 border-b-4 border-black text-center">
                    <UserPlus className="w-12 h-12 mx-auto text-black mb-2" />
                    <h2 className="text-2xl font-black">Inscripción al Curso</h2>
                    <p className="text-sm font-bold text-lime-950 uppercase mt-1">
                        Grupo: {grupoInfo ? grupoInfo.nombre : 'Cargando...'}
                    </p>
                </div>

                <div className="p-6 space-y-6">
                    {errorMsg && (
                        <div className="bg-rose-50 border-2 border-rose-500 text-rose-700 p-4 rounded-2xl font-bold text-sm flex items-center gap-3">
                            <AlertCircle className="w-5 h-5 shrink-0" />
                            <span>{errorMsg}</span>
                        </div>
                    )}

                    {successData ? (
                        <div className="text-center space-y-6">
                            <div className="bg-[#c8e6cd] border-2 border-black p-4 rounded-2xl inline-flex items-center gap-2 font-black text-sm text-green-950">
                                <CheckCircle className="w-5 h-5 text-emerald-700" fill="white" />
                                <span>¡Inscripción Exitosa!</span>
                            </div>

                            <div className="space-y-2 bg-[#fcfcfa] border-2 border-black p-5 rounded-2xl">
                                <h3 className="text-xl font-black text-black">
                                    {successData.alumno.nombre} {successData.alumno.apellido_paterno}
                                </h3>
                                <p className="text-sm font-bold text-gray-500">
                                    Te has registrado correctamente en el grupo <strong>{grupoInfo?.nombre}</strong>.
                                </p>
                                <div className="pt-4 flex flex-col items-center">
                                    <span className="text-xs font-bold text-gray-400 uppercase tracking-widest">Tu Número de Lista es:</span>
                                    <span className="text-4xl font-black text-purple-600 bg-white border-2 border-black px-6 py-2 rounded-xl mt-2 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                                        {successData.alumno.numero_lista}
                                    </span>
                                </div>
                            </div>

                            <div className="bg-yellow-50 border-2 border-black p-4 rounded-2xl text-xs font-bold text-yellow-800 text-left">
                                <p className="font-black text-sm mb-1 uppercase tracking-widest">Información de Acceso:</p>
                                <ul className="list-disc list-inside space-y-1 mt-1 text-gray-700">
                                    <li>Para ingresar a resolver exámenes en clase, usarás tu nombre y confirmarás con los últimos 4 dígitos de tu celular.</li>
                                    <li>Tu celular registrado es: <strong>{numeroCelular}</strong></li>
                                </ul>
                            </div>

                            <div className="pt-2">
                                <Link
                                    to="/"
                                    className="w-full inline-block text-center py-3.5 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 transition-all text-sm"
                                >
                                    Ir al Portal de Alumnos
                                </Link>
                            </div>
                        </div>
                    ) : (
                        <form onSubmit={handleSubmit} className="space-y-4">
                            {!requiereValidacion ? (
                                <>
                                    <div className="space-y-1">
                                        <label className="block text-xs font-black uppercase tracking-wider text-gray-700">Nombre(s)</label>
                                        <input
                                            type="text"
                                            required
                                            value={nombre}
                                            onChange={(e) => setNombre(e.target.value)}
                                            placeholder="Ej. Juan Carlos"
                                            className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50"
                                        />
                                    </div>

                                    <div className="grid grid-cols-2 gap-4">
                                        <div className="space-y-1">
                                            <label className="block text-xs font-black uppercase tracking-wider text-gray-700">A. Paterno</label>
                                            <input
                                                type="text"
                                                required
                                                value={apellidoPaterno}
                                                onChange={(e) => setApellidoPaterno(e.target.value)}
                                                placeholder="Ej. Pérez"
                                                className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50"
                                            />
                                        </div>
                                        <div className="space-y-1">
                                            <label className="block text-xs font-black uppercase tracking-wider text-gray-700">A. Materno</label>
                                            <input
                                                type="text"
                                                value={apellidoMaterno}
                                                onChange={(e) => setApellidoMaterno(e.target.value)}
                                                placeholder="Ej. Gómez"
                                                className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50"
                                            />
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-3 gap-4">
                                        <div className="col-span-1 space-y-1">
                                            <label className="block text-xs font-black uppercase tracking-wider text-gray-700">Edad</label>
                                            <input
                                                type="number"
                                                min="5"
                                                max="120"
                                                value={edad}
                                                onChange={(e) => setEdad(e.target.value)}
                                                placeholder="15"
                                                className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 text-center"
                                            />
                                        </div>
                                        <div className="col-span-2 space-y-1">
                                            <label className="block text-xs font-black uppercase tracking-wider text-gray-700">Número de Celular</label>
                                            <input
                                                type="tel"
                                                required
                                                value={numeroCelular}
                                                onChange={(e) => setNumeroCelular(e.target.value)}
                                                placeholder="10 dígitos"
                                                className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50"
                                            />
                                        </div>
                                    </div>
                                </>
                            ) : (
                                <div className="bg-yellow-50 border-2 border-black p-5 rounded-2xl space-y-4">
                                    <div className="flex items-start gap-3">
                                        <KeyRound className="w-5 h-5 text-yellow-800 shrink-0 mt-0.5" />
                                        <div>
                                            <h4 className="font-black text-sm text-yellow-900">Validación de Identidad</h4>
                                            <p className="text-xs text-yellow-800 font-semibold mt-1">
                                                Para confirmar que deseas registrarte en este grupo, ingresa los últimos 4 dígitos de tu número celular:
                                            </p>
                                        </div>
                                    </div>

                                    <div className="space-y-1">
                                        <label className="block text-xs font-black uppercase tracking-wider text-gray-700 text-center">Últimos 4 dígitos</label>
                                        <input
                                            type="password"
                                            maxLength="4"
                                            required
                                            pattern="[0-9]{4}"
                                            value={celularUltimosCuatro}
                                            onChange={(e) => setCelularUltimosCuatro(e.target.value.replace(/[^0-9]/g, ''))}
                                            placeholder="••••"
                                            className="w-32 mx-auto block border-2 border-black rounded-xl p-3 font-bold text-center text-xl tracking-widest focus:outline-none focus:bg-gray-50"
                                        />
                                    </div>
                                </div>
                            )}

                            <div className="flex gap-3 pt-2">
                                {requiereValidacion && (
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setRequiereValidacion(false);
                                            setErrorMsg(null);
                                        }}
                                        className="w-1/3 py-3.5 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 transition-all text-sm"
                                    >
                                        Atrás
                                    </button>
                                )}
                                <button
                                    type="submit"
                                    disabled={submitting}
                                    className={`flex-grow flex items-center justify-center gap-2 py-3.5 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 text-sm`}
                                >
                                    {submitting ? (
                                        <>
                                            <RefreshCw className="w-4 h-4 animate-spin" />
                                            Registrando...
                                        </>
                                    ) : (
                                        <>
                                            {requiereValidacion ? 'Confirmar Inscripción' : 'Inscribirse al Grupo'}
                                            <ArrowRight className="w-4 h-4" />
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
