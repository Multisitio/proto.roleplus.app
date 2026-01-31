<?php
// Establece la cabecera para indicar que la respuesta es en JSON
header('Content-Type: application/json');

// Obtiene los datos JSON del cuerpo de la solicitud
$data = file_get_contents('php://input');

// Decodifica los datos JSON en un array asociativo
$dataArray = json_decode($data, true);

// Comprueba si la decodificación fue exitosa
if (json_last_error() === JSON_ERROR_NONE) {
    // Procesa los datos aquí
    // Por ejemplo, puedes guardarlos en una base de datos

    // Simulación de procesamiento de datos (solo para ejemplo)
    // Aquí es donde debes conectar a la base de datos y guardar los datos
    // ...

    // Enviar una respuesta de éxito
    echo json_encode(['message' => 'Datos recibidos y procesados con éxito']);
    http_response_code(200);
} else {
    // Si hubo un error en la decodificación JSON, envía un mensaje de error
    echo json_encode(['message' => 'Error en los datos JSON recibidos']);
    http_response_code(400);
}
