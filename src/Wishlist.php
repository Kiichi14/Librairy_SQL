<?php

class Wishlist {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function getWishlist($id, $name) {
        $sth = $this->conn->prepare('SELECT Category.name_category, book_edition.published_at, Books.published_at, Books.resume, Books.author, Books.published_at, Books.title, Editions.name, Editions.format, Editeur.name_editor FROM `Wishlist` 
        INNER JOIN book_edition ON Wishlist.edition_id = book_edition.id 
        INNER JOIN Books ON book_edition.book_id = Books.id 
        INNER JOIN Editions ON book_edition.edition_id = Editions.id 
        INNER JOIN Editeur ON Books.editeur_id = Editeur.id 
        INNER JOIN Category ON Books.category_id = Category.id 
        WHERE users_id = :id LIMIT 100');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll();
        $obj = new stdClass();
        $obj->name = $name;
        $obj->all = $result;
        echo json_encode($obj);
    }

    public function addToWishlist($id, $edition) {
        $sth = $this->conn->prepare('INSERT INTO Wishlist (users_id, edition_id) VALUES (:id, :edition)');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->bindParam('edition', $edition, PDO::PARAM_INT);
        $sth->execute();
        echo 'Le livre a bien été ajoutée a votre wishlist';
    }
}

?>