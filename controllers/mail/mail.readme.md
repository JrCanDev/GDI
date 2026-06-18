# Configuration de l'Envoi d'E-mails (SMTP) - Guide de Déploiement

Ce module permet d'envoyer des e-mails au format HTML (par exemple pour la réinitialisation de mot de passe) en s'appuyant sur **PHPMailer** et un relais **SMTP**. 

---

## 1. SMTP & Relais

Le protocole **SMTP (Simple Mail Transfer Protocol)** régit l'envoi de courriers électroniques. Plutôt que d'utiliser la fonction native PHP `mail()` (qui utilise le démon de messagerie local du serveur, souvent non configuré, bloqué par les hébergeurs ou directement classé comme spam par les destinataires), ce projet se connecte directement à un serveur de messagerie externe (comme Gmail, Outlook, Brevo, SendGrid, etc.) via des identifiants sécurisés.

---

## 2. Variables d'Environnement (`.env`)

La configuration s'effectue dans le fichier `.env` à la racine du projet GDI :

```env
# Configuration SMTP
SMTP_HOST='smtp.gmail.com'                # Serveur SMTP de votre fournisseur (ici google)
SMTP_AUTH=true                            # Activer l'authentification (true/false)
SMTP_USERNAME='mail@gmail.com'            # Identifiant / Adresse e-mail de connexion
SMTP_PASSWORD='password'                  # Mot de passe standard ou mot de passe d'application
SMTP_SECURE='tls'                         # Type de chiffrement : 'tls' ou 'ssl'
SMTP_PORT=587                             # Port SMTP (587 pour TLS / 465 pour SSL)

# Variables de Test
MAIL_TEST='destinataire-test@example.com' # E-mail de test pour l'admin (si l'admin n'a pas de mail, on utilise celui-ci)
```

---

## 3. Configuration avec Google 

Si vous choisissez d'utiliser un compte Google comme relais SMTP, des mesures de sécurité spécifiques s'appliquent : il faut utiliser un "mot de passe d'application". 

### Procédure pour obtenir un "Mot de passe d'application" :
1. Connectez-vous à la console de gestion de votre compte Google : [Mon compte Google](https://myaccount.google.com/).
2. Dans le menu de gauche, cliquez sur **Sécurité**.
3. Assurez-vous que la **Validation en deux étapes (2FA)** est bien **activée** sur votre compte (elle est requise pour générer des mots de passe d'application).
4. Allez dans la section **Validation en deux étapes** (ou recherchez directement *"Mots de passe d'application"* dans la barre de recherche en haut).
5. Faites défiler tout en bas de la page et cliquez sur **Mots de passe d'application**.
6. Saisissez un nom pour identifier l'application (ex: `GDI System Production`).
7. Cliquez sur **Créer**. Google génère alors un mot de passe unique à **16 caractères** 
8. Copiez ce code de 16 caractères et collez-le dans votre fichier `.env` comme valeur pour `SMTP_PASSWORD`.

### Configuration standard Google dans le `.env` :
```env
SMTP_HOST='smtp.gmail.com'
SMTP_AUTH=true
SMTP_USERNAME='votre_adresse@gmail.com'
SMTP_PASSWORD='le_code_a_16_caracteres'
SMTP_SECURE='tls'
SMTP_PORT=587
```
