import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { ArrowLeft, ArrowRight, Target, CheckCircle2, AlertTriangle, Lightbulb, Play, BookMarked, Eye, EyeOff } from 'lucide-react';
import MathView from '../components/MathView';

export default function VerUnidad() {
    const { unidadId } = useParams();
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState('introduccion');
    const [visibleSolutions, setVisibleSolutions] = useState({});

    useEffect(() => {
        setActiveTab('introduccion');
        setVisibleSolutions({});
        window.scrollTo(0, 0);
        setLoading(true);

        const fetchContent = async () => {
            try {
                const res = await fetch(`/api/public/unidades/${unidadId}/contenido`);
                if (res.ok) {
                    const json = await res.json();
                    setData(json);
                }
            } catch (e) {
                console.error('Error fetching unit content', e);
            } finally {
                setLoading(false);
            }
        };

        fetchContent();
    }, [unidadId]);

    const toggleSolution = (index) => {
        setVisibleSolutions(prev => ({
            ...prev,
            [index]: !prev[index]
        }));
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-[#f7f7f5] flex items-center justify-center font-sans font-bold text-gray-500">
                Cargando contenido didáctico...
            </div>
        );
    }

    if (!data) {
        return (
            <div className="min-h-screen bg-[#f7f7f5] flex flex-col items-center justify-center p-6 text-center space-y-4">
                <h3 className="text-2xl font-black">Unidad no encontrada</h3>
                <Link to="/" className="px-6 py-2 border-2 border-black rounded-xl bg-[#dceeb1] font-bold shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                    Regresar al Inicio
                </Link>
            </div>
        );
    }

    const { unidad, contenido } = data;

    const tabs = [
        { id: 'introduccion', name: 'Introducción' },
        { id: 'teoria', name: 'Teoría' },
        { id: 'conceptos', name: 'Conceptos' },
        { id: 'ejemplos', name: 'Ejemplos' },
        { id: 'errores', name: 'Errores Comunes' },
        { id: 'practica', name: 'Práctica' }
    ];

    const activeTabIndex = tabs.findIndex(t => t.id === activeTab);
    const nextTab = activeTabIndex < tabs.length - 1 ? tabs[activeTabIndex + 1] : null;

    return (
        <div className="min-h-screen bg-[#f7f7f5] text-black font-sans flex flex-col justify-between">
            {/* Header */}
            <header className="bg-white border-b-2 border-black sticky top-0 z-20">
                <div className="w-full px-6 md:px-12 py-4 flex items-center gap-4">
                    <Link to="/" className="p-2 border-2 border-black rounded-xl bg-white hover:bg-gray-100 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                        <ArrowLeft className="w-5 h-5" />
                    </Link>
                    <div className="flex items-center gap-2">
                        <span className="text-xs font-black uppercase bg-[#c5b0f4] px-2 py-1 border border-black rounded">
                            Unidad {unidad.numero}
                        </span>
                        <h1 className="font-black text-lg md:text-xl truncate">{unidad.nombre}</h1>
                    </div>
                </div>
            </header>

            {/* Main Layout */}
            <main className="flex-1 w-full px-6 md:px-12 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {/* Tabs Navigation (Left Sidebar on desktop) */}
                <div className="lg:col-span-3 space-y-2 lg:sticky lg:top-24">
                    <span className="text-xs font-black uppercase tracking-wider text-gray-500 block mb-2 px-1">Secciones de estudio</span>
                    <div className="flex overflow-x-auto lg:flex-col gap-2 pb-2 lg:pb-0 scrollbar-thin">
                        {tabs.map((tab) => (
                            <button
                                key={tab.id}
                                onClick={() => setActiveTab(tab.id)}
                                className={`px-4 py-3 rounded-xl border-2 font-bold text-sm text-left transition-all whitespace-nowrap lg:whitespace-normal shrink-0 lg:shrink ${
                                    activeTab === tab.id
                                        ? 'bg-[#dceeb1] border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-black'
                                        : 'border-transparent text-gray-600 hover:text-black hover:border-black hover:bg-white/50'
                                }`}
                            >
                                {tab.name}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Tab Contents (Right area) */}
                <div className="lg:col-span-9 bg-white border-2 border-black rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] p-6 md:p-8 space-y-8 min-h-[500px]">
                    
                    {/* Tab: Introducción */}
                    {activeTab === 'introduccion' && (
                        <div className="space-y-8">
                            <div className="space-y-4">
                                <h3 className="text-2xl font-black">Introducción</h3>
                                <MathView text={contenido.introduccion} className="text-gray-800 text-base" />
                            </div>

                            {contenido.objetivos && contenido.objetivos.length > 0 && (
                                <div className="space-y-4 border-t-2 border-black pt-6">
                                    <h4 className="text-lg font-black flex items-center gap-2">
                                        <Target className="w-5 h-5 text-gray-600" /> Objetivos de Aprendizaje
                                    </h4>
                                    <ul className="space-y-2">
                                        {contenido.objetivos.map((obj, i) => (
                                            <li key={i} className="flex items-start gap-2 text-sm font-semibold text-gray-700">
                                                <CheckCircle2 className="w-4 h-4 text-green-600 shrink-0 mt-0.5" />
                                                <span>{obj}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}

                            {contenido.conocimientos_previos && contenido.conocimientos_previos.length > 0 && (
                                <div className="space-y-4 border-t-2 border-black pt-6">
                                    <h4 className="text-lg font-black flex items-center gap-2">
                                        <Lightbulb className="w-5 h-5 text-gray-600" /> Conocimientos Previos Sugeridos
                                    </h4>
                                    <ul className="space-y-2">
                                        {contenido.conocimientos_previos.map((con, i) => (
                                            <li key={i} className="flex items-start gap-2 text-sm font-semibold text-gray-700">
                                                <div className="w-1.5 h-1.5 rounded-full bg-black shrink-0 mt-2" />
                                                <span>{con}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Tab: Teoría */}
                    {activeTab === 'teoria' && (
                        <div className="space-y-8">
                            <h3 className="text-2xl font-black">Explicación Teórica</h3>
                            <div className="space-y-8 divide-y-2 divide-gray-100">
                                {contenido.explicacion && contenido.explicacion.map((sec, i) => (
                                    <div key={i} className={`space-y-4 ${i > 0 ? 'pt-8' : ''}`}>
                                        <h4 className="text-lg font-extrabold">{sec.titulo}</h4>
                                        <MathView text={sec.texto} className="text-gray-800 text-base" />
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Tab: Conceptos */}
                    {activeTab === 'conceptos' && (
                        <div className="space-y-8">
                            <h3 className="text-2xl font-black">Conceptos Clave</h3>
                            {contenido.conceptos_clave && contenido.conceptos_clave.length === 0 ? (
                                <p className="text-gray-500 font-bold italic">No se han listado conceptos clave para esta unidad.</p>
                            ) : (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {contenido.conceptos_clave && contenido.conceptos_clave.map((con, i) => (
                                        <div key={i} className="bg-[#f4ecd6] border-2 border-black p-5 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] space-y-2">
                                            <span className="text-xs font-black uppercase tracking-wider text-gray-600">Término</span>
                                            <h4 className="font-black text-base"><MathView text={con.concepto} /></h4>
                                            <p className="text-sm font-medium text-gray-800 border-t border-black/20 pt-2">{con.definicion}</p>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    )}

                    {/* Tab: Ejemplos */}
                    {activeTab === 'ejemplos' && (
                        <div className="space-y-8">
                            <h3 className="text-2xl font-black">Ejemplos Resueltos</h3>
                            {contenido.ejemplos && contenido.ejemplos.length === 0 ? (
                                <p className="text-gray-500 font-bold italic">No se han redactado ejemplos para esta unidad.</p>
                            ) : (
                                <div className="space-y-6">
                                    {contenido.ejemplos && contenido.ejemplos.map((ej, i) => {
                                        const showSol = visibleSolutions[i];
                                        return (
                                            <div key={i} className="border-2 border-black rounded-2xl overflow-hidden shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                                <div className="bg-[#c8e6cd] p-5 border-b-2 border-black font-black text-base">
                                                    {ej.titulo}
                                                </div>
                                                <div className="p-6 space-y-6">
                                                    <MathView text={ej.explicacion} className="text-gray-800 font-semibold" />
                                                    
                                                    {/* Toggle button */}
                                                    <button
                                                        onClick={() => toggleSolution(i)}
                                                        className="flex items-center gap-2 px-4 py-2 border-2 border-black rounded-xl bg-white hover:bg-gray-50 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all font-bold text-sm"
                                                    >
                                                        {showSol ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                                        {showSol ? 'Ocultar Solución' : 'Ver Solución Paso a Paso'}
                                                    </button>

                                                    {/* Solution details */}
                                                    {showSol && (
                                                        <div className="bg-gray-50 border-t-2 border-black p-5 -mx-6 -mb-6 space-y-3">
                                                            <h5 className="font-black text-sm uppercase tracking-wider text-gray-500">Procedimiento / Solución</h5>
                                                            <MathView text={ej.solucion} className="text-gray-800 text-sm" />
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                        </div>
                    )}

                    {/* Tab: Errores */}
                    {activeTab === 'errores' && (
                        <div className="space-y-8">
                            <h3 className="text-2xl font-black">Errores Comunes a Evitar</h3>
                            {contenido.errores_comunes && contenido.errores_comunes.length === 0 ? (
                                <p className="text-gray-500 font-bold italic">No se han cargado errores comunes para esta unidad.</p>
                            ) : (
                                <div className="space-y-6">
                                    {contenido.errores_comunes && contenido.errores_comunes.map((err, i) => (
                                        <div key={i} className="border-2 border-black rounded-2xl overflow-hidden shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-white">
                                            <div className="bg-[#efd4d4] p-4 border-b-2 border-black flex items-center gap-2 text-red-950 font-black text-sm uppercase tracking-wider">
                                                <AlertTriangle className="w-4 h-4 text-red-800" /> {err.error}
                                            </div>
                                            <div className="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div className="p-4 border-2 border-dashed border-red-300 bg-red-50/50 rounded-xl space-y-2">
                                                    <span className="text-xs font-black uppercase text-red-800">Así NO se hace:</span>
                                                    <MathView text={err.ejemplo_incorrecto} className="text-sm font-bold text-red-950" />
                                                </div>
                                                <div className="p-4 border-2 border-black bg-[#c8e6cd]/30 rounded-xl space-y-2">
                                                    <span className="text-xs font-black uppercase text-green-800">Forma correcta:</span>
                                                    <MathView text={err.correccion} className="text-sm font-bold text-green-950" />
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    )}

                    {/* Tab: Práctica */}
                    {activeTab === 'practica' && (
                        <div className="space-y-8">
                            <h3 className="text-2xl font-black">Ejercicios de Práctica</h3>
                            {contenido.ejercicios_guiados && contenido.ejercicios_guiados.length === 0 ? (
                                <p className="text-gray-500 font-bold italic">No se han cargado ejercicios para esta unidad.</p>
                            ) : (
                                <div className="space-y-6">
                                    {contenido.ejercicios_guiados && contenido.ejercicios_guiados.map((ejer, i) => {
                                        const showHint = visibleSolutions[`hint_${i}`];
                                        return (
                                            <div key={i} className="border-2 border-black p-6 rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] bg-white space-y-4">
                                                <div className="flex items-start gap-4">
                                                    <span className="w-8 h-8 flex items-center justify-center font-black text-sm bg-black text-white border-2 border-black rounded-lg shrink-0">
                                                        {i + 1}
                                                    </span>
                                                    <div className="space-y-3 flex-1 pt-1">
                                                        <h4 className="font-extrabold text-base"><MathView text={ejer.instruccion} /></h4>
                                                        
                                                        {/* Toggle hint */}
                                                        <button
                                                            onClick={() => setVisibleSolutions(prev => ({ ...prev, [`hint_${i}`]: !prev[`hint_${i}`] }))}
                                                            className="flex items-center gap-1.5 text-xs font-black text-gray-500 hover:text-black uppercase tracking-wider focus:outline-none"
                                                        >
                                                            <Play className={`w-3 h-3 transition-transform ${showHint ? 'rotate-90' : ''}`} />
                                                            {showHint ? 'Ocultar Guía' : 'Ver Guía de Resolución'}
                                                        </button>

                                                        {showHint && (
                                                            <div className="bg-[#f7f7f5] border-2 border-black p-4 rounded-xl text-xs font-semibold text-gray-700 leading-relaxed">
                                                                <MathView text={ejer.guia} />
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}

                            {/* Bibliography footer */}
                            {contenido.bibliografia && contenido.bibliografia.length > 0 && (
                                <div className="space-y-4 border-t-2 border-black pt-8 mt-12">
                                    <h4 className="text-base font-black flex items-center gap-2 text-gray-600">
                                        <BookMarked className="w-5 h-5 text-gray-500" /> Referencias Bibliográficas
                                    </h4>
                                    <ul className="space-y-2">
                                        {contenido.bibliografia.map((bib, i) => (
                                            <li key={i} className="text-xs font-semibold text-gray-500 leading-relaxed list-decimal list-inside">
                                                <span>{bib}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Navegación al final de la sección */}
                    {nextTab ? (
                        <div className="pt-8 border-t-2 border-black flex justify-end">
                            <button
                                onClick={() => {
                                    setActiveTab(nextTab.id);
                                    window.scrollTo({ top: 0, behavior: 'smooth' });
                                }}
                                className="flex items-center gap-2 px-6 py-3 border-2 border-black rounded-xl font-bold bg-[#dceeb1] hover:bg-lime-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm"
                            >
                                Siguiente Sección: {nextTab.name}
                                <ArrowRight className="w-4 h-4" />
                            </button>
                        </div>
                    ) : data.siguiente_unidad ? (
                        <div className="pt-8 border-t-2 border-black flex justify-end">
                            <Link
                                to={`/unidades/${data.siguiente_unidad.id}`}
                                className="flex items-center gap-2 px-6 py-3 border-2 border-black rounded-xl font-bold bg-[#c5b0f4] hover:bg-purple-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm text-black"
                            >
                                Siguiente Unidad: U{data.siguiente_unidad.numero} - {data.siguiente_unidad.nombre}
                                <ArrowRight className="w-4 h-4" />
                            </Link>
                        </div>
                    ) : null}
                </div>
            </main>

            {/* Footer */}
            <footer className="bg-white border-t-2 border-black py-8 mt-12">
                <div className="w-full px-6 md:px-12 text-center text-sm font-semibold text-gray-500">
                    Colegio de Ciencias y Humanidades — UNAM
                </div>
            </footer>
        </div>
    );
}
