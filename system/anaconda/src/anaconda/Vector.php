<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */

namespace anaconda;

/**
 * {@link \Vector} allows the developer to work with arrays with a little bit
 * more ease.
 * 
 * Advantages of using Vector over a normal PHP array:
 *  - Append, prepend, insert whole arrays and keep their keys.
 *  - Easier to read when daisy chaining array commands.
 * 
 * @package     
 * @name        Vector
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Vector implements \Vector {
    /**///<editor-fold desc="Constants">
    /*\**********************************************************************\*/
    /*\                             Constants                                \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Static Fields">
    /*\**********************************************************************\*/
    /*\                             Static Fields                            \*/
    /*\**********************************************************************\*/
    public static function ToArray($value) {
        if ($value instanceof \Vector) {
            return $value->items();
        } else if (is_object($value)) {
            return array($value);
        } else if (is_null($value)) {
            return array();
        }
        
        return (array)$value;
    }

    public static function ToVector($value) {
        if ($value instanceof \Vector) {
            return $value;
        } else if (is_object($value)) {
            return new self(array($value));
        } else if (is_null($value)) {
            return new self();
        }
        
        return new self((array)$value);
    }
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
    private $max = 0;
    
    private $deep = true;
    
    private $items = array();
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function items() {
        return $this->items;
    }
    
    public function setItems($value) {
        $this->items = $value;
    }
    
    public function keys() {
        return array_keys($this->items);
    }
    
    public function values() {
        return array_values($this->items);
    }
    
    public function set($key, $value) {
        $this->items[$this->key($key)] = $value;
        
        return $this;
    }
    
    public function get($key) {
        return $this->items[$key];
    }
    
    public function getOffsetOfKey($key) {
        return array_search($key, $this->keys(), true);
    }
    
    public function getKey($value) {
        return array_search($value, $this->items, true);
    }
    
    public function getOffsetOf($value) {
        return array_search($value, $this->values(), true);
    }
    
    public function getKeyAtOffset($offset) {
        $keys = $this->keys();
        
        return $keys[$offset];
    }
    
    public function getValueAtOffset($offset) {
        $values = $this->values();
        
        return $values[$offset];
    }
    
    public function has($key) {
        return isset($this->items[$key]);
    }
    
    public function hasValue($value) {
        return $this->getKey($value) !== false;
    }

    public function count() {
        return count($this->items);
    }

    public function getIterator() {
        return new \ArrayIterator($this->items);
    }

    public function offsetExists($key) {
        return $this->has($key);
    }

    public function offsetGet($key) {
        $value = &$this->items[$key];
        
        if ($value === null && $this->deep) {
            $value = array();
        }
        
        if (is_array($value)) {
            $vector = new self();
            
            $vector->items = &$value;
            
            return $vector;
        }
        
        return $value;
    }

    public function offsetSet($key, $value) {
        return $this->set($key, $value);
    }

    public function offsetUnset($key) {
        return $this->removeKey($key);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct($array=array()) {
        $this->items = $array;
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
    protected function key($key, $reassign=false) {
        if (is_integer($key)) {
            $this->max = max(array($this->max, $key + 1));

            if ($reassign) {
                $key = $this->max++;
            }
        }
        
        if ($key === null || $key === '') {
            $key = $this->max++;
        }
        
        return $key;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function slice($offset, $length=null, $preserve=false) {
        return new self(array_slice($this->items, $offset, $length, $preserve));
    }
    
    public function range($left, $right, $preserve=false) {
        return $this->slice($left, $right - $left, $preserve);
    }
    
    public function splice($offset, $length=null, $replace=array()) {
        $replace = self::ToArray($replace);
        
        $vector = new self(array_slice($this->items, $offset, $length));

        $first = array_slice($this->items, 0, $offset, true);
        
        $last = array_slice($this->items, $offset + $length, null, true);
        
        if (count($replace)) {
            $keys = array_keys($replace);
            
            foreach ($keys as &$key) {
                $key = $this->key($key, true);

                if (isset($first[$key])) {
                    unset($first[$key]);
                }
                else if (isset($last[$key])) {
                    unset($last[$key]);
                }
            }

            $replace = array_combine($keys, $replace);
        }

        $this->items = $first + $replace + $last;
        
        return $vector;
    }
    
    public function append($key, $value) {
        $this->appendAll(array($key=>$value));
        
        return $this;
    }
    
    public function prepend($key, $value) {
        $this->prependAll(array($key=>$value));
        
        return $this;
    }
    
    public function insert($key, $value, $offset=0) {
        $this->insertAll(array($key=>$value), $offset);
        
        return $this;
    }
    
    public function appendAll($array) {
        return $this->insertAll($array, count($this));
    }
    
    public function prependAll($array) {
        return $this->insertAll($array, 0);
    }
    
    public function insertAll($array, $offset=0) {
        $this->splice($offset, 0, $array);
        
        return $this;
    }
    
    public function appendTo(&$array) {
        return $this->insertInto($array, count($array));
    }
    
    public function prependTo(&$array) {
        return $this->insertInto($array, 0);
    }
    
    public function insertInto(&$array, $offset=0) {
        $vector = $array;

        if (is_array($array)) {
            $vector = new self();

            $vector->items = &$array;
        }

        $vector->splice($offset, 0, $this->items);
        
        return $this;
    }

    public function remove($value) {
        return $this->removeKey($this->getKey($value));
    }
    
    public function removeKey($key) {
        $value = $this->get($key);

        unset($this->items[$key]);
        
        return $value;
    }
    
    public function removeOffset($offset) {
        return $this->removeKey($this->getKeyAtOffset($offset));
    }
    
    public function shift() {
        return array_shift($this->items);
    }
    
    public function pop() {
        return array_pop($this->items);
    }
    
    public function intersect($array) {
        $array = self::ToArray($array);
        
        $vector = new self();
        
        foreach ($this->items as $key=>$value) {
            if (array_search($value, $array, true) !== false) {
                $vector[$key] = $value;
            }
        }
        
        return $vector;
    }
    
    public function intersectKey($array) {
        $array = self::ToArray($array);
        
        return new self(array_intersect_key($this->items, $array));
    }
    
    public function select($keys) {
        $keys = self::ToArray($keys);

        $values = new self();
        
        foreach ($keys as $key) {
            if ($this->has($key)) {
                $values[$key] = $this->get($key);
            }
        }
        
        return $values;
    }
    
    public function unique() {
        return new self(array_unique($this->items));
    }
    
    public function flip() {
        return new self(array_flip($this->items));
    }
    
    public function equalTo($array) {
        return $this->subsetOf($array) && self::ToVector($array)->subsetOf($this);
    }
    
    public function subsetOf($array) {
        $array = self::ToVector($array);
        
        if (count($this) > count($array)) {
            return false;
        }
        
        foreach ($this->items as $key=>$value) {
            if (!$array->has($key)) {
                return false;
            }

            if ($value instanceof \Vector || is_array($value)) {
                $value = self::ToVector($value);
                
                if (!$value->subsetOf($array[$key])) {
                    return false;
                }
            }
            else {
                if ($value !== $array[$key]) {
                    return false;
                }
            }
        }
        
        return true;
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
