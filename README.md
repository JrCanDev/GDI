# GDI
## Introduction
by **kinator**

This project is a Postgresql database management and visualisation website for the professors of the IUTLCO. It can :

- Choose which year is being displayed
- Show services by professor or by ressource
- Display various statistics in 'raw', 'by group' and 'PN vs Calais'
- Offers a 'Maquette' page to display the classes in the selected year by 'week numbers', or show the number of hours in a week or the total in a semester ordered by 'ressource and seance-type'
- Show the professors' informations to the users with 'part-time professor' privileges
- Enable the admin users to modify, delete and insert values into the database

## Initialise the website
First download and install *PHP Composer* [HERE](https://getcomposer.org/download/)

Then, in the project's source directory, to install all the dependencies :

```bash
composer install
```
Will install all dependecies into a new folder ``vendor``.

Transfer the project into you web-hosting solution, and the website is online.

## Setup the Postgresql database
Install Postgresql and import the tables and views from ``bdd.sql``. Then, create a .env and set the environment variables present in ``/lib/pdo.php``. This is to enable the connection between the website and the database.