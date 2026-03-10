/**
 * Point d'entrée principal de l'application
 * @module app
 */
require('dotenv').config();

const express = require('express');
const cors = require('cors');
const { app: appConfig } = require('./config');
const routes = require('./routes');
const DatabaseService = require('./services/database.service');

// Création de l'application Express
const app = express();

// Middlewares
app.use(cors(appConfig.corsOptions));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Routes API
app.use('/api', routes);

// Route racine
app.get('/', (req, res) => {
    res.json({
        name: 'Akoho API',
        version: '1.0.0',
        description: 'Backend API pour le projet Akoho',
        endpoints: {
            health: '/api/health',
            databaseStatus: '/api/health/database',
            connectDB: 'POST /api/health/database/connect',
            disconnectDB: 'POST /api/health/database/disconnect',
            lots: {
                getAll: 'GET /api/lots',
                getById: 'GET /api/lots/:id',
                getByRace: 'GET /api/lots/race/:idRace',
                getStatistics: 'GET /api/lots/statistics',
                create: 'POST /api/lots',
                update: 'PUT /api/lots/:id',
                delete: 'DELETE /api/lots/:id'
            }
        }
    });
});

// Gestion des routes non trouvées
app.use((req, res) => {
    res.status(404).json({
        status: 'NOT_FOUND',
        message: `Route ${req.method} ${req.url} non trouvée`
    });
});

// Gestion globale des erreurs
app.use((err, req, res, next) => {
    console.error('Erreur non gérée:', err);
    res.status(500).json({
        status: 'ERROR',
        message: 'Erreur interne du serveur',
        ...(appConfig.env === 'development' && { error: err.message })
    });
});

// Démarrage du serveur
const startServer = async () => {
    try {
        // Tentative de connexion à la base de données au démarrage
        const dbService = DatabaseService.getInstance();
        const connectionResult = await dbService.connect();
        
        if (connectionResult.success) {
            console.log(`📦 Base de données: ${connectionResult.database}`);
        } else {
            console.warn('⚠️  Base de données non connectée:', connectionResult.message);
            console.warn('   L\'API démarre quand même, vous pouvez connecter manuellement via /api/health/database/connect');
        }

        // Démarrage du serveur HTTP
        app.listen(appConfig.port, () => {
            console.log(`
🚀 Serveur Akoho démarré!
📍 URL: http://localhost:${appConfig.port}
🔧 Environnement: ${appConfig.env}
📋 Endpoints disponibles:
   - GET  /                           - Info API
   - GET  /api/health                 - Santé API
   - GET  /api/health/database        - Statut BDD
   - POST /api/health/database/connect    - Connecter BDD
   - POST /api/health/database/disconnect - Déconnecter BDD
            `);
        });
    } catch (error) {
        console.error('❌ Erreur au démarrage du serveur:', error);
        process.exit(1);
    }
};

// Gestion propre de l'arrêt
process.on('SIGINT', async () => {
    console.log('\n⏹️  Arrêt du serveur...');
    const dbService = DatabaseService.getInstance();
    await dbService.disconnect();
    process.exit(0);
});

process.on('SIGTERM', async () => {
    const dbService = DatabaseService.getInstance();
    await dbService.disconnect();
    process.exit(0);
});

// Lancement
startServer();
