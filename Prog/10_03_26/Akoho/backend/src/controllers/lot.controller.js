/**
 * Contrôleur pour la gestion des Lots
 * Gère les requêtes HTTP et délègue au Service
 * @module controllers/lot
 */
const LotService = require('../services/lot.service');

class LotController {
    constructor() {
        this.lotService = new LotService();
    }

    /**
     * GET /api/lots
     * Récupère tous les lots
     */
    getAll = async (req, res) => {
        try {
            const result = await this.lotService.getAllLots();
            
            if (result.success) {
                res.json({
                    success: true,
                    data: result.data,
                    count: result.count
                });
            } else {
                res.status(500).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };

    /**
     * GET /api/lots/:id
     * Récupère un lot par son ID
     */
    getById = async (req, res) => {
        try {
            const { id } = req.params;
            const result = await this.lotService.getLotById(id);
            
            if (result.success) {
                res.json({
                    success: true,
                    data: result.data
                });
            } else {
                const status = result.error.includes('non trouvé') ? 404 : 400;
                res.status(status).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };

    /**
     * POST /api/lots
     * Crée un nouveau lot
     */
    create = async (req, res) => {
        try {
            const lotData = req.body;
            const result = await this.lotService.createLot(lotData);
            
            if (result.success) {
                res.status(201).json({
                    success: true,
                    data: result.data,
                    message: result.message
                });
            } else {
                res.status(400).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };

    /**
     * PUT /api/lots/:id
     * Met à jour un lot existant
     */
    update = async (req, res) => {
        try {
            const { id } = req.params;
            const lotData = req.body;
            const result = await this.lotService.updateLot(id, lotData);
            
            if (result.success) {
                res.json({
                    success: true,
                    data: result.data,
                    message: result.message
                });
            } else {
                const status = result.error.includes('non trouvé') ? 404 : 400;
                res.status(status).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };

    /**
     * DELETE /api/lots/:id
     * Supprime un lot
     */
    delete = async (req, res) => {
        try {
            const { id } = req.params;
            const result = await this.lotService.deleteLot(id);
            
            if (result.success) {
                res.json({
                    success: true,
                    message: result.message
                });
            } else {
                const status = result.error.includes('non trouvé') ? 404 : 400;
                res.status(status).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };

    /**
     * GET /api/lots/race/:idRace
     * Récupère les lots par race
     */
    getByRace = async (req, res) => {
        try {
            const { idRace } = req.params;
            const result = await this.lotService.getLotsByRace(idRace);
            
            if (result.success) {
                res.json({
                    success: true,
                    data: result.data,
                    count: result.count
                });
            } else {
                res.status(400).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };

    /**
     * GET /api/lots/statistics
     * Récupère les statistiques des lots
     */
    getStatistics = async (req, res) => {
        try {
            const result = await this.lotService.getStatistics();
            
            if (result.success) {
                res.json({
                    success: true,
                    data: result.data
                });
            } else {
                res.status(500).json({
                    success: false,
                    error: result.error
                });
            }
        } catch (error) {
            res.status(500).json({
                success: false,
                error: `Erreur serveur: ${error.message}`
            });
        }
    };
}

module.exports = LotController;
