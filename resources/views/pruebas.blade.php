<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio San Andrés - Educación de Excelencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .fade-in.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: left 0.3s ease;
        }
        
        .nav-link:hover::after {
            left: 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-xl">SA</span>
                        </div>
                        <span class="text-white font-bold text-xl">Colegio San Andrés</span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#inicio" class="nav-link text-white hover:text-blue-200 px-3 py-2 text-sm font-medium transition-colors">Inicio</a>
                        <a href="#nosotros" class="nav-link text-white hover:text-blue-200 px-3 py-2 text-sm font-medium transition-colors">Nosotros</a>
                        <a href="#noticias" class="nav-link text-white hover:text-blue-200 px-3 py-2 text-sm font-medium transition-colors">Noticias</a>
                        <a href="#matriculas" class="nav-link text-white hover:text-blue-200 px-3 py-2 text-sm font-medium transition-colors">Matrículas</a>
                        <a href="#actividades" class="nav-link text-white hover:text-blue-200 px-3 py-2 text-sm font-medium transition-colors">Actividades</a>
                        <a href="#fotos" class="nav-link text-white hover:text-blue-200 px-3 py-2 text-sm font-medium transition-colors">Fotos</a>
                        <a href="#login" class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">Iniciar Sesión</a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 glass-effect">
                <a href="#inicio" class="text-white block px-3 py-2 text-base font-medium">Inicio</a>
                <a href="#nosotros" class="text-white block px-3 py-2 text-base font-medium">Nosotros</a>
                <a href="#noticias" class="text-white block px-3 py-2 text-base font-medium">Noticias</a>
                <a href="#matriculas" class="text-white block px-3 py-2 text-base font-medium">Matrículas</a>
                <a href="#actividades" class="text-white block px-3 py-2 text-base font-medium">Actividades</a>
                <a href="#fotos" class="text-white block px-3 py-2 text-base font-medium">Fotos</a>
                <a href="#login" class="text-white block px-3 py-2 text-base font-medium">Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="gradient-bg min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full floating"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-white rounded-full floating" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-20 left-32 w-12 h-12 bg-white rounded-full floating" style="animation-delay: 2s;"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 fade-in">
                Educación de <span class="text-yellow-300">Excelencia</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto fade-in">
                Formamos líderes del futuro con valores sólidos y conocimiento integral
            </p>
            <div class="space-x-4 fade-in">
                <button class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors hover-scale">
                    Conoce Más
                </button>
                <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-colors hover-scale">
                    Matricúlate Ya
                </button>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="nosotros" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Acerca de Nosotros</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Con más de 50 años de experiencia, somos una institución comprometida con la formación integral de nuestros estudiantes
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg hover:shadow-lg transition-shadow fade-in hover-scale">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Excelencia Académica</h3>
                    <p class="text-gray-600">Metodologías innovadoras y profesores altamente calificados</p>
                </div>
                <div class="text-center p-6 rounded-lg hover:shadow-lg transition-shadow fade-in hover-scale">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Formación Integral</h3>
                    <p class="text-gray-600">Desarrollo de habilidades sociales, deportivas y artísticas</p>
                </div>
                <div class="text-center p-6 rounded-lg hover:shadow-lg transition-shadow fade-in hover-scale">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Valores Sólidos</h3>
                    <p class="text-gray-600">Compromiso con la ética, responsabilidad y respeto</p>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="noticias" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Noticias y Eventos</h2>
                <p class="text-xl text-gray-600">Mantente al día con las últimas noticias de nuestra comunidad educativa</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover-scale fade-in">
                    <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                    <div class="p-6">
                        <span class="text-sm text-purple-600 font-semibold">15 Mayo 2025</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Inauguración del Laboratorio de Ciencias</h3>
                        <p class="text-gray-600 mb-4">Nuevas instalaciones equipadas con tecnología de punta para el aprendizaje científico.</p>
                        <a href="#" class="text-purple-600 font-semibold hover:text-purple-800">Leer más →</a>
                    </div>
                </article>
                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover-scale fade-in">
                    <div class="h-48 bg-gradient-to-r from-green-500 to-blue-600"></div>
                    <div class="p-6">
                        <span class="text-sm text-purple-600 font-semibold">10 Mayo 2025</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Festival de Talentos 2025</h3>
                        <p class="text-gray-600 mb-4">Nuestros estudiantes brillaron en el festival anual mostrando sus habilidades artísticas.</p>
                        <a href="#" class="text-purple-600 font-semibold hover:text-purple-800">Leer más →</a>
                    </div>
                </article>
                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover-scale fade-in">
                    <div class="h-48 bg-gradient-to-r from-orange-500 to-red-600"></div>
                    <div class="p-6">
                        <span class="text-sm text-purple-600 font-semibold">5 Mayo 2025</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Programa de Intercambio</h3>
                        <p class="text-gray-600 mb-4">Abrimos convocatoria para nuestro programa de intercambio internacional.</p>
                        <a href="#" class="text-purple-600 font-semibold hover:text-purple-800">Leer más →</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Enrollment Section -->
    <section id="matriculas" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Proceso de Matrículas</h2>
                <p class="text-xl text-gray-600">Únete a nuestra familia educativa. Proceso de admisión 2025-2026</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="fade-in">
                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white font-bold">1</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Solicitud en Línea</h3>
                                <p class="text-gray-600">Completa el formulario de admisión disponible en nuestro portal</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white font-bold">2</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Documentación</h3>
                                <p class="text-gray-600">Entrega de documentos requeridos y certificados académicos</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white font-bold">3</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Evaluación</h3>
                                <p class="text-gray-600">Prueba de conocimientos y entrevista con el estudiante</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white font-bold">4</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Confirmación</h3>
                                <p class="text-gray-600">Notificación de admisión y proceso de matrícula</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="bg-gray-50 p-8 rounded-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Información de Matrícula</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">Fecha límite:</span>
                                <span class="font-semibold">30 de Junio 2025</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">Edades:</span>
                                <span class="font-semibold">3 - 18 años</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">Modalidad:</span>
                                <span class="font-semibold">Presencial</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Horario:</span>
                                <span class="font-semibold">7:00 AM - 3:00 PM</span>
                            </div>
                        </div>
                        <button class="w-full mt-6 gradient-bg text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                            Iniciar Proceso
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section id="actividades" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Actividades Extracurriculares</h2>
                <p class="text-xl text-gray-600">Desarrolla tus talentos y habilidades más allá del aula</p>
            </div>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center hover-scale fade-in">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Robótica</h3>
                    <p class="text-gray-600 text-sm">Programación y construcción de robots</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center hover-scale fade-in">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Deportes</h3>
                    <p class="text-gray-600 text-sm">Fútbol, baloncesto, volleyball y más</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center hover-scale fade-in">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Música</h3>
                    <p class="text-gray-600 text-sm">Coro, banda y clases individuales</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center hover-scale fade-in">
                    <div class="w-16 h-16 gradient-bg rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Teatro</h3>
                    <p class="text-gray-600 text-sm">Obras y desarrollo escénico</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Photos Section -->
    <section id="fotos" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Galería de Fotos</h2>
                <p class="text-xl text-gray-600">Momentos especiales de nuestra comunidad educativa</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-400 to-purple-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-green-400 to-blue-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-purple-400 to-pink-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-pink-400 to-red-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-indigo-400 to-purple-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-teal-400 to-blue-500 aspect-square rounded-lg hover-scale fade-in"></div>
                <div class="bg-gradient-to-br from-orange-400 to-pink-500 aspect-square rounded-lg hover-scale fade-in"></div>
            </div>
        </div>
    </section>

    <!-- Login Section -->
    <section id="login" class="py-20 gradient-bg">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl p-8 fade-in">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Iniciar Sesión</h2>
                    <p class="text-gray-600">Accede a tu portal educativo</p>
                </div>
                <form class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors" placeholder="Ingresa tu usuario">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                        <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors" placeholder="Ingresa tu contraseña">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                        </label>
                        <a href="#" class="text-sm text-purple-600 hover:text-purple-800">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="w-full gradient-bg text-white py-2 px-4 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                        Ingresar
                    </button>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">¿No tienes cuenta? <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold">Regístrate aquí</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-xl">SA</span>
                        </div>
                        <span class="font-bold text-xl">Colegio San Andrés</span>
                    </div>
                    <p class="text-gray-400">Formando líderes del futuro con excelencia académica y valores sólidos.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#nosotros" class="hover:text-white transition-colors">Acerca de Nosotros</a></li>
                        <li><a href="#noticias" class="hover:text-white transition-colors">Noticias</a></li>
                        <li><a href="#matriculas" class="hover:text-white transition-colors">Matrículas</a></li>
                        <li><a href="#actividades" class="hover:text-white transition-colors">Actividades</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>📍 Calle 123 #45-67, Bogotá</li>
                        <li>📞 (601) 234-5678</li>
                        <li>✉️ info@colegiosanandres.edu.co</li>
                        <li>🕒 Lun - Vie: 7:00 AM - 4:00 PM</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-4">Síguenos</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <span class="text-sm font-bold">f</span>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <span class="text-sm font-bold">t</span>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors">
                            <span class="text-sm font-bold">@</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Colegio San Andrés. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Initialize fade-in for elements already in view
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.querySelectorAll('.fade-in').forEach((el, index) => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight) {
                        setTimeout(() => {
                            el.classList.add('active');
                        }, index * 100);
                    }
                });
            }, 100);
        });

        // Navigation background change on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(102, 126, 234, 0.95)';
            } else {
                nav.style.background = 'rgba(255, 255, 255, 0.1)';
            }
        });

        // Form submission handler
        document.querySelector('form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('¡Funcionalidad de login en desarrollo! Esta es una demostración.');
        });

        // Interactive buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', (e) => {
                if (e.target.textContent.includes('Conoce Más') || 
                    e.target.textContent.includes('Matricúlate Ya') || 
                    e.target.textContent.includes('Iniciar Proceso')) {
                    e.preventDefault();
                    alert('¡Gracias por tu interés! Esta funcionalidad estará disponible próximamente.');
                }
            });
        });

        // Gallery hover effect
        document.querySelectorAll('#fotos .aspect-square').forEach((photo, index) => {
            photo.addEventListener('click', () => {
                alert(`Foto ${index + 1} - En una implementación real, aquí se abriría una galería completa.`);
            });
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.getElementById('inicio');
            const rate = scrolled * -0.5;
            
            if (heroSection) {
                heroSection.style.transform = `translateY(${rate}px)`;
            }
        });

        // Counter animation for statistics (you can add stats section if needed)
        function animateCounter(element, start, end, duration) {
            let startTime = null;
            const step = (timestamp) => {
                if (!startTime) startTime = timestamp;
                const progress = Math.min((timestamp - startTime) / duration, 1);
                const current = Math.floor(progress * (end - start) + start);
                element.textContent = current;
                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            };
            requestAnimationFrame(step);
        }

        // Add floating animation to cards on hover
        document.querySelectorAll('.hover-scale').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px) scale(1.02)';
                card.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = '';
            });
        });
    </script>
</body>
</html>