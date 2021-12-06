<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        // Start Session
        session_start();
        $url = $this->parseURL();
        // If URL is empty
        if ($url === NULL) {
            $url[0] = $this->controller;
            $url[1] = $this->method;
        }
        // Check if Controller exists
        if ( file_exists('controller/' . $url[0] . '.php') ) {
            $this->controller = $url[0];
        } else {
            // If want redirect to errors if not found the controller
            $this->controller = 'Defaults';
            $url[1] = 'not_found';
        }

        unset($url[0]);
        require_once 'controller/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Method
        if ( isset($url[1]) ) {
            if ( method_exists($this->controller, $url[1]) ) {
                $this->method = $url[1];
            } else {
                $this->method = 'index';
            }
        } else {
            $this->method = 'index';
        }
        
        unset($url[1]);

        // Parameters
        if ( !empty($url) ) {
            $this->params = array_values($url);
        }
        // Run Controller and Method with Params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    /**
     * Parse URL to Sanitize Input and Correctly get the Route
     */
    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
/**
 * Get Absolute Path, prevent path traversal attack
 * 
 * Source: https://www.php.net/manual/en/function.realpath.php#84012
 * @param string $path
 * @return string
 */
function get_absolute_path($path) {
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode(DIRECTORY_SEPARATOR, $absolutes);
}
/**
 * Get Asset from Public Folder like CSS or JS
 * @param string $location
 * @return string
 */
function asset($path) {
    if (gettype($path) === 'string') {
        $location = get_absolute_path($path);
        $url = BASEURL . '/public' . '/' . $location;
        return $url;
    } else {
        throw new Error('Paramater should be string');
    }
}
/**
 * Create User Session
 * 
 * If session is already available, it return false.
 * @param string $user
 * @return bool
 */
function create_session($user) {
    if ( !isset($_SESSION['user']) ) {
        $_SESSION['user'] = $user;
        $_SESSION['remote_address'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_access'] = time();
        return true;
    } else {
        return false;
    }
}
/**
 * Unset and Destroy User Session
 * 
 * If session is not available, it return false.
 * @return bool
 */
function destroy_session() {
    if ( isset($_SESSION['user']) ) {
        session_unset();
        session_destroy();
        return true;
    } else {
        return false;
    }
}
/**
 * Check User Session
 * 
 * @return bool
 */
function cekSession() {
    if ( !isset($_SESSION['user']) ) {
        header('Location: http://localhost/Ecommercehandal/Login'); 
    }
    else if ($_SESSION['remote_address'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        destroy_session();
        header('Location: http://localhost/Ecommercehandal/Login');
    }
    else if ($_SESSION['last_access'] - time() > 3600) {
        destroy_session();
        header('Location: http://localhost/Ecommercehandal/Login');
    }
}