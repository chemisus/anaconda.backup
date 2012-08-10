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
 * {@link \ElementTemplate}
 * 
 * @package     
 * @name        ElementTemplate
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class ElementTemplate extends CompositeTemplate implements Node, Element {
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

    private $tag;
    
    private $attributes = array();
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function getDocument() {
        return $this->document;
    }

    public function getTag() {
        return $this->tag;
    }
    
    protected function setTag($value) {
        $this->tag = $value;
    }

    public function getAttributes() {
        return $this->attributes;
    }
    
    protected function setAttributes($value) {
        $this->attributes = $value;
    }
    
    protected function getAttribute($key) {
        return $this->attributes[$key];
    }
    
    protected function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function getValue() {
        $value = '';
        
        foreach ($this->getChildren() as $child) {
            $value .= $child->getValue();
        }
        
        return $value;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct($tag=null, $attributes=array()) {
        parent::__construct();
        
        $this->setTag($tag);
        
        $this->setAttributes($attributes);
        
        $this->addCompositeInterface('Node');
        
        $this->addDecorationInterface('Node');
        
        $this->addDecorationInterface('Element');
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
        xmp(__METHOD__);

        parent::doReset();
    }

    protected function doPrepare(\Publisher $publisher) {
        xmp(__METHOD__);

        parent::doPrepare($publisher);
    }

    protected function doCheck(\Publisher $publisher) {
        xmp(__METHOD__);

        parent::doCheck($publisher);
    }

    protected function doPublish(\Publisher $publisher) {
        xmp(__METHOD__);

        parent::doPublish($publisher);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function toXml($level=0) {
        $pad = str_pad('', $level * 4, ' ');
        
        $xml = "\n{$pad}<{$this->getTag()}";
        
        foreach ($this->getAttributes() as $key=>$value) {
            $xml .= " {$key}=\"{$value}\"";
        }
        
        if (!count($this->getChildren())) {
            $xml .= ' />';
        }
        else {
            $xml .= '>';
            
            $value = '';
            
            foreach ($this->getChildren() as $child) {
                $value .= $child->toXml($level + 1);
            }
            
            $xml .= "{$value}</ {$this->getTag()}>";
        }
        
        return $xml;
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
