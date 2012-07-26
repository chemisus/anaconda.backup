<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */



/**
 * {@link \Autoloader}
 * 
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
interface Autoloader {
    /**///<editor-fold desc="Constants">
    /*\**********************************************************************\*/
    /*\                             Constants                                \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Static Fields and Methods">
    /*\**********************************************************************\*/
    /*\                             Static Fields                            \*/
    /*\**********************************************************************\*/


    /*\**********************************************************************\*/
    /*\                             Static Methods                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    /**
     *
     */
    public function register();

    /**
     *
     * @param string $file
     * @param array $extract
     */
    public function req($file, array $extract=array());
    
    /**
     *
     * @param string $file
     * @param array $extract
     */
    public function req_once($file, array $extract=array());
    
    /**
     *
     * @param string $file
     * @param array $extract
     */
    public function inc($file, array $extract=array());
    
    /**
     *
     * @param string $file
     * @param array $extract
     */
    public function inc_once($file, array $extract=array());

    /**
     *
     * @param string $file
     * @param integer $count
     * @param boolean $throw
     * @return Arr
     */
    public function find($file, $count=0, $throw=false);
    
    /**
     *
     * @param string $file
     * @param boolean $throw
     * @return string
     */
    public function findOne($file, $throw=false);
    
    public function add($a, $b=null, $c=null);
    
    public function addTranslate($from, $to);
    
    public function removeTranslate($from);
    
    public function addExtension($extension);
    
    public function extensions();
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
