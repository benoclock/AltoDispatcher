### Adding roles to routes

```php
<?php

// require Composer as usual
require __DIR__ . '/../vendor/autoload.php';

// Instanciate AltoRouter
$router = new AltoRouter();

// Map your routes
// MainController::home => AltoDispatcher will instanciate "MainController" and call its 
$router->map(
    'GET',
    '/',
    [
        'controller' => 'MainController',
        'method' => 'home',
        // Set an array of roles for that route (can be set to null)
        'role' => ['admin', 'catalog-manager']
    ],
    'main-home'
);
// Can be set like this too
$router->map('GET', '/other-page', 'MainController::otherPage::['admin', 'user']', 'other-page');

$match = $router->match();

// You can optionnally add a try/catch here to handle Exceptions
// Instanciate the dispatcher, give it the $match variable and a fallback action
$dispatcher = new Dispatcher($match, 'ErrorController::err404');
// Setup controllers argument(s)
$dispatcher->setControllersArguments($router, 'foo', 3, $match); // To get match informations (ex: route name, roles, etc..)
// then run the dispatch method which will call the mapped method
$dispatcher->dispatch();


// In the CoreController _construct to handle roles
```

```php
<?php

namespace App\Controllers;
use App\Models\User;


class CoreController
{

    protected $match;
    protected $routeName;
    protected $routeRole;


    public function __construct($match)
    {

        $this->match        = $match;
        $this->routeName    = $match['name'];
        $this->routeRole    = $match['target']['role'];

        // Access Control List
        $acl = [
            $this->routeName => $this->routeRole
        ];
   
        // If current route have roles we send it to $this->checkAuthorisation() method
        if ($this->routeRole != null) {
            // Get the role list
            $autorizedRole = $acl[$this->routeName];
            $this->checkAuthorisation($autorizedRole);
        }
    }
    
    /**
     * Check if user can access the requested url
     *
     * @param array $requiredRole
     * @return boolean
     */
    protected function checkAuthorisation($requiredRole = []): bool
    {

        // Check if you have an user instance
        // Get your current user

        // If User have required role, return true
        foreach ($requiredRole as $role) {
            if ($role === $userRole) {
                return true;
            }
        };

        // Handle here if user is not authorized
    }
}

```
