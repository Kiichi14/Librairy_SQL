<?php

class Author {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function getBookStats($name) {
        $sth = $this->conn->prepare('SELECT COUNT(Books.id) FROM `Biblio`
        INNER JOIN book_edition ON Biblio.edition_id = book_edition.id
        INNER JOIN Books ON Books.id = book_edition.book_id
        WHERE Books.author = :name LIMIT 0,100');
        $sth->bindParam('name', $name, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $obj = new stdClass();
        $obj->name_author = $name;
        foreach ($result as $row) {
            $obj->result_library = (int) $row['COUNT(Books.id)'];
        }
        echo json_encode($obj);
    }

}

?>