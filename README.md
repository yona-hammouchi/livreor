# Projet : Livre d'Or - Naissance d'un Petit Garçon

## Description

Site web permettant aux utilisateurs de laisser des commentaires dans un livre d’or sur le thème de la naissance d’un petit garçon.

Technologies
PHP : Backend pour la gestion des pages et des sessions.
MySQL : Base de données.
HTML/CSS : Structure et design des pages.

Sécurité et Sessions
Mots de passe sécurisés avec password_hash().
Sessions pour gérer l’authentification.
Requêtes SQL préparées pour éviter les injections SQL.

### Fonctionnalités principales :
- **Page d'accueil** : Présentation du site avec une galerie photo et un bouton d'inscription.
- **Inscription et Connexion** : Formulaires pour créer un compte et se connecter.
- **Profil utilisateur** : Modification du login et mot de passe.
- **Page de déconnexion** : Permet à l'utilisateur de se déconnecter de son profil.
- **Livre d’or** : Affichage des commentaires avec pagination et recherche de mots-clés.
- **Ajout de commentaire** : Accessible uniquement aux utilisateurs connectés.
- **Gestion des commentaires** : Ajouter, modifier, supprimer ses propres commentaires.
- **Admin** : Gestion des utilisateurs et des commentaires.

---

## Structure des Pages
- **index.php** : Page d’accueil avec présentation et galerie photo.
- **inscription.php** : Formulaire d'inscription.
- **connexion.php** : Formulaire de connexion.
- **deconnexion.php** : Sysèteme de déconnexion.
- **profil.php** : Modification du profil (login, mot de passe).
- **livre-or.php** : Affichage des commentaires et pagination.
- **commentaire.php** : Ajout de commentaire, uniquement pour les utilisateurs connectés.
- **admin.php** : Gestion des utilisateurs et des commentaires pour l’administrateur.

---

## Base de données
### Tables :
- **users** : `id`, `login`, `password`, `role`, `created_at`.
- **comments** : `id`, `user_id`, `comment`, `created_at`.

### Requête SQL (vérification rôle) :
```sql
SELECT * FROM users WHERE login = :login AND password = :password;
