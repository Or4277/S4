/**
 * Point d'entrée principal des routes
 * @module routes
 */
const express = require('express');
const router = express.Router();

// Import des routes
const healthRoutes = require('./health.routes');
const lotRoutes = require('./lot.routes');

// Enregistrement des routes
router.use('/health', healthRoutes);
router.use('/lots', lotRoutes);

module.exports = router;
