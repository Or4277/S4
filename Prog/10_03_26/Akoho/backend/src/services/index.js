/**
 * Point d'entrée des services
 * @module services
 */
const DatabaseService = require('./database.service');
const LotService = require('./lot.service');

module.exports = {
    DatabaseService,
    LotService
};
