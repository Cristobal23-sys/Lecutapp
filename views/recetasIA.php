<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ingredientes'])) {
        // Obtener los ingredientes del formulario
        $ingredientes = htmlspecialchars(trim($_POST['ingredientes']));
        
        // Configuración de la API de Cohere
        $url = "https://api.cohere.ai/v1/generate"; // Asegúrate de que esta URL sea correcta
        $apiKey = "7NAgt5FA9SapkNF5Iik3zNStHLsNNuybQQnIqP6I"; // Reemplaza con tu clave API

        // Datos para la solicitud
        $data = [
            'model' => 'command-r', // O el modelo que desees usar
            'prompt' => "Crea una receta utilizando los siguientes ingredientes: $ingredientes.",
            'maxTokens' => 150,
            'temperature' => 0.7,
        ];

        // Inicializar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Ejecutar la solicitud
        $response = curl_exec($ch);
        
        if ($response === false) {
            echo "Error en la solicitud: " . curl_error($ch);
            curl_close($ch);
            exit;
        }

        $decoded_response = json_decode($response, true);
        
        if (isset($decoded_response['generations'][0]['text'])) {
            echo "<h2>Receta Generada:</h2><p>" . nl2br($decoded_response['generations'][0]['text']) . "</p>";
        } else {
            echo "No se pudo obtener una receta válida.";
        }

        curl_close($ch);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Recetas</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="ingredientes" placeholder="Ingresa los ingredientes (separados por comas)" required>
        <button type="submit">Generar Receta</button>
    </form>
</body>
</html>