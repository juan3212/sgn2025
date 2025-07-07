<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Fotos - Colegio Bilingüe Creativo</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Header & Navigation -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ route('landing') }}" class="text-2xl font-bold text-blue-600">Colegio Creativo</a>
                <a href="{{ route('landing') }}" class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition duration-300">← Volver a Inicio</a>
            </div>
        </nav>
    </header>

    <main>
        <!-- Gallery Section -->
        <section id="gallery" class="py-16 md:py-24">
            <div class="container mx-auto px-6">
                <h1 class="text-3xl md:text-4xl font-bold text-center mb-12">Galería de Fotos</h1>
                <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12">
                    Revive los mejores momentos de nuestra comunidad educativa. Aquí encontrarás fotos de nuestros eventos, actividades diarias y proyectos especiales.
                </p>

                <!-- Photo Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Image 1 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/818cf8/ffffff?text=Arte" alt="Clase de arte" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 2 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/a3e635/ffffff?text=Deportes" alt="Equipo de fútbol" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 3 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/fbbf24/ffffff?text=Ciencia" alt="Experimento de ciencia" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 4 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/60a5fa/ffffff?text=Música" alt="Banda escolar" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 5 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/f87171/ffffff?text=Amistad" alt="Estudiantes sonriendo" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 6 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/4ade80/ffffff?text=Lectura" alt="Club de lectura" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 7 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/f472b6/ffffff?text=Graduación" alt="Ceremonia de graduación" class="w-full h-full object-cover">
                    </div>
                     <!-- Image 8 -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/c084fc/ffffff?text=Tecnología" alt="Clase de computación" class="w-full h-full object-cover">
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
             <p class="text-gray-500 text-sm mt-8">&copy; 2025 Colegio Bilingüe Creativo. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
