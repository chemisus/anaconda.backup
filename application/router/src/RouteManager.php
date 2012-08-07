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



/**
 * {@link \RouteManager}
 * 
 * @package     
 * @name        RouteManager
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class RouteManager {
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
    private $routes = array();
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
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function setup() {
        $config = new DOMDocument();

        $config->load(ROOT.'application/anaconda/config/routes.xml');

        $xpath = new DOMXPath($config);

        foreach ($xpath->query('/routes/route') as $node) {
            $defaults = array();

            foreach ($xpath->query('default', $node) as $default) {
                $defaults[$default->getAttribute('name')] = $default->getAttribute('value');
            }

            $parameters = array();

            foreach ($xpath->query('parameter', $node) as $parameter) {
                $parameters[$parameter->getAttribute('name')] = explode(':', $parameter->getAttribute('value'));
            }

            $this->routes[] = new Route(
                    $node->getAttribute('value'),
                    $node->getAttribute('controller'),
                    $node->getAttribute('method'),
                    $parameters,
                    $defaults);
        }
        
        xmp($this->routes);
    }
    
    public function route() {
        $paths = array_flatten(isset($_REQUEST['route']) ? $_REQUEST['route'] : array(), '/', false);

        foreach ($paths as $path) {
            foreach ($this->routes as $route) {
                if (($values = $route->route(trim($path, '/'))) === false) {
                    continue;
                }

                $controller = new $values['controller']();

                $method = new \ReflectionMethod($controller, $values['method']);

                $parameters = array();

                foreach ($method->getParameters() as $parameter) {
                    $parameters[$parameter->getName()] = isset($values['parameters'][$parameter->getName()]) ? $values['parameters'][$parameter->getName()] : null;
                }

                $controller->before();
                
                $method->invokeArgs($controller, $parameters);

                $controller->after();

                break;
            }
        }

        $paths = array();

        $paths[] = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

        foreach ($paths as $path) {
            foreach ($this->routes as $route) {
                if (($values = $route->route(trim($path, '/'))) === false) {
                    continue;
                }

                $controller = new $values['controller']();

                $method = new \ReflectionMethod($controller, $values['method']);

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
