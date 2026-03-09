CREATE DATABASE IF NOT EXISTS s4;
USE s4;


DROP TABLE IF EXISTS affectation;
DROP TABLE IF EXISTS medicament;
DROP TABLE IF EXISTS symptome;

CREATE TABLE symptome (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE medicament (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    effet INT NOT NULL DEFAULT 
);

CREATE TABLE affectation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    symptome_id INT NOT NULL,
    medicament_id INT NOT NULL,
    FOREIGN KEY (symptome_id) REFERENCES symptome(id) ON DELETE CASCADE,
    FOREIGN KEY (medicament_id) REFERENCES medicament(id) ON DELETE CASCADE
);

-- =====================
-- DONNÉES DE TEST
-- =====================

-- Insertion des symptômes
INSERT INTO symptome (nom) VALUES 
('Fièvre'),
('Maux de tête'),
('Toux'),
('Nausée'),
('Fatigue'),
('Douleurs musculaires'),
('Mal de gorge'),
('Nez bouché'),
('Diarrhée'),
('Insomnie');

-- Insertion des médicaments avec effet (réduction de gravité)
INSERT INTO medicament (nom, prix, effet) VALUES 
('Doliprane', 3.50, 5),
('Ibuprofène', 4.20, 6),
('Aspirine', 2.80, 4),
('Sirop Toux', 8.50, 7),
('Smecta', 5.00, 5),
('Vogalib', 4.50, 6),
('Strepsils', 6.00, 4),
('Rhinofluimucil', 7.80, 6),
('Imodium', 5.50, 7),
('Donormyl', 6.50, 5),
('Vitamine C', 3.00, 3),
('Fervex', 9.00, 8),
('Efferalgan', 3.80, 5),
('Nurofen', 5.20, 6),
('Spasfon', 4.00, 4);

-- Relations symptôme-médicament
-- Fièvre (id=1)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(1, 1),  -- Doliprane
(1, 2),  -- Ibuprofène
(1, 3),  -- Aspirine
(1, 12), -- Fervex
(1, 13); -- Efferalgan

-- Maux de tête (id=2)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(2, 1),  -- Doliprane
(2, 2),  -- Ibuprofène
(2, 3),  -- Aspirine
(2, 13), -- Efferalgan
(2, 14); -- Nurofen

-- Toux (id=3)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(3, 4),  -- Sirop Toux
(3, 12); -- Fervex

-- Nausée (id=4)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(4, 6),  -- Vogalib
(4, 15); -- Spasfon

-- Fatigue (id=5)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(5, 11), -- Vitamine C
(5, 12); -- Fervex

-- Douleurs musculaires (id=6)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(6, 2),  -- Ibuprofène
(6, 14), -- Nurofen
(6, 15); -- Spasfon

-- Mal de gorge (id=7)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(7, 7),  -- Strepsils
(7, 12); -- Fervex

-- Nez bouché (id=8)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(8, 8),  -- Rhinofluimucil
(8, 12); -- Fervex

-- Diarrhée (id=9)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(9, 5),  -- Smecta
(9, 9);  -- Imodium

-- Insomnie (id=10)
INSERT INTO affectation (symptome_id, medicament_id) VALUES 
(10, 10); -- Donormyl