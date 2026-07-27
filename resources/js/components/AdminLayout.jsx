import React, { useEffect, useState } from 'react';
import { useNavigate, Link, useLocation } from 'react-router-dom';
import {
    LayoutDashboard, Users, LogOut, BookOpen,
    Menu, X, HelpCircle, Clipboard, QrCode,
    Home, ChevronRight
} from 'lucide-react';

const navigation = [
    { name: 'Dashboard',         href: '/admin/dashboard',   icon: LayoutDashboard, color: 'bg-[#dceeb1]' },
    { name: 'Grupos y Alumnos',  href: '/admin/grupos',      icon: Users,           color: 'bg-[#c5b0f4]' },
    { name: 'Banco de Preguntas',href: '/admin/preguntas',   icon: HelpCircle,      color: 'bg-[#f4ecd6]' },
    { name: 'Evaluaciones',      href: '/admin/evaluaciones',icon: Clipboard,       color: 'bg-[#c8e6cd]' },
    { name: 'Sesiones QR',       href: '/admin/sesiones',    icon: QrCode,          color: 'bg-[#efd4d4]' },
];

/* ─── Sidebar content — shared between mobile drawer and desktop ─── */
function SidebarContent({ user, location, onLinkClick, onLogout }) {
    return (
        <div className="flex flex-col h-full">
            {/* Brand */}
            <div className="p-6 border-b-2 border-black bg-[#c5b0f4] flex items-center gap-3 shrink-0">
                <div className="w-10 h-10 bg-black flex items-center justify-center rounded-xl shrink-0">
                    <BookOpen className="text-white w-6 h-6" />
                </div>
                <div className="min-w-0">
                    <h1 className="font-black text-lg leading-none tracking-tight truncate">MATEMÁTICAS</h1>
                    <span className="text-xs font-bold text-gray-700 uppercase tracking-wider">CCH — UNAM</span>
                </div>
            </div>

            {/* Nav */}
            <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
                <p className="text-[10px] font-black uppercase tracking-widest text-gray-400 px-2 pb-2">
                    Menú Principal
                </p>
                {navigation.map((item) => {
                    const Icon = item.icon;
                    const isActive = location.pathname === item.href;
                    return (
                        <Link
                            key={item.name}
                            to={item.href}
                            onClick={onLinkClick}
                            className={`group flex items-center gap-3 px-3 py-3 rounded-xl border-2 font-bold text-sm transition-all ${
                                isActive
                                    ? `${item.color} border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-black`
                                    : 'border-transparent text-gray-600 hover:text-black hover:border-black hover:bg-gray-50 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]'
                            }`}
                        >
                            <span className={`w-8 h-8 flex items-center justify-center rounded-lg border-2 border-black shrink-0 transition-all ${
                                isActive ? 'bg-white' : 'bg-gray-100 group-hover:bg-white'
                            }`}>
                                <Icon className="w-4 h-4" />
                            </span>
                            <span className="flex-1 truncate">{item.name}</span>
                            {isActive && <ChevronRight className="w-4 h-4 shrink-0" />}
                        </Link>
                    );
                })}
            </nav>

            {/* Footer */}
            <div className="p-4 border-t-2 border-black bg-gray-50 space-y-2 shrink-0">
                {/* User info */}
                {user && (
                    <div className="flex items-center gap-3 px-3 py-3 bg-white border-2 border-black rounded-xl mb-3 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                        <div className="w-9 h-9 bg-[#c5b0f4] border-2 border-black rounded-lg flex items-center justify-center shrink-0 font-black text-base">
                            {user.name?.charAt(0).toUpperCase()}
                        </div>
                        <div className="min-w-0">
                            <p className="font-black text-sm leading-tight truncate">{user.name}</p>
                            <p className="text-xs text-gray-500 truncate">{user.email}</p>
                        </div>
                    </div>
                )}

                {/* Ver cursos */}
                <Link
                    to="/"
                    onClick={onLinkClick}
                    className="w-full flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-black rounded-xl font-bold text-sm bg-[#f4ecd6] hover:bg-yellow-100 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                >
                    <Home className="w-4 h-4" />
                    Ver Cursos
                </Link>

                {/* Logout */}
                <button
                    onClick={onLogout}
                    className="w-full flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-black rounded-xl font-bold text-sm bg-[#efd4d4] hover:bg-red-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                >
                    <LogOut className="w-4 h-4" />
                    Cerrar Sesión
                </button>
            </div>
        </div>
    );
}

/* ─── Main layout ─── */
export default function AdminLayout({ children }) {
    const navigate   = useNavigate();
    const location   = useLocation();
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [user, setUser] = useState(null);

    useEffect(() => {
        const storedUser = localStorage.getItem('admin_user');
        const token      = localStorage.getItem('admin_token');
        if (!token) {
            navigate('/login');
        } else if (storedUser) {
            setUser(JSON.parse(storedUser));
        }
    }, [navigate]);

    // Close mobile sidebar on location change
    useEffect(() => {
        setSidebarOpen(false);
    }, [location.pathname]);

    const handleLogout = async () => {
        try {
            const token = localStorage.getItem('admin_token');
            await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
        } catch (e) {
            console.error('Logout error', e);
        } finally {
            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            navigate('/login');
        }
    };

    const currentPage = navigation.find(n => n.pathname === location.pathname)?.name
        ?? navigation.find(n => location.pathname.startsWith(n.href))?.name
        ?? 'Panel';

    return (
        <div className="min-h-screen bg-[#f7f7f5] text-black font-sans flex flex-col">

            {/* ─── TOP BAR (all screens) ─── */}
            <header className="sticky top-0 z-30 bg-white border-b-2 border-black flex items-center justify-between px-4 md:px-6 h-14 shrink-0">
                {/* Left: hamburger + brand */}
                <div className="flex items-center gap-3">
                    {/* Hamburger — shown always on mobile, hidden on md+ */}
                    <button
                        onClick={() => setSidebarOpen(v => !v)}
                        className="lg:hidden p-1.5 border-2 border-black rounded-lg bg-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all"
                        aria-label="Abrir menú"
                    >
                        {sidebarOpen ? <X size={18} /> : <Menu size={18} />}
                    </button>

                    {/* Brand visible on mobile (sidebar hidden) */}
                    <div className="flex items-center gap-2 lg:hidden">
                        <div className="w-7 h-7 bg-black flex items-center justify-center rounded-md">
                            <BookOpen className="text-white w-4 h-4" />
                        </div>
                        <span className="font-black tracking-tight text-base leading-none">
                            Matemáticas CCH
                        </span>
                    </div>

                    {/* Breadcrumb on desktop */}
                    <div className="hidden lg:flex items-center gap-2 text-sm text-gray-500 font-semibold">
                        <span className="font-black text-black">Panel Administrativo</span>
                        <ChevronRight className="w-4 h-4" />
                        <span>{navigation.find(n => location.pathname.startsWith(n.href))?.name ?? 'Inicio'}</span>
                    </div>
                </div>

                {/* Right: user chip */}
                {user && (
                    <div className="flex items-center gap-2 px-3 py-1.5 bg-[#f4ecd6] border-2 border-black rounded-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                        <div className="w-6 h-6 bg-black text-white rounded-md flex items-center justify-center font-black text-xs shrink-0">
                            {user.name?.charAt(0).toUpperCase()}
                        </div>
                        <span className="font-bold text-sm hidden sm:block max-w-[140px] truncate">{user.name}</span>
                    </div>
                )}
            </header>

            <div className="flex flex-1 overflow-hidden">
                {/* ─── MOBILE OVERLAY ─── */}
                {sidebarOpen && (
                    <div
                        className="lg:hidden fixed inset-0 z-20 bg-black/40 backdrop-blur-sm"
                        onClick={() => setSidebarOpen(false)}
                    />
                )}

                {/* ─── SIDEBAR ─── */}
                <aside className={`
                    fixed top-14 left-0 z-20 h-[calc(100vh-3.5rem)] w-72 bg-white border-r-2 border-black
                    flex flex-col transform transition-transform duration-200 ease-in-out
                    ${sidebarOpen ? 'translate-x-0 shadow-[8px_0px_0px_0px_rgba(0,0,0,0.15)]' : '-translate-x-full'}
                    lg:translate-x-0 lg:static lg:h-auto lg:w-64 xl:w-72 lg:shrink-0 lg:shadow-none
                `}>
                    <SidebarContent
                        user={user}
                        location={location}
                        onLinkClick={() => setSidebarOpen(false)}
                        onLogout={handleLogout}
                    />
                </aside>

                {/* ─── MAIN CONTENT ─── */}
                <main className="flex-1 overflow-y-auto bg-[#f7f7f5]">
                    <div className="p-4 sm:p-6 lg:p-8 xl:p-10 max-w-7xl mx-auto">
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
