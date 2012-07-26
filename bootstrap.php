<?php
/**
 * @author      Terrence Howard <chemisus@gmail.com>
 * @copyright   Copyright (c) 2012, Terrence Howard
 */

define('ROOT', __DIR__.'/');

call_user_func(function () {
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
});
