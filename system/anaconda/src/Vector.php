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
 * {@link \Vector}
 * 
 * @package     
 * @name        Vector
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
interface Vector extends Countable, ArrayAccess, IteratorAggregate {
    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    function items();
    
    function keys();
    
    function values();
    
    function set($key, $value);
    
    function get($key);
    
    function getOffsetOfKey($key);
    
    function getKey($value);
    
    function getOffsetOf($value);
    
    function getKeyAtOffset($offset);
    
    function getValueAtOffset($offset);
    
    function has($key);
    
    function hasValue($value);
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    function slice($offset, $length=null, $preserve=false);
    
    function range($left, $right, $preserve=false);
    
    function splice($offset, $length=null, $replace=array());
    
    function append($key, $value);
    
    function prepend($key, $value);
    
    function insert($key, $value, $offset=0);
    
    function appendAll($array);
    
    function prependAll($array);
    
    function insertAll($array, $offset=0);
    
    function appendTo(&$array);
    
    function prependTo(&$array);
    
    function insertInto(&$array, $offset=0);

    function remove($value);
    
    function removeKey($key);
    
    function removeOffset($offset);
    
    function shift();
    
    function pop();
    
    function intersect($array);
    
    function intersectKey($array);
    
    function select($keys);
    
    function unique();
    
    function flip();
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}
