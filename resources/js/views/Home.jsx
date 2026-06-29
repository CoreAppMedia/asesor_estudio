import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { BookOpen, GraduationCap, ChevronRight, LogIn } from 'lucide-react';

export default function Home() {
    const [cursos, setCursos] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchCursos = async () => {
            try {
                const res = await fetch('/api/public/cursos');
                if (res.ok) {
                    const data = await res.json();
                    setCursos(data);
                }
            } catch (e) {
                console.error('Error fetching courses list', e);
            } finally {
                setLoading(false);
            }
        };

        fetchCursos();
    }, []);

    // Colores pastel para los semestres
    const colors = [
        { bg: 'bg-[#dceeb1]', border: 'border-black' },
        { bg: 'bg-[#c5b0f4]', border: 'border-black' },
        { bg: 'bg-[#c8e6cd]', border: 'border-black' },
        { bg: 'bg-[#f4ecd6]', border: 'border-black' }
    ];

    return (
        <div className="min-h-screen bg-[#f7f7f5] text-black font-sans flex flex-col justify-between">
            {/* Header */}
            <header className="bg-white border-b-2 border-black sticky top-0 z-20">
                <div className="w-full px-6 md:px-12 py-4 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 bg-black flex items-center justify-center rounded-lg">
                            <BookOpen className="text-white w-6 h-6" />
                        </div>
                        <div>
                            <h1 className="font-black text-xl leading-none">MATEMÁTICAS CCH</h1>
                            <span className="text-xs font-semibold tracking-wider text-gray-500 uppercase">UNAM</span>
                        </div>
                    </div>
                    <Link
                        to="/login"
                        className="flex items-center gap-2 px-4 py-2 border-2 border-black rounded-xl font-bold bg-[#f7f7f5] hover:bg-gray-100 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm"
                    >
                        <LogIn className="w-4 h-4" />
                        Acceso Docente
                    </Link>
                </div>
            </header>

            {/* Main Content */}
            <main className="flex-1 w-full px-6 md:px-12 py-10 space-y-12">
                {/* Hero section */}
                <div className="text-center space-y-4 py-4">
                    <h2 className="text-4xl md:text-5xl font-black tracking-tight leading-none">
                        Tu asesor de estudio independiente
                    </h2>
                    <p className="text-lg md:text-xl text-gray-600 font-medium max-w-2xl mx-auto">
                        Accede de forma libre a explicaciones conceptuales, ejemplos resueltos paso a paso y ejercicios de autoevaluación.
                    </p>
                </div>

                {/* Courses Grid */}
                {loading ? (
                    <div className="text-center py-20 font-bold text-gray-500">
                        Cargando catálogo curricular...
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {cursos.length > 0 && cursos[0].semestres.map((semestre, idx) => {
                            const color = colors[idx % colors.length];
                            return (
                                <div
                                    key={semestre.id}
                                    className={`${color.bg} border-2 ${color.border} rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] overflow-hidden flex flex-col justify-between`}
                                >
                                    <div className="p-6 border-b-2 border-black flex justify-between items-center bg-white/20">
                                        <div>
                                            <h3 className="text-2xl font-black leading-tight">
                                                {semestre.descripcion}
                                            </h3>
                                            <span className="text-xs font-black uppercase tracking-wider text-gray-600">
                                                Semestre {semestre.numero}
                                            </span>
                                        </div>
                                        <div className="p-2 bg-white border-2 border-black rounded-lg">
                                            <GraduationCap className="w-6 h-6" />
                                        </div>
                                    </div>

                                    <div className="p-6 space-y-4 flex-1">
                                        <h4 className="font-extrabold text-xs uppercase tracking-widest text-gray-700">
                                            Unidades de Aprendizaje
                                        </h4>
                                        <div className="space-y-2">
                                            {semestre.unidades && semestre.unidades.length > 0 ? (
                                                semestre.unidades.map((unidad) => (
                                                    <Link
                                                        key={unidad.id}
                                                        to={`/unidades/${unidad.id}`}
                                                        className="flex items-center justify-between p-4 bg-white border-2 border-black rounded-xl hover:bg-gray-50 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all font-bold text-left"
                                                    >
                                                        <div>
                                                            <span className="text-xs font-black text-gray-500 block">
                                                                Unidad {unidad.numero}
                                                            </span>
                                                            <span className="text-sm leading-tight text-gray-900">
                                                                {unidad.nombre}
                                                            </span>
                                                        </div>
                                                        <ChevronRight className="w-5 h-5 text-gray-500 shrink-0 ml-2" />
                                                    </Link>
                                                ))
                                            ) : (
                                                <p className="text-sm text-gray-500 italic">No hay unidades en este semestre.</p>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </main>

            {/* Footer */}
            <footer className="bg-white border-t-2 border-black py-8">
                <div className="w-full px-6 md:px-12 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left text-sm font-semibold text-gray-500">
                    <p>© 2026 Plataforma de Matemáticas CCH. Todos los derechos reservados.</p>
                    <p>Colegio de Ciencias y Humanidades — UNAM</p>
                </div>
            </footer>
        </div>
    );
}
