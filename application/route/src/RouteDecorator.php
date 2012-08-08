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
 * {@link \RouteDecorator}
 * 
 * @package     
 * @name        RouteDecorator
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class RouteDecorator extends SubscriberDecorator implements Route {
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
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function controller() {
        return $this->subscriber()->controller();
    }
    
    public function method() {
        return $this->subscriber()->method();
    }
    
    public function parameters() {
        return $this->subscriber()->parameters();
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct(\Route $route) {
        parent::__construct($route);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Private Methods">
    /*\**********************************************************************\*/
    /*\                             Private Methods                          \*/
    /*\**********************************************************************\*/
    protected function doCheck(\Publisher $publisher) {
        return true;
    }
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
    public function offsetExists($offset) {
        return $this->subscriber()->offsetExists($offset);
    }

    public function offsetGet($offset) {
        return $this->subscriber()->offsetGet($offset);
    }

    public function offsetSet($offset, $value) {
        return $this->subscriber()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset) {
        return $this->subscriber()->offsetUnset($offset);
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
