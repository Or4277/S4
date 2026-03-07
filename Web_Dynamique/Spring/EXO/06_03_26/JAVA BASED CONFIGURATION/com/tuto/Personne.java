package com.tuto;
import org.springframework.stereotype.Component;

public class Personne {
    private String firstName;
    private String lastName;
    private int age;


    public String getFirstName(){
        return firstName;
    }
    public void setFirstName(String firstName){
        this.firstName= firstName;

    }
    public String getLastName(){
        return lastName;
    }
    public void setLastName(String lastName){
        this.lastName= lastName;

    }
    public int getAge(){
        return age;
    }
    public void setAge (int age){
        this.age= age;

    }

    public void Afficher(){
        System.out.println("FirstName:" + firstName );
        System.out.println("LastName:" + lastName);
        System.out.println("Age:" + age);
    }


    
}
