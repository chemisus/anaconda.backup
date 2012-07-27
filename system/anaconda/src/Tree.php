<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */



/**
 * {@link \Tree}
 * 
 * @package     
 * @name        Tree
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
interface Tree extends Countable {
    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    function getRoots();
    
    function has($node);
    
    function getRoot($node);
    
    function getAncestors($node);
    
    function getParent($node);
    
    function hasParent($node);
    
    function getDescendants($node);
    
    function getChildren($node);

    function getHeight($node);
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    function add($node);
    
    function move($parent, $node);
    
    function orphan($node);
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}
