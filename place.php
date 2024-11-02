<?php


    // Устанавливаем соединение с базой данных
    $pdo = new PDO(
        'mysql:host=localhost;dbname=flight_pool;charset=utf8', 
        'root', 
        null, 
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    // Получаем токен из заголовков
    $token = getallheaders()['Authorization'];

    $query = $pdo->prepare("SELECT document_number FROM users WHERE api_token = '$token'");
    $query->execute();
    $user = $query->fetch();

    $document_number = $user['document_number'];
    $query = $pdo->prepare("SELECT place_from, place_back FROM passengers WHERE document_number = '$document_number'");
    $query->execute();

    // Получаем результат
    $passengers = $query->fetch();

    echo json_encode([
            'place_from' => $passengers['place_from'],
            'place_back' => $passengers['place_back'],
        ]);


?>
