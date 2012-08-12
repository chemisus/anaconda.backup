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

namespace node;

/**
 * {@link \node\Element}
 * 
 * @package     node
 * @name        Element
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Element implements Elementable, Blockable {
    /**///<editor-fold desc="Fields">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    /**
     *
     * @var string
     */
    private $tag;
    
    /**
     *
     * @var Documentable
     */
    private $document;
    
    /**
     *
     * @var array
     */
    private $attributes = array();

    /**
     *
     * @var \CompositeContainer
     */
    private $children;
    /**///</editor-fold>

    /**///<editor-fold desc="Public Accessors">
    /*\**********************************************************************\*/
    /*\                             Public Accessors                         \*/
    /*\**********************************************************************\*/
    public function getAttributes() {
        return $this->attributes;
    }
    
    public function getDocument() {
        return $this->document;
    }

    public function getTag() {
        return $this->tag;
    }
    
    public function getChildren() {
        return $this->children->getChildren();
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
    protected function setAttributes($value) {
        $this->attributes = $value;
    }

    protected function setDocument($value) {
        $this->document = $value;
    }
    
    protected function setTag($value) {
        $this->tag = $value;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct(
            $document=null,
            $tag=null,
            $attributes=array()
    ) {
        $this->setAttributes($attributes);
        
        $this->setTag($tag);
        
        $this->setDocument($document);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function getValue() {
    }

    public function addChild($child) {
        return $this->children->addChild($child);
    }

    public function removeChild($child) {
        return $this->children->removeChild($child);
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
    protected function addCompositeInterface($value) {
        return $this->children->addCompositeInterface($value);
    }

    protected function removeCompositeInterface($value) {
        return $this->children->removeCompositeInterface($value);
    }
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
