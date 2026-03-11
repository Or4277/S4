
package com.tuto;


import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;

@Component
public class Ecole {
    
    @Value("ITU")
    private String nom;



   
    public String GetName(){
        return nom;
    }
    public void SetName(String nom){
        this.nom= nom ;
    }
    public void display(){ 
        System.out.println("Nom de l'ecole  :" + nom);
    ;

    }




    
    
}
