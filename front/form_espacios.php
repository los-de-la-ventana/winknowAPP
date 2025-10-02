

<body>
    <div class="container">
        <div class="card">
            <h1>Gestión de Espacios</h1>
            <p class="subtitle">Sistema de registro y administración de espacios del ITSP</p>
            
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <!-- ESTADÍSTICAS -->
            <div class="stats">
                <?php
                $mysqli = conectarDB();
                $statsQuery = "SELECT 
                    COUNT(*) as total,
                    SUM(capacidad) as capacidad_total,
                    COUNT(CASE WHEN Tipo_salon = 'Aula' THEN 1 END) as aulas,
                    COUNT(CASE WHEN Tipo_salon = 'Taller' THEN 1 END) as talleres,
                    COUNT(CASE WHEN Tipo_salon = 'Laboratorio' THEN 1 END) as laboratorios,
                    COUNT(CASE WHEN Tipo_salon = 'Salon' THEN 1 END) as salones
                FROM Espacios";
                $statsResult = $mysqli->query($statsQuery);
                $stats = $statsResult->fetch_assoc();
                ?>
             
            </div>
            
            <!-- FORMULARIO DE REGISTRO -->
            <h2> Registrar Nuevo Espacio</h2>
            <form method="POST" action="">
                <input type="hidden" name="accion" value="registrar">
                
                <div class="form-group">
                    <label for="num_salon">Número de Salón:</label>
                    <input type="number" id="num_salon" name="num_salon" required 
                           placeholder="Ej: 101, 201, 301..." min="1">
                </div>
                
                <div class="form-group">
                    <label for="capacidad">Capacidad (personas):</label>
                    <input type="number" id="capacidad" name="capacidad" required 
                           placeholder="Ej: 30" min="1" max="200">
                </div>
                
                <div class="form-group">
                    <label for="tipo_salon">Tipo de Espacio:</label>
                    <select id="tipo_salon" name="tipo_salon" required>
                        <option value="">-- Seleccione un tipo --</option>
                        <option value="Aula">📚 Aula</option>
                        <option value="Taller">🔧 Taller</option>
                        <option value="Laboratorio">🧪 Laboratorio</option>
                        <option value="Salon">🏫 Salón</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary"> Registrar Espacio</button>
            </form>
        </div>
        
       
</body>
</html>