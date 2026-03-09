<?php

namespace app\controllers;

use app\models\Symptome;
use app\models\Medicament;
use Flight;

class SymptomeController {



    public static function accueil() {
        $symptomeModel = new Symptome(Flight::db());
        $symptomes = $symptomeModel->getAllSymptomes();
        $count = $symptomeModel->compterSymptomes($symptomes);
        
        $medicamentModel = new Medicament(Flight::db());
        $medicaments = $medicamentModel->getAllMedicaments();
        $countMedicaments = $medicamentModel->compterMedicaments($medicaments);
        
        Flight::render('accueil', [
            'symptomes' => $symptomes,
            'count' => $count,
            'medicaments' => $medicaments,
            'countMedicaments' => $countMedicaments
        ]);
    }

	/* creation  symptome*/
    public static function createSymptome() {
        $nom = Flight::request()->data->nom ?? '';
        $symptomeModel = new Symptome(Flight::db());
        $symptomeModel->createSymptome($nom);
    }


    public static function editSymptome($id) {
        $symptomeModel = new Symptome(Flight::db());
        $symptome = $symptomeModel->getSymptomeById($id);
        Flight::render('edit_symptome', ['symptome' => $symptome]);
    }

	/* modifier symptome*/
    public static function updateSymptome($id) {
        $nom = Flight::request()->data->nom ?? '';
        $symptomeModel = new Symptome(Flight::db());
        $symptomeModel->updateSymptome($id, $nom);
    }

	/*suprression symptome*/
    public static function deleteSymptome($id) {
        $symptomeModel = new Symptome(Flight::db());
        $symptomes = $symptomeModel->getAllSymptomes();
        $existe = $symptomeModel->verifierExistence($symptomes, $id);
        
        if ($existe) {
            $symptomeModel->deleteSymptome($id);
            Flight::redirect('/?success=Symptôme supprimé');
        } else {
            Flight::redirect('/?error=Symptôme non trouvé');
        }
    }


	/* creation medoc*/
    public static function createMedicament() {
        $nom = Flight::request()->data->nom ?? '';
        $prix = floatval(Flight::request()->data->prix ?? 0);
        $effet = intval(Flight::request()->data->effet ?? 1);
        $medicamentModel = new Medicament(Flight::db());
        $medicamentModel->createMedicament($nom, $prix, $effet);
    }

	/*formulaire*/
    public static function editMedicament($id) {
        $medicamentModel = new Medicament(Flight::db());
        $medicament = $medicamentModel->getMedicamentById($id);
        Flight::render('edit_medicament', ['medicament' => $medicament]);
    }

    /*modif medoc*/
    public static function updateMedicament($id) {
        $nom = Flight::request()->data->nom ?? '';
        $prix = floatval(Flight::request()->data->prix ?? 0);
        $effet = intval(Flight::request()->data->effet ?? 1);
        $medicamentModel = new Medicament(Flight::db());
        $medicamentModel->updateMedicament($id, $nom, $prix, $effet);

    }

	/*suppression medoc*/
    public static function deleteMedicament($id) {
        $medicamentModel = new Medicament(Flight::db());
        $medicamentModel->deleteMedicament($id);
        Flight::redirect('/?success=Médicament supprimé');
    }

	/*Malade*/
    public static function genererOrdonnance() {
        $symptomeIds = Flight::request()->data->symptomes ?? [];
        $gravites = Flight::request()->data->gravites ?? []; // gravité par symptôme
        $budget = floatval(Flight::request()->data->budget ?? 0);
        
        if (empty($symptomeIds)) {
            Flight::redirect('/?error=Veuillez sélectionner au moins un symptôme');
            return;
        }
        
        $medicamentModel = new Medicament(Flight::db());
        $symptomeModel = new Symptome(Flight::db());
        
        // Récupérer les noms des symptômes sélectionnés avec leurs gravités (récursif)
        $symptomesSelectionnes = self::getSymptomesAvecGraviteRecursif($symptomeModel, $symptomeIds, $gravites, 0, []);
        
        // Calculer la gravité totale (récursif)
        $graviteTotal = self::calculerGraviteTotaleRecursif($symptomesSelectionnes, 0);
        
        // Générer toutes les combinaisons de médicaments (récursif)
        $toutesCombinaisons = $medicamentModel->genererToutesCombinaisons($symptomeIds);
        
        // Trouver la combinaison la moins chère
        $combinaisonMoinsChere = $medicamentModel->trouverCombinaisonMoinsChere($toutesCombinaisons);
        
        // Trouver une combinaison dans le budget
        $combinaisonDansBudget = $medicamentModel->trouverMeilleureCombinaisonPourBudget($toutesCombinaisons, $budget, $graviteTotal);
        
        // Vérifier si le budget est suffisant pour au moins une combinaison
        if ($combinaisonDansBudget) {
            // Budget suffisant - montrer la combinaison choisie
            $resultatGuerison = $medicamentModel->calculerGuerison($graviteTotal, $combinaisonDansBudget['medicaments']);
            
            Flight::render('ordonnance', [
                'medicaments' => $combinaisonDansBudget['medicaments'],
                'prixTotal' => $combinaisonDansBudget['prix'],
                'budget' => $budget,
                'symptomesSelectionnes' => $symptomesSelectionnes,
                'graviteTotal' => $graviteTotal,
                'resultatGuerison' => $resultatGuerison,
                'toutesCombinaisons' => $toutesCombinaisons,
                'budgetSuffisant' => true
            ]);
        } else {
            // Budget insuffisant même pour la moins chère
            $resultatGuerison = $combinaisonMoinsChere ? $medicamentModel->calculerGuerison($graviteTotal, $combinaisonMoinsChere['medicaments']) : null;
            
            Flight::render('ordonnance_budget', [
                'combinaisonMoinsChere' => $combinaisonMoinsChere,
                'prixMinimum' => $combinaisonMoinsChere ? $combinaisonMoinsChere['prix'] : 0,
                'budget' => $budget,
                'symptomesSelectionnes' => $symptomesSelectionnes,
                'graviteTotal' => $graviteTotal,
                'resultatGuerison' => $resultatGuerison,
                'toutesCombinaisons' => $toutesCombinaisons,
                'budgetSuffisant' => false
            ]);
        }
    }

