<?php
require_once '../app/models/BookModel.php';

class BookController {

    // GET
    function getAllBooks( ){
        // Llamamos al modelo para obtener todos los libros
        $books = BookModel::getAllBooks();

        if (empty($books)) {
            http_response_code(404);
            echo json_encode([
                "status" => "ERROR",
                "code" => 404,
                "response" => []
            ]);
        } else {
            echo json_encode([
                "status" => "OK",
                "code" => 200,
                "response" => $books 
            ], JSON_PRETTY_PRINT);
        }
    }

    function getBooksById($id ){
    // Llamamos al modelo para obtener el libro por id
        $book = BookModel::getBookById($id);

        if ($book) {
            http_response_code(200); 
            echo json_encode([
                "message" => "Libro encontrado",
                "data" => $book->jsonSerialize()  
            ], JSON_PRETTY_PRINT);  
        }else {
            http_response_code(404); 
            echo json_encode([
                "message" => "Libro no encontrado",
                "data" => []
            ]);
        }
    }

    // POST
    function createBooks($data ){
        if (empty($data['title']) || empty($data['author']) || empty($data['category']) || empty($data['price']) || empty($data['stock'])) {
            http_response_code(400); 
            echo json_encode([
                "message" => "Te falta algun campo por llenar"
            ]);
            return;
        }
        // Crear el libro en el modelo
        $newBook = BookModel::createBook($data);
        http_response_code(201); 
        echo json_encode([
            "message" => "Libro creado correctamente",
            "data" => $newBook 
        ], JSON_PRETTY_PRINT);
    }

    // PUT
    function updateBooks($id, $data){
        // Validar datos requeridos
        if (!isset($data['price']) && !isset($data['stock'])) {
            http_response_code(400); 
            echo json_encode([
                "message" => "Error al actualizar"
            ]);
            return;
        }

        // Actualizar el libro en el modelo
        $updatedBook = BookModel::updateBook($id, $data);
        if ($updatedBook) {
            http_response_code(200);
            echo json_encode([
                "message" => "Libro actualizado correctamente",
                "data" => $updatedBook
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404); 
            echo json_encode([
                "message" => "Libro no encontrado"
            ]);
        }
    }
    
    // DELETE
    function deleteBooks($id){
        $deleted = BookModel::deleteBook($id);
        if ($deleted) {
            http_response_code(200); 
            echo json_encode([
                "message" => "Libro borrado correctamente"
            ]);
        } else {
            http_response_code(404); 
            echo json_encode([
                "message" => "Libro no encontrado"
            ]);
        }
    }
}
?>
