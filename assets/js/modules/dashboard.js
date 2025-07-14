// JS específico para el dashboard

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
    // Cargar métricas del inventario
    cargarMetricasInventario();
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

function cargarMetricasInventario() {
    fetch('index.php?action=obtenerEstadisticasInventario')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.estadisticas;
                // Actualizar métricas del inventario en el dashboard
                document.getElementById('totalProductosInventario').textContent = stats.total_productos || 0;
                const valorCosto = parseFloat(stats.valor_total_costo || 0);
                document.getElementById('valorInventarioCostoDashboard').textContent = '$' + valorCosto.toLocaleString('es-CO');
                document.getElementById('stockBajoDashboard').textContent = stats.productos_stock_bajo || 0;
            }
        })
        .catch(error => {
            console.error('Error al cargar métricas del inventario:', error);
            // Valores por defecto en caso de error
            document.getElementById('totalProductosInventario').textContent = '0';
            document.getElementById('valorInventarioCostoDashboard').textContent = '$0';
            document.getElementById('stockBajoDashboard').textContent = '0';
        });
} 