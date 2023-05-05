<?php

Class Library {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function getLibrary($id, $name) {
        $sth = $this->conn->prepare('SELECT Reading_Status.status, book_edition.published_at, Books.published_at, Books.title, Books.resume, Books.author, Editions.name, Editions.format FROM `Biblio`
            INNER JOIN book_edition ON Biblio.edition_id = book_edition.id 
            INNER JOIN `Books` ON book_edition.book_id = Books.id 
            INNER JOIN `Editions` ON book_edition.edition_id = Editions.id
            INNER JOIN `Reading_Status` ON reading_status.edition_id = book_edition.id
            WHERE users_id = :id LIMIT 0,100');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll();
        $obj = new stdClass();
        $obj->name = $name;
        $obj->all = $result;
        echo json_encode($obj);    
    }

    public function addToLibrary($edition, $user) {
        $sth = $this->conn->prepare('INSERT INTO Biblio (users_id, edition_id) VALUES (:user, :edition)');
        $sth->bindParam('edition', $edition, PDO::PARAM_INT);
        $sth->bindParam('user', $user, PDO::PARAM_INT);
        $sth->execute();
        echo 'Une édition de livre a bien été ajouté a votre bibliothéque';
    }

    public function deleteBook($user, $id){
        $sth = $this->conn->prepare('DELETE FROM `Biblio`
        WHERE users_id = :user AND edition_id = :edition');
        $sth->bindParam('user', $user, PDO::PARAM_INT);
        $sth->bindParam('edition', $id, PDO::PARAM_INT);
        $sth->execute();
        echo 'Le livre a bien été supprimer de votre bibliothéque';
    }
}

?>