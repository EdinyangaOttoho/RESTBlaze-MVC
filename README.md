# RESTBlaze
A lightweight MVC for creating REST APIs and/or web applications with ease and speed (not so different from the regular VanillaPHP).

## Installation
For now, RESTBlaze can be downloaded by cloning this repository as is and getting to work once done. In an additional note, you need to be making use of a virtual host as provided by Laragon or equivalent in order to run the project. Laragon can be downloaded from https://laragon.net/downloads. Once downloaded, you can get started building your cool app.

## Usage
RESTBlaze, like every other PHP framework, has routes and a .env file for setting environment variables. This makes it easy to configure params globally for your app. For every change in the app's routes, you need to reconfigure the framework's engine by running the launcher.php file in the root folder of the framework either using the browser directly or by shell as shown:

```shell
php launcher.php
```
That's how simple it is to set your app up. Your app would ideally be accessed via a virtual host (as created/configured by Largon for instance) such as http://myapp.test. Yeah. Just like that. Having said all these, a walthrough of the functionality of RESTBlaze would be enumerated.

## Routing
Routing in RESTBlaze can be very easy. For now, RESTBlaze supports GET and POST requests and would be improved in future to employ more features. There are basically two approaches to routing, which involve: Parameterized and Non-parameterized routing. Routes also can be named or unnamed and they are accessed in the routes.php file in the root of the project directory. These are illustrated below;

### Parameterized Routes
A parameterized route would be one that involves a variable such as a user ID in a URL (GET or POST) which you would love to access and use in your controller such as the one shown below:
```shell
https://myapp.test/users/24903230204/profile
```
Looking at the URL shown below, you can notice the numeric value, 24903230204 which you would most likely love to use and its as easy as writing:
```php
$router::get("/users/{id}/profile", "ControllerClass@controllermethod", "profile");
```
From the snippet above, you would notice the "profile" as the third argument and the Route path (URL) declared as the first argument. The second argument represents the Controller Class you would love to use and the method in that class as well. The argument, ID can be accessed using the specified controller method, which will be discussed later in this doc.
In summary, you can write a route as thus:
```php
$router::get("/path/to/url", "ControllerClass@controllermethod", "route_name");
```
The third argument is optional and only applicable when you want to easily point redirects to a route without having to use its full path.

### Non Parameterized Routes
A non-parameterized route as the name implies, is that which has no parameter declared. such as;
```shell
https://myapp.test/user/profile
```
This also is handled using a controller, only that the controller method would not have any argument.

## Controllers
A typical controller can be created or accessed in the /controllers folder and has the following syntax structure:
```php
class ControllerName extends RESTBlaze {
    public function controllermethod(...$arguments) {
        //handled here
    }
}
```
As seen above, you need to inherit some functionality from the RESTBlaze parent class to have full features from RESTBlaze, which include but are not limited to DB, views etc.
The controller method above would typically be used to handle a request from a route in the form:
```php
$router::get("/path/to/url/{param}", "ControllerName@controllermethod");
```
Each parameter in the URL is added as an argument in order of appearance to the assigned controller method.
There's a lot to unveil from the RESTBlaze framework as would be discussed later on in this doc.

## Views
A view is more like a page (or template) that a user sees such as a HTML page having CSS, JS, image etc assets. A view can be created or accessed in the /views folder. Views can be put into folders as well.
A simple implementation of a view in a controller as the one above would be:
```php
class ControllerName extends RESTBlaze {
    public function controllermethod(...$arguments) {
        $this->view("home");
    }
}
```
Assume we have a home.php file in the /views folder.
We can also pass variables to routes using a third parameter as thus:
```php
$this->view("home", ["user"=>"Edinyanga Ottoho", "password"=>"123456"]);
```
This lets the user access the variables, user and password within the view as thus:
```php
$user_name = $user;
$user_password = $password;
```
When a view is put into a folder, say /views/dashboard/pages, it can be accessed using a dot as a directory separator, which means ideally, a view must not be named with a dot within its name. Views named in the form, page.set.php would cause fatal errors in your project. Try as much as possible to use page-set.php, page_set.php or similar. Having said this, we can point to a view in the folder, /views/profiles/page/home.php thus:
```php
$this->view("profile.page.home");
```
A view would ideally look like this:
```php
<!DOCTYPE html>
<html>
<head>
    <title>View title</title>
</head>
<body>
    <?php
        $variable = $route_variable
    ?>
</body>
</html>
```
It's as simple as that. And that would be all for views.

## Redirects
In order to make redirects on RESTBlaze, you simply use:
```php
$this->redirect("/home/profile");
```
The above applies to "hard url" or unnamed routes, but to access a named route would be as simple as:
```php
$this->redirect(":routename");
```
Take note of the colon used in the redirect. It is absolutely important.

## .env
The .env file is used to configure environment variables in a line-by-line key-value-pair such as:
```shell
MYSQLI_HOST=localhost
MYSQLI_USER=root
```
The following can be accessed using the env object from the RESTBlaze class as thus:
```php
$this->env->MYSQLI_HOST
```
This applies to the latter as well.

## Databases
Databases can be connected to and queried using RESTBlaze via the DB property. Before any query can be conducted, you must configure the MYSQLI_HOST, MYSQLI_USER, MYSQLI_USER, MYSQLI_PASSWORD, MYSQLI_DATABASE keys of the .env file to point to the appropriate database. Once done, it's as simple as using:
```php
$username = "Edinyanga";
$query = $this->DB->query("SELECT * FROM users WHERE username = '?'", [$username]);
```
To get the result, one can use the in-built mysqli_fetch_array. The above format applies to all forms of database operations. The "?" used is for parameters and the second argument of the query method is an array containing values for the ? in order of appearance. This is also known as a prepared statement. All filter/sanitization is done behind the scenes before the query is conducted.

## Migrations
This feature is still in BETA stage, but can be efficient to an extent. You use this to migrate the databases. Once the launcher.php is executed, all migrations are done, and all routes are registered.
```php
php launcher.php
```
This was shown earlier, but is done so once again for quick access.

## Foot notes
RESTBlaze configures the .htaccess file of your app to suit its functionality and it would not be recommended to edit or modify your .htaccess file as it could lead to breaks in your project.
Make sure to run launcher.php any time a route is added or removed from your app or in order for migrations to be made.

## Contributing
RESTBlaze is open to contributions. Feel free to make requests or contact me via +2349122455484 most preferably on WhatsApp. Your PRs are welcome! If any issue is discovered, please do well to open an issue as soon as possible so it gets fixed.