import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import {
    BookOpen, Lock, Mail, ArrowLeft, Users, BarChart3,
    ClipboardList, QrCode, HelpCircle, ShieldCheck,
    GraduationCap, CheckCircle2
} from 'lucide-react';

const adminFeatures = [
    {
        icon: Users,
        color: 'bg-[#dceeb1]',
        title: 'Grupos y Alumnos',
        desc: 'Crea y gestiona grupos escolares, inscribe alumnos y genera códigos QR para acceso rápido.',
    },
    {
        icon: HelpCircle,
        color: 'bg-[#c5b0f4]',
        title: 'Banco de Preguntas',
        desc: 'Diseña reactivos con soporte completo de fórmulas LaTeX para opción múltiple y respuesta abierta.',
    },
    {
        icon: ClipboardList,
        color: 'bg-[#f4ecd6]',
        title: 'Evaluaciones',
        desc: 'Arma cuestionarios y exámenes por unidad, asignándolos a grupos específicos con control de intentos.',
    },
    {
        icon: QrCode,
        color: 'bg-[#c8e6cd]',
        title: 'Sesiones QR en Vivo',
        desc: 'Lanza sesiones de evaluación en tiempo real. Los alumnos acceden escaneando un código QR.',
    },
    {
        icon: BarChart3,
        color: 'bg-[#efd4d4]',
        title: 'Revisión y Calificación',
        desc: 'Revisa respuestas abiertas, asigna calificaciones parciales y consolida la nota final del alumno.',
    },
    {
        icon: ShieldCheck,
        color: 'bg-[#dceeb1]',
        title: 'Panel de Control',
        desc: 'Vista general del estado del sistema: grupos activos, sesiones abiertas y estadísticas de avance.',
    },
];

