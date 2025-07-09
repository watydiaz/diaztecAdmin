<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Diaztecnologia</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 25%, #4a4a4a 50%, #6a6a6a 75%, #8a8a8a 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .dashboard-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        .card-icon {
            font-size: 3rem;
            opacity: 0.8;
            float: right;
            margin-top: -10px;
        }
        .metric-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .metric-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            height: 400px;
            position: relative;
        }
        .chart-container canvas {
            max-height: 320px !important;
            height: 320px !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .chart-container-small {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            height: 350px;
            position: relative;
        }
        .chart-container-small canvas {
            max-height: 270px !important;
            height: 270px !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .gradient-primary { background: linear-gradient(45deg, #667eea, #764ba2); }
        .gradient-success { background: linear-gradient(45deg, #56ab2f, #a8e6cf); }
        .gradient-warning { background: linear-gradient(45deg, #f093fb, #f5576c); }
        .gradient-info { background: linear-gradient(45deg, #4facfe, #00f2fe); }
        .gradient-danger { background: linear-gradient(45deg, #fa709a, #fee140); }
        .gradient-secondary { background: linear-gradient(45deg, #a8edea, #fed6e3); }
        .productivity-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        /* Responsive fixes */
        @media (max-width: 768px) {
            .chart-container {
                height: 300px;
            }
            .chart-container canvas {
                max-height: 220px !important;
                height: 220px !important;
            }
            .chart-container-small {
                height: 280px;
            }
            .chart-container-small canvas {
                max-height: 200px !important;
                height: 200px !important;
            }
            .card-stats {
                margin-bottom: 15px;
            }
            .productivity-section {
                padding: 15px;
                margin: 15px 0;
            }
            /* En móviles, hacer que el gráfico de dona ocupe el ancho completo */
            .col-md-4 {
                margin-top: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .chart-container, .chart-container-small {
                height: 250px;
                padding: 15px;
            }
            .chart-container canvas, .chart-container-small canvas {
                max-height: 180px !important;
                height: 180px !important;
            }
            .metric-value {
                font-size: 1.5rem !important;
            }
            .card-stats {
                padding: 15px;
            }
        }
        
        /* Evitar overflow en contenedores */
        .container-fluid {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        .dashboard-container {
            overflow-x: hidden;
            width: 100%;
        }
        
        .row {
            margin-left: -10px;
            margin-right: -10px;
        }
        
        .col-md-3, .col-md-4, .col-md-6, .col-md-8 {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        /* Asegurar que los canvas no se salgan de sus contenedores */
        canvas {
            display: block;
            box-sizing: border-box;
        }
        .section-title {
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 3px solid #4a6fa5;
            padding-bottom: 10px;
        }
        .welcome-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            color: #212529;
            border: 2px solid #ced4da;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        <div class="container">
            <!-- Header de bienvenida -->
            <div class="welcome-header">
                <h1><i class="fas fa-tachometer-alt me-3"></i>Dashboard Diaztecnologia</h1>
                <p class="mb-0">Centro de Control y Métricas de Rendimiento</p>
                <small id="fechaActual"></small>
            </div>

            <!-- Métricas principales -->
            <div class="stats-grid">
                <!-- Clientes -->
                <div class="card dashboard-card text-white gradient-primary">
                    <div class="card-body">
                        <i class="fas fa-users card-icon"></i>
                        <div class="metric-label">Clientes Registrados</div>
                        <div class="metric-value" id="clientesRegistrados">0</div>
                    </div>
                </div>

                <!-- Órdenes Pendientes -->
                <div class="card dashboard-card text-white gradient-warning">
                    <div class="card-body">
                        <i class="fas fa-clock card-icon"></i>
                        <div class="metric-label">Órdenes Pendientes</div>
                        <div class="metric-value" id="ordenesPendientes">0</div>
                    </div>
                </div>

                <!-- Órdenes en Proceso -->
                <div class="card dashboard-card text-white gradient-info">
                    <div class="card-body">
                        <i class="fas fa-cogs card-icon"></i>
                        <div class="metric-label">En Proceso</div>
                        <div class="metric-value" id="ordenesEnProceso">0</div>
                    </div>
                </div>

                <!-- Órdenes Terminadas -->
                <div class="card dashboard-card text-white gradient-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle card-icon"></i>
                        <div class="metric-label">Terminadas</div>
                        <div class="metric-value" id="ordenesTerminadas">0</div>
                    </div>
                </div>

                <!-- Órdenes Entregadas -->
                <div class="card dashboard-card text-white gradient-secondary">
                    <div class="card-body">
                        <i class="fas fa-handshake card-icon"></i>
                        <div class="metric-label">Entregadas</div>
                        <div class="metric-value" id="ordenesEntregadas">0</div>
                    </div>
                </div>

                <!-- Saldos Pendientes -->
                <div class="card dashboard-card text-white gradient-danger">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle card-icon"></i>
                        <div class="metric-label">Saldos Pendientes</div>
                        <div class="metric-value" id="saldosPendientes">$0</div>
                    </div>
                </div>
            </div>

            <!-- Sección de Productividad -->
            <div class="row">
                <div class="col-md-6">
                    <div class="productivity-section">
                        <h4 class="section-title"><i class="fas fa-chart-line me-2"></i>Ventas Diarias</h4>
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="metric-value text-success" id="pagosDiarios">$0</div>
                                <div class="metric-label text-muted">Hoy</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="metric-value text-info" id="pagosMensuales">$0</div>
                                <div class="metric-label text-muted">Este Mes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="productivity-section">
                        <h4 class="section-title"><i class="fas fa-bolt me-2"></i>Productividad</h4>
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="metric-value text-primary" id="ordenesHoy">0</div>
                                <div class="metric-label text-muted">Órdenes Hoy</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="metric-value text-warning" id="tiempoPromedio">0d</div>
                                <div class="metric-label text-muted">Tiempo Prom.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-container">
                        <h5><i class="fas fa-chart-area me-2"></i>Evolución de Ventas (Últimos 7 días)</h5>
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container-small">
                        <h5><i class="fas fa-chart-pie me-2"></i>Estados de Órdenes</h5>
                        <canvas id="ordenesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top productos -->
            <div class="productivity-section">
                <h4 class="section-title"><i class="fas fa-trophy me-2"></i>Top Productos Más Vendidos</h4>
                <div class="row" id="topProductos">
                    <div class="col-12 text-center text-muted">
                        <i class="fas fa-spinner fa-spin me-2"></i>Cargando productos...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales para los gráficos
        let ventasChart, ordenesChart;

        // Función para redimensionar gráficos
        function resizeCharts() {
            if (ventasChart) {
                ventasChart.resize();
            }
            if (ordenesChart) {
                ordenesChart.resize();
            }
        }

        // Escuchar cambios de tamaño de ventana
        window.addEventListener('resize', function() {
            setTimeout(resizeCharts, 100);
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar fecha actual
            const ahora = new Date();
            const opciones = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            document.getElementById('fechaActual').textContent = 
                ahora.toLocaleDateString('es-ES', opciones);

            // Función para formatear números
            function formatMiles(num) {
                return parseInt(num).toLocaleString('es-CO');
            }

            // Cargar métricas principales
            fetch('index.php?action=obtenerMetricas')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('clientesRegistrados').textContent = data.clientesRegistrados;
                        document.getElementById('ordenesPendientes').textContent = data.ordenesPendientes;
                        document.getElementById('ordenesEnProceso').textContent = data.ordenesEnProceso;
                        document.getElementById('ordenesTerminadas').textContent = data.ordenesTerminadas;
                        document.getElementById('ordenesEntregadas').textContent = data.ordenesEntregadas;
                        
                        // Crear gráfico de órdenes
                        crearGraficoOrdenes(data);
                    }
                })
                .catch(error => {
                    console.error('Error al obtener métricas:', error);
                });

            // Cargar datos de pagos
            fetch('index.php?action=obtenerPagosDashboard')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let pagosDiarios = parseInt(data.pagosDiarios) || 0;
                        let pagosMensuales = parseInt(data.pagosMensuales) || 0;
                        document.getElementById('pagosDiarios').textContent = `$${formatMiles(pagosDiarios)}`;
                        document.getElementById('pagosMensuales').textContent = `$${formatMiles(pagosMensuales)}`;
                    }
                })
                .catch(error => {
                    console.error('Error al obtener pagos:', error);
                });

            // Cargar saldos pendientes
            fetch('index.php?action=obtenerSaldosPendientes')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let saldos = parseInt(data.saldosPendientes) || 0;
                        document.getElementById('saldosPendientes').textContent = `$${formatMiles(saldos)}`;
                    }
                })
                .catch(error => {
                    console.error('Error al obtener saldos:', error);
                });

            // Cargar datos de productividad
            cargarProductividad();
            
            // Cargar gráfico de ventas
            cargarGraficoVentas();
            
            // Cargar top productos
            cargarTopProductos();
        });

        function crearGraficoOrdenes(data) {
            // Destruir gráfico existente si existe
            if (ordenesChart) {
                ordenesChart.destroy();
            }
            
            const ctx = document.getElementById('ordenesChart').getContext('2d');
            ordenesChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pendientes', 'En Proceso', 'Terminadas', 'Entregadas'],
                    datasets: [{
                        data: [
                            data.ordenesPendientes || 0,
                            data.ordenesEnProceso || 0,
                            data.ordenesTerminadas || 0,
                            data.ordenesEntregadas || 0
                        ],
                        backgroundColor: [
                            '#f093fb',
                            '#4facfe',
                            '#56ab2f',
                            '#a8edea'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    },
                    layout: {
                        padding: 10
                    }
                }
            });
        }

        function cargarGraficoVentas() {
            fetch('index.php?action=obtenerVentasSemana')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        crearGraficoVentas(data.ventas);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar ventas:', error);
                    // Crear gráfico con datos vacíos
                    crearGraficoVentas([]);
                });
        }

        function crearGraficoVentas(ventas) {
            // Destruir gráfico existente si existe
            if (ventasChart) {
                ventasChart.destroy();
            }
            
            const ctx = document.getElementById('ventasChart').getContext('2d');
            
            // Últimos 7 días
            const fechas = [];
            const valores = [];
            
            for (let i = 6; i >= 0; i--) {
                const fecha = new Date();
                fecha.setDate(fecha.getDate() - i);
                const fechaStr = fecha.toISOString().split('T')[0];
                fechas.push(fecha.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' }));
                
                const venta = ventas.find(v => v.fecha === fechaStr);
                valores.push(venta ? parseInt(venta.total) : 0);
            }

            ventasChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [{
                        label: 'Ventas ($)',
                        data: valores,
                        borderColor: '#4a6fa5',
                        backgroundColor: 'rgba(74, 111, 165, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString('es-CO');
                                }
                            }
                        }
                    },
                    layout: {
                        padding: 10
                    }
                }
            });
        }

        function cargarProductividad() {
            fetch('index.php?action=obtenerProductividad')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('ordenesHoy').textContent = data.ordenesHoy || 0;
                        document.getElementById('tiempoPromedio').textContent = data.tiempoPromedio || '0d';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar productividad:', error);
                });
        }

        function cargarTopProductos() {
            fetch('index.php?action=obtenerTopProductos')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTopProductos(data.productos);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar productos:', error);
                    document.getElementById('topProductos').innerHTML = 
                        '<div class="col-12 text-center text-muted">Error al cargar productos</div>';
                });
        }

        function mostrarTopProductos(productos) {
            const container = document.getElementById('topProductos');
            
            if (!productos || productos.length === 0) {
                container.innerHTML = '<div class="col-12 text-center text-muted">No hay datos de productos</div>';
                return;
            }

            let html = '';
            productos.slice(0, 3).forEach((producto, index) => {
                const iconos = ['🥇', '🥈', '🥉'];
                html += `
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div style="font-size: 2rem;">${iconos[index]}</div>
                                <h6 class="card-title">${producto.nombre}</h6>
                                <p class="text-muted mb-0">${producto.cantidad_vendida} vendidos</p>
                                <small class="text-success">$${parseInt(producto.total_ventas).toLocaleString('es-CO')}</small>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>