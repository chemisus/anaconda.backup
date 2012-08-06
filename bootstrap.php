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

require_once('functions.php');

class Bootstrap {
    public static function Run() {
        define('ROOT', dirname(__FILE__).'/');
        
        spl_autoload_register(function ($class) {
            $class = strtr($class, array('\\'=>'/'));
            
            require_once(array_shift(glob(ROOT."*/*/src/{$class}.php")));
        });
    }
}

Bootstrap::Run();

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

$controller = new ModuleController();

$controller->before();

$routes = array_flatten(isset($_REQUEST['route']) ? $_REQUEST['route'] : array(), '/', false);

foreach ($routes as $route) {
    xmp(trim($route, '/'));
    
    $matches = array();
    
    preg_match('/^(?P<controller>[^\/]*)(\/(?P<action>[^\/]*))?(\/(?P<module>[^\/]*))?(\/(?P<index>[^\/]*))?/', $route, $matches);
    
    if ($matches['controller'] === 'module' && $matches['action'] === 'create') {
        $controller->create($_REQUEST['field'][$matches['module']]);
    } else if ($matches['controller'] === 'module' && $matches['action'] === 'delete') {
        $controller->delete($_REQUEST['field'][$matches['module']][$matches['index']]['name']);
    }
    
/*
    if (preg_match('/^module\/create/', $route)) {
        $controller->create($_REQUEST['field']['newmodule']);
    }
 * 
 */
}

$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

xmp(trim($path, '/'));

$controller->after();

$controller->render();
