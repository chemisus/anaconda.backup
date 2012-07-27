<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
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

$subscribers->add(
    new anaconda\SubscriberSubset(array('name'=>'hi'),
    new anaconda\SubscriberCallback(function (\Publisher $publisher) {
})));


$publisher = new anaconda\Publisher(array(
    'name'=>'hi', 
    'hey'=>'hey', 
    'blah'=>array('ha'=>'he')
));

foreach ($subscribers->getRoots() as $subscriber) {
    publish($subscribers, $subscriber, $publisher);
}












