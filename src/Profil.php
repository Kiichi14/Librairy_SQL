<?php

class Profil {

    private $conn;

    public function __construct() {
        $this->conn = (new Connection)->openConnection();
    }

    public function getStats($id) {
        $sth = $this->conn->prepare("SELECT 'users_id' AS Biblio, COUNT(*) FROM Biblio AS total WHERE users_id = :id 
        UNION
        SELECT status, Count(status) FROM Reading_Status AS reading WHERE user_id = :id GROUP BY status LIMIT 0,100");
        $sth->bindParam('id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $arrayResult = array();
        foreach($result as $row) {
            $arrayResult[$row['Biblio']] = (int) $row['COUNT(*)']; 
        }
        // Renommez la clé 'users_id' en 'librairie'
        $arrayResult['librairie'] = $arrayResult['users_id'];
        unset($arrayResult['users_id']);

        // Vérifiez si chaque clé est présente dans le tableau final et la définir sur zéro si elle est absente
        $clés = ['librairie', 'in progress', 'finish'];
        foreach ($clés as $clé) {
            if (!array_key_exists($clé, $arrayResult)) {
                $arrayResult[$clé] = 0;
            }
        }
        echo json_encode($arrayResult);
    }

}

?>