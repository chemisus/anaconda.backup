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

$routeManager = new RouteManager();

$routeManager->setup();

$routeManager->route();


