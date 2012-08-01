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

define('ROOT', __DIR__.'/');

class Bootstrap {
    public static function Run() {
        require_once(ROOT.'system/anaconda/src/Autoloader.php');

        require_once(ROOT.'system/anaconda/src/anaconda/Autoloader.php');

        \anaconda\Autoloader::Factory('autoload')
        ->register()
        ->addTranslate('\\', '{,_,/}')
        ->addTranslate('_', '{,_,/}')
        ->addExtension('.php')
        ->add(
            ROOT,
            array(
                'packages/*',
                'application/*',
                'system/*',
            ),
            'src'
        );

        \anaconda\Autoloader::Factory('view')
        ->addTranslate('\\', '{,_,/}')
        ->addTranslate('_', '{,_,/}')
        ->addExtension('.xsl')
        ->add(
            ROOT,
            array(
                'packages/*',
                'application/*',
                'system/*',
            ),
            'view/*'
        );

        \anaconda\Autoloader::Factory('assets')
        ->addTranslate('\\', '{,_,/}')
        ->addTranslate('_', '{,_,/}')
        ->add(
                ROOT,
                array(
                    'packages/*',
                    'application/*',
                    'system/*',
                ),
                'assets'
            )->add(
                ROOT,
                'www/assets'
        );

        \anaconda\Autoloader::Factory('load')
        ->addTranslate('\\', '{,_,/}')
        ->addTranslate('_', '{,_,/}')
        ->add(
            ROOT,
            array(
                'system/*',
                'application/*',
                'packages/*',
            )
        );

        foreach (\anaconda\Autoloader::Get('load')->find('load.php') as $load) {
            require_once($load);
        }
    }
}

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\                 Anything below this line is all a test.                  \*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

Bootstrap::Run();


$subscribers = new anaconda\Tree();
$subscribers->setBounds(new anaconda\Vector());
$subscribers->setHeights(new anaconda\Vector());
$subscribers->setNodes(new anaconda\Vector());
$subscribers->setTrees(new anaconda\Vector());

function publish(\Tree $tree, $node, $publisher) {
    if ($node->check($publisher) === false) {
        return;
    }
    
    $node->execute($publisher);
    
    foreach ($tree->getChildren($node) as $child) {
        publish($tree, $child, $publisher);
    }
}

$subscribers->add('system.load',
    new anaconda\SubscriberSubset(array(
        'event' => 'system.load',
    ),
    new anaconda\SubscriberCallback(function (\Publisher $publisher) {
        $values = array();

        if ($publisher instanceof \Vector) {
            foreach ($publisher['field'] as $model=>$indexes) {
                if (is_array($indexes)) {
                    foreach ($indexes as $index=>$fields) {
                        $values[] = array(
                            'event' => 'form.load',
                            'model' => $model,
                            'index' => $index,
                            'fields' => \anaconda\Vector::ToVector($fields),
                            'value' => $value,
                        );
                    }
                }
            }
        }

        foreach ($values as $value) {
            $event = new \anaconda\Publisher($value);
        }
        
        echo 'load';
    })));

$subscribers->add('system.execute',
    new anaconda\SubscriberSubset(array(
        'event' => 'system.execute',
    ),
    new anaconda\SubscriberCallback(function (\Publisher $publisher) {
        $values = array();

        if ($publisher instanceof \Vector) {
            foreach ($publisher['action'] as $model=>$indexes) {
                if (is_array($indexes)) {
                    foreach ($indexes as $index=>$fields) {
                        if (is_array($fields)) {
                            foreach ($fields as $field=>$value) {
                                $values[] = array(
                                    'event' => 'form.execute',
                                    'model' => $model,
                                    'index' => $index,
                                    'action' => $field,
                                    'value' => $value,
                                );
                            }
                        }
                    }
                }
            }
        }
        
        foreach ($values as $value) {
            $event = new \anaconda\Publisher($value);
        }
        
        echo 'execute';
    })));




$load = new anaconda\Publisher(array(
    'event' => 'system.load', 
    'input' => \anaconda\Vector::ToVector($_REQUEST)->offsetGet('input'),
));

$execute = new anaconda\Publisher(array(
    'event' => 'system.execute', 
    'action' => \anaconda\Vector::ToVector($_REQUEST)->offsetGet('action'),
));

foreach ($subscribers->getRoots() as $subscriber) {
    publish($subscribers, $subscriber, $load);
}

foreach ($subscribers->getRoots() as $subscriber) {
    publish($subscribers, $subscriber, $execute);
}



























