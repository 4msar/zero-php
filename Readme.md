# Zero MVC

Zero Logo
![Logo](./assets/logo.jpg)

## How to run

-   At first change the DB credentials & app URL (to http://localhost:8900) in `.env` file
-   Import the demo DB `db.sql` in your DB
-   then open a terminal or command line in this project and run this command `php zero serve`.
-   before run the php insure that your php bin folder already added in ENV path.
-   or use static test domain by configure your server.
-   then open your browser and put this (`http://localhost:8900`) in url.
-   That's it.

### Login Credentials

Default Admin Email & Password

```
hello@msar.me
password
```

### Docker

If you have docker installed in your system then you can run this project by docker.

-   At first change the DB credentials & others config in `.env` file
-   then open a terminal or command line in this project and run this command `docker-compose up -d`.
-   then open your browser and put this (`http://localhost:8000`) in url.

### Composer and Other

In this application by default has a AutoLoader to load all the files and classes. If you want to use composer then you can use it.

-   At first run this command `composer install` in your terminal or command line.
-   then open your browser and put this (`http://localhost:8900`) in url.

## Documentations

I follow MVC pattern here.

-   In `views` folder have all the view files.
-   In `app/Models` folder have all the models
-   In `app/Controllers` folder have all the Controllers
-   In `app/routes.php` file contain all the routes of the App.
-   In `app/Classes` folder have all the necessary files and classes.

### Available Methods

Here is the list of available methods.

### Structures

Users - Table - id, name, email, password

## License

MIT
