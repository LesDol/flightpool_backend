<?php


$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'flight_pool',
    'user' => 'root',
    'password' => '',
];


$pdo = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'] . ';charset=utf8', $dbConfig['user'], $dbConfig['password'], [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);


function searchAirports(PDO $pdo, $query) {

    $stmt = $pdo->prepare("SELECT * FROM airports WHERE name LIKE CONCAT('%', :query, '%') COLLATE utf8_general_ci OR iata LIKE CONCAT('%', :query, '%') COLLATE utf8_general_ci");

    $stmt->bindValue(':query', '%' . strtolower(trim($query)) . '%');

   
    $stmt->execute();


    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
    return $results;
}
try {

    $query = $_GET['query'];

    $airports = searchAirports($pdo, $query);

    if (empty($airports)) {
      
        $response = [
            "data" => [
                "items" => []
            ]
        ];
    } else {
       
        $response = [
            "data" => [
                "items" => array_map(function($airport) {
                    return [
                        "name" => $airport['name'],
                        "iata" => $airport['iata']
                    ];
                }, $airports)
            ]
        ];
    }


    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode([
        "error" => [
            "code" => $e->getCode(),
            "message" => $e->getMessage(),
            "errors" => []
        ]
    ]);
}