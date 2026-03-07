package com.tuto;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class AppConfig {
    @Bean
    public Personne personne(){
        Personne pers= new Personne();

        pers.setFirstName("RNV");
        pers.setLastName("Oxno");
        pers.setAge(18);

        return pers;

    }
    
}
