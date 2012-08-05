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

namespace model;

/**
 * {@link model\ModuleManager}
 * 
 * @package     model
 * @name        ModuleManager
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class ModuleManager {
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
    private $document;
    
    private $xpath;
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
    public function loadXml($file) {
        $this->document = new \DOMDocument();
        
        $this->document->load($file);

        $this->xpath = new \DOMXPath($this->document);
    }

    public function saveXml($file) {
        $this->document->save($file);
    }
    
    public function all() {
        return $this->document->documentElement;
    }
    
    public function addModule($module) {
        $modules = $this->xpath->query("/modules");
        
        $node = $this->document->createElement('module');
        
        $node->setAttribute('name', $module);
        
        $modules->appendNode($node);
    }
    
    public function removeModule($module) {
        $modules = $this->xpath->query("/modules");
        
        $modules->removeChild($this->xpath->query("/modules/module[@name='{$module}']"));
    }
    
    public function newField($module, $field) {
        $module = $this->xpath->query("/modules/module[@name='{$module}']");

        $node = $this->document->createElement('field');
        
        $node->setAttribute('name', $field);
        
        $module->appendNode($node);
    }
    
    public function removeField($module, $field) {
        $node = $this->xpath->query("/modules/module[@name='{$module}']");
        
        $node->removeChild($this->xpath->query("/modules/module[@name='{$module}']/field[[@name='{$field}']"));
    }
    
    public function addDecorator($module, $decorator) {
        $module = $this->xpath->query("/modules/module[@name='{$module}']");

        $node = $this->document->createElement('decorator');
        
        $node->setAttribute('name', $decorator);
        
        $module->appendNode($node);
    }
    
    public function addFieldDecorator($module, $field, $decorator) {
        $field = $this->xpath->query("/modules/module[@name='{$module}']/field[[@name='{$field}']");

        $node = $this->document->createElement('decorator');
        
        $node->setAttribute('name', $decorator);
        
        $field->appendNode($node);
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
