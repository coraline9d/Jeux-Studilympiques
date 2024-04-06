# Bienvenue sur le site des Jeux Studilympiques

Les Jeux Studilympiques sont les Jeux Olympiques de l’école Studi, ayant pour site web : https://jeuxstudilympiques.store

## Spécifications Techniques

**Technologie**

- PHP >= 8
- Composer >= 2
- Framework : Symfony
- Base de données : mySql 5.7

**Front**

- HTML5 (Twig)
- SCSS
- Bootstrap
- Javascript

**Back**

- Minimum PHP 8.0
- Symfony 8.2
- mySql 5.7

## Installation Locale

Pour installer le projet en local, suivez les étapes ci-dessous :

1. Clonez le dépôt :

```bash
git clone git@github.com:coraline9d/Jeux-Studilympiques.git
```

2. Installez les dépendances PHP avec Composer :

```bash
composer install
```

3. Installez les dépendances Node.js avec npm :

```bash
npm install
```

4. La base de données se trouve dans le fichier `.env.local`, vous n’avez donc pas besoin de la créer.

5. Pour configurer le serveur SMTP, remplissez vos identifiants et mot de passe (par exemple ceux de Mailtrap) dans le fichier `.env.local` .

6. Utilisant Webpack Encore, il faut construire les assets avec npm :

```bash
npm run build
```

7. Installez les assets d’EasyAdmin :

```bash
php bin/console assets:install --symlink public
```

8. Démarrez le serveur local avec Symfony CLI :

```bash
symfony serve -d
```

9. Pour déployer le site complètement en local, vous aurez besoin d’un serveur local tel que MAMP, XAMPP ou WAMP. Démarrez ce serveur pour accéder au site sur votre machine.
