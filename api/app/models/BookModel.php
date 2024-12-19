<?php
require_once 'Book.php';

class BookModel {
    //Ruta donde se encuentan los ibros
    private static $file = '../core/data/libros.json';

    // Obtenemos todos los libros
    public static function getAllBooks(){
        $booksData = self::getData(); 
        $books = [];
        foreach ($booksData as $bookData) {
            $books[] = Book::fromArray($bookData);
        }
        return $books;
    }
    
    //Funcion para obtener un libro por Id
    public static function getBookById($id){
        $books = self::getAllBooks();
        foreach ($books as $book) {
            if ($book->getId() === $id) {
                return $book;
            }
        }
        return null;
    }
    
    // Funcion para crear el libro 
    public static function createBook($data){
        $books = self::getAllBooks();
        $newId = self::getNextId($books);
    
        // Validar datos requeridos
        if (!isset($data['title'], $data['author'], $data['category'], $data['price'], $data['stock'])) {
            throw new Exception("Campos Requeridos");
        }
    
        $newBook = new Book(
            $newId,
            $data['title'],
            $data['author'],
            $data['category'],
            $data['price'],
            $data['stock']
        );
    
        $books[] = $newBook;
        self::saveData($books);
        return $newBook;
    }

    // Funcion para actualizar el libro
    public static function updateBook($id, $data){
        $books = self::getAllBooks();
        foreach ($books as $book) {
            if ($book->getId() === $id) {
                // Actualizar las propiedades que en este caso podra actualizar el precio o el stock
                if (isset($data['price'])) {
                    $book->setPrice($data['price']); 
                }
                if (isset($data['stock'])) {
                    $book->setStock($data['stock']); 
                }
                self::saveData($books);

                return [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'author' => $book->getAuthor(),
                    'category' => $book->getCategory(),
                    'price' => $book->getPrice(),
                    'stock' => $book->getStock()
                ]; 
            }
        }
        return null; 
    }

    // Funcion para borrar el libro
    public static function deleteBook($id): bool {
        $books = self::getAllBooks();
        foreach ($books as $index => $book) {
            if ($book->getId() === $id) {
                unset($books[$index]);
                self::saveData(array_values($books));
                return true;
            }
        }
        return false;
    }

    // Obtener los datos del archivo JSON
    private static function getData() {
        if (file_exists(self::$file)) {
            $json = file_get_contents(self::$file);
            $data = json_decode($json, true);

            // Validar que los datos sean un array y no contengan valores inesperados
            if (is_array($data)) {
                return array_filter($data, function ($item) {
                return isset($item['id'], $item['title'], $item['author'], $item['category'], $item['price'], $item['stock']);
                });
            }
        }
        return []; 
    }
    
    //Funcion para guardar la informacion actualizada
    private static function saveData($data) {
        $formatData = array_map(function ($book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'category' => $book->getCategory(),
                'price' => $book->getPrice(),
                'stock' => $book->getStock(),
            ];
        }, $data);

        file_put_contents(self::$file, json_encode($formatData, JSON_PRETTY_PRINT));
    }

    // Funcion para generar un identificador para cuando se crea un nuevo libro
    private static function getNextId($books){
        if (empty($books)) {
            return 1;
        }
        return end($books)->getId() + 1;
    }
}
