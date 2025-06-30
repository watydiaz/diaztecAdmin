<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        
    <center><br>
    <h3>Bienvenido al Sistema de Gestión</h3>
    </center>

        <div class="dashboard-links">
            <div class="container mt-5">
                <div class="row">
                    <!-- Card para cantidad de clientes registrados -->
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Clientes Registrados</h5>
                                <p class="card-text" id="clientesRegistrados">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card para cantidad de órdenes pendientes -->
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Órdenes Pendientes</h5>
                                <p class="card-text" id="ordenesPendientes">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card para cantidad de órdenes en proceso -->
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Órdenes en Proceso</h5>
                                <p class="card-text" id="ordenesEnProceso">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card para cantidad de órdenes terminadas -->
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Órdenes Terminadas</h5>
                                <p class="card-text" id="ordenesTerminadas">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card para cantidad de órdenes entregadas -->
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Órdenes Entregadas</h5>
                                <p class="card-text" id="ordenesEntregadas">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card para pagos diarios -->
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pagos de Hoy</h5>
                                <p class="card-text" id="pagosDiarios">$0.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card para pagos mensuales -->
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pagos del Mes</h5>
                                <p class="card-text" id="pagosMensuales">$0.00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('index.php?action=obtenerMetricas')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('clientesRegistrados').textContent = data.clientesRegistrados;
                        document.getElementById('ordenesPendientes').textContent = data.ordenesPendientes;
                        document.getElementById('ordenesEnProceso').textContent = data.ordenesEnProceso;
                        document.getElementById('ordenesTerminadas').textContent = data.ordenesTerminadas;
                        document.getElementById('ordenesEntregadas').textContent = data.ordenesEntregadas;
                    } else {
                        alert('Error al obtener las métricas: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al obtener las métricas:', error);
                });

            // Cargar pagos diarios y mensuales
            fetch('index.php?action=obtenerPagosDashboard')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Formatear con separador de miles y sin decimales
                        function formatMilesSinDecimales(num) {
                            return parseInt(num).toLocaleString('es-CO');
                        }
                        let pagosDiarios = parseInt(data.pagosDiarios) || 0;
                        let pagosMensuales = parseInt(data.pagosMensuales) || 0;
                        document.getElementById('pagosDiarios').textContent = `$${formatMilesSinDecimales(pagosDiarios)}`;
                        document.getElementById('pagosMensuales').textContent = `$${formatMilesSinDecimales(pagosMensuales)}`;
                        document.getElementById('pagosDiarios').style.fontWeight = '';
                        document.getElementById('pagosDiarios').style.fontSize = '';
                        document.getElementById('pagosMensuales').style.fontWeight = '';
                        document.getElementById('pagosMensuales').style.fontSize = '';
                    } else {
                        document.getElementById('pagosDiarios').textContent = 'Error';
                        document.getElementById('pagosMensuales').textContent = 'Error';
                    }
                })
                .catch(error => {
                    document.getElementById('pagosDiarios').textContent = 'Error';
                    document.getElementById('pagosMensuales').textContent = 'Error';
                });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>