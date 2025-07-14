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
    <link rel="stylesheet" href="assets/css/modules/dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        <div class="container-fluid">
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
                <div class="card dashboard-card text-white" style="background: linear-gradient(45deg, #232526, #4a6fa5);">
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

                <!-- MÉTRICAS DE INVENTARIO -->
                
                <!-- Total Productos en Inventario -->
                <div class="card dashboard-card text-white" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                    <div class="card-body">
                        <i class="fas fa-cubes card-icon"></i>
                        <div class="metric-label">Total Productos</div>
                        <div class="metric-value" id="totalProductosInventario">0</div>
                        <small class="opacity-75">En inventario</small>
                    </div>
                </div>

                <!-- Valor Total del Inventario (Costo) -->
                <div class="card dashboard-card text-white" style="background: linear-gradient(45deg, #56ab2f, #a8e6cf);">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart card-icon"></i>
                        <div class="metric-label">Valor Inventario</div>
                        <div class="metric-value" id="valorInventarioCostoDashboard">$0</div>
                        <small class="opacity-75">Precio de costo</small>
                    </div>
                </div>

                <!-- Stock Bajo -->
                <div class="card dashboard-card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle card-icon"></i>
                        <div class="metric-label">Stock Bajo</div>
                        <div class="metric-value" id="stockBajoDashboard">0</div>
                        <small class="opacity-75">Productos críticos</small>
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

    <script src="assets/js/modules/dashboard.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>