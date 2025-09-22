<x-app-layout>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Formulario de Registro de Estudiante</h1>
        
        <!-- Pestañas -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('estudiante')" id="tab-estudiante" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                    Estudiante
                </button>
                <button onclick="showTab('padre')" id="tab-padre" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Padre
                </button>
                <button onclick="showTab('acudiente')" id="tab-acudiente" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Acudiente
                </button>
                <button onclick="showTab('documentos')" id="tab-documentos" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Documentos
                </button>
            </nav>
        </div>

        <form id="registrationForm">
            <!-- Sección Estudiante -->
            <div id="content-estudiante" class="tab-content">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Información del Estudiante</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="estudiante_nombre" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos *</label>
                        <input type="text" name="estudiante_apellidos" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                        <select name="estudiante_tipo_documento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Tarjeta de Identidad</option>
                            <option value="3">Registro Civil</option>
                            <option value="4">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Documento *</label>
                        <input type="number" name="estudiante_numero_documento" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento Expedición *</label>
                        <select name="estudiante_depto_expedicion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cundinamarca</option>
                            <option value="2">Antioquia</option>
                            <option value="3">Valle del Cauca</option>
                            <option value="4">Atlántico</option>
                            <option value="5">Santander</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipio Expedición *</label>
                        <select name="estudiante_municipio_expedicion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Bogotá</option>
                            <option value="2">Medellín</option>
                            <option value="3">Cali</option>
                            <option value="4">Barranquilla</option>
                            <option value="5">Bucaramanga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento *</label>
                        <input type="date" name="estudiante_birth" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento Nacimiento *</label>
                        <select name="estudiante_depto_nacimiento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cundinamarca</option>
                            <option value="2">Antioquia</option>
                            <option value="3">Valle del Cauca</option>
                            <option value="4">Atlántico</option>
                            <option value="5">Santander</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipio Nacimiento *</label>
                        <select name="estudiante_municipio_nacimiento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Bogotá</option>
                            <option value="2">Medellín</option>
                            <option value="3">Cali</option>
                            <option value="4">Barranquilla</option>
                            <option value="5">Bucaramanga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sexo *</label>
                        <select name="estudiante_sexo" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Sanguíneo (RH) *</label>
                        <select name="estudiante_rh" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input type="number" name="estudiante_telefono" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                        <input type="email" name="estudiante_correo" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Colegio de Procedencia *</label>
                        <input type="text" name="estudiante_colegio" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grado *</label>
                        <select name="estudiante_grado" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">1°</option>
                            <option value="2">2°</option>
                            <option value="3">3°</option>
                            <option value="4">4°</option>
                            <option value="5">5°</option>
                            <option value="6">6°</option>
                            <option value="7">7°</option>
                            <option value="8">8°</option>
                            <option value="9">9°</option>
                            <option value="10">10°</option>
                            <option value="11">11°</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                        <input type="text" name="estudiante_direccion" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">EPS *</label>
                        <select name="estudiante_eps" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="SURA">SURA</option>
                            <option value="Nueva EPS">Nueva EPS</option>
                            <option value="Sanitas">Sanitas</option>
                            <option value="Compensar">Compensar</option>
                            <option value="Famisanar">Famisanar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Religión *</label>
                        <input type="text" name="estudiante_religion" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alergias *</label>
                        <input type="text" name="estudiante_alergias" required placeholder="Ninguna si no tiene"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Enfermedades *</label>
                        <input type="text" name="estudiante_enfermedades" required placeholder="Ninguna si no tiene"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Sección Padre -->
            <div id="content-padre" class="tab-content hidden">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Información del Padre</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="padre_nombre" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                        <input type="text" name="padre_apellido" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                        <select name="padre_tipo_documento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Cédula de Extranjería</option>
                            <option value="3">Pasaporte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Documento *</label>
                        <input type="number" name="padre_numero_documento" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento Expedición *</label>
                        <select name="padre_depto_expedicion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cundinamarca</option>
                            <option value="2">Antioquia</option>
                            <option value="3">Valle del Cauca</option>
                            <option value="4">Atlántico</option>
                            <option value="5">Santander</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipio Expedición *</label>
                        <select name="padre_municipio_expedicion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Bogotá</option>
                            <option value="2">Medellín</option>
                            <option value="3">Cali</option>
                            <option value="4">Barranquilla</option>
                            <option value="5">Bucaramanga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                        <input type="text" name="padre_direccion" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input type="text" name="padre_telefono" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                        <input type="email" name="padre_correo" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado Laboral *</label>
                        <select name="padre_estado_laboral" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Empleado</option>
                            <option value="2">Independiente</option>
                            <option value="3">Desempleado</option>
                            <option value="4">Pensionado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empresa</label>
                        <input type="text" name="padre_empresa" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Sección Acudiente -->
            <div id="content-acudiente" class="tab-content hidden">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Información del Acudiente</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="acudiente_nombre" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                        <input type="text" name="acudiente_apellido" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                        <select name="acudiente_tipo_documento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Cédula de Extranjería</option>
                            <option value="3">Pasaporte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Documento *</label>
                        <input type="number" name="acudiente_numero_documento" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento Expedición *</label>
                        <select name="acudiente_depto_expedicion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Cundinamarca</option>
                            <option value="2">Antioquia</option>
                            <option value="3">Valle del Cauca</option>
                            <option value="4">Atlántico</option>
                            <option value="5">Santander</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipio Expedición *</label>
                        <select name="acudiente_municipio_expedicion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Bogotá</option>
                            <option value="2">Medellín</option>
                            <option value="3">Cali</option>
                            <option value="4">Barranquilla</option>
                            <option value="5">Bucaramanga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                        <input type="text" name="acudiente_direccion" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input type="text" name="acudiente_telefono" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                        <input type="email" name="acudiente_correo" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parentesco *</label>
                        <select name="acudiente_parentesco" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Padre</option>
                            <option value="2">Madre</option>
                            <option value="3">Abuelo/a</option>
                            <option value="4">Tío/a</option>
                            <option value="5">Hermano/a</option>
                            <option value="6">Otro</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección Documentos -->
            <div id="content-documentos" class="tab-content hidden">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Documentos Requeridos</h2>
                
                <div class="space-y-8">
                    <!-- Documentos del Estudiante -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Documentos del Estudiante</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Documento de Identidad *</label>
                                <input type="file" name="doc_estudiante_documento" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos de los Padres -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Documentos de los Padres</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Documento de Identidad *</label>
                                <input type="file" name="doc_padres_documento" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Certificado Laboral</label>
                                <input type="file" name="doc_padres_certificado_laboral" accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG (Opcional)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Certificado de Ingresos</label>
                                <input type="file" name="doc_padres_certificado_ingresos" accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG (Opcional)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cámara de Comercio</label>
                                <input type="file" name="doc_padres_camara_comercio" accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG (Opcional)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos del Acudiente -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Documentos del Acudiente</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Documento de Identidad *</label>
                                <input type="file" name="doc_acudiente_documento" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de navegación -->
            <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                <button type="button" id="prevBtn" onclick="previousTab()" 
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" 
                        disabled>
                    Anterior
                </button>
                <button type="button" id="nextBtn" onclick="nextTab()" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Siguiente
                </button>
                <button type="submit" id="submitBtn" 
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 hidden">
                    Registrar Estudiante
                </button>
            </div>
        </form>
    </div>

    <script>
        let currentTab = 0;
        const tabs = ['estudiante', 'padre', 'acudiente', 'documentos'];

        function showTab(tabName) {
            // Ocultar todas las pestañas
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remover clase activa de todos los botones de pestaña
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Mostrar la pestaña seleccionada
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Activar el botón de la pestaña seleccionada
            const activeButton = document.getElementById(`tab-${tabName}`);
            activeButton.classList.add('border-blue-500', 'text-blue-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500');

            // Actualizar el índice de la pestaña actual
            currentTab = tabs.indexOf(tabName);
            updateNavigationButtons();
        }

        function nextTab() {
            if (currentTab < tabs.length - 1) {
                currentTab++;
                showTab(tabs[currentTab]);
            }
        }

        function previousTab() {
            if (currentTab > 0) {
                currentTab--;
                showTab(tabs[currentTab]);
            }
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            // Botón anterior
            prevBtn.disabled = currentTab === 0;
            
            // Botón siguiente y enviar
            if (currentTab === tabs.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        // Manejar el envío del formulario
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Aquí puedes agregar la lógica para procesar el formulario
            const formData = new FormData(this);
            
            // Mostrar mensaje de confirmación (puedes personalizar esto)
            alert('¡Formulario enviado exitosamente! Los datos han sido procesados.');
            
            // Aquí podrías enviar los datos a un servidor
            console.log('Datos del formulario:', Object.fromEntries(formData));
        });

        // Inicializar la primera pestaña
        showTab('estudiante');
    </script>
    
  

</x-app-layout>
