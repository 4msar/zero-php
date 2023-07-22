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

### Available Commands

Here is the list of available methods.

-   `php zero serve` - To run the application
-   `php zero make:controller <ControllerName>` - To create a new controller
-   `php zero make:model <ModelName>` - To create a new model
-   `php zero make:view <name>` - To create a new view file (you can pass the folder name with name like `folder.name` or `folder/name`)

### Available Functions

Here is the list of available functions.

-   `env('ENV_KEY')` - To get the value of a ENV key
-   `app('classPath')` - To get the instance of a class
-   `view('viewName', $data)` - To load a view file
-   `response('data', 'status')` - To send a response
-   `auth()` - To get the instance of Auth class
-   `request($key)` - To get the instance of Request class or get the value of a request key
-   `input()` - To get the instance of Redirect class
-   `flash('key', 'value')` - To set a flash message
-   `flash('key')` - To get a flash message
-   `isJson($data)` - To check a data is json or not
-   `redirectTo('url')` - To redirect to a url
-   `url($path)` - To get the full url of a path
-   `assets($path)` - To get the full url of a assets path
-   `dd($data)` - To dump and die a data
-   `request_method($method)` - To check the request method

### Available Classes

Here is the list of available classes.

-   `Auth` - To handle the authentication
-   `Base` - A stdClass to handle the data
-   `Controller` - To handle the controller
-   `ConnectDB` - To handle the database
-   `Collection` - To handle the collection of data
-   `Input` - To handle the input data
-   `Manager` - To handle the manager
-   `Model` - To handle the model
-   `Request` - To handle the request
-   `Response` - To handle the response
-   `Router` - To handle the router
-   `Session` - To handle the session
-   `View` - To handle the view
-   `Validation` - To handle the validation

### Structures

Users - Table - id, name, email, password

## License

MIT
