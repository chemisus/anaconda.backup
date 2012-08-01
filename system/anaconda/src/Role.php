<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */



/**
 * {@link \Role}
 * 
 * @package     
 * @name        Role
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
interface Role {
    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**
     * @return string
     */
    function key();
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    /**
     * @return array 
     */
    function check( array $permissions,
                    \Operation $operation,
                    \Subject $subject);
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}
