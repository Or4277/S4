/**
 * Routes pour la vérification de santé de l'API
 * @module routes/health
 */
const express = require('express');
const router = express.Router();
const HealthController = require('../controllers/health.controller');

/**
 * @route GET /api/health
 * @description Vérifie la santé de l'API
 */
router.get('/', HealthController.getHealth);

/**
 * @route GET /api/health/database
 * @description Vérifie l'état de la connexion à la base de données
 */
router.get('/database', HealthController.getDatabaseStatus);

/**
 * @route POST /api/health/database/connect
 * @description Tente de se connecter à la base de données
 */
router.post('/database/connect', HealthController.connectDatabase);

/**
 * @route POST /api/health/database/disconnect
 * @description Déconnecte de la base de données
 */
router.post('/database/disconnect', HealthController.disconnectDatabase);

module.exports = router;
