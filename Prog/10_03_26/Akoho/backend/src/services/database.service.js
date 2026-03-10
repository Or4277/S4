/**
 * Service de gestion de la connexion SQL Server
 * Pattern Singleton pour gérer le pool de connexions
 * @module services/database
 */
const sql = require('mssql');
const databaseConfig = require('../config/database.config');

class DatabaseService {
    constructor() {
        this._pool = null;
        this._isConnected = false;
    }

    /**
     * Instance singleton du service
     * @private
     */
    static _instance = null;

    /**
     * Récupère l'instance unique du service
     * @returns {DatabaseService}
     */
    static getInstance() {
        if (!DatabaseService._instance) {
            DatabaseService._instance = new DatabaseService();
        }
        return DatabaseService._instance;
    }

    /**
     * Établit la connexion à la base de données
     * @returns {Promise<{success: boolean, message: string}>}
     */
    async connect() {
        try {
            if (this._isConnected && this._pool) {
                return {
                    success: true,
                    message: 'Déjà connecté à la base de données'
                };
            }

            this._pool = await sql.connect(databaseConfig);
            this._isConnected = true;

            console.log('✅ Connexion à SQL Server établie avec succès');
            return {
                success: true,
                message: 'Connexion à SQL Server établie avec succès',
                database: databaseConfig.database,
                server: databaseConfig.server
            };
        } catch (error) {
            this._isConnected = false;
            console.error('❌ Erreur de connexion à SQL Server:', error.message);
            return {
                success: false,
                message: `Erreur de connexion: ${error.message}`,
                error: error.code || 'UNKNOWN_ERROR'
            };
        }
    }

    /**
     * Ferme la connexion à la base de données
     * @returns {Promise<{success: boolean, message: string}>}
     */
    async disconnect() {
        try {
            if (this._pool) {
                await this._pool.close();
                this._pool = null;
                this._isConnected = false;
                console.log('🔌 Connexion à SQL Server fermée');
                return {
                    success: true,
                    message: 'Connexion fermée avec succès'
                };
            }
            return {
                success: true,
                message: 'Aucune connexion active à fermer'
            };
        } catch (error) {
            console.error('❌ Erreur lors de la fermeture:', error.message);
            return {
                success: false,
                message: `Erreur lors de la fermeture: ${error.message}`
            };
        }
    }

    /**
     * Vérifie l'état de la connexion
     * @returns {Promise<{connected: boolean, details: object}>}
     */
    async checkConnection() {
        try {
            if (!this._pool || !this._isConnected) {
                return {
                    connected: false,
                    details: {
                        message: 'Pas de connexion active',
                        timestamp: new Date().toISOString()
                    }
                };
            }

            // Test de connexion avec une requête simple
            const result = await this._pool.request().query('SELECT 1 AS test');
            
            return {
                connected: true,
                details: {
                    message: 'Connexion active et fonctionnelle',
                    database: databaseConfig.database,
                    server: databaseConfig.server,
                    timestamp: new Date().toISOString()
                }
            };
        } catch (error) {
            this._isConnected = false;
            return {
                connected: false,
                details: {
                    message: `Erreur de connexion: ${error.message}`,
                    timestamp: new Date().toISOString()
                }
            };
        }
    }

    /**
     * Récupère le pool de connexions
     * @returns {sql.ConnectionPool|null}
     */
    getPool() {
        return this._pool;
    }

    /**
     * Vérifie si la connexion est établie
     * @returns {boolean}
     */
    isConnected() {
        return this._isConnected;
    }

    /**
     * Exécute une requête SQL
     * @param {string} query - La requête SQL à exécuter
     * @param {object} params - Les paramètres de la requête
     * @returns {Promise<object>}
     */
    async executeQuery(query, params = {}) {
        try {
            if (!this._pool || !this._isConnected) {
                throw new Error('Pas de connexion active à la base de données');
            }

            const request = this._pool.request();
            
            // Ajouter les paramètres à la requête
            Object.entries(params).forEach(([key, value]) => {
                request.input(key, value);
            });

            const result = await request.query(query);
            return {
                success: true,
                data: result.recordset,
                rowsAffected: result.rowsAffected
            };
        } catch (error) {
            return {
                success: false,
                error: error.message
            };
        }
    }
}

module.exports = DatabaseService;
