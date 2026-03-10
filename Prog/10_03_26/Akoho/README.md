# 🐔 Akoho - Projet Full-Stack

Application Full-Stack avec **Node.js** (Backend), **Angular** (Frontend) et **SQL Server** (Base de données).

## 📁 Structure du projet

```
Akoho/
├── backend/                    # API Node.js
│   ├── src/
│   │   ├── config/            # Configuration (DB, App)
│   │   ├── controllers/       # Contrôleurs
│   │   ├── routes/            # Routes API
│   │   ├── services/          # Services (logique métier)
│   │   └── app.js             # Point d'entrée
│   ├── .env                   # Variables d'environnement
│   └── package.json
│
├── frontend/                   # Application Angular
│   ├── src/
│   │   ├── app/
│   │   │   ├── core/          # Services et modèles
│   │   │   └── features/      # Composants fonctionnels
│   │   └── environments/      # Config environnements
│   └── package.json
│
└── 1-schema.sql               # Schéma de la base de données
```

## 🚀 Installation

### Prérequis
- Node.js 18+
- SQL Server
- Angular CLI 17+

### Backend

```bash
cd backend
npm install
```

Configurez le fichier `.env` avec vos paramètres SQL Server :
```env
DB_SERVER=localhost
DB_PORT=1433
DB_DATABASE=akoho
DB_USER=sa
DB_PASSWORD=votre_mot_de_passe
```

Démarrez le serveur :
```bash
npm run dev
```

Le backend sera accessible sur **http://localhost:3000**

### Frontend

```bash
cd frontend
npm install
ng serve
```

Le frontend sera accessible sur **http://localhost:4200**

## 📡 API Endpoints

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/health` | Vérifie la santé de l'API |
| GET | `/api/health/database` | Statut de la connexion BDD |
| POST | `/api/health/database/connect` | Connecter à la BDD |
| POST | `/api/health/database/disconnect` | Déconnecter de la BDD |

## 🛠️ Technologies utilisées

### Backend
- Express.js - Framework web
- mssql - Driver SQL Server
- dotenv - Gestion des variables d'environnement
- cors - Gestion CORS

### Frontend
- Angular 17 - Framework frontend
- RxJS - Programmation réactive
- Standalone Components - Architecture moderne Angular

## 📝 Auteur

Projet créé le 10 mars 2026
