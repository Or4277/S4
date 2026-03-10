/**
 * Repository pour l'accès aux données des Lots
 * Pattern Repository pour l'encapsulation des requêtes SQL
 * @module repositories/lot
 */
const DatabaseService = require('../services/database.service');
const Lot = require('../models/lot.model');

class LotRepository {
    constructor() {
        this.dbService = DatabaseService.getInstance();
    }

    /**
     * Récupère tous les lots
     * @returns {Promise<Lot[]>}
     */
    async findAll() {
        const query = `
            SELECT l.*, r.nom as race_nom 
            FROM lots l
            LEFT JOIN races r ON l.id_race = r.id
            ORDER BY l.date_creation DESC
        `;
        const result = await this.dbService.executeQuery(query);
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        return result.data.map(row => ({
            ...Lot.fromDatabase(row).toJSON(),
            raceNom: row.race_nom
        }));
    }

    /**
     * Récupère un lot par son ID
     * @param {number} id 
     * @returns {Promise<Lot|null>}
     */
    async findById(id) {
        const query = `
            SELECT l.*, r.nom as race_nom 
            FROM lots l
            LEFT JOIN races r ON l.id_race = r.id
            WHERE l.id = @id
        `;
        const result = await this.dbService.executeQuery(query, { id });
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        if (result.data.length === 0) {
            return null;
        }
        
        const row = result.data[0];
        return {
            ...Lot.fromDatabase(row).toJSON(),
            raceNom: row.race_nom
        };
    }

    /**
     * Crée un nouveau lot
     * @param {object} lotData 
     * @returns {Promise<Lot>}
     */
    async create(lotData) {
        const query = `
            INSERT INTO lots (id_race, nombre_poules_depart, date_creation, id_incubation)
            OUTPUT INSERTED.*
            VALUES (@idRace, @nombrePoulesDepart, @dateCreation, @idIncubation)
        `;
        
        const params = {
            idRace: lotData.idRace,
            nombrePoulesDepart: lotData.nombrePoulesDepart,
            dateCreation: lotData.dateCreation || new Date(),
            idIncubation: lotData.idIncubation || null
        };
        
        const result = await this.dbService.executeQuery(query, params);
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        return Lot.fromDatabase(result.data[0]).toJSON();
    }

    /**
     * Met à jour un lot existant
     * @param {number} id 
     * @param {object} lotData 
     * @returns {Promise<Lot|null>}
     */
    async update(id, lotData) {
        const query = `
            UPDATE lots 
            SET id_race = @idRace,
                nombre_poules_depart = @nombrePoulesDepart,
                date_creation = @dateCreation,
                id_incubation = @idIncubation
            OUTPUT INSERTED.*
            WHERE id = @id
        `;
        
        const params = {
            id,
            idRace: lotData.idRace,
            nombrePoulesDepart: lotData.nombrePoulesDepart,
            dateCreation: lotData.dateCreation,
            idIncubation: lotData.idIncubation || null
        };
        
        const result = await this.dbService.executeQuery(query, params);
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        if (result.data.length === 0) {
            return null;
        }
        
        return Lot.fromDatabase(result.data[0]).toJSON();
    }

    /**
     * Supprime un lot par son ID
     * @param {number} id 
     * @returns {Promise<boolean>}
     */
    async delete(id) {
        const query = `DELETE FROM lots WHERE id = @id`;
        const result = await this.dbService.executeQuery(query, { id });
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        return result.rowsAffected[0] > 0;
    }

    /**
     * Récupère les lots par race
     * @param {number} idRace 
     * @returns {Promise<Lot[]>}
     */
    async findByRace(idRace) {
        const query = `
            SELECT l.*, r.nom as race_nom 
            FROM lots l
            LEFT JOIN races r ON l.id_race = r.id
            WHERE l.id_race = @idRace
            ORDER BY l.date_creation DESC
        `;
        const result = await this.dbService.executeQuery(query, { idRace });
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        return result.data.map(row => ({
            ...Lot.fromDatabase(row).toJSON(),
            raceNom: row.race_nom
        }));
    }

    /**
     * Compte le nombre total de lots
     * @returns {Promise<number>}
     */
    async count() {
        const query = `SELECT COUNT(*) as total FROM lots`;
        const result = await this.dbService.executeQuery(query);
        
        if (!result.success) {
            throw new Error(result.error);
        }
        
        return result.data[0].total;
    }
}

module.exports = LotRepository;
