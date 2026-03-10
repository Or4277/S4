/**
 * Modèle représentant un Lot de poules
 * @module models/lot
 */
class Lot {
    /**
     * @param {number} id - Identifiant unique
     * @param {number} idRace - Identifiant de la race
     * @param {number} nombrePoulesDepart - Nombre de poules au départ
     * @param {Date} dateCreation - Date de création du lot
     * @param {number|null} idIncubation - Identifiant de l'incubation (optionnel)
     */
    constructor(id, idRace, nombrePoulesDepart, dateCreation, idIncubation = null) {
        this.id = id;
        this.idRace = idRace;
        this.nombrePoulesDepart = nombrePoulesDepart;
        this.dateCreation = dateCreation;
        this.idIncubation = idIncubation;
    }

    /**
     * Crée une instance de Lot à partir d'un objet SQL
     * @param {object} row - Ligne de résultat SQL
     * @returns {Lot}
     */
    static fromDatabase(row) {
        return new Lot(
            row.id,
            row.id_race,
            row.nombre_poules_depart,
            row.date_creation,
            row.id_incubation
        );
    }

    /**
     * Convertit le lot en objet pour la base de données
     * @returns {object}
     */
    toDatabase() {
        return {
            id_race: this.idRace,
            nombre_poules_depart: this.nombrePoulesDepart,
            date_creation: this.dateCreation,
            id_incubation: this.idIncubation
        };
    }

    /**
     * Convertit le lot en JSON pour l'API
     * @returns {object}
     */
    toJSON() {
        return {
            id: this.id,
            idRace: this.idRace,
            nombrePoulesDepart: this.nombrePoulesDepart,
            dateCreation: this.dateCreation,
            idIncubation: this.idIncubation
        };
    }

    /**
     * Valide les données du lot
     * @returns {{valid: boolean, errors: string[]}}
     */
    validate() {
        const errors = [];

        if (!this.idRace || this.idRace <= 0) {
            errors.push('L\'identifiant de la race est requis et doit être positif');
        }

        if (!this.nombrePoulesDepart || this.nombrePoulesDepart <= 0) {
            errors.push('Le nombre de poules au départ est requis et doit être positif');
        }

        return {
            valid: errors.length === 0,
            errors
        };
    }
}

module.exports = Lot;
