/**
 * Service métier pour la gestion des Lots
 * Contient la logique métier et utilise le Repository pour l'accès aux données
 * @module services/lot
 */
const LotRepository = require('../repositories/lot.repository');
const Lot = require('../models/lot.model');

class LotService {
    constructor() {
        this.repository = new LotRepository();
    }

    /**
     * Récupère tous les lots
     * @returns {Promise<{success: boolean, data?: Lot[], error?: string}>}
     */
    async getAllLots() {
        try {
            const lots = await this.repository.findAll();
            return {
                success: true,
                data: lots,
                count: lots.length
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors de la récupération des lots: ${error.message}`
            };
        }
    }

    /**
     * Récupère un lot par son ID
     * @param {number} id 
     * @returns {Promise<{success: boolean, data?: Lot, error?: string}>}
     */
    async getLotById(id) {
        try {
            if (!id || isNaN(id)) {
                return {
                    success: false,
                    error: 'ID invalide'
                };
            }

            const lot = await this.repository.findById(parseInt(id));
            
            if (!lot) {
                return {
                    success: false,
                    error: `Lot avec l'ID ${id} non trouvé`
                };
            }

            return {
                success: true,
                data: lot
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors de la récupération du lot: ${error.message}`
            };
        }
    }

    /**
     * Crée un nouveau lot
     * @param {object} lotData 
     * @returns {Promise<{success: boolean, data?: Lot, error?: string}>}
     */
    async createLot(lotData) {
        try {
            // Création d'une instance pour validation
            const lot = new Lot(
                null,
                lotData.idRace,
                lotData.nombrePoulesDepart,
                lotData.dateCreation || new Date(),
                lotData.idIncubation
            );

            // Validation
            const validation = lot.validate();
            if (!validation.valid) {
                return {
                    success: false,
                    error: validation.errors.join(', ')
                };
            }

            const createdLot = await this.repository.create(lot.toJSON());
            
            return {
                success: true,
                data: createdLot,
                message: 'Lot créé avec succès'
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors de la création du lot: ${error.message}`
            };
        }
    }

    /**
     * Met à jour un lot existant
     * @param {number} id 
     * @param {object} lotData 
     * @returns {Promise<{success: boolean, data?: Lot, error?: string}>}
     */
    async updateLot(id, lotData) {
        try {
            if (!id || isNaN(id)) {
                return {
                    success: false,
                    error: 'ID invalide'
                };
            }

            // Vérifier si le lot existe
            const existingLot = await this.repository.findById(parseInt(id));
            if (!existingLot) {
                return {
                    success: false,
                    error: `Lot avec l'ID ${id} non trouvé`
                };
            }

            // Validation des nouvelles données
            const lot = new Lot(
                id,
                lotData.idRace,
                lotData.nombrePoulesDepart,
                lotData.dateCreation,
                lotData.idIncubation
            );

            const validation = lot.validate();
            if (!validation.valid) {
                return {
                    success: false,
                    error: validation.errors.join(', ')
                };
            }

            const updatedLot = await this.repository.update(parseInt(id), lot.toJSON());
            
            return {
                success: true,
                data: updatedLot,
                message: 'Lot mis à jour avec succès'
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors de la mise à jour du lot: ${error.message}`
            };
        }
    }

    /**
     * Supprime un lot
     * @param {number} id 
     * @returns {Promise<{success: boolean, error?: string}>}
     */
    async deleteLot(id) {
        try {
            if (!id || isNaN(id)) {
                return {
                    success: false,
                    error: 'ID invalide'
                };
            }

            // Vérifier si le lot existe
            const existingLot = await this.repository.findById(parseInt(id));
            if (!existingLot) {
                return {
                    success: false,
                    error: `Lot avec l'ID ${id} non trouvé`
                };
            }

            const deleted = await this.repository.delete(parseInt(id));
            
            if (deleted) {
                return {
                    success: true,
                    message: 'Lot supprimé avec succès'
                };
            }

            return {
                success: false,
                error: 'Erreur lors de la suppression'
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors de la suppression du lot: ${error.message}`
            };
        }
    }

    /**
     * Récupère les lots par race
     * @param {number} idRace 
     * @returns {Promise<{success: boolean, data?: Lot[], error?: string}>}
     */
    async getLotsByRace(idRace) {
        try {
            if (!idRace || isNaN(idRace)) {
                return {
                    success: false,
                    error: 'ID de race invalide'
                };
            }

            const lots = await this.repository.findByRace(parseInt(idRace));
            return {
                success: true,
                data: lots,
                count: lots.length
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors de la récupération des lots: ${error.message}`
            };
        }
    }

    /**
     * Récupère les statistiques des lots
     * @returns {Promise<{success: boolean, data?: object, error?: string}>}
     */
    async getStatistics() {
        try {
            const count = await this.repository.count();
            const lots = await this.repository.findAll();
            
            const totalPoules = lots.reduce((sum, lot) => sum + lot.nombrePoulesDepart, 0);
            
            return {
                success: true,
                data: {
                    totalLots: count,
                    totalPoulesDepart: totalPoules
                }
            };
        } catch (error) {
            return {
                success: false,
                error: `Erreur lors du calcul des statistiques: ${error.message}`
            };
        }
    }
}

module.exports = LotService;
