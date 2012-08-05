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
