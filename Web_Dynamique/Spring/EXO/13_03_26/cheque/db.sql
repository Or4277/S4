CREATE TABLE cheque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_cheque VARCHAR(50) NOT NULL,
    numero_compte VARCHAR(50) NOT NULL,
    date_validite DATE,
    montant FLOAT
);


INSERT INTO cheque(numero_cheque,numero_compte,date_validite,montant) VALUES 
("CHQ01","2213","2026-03-13","10000.00"),
("CHQ02","1234","2026-03-13","2500.00"),
("CHQ03","003423","2026-03-13","12300.00"),
("CHQ04","9887","2026-03-13","08473.00"),
("CHQ05","45689","2026-03-13","98765.00"),
("CHQ06","6672","2026-03-13","89800.00");