    /**
     * Récupérer les symptômes sélectionnés avec leurs gravités - RECURSIF
     */
    private static function getSymptomesAvecGraviteRecursif($symptomeModel, $ids, $gravites, $index, $result) {
        if ($index >= count($ids)) {
            return $result;
        }
        
        $id = $ids[$index];
        $s = $symptomeModel->getSymptomeById($id);
        if ($s) {
            // Ajouter la gravité saisie par le patient
            $s['gravite'] = isset($gravites[$id]) ? intval($gravites[$id]) : 1;
            $result[] = $s;
        }
        
        return self::getSymptomesAvecGraviteRecursif($symptomeModel, $ids, $gravites, $index + 1, $result);
    }

    /**
     * Calculer la gravité totale - RECURSIF
     */
    private static function calculerGraviteTotaleRecursif($symptomes, $index) {
        if ($index >= count($symptomes)) {
            return 0;
        }
        $gravite = isset($symptomes[$index]['gravite']) ? $symptomes[$index]['gravite'] : 0;
        return $gravite + self::calculerGraviteTotaleRecursif($symptomes, $index + 1);
    }

    /**
     * Récupérer les symptômes sélectionnés - RECURSIF
     */
    private static function getSymptomesSelectionnesRecursif($symptomeModel, $ids, $index, $result) {
        if ($index >= count($ids)) {
            return $result;
        }
        
        $s = $symptomeModel->getSymptomeById($ids[$index]);
        if ($s) {
            $result[] = $s;
        }
        
        return self::getSymptomesSelectionnesRecursif($symptomeModel, $ids, $index + 1, $result);
    }

    // ==========================================
    // TRI DES SYMPTOMES - VERSION RECURSIVE (Quicksort)
    // ==========================================

    /**
     * Afficher les symptômes triés par nom - RECURSIF (quicksort)
     */
    public static function symptomesTries() {
        $symptomeModel = new Symptome(Flight::db());
        $symptomes = $symptomeModel->getAllSymptomes();
        $symptomes = self::quicksortRecursif($symptomes);
        
        Flight::render('accueil', [
            'symptomes' => $symptomes,
            'count' => $symptomeModel->compterSymptomes($symptomes)
        ]);
    }

    /**
     * Quicksort récursif
     */
    private static function quicksortRecursif($array) {
        if (count($array) <= 1) {
            return $array;
        }
        
        $pivot = $array[0];
        $left = [];
        $right = [];
        
        self::partitionnerRecursif($array, $pivot, 1, $left, $right);
        
        return array_merge(
            self::quicksortRecursif($left),
            [$pivot],
            self::quicksortRecursif($right)
        );
    }

    private static function partitionnerRecursif($array, $pivot, $index, &$left, &$right) {
        if ($index >= count($array)) {
            return;
        }
        
        if (strcasecmp($array[$index]['nom'], $pivot['nom']) < 0) {
            $left[] = $array[$index];
        } else {
            $right[] = $array[$index];
        }
        
        self::partitionnerRecursif($array, $pivot, $index + 1, $left, $right);
    }

    // ==========================================
    // RECHERCHE - VERSION RECURSIVE
    // ==========================================

    /**
     * Rechercher un symptôme
     */
    public static function rechercherSymptome() {
        $nom = Flight::request()->query->nom ?? '';
        
        $symptomeModel = new Symptome(Flight::db());
        $symptomes = $symptomeModel->getAllSymptomes();
        $resultat = $symptomeModel->rechercherSymptome($symptomes, $nom);
        
        Flight::render('recherche_resultat', [
            'resultat' => $resultat,
            'terme' => $nom
        ]);
    }
}
