<?php
require_once 'models/OrdenModel.php';

class OrdenController {
    private $ordenModel;

    public function __construct() {
        $this->ordenModel = new OrdenModel();
    }

    public function listarOrdenes() {
        $ordenes = $this->ordenModel->obtenerOrdenes();
        include 'views/ordenes.php';
    }

    public function agregarOrden($data) {
        // Manejo de imágenes
        $imagenes = [];
        $rutaBase = realpath(__DIR__ . '/../assets/img/') . DIRECTORY_SEPARATOR; // Ruta absoluta correcta

        if (isset($_FILES['imagenes'])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$key]);
                    $rutaDestino = $rutaBase . $nombreArchivo;

                    if (move_uploaded_file($tmpName, $rutaDestino)) {
                        $imagenes[] = 'assets/img/' . $nombreArchivo; // Guardar ruta relativa
                        error_log('Archivo subido correctamente: ' . $rutaDestino); // Registro para depuración
                    } else {
                        error_log('Error al mover el archivo a ' . $rutaDestino); // Registro de error
                    }
                } else {
                    error_log('Error al subir el archivo: ' . $_FILES['imagenes']['error'][$key]); // Registro de error
                }
            }
        } else {
            $imagenes = []; // Asegurar que sea un array vacío si no hay imágenes
        }

        // Agregar rutas de imágenes al arreglo de datos
        $data['imagen_url'] = implode(',', $imagenes); // Guardar las rutas relativas en la base de datos

        // Validar que cliente_id y usuario_tecnico_id sean válidos
        if (empty($data['cliente_id']) || empty($data['usuario_tecnico_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Cliente o técnico no especificado.']);
            exit();
        }

        // Intentar agregar la orden
        $resultado = $this->ordenModel->agregarOrden($data);
        error_log('Datos enviados al modelo: ' . print_r($data, true)); // Registro de depuración

        header('Content-Type: application/json');
        if ($resultado) {
            // Obtener el último ID insertado
            $lastId = $this->ordenModel->getLastInsertId();
            echo json_encode(['success' => true, 'message' => 'Orden registrada exitosamente.', 'orden_id' => $lastId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la orden.']);
        }
        exit();
    }

    public function obtenerOrden($id) {
        $orden = $this->ordenModel->obtenerOrdenPorId($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'orden' => $orden]);
        exit();
    }

    public function actualizarOrden($id, $data) {
        // Manejo de imágenes
        $imagenes = [];
        $rutaBase = realpath(__DIR__ . '/../assets/img/') . DIRECTORY_SEPARATOR;

        if (isset($_FILES['imagenes']) && isset($_FILES['imagenes']['tmp_name']) && !empty($_FILES['imagenes']['tmp_name'][0])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$key]);
                    $rutaDestino = $rutaBase . $nombreArchivo;

                    if (move_uploaded_file($tmpName, $rutaDestino)) {
                        $imagenes[] = 'assets/img/' . $nombreArchivo; // Guardar ruta relativa
                    }
                }
            }
        }

        // Obtener la orden existente para conservar datos no modificados
        $ordenExistente = $this->ordenModel->obtenerOrdenPorId($id);

        // Si no se subieron imágenes, mantener las anteriores
        if (empty($imagenes)) {
            $data['imagen_url'] = $ordenExistente['imagen_url'];
        } else {
            $data['imagen_url'] = implode(',', $imagenes);
        }

        $data['cliente_id'] = $data['cliente_id'] ?? $ordenExistente['cliente_id'];
        $data['usuario_tecnico_id'] = $data['usuario_tecnico_id'] ?? $ordenExistente['usuario_tecnico_id'];
        $data['fecha_ingreso'] = $data['fecha_ingreso'] ?? $ordenExistente['fecha_ingreso'];
        $data['fecha_entrega_estimada'] = $data['fecha_entrega_estimada'] ?? $ordenExistente['fecha_entrega_estimada'];
        $data['marca'] = $data['marca'] ?? $ordenExistente['marca'];
        $data['modelo'] = $data['modelo'] ?? $ordenExistente['modelo'];
        $data['imei_serial'] = $data['imei_serial'] ?? $ordenExistente['imei_serial'];
        $data['falla_reportada'] = $data['falla_reportada'] ?? $ordenExistente['falla_reportada'];
        $data['diagnostico'] = $data['diagnostico'] ?? $ordenExistente['diagnostico'];
        $data['estado'] = $data['estado'] ?? $ordenExistente['estado'];
        $data['prioridad'] = $data['prioridad'] ?? $ordenExistente['prioridad'];
        $data['contraseña_equipo'] = $data['contraseña_equipo'] ?? $ordenExistente['contraseña_equipo'];

        // Actualizar la orden
        $resultado = $this->ordenModel->actualizarOrden($id, $data);

        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Orden actualizada exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la orden.']);
        }
        exit();
    }

    public function eliminarOrden($id) {
        $this->ordenModel->eliminarOrden($id);
        header('Location: index.php?action=ordenes');
        exit();
    }

    public function generarRemision($id) {
        // Obtener los datos de la orden
        $orden = $this->ordenModel->obtenerOrdenPorId($id);

        if (!$orden) {
            echo "<h1>Orden no encontrada</h1>";
            exit();
        }

        // Incluir la vista de la remisión
        include 'views/remision.php';
    }

    public function cambiarEstadoEntregado($id) {
        $resultado = $this->ordenModel->actualizarEstadoOrden($id, 'entregado');

        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Estado cambiado a entregado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado de la orden.']);
        }
        exit();
    }

    public function cambiarEstadoTerminado($id) {
        $resultado = $this->ordenModel->actualizarEstadoOrden($id, 'terminado');
        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Estado cambiado a terminado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado de la orden.']);
        }
        exit();
    }
}