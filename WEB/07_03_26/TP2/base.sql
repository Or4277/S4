CREATE DATABASE csv;
use csv;

create table clients(
    Numclient int  primary key auto_increment,
    Nom VARCHAR(50),
    Prenom  VARCHAR(50),
    Adresse VARCHAR (100)
);