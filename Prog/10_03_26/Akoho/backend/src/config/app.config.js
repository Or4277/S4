/**
 * Configuration de l'application Express
 * @module config/app
 */
require('dotenv').config();

const appConfig = {
    port: parseInt(process.env.PORT) || 3000,
    env: process.env.NODE_ENV || 'development',
    corsOptions: {
        origin: 'http://localhost:4200', // Angular dev server
        optionsSuccessStatus: 200,
        credentials: true
    }
};

module.exports = appConfig;
