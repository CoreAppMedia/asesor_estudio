import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { ArrowLeft, CheckCircle, XCircle, AlertCircle, ArrowRight, Award, RefreshCw, HelpCircle } from 'lucide-react';
import MathView from '../components/MathView';

export default function ResolverCuestionario() {
    const { unidadId } = useParams();

    // Client Identification
    const getClientUuid = () => {
        let uuid = localStorage.getItem('client_uuid');
        if (!uuid) {
            uuid = 'client_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            localStorage.setItem('client_uuid', uuid);
        }
        return uuid;
    };

    // States
    const [preguntas, setPreguntas] = useState([]);
    const [unidadNombre, setUnidadNombre] = useState('');
    const [loading, setLoading] = useState(true);
    const [errorMsg, setErrorMsg] = useState('');
    const [quizStarted, setQuizStarted] = useState(false);
    
    // Quiz state
    const [currentIndex, setCurrentIndex] = useState(0);
    const [respuestas, setRespuestas] = useState({}); // { pregunta_id: 'A' }
    
    // Evaluation results
    const [resultado, setResultado] = useState(null);
    const [evaluating, setEvaluating] = useState(false);

    const loadCuestionario = async () => {
        setLoading(true);
        setErrorMsg('');
        setQuizStarted(false);
        setResultado(null);
        setCurrentIndex(0);
        setRespuestas({});

        try {
            const uuid = getClientUuid();
            const res = await fetch(`/api/public/unidades/${unidadId}/cuestionario`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Client-UUID': uuid
                }
            });

            const data = await res.json();

            if (res.status === 403) {
                setErrorMsg(data.message || 'Límite de intentos alcanzado.');
            } else if (!res.ok) {
                setErrorMsg(data.message || 'Error al cargar el cuestionario.');
            } else {
                setPreguntas(data.preguntas || []);
                // Cargar nombre de unidad
                const uRes = await fetch(`/api/public/unidades/${unidadId}/contenido`);
                if (uRes.ok) {
                    const uData = await uRes.json();
                    setUnidadNombre(uData.unidad.nombre);
                }
            }
        } catch (e) {
            setErrorMsg('Error de red al cargar el cuestionario de autoevaluación.');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadCuestionario();
    }, [unidadId]);

    const selectOption = (preguntaId, opcionKey) => {
        setRespuestas(prev => ({
            ...prev,
            [preguntaId]: opcionKey
        }));
    };

    const handleNext = () => {
        if (currentIndex < preguntas.length - 1) {
            setCurrentIndex(currentIndex + 1);
        }
    };

    const handlePrev = () => {
        if (currentIndex > 0) {
            setCurrentIndex(currentIndex - 1);
        }
    };

    const handleSubmitQuiz = async () => {
        setEvaluating(true);
        try {
            const uuid = getClientUuid();
            const res = await fetch(`/api/public/unidades/${unidadId}/cuestionario/evaluar`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Client-UUID': uuid
                },
                body: JSON.stringify({ respuestas })
            });

            const data = await res.json();
            if (res.ok) {
                setResultado(data);
            } else {
                alert(data.message || 'Error al calificar el cuestionario.');
            }
        } catch (e) {
            console.error(e);
            alert('Error de red al calificar.');
        } finally {
            setEvaluating(false);
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-[#f7f7f5] flex items-center justify-center font-sans font-bold text-gray-500">
                Preparando cuestionario de autoevaluación...
            </div>
        );
    }

    // Limit exceeded / Error screen
    if (errorMsg) {
        return (
            <div className="min-h-screen bg-[#f7f7f5] text-black flex flex-col items-center justify-center p-6 text-center">
                <div className="max-w-md bg-white border-2 border-black rounded-2xl p-8 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] space-y-6">
                    <div className="w-14 h-14 bg-[#efd4d4] flex items-center justify-center border-2 border-black rounded-2xl mx-auto">
                        <AlertCircle className="w-8 h-8 text-red-800" />
                    </div>
                    <div className="space-y-2">
                        <h3 className="text-2xl font-black">¡Límite de Intentos Alcanzado!</h3>
                        <p className="text-gray-600 font-semibold leading-relaxed text-sm">
                            {errorMsg}
                        </p>
                    </div>
                    <div className="flex gap-4 pt-2 justify-center">
                        <Link to={`/unidades/${unidadId}`} className="px-6 py-3 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-sm">
                            Repasar Lección
                        </Link>
                    </div>
                </div>
            </div>
        );
    }

    const currentQuestion = preguntas[currentIndex];
    const isAnswered = currentQuestion && respuestas[currentQuestion.id] !== undefined;

    return (
        <div className="min-h-screen bg-[#f7f7f5] text-black font-sans flex flex-col justify-between">
            {/* Header */}
            <header className="bg-white border-b-2 border-black sticky top-0 z-20">
                <div className="w-full px-6 md:px-12 py-4 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <Link to={`/unidades/${unidadId}`} className="p-2 border-2 border-black rounded-xl bg-white hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                            <ArrowLeft className="w-4 h-4" />
                        </Link>
                        <h1 className="font-black text-sm md:text-base leading-none truncate max-w-[200px] sm:max-w-xs">{unidadNombre}</h1>
                    </div>
                    <span className="text-xs font-black uppercase tracking-wider bg-gray-100 px-3 py-1 border-2 border-black rounded-lg">
                        Autoevaluación
                    </span>
                </div>
            </header>

            {/* Main content */}
            <main className="flex-1 w-full px-6 md:px-12 py-8 flex flex-col justify-center">
                
                {/* Welcome Screen */}
                {!quizStarted && (
                    <div className="bg-white border-2 border-black rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] p-8 text-center space-y-6">
                        <div className="w-14 h-14 bg-[#c5b0f4] flex items-center justify-center border-2 border-black rounded-2xl mx-auto">
                            <HelpCircle className="w-8 h-8 text-black" />
                        </div>
                        <div className="space-y-2">
                            <h3 className="text-2xl font-black">Cuestionario de Práctica</h3>
                            <p className="text-gray-600 font-semibold leading-relaxed text-sm">
                                Se presentará una selección aleatoria de <strong>5 preguntas</strong> de opción múltiple o respuesta abierta correspondientes a los temas de esta unidad.
                            </p>
                        </div>
                        <div className="bg-[#f4ecd6] border-2 border-black p-4 rounded-xl text-xs font-black text-amber-950 max-w-sm mx-auto">
                            Regla de negocio: Tienes un límite máximo de 3 intentos de práctica por día.
                        </div>
                        <button
                            onClick={() => setQuizStarted(true)}
                            className="px-8 py-3 border-2 border-black rounded-xl font-black bg-[#dceeb1] hover:bg-lime-200 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            Comenzar
                        </button>
                    </div>
                )}

                {/* Questionnaire Active Screen */}
                {quizStarted && !resultado && currentQuestion && (
                    <div className="space-y-6">
                        {/* Progress */}
                        <div className="space-y-2">
                            <div className="flex justify-between items-center text-xs font-black uppercase text-gray-500">
                                <span>Pregunta {currentIndex + 1} de {preguntas.length}</span>
                                <span>{Math.round(((currentIndex + 1) / preguntas.length) * 100)}% Completado</span>
                            </div>
                            <div className="h-4 w-full bg-white border-2 border-black rounded-full overflow-hidden">
                                <div
                                    className="h-full bg-[#dceeb1] border-r-2 border-black transition-all duration-300"
                                    style={{ width: `${((currentIndex + 1) / preguntas.length) * 100}%` }}
                                />
                            </div>
                        </div>

                        {/* Question Card */}
                        <div className="bg-white border-2 border-black rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] p-6 md:p-8 space-y-6">
                            <h4 className="font-extrabold text-lg md:text-xl leading-relaxed">
                                <MathView text={currentQuestion.texto_pregunta} />
                            </h4>

                            {/* Options rendering */}
                            {currentQuestion.tipo_pregunta === 'opcion_multiple' && currentQuestion.opciones_json ? (
                                <div className="grid grid-cols-1 gap-3 pt-4">
                                    {Object.entries(currentQuestion.opciones_json).map(([key, val]) => {
                                        const isSelected = respuestas[currentQuestion.id] === key;
                                        return (
                                            <button
                                                key={key}
                                                onClick={() => selectOption(currentQuestion.id, key)}
                                                className={`flex items-center gap-4 p-4 rounded-xl border-2 font-bold text-left transition-all ${
                                                    isSelected
                                                        ? 'bg-[#c5b0f4] border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-black'
                                                        : 'bg-white border-gray-200 text-gray-700 hover:border-black hover:bg-gray-50'
                                                }`}
                                            >
                                                <span className="w-6 h-6 flex items-center justify-center bg-black text-white text-xs font-black rounded-lg shrink-0">
                                                    {key}
                                                </span>
                                                <MathView text={val} className="text-sm md:text-base" />
                                            </button>
                                        );
                                    })}
                                </div>
                            ) : (
                                <div className="pt-4 space-y-2">
                                    <label className="block text-xs font-black uppercase text-gray-500">Introduce tu respuesta</label>
                                    <input
                                        type="text"
                                        placeholder="Ej. 2x - 5"
                                        value={respuestas[currentQuestion.id] || ''}
                                        onChange={(e) => selectOption(currentQuestion.id, e.target.value)}
                                        className="w-full p-4 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50"
                                    />
                                </div>
                            )}
                        </div>

                        {/* Navigation Actions */}
                        <div className="flex justify-between items-center pt-2">
                            <button
                                onClick={handlePrev}
                                disabled={currentIndex === 0}
                                className="px-5 py-2 border-2 border-black rounded-xl font-bold bg-white hover:bg-gray-50 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50"
                            >
                                Anterior
                            </button>

                            {currentIndex < preguntas.length - 1 ? (
                                <button
                                    onClick={handleNext}
                                    disabled={!isAnswered}
                                    className="flex items-center gap-1 px-5 py-2 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50"
                                >
                                    Siguiente <ArrowRight className="w-4 h-4" />
                                </button>
                            ) : (
                                <button
                                    onClick={handleSubmitQuiz}
                                    disabled={!isAnswered || evaluating}
                                    className="px-6 py-2 border-2 border-black rounded-xl font-black bg-[#c5b0f4] hover:bg-purple-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50"
                                >
                                    {evaluating ? 'Calificando...' : 'Finalizar y Calificar'}
                                </button>
                            )}
                        </div>
                    </div>
                )}

                {/* Results Screen */}
                {resultado && (
                    <div className="space-y-8">
                        {/* Summary Score Card */}
                        <div className={`border-2 border-black rounded-2xl p-6 md:p-8 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] text-center space-y-4 ${
                            resultado.score >= 8 ? 'bg-[#c8e6cd]' : resultado.score >= 6 ? 'bg-[#f4ecd6]' : 'bg-[#efd4d4]'
                        }`}>
                            <div className="w-12 h-12 bg-white flex items-center justify-center border-2 border-black rounded-xl mx-auto shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                                <Award className="w-6 h-6 text-black" />
                            </div>
                            <div>
                                <h3 className="text-3xl font-black">Tu Calificación: {resultado.score}</h3>
                                <p className="text-sm font-bold text-gray-700 mt-1">
                                    Aceptaste {resultado.correct_count} de {resultado.total_count} reactivos.
                                </p>
                            </div>
                            <div className="text-xs font-black uppercase text-gray-600">
                                Intentos restantes para hoy: {resultado.attempts_remaining}
                            </div>
                        </div>

                        {/* Detailed question results */}
                        <div className="space-y-4">
                            <h4 className="font-black text-sm uppercase tracking-wider text-gray-500">Reporte de Respuestas</h4>
                            <div className="space-y-4">
                                {resultado.results && resultado.results.map((res, i) => (
                                    <div key={i} className="bg-white border-2 border-black rounded-2xl p-5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] space-y-3">
                                        <div className="flex justify-between items-start gap-4">
                                            <h5 className="font-bold text-sm md:text-base leading-relaxed">
                                                <MathView text={res.texto_pregunta} />
                                            </h5>
                                            {res.es_correcta ? (
                                                <CheckCircle className="w-5 h-5 text-green-600 shrink-0 mt-1" />
                                            ) : (
                                                <XCircle className="w-5 h-5 text-red-600 shrink-0 mt-1" />
                                            )}
                                        </div>

                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-semibold pt-2 border-t border-gray-100">
                                            <div className="space-y-1">
                                                <span className="text-gray-500 uppercase tracking-wide block">Tu respuesta:</span>
                                                <span className={`px-2 py-0.5 rounded border ${
                                                    res.es_correcta ? 'bg-green-50 border-green-200 text-green-950' : 'bg-red-50 border-red-200 text-red-950'
                                                }`}>
                                                    {res.respuesta_alumno || '(En blanco)'}
                                                </span>
                                            </div>
                                            <div className="space-y-1">
                                                <span className="text-gray-500 uppercase tracking-wide block">Respuesta correcta:</span>
                                                <span className="bg-green-50 border border-green-200 px-2 py-0.5 rounded text-green-950">
                                                    {res.respuesta_correcta}
                                                </span>
                                            </div>
                                        </div>

                                        <div className="text-xs font-semibold text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                            {res.feedback}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex gap-4 justify-center">
                            <Link to={`/unidades/${unidadId}`} className="px-6 py-3 border-2 border-black rounded-xl font-bold bg-white hover:bg-gray-50 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-sm">
                                Volver a la Lección
                            </Link>
                            {resultado.attempts_remaining > 0 && (
                                <button
                                    onClick={loadCuestionario}
                                    className="flex items-center gap-2 px-6 py-3 border-2 border-black rounded-xl font-black bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-sm"
                                >
                                    <RefreshCw className="w-4 h-4" />
                                    Intentar de Nuevo
                                </button>
                            )}
                        </div>
                    </div>
                )}
            </main>

            {/* Footer */}
            <footer className="bg-white border-t-2 border-black py-6">
                <div className="w-full px-6 md:px-12 text-center text-xs font-semibold text-gray-500">
                    Colegio de Ciencias y Humanidades — UNAM
                </div>
            </footer>
        </div>
    );
}
