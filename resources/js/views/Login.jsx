import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { BookOpen, Lock, Mail } from 'lucide-react';

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
        <div className="min-h-screen bg-[#f7f7f5] flex items-center justify-center p-6 text-black font-sans">
            <div className="w-full max-w-md bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                {/* Header Banner */}
                <div className="bg-[#c5b0f4] p-8 border-b-2 border-black text-center">
                    <div className="w-12 h-12 bg-black flex items-center justify-center rounded-xl mx-auto mb-4">
                        <BookOpen className="text-white w-7 h-7" />
                    </div>
                    <h2 className="text-2xl font-black tracking-tight">Iniciar Sesión</h2>
                    <p className="text-sm font-bold text-gray-700 mt-1">Plataforma de Matemáticas CCH</p>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="p-8 space-y-6">
                    {error && (
                        <div className="bg-[#efd4d4] border-2 border-black p-4 rounded-xl font-bold text-sm text-red-800">
                            {error}
                        </div>
                    )}

                    <div className="space-y-2">
                        <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Email del Docente</label>
                        <div className="relative">
                            <span className="absolute inset-y-0 left-0 flex items-center pl-3">
                                <Mail className="text-gray-400 w-5 h-5" />
                            </span>
                            <input
                                type="email"
                                required
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                placeholder="ejemplo@cch.unam.mx"
                                className="w-full pl-10 pr-4 py-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all"
                            />
                        </div>
                    </div>

                    <div className="space-y-2">
                        <label className="block text-sm font-black uppercase tracking-wider text-gray-700">Contraseña</label>
                        <div className="relative">
                            <span className="absolute inset-y-0 left-0 flex items-center pl-3">
                                <Lock className="text-gray-400 w-5 h-5" />
                            </span>
                            <input
                                type="password"
                                required
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                placeholder="••••••••"
                                className="w-full pl-10 pr-4 py-3 border-2 border-black rounded-xl font-bold bg-white focus:outline-none focus:bg-gray-50 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full py-4 border-2 border-black rounded-xl font-black bg-[#dceeb1] hover:bg-lime-200 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50"
                    >
                        {loading ? 'Ingresando...' : 'Entrar al Panel'}
                    </button>
                </form>
            </div>
        </div>
    );
}
