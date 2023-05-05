<?php

Class User {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function getAllUser() {
        $sth = $this->conn->prepare('SELECT * FROM Users');
        $sth->execute();
        $result = $sth->fetchAll();
        echo json_encode($result);
    }

    public function getUserById($id) {
        $sth = $this->conn->prepare('SELECT * FROM Users WHERE id = :id');
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch();
        echo json_encode($result);
    }
}

?>