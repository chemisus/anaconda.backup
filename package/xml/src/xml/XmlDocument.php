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

namespace xml;

/**
 * {@link \xml\XmlDocument}
 * 
 * @package     xml
 * @name        XmlDocument
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlDocument extends XmlNode implements \node\Document, \SubscriberComposite {
    /**///<editor-fold desc="Fields">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    private $reader;
    
    private $writer;
    
    private $application;
    
    private $factory;
    
    private $children;
    /**///</editor-fold>

    /**///<editor-fold desc="Public Accessors">
    /*\**********************************************************************\*/
    /*\                             Public Accessors                         \*/
    /*\**********************************************************************\*/
    public function getSubscribers() {
        return $this->children;
    }
    
    public function getDocument() {
        return $this;
    }
    
    public function getApplication() {
        return $this->application;
    }
    
    public function getValue() {
        return $this->getRoot()->getValue();
    }
    
    public function getReader() {
        return $this->reader;
    }

    public function getWriter() {
        return $this->writer;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Mutators">
    /*\**********************************************************************\*/
    /*\                             Public Mutators                          \*/
    /*\**********************************************************************\*/
    public function setReader($value) {
        $this->reader = $value;
    }
    
    public function setWriter($value) {
        $this->writer = $value;
    }
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
    public function __construct($application=null, $reader=null, $writer=null) {
        $this->application = $application;
        
        $this->factory = new XmlFactory('');
        
        $this->addFactory($application->getFactory());
        
        $this->setReader($reader);
        
        $this->setWriter($writer);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function addFactory($value) {
        $value->setNextFactory($this->factory);

        $this->factory = $value;
    }
    
    public function removeFactory($value) {
        throw new Exception("Not yet implemented.");
    }
    
    public function addChild($value) {
        $this->children[] = $value;
    }
    
    public function resolve($tag) {
        return $this->factory->resolve($tag);
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
