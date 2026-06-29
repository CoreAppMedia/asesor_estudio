import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import Login from './views/Login';
import Dashboard from './views/admin/Dashboard';
import Grupos from './views/admin/Grupos';
import Home from './views/Home';
import VerUnidad from './views/VerUnidad';
import Preguntas from './views/admin/Preguntas';
import ResolverCuestionario from './views/ResolverCuestionario';
import Evaluaciones from './views/admin/Evaluaciones';
import Sesiones from './views/admin/Sesiones';
import AccesoSesion from './views/AccesoSesion';
import ResolverSesion from './views/ResolverSesion';
import RevisarSesion from './views/admin/RevisarSesion';
import InscribirseGrupo from './views/InscribirseGrupo';

// Ruta protegida que valida si existe un token de sesión
function ProtectedRoute({ children }) {
    const token = localStorage.getItem('admin_token');
    return token ? children : <Navigate to="/login" replace />;
}

function App() {
    return (
        <BrowserRouter>
            <Routes>
                {/* Rutas Públicas */}
                <Route path="/" element={<Home />} />
                <Route path="/unidades/:unidadId" element={<VerUnidad />} />
                <Route path="/unidades/:unidadId/cuestionario" element={<ResolverCuestionario />} />
                <Route path="/acceso" element={<AccesoSesion />} />
                <Route path="/sesion/resolver" element={<ResolverSesion />} />
                <Route path="/grupos/:grupoId/inscribirse" element={<InscribirseGrupo />} />

                {/* Ruta de Login */}
                <Route path="/login" element={<Login />} />

                {/* Rutas Administrativas Protegidas */}
                <Route 
                    path="/admin/dashboard" 
                    element={
                        <ProtectedRoute>
                            <Dashboard />
                        </ProtectedRoute>
                    } 
                />
                <Route 
                    path="/admin/grupos" 
                    element={
                        <ProtectedRoute>
                            <Grupos />
                        </ProtectedRoute>
                    } 
                />
                <Route 
                    path="/admin/preguntas" 
                    element={
                        <ProtectedRoute>
                            <Preguntas />
                        </ProtectedRoute>
                    } 
                />
                <Route 
                    path="/admin/evaluaciones" 
                    element={
                        <ProtectedRoute>
                            <Evaluaciones />
                        </ProtectedRoute>
                    } 
                />
                <Route 
                    path="/admin/sesiones" 
                    element={
                        <ProtectedRoute>
                            <Sesiones />
                        </ProtectedRoute>
                    } 
                />
                <Route 
                    path="/admin/sesiones/:sesionId/revision" 
                    element={
                        <ProtectedRoute>
                            <RevisarSesion />
                        </ProtectedRoute>
                    } 
                />

                {/* Redirección por defecto */}
                <Route path="*" element={<Navigate to="/" replace />} />
            </Routes>
        </BrowserRouter>
    );
}

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<App />);
}
