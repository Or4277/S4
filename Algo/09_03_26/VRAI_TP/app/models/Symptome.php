<?php

namespace app\models;

class Symptome {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }



    public function getAllSymptomes() {
        $stmt = $this->db->query("SELECT * FROM symptome ORDER BY id");
        $allRows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->buildArrayRecursif($allRows, 0);
    }

    private function buildArrayRecursif($rows, $index) {
        if ($index >= count($rows)) {
            return [];
        }
        $result = [$rows[$index]];
        return array_merge($result, $this->buildArrayRecursif($rows, $index + 1));
    }


    public function getSymptomeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM symptome WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function createSymptome($nom) {
        $stmt = $this->db->prepare("INSERT INTO symptome (nom) VALUES (?)");
        $stmt->execute([$nom]);
        return $this->db->lastInsertId();
    }


    public function updateSymptome($id, $nom) {
        $stmt = $this->db->prepare("UPDATE symptome SET nom = ? WHERE id = ?");
        return $stmt->execute([$nom, $id]);
    }


    public function deleteSymptome($id) {
        $stmt = $this->db->prepare("DELETE FROM symptome WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        // Réinitialiser l'auto-increment si la table est vide
        $this->resetAutoIncrementSiVide();
        
        return $result;
    }

    /**
     * Réinitialiser l'auto-increment si la table est vide - RECURSIF (vérification)
     */
    private function resetAutoIncrementSiVide() {
        $count = $this->db->query("SELECT COUNT(*) FROM symptome")->fetchColumn();
        if ($count == 0) {
            $this->db->exec("ALTER TABLE symptome AUTO_INCREMENT = 1");
        }
    }


    /**
     * Rechercher symptôme par nom - RECURSIF
     */
    public function rechercherSymptome($symptomes, $nomRecherche, $index = 0) {
        if ($index >= count($symptomes)) {
            return null;
        }
        if (strtolower($symptomes[$index]['nom']) === strtolower($nomRecherche)) {
            return $symptomes[$index];
        }
        return $this->rechercherSymptome($symptomes, $nomRecherche, $index + 1);
    }

    /**
     * Compter les symptômes - RECURSIF
     */
    public function compterSymptomes($symptomes, $index = 0) {
        if ($index >= count($symptomes)) {
            return 0;
        }
        return 1 + $this->compterSymptomes($symptomes, $index + 1);
    }

    /**
     * Vérifier l'existence d'un ID - RECURSIF
     */
    public function verifierExistence($symptomes, $id, $index = 0) {
        if ($index >= count($symptomes)) {
            return false;
        }
        if ($symptomes[$index]['id'] == $id) {
            return true;
        }
        return $this->verifierExistence($symptomes, $id, $index + 1);
    }
}
