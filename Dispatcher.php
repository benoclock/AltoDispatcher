<?php

class Dispatcher {
    /**
     * @var string[]
     */
    private $params = [];

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $method;

    /**
     * Constructor method
     *
     * @param mixed $match Array returned by AltoRouter::match()
     * @param string|array $fourOFourAction A valid target for the 404 action
     */
    public function __construct($match, $fourOFourAction) {
        // if no route were matched, trigger the 404 action by parsing it so it is called by a later dispatch()
        if (!$match) {
            header('HTTP/1.0 404 Not Found');
            $this->parseTarget($fourOFourAction);
            return;
        }

        // Getting DISPATCH infos provided by AltoRouter
        $this->parseTarget($match['target']);
        
        // Getting URL params (dynamic parts in routes' URL pattern)
        $this->params = $match['params'];
    }

    /**
     * Parses the target into valid controller and method properties
     *
     * @param string|array $target
     * @return void
     */
    public function parseTarget($target) {
        // Getting controller's name and method's name
        // if it's an array
        if (is_array($target)) {
            if (!empty($target['controller']) && !empty($target['method'])) {
                $this->controller = $target['controller'];
                $this->method = $target['method'];
            }
            else {
                throw new \Exception('Target (array) of current route is incorrect');
            }
        }
        // if it's a string containing controller and method
        else if (is_string($target)) {
            // Controller#method or Controller::method or Controller@method
            $availableSeparators = ['#', '::', '@'];
            $separatorFound = false;
            
            foreach ($availableSeparators as $currentSeparator) {
                if (strpos($target, $currentSeparator) !== false) {
                    $separatorFound = true;
                    $explodedInfos = explode($currentSeparator, $target);
                    $this->controller = $explodedInfos[0];
                    $this->method = $explodedInfos[1];

                    break;
                }
            }

            if (!$separatorFound) {
                throw new \Exception('Target (string) of current route is incorrect');
            }
        }
        else {
            throw new \Exception('Target of current route has incorrect type');
        }
    }

    /**
     * Dispatches matched route
     *
     * @return void
     */
    public function dispatch() {
        if (!empty($this->controller) && !empty($this->method)) {
            // controller instanciation
            $controller = new $this->controller();
            // method call with arguments unpacking
            $controller->{$this->method}(...array_values($this->params));
        }
        else {
            throw new \Exception('Cannot dispatch : controller or method is empty');
        }
    }
}