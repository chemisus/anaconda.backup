<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */

namespace anaconda;

/**
 * {@link \anaconda\Autoloader}
 * 
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Autoloader implements \Autoloader {
    /**///<editor-fold desc="Constants">
    /*\**********************************************************************\*/
    /*\                             Constants                                \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Static Fields and Methods">
    /*\**********************************************************************\*/
    /*\                             Static Fields                            \*/
    /*\**********************************************************************\*/
    /**
     *
     * @var \anaconda\ArrList
     */
    private static $Autoloaders;

    /*\**********************************************************************\*/
    /*\                             Static Methods                           \*/
    /*\**********************************************************************\*/
    /**
     *
     * @param string $key
     * @return \Autoloader
     */
    public static function Factory($key) {
        if (self::$Autoloaders == null) {
            self::$Autoloaders = array();
        }
        
        if (!isset(self::$Autoloaders[$key])) {
            self::$Autoloaders[$key] = new self();
        }
        
        return self::$Autoloaders[$key];
    }
    
    /**
     *
     * @param string $key
     * @return \Autoloader
     */
    public static function Get($key) {
        $autoloader = self::$Autoloaders[$key];
        
        return $autoloader;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Fields and Properties">
    /*\**********************************************************************\*/
    /*\                             Fields                                   \*/
    /*\**********************************************************************\*/
    /**
     *
     * @var array
     */
    private $patterns = array();

    /**
     *
     * @var array
     */
    private $translate = array();

    /**
     *
     * @var array
     */
    private $extensions = array();
    
    private $case = array(
        'a' => '[aA]',
        'b' => '[bB]',
        'c' => '[cC]',
        'd' => '[dD]',
        'e' => '[eE]',
        'f' => '[fF]',
        'g' => '[gG]',
        'h' => '[hH]',
        'i' => '[iI]',
        'j' => '[jJ]',
        'k' => '[kK]',
        'l' => '[lL]',
        'm' => '[mM]',
        'n' => '[nN]',
        'o' => '[oO]',
        'p' => '[pP]',
        'q' => '[qQ]',
        'r' => '[rR]',
        's' => '[sS]',
        't' => '[tT]',
        'u' => '[uU]',
        'v' => '[vV]',
        'w' => '[wW]',
        'x' => '[xX]',
        'y' => '[yY]',
        'z' => '[zZ]',
        'A' => '[aA]',
        'B' => '[bB]',
        'C' => '[cC]',
        'D' => '[dD]',
        'E' => '[eE]',
        'F' => '[fF]',
        'G' => '[gG]',
        'H' => '[hH]',
        'I' => '[iI]',
        'J' => '[jJ]',
        'K' => '[kK]',
        'L' => '[lL]',
        'M' => '[mM]',
        'N' => '[nN]',
        'O' => '[oO]',
        'P' => '[pP]',
        'Q' => '[qQ]',
        'R' => '[rR]',
        'S' => '[sS]',
        'T' => '[tT]',
        'U' => '[uU]',
        'V' => '[vV]',
        'W' => '[wW]',
        'X' => '[xX]',
        'Y' => '[yY]',
        'Z' => '[zZ]',
    );

    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
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
    public function register() {
        spl_autoload_register(array($this, 'autoload'));
        
        return $this;
    }

    public function autoload($class) {
        $class = $class;
        
        $class = strtr($class, $this->translate);
      
        $this->req_once($class);
    }

    public function req($file, array $extract=array()) {
        {
            extract($extract);

            require($this->findOne($file, true));
        }
    }
    
    public function req_once($file, array $extract=array()) {
        {
            extract($extract);

            require_once($this->findOne($file, true));
        }
    }
    
    public function inc($file, array $extract=array()) {
        {
            extract($extract);

            include($this->findOne($file));
        }
    }
    
    public function inc_once($file, array $extract=array()) {
        {
            extract($extract);

            include_once($this->findOne($file));
        }
    }
    
    public function find($file, $count=0, $throw=false) {
        if (!$file) {
            throw new Exception("File is empty.");
        }

        $files = array();

        foreach ($this->patterns as $pattern) {
            $pattern = '/'.trim($pattern, '/').'/'.strtr(trim($file, '/'), array_merge($this->case, $this->translate)).$this->extensions();

            $glob = glob($pattern, GLOB_BRACE | GLOB_NOSORT);
            
            $files = array_merge($files, $glob);
            
            if ($count > 0 && count($files) >= $count) {
                $files = array_slice($files, 0, $count);
                
                break;
            }
        }

        if (!count($files) && $throw) {
            throw new \Exception("Could not find file '{$file}'");
        }
        
        $array = array();
        
        foreach ($files as $file) {
            $array[] = $file;
        }
        
        return $array;
    }
    
    public function findOne($file, $throw=true) {
        return array_shift($this->find($file, 1, $throw));
    }
    
    public function add($a, $b=null, $c=null) {
        $array = func_get_args();
        
        $patterns = array();
        
        $next = (array)array_shift($array);
        
        foreach ($next as $value) {
            $patterns[] = $value;
        }
        
        while (count($array)) {
            $new = array();
            
            $next = (array)array_shift($array);
            
            foreach ($patterns as $pattern) {
                foreach ($next as $value) {
                    $new[] = '/'.trim($pattern, '/').'/'.$value;
                }
            }
            
            $patterns = $new;
        }

        foreach ($patterns as $pattern) {
            $this->patterns[] = $pattern;
        }
        
        return $this;
    }
    
    public function addTranslate($from, $to) {
        $this->translate[(string)$from] = (string)$to;
        
        return $this;
    }
    
    public function removeTranslate($from) {
        $this->translate->remove($from);
        
        return $this;
    }
    
    public function addExtension($extension) {
        $this->extensions[$extension] = $extension;
        
        return $this;
    }
    
    public function extensions() {
        return '{'.implode(', ', $this->extensions).'}';
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
