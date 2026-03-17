package main.dao;
import main.conn.UtilDB;
import main.modele.*;

import org.springframework.stereotype.Component;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

@Component
public class ChequeDAO {
    private UtilDB utilDB;
    public ChequeDAO(UtilDB utilDB) {
        this.utilDB = utilDB;
    }
    public List<Cheque> getAllCheques() throws SQLException {
       List<Cheque> cheques = new ArrayList<>();
        try(Connection c = utilDB.getConnection()){
            PreparedStatement ps = c.prepareStatement("SELECT * FROM cheque");
            ResultSet rs = ps.executeQuery();
            while(rs.next()){
                Cheque cheque = new Cheque();
                cheque.setId_cheque(rs.getInt("id"));
                cheque.setNumero_cheque(rs.getString("numero_cheque"));
                cheque.setNumero_compte(rs.getString("numero_compte"));
                cheque.setDate(rs.getDate("date_validite").toLocalDate());
                cheque.setMontant(rs.getFloat("montant"));
                cheques.add(cheque);
            }
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
        return cheques;
    }
}
