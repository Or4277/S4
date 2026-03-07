public class Student {

    private String name;
    private int number;
    private String email;

    
    public String getName() {
         return name; 
    }
    public int getNumber() {
         return number; 
    }
    public String getEmail() {
         return email; 
    }

    
    public void setName(String name) { 
        this.name = name; 
    }

    public void setNumber(int number) { 
        this.number = number; 
    }
    public void setEmail(String email) {
         this.email = email; 
    }


    public void afficher() {
        System.out.println("Nom: " + name + " Numero: " + number + " Email: " + email);
    }
}