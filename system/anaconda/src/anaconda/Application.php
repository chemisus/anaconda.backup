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

namespace anaconda;

/**
 * {@link \anaconda\Application}
 * 
 * @package     anaconda
 * @name        Application
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Application implements \Application, \Resolvable {
    /**///<editor-fold desc="Fields">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    private $factory;
    
    private $page;
    
    private $request;
    
    private $response;
    
    private $router;
    
    private $subscribers;
    
    private $session;
    
    private $controllers = array();
    
    private $configuration;
    /**///</editor-fold>

    /**///<editor-fold desc="Public Accessors">
    /*\**********************************************************************\*/
    /*\                             Public Accessors                         \*/
    /*\**********************************************************************\*/
    public function getControllers() {
        return $this->controllers;
    }
    
    public function getPage() {
        return $this->page;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getRouter() {
        return $this->router;
    }
    
    public function getSession() {
        return $this->session;
    }
    
    public function getConfiguration() {
        return $this->configuration;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Mutators">
    /*\**********************************************************************\*/
    /*\                             Public Mutators                          \*/
    /*\**********************************************************************\*/
    public function setControllers($value) {
        $this->controllers = $value;
    }

    public function setPage($value) {
        $this->page = $value;
    }

    public function setRequest($value) {
        $this->request = $value;
    }

    public function setResponse($value) {
        $this->response = $value;
    }

    public function setRouter($value) {
        $this->router = $value;
    }

    public function setSession($value) {
        $this->session = $value;
    }
    
    public function setConfiguration($value) {
        $this->configuration = $value;
    }
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
    public function __construct() {
        $this->subscribers = new SubscriberContainer();
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function addFactory($value) {
        $value->setNextFactory($this->factory);

        $this->factory = $value;
    }
    
    public function removeFactory($value) {
        throw new Exception("Not yet implemented.");
    }
    
    final public function run() {
        $this->setup();
        
        $this->configurations();
        
        $this->execute();
        
        $this->flush();
    }

    public function subscribe($value) {
        return $this->subscribers->addSubscriber($value);
    }

    public function unsubscribe($value) {
        return $this->subscribers->removeSubscriber($value);
    }
    
    public function publish($publisher) {
        $publisher->publish($this->subscribers->getSubscribers());
        
        return $publisher;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Private Methods">
    /*\**********************************************************************\*/
    /*\                             Private Methods                          \*/
    /*\**********************************************************************\*/
    private function setup() {
        if ($this->getSession() == null) {
            $this->setSession($this->resolve('Session')->instance($this));
        }
        
        if ($this->getConfiguration() == null) {
            $this->setConfiguration($this->resolve('Configuration')->instance($this));
        }
        
        if ($this->getResponse() == null) {
            $this->setResponse($this->resolve('Response')->instance($this));
        }
        
        if ($this->getRequest() == null) {
            $this->setRequest($this->resolve('Request')->instance($this));
        }
        
        if ($this->getRouter() == null) {
            $this->setRouter($this->resolve('Router')->instance($this));
        }
        
        if ($this->getPage() == null) {
            $this->setPage($this->getRouter()->route());
        }
    }
    
    private function configurations() {
        foreach (glob(MOD."config.xml", GLOB_BRACE) as $filename) {
            $this->getConfiguration()->load($filename);
        }
    }
    
    private function execute() {
    }
    
    private function flush() {
//        echo $this->getPage()->render();
    }

    public function resolve($tag) {
        return $this->factory->resolve($tag);
    }
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
