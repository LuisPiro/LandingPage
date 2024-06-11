<?php
// Verificar si se recibio una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_FILES['images'])) {
        // Recibir y sanitizar los datos del formulario
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

        // Carpeta donde se guardaran las imagenes
        $uploads_dir = 'uploads/';
        // Extensiones permitidas
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        // Contador para llevar el control del número de imágenes procesadas
        $uploaded_count = 0;

        // Procesar cada imagen recibida
        foreach ($_FILES['images']['name'] as $key => $image_name) {
            $tmp_name = $_FILES['images']['tmp_name'][$key];
            $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $file_name = uniqid() . '.' . $ext;

            // Verificar si la extension es permitida
            if (in_array($ext, $allowed_ext)) {
                // Mover el archivo al directorio de carga
                if (move_uploaded_file($tmp_name, "$uploads_dir/$file_name")) {
                    $uploaded_count++;
                } else {
                    // Manejar errores al mover el archivo
                    http_response_code(500); // Error interno del servidor
                    exit("Error al mover el archivo.");
                }
            } else {
                // Manejar archivos con extensiones no permitidas
                http_response_code(400); // Solicitud incorrecta
                exit("Error: extension de archivo no permitida.");
            }
        }

        // Notificar al usuario sobre el resultado del envio
        if ($uploaded_count > 0) {
            // Envio exitoso
            http_response_code(200);
            echo "Información enviada correctamente. Imagenes cargadas: $uploaded_count";
        } else {
            // No se cargaron imagenes
            http_response_code(400);
            echo "Error: no se cargaron imagenes.";
        }
    } else {
        // Datos incompletos
        http_response_code(400);
        echo "Error: Todos los campos son obligatorios.";
    }
} else {
    // No se recibio una solicitud POST valida
    http_response_code(405); // Metodo no permitido
    echo "Error: Metodo no permitido.";
}
?>