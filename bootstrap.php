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
    public static function Main() {
        define('ROOT', dirname(__FILE__).'/');
        
        spl_autoload_register(function ($class) {
            $class = strtr($class, array('\\'=>'/'));
            
            $filename = array_shift(glob(ROOT."*/*/src/{$class}.php"));
            
            if ($filename === null) {
                $stack = debug_backtrace();
                
                array_shift($stack);
                
                $trace = array_shift($stack);
                
                $file = $trace['file'];
                
                $line = $trace['line'];
                
                throw new Exception("Could not load {$class}. Called from {$file} on line {$line}");
            }
            
            require_once($filename);
        });
    }
}

Bootstrap::Main();

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

xmp(memory_get_peak_usage());

class A {
    private $array = array();
    
    public function __construct($array=array()) {
        $this->array = $array;
    }
}

xmp(memory_get_peak_usage());

for ($i = 0; $i < 2; $i++)
$a[$i] = new A(array());

xmp(memory_get_peak_usage());





/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

$application = new ApplicationTemplate(new FactoryTemplate());

$application->addFactory(new \node\ElementFactory());

$application->addFactory(new \route\RouteFactory());

$document = new \node\DocumentTemplate($application);

$document->fromXml(file_get_contents(ROOT.'application/anaconda/config/routes.xml'));

xmp($document->toXml());

xmp($document);

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/





