import React, { useEffect, useState } from 'react';
import AdminLayout from '../../components/AdminLayout';
import { Users, GraduationCap, Calendar, Award } from 'lucide-react';

export default function Dashboard() {
    const [stats, setStats] = useState({
        groupsCount: 0,
        studentsCount: 0,
        activeSessions: 0,
        avgScore: '8.4',
    });
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchStats = async () => {
            try {
                const token = localStorage.getItem('admin_token');
                const [groupsRes, studentsRes, sesionesRes] = await Promise.all([
                    fetch('/api/grupos', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                    fetch('/api/alumnos', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                    fetch('/api/sesiones', {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                ]);

                const newStats = {};

                if (groupsRes.ok) {
                    const groups = await groupsRes.json();
                    newStats.groupsCount = Array.isArray(groups) ? groups.length : 0;
                }
                if (studentsRes.ok) {
                    const students = await studentsRes.json();
                    newStats.studentsCount = Array.isArray(students) ? students.length : 0;
                }
                if (sesionesRes.ok) {
                    const sesiones = await sesionesRes.json();
                    newStats.activeSessions = Array.isArray(sesiones)
                        ? sesiones.filter(s => s.activa).length
                        : 0;
                }

                setStats(prev => ({ ...prev, ...newStats }));
            } catch (e) {
                console.error('Error fetching stats', e);
            } finally {
                setLoading(false);
            }
        };

        fetchStats();
    }, []);


    const cards = [
        { title: 'Total Grupos', value: stats.groupsCount, icon: Users, color: 'bg-[#dceeb1]' },
        { title: 'Total Alumnos', value: stats.studentsCount, icon: GraduationCap, color: 'bg-[#c5b0f4]' },
        { title: 'Sesiones Activas', value: stats.activeSessions, icon: Calendar, color: 'bg-[#c8e6cd]' },
        { title: 'Promedio General', value: stats.avgScore, icon: Award, color: 'bg-[#f4ecd6]' },
    ];

    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Title */}
                <div>
                    <h2 className="text-4xl font-black tracking-tight mb-2">Panel de Control</h2>
                    <p className="text-gray-600 font-medium">Bienvenido al administrador académico de Matemáticas CCH.</p>
                </div>

                {/* Cards grid */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {cards.map((card, idx) => {
                        const Icon = card.icon;
                        return (
                            <div
                                key={idx}
                                className={`${card.color} border-2 border-black p-6 rounded-2xl shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] flex flex-col justify-between h-40`}
                            >
                                <div className="flex justify-between items-start">
                                    <span className="font-extrabold text-sm uppercase tracking-wider text-gray-800">{card.title}</span>
                                    <div className="p-2 bg-white border-2 border-black rounded-lg">
                                        <Icon className="w-5 h-5" />
                                    </div>
                                </div>
                                <div className="text-4xl font-black">{loading ? '...' : card.value}</div>
                            </div>
                        );
                    })}
                </div>

                {/* Help Panel */}
                <div className="bg-[#f7f7f5] border-2 border-black p-8 rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
                    <h3 className="text-xl font-black mb-3">Guía de Inicio Rápido</h3>
                    <p className="text-gray-700 leading-relaxed mb-6">
                        Para comenzar con las clases, primero dirígete al menú de <strong>Grupos y Alumnos</strong>. Ahí podrás dar de alta las generaciones y grupos escolares correspondientes al semestre actual, y cargar las listas oficiales en formato CSV.
                    </p>
                    <div className="inline-block bg-black text-white px-6 py-2 rounded-full text-sm font-semibold">
                        Soporte técnico: admin@cch.unam.mx
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
