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

class Bootstrap {
    public static function Main() {
        $bootstrap = new static();
        
        $bootstrap->load();
        
        $bootstrap->register();
        
        $bootstrap->application();
        
        $bootstrap->factories();
        
        $bootstrap->run();
    }

    private $application;
    
    private function load() {
        require_once('constants.php');

        require_once('functions.php');
    }
    
    private function register() {
        spl_autoload_register(function ($class) {
            $class = strtr($class, array('\\'=>'/'));
            
            $filename = array_shift(glob(SRC."{$class}.php", GLOB_BRACE));
            
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
    
    private function application() {
        $this->application = new \anaconda\Application();
    }

    private function factories() {
        $classes = array();
        
        foreach (glob(RSRC."*/factory/*.php", GLOB_BRACE) as $path) {
            $relative = array_pop(explode('/', substr($path, strlen(ROOT)), 4));
            
            $file = array_shift(glob(SRC.$relative, GLOB_BRACE));
            
            $file = array_pop(explode('/', substr($path, strlen(ROOT)), 4));
            
            $class = substr($file, 0, strrpos($file, '.'));
            
            $tag = basename($class);
            
            $class = strtr($class, array('/'=>'\\'));
            
            if (isset($classes[$class])) {
                continue;
            }
            
            $classes[$class] = $class;
            
            $factory = new $class($tag);
            
            $this->application->addFactory($factory);
        }
    }
    
    private function run() {
        $this->application->run();
    }
}

Bootstrap::Main();

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
















