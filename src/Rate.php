<?php

class Rate {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function getComments($id, $book) {
        $sth = $this->conn->prepare('SELECT Comments.comment, Comments.rate, Books.title, Books.author, Users.name FROM Comments
        INNER JOIN Users ON Users.id = Comments.user_id
        INNER JOIN Books ON Books.id = Comments.book_id
        WHERE user_id = :id AND book_id = :book_id');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->bindParam('book_id', $book, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch();
        echo json_encode($result);
    }

    public function postRate($comment, $rate, $book, $user) {
        $sth = $this->conn->prepare('INSERT INTO Comments (comment, rate, book_id, user_id) VALUES (:comment, :rate, :book, :user)');
        $sth->bindParam('comment', $comment, PDO::PARAM_STR);
        $sth->bindParam('rate', $rate, PDO::PARAM_STR);
        $sth->bindParam('book', $book, PDO::PARAM_INT);
        $sth->bindParam('user', $user, PDO::PARAM_INT);
        $sth->execute();
        echo 'Votre Commentaire a bien été postée';
    }

    public function updateRate($rate, $id, $user) {
        $sth = $this->conn->prepare('UPDATE Comments SET rate=:rate WHERE Comments.id = :id AND Comments.user_id = :user_id');
        $sth->bindParam('rate', $rate, PDO::PARAM_STR);
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->bindParam('user_id', $user, PDO::PARAM_INT);
        $sth->execute();
        echo 'La note a bien été mis a jour';
    }
}

?>