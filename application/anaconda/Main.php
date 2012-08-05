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
    
    private $factory;
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
    public function __construct(\XmlFactory $factory) {
        $this->factory = $factory;
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
    protected function key($node) {
        return array_search($node, $this->nodes, true);
    }
    
    protected function bounds($node) {
        return array_keys(array_intersect($this->bounds, array($this->key($node))));
    }
    
    protected function moved($node, $parent) {
        $level = $parent !== null ? $this->levels[$this->key($parent)] + 1 : 0;
        
        $change = $level - $this->levels[$this->key($node)];
        
        $root = $parent !== null ? $this->roots[$this->key($parent)] : $this->key($node);
        
        $this->levels[$this->key($node)] = $level;

        $this->roots[$this->key($node)] = $root;

        foreach ($this->descendants($node) as $child) {
            $this->levels[$this->key($child)] += $change;

            $this->roots[$this->key($child)] = $this->roots[$this->key($node)];
        }
    }
    
    protected function newNode($line) {
        return $this->factory->newNode($this, $line);
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
        return array_intersect_key($this->nodes, array_intersect($this->levels, array(0)));
    }
    
    public function root($node) {
        return $this->roots[$this->key($node)];
    }
    
    public function level($node) {
        return $this->levels[$this->key($node)];
    }
    
    public function ancestors($node) {
        $bounds = $this->bounds($node);
        
        $left = array_slice($this->bounds, 0, $bounds[0]);
        
        $right = array_slice($this->bounds, $bounds[1] + 1);
        
        $keys = array_intersect($right, $left);
        
        $nodes = array();
        
        foreach ($keys as $key) {
            $nodes[$key] = $this->nodes[$key];
        }
        
        return $nodes;
    }
    
    public function parent($node) {
        return array_shift($this->ancestors($node));
    }
    
    public function descendants($node) {
        $bounds = $this->bounds($node);
        
        $keys = array_unique(array_slice($this->bounds, $bounds[0] + 1, $bounds[1] - $bounds[0] - 1));

        $nodes = array();
        
        foreach ($keys as $key) {
            $nodes[$key] = $this->nodes[$key];
        }
        
        return $nodes;
    }
    
    public function children($node) {
        return array_intersect_key($this->descendants($node), array_intersect($this->levels, array($this->level($node) + 1)));
    }
    
    public function contains($node) {
        return array_search($node, $this->nodes, true) !== false;
    }
    
    public function import($node) {
        if (!$this->contains($node)) {
            
            $this->nodes[] = $node;
            
            $key = $this->key($node);
            
            $this->bounds[] = $key;
            
            $this->bounds[] = $key;
            
            $this->roots[$key] = $key;
            
            $this->levels[$key] = 0;
        }
        
        return $node;
    }
    
    public function append($node, $parent) {
        if (!empty($parent) && !$this->contains($parent)) {
            throw new Exception;
        }
        
        $this->orphan($node);
        
        if (!empty($parent)) {
            $bounds = $this->bounds($node);
            
            $current = array_splice($this->bounds, $bounds[0], $bounds[1] - $bounds[0] + 1);

            $bounds = $this->bounds($parent);
            
            array_splice($this->bounds, $bounds[1], 0, $current);

            $this->moved($node, $parent);
        }
        
        return $node;
    }
    
    public function orphan($node) {
        $this->import($node);
        
        if (!count($this->ancestors($node))) {
            return $node;
        }

        $bounds = $this->bounds($node);

        $current = array_splice($this->bounds, $bounds[0], $bounds[1] - $bounds[0] + 1);
        
        array_splice($this->bounds, -1, 0, $current);
        
        $this->moved($node, null);
    }
    
    public function loadXmlFile($filename) {
        $matches = array();
        
        preg_match_all('/(?:\<|\>|([^\<\>]*))/', file_get_contents($filename), $matches);

        $current = null;

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

                        $this->append($this->newNode($value), $current);
                    }
                    else if ($value[0] === '/') {
                        $current = $this->parent($current);
                    } else if (substr($value, -1) === '/') {
                        $this->append($this->newNode($value), $current);
                    } else {
                        $current = $this->append($this->newNode($value), $current);
                    }
                    
                    $value = array_shift($matches[0]);
                    
                    if ($value !== '>') {
                        throw new Exception;
                    }
                break;

                default:
                    if ($current) {
                        $current->addValue($value);
                    }
            }
        }
    }
    
    public function saveXml() {
        $string = '';
        
        foreach ($this->roots() as $root) {
            $string .= $root->saveXml();
        }
        
        return $string;
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
interface XmlNode {
    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    function document();
    
    function ancestors();
    
    function parent();
    
    function descendants();
    
    function children();
    
    function attributes();
    
    function tag();
    
    function values();
    
    function saveXml();
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}

/**
 * {@link anaconda\XmlElement}
 * 
 * @package     anaconda
 * @name        XmlElement
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlElement implements XmlNode {
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
    
    private $document;
    /**///</editor-fold>

    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    public function tag() {
        return $this->tag;
    }
    
    public function values() {
        return $this->values;
    }
    
    public function attributes() {
        return $this->attributes;
    }
    
    public function document() {
        return $this->document;
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Constructors">
    /*\**********************************************************************\*/
    /*\                             Constructors                             \*/
    /*\**********************************************************************\*/
    public function __construct(\XmlDocument $document, $tag, $attributes) {
        $this->document = $document;
        
        $this->tag = $tag;
        
        $this->attributes = $attributes;
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
    public function children() {
        return $this->document()->children($this);
    }
    
    public function descendants() {
        return $this->document()->descendants($this);
    }

    public function ancestors() {
        return $this->document()->ancestors($this);
    }
    
    public function parent() {
        return $this->document()->parent($this);
    }
    
    public function addValue($value) {
        $this->values[] = $value;
    }
    
    public function saveXml() {
        $string = '';
        
        $tag = $this->tag();
        
        $pad = str_pad('', $this->document->level($this) * 4, ' ');

        $attributes = array();
        
        foreach ($this->attributes() as $key=>$value) {
            $attributes[] = ' '.$key.'="'.$value.'"';
        }
        
        $attributes = implode('', $attributes);

        $children = array();
        
        $values = $this->values();
        
        $value = array_shift($values);

        $children[0] = '';
        
        if (!strlen(trim($value)) || strpos(trim($value), "\n") === false) {
            $value = trim($value);
        }
        
        $children[0] .= $value;

        if (!strlen(trim($value)) || strpos(trim($value), "\n") !== false) {
            $children[0] .= "\n";
        }
        
        foreach ($this->children($this) as $child) {
            $children[] = $child->saveXml();

            $children[] = trim(array_shift($values))."\n";
        }
        
        
        $children = implode('', $children);

        $cpad = '';
        
        if (!strlen(trim($value)) || strpos(trim($value), "\n") !== false) {
            $cpad = $pad;
        }

        $close = strlen($children) ? ">{$children}{$cpad}</{$tag}>" : " />";
        
        $string .= "{$pad}<{$tag}{$attributes}{$close}";
        
        return $string;
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
 * {@link anaconda\XmlFactory}
 * 
 * @package     anaconda
 * @name        XmlFactory
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XmlFactory {
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
    protected function parse($line) {
        $matches = array();

        preg_match_all('/[^\=\s\?\/\"]+|\=|\/|\?|(?:\"[^\"]*\")/', $line, $matches);
        
        $tag = null;
        
        $attributes = array();
        
        $extras = array();

        while (count($matches[0])) {
            $match = array_shift($matches[0]);
            
            switch ($match) {
                case '?':
                    $extras['?'] = '?';
                break;
                
                case '/':
                    $extras['/'] = '/';
                break;
                
                default:
                    if ($tag === null) {
                        $tag = $match;
                        
                        break;
                    }
                    
                    $key = $match;
                    
                    $match = array_shift($matches[0]);
                    
                    if ($match !== '=') {
                        $attributes[$key] = $key;
                        
                        break;
                    }
                    
                    $match = array_shift($matches[0]);
                    
                    $attributes[$key] = trim($match, '"');
                    
                break;
            }
        }
        
        return array($tag, $attributes, $extras);
    }
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    public function newNode(\XmlDocument $document, $line) {
        list($tag, $attributes, $extras) = $this->parse($line);
        
        switch ($tag) {
            case 'xsl:analyze-string':
            case 'xsl:apply-imports':
            case 'xsl:apply-templates':
            case 'xsl:attribute':
            case 'xsl:attribute-set':
            case 'xsl:call-template':
            case 'xsl:character-map':
            case 'xsl:choose':
            case 'xsl:comment':
            case 'xsl:copy':
            case 'xsl:copy-of':
            case 'xsl:decimal-format':
            case 'xsl:document':
            case 'xsl:element':
            case 'xsl:fallback':
            case 'xsl:for-each':
            case 'xsl:for-each-group':
            case 'xsl:function':
            case 'xsl:if':
            case 'xsl:import':
            case 'xsl:import-schema':
            case 'xsl:include':
            case 'xsl:key':
            case 'xsl:matching-substring':
            case 'xsl:message':
            case 'xsl:namespace':
            case 'xsl:namespace-alias':
            case 'xsl:next-match':
            case 'xsl:non-matching-substring':
            case 'xsl:number':
            case 'xsl:otherwise':
            case 'xsl:output':
            case 'xsl:output-character':
            case 'xsl:param':
            case 'xsl:perform-sort':
            case 'xsl:preserve-space':
            case 'xsl:processing-instruction':
            case 'xsl:result-document':
            case 'xsl:sequence':
            case 'xsl:sort':
            case 'xsl:strip-space':
            case 'xsl:template':
            case 'xsl:text':
            case 'xsl:transform':
            case 'xsl:value-of':
            case 'xsl:variable':
            case 'xsl:when':
            case 'xsl:with-param':
            default:
                $node = new XmlElement($document, $tag, $attributes);
            break;
        
            case 'xsl:stylesheet':
                $node = new XslStylesheet($document, $tag, $attributes);
            break;
        }

        return $node;
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
 * {@link anaconda\XslNode}
 * 
 * @package     anaconda
 * @name        XslNode
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
interface XslNode {
    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}

/**
 * {@link anaconda\XslNodeMatchable}
 * 
 * @package     anaconda
 * @name        XslNodeMatchable
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
interface XslNodeMatchable {
    /**///<editor-fold desc="Properties">
    /*\**********************************************************************\*/
    /*\                             Properties                               \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
    /*\**********************************************************************\*/
    function match(\XmlNode $node);
    /**///</editor-fold>

    /**///<editor-fold desc="Event Handlers">
    /*\**********************************************************************\*/
    /*\                             Event Handlers                           \*/
    /*\**********************************************************************\*/
    /**///</editor-fold>
}

/**
 * {@link anaconda\XslStylesheet}
 * 
 * @package     anaconda
 * @name        XslStylesheet
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XslStylesheet extends XmlElement implements XslNode {
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
    /**///</editor-fold>

    /**///<editor-fold desc="Public Methods">
    /*\**********************************************************************\*/
    /*\                             Public Methods                           \*/
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
}

/**
 * {@link anaconda\XslTransformer}
 * 
 * @package     anaconda
 * @name        XslTransformer
 * @author      Terrence Howard <chemisus@gmail.com>
 * @version     0.1
 * @since       0.1
 */
class XslTransformer {
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
    private $stylesheet;
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
    public function __construct(\XmlDocument $stylesheet) {
        $this->stylesheet = $stylesheet;
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
    public function transform(\XmlDocument $document) {
        foreach ($this->stylesheet->nodes() as $node) {
            
        }
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


/*
$xsl = new XmlDocument(new XmlFactory());

$xsl->loadXmlFile(ROOT."/application/anaconda/view/layout.xsl");

$xml = new XmlDocument(new XmlFactory());

$xml->loadXmlFile(ROOT."/application/anaconda/config/modules.xml");

$transformer = new XslTransformer($xsl);

$transformer->transform($xsl);

echo '<xmp>';
print_r($xsl->saveXml());
echo '</xmp>';

*/