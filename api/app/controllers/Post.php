<?php
class Post{
    function __construct(){
        
    }

    // GET
    function getAllBooks( ){
        echo "Hola dede getAllBooks";
    }

    function getBooksById($id ){
        echo "Hola dede getAllBooks Id";
    }

    // POST
    function createBooks($data ){
        echo "Hola createBooks";
    }

    // PUT
    function updateBooks($id, $data){
        echo "Hola dede updateBooks";
    }
    
    // DELETE
    function deleteBooks($id){
        echo "Hola dede DeleteBooks";
    }
        
          
}
?>