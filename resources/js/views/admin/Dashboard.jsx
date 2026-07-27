import React, { useEffect, useState } from 'react';
import AdminLayout from '../../components/AdminLayout';
import { Link } from 'react-router-dom';
import {
    Users, GraduationCap, Radio, Award,
    HelpCircle, Clipboard, QrCode, ArrowRight,
    TrendingUp, BookOpen, CheckCircle2, AlertCircle
} from 'lucide-react';

/* ─── Stat Card ─── */
function StatCard({ title, value, icon: Icon, color, loading }) {
    return (
        <div className={`${color} border-2 border-black rounded-2xl shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] p-5 flex flex-col gap-4 hover:shadow-[7px_7px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all`}>
            <div className="flex items-start justify-between">
                <p className="font-black text-xs uppercase tracking-widest text-gray-700 leading-tight max-w-[70%]">{title}</p>
                <div className="w-9 h-9 bg-white border-2 border-black rounded-xl flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <Icon className="w-4 h-4" />
                </div>
            </div>
            <div className="text-4xl sm:text-5xl font-black leading-none">
                {loading ? (
                    <span className="inline-block w-12 h-10 bg-black/10 rounded-lg animate-pulse" />
                ) : value}
            </div>
        </div>
    );
}

/* ─── Quick-action card ─── */
function ActionCard({ title, desc, href, icon: Icon, color }) {
    return (
        <Link
            to={href}
            className={`group flex items-center gap-4 p-4 sm:p-5 bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all`}
        >
            <div className={`${color} w-12 h-12 border-2 border-black rounded-xl flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]`}>
                <Icon className="w-6 h-6" />
            </div>
            <div className="flex-1 min-w-0">
                <p className="font-black text-sm sm:text-base leading-tight">{title}</p>
                <p className="text-xs sm:text-sm text-gray-500 font-medium leading-snug mt-0.5 truncate">{desc}</p>
            </div>
            <ArrowRight className="w-5 h-5 text-gray-400 group-hover:text-black group-hover:translate-x-0.5 transition-all shrink-0" />
        </Link>
    );
}

/* ─── Step item for quick-start ─── */
function Step({ n, text, done = false }) {
    return (
        <div className="flex items-start gap-3">
            <div className={`w-7 h-7 rounded-full border-2 border-black flex items-center justify-center shrink-0 font-black text-xs ${done ? 'bg-[#dceeb1]' : 'bg-white'}`}>
                {done ? <CheckCircle2 className="w-4 h-4" /> : n}
            </div>
            <p className="text-sm font-semibold text-gray-700 leading-relaxed pt-0.5">{text}</p>
        </div>
    );
}

