import React, { useEffect, useState } from 'react';
import { useNavigate, Link, useLocation } from 'react-router-dom';
import { LayoutDashboard, Users, LogOut, BookOpen, Menu, X, HelpCircle, Clipboard, QrCode } from 'lucide-react';

export default function AdminLayout({ children }) {
    const navigate = useNavigate();
    const location = useLocation();
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [user, setUser] = useState(null);

    useEffect(() => {
        const storedUser = localStorage.getItem('admin_user');
        const token = localStorage.getItem('admin_token');
        if (!token) {
            navigate('/login');
        } else if (storedUser) {
            setUser(JSON.parse(storedUser));
        }
    }, [navigate]);

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

    const navigation = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
        { name: 'Grupos y Alumnos', href: '/admin/grupos', icon: Users },
        { name: 'Banco de Preguntas', href: '/admin/preguntas', icon: HelpCircle },
        { name: 'Evaluaciones', href: '/admin/evaluaciones', icon: Clipboard },
        { name: 'Sesiones QR', href: '/admin/sesiones', icon: QrCode },
    ];

    return (
        <div className="min-h-screen bg-white flex flex-col md:flex-row text-black font-sans">
            {/* Mobile Header */}
            <div className="md:hidden flex items-center justify-between p-4 border-b-2 border-black bg-white z-20">
                <div className="flex items-center gap-2">
                    <div className="w-8 h-8 bg-black flex items-center justify-center rounded">
                        <BookOpen className="text-white w-5 h-5" />
                    </div>
                    <span className="font-bold tracking-tight text-lg">Matemáticas CCH</span>
                </div>
                <button onClick={() => setSidebarOpen(!sidebarOpen)} className="p-1 border-2 border-black rounded bg-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    {sidebarOpen ? <X size={20} /> : <Menu size={20} />}
                </button>
            </div>

            {/* Sidebar */}
            <aside className={`fixed inset-y-0 left-0 z-10 w-64 bg-white border-r-2 border-black flex flex-col justify-between transform ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'} md:translate-x-0 md:static transition-transform duration-200 ease-in-out`}>
                <div className="p-6">
                    {/* Brand */}
                    <div className="hidden md:flex items-center gap-3 mb-10">
                        <div className="w-10 h-10 bg-black flex items-center justify-center rounded-lg">
                            <BookOpen className="text-white w-6 h-6" />
                        </div>
                        <div>
                            <h1 className="font-black text-xl leading-none">MATEMÁTICAS</h1>
                            <span className="text-xs font-semibold tracking-wider text-gray-500 uppercase">CCH - UNAM</span>
                        </div>
                    </div>

                    {/* Nav Links */}
                    <nav className="space-y-2">
                        {navigation.map((item) => {
                            const Icon = item.icon;
                            const isActive = location.pathname === item.href;
                            return (
                                <Link
                                    key={item.name}
                                    to={item.href}
                                    onClick={() => setSidebarOpen(false)}
                                    className={`flex items-center gap-3 px-4 py-3 rounded-lg border-2 font-bold transition-all ${
                                        isActive
                                            ? 'bg-[#dceeb1] border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] text-black'
                                            : 'border-transparent text-gray-600 hover:text-black hover:border-black hover:bg-gray-50'
                                    }`}
                                >
                                    <Icon className="w-5 h-5" />
                                    {item.name}
                                </Link>
                            );
                        })}
                    </nav>
                </div>

                {/* Sidebar Footer */}
                <div className="p-6 border-t-2 border-black bg-gray-50">
                    {user && (
                        <div className="mb-4">
                            <p className="font-black text-sm">{user.name}</p>
                            <p className="text-xs text-gray-500 truncate">{user.email}</p>
                        </div>
                    )}
                    <button
                        onClick={handleLogout}
                        className="w-full flex items-center justify-center gap-2 px-4 py-2 border-2 border-black rounded-lg font-bold bg-[#efd4d4] hover:bg-red-200 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                    >
                        <LogOut className="w-4 h-4" />
                        Cerrar Sesión
                    </button>
                </div>
            </aside>

            {/* Main Content Area */}
            <main className="flex-1 bg-white p-6 md:p-10 overflow-y-auto max-h-screen">
                <div className="max-w-6xl mx-auto">
                    {children}
                </div>
            </main>
        </div>
    );
}
