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

class Anaconda extends ApplicationTemplate {
    public function run() {
        $this->subscribe(new RouterTemplate('^home$', new \page\Home()));
        
        $this->subscribe(new RouterTemplate('^$', new \page\Home()));

        $this->subscribe(new RouterTemplate('^cds(/<action>(/<name>))', new \page\Cds()));
        
        $this->subscribe(new RouterTemplate('^cms(/<module>(/<action>(/<id>)))', new \page\Cms()));

        $this->publish(new PublisherLimit(1, new \PublisherTemplate(array(
            'name' => 'system.ready',
            'publisher' => $this,
        ))));

        $page = $this->publish(new PublisherLimit(1, new \PublisherTemplate(array(
            'name' => 'system.route',
            'publisher' => $this,
            'path' => empty($_SERVER['PATH_INFO']) ? '' : trim($_SERVER['PATH_INFO'], '/'),
        ))));

        if (!$page->handled()) {
            $page = $this->publish(new PublisherLimit(1, new \PublisherTemplate(array(
                'name' => 'system.error',
                'publisher' => $this,
                'code' => 404
            ))));
        }
        
        if ($page->handled()) {
            $xsl = $page['page']->naked()->views();
            
            $xslt = new XSLTProcessor();
            
            $xslt->importStylesheet($xsl);
            
            echo $xslt->transformToXml($page['page']->naked()->elements());
        }
    }
}

/**
 * {@link anaconda\XmlDocument}
 * 
 * @package     anaconda
 * @name        XmlDocument
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlDocument {
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
    private $nodes = array();
    
    private $levels = array();
    
    private $roots = array();
    
    private $bounds = array();
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
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
    protected function key($node) {
        return array_search($node, $this->nodes, true);
    }
    
    protected function bounds($key) {
        return array_keys(array_intersect($this->bounds, array($key)));
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function nodes() {
        return new ArrayIterator($this->nodes);
    }
    
    public function roots() {
    }
    
    public function root($node) {
    }
    
    public function level($node) {
    }
    
    public function ancestors($node) {
    }
    
    public function parent($node) {
    }
    
    public function descendants($node) {
    }
    
    public function children($node) {
    }
    
    public function loadXmlFile($filename) {
        $matches = array();
        
        preg_match_all('/(?:\<|\>|([^\<\>]*))/', file_get_contents($filename), $matches);

        $nodes = array();
        
        $stack = array();
        
        $current = new XmlNode('');

        while (count($matches[0])) {
            $value = array_shift($matches[0]);
            
            switch ($value) {
                case '<':
                    $value = array_shift($matches[0]);
                    
                    $value = trim($value);
                    
                    if ($value[0] === '?') {
                        if (substr($value, -1) !== '?') {
                            throw new Exception;
                        }

                        $nodes[] = new XmlNode(trim($value, '?'));
                    }
                    else if ($value[0] === '/') {
                        $current = array_pop($stack);
                    } else if (substr($value, -1) === '/') {
                        $nodes[] = new XmlNode(trim($value, '/'));
                    } else {
                        $stack[] = $current;
                        
                        $current = new XmlNode(trim($value, '/'));

                        $nodes[] = $current;
                    }
                    
                    $value = array_shift($matches[0]);
                    
                    if ($value !== '>') {
                        throw new Exception;
                    }
                break;

                default:
                    $current->addValue($value);
            }
        }
        
        echo '<xmp>';
        print_r($nodes);
        echo '</xmp>';
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

/**
 * {@link anaconda\XmlNode}
 * 
 * @package     anaconda
 * @name        XmlNode
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlNode {
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
    private $tag;
    
    private $attributes = array();
    
    private $values = array();
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct($line) {
        $matches = array();
        
        preg_match('/(\S+)\s*(?:([^\s\=]+)(?:=\s*\"([^\"]*)\"))*/', $line, $matches);

        array_shift($matches);

        $this->tag = array_shift($matches);

        while (count($matches)) {
            $this->attributes[array_shift($matches)] = array_shift($matches);
        }
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

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function addValue($value) {
        $this->values[] = $value;
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




$document = new XmlDocument();

$document->loadXmlFile(ROOT."/application/anaconda/view/layout.xsl");