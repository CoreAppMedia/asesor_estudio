import React, { useEffect, useState } from 'react';
import { useSearchParams, useNavigate, Link } from 'react-router-dom';
import { KeyRound, Users, GraduationCap, Clipboard, ArrowRight, AlertCircle, RefreshCw } from 'lucide-react';

export default function AccesoSesion() {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    // States
    const [codigo, setCodigo] = useState('');
    const [verificando, setVerificando] = useState(false);
    const [sesionData, setSesionData] = useState(null);
    const [alumnoId, setAlumnoId] = useState('');
    const [celularUltimosCuatro, setCelularUltimosCuatro] = useState('');
    const [iniciando, setIniciando] = useState(false);
    const [errorMsg, setErrorMsg] = useState(null);
    const [intentoIniciado, setIntentoIniciado] = useState(null);

    // Prefill code from URL parameter '?code=XXXXX'
    useEffect(() => {
        const codeParam = searchParams.get('code');
        if (codeParam) {
            setCodigo(codeParam.toUpperCase());
            handleBuscarSesion(codeParam.toUpperCase());
        }
    }, [searchParams]);

    const handleBuscarSesion = async (codeToSearch) => {
        const searchCode = codeToSearch || codigo;
        if (!searchCode || searchCode.trim().length === 0) {
            setErrorMsg('Por favor ingresa un código de acceso.');
            return;
        }

        setVerificando(true);
        setErrorMsg(null);
        setSesionData(null);
        setAlumnoId('');

        try {
            const res = await fetch(`/api/public/sesiones/buscar/${searchCode.trim()}`);
            const data = await res.json();

            if (res.ok) {
                setSesionData(data);
            } else {
                setErrorMsg(data.message || 'Código de sesión no válido o inactivo.');
            }
        } catch (e) {
            console.error(e);
            setErrorMsg('Error de red al conectar con el servidor.');
        } finally {
            setVerificando(false);
        }
    };

    const handleIniciarExamen = async (e) => {
        e.preventDefault();
        setErrorMsg(null);

        if (!alumnoId) {
            setErrorMsg('Por favor selecciona tu nombre de la lista.');
            return;
        }

        if (!celularUltimosCuatro || celularUltimosCuatro.length !== 4) {
            setErrorMsg('Por favor ingresa los últimos 4 dígitos de tu celular.');
            return;
        }

        setIniciando(true);
        try {
            const res = await fetch('/api/public/sesiones/iniciar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    codigo_acceso: sesionData.codigo_acceso,
                    alumno_id: parseInt(alumnoId),
                    celular_ultimos_cuatro: celularUltimosCuatro
                })
            });

            const data = await res.json();

            if (res.ok) {
                // Guardar la información del intento iniciado en localStorage
                localStorage.setItem('alumno_intento_activo', JSON.stringify({
                    intento_id: data.intento_id,
                    session_token: data.session_token,
                    celular_ultimos_cuatro: celularUltimosCuatro,
                    alumno_id: data.alumno.id,
                    alumno_nombre: data.alumno.nombre,
                    evaluacion_nombre: data.evaluacion.nombre,
                    iniciado_at: data.iniciado_at,
                    codigo_acceso: sesionData.codigo_acceso,
                    tiempo_limite_minutos: data.evaluacion.tiempo_limite_minutos
                }));

                navigate('/sesion/resolver');
            } else {
                setErrorMsg(data.message || 'No se pudo iniciar la evaluación.');
            }
        } catch (err) {
            console.error(err);
            setErrorMsg('Error de red al iniciar la sesión de examen.');
        } finally {
            setIniciando(false);
        }
    };

    return (
        <div className="min-h-screen bg-[#fcfcfa] flex flex-col items-center justify-center p-4">
            {/* Top Logo branding */}
            <div className="text-center mb-8 space-y-2">
                <div className="inline-flex items-center gap-2 px-3 py-1 border-2 border-black rounded-full bg-[#f4ecd6] font-bold text-xs">
                    <GraduationCap className="w-4 h-4" /> CCH - UNAM
                </div>
                <h1 className="text-3xl font-black tracking-tight text-black">Asesor de Estudios de Matemáticas</h1>
                <p className="text-gray-500 font-medium">Evaluaciones del Curso en el Aula</p>
            </div>

            {/* Main Interactive Card */}
            <div className="w-full max-w-md bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                {/* Upper banner card */}
                <div className="bg-[#c5b0f4] p-6 border-b-4 border-black text-center">
                    <KeyRound className="w-12 h-12 mx-auto text-black mb-2" />
                    <h2 className="text-2xl font-black">Acceso a Evaluación</h2>
                </div>

                <div className="p-6 space-y-6">
                    {errorMsg && (
                        <div className="bg-rose-50 border-2 border-rose-500 text-rose-700 p-4 rounded-2xl font-bold text-sm flex items-center gap-3 animate-shake">
                            <AlertCircle className="w-5 h-5 shrink-0" />
                            <span>{errorMsg}</span>
                        </div>
                    )}

                    {/* Step 1: Input Session Code */}
                    {!sesionData && !intentoIniciado && (
                        <div className="space-y-4">
                            <div className="space-y-1">
                                <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Código de la Sesión</label>
                                <input
                                    type="text"
                                    value={codigo}
                                    onChange={(e) => setCodigo(e.target.value.toUpperCase())}
                                    placeholder="Ej. B3R78"
                                    maxLength="6"
                                    className="w-full border-2 border-black rounded-xl p-4 font-mono font-black text-2xl tracking-widest text-center uppercase focus:outline-none focus:bg-gray-50"
                                />
                            </div>

                            <button
                                onClick={() => handleBuscarSesion()}
                                disabled={verificando}
                                className="w-full flex items-center justify-center gap-2 py-4 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50"
                            >
                                {verificando ? (
                                    <>
                                        <RefreshCw className="w-5 h-5 animate-spin" />
                                        Verificando código...
                                    </>
                                ) : (
                                    <>
                                        Ingresar a la Sesión
                                        <ArrowRight className="w-5 h-5" />
                                    </>
                                )}
                            </button>
                        </div>
                    )}

                    {/* Step 2: Session details found. Let the student select their list number/name */}
                    {sesionData && !intentoIniciado && (
                        <form onSubmit={handleIniciarExamen} className="space-y-5">
                            {/* Exam detail box */}
                            <div className="bg-[#f0f9ff] border-2 border-black p-4 rounded-2xl font-bold text-xs text-sky-900 space-y-2">
                                <div className="flex items-center gap-2">
                                    <Clipboard className="w-4 h-4 text-sky-600" />
                                    <span>Examen: <strong className="text-black text-sm block">{sesionData.evaluacion.nombre}</strong></span>
                                </div>
                                <div className="flex items-center justify-between border-t border-sky-200 pt-2 mt-1">
                                    <span>Grupo: <strong>{sesionData.grupo}</strong></span>
                                    <span>Límite: <strong>{sesionData.evaluacion.tiempo_limite_minutos} minutos</strong></span>
                                    <span>Reactivos: <strong>{sesionData.evaluacion.total_preguntas}</strong></span>
                                </div>
                            </div>

                            <div className="space-y-1">
                                <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Selecciona tu Nombre de la Lista</label>
                                <select
                                    value={alumnoId}
                                    onChange={(e) => setAlumnoId(e.target.value)}
                                    className="w-full border-2 border-black rounded-xl p-3 font-bold focus:outline-none focus:bg-gray-50 max-h-48 overflow-y-auto"
                                >
                                    <option value="">-- Buscar en la lista --</option>
                                    {sesionData.alumnos.map((al) => (
                                        <option key={al.id} value={al.id}>
                                            [{al.numero_lista}] {al.apellido}, {al.nombre}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            {alumnoId && (
                                <div className="space-y-1 animate-fadeIn">
                                    <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Últimos 4 dígitos de tu celular</label>
                                    <input
                                        type="text"
                                        pattern="[0-9]*"
                                        inputMode="numeric"
                                        maxLength="4"
                                        value={celularUltimosCuatro}
                                        onChange={(e) => setCelularUltimosCuatro(e.target.value.replace(/\D/g, ''))}
                                        placeholder="Ej. 5678"
                                        className="w-full border-2 border-black rounded-xl p-3 font-mono font-black text-xl text-center focus:outline-none focus:bg-gray-50"
                                        required
                                    />
                                </div>
                            )}

                            <div className="flex gap-3">
                                <button
                                    type="button"
                                    onClick={() => setSesionData(null)}
                                    className="w-1/3 py-3 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 active:translate-y-0.5 transition-all text-center text-sm"
                                >
                                    Volver
                                </button>
                                <button
                                    type="submit"
                                    disabled={iniciando}
                                    className="w-2/3 flex items-center justify-center gap-2 py-3 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 text-sm"
                                >
                                    {iniciando ? (
                                        <>
                                            <RefreshCw className="w-4 h-4 animate-spin" />
                                            Iniciando...
                                        </>
                                    ) : (
                                        <>
                                            Iniciar Examen
                                            <ArrowRight className="w-4 h-4" />
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    )}

                    {/* Step 3: Success placeholder screen for Phase 5 */}
                    {intentoIniciado && (
                        <div className="text-center space-y-6">
                            <div className="bg-[#dceeb1] border-2 border-black p-4 rounded-2xl inline-flex items-center gap-2 font-black text-sm">
                                <CheckCircle className="w-5 h-5 text-emerald-700" fill="white" />
                                <span>¡Acceso Concedido!</span>
                            </div>

                            <div className="space-y-2">
                                <h3 className="text-xl font-black text-black">Hola, {intentoIniciado.alumno.nombre}</h3>
                                <p className="text-sm font-bold text-gray-500">
                                    Tu examen <strong>"{intentoIniciado.evaluacion.nombre}"</strong> ha comenzado.
                                </p>
                            </div>

                            <div className="bg-yellow-50 border-2 border-black p-4 rounded-2xl text-xs font-bold text-yellow-800 text-left">
                                <p className="font-black text-sm mb-1 uppercase tracking-widest">Información importante:</p>
                                <ul className="list-disc list-inside space-y-1 mt-1 text-gray-700">
                                    <li>Tienes <strong>{sesionData?.evaluacion.tiempo_limite_minutos} minutos</strong> para resolverlo.</li>
                                    <li>Si se cierra el navegador accidentalmente, vuelve a escanear el código QR para reanudar.</li>
                                    <li>Las preguntas se guardarán en el servidor cuando se implemente el motor de examen en la siguiente fase (Fase 6).</li>
                                </ul>
                            </div>

                            <div className="border-t-2 border-black pt-4">
                                <p className="text-xs text-gray-400 font-bold italic">
                                    (El simulador dinámico del examen se integrará en la Fase 6: Evaluaciones y Calificaciones Síncronas)
                                </p>
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {/* Back link */}
            <div className="mt-8">
                <Link to="/" className="text-xs font-black text-gray-500 uppercase tracking-widest hover:text-black transition-colors underline">
                    Volver al Portal de Alumnos
                </Link>
            </div>
        </div>
    );
}
