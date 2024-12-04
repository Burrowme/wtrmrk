<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_FILES['fontFile'])) {
        $file = $_FILES['fontFile'];
        $filePath = '../fonts/' . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $filePath)) {

            echo json_encode(['message' => $file['name'], 'status' => 200]);
        } else {
            echo json_encode(['message' => "Failed to upload font.", 'status' => 500]);
        }
    }
}
