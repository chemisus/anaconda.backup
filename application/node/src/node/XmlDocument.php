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
 * {@link node\XmlDocument}
 * 
 * @package     node
 * @name        XmlDocument
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlDocument implements Document {
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
    private $children = array();
    
    private $factory;
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function children() {
        return $this->children;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct(Factory $factory=null) {
        $this->factory = $factory;
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
    public function appendNode(Node $node) {
        $this->children[] = $node;
    }

    public function prependNode(Node $node) {
        throw new Exception;
    }
    
    public function toXml() {
        $xml = '';
        
        foreach ($this->children() as $child) {
            $xml .= $child->toXml();
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
                    $current->appendNode(new XmlDeclaration($line));

                    if (array_shift($matches[0]) !== '>') {
                        throw new Exception;
                    }
                }
                else if (left($line, '--')) {
                    while (!right($line, '-->')) {
                        $line .= array_shift($matches[0]);
                    }
                    
                    $current->appendNode(new XmlText('<'.$line));
                }
                else if (right($line, '/')) {
                    list($tag, $attributes) = $this->parseLine($line);
                    
                    $current->appendNode($this->factory->newNode($tag, $attributes));

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
                    
                    $node = $this->factory->newNode($tag, $attributes);
                    
                    $current->appendNode($node);
                    
                    $current = $node;

                    if (array_shift($matches[0]) !== '>') {
                        throw new Exception;
                    }
                }
                
            }
            else {
                $current->appendNode(new XmlText($line));
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
