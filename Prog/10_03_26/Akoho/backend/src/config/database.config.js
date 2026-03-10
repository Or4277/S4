/**
 * Configuration de la base de données SQL Server
 * @module config/database
 */
require('dotenv').config();

const databaseConfig = {
    server: process.env.DB_SERVER || 'localhost',
    port: parseInt(process.env.DB_PORT) || 1433,
    database: process.env.DB_DATABASE || 's4',
    user: process.env.DB_USER || 'sa',
    password: process.env.DB_PASSWORD || 'SQLserver2213',
    options: {
        encrypt: false, // Pour les connexions locales
        trustServerCertificate: true, // Pour le développement
        enableArithAbort: true
    },
    pool: {
        max: 10,
        min: 0,
        idleTimeoutMillis: 30000
    }
};

module.exports = databaseConfig;