/* ─── Dashboard ─── */
export default function Dashboard() {
    const [stats, setStats] = useState({
        groupsCount: 0,
        studentsCount: 0,
        activeSessions: 0,
        avgScore: '—',
    });
    const [loading, setLoading] = useState(true);
    const [user, setUser] = useState(null);

    useEffect(() => {
        const stored = localStorage.getItem('admin_user');
        if (stored) setUser(JSON.parse(stored));

        const fetchStats = async () => {
            try {
                const token = localStorage.getItem('admin_token');
                const [groupsRes, studentsRes, sesionesRes] = await Promise.all([
                    fetch('/api/grupos',   { headers: { 'Authorization': `Bearer ${token}` } }),
                    fetch('/api/alumnos',  { headers: { 'Authorization': `Bearer ${token}` } }),
                    fetch('/api/sesiones', { headers: { 'Authorization': `Bearer ${token}` } }),
                ]);

                const newStats = {};
                if (groupsRes.ok)   { const g = await groupsRes.json();   newStats.groupsCount   = Array.isArray(g) ? g.length : 0; }
                if (studentsRes.ok) { const s = await studentsRes.json(); newStats.studentsCount = Array.isArray(s) ? s.length : 0; }
                if (sesionesRes.ok) { const e = await sesionesRes.json(); newStats.activeSessions = Array.isArray(e) ? e.filter(s => s.activa).length : 0; }

                setStats(prev => ({ ...prev, ...newStats }));
            } catch (e) {
                console.error('Error fetching stats', e);
            } finally {
                setLoading(false);
            }
        };

        fetchStats();
    }, []);

    const statCards = [
        { title: 'Total de Grupos',    value: stats.groupsCount,    icon: Users,         color: 'bg-[#dceeb1]' },
        { title: 'Total de Alumnos',   value: stats.studentsCount,  icon: GraduationCap, color: 'bg-[#c5b0f4]' },
        { title: 'Sesiones Activas',   value: stats.activeSessions, icon: Radio,         color: 'bg-[#c8e6cd]' },
        { title: 'Promedio General',   value: stats.avgScore,       icon: Award,         color: 'bg-[#f4ecd6]' },
    ];

    const quickActions = [
        { title: 'Grupos y Alumnos',   desc: 'Gestiona grupos, inscripciones y listas',         href: '/admin/grupos',      icon: Users,       color: 'bg-[#dceeb1]' },
        { title: 'Banco de Preguntas', desc: 'Crea reactivos con soporte LaTeX',                href: '/admin/preguntas',   icon: HelpCircle,  color: 'bg-[#c5b0f4]' },
        { title: 'Evaluaciones',       desc: 'Diseña y asigna cuestionarios por unidad',        href: '/admin/evaluaciones',icon: Clipboard,   color: 'bg-[#f4ecd6]' },
        { title: 'Sesiones QR',        desc: 'Lanza y monitorea sesiones en tiempo real',        href: '/admin/sesiones',    icon: QrCode,      color: 'bg-[#c8e6cd]' },
    ];

    const hour = new Date().getHours();
    const greeting = hour < 12 ? 'Buenos días' : hour < 18 ? 'Buenas tardes' : 'Buenas noches';

    return (
        <AdminLayout>
            <div className="space-y-6 sm:space-y-8">

                {/* ─── Page header ─── */}
                <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <p className="text-sm font-bold text-gray-500 mb-1">
                            {greeting}{user ? `, ${user.name.split(' ')[0]}` : ''} 👋
                        </p>
                        <h2 className="text-3xl sm:text-4xl font-black tracking-tight leading-none">
                            Panel de Control
                        </h2>
                        <p className="text-gray-500 font-medium mt-1.5 text-sm sm:text-base">
                            Resumen del sistema académico de Matemáticas CCH.
                        </p>
                    </div>
                    {/* Live indicator */}
                    <div className="flex items-center gap-2 px-4 py-2 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] self-start sm:self-auto">
                        <span className="relative flex h-2.5 w-2.5">
                            <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75" />
                            <span className="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-600" />
                        </span>
                        <span className="text-xs font-black uppercase tracking-wider">Sistema activo</span>
                    </div>
                </div>

                {/* ─── Stat cards — responsive grid ─── */}
                <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                    {statCards.map((c, i) => (
                        <StatCard key={i} {...c} loading={loading} />
                    ))}
                </div>

                {/* ─── Quick actions + Quick start — side by side on large screens ─── */}
                <div className="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">

                    {/* Quick actions — 2/3 width on xl */}
                    <div className="xl:col-span-2 space-y-4">
                        <div className="flex items-center gap-2">
                            <TrendingUp className="w-5 h-5" />
                            <h3 className="text-lg sm:text-xl font-black">Acceso Rápido</h3>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            {quickActions.map((a, i) => (
                                <ActionCard key={i} {...a} />
                            ))}
                        </div>
                    </div>

                    {/* Quick-start guide — 1/3 width on xl */}
                    <div className="space-y-4">
                        <div className="flex items-center gap-2">
                            <BookOpen className="w-5 h-5" />
                            <h3 className="text-lg sm:text-xl font-black">Guía de Inicio</h3>
                        </div>
                        <div className="bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] p-5 sm:p-6 space-y-4">
                            <Step n={1} text="Crea un grupo escolar con nombre, turno y semestre." />
                            <Step n={2} text="Inscribe alumnos con CSV o individualmente." />
                            <Step n={3} text="Agrega reactivos al Banco de Preguntas (con LaTeX)." />
                            <Step n={4} text="Diseña una evaluación y asígnala al grupo." />
                            <Step n={5} text="Lanza una sesión QR y supervisa en tiempo real." />
                            <Step n={6} text="Revisa respuestas abiertas y consolida calificaciones." />

                            <div className="pt-2 border-t-2 border-black">
                                <div className="flex items-start gap-2 text-xs font-semibold text-gray-500">
                                    <AlertCircle className="w-4 h-4 shrink-0 mt-0.5 text-amber-500" />
                                    <span>¿Necesitas ayuda? Escribe a <strong className="text-black">admin@cch.unam.mx</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* ─── Footer info bar ─── */}
                <div className="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t-2 border-black/10">
                    <p className="text-xs font-semibold text-gray-400">
                        © 2026 Plataforma de Matemáticas CCH · UNAM
                    </p>
                    <div className="flex items-center gap-2">
                        <div className="w-5 h-5 bg-black flex items-center justify-center rounded">
                            <BookOpen className="text-white w-3 h-3" />
                        </div>
                        <span className="text-xs font-black text-gray-500 uppercase tracking-wider">Colegio de Ciencias y Humanidades</span>
                    </div>
                </div>

            </div>
        </AdminLayout>
    );
}
