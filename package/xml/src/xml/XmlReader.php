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
 * {@link \xml\XmlReader}
 * 
 * @package     xml
 * @name        XmlReader
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlReader implements \node\Reader {
    /**///<editor-fold desc="Fields">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Public Accessors">
    /*\**********************************************************************\*/
    /*\                             Public Accessors                         \*/
    /*\**********************************************************************\*/
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
    public function read($node, $xml, $offset=0) {
        $matches = array();

        while (preg_match('/\</', $xml, $matches, PREG_OFFSET_CAPTURE, $offset)) {
            list($start, $offset) = $matches[0];
            
            $offset += strlen($matches[0][0]);
            
            if (!preg_match('/[^\>]*/', $xml, $matches, PREG_OFFSET_CAPTURE, $offset)) {
                throw new Exception;
            }
            
            list($middle, $offset) = $matches[0];
            
            $offset += strlen($matches[0][0]);
            
            if (!preg_match('/\>/', $xml, $matches, PREG_OFFSET_CAPTURE, $offset)) {
                throw new Exception;
            }
            
            list($end, $offset) = $matches[0];
            
            $offset += strlen($matches[0][0]);

            $values = explode(" ", trim($middle), 2);
            
            $tag = trim(array_shift($values));
            
            $attributes = trim(array_shift($values));
            
            if (preg_match('/^\s*\//', $middle, $matches)) {
                break;
            }
            
            $child = $node->resolve(array(
                'interface' => 'node\Element',
                'tag' => $tag,
                'attributes' => $attributes,
            ))->instance($node->getDocument(), $tag, $attributes);
            
            $node->addChild($child);
            
            if (preg_match('/\/\s*$/', $middle, $matches)) {
                continue;
            }
            
            if (preg_match('/^\s*\?/', $middle, $matches)) {
                continue;
            }
            
            $offset = $child->read($xml, $offset);
        }
                
        return $offset;
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
