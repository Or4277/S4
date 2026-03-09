<?php

namespace app\models;

class Medicament {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    public function getAllMedicaments() {
        $stmt = $this->db->query("SELECT * FROM medicament ORDER BY id");
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

    
    public function getMedicamentById($id) {
        $stmt = $this->db->prepare("SELECT * FROM medicament WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

  
    public function createMedicament($nom, $prix, $effet) {
        $stmt = $this->db->prepare("INSERT INTO medicament (nom, prix, effet) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prix, $effet]);
        return $this->db->lastInsertId();
    }


    public function updateMedicament($id, $nom, $prix, $effet) {
        $stmt = $this->db->prepare("UPDATE medicament SET nom = ?, prix = ?, effet = ? WHERE id = ?");
        $stmt->execute([$nom, $prix, $effet, $id]);
    }


    public function deleteMedicament($id) {
        $stmt = $this->db->prepare("DELETE FROM medicament WHERE id = ?");
        $stmt->execute([$id]);
        
        // Réinitialiser l'auto-increment si la table est vide
        $this->resetAutoIncrementSiVide();
    }

    /**
     * Réinitialiser l'auto-increment si la table est vide
     */
    private function resetAutoIncrementSiVide() {
        $count = $this->db->query("SELECT COUNT(*) FROM medicament")->fetchColumn();
        if ($count == 0) {
            $this->db->exec("ALTER TABLE medicament AUTO_INCREMENT = 1");
        }
    }

    public function compterMedicaments($medicaments, $index = 0) {
        if ($index >= count($medicaments)) {
            return 0;
        }
        return 1 + $this->compterMedicaments($medicaments, $index + 1);
    }

    // ==========================================
    // ORDONNANCE - VERSION RECURSIVE
    // ==========================================

    /**
     * Obtenir les médicaments pour une liste de symptômes - RECURSIF (meilleur effet)
     */
    public function getMedicamentsParSymptomes($symptomeIds, $index = 0, $medicaments = [], $medicamentIds = []) {
        if ($index >= count($symptomeIds)) {
            return $medicaments;
        }
        
        $symptomeId = $symptomeIds[$index];
        
        $stmt = $this->db->prepare("
            SELECT m.*, sm.symptome_id
            FROM medicament m
            JOIN affectation sm ON m.id = sm.medicament_id
            WHERE sm.symptome_id = ?
            ORDER BY m.effet DESC
            LIMIT 1
        ");
        $stmt->execute([$symptomeId]);
        $med = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($med && !in_array($med['id'], $medicamentIds)) {
            $medicaments[] = $med;
            $medicamentIds[] = $med['id'];
        }
        
        return $this->getMedicamentsParSymptomes($symptomeIds, $index + 1, $medicaments, $medicamentIds);
    }

    /* maka medicament par symptome par id */
    public function getMedicamentsParSymptomeId($symptomeId) {
        $stmt = $this->db->prepare("
            SELECT m.*, sm.symptome_id
            FROM medicament m
            JOIN affectation sm ON m.id = sm.medicament_id
            WHERE sm.symptome_id = ?
            ORDER BY m.prix ASC
        ");
        $stmt->execute([$symptomeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Générer toutes les combinaisons de médicaments possibles - RECURSIF
     */
    public function genererToutesCombinaisons($symptomeIds) {
        // Récupérer les médicaments pour chaque symptôme
        $medsParSymptome = $this->collecterMedsParSymptomeRecursif($symptomeIds, 0, []);
        
        // Générer toutes les combinaisons
        $combinaisons = $this->genererCombinaisonsRecursif($medsParSymptome, 0, [], []);
        
        // Calculer le prix et l'effet de chaque combinaison
        return $this->calculerInfosCombinaisonsRecursif($combinaisons, 0, []);
    }

    private function collecterMedsParSymptomeRecursif($symptomeIds, $index, $result) {
        if ($index >= count($symptomeIds)) {
            return $result;
        }
        $meds = $this->getMedicamentsParSymptomeId($symptomeIds[$index]);
        if (!empty($meds)) {
            $result[] = $meds;
        }
        return $this->collecterMedsParSymptomeRecursif($symptomeIds, $index + 1, $result);
    }

    private function genererCombinaisonsRecursif($medsParSymptome, $index, $combinaisonActuelle, $toutesCombinaisons) {
        if ($index >= count($medsParSymptome)) {
            if (!empty($combinaisonActuelle)) {
                $toutesCombinaisons[] = $combinaisonActuelle;
            }
            return $toutesCombinaisons;
        }
        
        return $this->parcourirMedsRecursif($medsParSymptome, $index, 0, $combinaisonActuelle, $toutesCombinaisons);
    }

    private function parcourirMedsRecursif($medsParSymptome, $symptomeIndex, $medIndex, $combinaisonActuelle, $toutesCombinaisons) {
        if ($medIndex >= count($medsParSymptome[$symptomeIndex])) {
            return $toutesCombinaisons;
        }
        
        $med = $medsParSymptome[$symptomeIndex][$medIndex];
        $nouvelleCombinaisonClean = $this->ajouterSansDoublonRecursif($combinaisonActuelle, $med, 0);
        
        $toutesCombinaisons = $this->genererCombinaisonsRecursif($medsParSymptome, $symptomeIndex + 1, $nouvelleCombinaisonClean, $toutesCombinaisons);
        
        return $this->parcourirMedsRecursif($medsParSymptome, $symptomeIndex, $medIndex + 1, $combinaisonActuelle, $toutesCombinaisons);
    }

    private function ajouterSansDoublonRecursif($combinaison, $med, $index) {
        if ($index >= count($combinaison)) {
            $combinaison[] = $med;
            return $combinaison;
        }
        if ($combinaison[$index]['id'] == $med['id']) {
            return $combinaison;
        }
        return $this->ajouterSansDoublonRecursif($combinaison, $med, $index + 1);
    }

    private function calculerInfosCombinaisonsRecursif($combinaisons, $index, $result) {
        if ($index >= count($combinaisons)) {
            return $this->trierCombinaisonsParPrix($result);
        }
        
        $comb = $combinaisons[$index];
        $prix = $this->calculerPrixTotal($comb);
        $effet = $this->calculerEffetTotal($comb);
        
        $result[] = [
            'medicaments' => $comb,
            'prix' => $prix,
            'effet' => $effet
        ];
        
        return $this->calculerInfosCombinaisonsRecursif($combinaisons, $index + 1, $result);
    }

    private function trierCombinaisonsParPrix($combinaisons) {
        if (count($combinaisons) <= 1) {
            return $combinaisons;
        }
        
        $pivot = $combinaisons[0];
        $left = [];
        $right = [];
        
        $this->partitionnerCombinaisonsRecursif($combinaisons, $pivot, 1, $left, $right);
        
        return array_merge(
            $this->trierCombinaisonsParPrix($left),
            [$pivot],
            $this->trierCombinaisonsParPrix($right)
        );
    }

    private function partitionnerCombinaisonsRecursif($combinaisons, $pivot, $index, &$left, &$right) {
        if ($index >= count($combinaisons)) {
            return;
        }
        
        if ($combinaisons[$index]['prix'] < $pivot['prix']) {
            $left[] = $combinaisons[$index];
        } else {
            $right[] = $combinaisons[$index];
        }
        
        $this->partitionnerCombinaisonsRecursif($combinaisons, $pivot, $index + 1, $left, $right);
    }

    /**
     * Trouver la combinaison la moins chère qui soigne - RECURSIF
     */
    public function trouverMeilleureCombinaisonPourBudget($combinaisons, $budget, $graviteTotal, $index = 0) {
        if ($index >= count($combinaisons)) {
            return null;
        }
        
        $comb = $combinaisons[$index];
        if ($comb['prix'] <= $budget) {
            return $comb;
        }
        
        return $this->trouverMeilleureCombinaisonPourBudget($combinaisons, $budget, $graviteTotal, $index + 1);
    }

    /**
     * Trouver la combinaison la moins chère - RECURSIF
     */
    public function trouverCombinaisonMoinsChere($combinaisons) {
        if (empty($combinaisons)) {
            return null;
        }
        return $combinaisons[0]; // Déjà triées par prix croissant
    }

    // ==========================================
    // CALCUL DU PRIX TOTAL - VERSION RECURSIVE
    // ==========================================

    /**
     * Calculer le prix total d'une ordonnance - RECURSIF
     */
    public function calculerPrixTotal($medicaments, $index = 0) {
        if ($index >= count($medicaments)) {
            return 0;
        }
        return $medicaments[$index]['prix'] + $this->calculerPrixTotal($medicaments, $index + 1);
    }

    // ==========================================
    // OPTIMISATION BUDGET - VERSION RECURSIVE
    // ==========================================

    /**
     * Trouver une combinaison de médicaments dans le budget - RECURSIF
     */
    public function optimiserBudget($symptomeIds, $budget) {
        $tousLesMedicaments = $this->getTousMedicamentsTries($symptomeIds);
        return $this->selectionnerMedicamentsRecursif($tousLesMedicaments, $budget, 0, [], [], 0, []);
    }

    private function getTousMedicamentsTries($symptomeIds) {
        $tousLesMedicaments = $this->collecterMedicamentsRecursif($symptomeIds, 0, []);
        return $this->trierParPrix($tousLesMedicaments);
    }

    private function collecterMedicamentsRecursif($symptomeIds, $index, $medicaments) {
        if ($index >= count($symptomeIds)) {
            return $medicaments;
        }
        
        $symptomeId = $symptomeIds[$index];
        $stmt = $this->db->prepare("
            SELECT m.*, sm.symptome_id
            FROM medicament m
            JOIN affectation sm ON m.id = sm.medicament_id
            WHERE sm.symptome_id = ?
            ORDER BY m.prix ASC
        ");
        $stmt->execute([$symptomeId]);
        $meds = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $medicaments = $this->ajouterMedicamentsRecursif($meds, 0, $medicaments);
        
        return $this->collecterMedicamentsRecursif($symptomeIds, $index + 1, $medicaments);
    }

    private function ajouterMedicamentsRecursif($meds, $index, $result) {
        if ($index >= count($meds)) {
            return $result;
        }
        $result[] = $meds[$index];
        return $this->ajouterMedicamentsRecursif($meds, $index + 1, $result);
    }

    private function selectionnerMedicamentsRecursif($medicaments, $budget, $index, $selection, $symptomesCouverts, $totalPrix, $medicamentIds) {
        if ($index >= count($medicaments)) {
            return $selection;
        }
        
        $med = $medicaments[$index];
        $symptomeId = $med['symptome_id'];
        
        if (!in_array($symptomeId, $symptomesCouverts) && !in_array($med['id'], $medicamentIds)) {
            if ($totalPrix + $med['prix'] <= $budget) {
                $selection[] = $med;
                $symptomesCouverts[] = $symptomeId;
                $medicamentIds[] = $med['id'];
                $totalPrix += $med['prix'];
            }
        }
        
        return $this->selectionnerMedicamentsRecursif($medicaments, $budget, $index + 1, $selection, $symptomesCouverts, $totalPrix, $medicamentIds);
    }

    // ==========================================
    // TRI PAR PRIX - VERSION RECURSIVE (Quicksort)
    // ==========================================

    /**
     * Trier les médicaments par prix - RECURSIF (Quicksort)
     */
    public function trierParPrix($medicaments) {
        if (count($medicaments) <= 1) {
            return $medicaments;
        }
        
        $pivot = $medicaments[0];
        $left = [];
        $right = [];
        
        $this->partitionnerRecursif($medicaments, $pivot, 1, $left, $right);
        
        return array_merge(
            $this->trierParPrix($left),
            [$pivot],
            $this->trierParPrix($right)
        );
    }

    private function partitionnerRecursif($medicaments, $pivot, $index, &$left, &$right) {
        if ($index >= count($medicaments)) {
            return;
        }
        
        if ($medicaments[$index]['prix'] < $pivot['prix']) {
            $left[] = $medicaments[$index];
        } else {
            $right[] = $medicaments[$index];
        }
        
        $this->partitionnerRecursif($medicaments, $pivot, $index + 1, $left, $right);
    }

    // ==========================================
    // CALCUL DE L'EFFET TOTAL - VERSION RECURSIVE
    // ==========================================

    /**
     * Calculer la somme des effets des médicaments - RECURSIF
     * L'effet représente la réduction de gravité apportée par les médicaments
     */
    public function calculerEffetTotal($medicaments, $index = 0) {
        if ($index >= count($medicaments)) {
            return 0;
        }
        $effet = isset($medicaments[$index]['effet']) ? $medicaments[$index]['effet'] : 0;
        return $effet + $this->calculerEffetTotal($medicaments, $index + 1);
    }

    /**
     * Calculer si le patient sera guéri - RECURSIF
     * Guéri si gravité totale - effet total des médicaments <= 0
     */
    public function calculerGuerison($graviteTotal, $medicaments) {
        $effetTotal = $this->calculerEffetTotal($medicaments);
        $graviteRestante = $graviteTotal - $effetTotal;
        return [
            'effet_total' => $effetTotal,
            'gravite_restante' => $graviteRestante,
            'gueri' => $graviteRestante <= 0
        ];
    }
}
