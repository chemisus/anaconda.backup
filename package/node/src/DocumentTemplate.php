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
 * {@link \DocumentTemplate}
 * 
 * @package     
 * @name        DocumentTemplate
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class DocumentTemplate extends CompositeTemplate implements Document, Node {
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
    public function getDocument() {
        return $this;
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
    public function __construct(\Application $application=null) {
        parent::__construct($application);
        
        $this->addCompositeInterface('Node');
        
        $this->addDecorationInterface('Node');
        
        $this->addDecorationInterface('Document');
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

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function createNode($tag, $attributes=array(), $interfaces=array()) {
        $value = $this->getApplication()->resolve($tag, $attributes, $interfaces, $this);
        
        $this->addChild($value);
        
        return $value;
    }
    
    public function toXml($level=0) {
        $xml = '';
        
        foreach ($this->getChildren() as $child) {
            $xml .= $child->toXml($level);
        }
        
        return $xml;
    }
    
    public function fromXml($xml) {
        $matches = array();

        preg_match_all('/\<|\>|[^\<\>]*/', $xml, $matches);
        
        $stack = array();
        
        $current = $this;
        
        while (count($matches[0])) {
            $line = array_shift($matches[0]);
            
            if ($line === '<') {
                $line = array_shift($matches[0]);

                if (left($line, '?') || right($line, '?')) {
                    $current->addChild(new TextTemplate('<'.$line.'>'));

                    if (array_shift($matches[0]) !== '>') {
                        throw new Exception;
                    }
                }
                else if (left($line, '--')) {
                    while (!right($line, '-->')) {
                        $line .= array_shift($matches[0]);
                    }
                    
                    $current->addChild(new XmlText('<'.$line));
                }
                else if (right($line, '/')) {
                    list($tag, $attributes) = $this->parseLine($line);
                    
                    $current->addChild($this->getApplication()->resolve($this, $tag, $attributes));

                    if (array_shift($matches[0]) !== '>') {
                        throw new Exception;
                    }
                }
                else if (left($line, '/')) {
                    $current = array_pop($stack);

                    if (array_shift($matches[0]) !== '>') {
                        throw new Exception;
                    }
                }
                else {
                    $stack[] = $current;

                    list($tag, $attributes) = $this->parseLine($line);
                    
                    $node = $this->getApplication()->resolve($this, $tag, $attributes);
                    
                    $current->addChild($node);
                    
                    $current = $node;

                    if (array_shift($matches[0]) !== '>') {
                        throw new Exception;
                    }
                }
            }
            else {
                $current->addChild(new TextTemplate($line));
            }
        }
    }
    
    public function parseLine($line) {
        list($tag, $attributes) = explode(' ', $line.' ', 2);
        
        $matches = array();

        preg_match_all('/(?:\"[^\"]*\")|\w+/', $attributes, $matches);
        
        $attributes = array();
        
        while (count($matches[0])) {
            $attributes[array_shift($matches[0])] = trim(array_shift($matches[0]), '"');
        }

        return array($tag, $attributes);
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
