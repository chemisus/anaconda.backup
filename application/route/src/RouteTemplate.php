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
 * {@link anaconda\Route}
 * 
 * @package     anaconda
 * @name        Route
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class RouteTemplate extends \SubscriberTemplate implements Route {
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
    private $defaults;
    
    private $values;
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function controller() {
        return $this->values['controller'];
    }
    
    public function method() {
        return $this->values['methods'];
    }
    
    public function parameters() {
        return $this->values['parameters'];
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct($controller, $method) {
        $this->defaults = array(
            'controller' => $controller,
            'method' => $method,
            'parameters' => array(),
        );
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
    protected function doReset() {
        $this->values = $this->defaults;
    }
    
    protected function doPrepare(\Publisher $publisher) {
        foreach ($this->values['parameters'] as $key=>$value) {
            if (is_string($value)) {
                $this->values['controller'] = str_replace("[{$key}]", $value, $this->values['controller']);

                $this->values['method'] = str_replace("[{$key}]", $value, $this->values['method']);
            }
        }

        $this->values['controller'] = '\\'.strtr(trim($this->values['controller'], '/'), array('/'=>'\\'));
    }
    
    protected function doCheck(\Publisher $publisher) {
        return true;
    }
    
    protected function doPublish(\Publisher $publisher) {
        $reflection = new ReflectionClass($this->values['controller']);
        
        $instance = $reflection->newInstance();
        
        $method = $reflection->getMethod($this->values['method']);
        
        $parameters = array();
        
        foreach ($method->getParameters() as $parameter) {
            $key = $parameter->getName();
            
            $value = isset($this->values['parameters'][$key]) ? $this->values['parameters'][$key] : null;
            
            $parameters[$key] = $value;
        }
        
        $instance->before();

        $method->invokeArgs($instance, $parameters);
        
        $instance->after();
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function offsetExists($offset) {
        return isset($this->values['parameters'][$offset]);
    }

    public function offsetGet($offset) {
        return $this->values['parameters'][$offset];
    }

    public function offsetSet($offset, $value) {
        $this->values['parameters'][$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->values['parameters'][$offset]);
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
