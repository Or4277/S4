/**
 * Routes pour la gestion des Lots
 * @module routes/lot
 */
const express = require('express');
const router = express.Router();
const LotController = require('../controllers/lot.controller');

// Instance du contrôleur
const lotController = new LotController();

/**
 * @route GET /api/lots/statistics
 * @description Récupère les statistiques des lots
 * @note Cette route doit être avant /:id pour éviter les conflits
 */
router.get('/statistics', lotController.getStatistics);

/**
 * @route GET /api/lots/race/:idRace
 * @description Récupère les lots par race
 */
router.get('/race/:idRace', lotController.getByRace);

/**
 * @route GET /api/lots
 * @description Récupère tous les lots
 */
router.get('/', lotController.getAll);

/**
 * @route GET /api/lots/:id
 * @description Récupère un lot par son ID
 */
router.get('/:id', lotController.getById);

/**
 * @route POST /api/lots
 * @description Crée un nouveau lot
 * @body {idRace: number, nombrePoulesDepart: number, dateCreation?: Date, idIncubation?: number}
 */
router.post('/', lotController.create);

/**
 * @route PUT /api/lots/:id
 * @description Met à jour un lot existant
 * @body {idRace: number, nombrePoulesDepart: number, dateCreation: Date, idIncubation?: number}
 */
router.put('/:id', lotController.update);

/**
 * @route DELETE /api/lots/:id
 * @description Supprime un lot
 */
router.delete('/:id', lotController.delete);

module.exports = router;
