package main.modele;

import java.time.LocalDate;

public class Cheque {
    private int id_cheque;
    private String numero_cheque;
    private String numero_compte;
    private LocalDate date;
    private float montant;

    public Cheque() {
    }

    public int getId_cheque() {
        return id_cheque;
    }

    public void setId_cheque(int id_cheque) {
        this.id_cheque = id_cheque;
    }

    public String getNumero_cheque() {
        return numero_cheque;
    }

    public void setNumero_cheque(String numero_cheque) {
        this.numero_cheque = numero_cheque;
    }

    public String getNumero_compte() {
        return numero_compte;
    }

    public void setNumero_compte(String numero_compte) {
        this.numero_compte = numero_compte;
    }

    public LocalDate getDate() {
        return date;
    }

    public void setDate(LocalDate date) {
        this.date = date;
    }

    public float getMontant() {
        return montant;
    }

    public void setMontant(float montant) {
        this.montant = montant;
    }

    public Cheque(String numero_cheque, String numero_compte, LocalDate date)  {
        setNumero_cheque(numero_cheque);
        setNumero_compte(numero_compte);
        this.date = date;
    }

    public Cheque(String numero_cheque) {
        this.numero_cheque = numero_cheque;
    }

}


