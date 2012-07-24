# Anaconda
 * Creator:     Terrence Howard <<chemisus@gmail.com>>
 * Version:     0.1.1
 * Date:        
 * Notes:
 * Location: https://github.com/chemisus/anaconda

## Introduction

Anaconda is a PHP Framework. Anaconda can almost be thought of as a [Model View Controller](http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) Framework (MVC) in the sense that it supports Model, Views (XSLT Stylesheets), and Controllers (`Form`).

Many other PHP MVC Frameworks will start a page by loading a `Controller` and then calling a method on the controller determined by the path requested by the user.

Anaconda, however, does not automatically start the page with the controller, but rather a `Page` that allows multiple `Form` objects. The content of the page should be wrapped in an html `<form></form>`, so that no matter which form performed an action, all forms will be submitted. When a user submits data, Anaconda will start a `Page` object, which then initialize any static and dynamic `Form` objects, load the `Field` data submitted by each `Form`, then execute the `Action` corresponding with the submit button clicked.

## Goals
The goals for Anaconda are as follows: 
 * File Finder; Register path patterns for files.
   * Class autoloading
   * File finding in code
 * Hierarchical; Extend or override modules as you wish.
   * Packages and application modules can extend or override system modules and views.
 * Structure; Know where your code goes.
   * Package; Other developer packages go here.
   * Application; Your application goes here.
   * System; Anaconda goes here.
 * Transformability; Data is supplied to views as XML to be transformed however needed.
 * Templates; Uses XSLT to transform XML data into HTML. 
   * Specify which XSLT files to include
 * Nodes; Generates the data streams by converting nodes into [DOMElement](http://www.php.net/manual/en/class.domelement.php) and returning a [DOMDocument](http://php.net/manual/en/class.domdocument.php), which is then used as the data stream for the views.
   * Node; The basic node unit. Acts as a container for other nodes.
   * Element; Extends Node. Can specify XSLT stylesheets to be included.
   * Page; Extends Element.
   * Form; Extends Element.
   * Field; Extends Element.
   * Action; Extends Field.
   * Any Model; Implements Element.
 * Pages; Specify forms, models, or other nodes to be added to the page data stream.
   * Initialize; Add forms or other nodes to the page.
   * 
 * Forms; Can be static or dynamic (not automatically initialized by a page).
   * Initialize; Add fields, actions, subforms, or other nodes to the form.
   * Load; Automatically load data that is submitted
   * Execute; When submit button is clicked, call the corresponding callback.
 * Fields; Used by Forms and Models.
   * Datatypes; Specify how data is stored and displayed.
   * Decorators; Specify validation settings for data.
 * Models; Specify how data should be grouped together.
 * Databases
   * ORM; Use MySql or any other relational database supported by PDO.
   * ODM; Use CouchDB or any other document document database.

## Anaconda Roadmap:

Project: M.N.Ip -> Major.Minor.Iteration[Phase]
Component: V.Ip -> Version.Iteration[Phase]


analysis            // What do we need?
design              // Files, Pseudo-Comment, Interfaces, Test Creation
implementation      // Class Creation
testing             // Test
evaluation          // What is wrong?
release             // Nothing important!


Collections
    Vector
    Map
    Tree
    Node

Model Managing
    Factory
    Manager

File Locator
    Globber

System Events
    Subscriber
    Subscriber Composite
    Subscriber Decorator
    Publisher


    


## Requirements

 * PHP 5.3
 * [PHP XSL](http://www.php.net/manual/en/book.xsl.php)
 * [mod_rewrite](http://httpd.apache.org/docs/current/mod/mod_rewrite.html)

## Installation

## Configuration

## Examples

## Operating Instructions

## Copywrite