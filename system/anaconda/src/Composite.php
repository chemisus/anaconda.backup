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
 * {@link \Composite}
 * 
 * @package     
 * @name        Composite
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Composite implements CompositeContainer {
    /**///<editor-fold desc="Fields">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    private $children = array();
    
    private $interfaces = array();
    /**///</editor-fold>

    /**///<editor-fold desc="Public Accessors">
    /*\**********************************************************************\*/
    /*\                             Public Accessors                         \*/
    /*\**********************************************************************\*/
    public function getChildren() {
        return new ArrayIterator($this->children);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Mutators">
    /*\**********************************************************************\*/
    /*\                             Public Mutators                          \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Protected Mutators">
    /*\**********************************************************************\*/
    /*\                             Protected Mutators                       \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function addChild($value) {
        foreach ($this->interfaces as $interface) {
            if (!($value instanceof $interface)) {
                throw new Exception("Child must implement {$interface}.");
            }
        }
        
        $this->children[] = $value;
    }

    public function removeChild($value) {
        $key = array_search($value, $this->children, true);
        
        if ($key !== false) {
            unset($this->children[$key]);
        }
    }

    public function addCompositeInterface($value) {
        $this->interfaces[] = $value;
    }

    public function removeCompositeInterface($value) {
        $key = array_search($value, $this->interfaces, true);
        
        if ($key !== false) {
            unset($this->interfaces[$key]);
        }
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
    /**///</editor-fold>

    /**///<editor-fold desc="Unused Sections" defaultstate="collapsed">
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

    /**///<editor-fold desc="Public Properties">
    /*\**********************************************************************\*/
    /*\                             Public Properties                        \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Private Properties">
    /*\**********************************************************************\*/
    /*\                             Private Properties                       \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Protected Properties">
    /*\**********************************************************************\*/
    /*\                             Protected Properties                     \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Private Accessors">
    /*\**********************************************************************\*/
    /*\                             Private Accessors                        \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Protected Accessors">
    /*\**********************************************************************\*/
    /*\                             Protected Accessors                      \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Private Mutators">
    /*\**********************************************************************\*/
    /*\                             Private Mutators                         \*/
    /*\**********************************************************************\*/
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

    /**///<editor-fold desc="Classes">
    /*\**********************************************************************\*/
    /*\                             Interfaces                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
    /**///</editor-fold>
}
