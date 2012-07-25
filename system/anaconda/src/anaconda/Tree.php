<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */

namespace anaconda;

/**
 * {@link \Tree}
 * 
 * @package     
 * @name        Tree
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class Tree implements \Tree {
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
    /** @var \Vector $nodes */
    public $nodes;
    
    /** @var \Vector $roots */
    public $roots;
    
    /** @var \Vector $heights */
    public $heights;
    
    /** @var \Vector $bounds */
    public $bounds;
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function has($node) {
        return $this->nodes->hasValue($node);
    }
    
    public function getRoot($node) {
        return $this->nodes->get($this->roots->get($this->key($node)));
    }
    
    public function getAncestors($node) {
        $bounds = $this->bounds($node);

        return $this->nodes->select(
                $this->bounds->slice($bounds[1] + 1)->appendAll(
                        $this->bounds->slice(0, $bounds[0])->unique()
                )
        );
    }
    
    public function getParent($node) {
        return $this->getAncestors($node)->shift();
    }
    
    public function hasParent($node) {
        return $this->getParent($node) !== null;
    }
    
    public function getDescendants($node) {
        $bounds = $this->bounds($node);
        
        return $this->nodes->intersectKey(
                $this->bounds->range($bounds[0] + 1, $bounds[1])
                ->unique()->flip()
        );
    }
    
    public function getChildren($node) {
        return $this->getDescendants($node)->intersectKey(
                    $this->heights->intersect(
                            $this->getHeight($node) + 1));
    }
    
    public function getHeight($node) {
        return $this->heights->get($this->key($node));
    }
    
    public function count() {
        return $this->nodes->count();
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct($nodes, $roots, $heights, $bounds) {
        $this->nodes = $nodes;
        
        $this->roots = $roots;
        
        $this->heights = $heights;
        
        $this->bounds = $bounds;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Private Methods">
    /*\**********************************************************************\*/
    /*\                             Private Methods                          \*/
    /*\**********************************************************************\*/
    public function key($node) {
        return $this->nodes->getKey($node);
    }
    
    private function bounds($node) {
        return $this->bounds->intersect($this->key($node))->keys();
    }
    
    private function moved($node, $parent) {
        $root = $this->key($this->getRoot($parent));
        
        $base = $this->heights->get($this->key($parent)) + 1;
        
        $this->roots->set($this->key($node), $root);
        
        $this->heights->set($this->key($node), $base);
        
        foreach ($this->getDescendants($node) as $value) {
            $this->roots->set($this->key($value), $root);

            $height = $this->getHeight($value);
            
            $this->heights->set($this->key($value), $height + $base);
        }
    }
    
    private function orphaned($node) {
        $root = $this->key($node);
        
        $base = $this->heights->get($this->key($node));
        
        $this->roots->set($this->key($node), $root);
        
        $this->heights->set($this->key($node), 0);
        
        foreach ($this->getDescendants($node) as $value) {
            $this->roots->set($this->key($value), $root);

            $height = $this->getHeight($value);
            
            $this->heights->set($this->key($value), $height - $base);
        }
    }
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
    public function add($node) {
        if ($this->has($node)) {
            return;
        }

        $this->nodes[] = $node;
        
        $key = $this->nodes->getKey($node);

        $this->roots[$key] = $key;

        $this->heights[$key] = 0;

        $this->bounds[] = $key;

        $this->bounds[] = $key;
    }
    
    public function move($node, $parent) {
        if (!$this->has($parent)) {
            return;
        }
        
        if (!$this->has($node)) {
            $this->add($node);
        }
        
        if ($this->hasParent($node)) {
            $this->orphan($node);
        }

        $bounds = $this->bounds($node);
        
        $nodes = $this->bounds->splice($bounds[0], $bounds[1] - $bounds[0] + 1);
        
        $this->bounds->setItems($this->bounds->values());
        
        $bounds = $this->bounds($parent);
        
        $nodes->insertInto($this->bounds, $bounds[1]);
        
        $this->bounds->setItems($this->bounds->values());

        $this->moved($node, $parent);
    }
    
    public function orphan($node) {
        if (!$this->hasParent($node)) {
            return;
        }
        
        $bounds = $this->bounds($node);
        
        $nodes = $this->bounds->splice($bounds[0], $bounds[1] - $bounds[0] + 1);

        $this->bounds->setItems($this->bounds->values());
        
        $this->bounds->appendAll($nodes->items());
        
        $this->bounds->setItems($this->bounds->values());

        $this->orphaned($node);
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
