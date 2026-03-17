package main.service;

import main.dao.ChequeDAO;
import main.modele.Cheque;
import org.springframework.stereotype.Component;

import java.sql.SQLException;
import java.util.List;

@Component
public class ChequeService {
    private ChequeDAO chequeDAO;
    public ChequeService(ChequeDAO dao) {
        chequeDAO = dao;
    }
    public List<Cheque> getAllCheques() throws SQLException, SQLException {
        return chequeDAO.getAllCheques();
    }
}
