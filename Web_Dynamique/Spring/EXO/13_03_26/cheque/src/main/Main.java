package main;
import main.conn.UtilDB;
import main.dao.ChequeDAO;
import main.modele.Cheque;
import main.service.ChequeService;
import org.springframework.context.ApplicationContext;
import org.springframework.context.annotation.AnnotationConfigApplicationContext;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.List;

public class Main {
    static void main(String[] args) throws SQLException {
        ApplicationContext context = new AnnotationConfigApplicationContext(ConfigGlobal.class);
        ChequeService service = context.getBean(ChequeService.class);
        List<Cheque> cheques = service.getAllCheques();
        System.out.println("Cheques: " + cheques.size());
    }
}