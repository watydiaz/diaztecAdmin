<?php
$targetDir = __DIR__ . '/';
$maxFileSize = 2 * 1024 * 1024; // 2MB
$response = ['success' => false];

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $fileName = basename($_FILES['imagen']['name']);
    $fileSize = $_FILES['imagen']['size'];
    $fileType = mime_content_type($fileTmpPath);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($fileType, $allowedTypes)) {
        $response['message'] = 'Tipo de archivo no permitido.';
    } elseif ($fileSize > $maxFileSize) {
        $response['message'] = 'La imagen es demasiado grande (máx 2MB).';
    } else {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $newName = uniqid('prod_', true) . '.' . $ext;
        $destPath = $targetDir . $newName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $response['success'] = true;
            $response['nombre_archivo'] = $newName;
        } else {
            $response['message'] = 'Error al mover el archivo.';
        }
    }
} else {
    $response['message'] = 'No se recibió archivo o hubo error en la subida.';
}
header('Content-Type: application/json');
echo json_encode($response); 