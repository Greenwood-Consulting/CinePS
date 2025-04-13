# Client CinePS 📽️🍿

**Client PHP CinePS, permettant d'interagir avec un serveur CinePS-API.** 🔗🌐

Projet CinePS-API : https://github.com/Greenwood-Consulting/CinePS-API

---

## Présentation 🎞️🎟️

Le projet **CinePS** est une application destinée à un groupe de cinéphiles permettant à ses membres de proposer chaque semaine des films à visionner et de voter pour choisir le film de la semaine qui sera visionné.  
Ce dépôt correspond au **client PHP** qui consomme l'API fournie par un serveur CinePS-API.

---

## Technologies utilisées 🛠️

- PHP version 8.2.0
- HTML/CSS
- JavaScript (accessoirement, pour le compte à rebours notamment)
- CURL pour les appels API

---

## Prérequis 📋

Pour faire fonctionner correctement le client CinePS, vous aurez besoin :

- PHP installé (version 8.2.0)
- Serveur web (Apache recommandé car seul serveur sur lequel le client CinePS a été testé) 
- Extensions PHP nécessaires : `curl`, `json`

---

## Installation

1. Clonez ce dépôt GitHub :

```bash
git clone https://github.com/<votre-compte>/CinePS.git
```

2. Placez les fichiers dans votre dossier de publication web (ex. `/var/www/html/CinePS`).

3. Vérifiez les droits d'accès aux fichiers pour que votre serveur web puisse y accéder.

---

## Configuration

### Configuration des variables d'environnement 🌐

Le fichier `config/env.php` contient les variables nécessaires pour configurer le client CinePS. Voici la liste des variables à définir :

- **`API_URL`** : L'URL de base de l'API CinePS-API (par exemple, `http://localhost:8000`).  
    *Utilisée pour effectuer les appels API vers le serveur CinePS-API.*

- **`API_MAIL`** : L'adresse e-mail utilisée pour s'authentifier auprès de l'API.  
    *Permet d'identifier l'utilisateur ou le client lors des appels API.*

- **`API_PASSWORD`** : Le mot de passe associé à l'adresse e-mail pour l'authentification.  
    *Assure la sécurité des échanges avec l'API.*

- **`FIN_PERIODE_VOTE`** : L'heure de fin de la période de vote (par exemple, `Fri 18:00`).  
    *Détermine le moment où les votes pour le film de la semaine sont clôturés.*

- **`VIDEOS_YOUTUBE`** : Une liste d'identifiants de vidéos YouTube (par exemple, `['D5ssxpM_k5M', 'SVUdV4yxylU']`).  
    *Représente les vidéos disponibles pour consultation via la page 'à propos'.*

Pour configurer ces variables, éditez le fichier `config/env.php` et remplacez les valeurs par celles correspondant à votre environnement.

---

## Utilisation

*(A compléter)*
Accédez au client CinePS via votre navigateur à l'URL configurée sur votre serveur web (par exemple `http://localhost/CinePS`).

---

## Structure du projet 🗃️

*(A compléter)*

---

