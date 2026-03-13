CREATE TABLE etudiant (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) ,
    prenom VARCHAR(100) ,
    anniv DATE,
    lieu_anniv VARCHAR(100),
    Num_inscription VARCHAR(100),
    Filiere VARCHAR(100)
);

CREATE TABLE note (
    id INT PRIMARY KEY AUTO_INCREMENT,
    etudiant_id INT ,        
    UE VARCHAR(100),
    Intitule VARCHAR(100),
    Credit INT,
    Note_sur_vingt INT,
    Resultat VARCHAR(100),
    FOREIGN KEY (etudiant_id) REFERENCES etudiant(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Index utile pour rechercher rapidement les notes d'un étudiant
CREATE INDEX idx_note_etudiant ON note (etudiant_id);


--Donnee de test

INSERT INTO etudiant (nom, prenom, anniv, lieu_anniv, Num_inscription, Filiere) VALUES
('Rakoto', 'Jean', '2002-05-12', 'Antananarivo', 'INS001', 'Informatique'),
('Rabe', 'Marie', '2001-11-03', 'Fianarantsoa', 'INS002', 'Informatique'),
('Rasoa', 'Paul', '2003-01-20', 'Toamasina', 'INS003', 'Gestion'),
('Randria', 'Luc', '2002-07-15', 'Mahajanga', 'INS004', 'Informatique'),
('Razanaka', 'Lina', '2001-09-08', 'Antsirabe', 'INS005', 'Gestion');