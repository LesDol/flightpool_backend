<?php
$data = $_POST;
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$phone = $data['phone'];
$document_number = $data['document_number'];
$password = $data['password'];
// Подключение к бд
$bd = new PDO(
    'mysql:host=localhost;dbname=flight_pool;charset=utf8', 
    'root', 
    null, 
    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

// Запись данных в бд
$stmt = $bd->prepare("
    insert into users(
        first_name,
        last_name,
        phone,
        document_number,
        password
    )   values(?,?,?,?,?)
"
);
$stmt->execute([
    $first_name,
    $last_name,
    $phone,
    $document_number,
    $password
]);

?>

<?php
// Проверка по номеру телефона
$data = $_POST;
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$phone = $data['phone'];
$document_number = $data['document_number'];
$password = $data['password'];
// Подключение к бд
$bd = new PDO(
    'mysql:host=localhost;dbname=flight_pool;charset=utf8', 
    'root', 
    null, 
    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

// SQL запрос на получение данных
$requestPhone = $bd
        ->query("SELECT phone FROM users WHERE phone = $phone")
        ->fetchAll();
// Если пользователь с таким номером существует, выводим телефон
if(!empty($requestPhone)){
    echo json_encode($requestPhone);
}
// Если пользователя с таким номером не существует, записываем в бд
if (empty($requestPhone)){
        // Запись данных в бд
        $stmt = $bd->prepare("
            insert into users(
            first_name,
            last_name,
            phone,
            document_number,
            password
            )   values(?,?,?,?,?)
        "
        );
        $stmt->execute([
            $first_name,
            $last_name,
            $phone,
            $document_number,
            $password
        ]);
}
?>