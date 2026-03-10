/**
 * Point d'entrée des configurations
 * @module config
 */
const databaseConfig = require('./database.config');
const appConfig = require('./app.config');

module.exports = {
    database: databaseConfig,
    app: appConfig
};
