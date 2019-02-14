<?php

class Dispatcher {
    /**
     * @var string[]
     */
    private $params;

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
     * @param array $match Array returned by AltoRouter::match()
     */
    public function __construct($match) {
        // Getting DISPATCH infos provided by AltoRouter
        $dispatchInfos = $match['target'];
        
        // Getting URL params (dynamic parts in routes' URL pattern)
        $this->params = $match['params'];
        
        // Getting controller's name and method's name
        // if it's an array
        if (is_array($dispatchInfos)) {
            if (!empty($dispatchInfos['controller']) && !empty($dispatchInfos['method'])) {
                // Getting
                $this->controller = $dispatchInfos['controller'];
                $this->method = $dispatchInfos['method'];
            }
            else {
                throw new \Exception('Target (array) of current route is incorrect');
            }
        }
        // if it's a string containing controller and method
        else if (is_string($dispatchInfos)) {
            // Controller#method or Controller::method or Controller@method
            $availableSeparators = ['#', '::', '@'];
            $separatorFound = false;
            
            foreach ($availableSeparators as $currentSeparator) {
                if (strpos($dispatchInfos, $currentSeparator) !== false) {
                    $separatorFound = true;
                    $explodedInfos = explode($currentSeparator, $dispatchInfos);
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
     * Dispatch matched route
     *
     * @return void
     */
    public function dispatch() {
        if (!empty($this->controller) && !empty($this->method)) {
            // controller instanciation
            $controller = new $this->controller();
            // method call
            $controller->{$this->method}($this->params);
        }
        else {
            throw new \Exception('Cannot dispatch : controller or method is empty');
        }
    }
}