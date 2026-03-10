/**
 * Contrôleur pour la vérification de la santé de l'application et de la base de données
 * @module controllers/health
 */
const DatabaseService = require('../services/database.service');

class HealthController {
    /**
     * Vérifie la santé générale de l'API
     * @param {Request} req 
     * @param {Response} res 
     */
    static async getHealth(req, res) {
        res.json({
            status: 'OK',
            message: 'API Akoho est opérationnelle',
            timestamp: new Date().toISOString()
        });
    }

    /**
     * Vérifie l'état de la connexion à la base de données
     * @param {Request} req 
     * @param {Response} res 
     */
    static async getDatabaseStatus(req, res) {
        try {
            const dbService = DatabaseService.getInstance();
            const connectionStatus = await dbService.checkConnection();

            const statusCode = connectionStatus.connected ? 200 : 503;
            
            res.status(statusCode).json({
                status: connectionStatus.connected ? 'CONNECTED' : 'DISCONNECTED',
                ...connectionStatus.details
            });
        } catch (error) {
            res.status(500).json({
                status: 'ERROR',
                message: `Erreur lors de la vérification: ${error.message}`,
                timestamp: new Date().toISOString()
            });
        }
    }

    /**
     * Tente de se connecter à la base de données
     * @param {Request} req 
     * @param {Response} res 
     */
    static async connectDatabase(req, res) {
        try {
            const dbService = DatabaseService.getInstance();
            const result = await dbService.connect();

            const statusCode = result.success ? 200 : 500;
            res.status(statusCode).json(result);
        } catch (error) {
            res.status(500).json({
                success: false,
                message: `Erreur: ${error.message}`,
                timestamp: new Date().toISOString()
            });
        }
    }

    /**
     * Déconnecte de la base de données
     * @param {Request} req 
     * @param {Response} res 
     */
    static async disconnectDatabase(req, res) {
        try {
            const dbService = DatabaseService.getInstance();
            const result = await dbService.disconnect();

            res.json(result);
        } catch (error) {
            res.status(500).json({
                success: false,
                message: `Erreur: ${error.message}`,
                timestamp: new Date().toISOString()
            });
        }
    }
}

module.exports = HealthController;
