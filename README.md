# AltoDispatcher

AltoDispatcher is a simple and easy-to-use class for dispatching after AltoRouter routing.

## Usage

```php
<?php

// require Composer as usual
require __DIR__ . '/../vendor/autoload.php';

// Instanciate AltoRouter
$router = new AltoRouter();

// Map your routes
$router->map( 'GET', '/', 'MainController::home', 'home' ); // MainController::home => AltoDispatcher will instanciate "MainController" and call its "home" method
$router->map( 'GET', '/other-page', 'MainController::otherPage', 'home' ); // MainController::otherPage => AltoDispatcher will instanciate "MainController" and call its "otherPage" method
// [...]

// If a route matches current URL
if ($match) {
    // You can optionnally add a try/catch here to handle Exceptions
    // Instanciate the dispatcher
    $dispatcher = new Dispatcher($match);
    // then run the dispatch method which will call the mapped method
    $dispatcher->dispatch();
}
else {
    // 404
    header('HTTP/1.0 404 Not Found');
    echo '404'; // display your own 404 page (calling a method of a controller, for example ErrorController::404)
}
```

## License

MIT License

Copyright (c) 2019 Benjamin CORDIER <benjamin@oclock.io>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
