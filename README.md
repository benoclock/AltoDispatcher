# AltoDispatcher

AltoDispatcher is a simple and easy-to-use class for dispatching after AltoRouter routing.

## Usage

### Simple

```php
<?php

// require Composer as usual
require __DIR__ . '/../vendor/autoload.php';

// Instanciate AltoRouter
$router = new AltoRouter();

// Map your routes
$router->map('GET', '/', 'MainController::home', 'home'); // MainController::home => AltoDispatcher will instanciate "MainController" and call its "home" method
$router->map('GET', '/other-page', 'MainController::otherPage', 'other-page'); // MainController::otherPage => AltoDispatcher will instanciate "MainController" and call its "otherPage" method
// [...]
$match = $router->match();

// You can optionnally add a try/catch here to handle Exceptions
// Instanciate the dispatcher, give it the $match variable and a fallback action
$dispatcher = new Dispatcher($match, 'ErrorController::err404');
// then run the dispatch method which will call the mapped method
$dispatcher->dispatch();
```

### With Controllers' namespace

```php
<?php

// require Composer as usual
require __DIR__ . '/../vendor/autoload.php';

// Instanciate AltoRouter
$router = new AltoRouter();

// Map your routes
$router->map('GET', '/', 'MainController::home', 'home'); // MainController::home => AltoDispatcher will instanciate "MainController" and call its "home" method
$router->map('GET', '/other-page', 'MainController::otherPage', 'other-page'); // MainController::otherPage => AltoDispatcher will instanciate "MainController" and call its "otherPage" method
// [...]
$match = $router->match();

// You can optionnally add a try/catch here to handle Exceptions
// Instanciate the dispatcher, give it the $match variable and a fallback action
$dispatcher = new Dispatcher($match, 'ErrorController::err404');
// Setup Controllers' namespace
$dispatcher->setControllersNamespace('App\Controllers');
// then run the dispatch method which will call the mapped method
$dispatcher->dispatch();
```

### With Argument to Controllers' constructor

```php
<?php

// require Composer as usual
require __DIR__ . '/../vendor/autoload.php';

// Instanciate AltoRouter
$router = new AltoRouter();

// Map your routes
$router->map('GET', '/', 'MainController::home', 'home'); // MainController::home => AltoDispatcher will instanciate "MainController" and call its "home" method
$router->map('GET', '/other-page', 'MainController::otherPage', 'other-page'); // MainController::otherPage => AltoDispatcher will instanciate "MainController" and call its "otherPage" method
// [...]
$match = $router->match();

// You can optionnally add a try/catch here to handle Exceptions
// Instanciate the dispatcher, give it the $match variable and a fallback action
$dispatcher = new Dispatcher($match, 'ErrorController::err404');
// Setup controllers argument(s)
$dispatcher->setControllersArguments($router, 'foo', 3);
// then run the dispatch method which will call the mapped method
$dispatcher->dispatch();
```

## License

MIT License

Copyright (c) 2019 Benjamin CORDIER <benjamin@oclock.io>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
