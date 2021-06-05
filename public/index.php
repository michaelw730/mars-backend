<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\SQLiteConnection;

require __DIR__ . '/../vendor/autoload.php';

DEFINE('DBFILE', "db/phpsqlite.db");

$app = AppFactory::create();

//default
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

//create db
$app->post('/db', function (Request $request, Response $response, $args) {
    //create db
    $result = "";
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    if ($pdo != null) {
        $result = 'Connected to the SQLite database successfully!';
    } else {
        $result = 'Whoops, could not connect to the SQLite database!';
    }
    $create_tables_sql = file_get_contents("sql/create_tables.sql");
    $pdo->exec($create_tables_sql);
    
    //output
    $payload = json_encode(['result' => $result], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//delete db
$app->delete('/db', function (Request $request, Response $response, $args) {
    //delete db
    $result = true;
    
    if (file_exists(DBFILE)) {
        unlink(DBFILE);
    }
    
    //output
    $payload = json_encode(['result' => $result], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//seed db
$app->post('/dbseed', function (Request $request, Response $response, $args) {
    //connect db
    $result = "";
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    if ($pdo != null) {
        $result = 'Connected to the SQLite database successfully!';
    } else {
        $result = 'Whoops, could not connect to the SQLite database!';
    }
    $sql = file_get_contents("sql/insert_seed_data.sql");
    $pdo->exec($sql);
    
    //output
    $payload = json_encode(['result' => $result], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//get items
$app->get('/items[/[{id}]]', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    if (isset($args['id'])) {
        $id = $args['id'];
        $params[":id"] = $id;
        $sql = "SELECT * FROM item WHERE id = :id";
    } else {
        $sql = "SELECT * FROM item";
    }
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
   
    $payload = json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//post items
$app->post('/items', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    $json = $request->getBody();
    $data = json_decode($json, true);

    $params[":description"] = $data['description'];
    $params[":weight"] = $data['weight'];
    $params[":category_id"] = $data['category_id'];

    $sql = "INSERT INTO item (description, weight, category_id) 
    VALUES (:description, :weight, :category_id);";
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
    $id = $pdo->lastInsertId();
   
    $payload = json_encode($id);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//patch items
$app->patch('/items/{id}', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    $json = $request->getBody();
    $data = json_decode($json, true);

    $id = $args['id'];

    $params[":id"] = $id;
    $params[":description"] = $data['description'];
    $params[":weight"] = $data['weight'];
    $params[":category_id"] = $data['category_id'];

    $sql = "UPDATE item 
    SET  description = :description,
        weight = :weight, 
        category_id = :category_id 
    WHERE id = :id;";
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
   
    $payload = json_encode(true);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//delete item
$app->delete('/items/{id}', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    $id = $args['id'];
    $params[":id"] = $id;
    $sql = "DELETE FROM item WHERE id = :id";
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
   
    $payload = json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


//get categories
$app->get('/categories[/[{id}]]', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    if (isset($args['id'])) {
        $id = $args['id'];
        $params[":id"] = $id;
        $sql = "SELECT * FROM category WHERE id = :id";
    } else {
        $sql = "SELECT * FROM category";
    }
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
   
    $payload = json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//delete category
$app->delete('/categories/{id}', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    $id = $args['id'];
    $params[":id"] = $id;
    $sql = "DELETE FROM category WHERE id = :id";
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
   
    $payload = json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//post category
$app->post('/categories', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    $json = $request->getBody();
    $data = json_decode($json, true);

    $params[":name"] = $data['name'];
    $params[":priority"] = $data['priority'];

    $sql = "INSERT INTO category (name, priority) 
    VALUES (:name, :priority);";
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);

    $id = $pdo->lastInsertId();
   
    $payload = json_encode($id);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

//patch categories
$app->patch('/categories/{id}', function (Request $request, Response $response, $args) {
    $result = "";
    $params = array();
    $json = $request->getBody();
    $data = json_decode($json, true);

    $id = $args['id'];

    $params[":id"] = $id;
    $params[":name"] = $data['name'];
    $params[":priority"] = $data['priority'];

    $sql = "UPDATE category 
    SET name = :name, 
        priority = :priority
    WHERE id = :id;";
    
    $pdo = (new SQLiteConnection())->connect(DBFILE);
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($params);
   
    $payload = json_encode(true);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
