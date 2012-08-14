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

function xmp() {
    echo '<xmp style="border: 1px black dotted;">';
    foreach (func_get_args() as $value) {
        echo "\n";
        print_r($value);
        echo "\n";
    }
    echo "\n";
    echo '</xmp>';
}

/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/

function array_flatten2($values, $glue='', $last=true) {
    if (!is_array($values)) {
        return array($last ? $values : null);
    }

    $array = array();

    foreach ($values as $key=>$value) {
        $value = array_flatten($value, $glue, $last);

        if (count($value)) {
            foreach ($value as $route) {
                $array[] = "{$key}{$glue}{$route}";
            }
        }
        else {
            $array[] = $key;
        }
    }

    return $array;
}

function array_flatten(array $array, $glue='') {
    $values = array();
    
    foreach ($array as $key=>$value) {
        if (is_array($value)) {
            foreach (array_flatten($value, $glue) as $k=>$v) {
                $values[$key.$glue.$k] = $v;
            }
        } else {
            $values[$key] = $value;
        }
    }
    
    return $values;
}

function left($string, $pattern) {
    return substr($string, 0, strlen($pattern)) === $pattern;
}

function right($string, $pattern) {
    return substr($string, strlen($string) - strlen($pattern)) === $pattern;
}


function factories($pattern) {
    $classes = array();

    $factories = array();

    foreach (glob($pattern, GLOB_BRACE) as $path) {
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

        $factories[] = new $class($tag);
    }

    return $factories;
}


/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
/*\**************************************************************************\*/
