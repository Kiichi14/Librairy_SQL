<?php

class Book {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function insertBook($title, $categoryId, $desc, $author, $editeurId) {
        $sth = $this->conn->prepare('INSERT INTO Books (title, category_id, resume, author, editeur_id, published_at) VALUES (?,?,?,?,?, NOW())');
        $sth->execute([$title, $categoryId, $desc, $author, $editeurId]);
        echo 'Votre livre a bien été ajoutée';
    }

    public function getAllBook() {
        $sth = $this->conn->prepare('SELECT * FROM Books INNER JOIN Category ON Books.category_id = Category.id INNER JOIN Editeur ON Books.editeur_id = Editeur.id ORDER BY published_at DESC');
        $sth->execute();
        $result = $sth->fetchAll();
        echo json_encode($result);
    }

    public function getBookById($id) {
        $sth = $this->conn->prepare('SELECT
        Books.title,
        Books.resume,
        Books.author,
        Books.published_at,
        Category.name_category,
        Editeur.name_editor,
        CONCAT(Editions.name, " ", Editions.format) AS edition_disponible,
        ROUND(AVG(Comments.rate), 2) AS moyenne
        FROM Books
        INNER JOIN Category ON Books.category_id = Category.id
        INNER JOIN Editeur ON Books.editeur_id = Editeur.id
        INNER JOIN Comments ON Books.id = Comments.book_id
        INNER JOIN book_edition ON Books.id = book_edition.book_id
        INNER JOIN `Editions` ON book_edition.edition_id = Editions.id 
        WHERE Books.id = :id
        GROUP BY Books.id, book_edition.book_id, Editions.id
        ORDER BY Books.published_at DESC');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($sth->rowCount() > 0){
            $arrayEdition = array();
            $obj = new stdClass();
            $obj->title = $result[0]['title'];
            $obj->resume = $result[0]['resume'];
            $obj->author = $result[0]['author'];
            $obj->category = $result[0]['name_category'];
            $obj->editeur = $result[0]['name_editor'];
            $obj->moyenne = $result[0]['moyenne'];
            foreach($result as $row){
                array_push($arrayEdition, $row['edition_disponible']);
            }
            $obj->edition = $arrayEdition;
            echo json_encode($obj);
        } else {
            echo 'Aucun Livre ne correspond a votre recherche';
        }

    }

    public function getBookByAuthor($author){
        $sth = $this->conn->prepare('SELECT
        Books.title,
        Books.resume,
        Books.author,
        Books.published_at,
        Category.name_category,
        Editeur.name_editor, 
        ROUND(AVG(Comments.rate), 2) AS moyenne
        FROM Books
        INNER JOIN Category ON Books.category_id = Category.id
        INNER JOIN Editeur ON Books.editeur_id = Editeur.id
        INNER JOIN Comments ON Books.id = Comments.book_id
        WHERE Books.author = :author
        GROUP BY Books.id
        ORDER BY Books.published_at DESC');
        $sth->bindParam('author', $author, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($sth->rowCount() > 0){
            echo json_encode($result);
        } else {
            echo 'Aucun Livre ne correspond a votre recherche';
        }
    }

    public function getBookByCategory($categoryId){
        $sth = $this->conn->prepare('SELECT
        Books.title,
        Books.resume,
        Books.author,
        Books.published_at,
        Category.name_category,
        Editeur.name_editor,
        ROUND(AVG(Comments.rate), 2) AS moyenne
        FROM Books
        INNER JOIN Category ON Books.category_id = Category.id
        INNER JOIN Editeur ON Books.editeur_id = Editeur.id
        INNER JOIN Comments ON Books.id = Comments.book_id 
        WHERE category_id = :category_id
        GROUP BY Books.id
        ORDER BY Books.published_at DESC');
        $sth->bindParam('category_id', $categoryId, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll();
        if($sth->rowCount() > 0){
            echo json_encode($result);
        } else {
            echo 'Aucun Livre dans cette catégorie';
        }
    }

    public function getBookByTitle($title){
        $sth = $this->conn->prepare('SELECT
        Books.title,
        Books.resume,
        Books.author,
        Books.published_at,
        Category.name_category,
        Editeur.name_editor,
        CONCAT(Editions.name, " ", Editions.format) AS edition_disponible,
        ROUND(AVG(Comments.rate), 2) AS moyenne
        FROM Books
        INNER JOIN Category ON Books.category_id = Category.id
        INNER JOIN Editeur ON Books.editeur_id = Editeur.id 
        INNER JOIN Comments ON Books.id = Comments.book_id
        INNER JOIN book_edition ON Books.id = book_edition.book_id
        INNER JOIN `Editions` ON book_edition.edition_id = Editions.id 
        WHERE title = :title
        GROUP BY Books.id, book_edition.book_id, Editions.id
        ORDER BY Books.published_at DESC');
        $sth->bindParam('title', $title, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($sth->rowCount() > 0){
            $arrayEdition = array();
            $obj = new stdClass();
            $obj->title = $result[0]['title'];
            $obj->resume = $result[0]['resume'];
            $obj->author = $result[0]['author'];
            $obj->category = $result[0]['name_category'];
            $obj->editeur = $result[0]['name_editor'];
            $obj->moyenne = $result[0]['moyenne'];
            foreach($result as $row){
                array_push($arrayEdition, $row['edition_disponible']);
            }
            $obj->edition = $arrayEdition;
            echo json_encode($obj);
        } else {
            echo 'Aucun Livre ne correspond a votre recherche';
        }
    }

    public function getBookByEditor($editor){
        $sth = $this->conn->prepare('SELECT 
        Books.title,
        Books.resume,
        Books.author,
        Books.published_at,
        Category.name_category,
        Editeur.name_editor,
        ROUND(AVG(Comments.rate), 2) AS moyenne
        FROM Books
        INNER JOIN Category ON Books.category_id = Category.id
        INNER JOIN Editeur ON Books.editeur_id = Editeur.id
        INNER JOIN Comments ON Books.id = Comments.book_id 
        WHERE editeur_id = :editeur_id
        GROUP BY Books.id
        ORDER BY Books.published_at DESC');
        $sth->bindParam('editeur_id', $editor, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll();
        if($sth->rowCount() > 0){
            echo json_encode($result);
        } else {
            echo 'Aucun Livre de cette Éditeur';
        }
    }

    public function updateCategory($genre, $id) {
        $sth = $this->conn->prepare('UPDATE Books SET category_id=:genre WHERE Books.id = :id');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->bindParam('genre', $genre, PDO::PARAM_INT);
        $sth->execute();
        echo 'Livre Mis a jour avec succés';
    }

    public function deleteCategory($id) {
        $sth = $this->conn->prepare('UPDATE Books SET category_id = null WHERE Books.id = :id');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->execute();
        echo 'La catégorie du livre a bien été retirée';
    }
}

?>