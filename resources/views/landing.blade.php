<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Bilingüe Creativo - Inicio</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://placehold.co/1920x1080/a3e635/ffffff?text=Bienvenidos+al+Colegio') no-repeat center center/cover;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Header & Navigation -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <a href="#" class="text-2xl font-bold text-blue-600">Colegio Creativo</a>
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="#nosotros" class="text-gray-600 hover:text-blue-500 transition duration-300">Nosotros</a>
                    <a href="#noticias" class="text-gray-600 hover:text-blue-500 transition duration-300">Noticias</a>
                    <a href="#horarios" class="text-gray-600 hover:text-blue-500 transition duration-300">Horarios</a>
                    <a href="#actividades" class="text-gray-600 hover:text-blue-500 transition duration-300">Actividades</a>
                    <a href="{{ route('fotos') }}" class="text-gray-600 hover:text-blue-500 transition duration-300">Fotos</a>
                    <a href="#matriculas" class="text-gray-600 hover:text-blue-500 transition duration-300">Matrículas</a>
                    <a href="#login" class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition duration-300">Iniciar Sesión</a>
                </div>
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4">
                <a href="#nosotros" class="block py-2 px-4 text-sm text-gray-600 hover:bg-blue-50 rounded">Nosotros</a>
                <a href="#noticias" class="block py-2 px-4 text-sm text-gray-600 hover:bg-blue-50 rounded">Noticias</a>
                <a href="#horarios" class="block py-2 px-4 text-sm text-gray-600 hover:bg-blue-50 rounded">Horarios</a>
                <a href="#actividades" class="block py-2 px-4 text-sm text-gray-600 hover:bg-blue-50 rounded">Actividades</a>
                <a href="{{ route('fotos') }}" class="block py-2 px-4 text-sm text-gray-600 hover:bg-blue-50 rounded">Fotos</a>
                <a href="#matriculas" class="block py-2 px-4 text-sm text-gray-600 hover:bg-blue-50 rounded">Matrículas</a>
                <a href="#login" class="block mt-2 text-center bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition duration-300">Iniciar Sesión</a>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero-section text-white h-96 flex items-center justify-center text-center">
            <div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Educación Inteligente y Creativa</h1>
                <p class="text-lg md:text-xl max-w-2xl">Formando líderes bilingües con un fuerte potencial humano, artístico e intelectual.</p>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="nosotros" class="py-16 md:py-24">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Acerca de Nosotros</h2>
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <img src="https://placehold.co/600x400/3b82f6/ffffff?text=Nuestros+Estudiantes" alt="Estudiantes sonriendo" class="rounded-lg shadow-xl">
                    </div>
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-xl text-blue-600 mb-2">¿Quiénes Somos?</h3>
                            <p>Somos un concepto de educación inteligente y creativa. Nuestros profesionales altamente calificados hacen que su hijo reciba una educación con calidad y amor. Nuestros espacios han sido diseñados y creados pensando en desarrollar la creatividad y la inteligencia de nuestros niños, haciendolos sentir como en casa.</p>
                        </div>
                         <div>
                            <h3 class="font-bold text-xl text-blue-600 mb-2">Metodología</h3>
                            <p>Acogemos el constructivismo como modelo educativo, apoyados en la teoría del Aprendizaje Significativo, utilizando las técnicas y estrategias del Superaprendizaje, dirigidas a desarrollar los procesos de aprendizaje de forma holística, de tal manera que se active el interés del estudiante.</p>
                        </div>
                    </div>
                </div>
                 <div class="grid md:grid-cols-2 gap-8 mt-12">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="font-bold text-xl text-blue-600 mb-2">Misión</h3>
                        <p>Ofrecer educación formal con énfasis en bilingüismo y en formación artística a nuestros estudiantes, desarrollando en ellos su potencial humano, sus habilidades artísticas, éticas e intelectuales; enmarcadas en los valores de amor, respeto, solidaridad, tolerancia, amistad y honestidad.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="font-bold text-xl text-blue-600 mb-2">Visión</h3>
                        <p>En el año 2025 seremos la comunidad educativa más reconocida en la localidad 14, en los campos del bilingüismo, desarrollo de actividades artísticas y un alto desempeño en las pruebas de estado, resaltando en nuestros estudiantes la implementación de competencias blandas.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- News Section -->
        <section id="noticias" class="bg-blue-50 py-16 md:py-24">
             <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Últimas Noticias</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- News Card 1 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="https://placehold.co/400x250/fbbf24/ffffff?text=Feria+de+Ciencia" alt="Feria de ciencia" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2">¡Éxito en nuestra Feria de Ciencia 2025!</h3>
                            <p class="text-gray-600 mb-4">Nuestros estudiantes demostraron su ingenio y creatividad en proyectos innovadores.</p>
                            <a href="#" class="text-blue-500 hover:underline">Leer más</a>
                        </div>
                    </div>
                    <!-- News Card 2 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="https://placehold.co/400x250/4ade80/ffffff?text=Día+del+Deporte" alt="Día del deporte" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2">Jornada Deportiva Familiar</h3>
                            <p class="text-gray-600 mb-4">Un día lleno de alegría, competencia sana y espíritu de equipo.</p>
                            <a href="#" class="text-blue-500 hover:underline">Leer más</a>
                        </div>
                    </div>
                    <!-- News Card 3 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="https://placehold.co/400x250/f87171/ffffff?text=Concierto+de+Música" alt="Concierto de música" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2">Concierto de Fin de Año</h3>
                            <p class="text-gray-600 mb-4">Nuestros talentosos músicos ofrecieron una presentación inolvidable.</p>
                            <a href="#" class="text-blue-500 hover:underline">Leer más</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Schedules Section -->
        <section id="horarios" class="py-16 md:py-24">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Nuestros Horarios</h2>
                <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                        <div class="border-r border-gray-200 pr-4">
                            <h3 class="font-bold text-lg text-blue-600">Preschool</h3>
                            <p class="text-gray-500 text-sm">(Prekinder, Kinder, Transición)</p>
                            <p class="font-semibold text-2xl mt-2">7:45 am - 1:30 pm</p>
                            <p class="text-sm mt-1 text-gray-500">Ingreso temprano: 6:45 am</p>
                        </div>
                        <div class="lg:border-r border-gray-200 pr-4">
                             <h3 class="font-bold text-lg text-blue-600">Elementary</h3>
                            <p class="text-gray-500 text-sm">(1° a 5°)</p>
                            <p class="font-semibold text-2xl mt-2">6:30 am - 2:30 pm</p>
                        </div>
                         <div class="border-r border-gray-200 pr-4">
                             <h3 class="font-bold text-lg text-blue-600">Middle School</h3>
                            <p class="text-gray-500 text-sm">(6° a 9°)</p>
                            <p class="font-semibold text-2xl mt-2">6:30 am - 2:30 pm</p>
                        </div>
                        <div>
                             <h3 class="font-bold text-lg text-blue-600">High School</h3>
                            <p class="text-gray-500 text-sm">(10° y 11°)</p>
                            <p class="font-semibold text-2xl mt-2">6:30 am - 2:30 pm</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Activities Section -->
        <section id="actividades" class="bg-blue-50 py-16 md:py-24">
             <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Actividades y Servicios</h2>
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-2xl font-bold mb-6">Actividades Extracurriculares</h3>
                        <ul class="space-y-4">
                           <li class="flex items-start">
                                <span class="bg-green-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3 mt-1">&#10003;</span>
                                <div>
                                    <h4 class="font-semibold">Curso de Inglés</h4>
                                    <p class="text-gray-600 text-sm">Obligatorio para estudiantes nuevos desde 2°. Se debe tomar en Diciembre, Enero y durante el año escolar 2026.</p>
                                </div>
                            </li>
                             <li class="flex items-center">
                                <span class="bg-green-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Ensamble Musical</span>
                            </li>
                             <li class="flex items-center">
                                <span class="bg-green-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Técnica Vocal</span>
                            </li>
                            <li class="flex items-center">
                                <span class="bg-green-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Ballet</span>
                            </li>
                            <li class="flex items-center">
                                <span class="bg-green-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Fútbol</span>
                            </li>
                        </ul>
                    </div>
                     <div>
                        <h3 class="text-2xl font-bold mb-6">Servicios Adicionales</h3>
                        <ul class="space-y-4">
                             <li class="flex items-center">
                                <span class="bg-blue-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Ruta escolar</span>
                            </li>
                             <li class="flex items-center">
                                <span class="bg-blue-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Tienda escolar</span>
                            </li>
                             <li class="flex items-center">
                                <span class="bg-blue-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Onces escolares y Almuerzo</span>
                            </li>
                             <li class="flex items-center">
                                <span class="bg-blue-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Asesoría de tareas</span>
                            </li>
                             <li class="flex items-center">
                                <span class="bg-blue-500 rounded-full text-white text-xs w-5 h-5 flex items-center justify-center mr-3">&#10003;</span>
                                <span class="font-semibold">Jornada adicional (Hasta las 5:00 pm)</span>
                            </li>
                        </ul>
                    </div>
                </div>
             </div>
        </section>

        <!-- Enrollment & Login Section -->
        <section id="matriculas" class="py-16 md:py-24">
            <div class="container mx-auto px-6 text-center">
                 <div class="max-w-2xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Proceso de Matrícula 2026</h2>
                    <p class="text-gray-600 mb-8">
                        ¿Interesado en formar parte de nuestra comunidad educativa? Contáctanos para conocer más sobre el proceso de admisión y matrícula.
                    </p>
                    <a href="#" class="bg-blue-600 text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-blue-700 transition duration-300">Contactar Admisiones</a>
                </div>
                 <div id="login" class="mt-16">
                      <h3 class="text-2xl font-bold mb-4">Portal de Estudiantes y Padres</h3>
                      <p class="text-gray-600 mb-6">Accede a tus calificaciones, comunicados y más.</p>
                       <div class="max-w-sm mx-auto">
                           <form class="bg-white p-8 rounded-xl shadow-lg">
                               <div class="mb-4">
                                   <label for="username" class="block text-left text-gray-700 font-semibold mb-2">Usuario</label>
                                   <input type="text" id="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tu ID de estudiante o correo">
                               </div>
                               <div class="mb-6">
                                   <label for="password" class="block text-left text-gray-700 font-semibold mb-2">Contraseña</label>
                                   <input type="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********">
                               </div>
                               <button type="submit" class="w-full bg-green-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-600 transition duration-300">Ingresar</button>
                           </form>
                       </div>
                 </div>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="font-bold text-xl mb-2">Colegio Bilingüe Creativo</p>
            <p>Dirección: Calle Falsa 123, Bogotá, Colombia</p>
            <p>Teléfono: (601) 555-1234</p>
            <div class="mt-6">
                <a href="#" class="text-gray-400 hover:text-white mx-2">Facebook</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Instagram</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Twitter</a>
            </div>
            <p class="text-gray-500 text-sm mt-8">&copy; 2025 Colegio Bilingüe Creativo. Todos los derechos reservados.</p>
        </div>
    </footer>


    <script>
        // JavaScript for mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when a link is clicked
        const mobileMenuLinks = mobileMenu.getElementsByTagName('a');
        for (let link of mobileMenuLinks) {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        }
    </script>

</body>
</html>