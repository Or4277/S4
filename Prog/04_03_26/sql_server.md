# Connexion à Docker et SQL Server

## 1. Lister les conteneurs Docker

```bash
docker ps
```

> Cette commande te montre tous les conteneurs actifs et leurs noms ou IDs.

---

## 2. Se connecter au conteneur Docker

Supposons que ton conteneur s’appelle `sqlserver` :

```bash
sudo docker exec -it sqlserver /bin/bash
```

> Tu passes alors en mode terminal à l’intérieur du conteneur.

---

## 3. Installer `sqlcmd` (si non installé)

Si `sqlcmd` n’est pas présent dans le conteneur :

```bash
# Mettre à jour apt
sudo apt-get update

# Installer curl et gnupg
sudo apt-get install -y curl gnupg

# Ajouter le dépôt Microsoft
curl https://packages.microsoft.com/keys/microsoft.asc | sudo apt-key add -
curl https://packages.microsoft.com/config/ubuntu/22.04/prod.list | sudo tee /etc/apt/sources.list.d/mssql-release.list

# Installer les outils SQL Server
sudo apt-get update
sudo ACCEPT_EULA=Y apt-get install -y mssql-tools unixodbc-dev

# Ajouter sqlcmd au PATH
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc
```

---

## 4. Se connecter à SQL Server avec `sqlcmd`

```bash
/opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "Oxnobobo2213"
```

> `-S localhost` : serveur SQL
> `-U sa` : utilisateur SQL Server
> `-P "Oxnobobo2213"` : mot de passe

---

## 5. Commandes utiles SQL

* Voir les bases de données :

```sql
SELECT name FROM sys.databases;
GO
```

* Créer une nouvelle base de données :

```sql
CREATE DATABASE MaBase;
GO
```

* Quitter `sqlcmd` :

```sql
EXIT
```