export default function Login() {
    const navigate = useNavigate();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Credenciales inválidas.');
            }

            localStorage.setItem('admin_token', data.token);
            localStorage.setItem('admin_user', JSON.stringify(data.user));

            navigate('/admin/dashboard');
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-[#f7f7f5] text-black font-sans flex flex-col lg:flex-row">

            {/* ─── LEFT PANEL — Info (hidden below lg / ~11") ─── */}
            <div className="hidden lg:flex lg:w-1/2 xl:w-3/5 flex-col bg-white border-r-2 border-black overflow-y-auto">

                {/* Brand header */}
                <div className="p-8 xl:p-12 border-b-2 border-black bg-[#c5b0f4] flex items-center gap-4">
                    <div className="w-12 h-12 bg-black flex items-center justify-center rounded-xl shrink-0">
                        <BookOpen className="text-white w-7 h-7" />
                    </div>
                    <div>
                        <h1 className="font-black text-2xl xl:text-3xl leading-none tracking-tight">MATEMÁTICAS CCH</h1>
                        <span className="text-sm font-bold text-gray-700">Plataforma de Asesoría · UNAM</span>
                    </div>
                </div>

                {/* Hero copy */}
                <div className="p-8 xl:p-12 space-y-4 border-b-2 border-black">
                    <div className="flex items-center gap-3">
                        <GraduationCap className="w-7 h-7 shrink-0" />
                        <h2 className="text-2xl xl:text-3xl font-black leading-tight tracking-tight">
                            Panel Docente — Gestión Académica Integral
                        </h2>
                    </div>
                    <p className="text-base xl:text-lg text-gray-600 font-medium leading-relaxed">
                        Herramienta diseñada para docentes del Colegio de Ciencias y Humanidades. Administra grupos,
                        crea evaluaciones con soporte LaTeX completo, lanza sesiones en tiempo real y monitorea
                        el desempeño de tus alumnos — todo desde un solo lugar.
                    </p>

                    {/* Highlights */}
                    <div className="flex flex-wrap gap-2 pt-2">
                        {['Soporte LaTeX / KaTeX', 'Sesiones QR en vivo', 'Grupos ilimitados', 'Estadísticas detalladas'].map(tag => (
                            <span
                                key={tag}
                                className="flex items-center gap-1.5 px-3 py-1 bg-[#f7f7f5] border-2 border-black rounded-full text-xs font-black"
                            >
                                <CheckCircle2 className="w-3.5 h-3.5 text-green-700" />
                                {tag}
                            </span>
                        ))}
                    </div>
                </div>

                {/* Features grid */}
                <div className="p-8 xl:p-12 flex-1">
                    <p className="text-xs font-black uppercase tracking-widest text-gray-500 mb-5">
                        ¿Qué puedes hacer como administrador?
                    </p>
                    <div className="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        {adminFeatures.map(({ icon: Icon, color, title, desc }) => (
                            <div
                                key={title}
                                className="flex gap-4 p-4 border-2 border-black rounded-2xl bg-white shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all"
                            >
                                <div className={`w-10 h-10 ${color} border-2 border-black rounded-xl flex items-center justify-center shrink-0`}>
                                    <Icon className="w-5 h-5" />
                                </div>
                                <div className="space-y-0.5 min-w-0">
                                    <p className="font-black text-sm">{title}</p>
                                    <p className="text-xs text-gray-500 font-medium leading-relaxed">{desc}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Footer note */}
                <div className="p-8 xl:p-12 border-t-2 border-black bg-[#f7f7f5]">
                    <p className="text-xs font-semibold text-gray-400">
                        © 2026 Plataforma de Matemáticas CCH · Colegio de Ciencias y Humanidades — UNAM
                    </p>
                </div>
            </div>

            {/* ─── RIGHT PANEL — Login form (always visible) ─── */}
            <div className="flex-1 lg:w-1/2 xl:w-2/5 flex flex-col items-center justify-center p-6 sm:p-10 lg:p-12 min-h-screen lg:min-h-0">

                {/* Mobile-only brand */}
                <div className="lg:hidden flex flex-col items-center mb-8 text-center">
                    <div className="w-14 h-14 bg-black flex items-center justify-center rounded-2xl mb-3 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <BookOpen className="text-white w-8 h-8" />
                    </div>
                    <h1 className="font-black text-2xl tracking-tight">MATEMÁTICAS CCH</h1>
                    <span className="text-sm font-semibold text-gray-500 mt-0.5">Plataforma de Asesoría · UNAM</span>
                </div>

                {/* Login card */}
                <div className="w-full max-w-sm">

                    <div className="mb-8">
                        <h2 className="text-3xl font-black tracking-tight">Iniciar Sesión</h2>
                        <p className="text-sm font-semibold text-gray-500 mt-1">
                            Accede al panel de administración docente.
                        </p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-5">

                        {error && (
                            <div className="bg-[#efd4d4] border-2 border-black p-4 rounded-xl font-bold text-sm text-red-800 flex items-start gap-3">
                                <span className="mt-0.5 text-red-600 shrink-0">✕</span>
                                {error}
                            </div>
                        )}

                        {/* Email */}
                        <div className="space-y-2">
                            <label className="block text-xs font-black uppercase tracking-widest text-gray-700">
                                Correo Electrónico
                            </label>
                            <div className="relative">
                                <span className="absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <Mail className="text-gray-400 w-5 h-5" />
                                </span>
                                <input
                                    type="email"
                                    required
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="docente@cch.unam.mx"
                                    className="w-full pl-11 pr-4 py-3.5 border-2 border-black rounded-xl font-semibold bg-white placeholder:text-gray-300 focus:outline-none focus:bg-gray-50 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all"
                                />
                            </div>
                        </div>

                        {/* Password */}
                        <div className="space-y-2">
                            <label className="block text-xs font-black uppercase tracking-widest text-gray-700">
                                Contraseña
                            </label>
                            <div className="relative">
                                <span className="absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <Lock className="text-gray-400 w-5 h-5" />
                                </span>
                                <input
                                    type="password"
                                    required
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                    placeholder="••••••••"
                                    className="w-full pl-11 pr-4 py-3.5 border-2 border-black rounded-xl font-semibold bg-white placeholder:text-gray-300 focus:outline-none focus:bg-gray-50 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all"
                                />
                            </div>
                        </div>

                        {/* Submit */}
                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-4 border-2 border-black rounded-xl font-black text-base bg-[#dceeb1] hover:bg-lime-200 shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? (
                                <span className="flex items-center justify-center gap-2">
                                    <span className="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin" />
                                    Ingresando...
                                </span>
                            ) : 'Entrar al Panel →'}
                        </button>

                        {/* Divider */}
                        <div className="flex items-center gap-3">
                            <div className="flex-1 h-px bg-gray-200" />
                            <span className="text-xs font-bold text-gray-400">ó</span>
                            <div className="flex-1 h-px bg-gray-200" />
                        </div>

                        {/* Back link */}
                        <Link
                            to="/"
                            className="w-full flex items-center justify-center gap-2 py-3 border-2 border-black rounded-xl font-bold text-sm text-gray-700 bg-white hover:bg-gray-50 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                        >
                            <ArrowLeft className="w-4 h-4" />
                            Volver al inicio
                        </Link>
                    </form>

                    {/* Caption */}
                    <p className="text-center text-xs text-gray-400 font-semibold mt-8">
                        Solo personal docente autorizado.<br />
                        Colegio de Ciencias y Humanidades — UNAM
                    </p>
                </div>
            </div>
        </div>
    );
}
