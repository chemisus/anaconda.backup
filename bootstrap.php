<?php
/**
 * This file is part of Anaconda.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version accepted by Anaconda Ltd. in accordance with section
 * 14 of the GNU General Public License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 * @license     http://www.gnu.org/licenses/gpl.txt
 *              GNU General Public License
 */

require_once('functions.php');

class Bootstrap {
    public static function Run() {
        define('ROOT', dirname(__FILE__).'/');
        
        spl_autoload_register(function ($class) {
            $class = strtr($class, array('\\'=>'/'));
            
            require_once(array_shift(glob(ROOT."*/*/src/{$class}.php")));
        });
    }
}

Bootstrap::Run();

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

/**
 * {@link anaconda\Router}
 * 
 * @package     anaconda
 * @name        Router
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Router extends \SubscriberTemplate {
    /**///<editor-fold desc="Constants">
    /*\**********************************************************************\*/
    /*\                             Constants                                \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Static Fields">
    /*\**********************************************************************\*/
    /*\                             Static Fields                            \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Static Methods">
    /*\**********************************************************************\*/
    /*\                             Static Methods                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Fields">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    private $pattern;
    
    private $controller;
    
    private $method;
    
    private $parameters = array();
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct($value, $controller, $method, $parameters) {
        $this->pattern = $this->pattern($value);
        
        $this->controller = $controller;
        
        $this->method = $method;
        
        $this->parameters = $parameters;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Private Methods">
    /*\**********************************************************************\*/
    /*\                             Private Methods                          \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Protected Methods">
    /*\**********************************************************************\*/
    /*\                             Protected Methods                        \*/
    /*\**********************************************************************\*/
    protected function pattern($route) {
        $matches = array();
        
        $route = strtr(trim($route, '/'), array('/'=>'\/'));
        
        preg_match_all('/\(|\)|\[|\]|[^\(\)\[\]]*/', trim($route, '/'), $matches);
            
        $stack = array();

        $current = '';
        
        while (count($matches[0])) {
            $match = array_shift($matches[0]);

            switch ($match) {
                case '(':
                    $stack[] = $current;
                    
                    $current = '';
                break;

                case ')':
                    $current = array_pop($stack)."(?:".$current.")?";
                break;

                case '[':
                    $match = array_shift($matches[0]);

                    if (!isset($this->parameters[$match])) {
                        $this->parameters[$match] = null;
                    }
                    
                    $current .= "(?P<{$match}>[A-Za-z0-9]*)";
                    
                    $match = array_shift($matches[0]);
                    
                    if ($match !== ']') {
                        throw new Exception;
                    }
                break;

                default:
                    $current .= $match;
            }
        }
        
        return "/{$current}/";
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function route($path) {
        $matches = array();

        if (!preg_match($this->pattern, $path, $matches)) {
            return false;
        }

        $parameters = array_intersect_key($matches, $this->parameters);

        foreach ($parameters as $key=>$value) {
            if (empty($value)) {
                $parameters[$key] = $this->parameters[$key];
            }
        }

        $controller = $this->controller;
        
        $method = $this->method;
        
        foreach ($parameters as $key=>$value) {
            $controller = str_replace("[{$key}]", $value, $controller);

            $method = str_replace("[{$key}]", $value, $method);
        }
        
        $controller = strtr($controller, array('/' => '\\'));
        
        $values = array(
            'controller' => $controller,
            'method' => $method,
            'parameters' => $parameters,
        );
        
        return $values;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Event Triggers">
    /*\**********************************************************************\*/
    /*\                             Event Triggers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Classes">
    /*\**********************************************************************\*/
    /*\                             Classes                                  \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}


$config = new DOMDocument();

$config->load(ROOT.'application/anaconda/config/routes.xml');

$xpath = new DOMXPath($config);

$routes = array();

foreach ($xpath->query('/routes/route') as $node) {
    $parameters = array();

    foreach ($xpath->query('parameter', $node) as $parameter) {
        $parameters[$parameter->getAttribute('name')] = $parameter->getAttribute('value');
    }

    $routes[] = new Router(
            $node->getAttribute('value'),
            $node->getAttribute('controller'),
            $node->getAttribute('method'),
            $parameters);
}

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

$paths = array_flatten(isset($_REQUEST['route']) ? $_REQUEST['route'] : array(), '/', false);

$paths[] = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

foreach ($paths as $path) {
    foreach ($routes as $route) {
        if (($values = $route->route($path)) === false) {
            continue;
        }
        
        xmp($values);

        $controller = new $values['controller']();
        
        $method = new ReflectionMethod($controller, $values['method']);
        
        $parameters = array();
        
        foreach ($method->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = null;
        }
        
        $parameters = array_merge($parameters, $values['parameters']);
        
        $controller->before();
        
        $method->invokeArgs($controller, $parameters);

        $controller->after();

        $controller->render();
        
        break;
    }
}

