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
            $route = new RouteTemplate(
                    $node->getAttribute('controller'),
                    $node->getAttribute('method'));
            
            foreach ($xpath->query('./*', $node) as $value) {
                switch ($value->tagName) {
                    case 'form':
                        $route = new RouteForm($value->getAttribute('name'), $value->getAttribute('value'), $route);
                    break;

                    case 'filter':
                        $route = new RouteFilter($value->getAttribute('name'), $value->getAttribute('value'), $route);
                    break;
                    
                    case 'default':
                        $route = new RouteDefault($value->getAttribute('name'), $value->getAttribute('value'), $route);
                    break;
                }
            }
            
            $route = new RoutePath($node->getAttribute('path'), $route);

            $route = new RouteName($route);
            
            $this->routes[] = $route;
        }
    }
    
    public function route() {
        $paths = isset($_REQUEST['route']) ? array_flatten($_REQUEST['route'], '/') : array();
        
        foreach ($paths as $path=>$value) {
            foreach ($this->routes as $route) {
                $publisher = new PublisherTemplate(array(
                    'name' => 'system.route',
                    'form' => $_REQUEST,
                    'path' => $path));
                
                $route->reset();
                
                $route->prepare($publisher);
                
                if ($route->check($publisher)) {
                    $route->publish($publisher);
                    
                    break;
                }
            }
        }
        
        $paths = array(
            trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '', '/') => null
        );

        foreach ($paths as $path=>$value) {
            foreach ($this->routes as $route) {
                $publisher = new PublisherTemplate(array(
                    'name' => 'system.route',
                    'form' => $_REQUEST,
                    'path' => $path));
                
                $route->reset();
                
                $route->prepare($publisher);
                
                if ($route->check($publisher)) {
                    $route->publish($publisher);
                    
                    break;
                }
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
