<?php
class Router {
    private $routes = [];
    private $dispatched = false;

    /**
     * Add a route
     */
    public function map($methods, $pattern, $target) {
        $methods = explode('|', $methods);
        $this->routes[] = [
            'methods' => $methods,
            'pattern' => $pattern,
            'target' => $target
        ];
    }

    /**
     * Match the current request against routes
     */
    public function match() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $path = substr($path, strlen($basePath));
        $path = trim($path, '/');

        foreach ($this->routes as $route) {
            // Convert route pattern to regex
            $pattern = $this->convertToRegex($route['pattern']);
            
            // Check if method matches
            if (!in_array($method, $route['methods'])) {
                continue;
            }
            
            // Check if path matches
            if (preg_match($pattern, $path, $matches)) {
                // Remove full match from matches
                array_shift($matches);
                
                // Parse target (Controller@method)
                $target = $route['target'];
                if (is_string($target) && strpos($target, '@') !== false) {
                    list($controller, $method) = explode('@', $target, 2);
                    $controllerFile = APP_PATH . 'controllers/' . $controller . '.php';
                    
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                        $controllerInstance = new $controller();
                        
                        // Call the method with parameters
                        call_user_func_array([$controllerInstance, $method], $matches);
                        $this->dispatched = true;
                        return true;
                    }
                } elseif (is_callable($target)) {
                    call_user_func_array($target, $matches);
                    $this->dispatched = true;
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Convert route pattern to regex
     */
    private function convertToRegex($pattern) {
        // Escape forward slashes
        $pattern = preg_replace('/\//', '\/', $pattern);
        
        // Convert parameters
        $pattern = preg_replace('/\[i:([a-z]+)\]/i', '(?P<$1>\\d+)', $pattern);
        $pattern = preg_replace('/\[s:([a-z]+)\]/i', '(?P<$1>[a-zA-Z0-9_-]+)', $pattern);
        $pattern = preg_replace('/\[a:([a-z]+)\]/i', '(?P<$1>.+)', $pattern);
        
        // Add start and end delimiters
        return '/^' . $pattern . '$/';
    }

    /**
     * Dispatch the router
     */
    public function dispatch() {
        $this->match();
    }

    /**
     * Check if a route was dispatched
     */
    public function isDispatched() {
        return $this->dispatched;
    }
}
