# Les métiers de l'immobilier

## Membres du groupe :

- ETU004102 RALIJAONA Jonathan Andriamanamisaina
- ETU004277 RANAIVOSOA Owan

---

## Entrées de données possibles (Inputs)

### 1) Caractéristiques Physiques du Bien

**Données brutes décrivant le produit immobilier.**

- ***Identification :*** Localisation exacte (quartier, ville), accessibilité (rue piétonne, parking, étage).
- ***Dimensions :*** Surface totale (m²), hauteur sous plafond, terrain.
- ***Style et Genre :*** Type de bien (appartement, villa, bureau) et style (ancien, moderne, futuriste).

### 2) Descriptif Technique et Confort

**Données précisant l'usage du bâtiment.**

- ***Configuration :*** Nombre de chambres, nombre de pièces d'eau, présence d'un extérieur (balcon, jardin).
- ***Spécifications :*** Équipements inclus (cuisine équipée, domotique/maison connectée, climatisation).

### 3) Données financières

**Valeurs monétaires nécessaires au calcul de rentabilité.**

- ***Prix :*** Prix de vente souhaité ou montant du loyer mensuel.
- ***Charges :*** taxes locales, caution (pour la location).

### 4) Données des Acteurs (Ressources Humaines)

**Informations sur les personnes gérées par le système.**

- ***Le Propriétaire (Vendeur ou Bailleur) :*** Identité, coordonnées, type de mandat signé avec l'agence.
- ***Le Client (Acheteur ou Locataire) :*** Identité, budget maximum, critères prioritaires (ex: "proche du métro").

### 5) Données Juridiques et Administratives

**Documents légaux garantissant la validité du dossier.**

- ***Titres :*** Numéro de titre de propriété ou contrat de bail.
- ***Diagnostics :*** Certificats obligatoires (énergie, amiante, sécurité électrique).

## Traitements de données possibles

- ***Identification de la nature de la transaction : Vente ou Location***

  - Traitement différencié selon le contexte (frais de notaire pour la vente vs caution pour la location).
- ***Rapprochement automatique (Matching)***

  - Le système compare les critères de l'acheteur/locataire avec les biens en stock.
  - Extraction automatique des biens correspondants à la localisation et au budget.
- ***Étude de solvabilité et filtrage des dossiers***

  - Pour la location : calcul automatique du ratio `Loyer / Revenus`.
  - Si le loyer dépasse 33% du salaire, le système marque le dossier comme "à risque".
- ***Calcul des frais et honoraires de l'agence***

  - Pour une vente : calcul du pourcentage de commission sur le prix final.
  - Pour une location : calcul des frais de dossier et d'état des lieux.
- ***Estimation du bénéfice de l'agence par projet***

  - `Bénéfice = Commissions encaissées - Frais engagés` (publicité, déplacements, temps passé par l'agent).
- ***Gestion de la conformité juridique***

  - Vérification automatique de la validité des diagnostics (ex: si le diagnostic énergie est périmé, le SI bloque la publication de l'annonce).
- ***Comparaison du prix de vente avec le marché***

  - Le système compare le prix du bien avec la moyenne des prix au m² du quartier pour conseiller le vendeur.
- ***Calcul des taxes et frais légaux***

  - Estimation des droits d'enregistrement (frais de notaire) pour l'acheteur.

  ## Sorties de données possibles (Outputs)
- ***Documents contractuels (Preuves d'accord)***

  - ***Acte de vente / Compromis :*** Document officiel scellant le transfert de propriété.
  - ***Contrat de bail :*** Pour la location, définit les droits et devoirs du locataire et du propriétaire.
- ***Documents financiers (Preuves de flux monétaires)***

  - ***Facture d'honoraires :*** Détail des commissions perçues par l'agence pour son service.
  - ***Reçus et Quittances :*** Preuve de paiement de la caution, du premier loyer ou des frais de dossier.
- ***Documents d'information (Traçabilité)***

  - ***Historique du bien :*** Récapitulatif des anciennes ventes, des travaux effectués et de l'évolution du prix.
  - ***Rapport de visite :*** Compte-rendu des avis des clients potentiels pour le propriétaire.
- ***Tableaux de bord de l'entreprise***

  - ***Récapitulatif des bénéfices :*** Calcul de la marge nette réalisée sur la période.
  - ***Statistiques de performance :*** Temps moyen de vente d'un bien, nombre de nouveaux clients par mois.
