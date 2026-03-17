package main.conn;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

@Component
public class UtilDB {
    private Connection conn;
    @Value("jdbc:mariadb://localhost:3306/s4")
    private  String url ;
    @Value("root")
    private String user ;
    @Value("")
    private  String mdp ;

    public Connection connect() throws SQLException {
        try {
            Class.forName("org.mariadb.jdbc.Driver");
        } catch (ClassNotFoundException e) {
            System.err.println("Driver introuvable !");
            throw new RuntimeException(e);
        }
        try {
            conn = DriverManager.getConnection(url, user, mdp);
            conn.setAutoCommit(false);
            System.out.println("Connexion réussie !");
        } catch (SQLException e) {
            System.err.println("Erreur de connexion : " + e.getMessage());
            throw e;
        }
        return conn;
    }

    public  Connection getConnection() throws SQLException {
        if (conn == null || conn.isClosed()) {
            conn = connect();
        }
        return conn;
    }



}
