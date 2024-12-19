<?php
class Book implements JsonSerializable {
    private $id;
    private $title;
    private $author;
    private $category;
    private $price;
    private $stock;

    // Constructor
    public function __construct($id, $title, $author, $category, $price, $stock) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->category = $category;
        $this->price = $price;
        $this->stock = $stock;
    }

    // Convertir el array a una instancia de Book
    public static function fromArray($data) {
        return new self(
            $data['id'],
            $data['title'],
            $data['author'],
            $data['category'],
            $data['price'],
            $data['stock']
        );
    }

    // Métodos getter para acceder a las propiedades
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getAuthor() { return $this->author; }
    public function getCategory() { return $this->category; }
    public function getPrice() { return $this->price; }
    public function getStock() { return $this->stock; }

    // Métodos setter para modificar las propiedades de los libros
    public function setTitle($title) { $this->title = $title; }
    public function setAuthor($author) { $this->author = $author; }
    public function setCategory($category) { $this->category = $category; }
    public function setPrice($price) { $this->price = $price; }
    public function setStock($stock) { $this->stock = $stock; }

    // Método para convertir el objeto en un array asociativo.
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'category' => $this->category,
            'price' => $this->price,
            'stock' => $this->stock,
        ];
    }
}

?>