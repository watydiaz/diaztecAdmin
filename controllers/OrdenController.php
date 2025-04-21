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
        $rutaBase = realpath(__DIR__ . '/../assets/img/') . DIRECTORY_SEPARATOR; // Ruta absoluta compatible con Windows

        // Agregar registro de errores para depuración
        if (!is_writable($rutaBase)) {
            error_log('La carpeta de destino no tiene permisos de escritura: ' . $rutaBase);
        }

        // Depuración: Verificar si los archivos están siendo recibidos correctamente
        error_log('Archivos recibidos: ' . print_r($_FILES, true));

        // Ajustar para manejar múltiples archivos desde el input `imagenes[]`
        if (isset($_FILES['imagenes'])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$key]);
                    $rutaDestino = $rutaBase . $nombreArchivo;

                    // Depuración: Verificar la ruta de destino
                    error_log('Ruta destino para imagen: ' . $rutaDestino);

                    if (move_uploaded_file($tmpName, $rutaDestino)) {
                        $imagenes[] = $rutaDestino;
                        // Depuración: Confirmar que el archivo se movió correctamente
                        error_log('Archivo movido correctamente: ' . $rutaDestino);
                    } else {
                        // Depuración: Registrar si move_uploaded_file falla
                        error_log('Error al mover el archivo a ' . $rutaDestino);
                    }
                } else {
                    // Depuración: Registrar si hay errores en el archivo
                    error_log('Error al subir el archivo: ' . $_FILES['imagenes']['error'][$key]);
                }
            }
        } else {
            // Depuración: Registrar si el input `imagenes[]` no está configurado
            error_log('Input `imagenes[]` no configurado.');
        }

        // Agregar rutas de imágenes al arreglo de datos
        $data['imagen_url'] = implode(',', $imagenes);

        // Validar que cliente_id y usuario_tecnico_id sean válidos
        if (empty($data['cliente_id']) || empty($data['usuario_tecnico_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Cliente o técnico no especificado.']);
            exit();
        }

        // Intentar agregar la orden
        $resultado = $this->ordenModel->agregarOrden($data);

        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Orden registrada exitosamente.']);
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

        if (isset($_FILES['imagenes'])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$key]);
                    $rutaDestino = $rutaBase . $nombreArchivo;
                    if (move_uploaded_file($tmpName, $rutaDestino)) {
                        $imagenes[] = $rutaDestino;
                    }
                }
            }
        }

        // Si no se subieron nuevas imágenes, mantener las existentes
        if (empty($imagenes)) {
            $ordenExistente = $this->ordenModel->obtenerOrdenPorId($id);
            $data['imagen_url'] = $ordenExistente['imagen_url'];
        } else {
            $data['imagen_url'] = implode(',', $imagenes);
        }

        // Asegurar que los campos no modificados conserven su información existente
        $ordenExistente = $this->ordenModel->obtenerOrdenPorId($id);

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
}