import React, { useEffect, useState, useRef } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Clock, CheckCircle, ArrowLeft, ArrowRight, AlertTriangle, RefreshCw, Send, BookOpen } from 'lucide-react';
import MathView from '../components/MathView';

export default function ResolverSesion() {
    const navigate = useNavigate();

    // Session status
    const [intentoInfo, setIntentoInfo] = useState(null);
    const [preguntas, setPreguntas] = useState([]);
    const [respuestas, setRespuestas] = useState({}); // { pregunta_id: 'A' or text }
    const [currentIndex, setCurrentIndex] = useState(0);

    // Time & Timer
    const [timeLeft, setTimeLeft] = useState(null); // in seconds
    const timerRef = useRef(null);

    // UI States
    const [loading, setLoading] = useState(true);
    const [savingStatus, setSavingStatus] = useState({}); // { pregunta_id: 'saving' | 'saved' | 'error' }
    const [errorMsg, setErrorMsg] = useState(null);
    const [evaluado, setEvaluado] = useState(false);
    const [showConfirmSubmit, setShowConfirmSubmit] = useState(false);
    const [celularUltimosCuatroSubmit, setCelularUltimosCuatroSubmit] = useState('');

    // Prevent accidental unload
    useEffect(() => {
        const handleBeforeUnload = (e) => {
            if (!evaluado) {
                e.preventDefault();
                e.returnValue = '¿Estás seguro de que deseas salir? Tus respuestas actuales se guardarán, pero el tiempo del examen seguirá corriendo.';
            }
        };
        window.addEventListener('beforeunload', handleBeforeUnload);
        return () => window.removeEventListener('beforeunload', handleBeforeUnload);
    }, [evaluado]);

    // Load active attempt from localStorage and sync questions from backend
    useEffect(() => {
        const stored = localStorage.getItem('alumno_intento_activo');
        if (!stored) {
            navigate('/acceso');
            return;
        }

        const info = JSON.parse(stored);
        setIntentoInfo(info);

        const syncAttempt = async () => {
            try {
                // El endpoint 'iniciar' sirve también para reanudar/cargar preguntas
                const res = await fetch('/api/public/sesiones/iniciar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        codigo_acceso: info.codigo_acceso,
                        alumno_id: info.alumno_id,
                        celular_ultimos_cuatro: info.celular_ultimos_cuatro
                    })
                });

                const data = await res.json();

                if (res.ok) {
                    setPreguntas(data.preguntas || []);

                    // Inicializar respuestas guardadas
                    const savedAnswers = {};
                    data.preguntas.forEach(p => {
                        savedAnswers[p.id] = p.respuesta_guardada || '';
                    });
                    setRespuestas(savedAnswers);

                    // *** CRÍTICO: actualizar intentoInfo con los datos frescos del backend ***
                    // Esto asegura que intento_id y session_token sean los correctos
                    const updatedInfo = {
                        ...info,
                        intento_id: data.intento_id,
                        session_token: data.session_token,
                        iniciado_at: data.iniciado_at,
                    };
                    setIntentoInfo(updatedInfo);
                    // Actualizar localStorage con los datos frescos también
                    localStorage.setItem('alumno_intento_activo', JSON.stringify(updatedInfo));

                    // Inicializar cronómetro basado en iniciado_at y tiempo_limite_minutos
                    const startTime = new Date(data.iniciado_at).getTime();
                    const limitTimeMs = data.evaluacion.tiempo_limite_minutos * 60 * 1000;
                    const endTime = startTime + limitTimeMs;

                    const calculateTimeLeft = () => {
                        const diff = Math.floor((endTime - Date.now()) / 1000);
                        if (diff <= 0) {
                            setTimeLeft(0);
                            clearInterval(timerRef.current);
                            handleAutoSubmit();
                        } else {
                            setTimeLeft(diff);
                        }
                    };

                    calculateTimeLeft();
                    timerRef.current = setInterval(calculateTimeLeft, 1000);

                } else {
                    setErrorMsg(data.message || 'Error al sincronizar el examen.');
                }
            } catch (err) {
                console.error(err);
                setErrorMsg('Error de red al cargar el examen.');
            } finally {
                setLoading(false);
            }
        };

        syncAttempt();

        return () => {
            if (timerRef.current) clearInterval(timerRef.current);
        };
    }, []);

    // Format time left (mm:ss)
    const formatTime = (seconds) => {
        if (seconds === null) return '--:--';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    // Auto submit when time runs out
    const handleAutoSubmit = async () => {
        setLoading(true);
        setErrorMsg('El tiempo límite ha expirado. Enviando tus respuestas...');
        try {
            const stored = localStorage.getItem('alumno_intento_activo');
            if (stored) {
                const info = JSON.parse(stored);
                await fetch(`/api/public/intentos/${info.intento_id}/finalizar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Session-Token': info.session_token
                    },
                    body: JSON.stringify({
                        celular_ultimos_cuatro: info.celular_ultimos_cuatro
                    })
                });
            }
            localStorage.removeItem('alumno_intento_activo');
            setEvaluado(true);
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
        }
    };

    // Save partial answer to database
    const saveAnswer = async (preguntaId, val) => {
        setSavingStatus(prev => ({ ...prev, [preguntaId]: 'saving' }));
        try {
            const res = await fetch(`/api/public/intentos/${intentoInfo.intento_id}/respuesta`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Session-Token': intentoInfo.session_token
                },
                body: JSON.stringify({
                    pregunta_id: preguntaId,
                    respuesta: val
                })
            });

            if (res.ok) {
                setSavingStatus(prev => ({ ...prev, [preguntaId]: 'saved' }));
            } else {
                const data = await res.json();
                setSavingStatus(prev => ({ ...prev, [preguntaId]: 'error' }));
                setErrorMsg(data.message || 'La respuesta no pudo guardarse.');
            }
        } catch (e) {
            console.error(e);
            setSavingStatus(prev => ({ ...prev, [preguntaId]: 'error' }));
        }
    };

    const handleSelectOption = (preguntaId, optionLetter) => {
        if (evaluado) return;
        setRespuestas(prev => ({ ...prev, [preguntaId]: optionLetter }));
        saveAnswer(preguntaId, optionLetter);
    };

    const handleOpenTextChange = (preguntaId, text) => {
        if (evaluado) return;
        setRespuestas(prev => ({ ...prev, [preguntaId]: text }));
    };

    const handleOpenTextBlur = (preguntaId) => {
        saveAnswer(preguntaId, respuestas[preguntaId]);
    };

    // Finalize exam manually
    const handleSubmitExamen = async () => {
        if (!celularUltimosCuatroSubmit || celularUltimosCuatroSubmit.length !== 4) {
            setErrorMsg('Por favor ingresa los últimos 4 dígitos de tu celular para validar la entrega.');
            setShowConfirmSubmit(false);
            return;
        }
        setLoading(true);
        setErrorMsg(null);
        try {
            const res = await fetch(`/api/public/intentos/${intentoInfo.intento_id}/finalizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Session-Token': intentoInfo.session_token
                },
                body: JSON.stringify({
                    celular_ultimos_cuatro: celularUltimosCuatroSubmit
                })
            });
 
             if (res.ok) {
                 localStorage.removeItem('alumno_intento_activo');
                 setEvaluado(true);
                 setShowConfirmSubmit(false);
             } else {
                 const data = await res.json();
                 setErrorMsg(data.message || 'Error al enviar el examen.');
                 setShowConfirmSubmit(false);
             }
         } catch (err) {
             console.error(err);
             setErrorMsg('Error de red al entregar el examen.');
             setShowConfirmSubmit(false);
         } finally {
            setLoading(false);
        }
    };

    if (loading && preguntas.length === 0) {
        return (
            <div className="min-h-screen bg-[#fcfcfa] flex items-center justify-center">
                <div className="text-center space-y-4">
                    <RefreshCw className="w-10 h-10 animate-spin mx-auto text-purple-600" />
                    <p className="font-bold text-gray-500">Cargando evaluación y sincronizando preguntas...</p>
                </div>
            </div>
        );
    }

    if (evaluado) {
        return (
            <div className="min-h-screen bg-[#fcfcfa] flex flex-col items-center justify-center p-4">
                <div className="w-full max-w-md bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 text-center space-y-6">
                    <div className="bg-[#dceeb1] border-2 border-black p-4 rounded-2xl inline-flex items-center gap-2 font-black text-sm">
                        <CheckCircle className="w-5 h-5 text-emerald-700" fill="white" />
                        <span>¡Examen Entregado!</span>
                    </div>

                    <div className="space-y-2">
                        <h2 className="text-2xl font-black text-black">Gracias por responder</h2>
                        <p className="text-sm font-bold text-gray-500">
                            Tu evaluación ha sido registrada con éxito en el servidor.
                        </p>
                    </div>

                    <div className="bg-blue-50 border-2 border-black p-4 rounded-2xl text-xs font-bold text-sky-800 text-left">
                        <p className="font-black text-sm mb-1 uppercase tracking-widest">¿Qué sigue ahora?</p>
                        <p className="text-gray-700 leading-relaxed mt-1">
                            El profesor revisará tus respuestas y te asignará la calificación definitiva tras revisar todo el grupo. Puedes cerrar esta pestaña con total seguridad.
                        </p>
                    </div>

                    <div className="pt-4 border-t-2 border-black">
                        <Link
                            to="/"
                            className="inline-block px-6 py-2.5 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 transition-all text-sm"
                        >
                            Volver al Portal
                        </Link>
                    </div>
                </div>
            </div>
        );
    }

    const activeQuestion = preguntas[currentIndex];
    const isLastQuestion = currentIndex === preguntas.length - 1;

    return (
        <div className="min-h-screen bg-[#fcfcfa] flex flex-col justify-between text-black font-sans">
            {/* Top Bar */}
            <header className="bg-white border-b-4 border-black py-4 px-6 sticky top-0 z-10 flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 bg-black flex items-center justify-center rounded">
                        <BookOpen className="text-white w-5 h-5" />
                    </div>
                    <div>
                        <h2 className="font-black text-sm uppercase tracking-wider">{intentoInfo?.evaluacion_nombre}</h2>
                        <p className="text-[10px] font-bold text-gray-500">Alumno: {intentoInfo?.alumno_nombre}</p>
                    </div>
                </div>

                {/* Clock Timer */}
                <div className={`flex items-center gap-2 px-4 py-2 border-2 border-black rounded-xl font-black font-mono shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] ${
                    timeLeft !== null && timeLeft < 120 
                        ? 'bg-rose-100 text-rose-700 border-rose-500 animate-pulse' 
                        : 'bg-yellow-100 text-black'
                }`}>
                    <Clock className="w-4 h-4 shrink-0" />
                    <span>{formatTime(timeLeft)}</span>
                </div>
            </header>

            {/* Main content grid */}
            <main className="w-full px-6 md:px-12 p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start flex-grow">
                {/* Left side: Navigation panel (bubbles) */}
                <section className="lg:col-span-3 bg-white border-2 border-black rounded-2xl p-5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <h3 className="font-black text-sm uppercase tracking-widest text-gray-400 mb-4">Preguntas</h3>
                    <div className="grid grid-cols-5 lg:grid-cols-4 gap-2">
                        {preguntas.map((p, idx) => {
                            const isAnswered = respuestas[p.id] !== undefined && respuestas[p.id] !== '';
                            const isActive = idx === currentIndex;
                            
                            return (
                                <button
                                    key={p.id}
                                    onClick={() => setCurrentIndex(idx)}
                                    className={`aspect-square flex items-center justify-center font-bold text-sm border-2 rounded-xl transition-all ${
                                        isActive 
                                            ? 'bg-black text-white border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]'
                                            : isAnswered
                                                ? 'bg-[#dceeb1] border-black text-black'
                                                : 'bg-white border-black/20 text-gray-400 hover:border-black'
                                    }`}
                                >
                                    {idx + 1}
                                </button>
                            );
                        })}
                    </div>
                </section>

                {/* Right side: Active Question Card */}
                {activeQuestion && (
                    <section key={activeQuestion.id} className="lg:col-span-9 bg-white border-4 border-black rounded-3xl p-6 md:p-8 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] space-y-6">
                        {/* Question indicator & status */}
                        <div className="flex items-center justify-between border-b-2 border-black pb-4">
                            <span className="font-black text-lg uppercase tracking-wider text-purple-600">
                                Pregunta {currentIndex + 1} de {preguntas.length}
                            </span>
                            <div className="flex items-center">
                                {savingStatus[activeQuestion.id] === 'saving' && (
                                    <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-yellow-400 bg-yellow-50 text-yellow-700 text-xs font-black uppercase tracking-wide animate-pulse">
                                        <svg className="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                        </svg>
                                        Guardando...
                                    </span>
                                )}
                                {savingStatus[activeQuestion.id] === 'saved' && (
                                    <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-green-500 bg-green-50 text-green-700 text-xs font-black uppercase tracking-wide shadow-[2px_2px_0px_0px_rgba(22,163,74,0.4)]">
                                        <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        ¡Guardado!
                                    </span>
                                )}
                                {savingStatus[activeQuestion.id] === 'error' && (
                                    <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-red-500 bg-red-50 text-red-700 text-xs font-black uppercase tracking-wide">
                                        <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        </svg>
                                        Error al guardar
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* LaTeX Question Statement */}
                        <div className="text-xl font-bold leading-relaxed text-black bg-[#fcfcfa] border-2 border-black p-5 rounded-2xl">
                            <MathView text={activeQuestion.texto_pregunta} />
                        </div>

                        {/* Answers UI */}
                        {activeQuestion.tipo_pregunta === 'opcion_multiple' ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {activeQuestion.opciones_json && Object.entries(activeQuestion.opciones_json).map(([letter, text]) => {
                                    const isSelected = respuestas[activeQuestion.id] === letter;
                                    return (
                                        <button
                                            key={letter}
                                            onClick={() => handleSelectOption(activeQuestion.id, letter)}
                                            className={`p-5 text-left border-2 border-black rounded-2xl flex gap-3 font-bold transition-all shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 ${
                                                isSelected 
                                                    ? 'bg-[#c5b0f4] text-black ring-2 ring-black' 
                                                    : 'bg-white hover:bg-gray-50'
                                            }`}
                                        >
                                            <span className={`w-6 h-6 border-2 border-black rounded-lg flex items-center justify-center text-xs shrink-0 ${
                                                isSelected ? 'bg-black text-white' : 'bg-gray-100'
                                            }`}>
                                                {letter}
                                            </span>
                                            <MathView text={text} className="text-sm" />
                                        </button>
                                    );
                                })}
                            </div>
                        ) : (
                            <div className="space-y-2">
                                <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Escribe tu desarrollo o respuesta:</label>
                                <textarea
                                    value={respuestas[activeQuestion.id] || ''}
                                    onChange={(e) => handleOpenTextChange(activeQuestion.id, e.target.value)}
                                    onBlur={() => handleOpenTextBlur(activeQuestion.id)}
                                    rows="6"
                                    placeholder="Escribe tu respuesta aquí. Al hacer clic fuera de este cuadro se guardará automáticamente."
                                    className="w-full border-2 border-black rounded-2xl p-4 font-bold text-sm focus:outline-none focus:bg-gray-50"
                                />
                                <div className="flex justify-end">
                                    <button
                                        onClick={() => saveAnswer(activeQuestion.id, respuestas[activeQuestion.id])}
                                        className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] transition-all text-xs"
                                    >
                                        Guardar respuesta abierta
                                    </button>
                                </div>
                            </div>
                        )}

                        {errorMsg && (
                            <div className="bg-rose-50 border-2 border-rose-500 text-rose-700 p-3 rounded-xl font-bold text-xs flex items-center gap-2">
                                <AlertTriangle className="w-4 h-4 shrink-0" />
                                <span>{errorMsg}</span>
                            </div>
                        )}
                    </section>
                )}
            </main>

            {/* Bottom Actions bar */}
            <footer className="bg-white border-t-4 border-black p-4 px-6 flex items-center justify-between sticky bottom-0 z-10">
                <button
                    onClick={() => setCurrentIndex(prev => Math.max(0, prev - 1))}
                    disabled={currentIndex === 0}
                    className="flex items-center gap-2 px-4 py-2.5 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 active:translate-y-0.5 transition-all disabled:opacity-50 text-sm"
                >
                    <ArrowLeft className="w-4 h-4" />
                    Anterior
                </button>

                {!isLastQuestion ? (
                    <button
                        onClick={() => setCurrentIndex(prev => Math.min(preguntas.length - 1, prev + 1))}
                        className="flex items-center gap-2 px-5 py-2.5 border-2 border-black rounded-xl font-bold bg-[#f4ecd6] hover:bg-amber-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] transition-all text-sm"
                    >
                        Siguiente
                        <ArrowRight className="w-4 h-4" />
                    </button>
                ) : (
                    <button
                        onClick={() => setShowConfirmSubmit(true)}
                        className="flex items-center gap-2 px-5 py-2.5 border-2 border-black rounded-xl font-bold bg-[#fb7185] hover:bg-rose-300 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm"
                    >
                        <Send className="w-4 h-4" />
                        Finalizar Examen
                    </button>
                )}
            </footer>

            {/* Confirm Submit Modal */}
            {showConfirmSubmit && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
                    <div className="bg-white border-4 border-black rounded-3xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] w-full max-w-sm overflow-hidden animate-slide-up text-center p-6 space-y-4">
                        <AlertTriangle className="w-12 h-12 text-rose-500 mx-auto" />
                        <h3 className="text-xl font-black">¿Entregar el Examen?</h3>
                        <p className="text-sm font-semibold text-gray-500 leading-relaxed">
                            Una vez entregado no podrás realizar cambios en tus respuestas. Asegúrate de haber respondido todo lo posible.
                        </p>

                        <div className="space-y-1 text-left">
                            <label className="block text-xs font-black uppercase tracking-wider text-gray-700">Confirma con los últimos 4 dígitos de tu celular</label>
                            <input
                                type="text"
                                pattern="[0-9]*"
                                inputMode="numeric"
                                maxLength="4"
                                value={celularUltimosCuatroSubmit}
                                onChange={(e) => setCelularUltimosCuatroSubmit(e.target.value.replace(/\D/g, ''))}
                                placeholder="Ej. 5678"
                                className="w-full border-2 border-black rounded-xl p-2.5 font-mono font-black text-lg text-center focus:outline-none focus:bg-gray-50"
                                required
                            />
                        </div>
                        
                        <div className="flex justify-center gap-3 pt-2">
                            <button
                                onClick={() => {
                                    setShowConfirmSubmit(false);
                                    setCelularUltimosCuatroSubmit('');
                                }}
                                className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 active:translate-y-0.5 transition-all text-sm"
                            >
                                Cancelar
                            </button>
                            <button
                                onClick={handleSubmitExamen}
                                disabled={celularUltimosCuatroSubmit.length !== 4}
                                className="px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#fb7185] hover:bg-rose-300 text-black active:translate-y-0.5 transition-all text-sm disabled:opacity-50"
                            >
                                Sí, entregar
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
