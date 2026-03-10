-- Création de la base de données si elle n'existe pas
IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'S4')
BEGIN
    CREATE DATABASE S4;
END
GO

USE S4;
GO

-- Suppression des tables si elles existent (dans l'ordre inverse des dépendances)
IF OBJECT_ID('historique_recensement_morts', 'U') IS NOT NULL DROP TABLE historique_recensement_morts;
IF OBJECT_ID('historique_recolte_oeufs', 'U') IS NOT NULL DROP TABLE historique_recolte_oeufs;
IF OBJECT_ID('lots', 'U') IS NOT NULL DROP TABLE lots;
IF OBJECT_ID('historique_incubations', 'U') IS NOT NULL DROP TABLE historique_incubations;
IF OBJECT_ID('races_details', 'U') IS NOT NULL DROP TABLE races_details;
IF OBJECT_ID('races', 'U') IS NOT NULL DROP TABLE races;
GO

-- Create races table
CREATE TABLE races (
    id INT PRIMARY KEY IDENTITY(1,1),
    nom NVARCHAR(100) NOT NULL UNIQUE,
    prix_achat DECIMAL(10, 2) NOT NULL,
    prix_vente_par_gramme DECIMAL(10, 2) NOT NULL,
    prix_oeuf DECIMAL(10, 2) NOT NULL
);
GO

-- Create races_details table
CREATE TABLE races_details (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_race INT NOT NULL,
    semaine INT NOT NULL,
    nourriture_par_semaine DECIMAL(10, 2) NOT NULL,
    augmentation_poids_par_semaine DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_race) REFERENCES races(id) ON DELETE CASCADE,
    UNIQUE(id_race, semaine)
);
GO

-- Create historique_incubations table
CREATE TABLE historique_incubations (
    id INT PRIMARY KEY IDENTITY(1,1),
    nombre_oeufs INT NOT NULL,
    date_incubation DATETIME NOT NULL,
    date_fin DATETIME DEFAULT GETDATE()
);
GO

-- Create lots table
CREATE TABLE lots (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_race INT NOT NULL,
    nombre_poules_depart INT NOT NULL,
    date_creation DATETIME DEFAULT GETDATE(),
    id_incubation INT,
    FOREIGN KEY (id_race) REFERENCES races(id),
    FOREIGN KEY (id_incubation) REFERENCES historique_incubations(id)
);
GO

-- Create historique_recolte_oeufs table
CREATE TABLE historique_recolte_oeufs (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_lot INT NOT NULL,
    nombre_oeufs INT NOT NULL,
    date_recolte DATETIME NOT NULL,
    FOREIGN KEY (id_lot) REFERENCES lots(id)
);
GO

-- Create historique_recensement_morts table
CREATE TABLE historique_recensement_morts (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_lot INT NOT NULL,
    date_deces DATETIME NOT NULL,
    nombre_morts INT NOT NULL,
    a_mange_jour BIT NOT NULL,
    FOREIGN KEY (id_lot) REFERENCES lots(id)
);
GO

-- =============================================
-- DONNÉES DE TEST
-- =============================================

-- Insérer des races de test
INSERT INTO races (nom, prix_achat, prix_vente_par_gramme, prix_oeuf) VALUES 
('Brahma', 15000.00, 12.50, 500.00),
('Rhode Island', 12000.00, 10.00, 450.00),
('Leghorn', 8000.00, 8.50, 400.00);
GO

-- Insérer des races_details de test
INSERT INTO races_details (id_race, semaine, nourriture_par_semaine, augmentation_poids_par_semaine) VALUES 
(1, 1, 500.00, 150.00),
(1, 2, 700.00, 200.00),
(1, 3, 900.00, 250.00),
(2, 1, 450.00, 120.00),
(2, 2, 600.00, 180.00),
(3, 1, 400.00, 100.00);
GO

-- Insérer des incubations de test
INSERT INTO historique_incubations (nombre_oeufs, date_incubation, date_fin) VALUES 
(50, '2026-02-01', '2026-02-22'),
(30, '2026-02-15', '2026-03-08');
GO

-- Insérer des lots de test
INSERT INTO lots (id_race, nombre_poules_depart, date_creation, id_incubation) VALUES 
(1, 100, '2026-01-15', NULL),
(2, 75, '2026-02-01', NULL),
(1, 45, '2026-02-22', 1),
(3, 50, '2026-03-01', NULL),
(2, 28, '2026-03-08', 2);
GO
/*  */
-- Insérer des récoltes d'oeufs de test
INSERT INTO historique_recolte_oeufs (id_lot, nombre_oeufs, date_recolte) VALUES 
(1, 85, '2026-02-15'),
(1, 90, '2026-02-22'),
(2, 60, '2026-02-20'),
(4, 40, '2026-03-08');
GO

-- Insérer des recensements de morts de test
INSERT INTO historique_recensement_morts (id_lot, date_deces, nombre_morts, a_mange_jour) VALUES 
(1, '2026-02-10', 3, 1),
(2, '2026-02-18', 2, 0),
(3, '2026-03-01', 1, 1);
GO

PRINT 'Schema et données de test créés avec succès!';