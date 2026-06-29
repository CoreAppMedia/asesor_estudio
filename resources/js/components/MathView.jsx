import React, { useEffect, useRef } from 'react';

export default function MathView({ text, className = '' }) {
    const containerRef = useRef(null);

    const cleanText = text ? text.replace(/\\\\/g, '\\') : '';

    // Primero actualizar el innerHTML con el texto limpio
    useEffect(() => {
        if (!containerRef.current) return;

        // Reemplazar saltos de línea con <br>
        const html = cleanText
            .split('\n')
            .map(line => line)
            .join('<br>');

        containerRef.current.innerHTML = html;

        // Luego procesar LaTeX con KaTeX
        if (window.renderMathInElement) {
            try {
                window.renderMathInElement(containerRef.current, {
                    delimiters: [
                        { left: '$$', right: '$$', display: true },
                        { left: '$', right: '$', display: false },
                        { left: '\\(', right: '\\)', display: false },
                        { left: '\\[', right: '\\]', display: true }
                    ],
                    throwOnError: false
                });
            } catch (e) {
                console.error('KaTeX rendering error', e);
            }
        }
    }, [cleanText]);

    return (
        <div
            ref={containerRef}
            className={`leading-relaxed ${className}`}
        />
    );
}
