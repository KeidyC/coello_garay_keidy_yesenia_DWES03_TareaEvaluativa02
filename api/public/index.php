<?php
require '../core/Router.php';
require '../app/controllers/BookController.php';

$router = new Router();
$url = $_SERVER['QUERY_STRING'];

//echo 'URL = ' .$url. '<br>';
// Rutas 
$router->add('/public/books/get', array(
    'controller' => 'BookController',
    'action' => 'getAllBooks'
));

$router->add('/public/books/get/{id}', array(
    'controller' => 'BookController',
    'action' => 'getBooksById'
));

$router->add('/public/books/create', array(
    'controller' => 'BookController',
    'action' => 'createBooks'
));

$router->add('/public/books/update/{id}', array(
    'controller' => 'BookController',
    'action' => 'updateBooks'
));

$router->add('/public/books/delete/{id}', array(
    'controller' => 'BookController',
    'action' => 'deleteBooks'
));

$urlParams = explode('/',$url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=>''
);

if(!empty($urlParams[2])){
    $urlArray['controller'] = ucwords($urlParams[2]);
    if(!empty($urlParams[3])){
        $urlArray['action'] = $urlParams[3];
        if(!empty($urlParams[4])){
            $urlArray['params'] = $urlParams[4];
        }
    }else{
        $urlArray['action']='index';
    }
}else{
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}

if($router->matchRoutes($urlArray)){
    $method = $_SERVER['REQUEST_METHOD'];
    $params = [];
    if ($method === 'GET') {
        $params[]=intval($urlArray['params']) ?? null;
    } elseif ($method === 'POST') {
        $json = file_get_contents('php://input');
        $params[] = json_decode($json, true);
    } elseif ($method === 'PUT') {
        $id = !empty($urlArray['params']) ? intval($urlArray['params']) : null;
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if ($id !== null && $data !== null) {
            $params[] = $id;  
            $params[] = $data;
        } else {
            http_response_code(400); 
            echo json_encode([
                "message" => "Faltan datos necesarios para procesar la solicitud."
            ]);
            exit;
        }
    } elseif ($method === 'DELETE'){
        $params[]=intval($urlArray['params']) ?? null;
    }

    $controller=$router->getParams()['controller'];
    $action=$router->getParams()['action'];

    $controller = new $controller();
    if(method_exists($controller, $action)){
        $resp = call_user_func_array([$controller, $action],$params);
    }else{
        http_response_code(404); 
        echo json_encode([
            "status" => "ERROR",
            "code" => 404,
            "message" => "Metodo no encontrado en el controlador"
        ]);
    }
}else{
    http_response_code(404); 
    echo json_encode([
        "status" => "ERROR",
        "code" => 404,
        "message" => "Ruta no encontrada: " . $url
    ]);
}
?>