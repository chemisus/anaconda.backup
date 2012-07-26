<?php

/**
 * Test class for Tree.
 * Generated by PHPUnit on 2012-07-25 at 17:19:00.
 */
class TreeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Tree
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new \anaconda\Tree;
        
        $this->object->setBounds(new \anaconda\Vector());
        
        $this->object->setHeights(new \anaconda\Vector());
        
        $this->object->setTrees(new \anaconda\Vector());
        
        $this->object->setNodes(new \anaconda\Vector());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testNew() {
        $node1 = new \stdClass();
        $node2 = new \stdClass();
        $node3 = new \stdClass();
        $node4 = new \stdClass();
        $node5 = new \stdClass();
        
        $this->object->add($node1);
        $this->object->add($node2);
        $this->object->add($node3);
        $this->object->add($node4);
        $this->object->add($node5);
        
        self::assertRoot($node1, $node2, $node3, $node4, $node5);
        self::assertLeaf($node1, $node2, $node3, $node4, $node5);
        
        $this->object->move($node1, $node2, $node3, $node4, $node5);
        self::assertRoot($node1);
        self::assertParent($node1, $node2, $node3, $node4, $node5);
        self::assertAncestor($node1, $node2, $node3, $node4, $node5);
    }
    
    public function testRound1() {
        $node1 = new \stdClass();
        $node2 = new \stdClass();
        $node3 = new \stdClass();
        $node4 = new \stdClass();
        $node5 = new \stdClass();
        
        $this->object->add($node1);
        $this->object->move($node1, $node2, $node3, $node4, $node5);
        self::assertRoot($node1);
        self::assertParent($node1, $node2, $node3, $node4, $node5);
        self::assertAncestor($node1, $node2, $node3, $node4, $node5);
    }
    
    public function testRound2() {
        $node1 = new \stdClass();
        $node2 = new \stdClass();
        $node3 = new \stdClass();
        $node4 = new \stdClass();
        $node5 = new \stdClass();
        
        $this->object->add($node1);
        $this->object->move($node1, $node2, $node3);
        $this->object->move($node2, $node4);
        $this->object->move($node3, $node5);
        self::assertRoot($node1);
        self::assertParent($node1, $node2, $node3);
        self::assertParent($node2, $node4);
        self::assertParent($node3, $node5);
        self::assertAncestor($node1, $node2, $node3, $node4, $node5);
        self::assertAncestor($node2, $node4);
        self::assertAncestor($node3, $node5);
        self::assertLeaf($node5, $node4);
    }
    
    public function testRound3() {
        $node1 = new \stdClass();
        $node2 = new \stdClass();
        $node3 = new \stdClass();
        $node4 = new \stdClass();
        $node5 = new \stdClass();
        
        $this->object->add($node1);
        $this->object->move($node1, $node2, $node3);
        $this->object->move($node2, $node4);
        $this->object->move($node3, $node5);
        $this->object->move($node4, $node3);
        self::assertRoot($node1);
        self::assertParent($node1, $node2);
        self::assertParent($node2, $node4);
        self::assertParent($node4, $node3);
        self::assertParent($node3, $node5);
        self::assertAncestor($node1, $node2, $node3, $node4, $node5);
        self::assertAncestor($node3, $node5);
        self::assertAncestor($node4, $node3, $node5);
        self::assertAncestor($node2, $node4, $node3, $node5);
        self::assertLeaf($node5);
    }
    
    public function testRound4() {
        $node1 = new \stdClass();
        $node2 = new \stdClass();
        $node3 = new \stdClass();
        $node4 = new \stdClass();
        $node5 = new \stdClass();
        
        $this->object->add($node1);
        $this->object->move($node1, $node2, $node3);
        $this->object->move($node2, $node4);
        $this->object->move($node3, $node5);
        $this->object->move($node4, $node3);
        $this->object->orphan($node2);
        self::assertRoot($node1);
        self::assertLeaf($node1);
        self::assertRoot($node2);
        self::assertParent($node2, $node4);
        self::assertParent($node4, $node3);
        self::assertParent($node3, $node5);
        self::assertAncestor($node3, $node5);
        self::assertAncestor($node4, $node3, $node5);
        self::assertAncestor($node2, $node4, $node3, $node5);
        self::assertLeaf($node5);
    }
    
    protected function assertRoot($node) {
        foreach (func_get_args() as $node) {
            self::assertCount(0, $this->object->getAncestors($node));
            self::assertNull($this->object->getParent($node));
        }
    }
    
    protected function assertLeaf($node) {
        foreach (func_get_args() as $node) {
            self::assertCount(0, $this->object->getDescendants($node));
            self::assertCount(0, $this->object->getChildren($node));
        }
    }
    
    protected function assertParent($parent, $node) {
        $nodes = func_get_args();
        
        array_shift($nodes);
        
        foreach ($nodes as $node) {
            self::assertEquals($parent, $this->object->getParent($node));
            self::assertTrue($this->object->getChildren($parent)->hasValue($node));
        }
    }
    
    protected function assertAncestor($ancestor, $node) {
        $nodes = func_get_args();
        
        array_shift($nodes);
        
        foreach ($nodes as $node) {
            self::assertTrue($this->object->getAncestors($node)->hasValue($ancestor));
            self::assertTrue($this->object->getDescendants($ancestor)->hasValue($node));
        }
    }
    }

?>
