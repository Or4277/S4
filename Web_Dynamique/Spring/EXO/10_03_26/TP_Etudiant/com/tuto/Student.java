package com.tuto;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;

@Component
public class Student {
    
    @Value("Oxno")
    private String name;
    
    @Value("18")
    private int number;

    @Value("oxno@gmail.com")
    private String email;
    

    @Autowired
    private Ecole ecole;

    public String getName(){
        return name;
    }
    public void setName(String name){
        this.name = name;
    }

    public int getNumber(){
        return number;
    }
    public void setNumber(int number){
        this.number = number;
    }

    public String getEmail(){
        return email;
    }
    public void setEmail(String email){
        this.email = email ;
    }

    public void display(){
        System.out.println("Nom:" + name);
        System.out.println("NUmero:" + number);
        System.out.println("Email:" + email);
        System.out.println("Ecole:" + ecole.GetName());

    }

    @Override
    public boolean equals(Object o){
        

    }
}
