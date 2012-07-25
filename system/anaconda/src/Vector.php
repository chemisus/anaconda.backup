<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
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