class IsOwnerPermission extends \anaconda\PermissionDecoration {
    protected function doCheck(\Operation $operation, $target, \Subject $subject, \Role $role) {
        return $target->owner === $subject;
    }
}




$session = new anaconda\Session();

$subject = new anaconda\Subject('chemisus');

$role = new anaconda\Role('admin');

$permission = new IsOwnerPermission(new \anaconda\Permission('user.create'));

$role->addPermission($permission);

$subject->addRole($role);

$session->assume($subject);

$object = new stdClass();

$object->owner = $subject;

$operation = new anaconda\Operation(array('user.create'=>$object));

$operation->execute($session->current());
















/*
class MessageModel {
    private $user;
    
    private $factories;
    
    public function getFactories() {
        return $this->factories;
    }
    
    public function setFactories($value) {
        $this->factories = $value;
    }
    
    public function getUserFactory() {
        return $this->factories['user'];
    }
    
    public function setUserFactory(\UserFactory $value) {
        $this->factories['user'] = $value;
    }
    
    public function getUser() {
        if ($this->user === null) {
            $this->user = $this->getUserFactory()->createUser();
        }
        
        return $this->user;
    }
    
    public function setUser($value) {
        $this->user = $value;
    }
}

class UserModel {
    private $name;
    
    private $factories;
    
    public function getFactories() {
        return $this->factories;
    }
    
    public function setFactories($value) {
        $this->factories = $value;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($value) {
        $this->name = $value;
    }
}

interface Factory {
    function getContainer();
    
    function setContainer($value);
    
    function create();
}

interface UserFactory {
    function createUser();
}

interface MessageFactory {
    function createMessage();
}

class UserFactoryTemplate implements Factory, UserFactory {
    private $container;
    
    public function getContainer() {
        return $this->container;
    }
    
    public function setContainer($value) {
        $this->container = $value;
    }
    
    public function create() {
        $value = new UserModel();
        
        $value->setFactories($this->getContainer());
        
        return $value;
    }

    public function createUser() {
        return $this->create();
    }
}

class MessageFactoryTemplate implements Factory, MessageFactory {
    private $container;
    
    public function getContainer() {
        return $this->container;
    }
    
    public function setContainer($value) {
        $this->container = $value;
    }
    
    public function create() {
        $value = new MessageModel();
        
        $value->setFactories($this->getContainer());
        
        return $value;
    }

    public function createMessage() {
        return $this->create();
    }
}

class FactoryContainer extends anaconda\Vector implements UserFactory, MessageFactory {
    public static function Build() {
        $value = new self();
        
        $value['user'] = new \UserFactoryTemplate();
        
        $value['user']->setContainer($value);
        
        $value['message'] = new \MessageFactoryTemplate();
        
        $value['message']->setContainer($value);
        
        return $value;
    }

    public function createMessage() {
        return $this['message']->create();
    }

    public function createUser() {
        return $this['user']->create();
    }
}



$factory = FactoryContainer::Build();

$message = $factory->createMessage();

$user = $message->getUser();

var_dump($user);



*/















