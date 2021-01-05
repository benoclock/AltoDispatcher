<?php

class Dispatcher
{
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
    private $controllersNamespace;

    /**
     * @var mixed
     */
    private $controllersArguments;

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
    public function __construct($match, $fourOFourAction)
    {
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
    public function parseTarget($target)
    {
        // Getting controller's name and method's name
        // if it's an array
        if (is_array($target)) {
            if (!empty($target['controller']) && !empty($target['method'])) {
                $this->controller = $target['controller'];
                $this->method = $target['method'];
            } else {
                throw new \Exception('Target (array) of current route is incorrect');
            }
        } elseif (is_string($target)) {
            // if it's a string containing controller and method
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
        } else {
            throw new \Exception('Target of current route has incorrect type');
        }
    }

    /**
     * Dispatches matched route
     *
     * @return void
     */
    public function dispatch()
    {
        if (!empty($this->controller) && !empty($this->method)) {
            // get Controller FQCN
            $controllerName = $this->controller;
            // If namespace is defined
            if (!empty($this->controllersNamespace)) {
                // If controller does not contain namespace
                if (strpos($this->controller, $this->controllersNamespace) === false) {
                    // then, add its namespace
                    $controllerName = str_replace('\\\\', '\\', $this->controllersNamespace . '\\' . $this->controller);
                }
            }
            // controller instanciation
            // if an argument to this constructor is set
            if (!empty($this->controllersArguments)) {
                // If it's an array
                if (is_array($this->controllersArguments)) {
                    // Then, each element will be an argument
                    $controller = new $controllerName(...array_values($this->controllersArguments));
                } else {
                    // Else, we add only this argument
                    $controller = new $controllerName($this->controllersArguments);
                }
            } else {
                $controller = new $controllerName();
            }
            // method call with arguments unpacking
            $controller->{$this->method}(...array_values($this->params));
        } else {
            throw new \Exception('Cannot dispatch : controller or method is empty');
        }
    }

    /**
     * Set the value of controllersNamespace property
     *
     * @param string $controllersNamespace
     */
    public function setControllersNamespace(string $controllersNamespace)
    {
        $this->controllersNamespace = $controllersNamespace;
    }

    /**
     * Set the value of controllersArguments
     *
     * @param mixed $controllersArguments
     */
    public function setControllersArguments(...$controllersArguments)
    {
        $this->controllersArguments = $controllersArguments;
    }
}
