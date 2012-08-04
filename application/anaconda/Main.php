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

class SubscriberFilterCallback extends \SubscriberDecorator {
    private $callback;
    
    public function __construct($callback, \Subscriber $subscriber) {
        parent::__construct($subscriber);
        
        $this->callback = $callback;
    }
    
    protected function check(\Publisher $publisher) {
        return call_user_func($this->callback, $publisher);
    }
}

class SubscriberPublishCallback extends \SubscriberTemplate {
    private $callback;
    
    public function __construct($callback) {
        $this->callback = $callback;
    }
    
    public function publish(\Publisher $publisher) {
        return call_user_func($this->callback, $publisher);
    }
}

class Main {
    public static function Run() {
        $subject = new \RoleTemplate(new \SubjectTemplate());

        $subject->addPermission(
                new \PermissionDecorator(
                        new \PermissionTemplate('blah')));

        $application = new \ApplicationTemplate($subject);

        $application->subscribe(
                new SubscriberFilterCallback(
                        function (\Publisher $publisher) {
            return $publisher['name'] === 'system.ready';
        }, new SubscriberPublishCallback(function () {
            echo 'hi';
        })));

        $application->subscribe(
                new SubscriberFilterCallback(
                        function (\Publisher $publisher) {
                            return $publisher['name'] === 'system.route'
                                && $publisher['path'] === '';
                        }, new SubscriberPublishCallback(
                                function () {
                                    echo 'root page';
                                })));

        $application->subscribe(
                new SubscriberFilterCallback(
                        function (\Publisher $publisher) {
                            return $publisher['name'] === 'system.route'
                                && $publisher['path'] === 'home';
                        }, new SubscriberPublishCallback(
                                function () {
                                    echo 'home page';
                                })));

        $operation = new \OperationTemplate();

        $operation->execute($application->subject());

        $application->publish(new \PublisherTemplate(array(
            'name' => 'system.ready'
        )));

        $application->publish(new \PublisherTemplate(array(
            'name' => 'system.route',
            'path' => empty($_SERVER['PATH_INFO']) ? '' : trim($_SERVER['PATH_INFO'], '/')
        )));
    }
}

Main::Run();