/*


interface Session {
    function execute(\Operation $value);
}

interface Subject {
    function addRole(\Role $value);
    
    function check(\Session $session, \Operation $value);
}

interface Role {
    function name();
    
    function addPermission(\Permission $value);
    
    function check(\Session $session, \Subject $subject, \Operation $value);
}

interface Permission {
    function name();
    
    function check(\Session $session, \Subject $subject, \Role $role, \Operation $value);
}

interface Operation {
    function getPermissions();
    
    function execute(\Session $session);
}

class SessionModel implements Session {
    public static function Instance() {
        if (!isset($_SESSION['ServerModel'])) {
            $session = new self();
            
            $session->push();
            
            $session->loadXML('../xml/access.xml');
            
            $session->subject()->addRole($session->getRole('guest'));
            
            $_SESSION['ServerModel'] = $session;
        }
        
        return $_SESSION['ServerModel'];
    }
    
    private $subjects = array();
    
    private $roles = array();
    
    public function getRole($key) {
        return $this->roles[$key];
    }
    
    private function __construct() {
    }
    
    public function addRole(\Role $role) {
        $this->roles[$role->name()] = $role;
    }
    
    public function subject() {
        return array_pop(array_values($this->subjects));
    }
    
    public function push() {
        $this->subjects[] = new SubjectModel();
    }
    
    public function pop() {
        array_pop($this->subjects);
    }
    
    public function execute(\Operation $operation) {
        if ($this->subject()->check($this, $operation)) {
            $operation->execute($this);
        }
    }
    
    public function loadXML() {
        $xml = new DOMDocument();

        $xml->load('../xml/access.xml');

        foreach ($xml->getElementsByTagName('role') as $roleNode) {
            $role = new RoleModel($roleNode->getAttribute('name'));

            foreach ($roleNode->getElementsByTagName('permission') as $permissionNode) {
                $value = new PermissionModel($permissionNode->getAttribute('name'));

                foreach ($permissionNode->getElementsByTagName('decorator') as $decoratorNode) {
                    $type = $decoratorNode->getAttribute('type');

                    $value = new $type($value);

                    $value->loadXML($decoratorNode);
                }

                $role->addPermission($value);
            }
            
            $this->addRole($role);
        }
    }
}

class SubjectModel implements Subject {
    private $roles = array();
    
    private $name = 'guest';
    
    public function name() {
        return $this->name;
    }
    
    public function addRole(\Role $value) {
        $this->roles[$value->name()] = $value;
    }
    
    public function check(\Session $session, \Operation $operation) {
        foreach ($this->roles as $role) {
            if ($role->check($session, $this, $operation)) {
                return true;
            }
        }
        
        return false;
    }
}

class RoleModel implements Role {
    private $permissions = array();
    
    private $name;
    
    public function name() {
        return $this->name;
    }
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function addPermission(\Permission $value) {
        $this->permissions[$value->name()] = $value;
    }
    
    public function check(\Session $session, \Subject $subject, \Operation $operation) {
        foreach ($this->permissions as $permission) {
            if ($permission->check($session, $subject, $this, $operation)) {
                return true;
            }
        }
        
        return false;
    }
}

class PermissionModel implements Permission {
    private $name;
    
    public function name() {
        return $this->name;
    }
    
    public function __construct($name) {
        $this->name = $name;
    }

    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        return true;
    }
}

abstract class PermissionDecorator implements Permission {
    private $permission;
    
    public function getPermission() {
        return $this->permission;
    }
    
    public function name() {
        return $this->permission->name();
    }
    
    public function __construct(\Permission $permission) {
        $this->permission = $permission;
    }

    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        return $this->permission->check($session, $subject, $role, $operation);
    }
    
    public function loadXML(DOMElement $element) {
    }
}

class OperationTypePermission extends PermissionDecorator {
    private $operation;
    
    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        return ($operation instanceof $this->operation)
            && (parent::check($session, $subject, $role, $operation));
    }

    public function loadXML(DOMElement $element) {
        $this->operation = $element->getAttribute('operation');
    }
}

class IsOwnerPermission extends PermissionDecorator {
    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        return parent::check($session, $subject, $role, $operation);
    }
}

class IsSubjectPermission extends PermissionDecorator {
    private $value;
    
    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        return $subject->name() === $this->value && parent::check($session, $subject, $role, $operation);
    }

    public function loadXML(DOMElement $element) {
        $this->value = $element->getAttribute('value');
    }
}

class IsRolePermission extends PermissionDecorator {
    private $value;
    
    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        return $role->name() === $this->value && parent::check($session, $subject, $role, $operation);
    }

    public function loadXML(DOMElement $element) {
        $this->value = $element->getAttribute('value');
    }
}

class AllowPermission extends PermissionDecorator {
    private $allows = array();
    
    public function check(\Session $session, \Subject $subject, \Role $role, \Operation $operation) {
        foreach ($operation->getPermissions() as $value) {
            if (array_search($value, $this->allows, true) === false) {
                return false;
            }
        }
        
        return parent::check($session, $subject, $role, $operation);
    }

    public function loadXML(DOMElement $element) {
        foreach ($element->getElementsByTagName('param') as $param) {
            $this->allows[] = $param->nodeValue;
        }
    }
}

class CreateUserOperation implements Operation {
    private $permissions = array(
        'user.create',
    );
    
    public function getPermissions() {
        return $this->permissions;
    }
    
    public function setPermissions($value) {
        $this->permissions = $value;
    }

    public function execute(\Session $session) {
        echo 'CreateUserOperation';
    }
}

class UpdateUserOperation implements Operation {
    private $permissions = array(
        'user.update',
    );
    
    public function getPermissions() {
        return $this->permissions;
    }
    
    public function setPermissions($value) {
        $this->permissions = $value;
    }

    public function execute(\Session $session) {
        echo 'UpdateUserOperation';
    }
}

*/



//session_start();



//session_write_close();