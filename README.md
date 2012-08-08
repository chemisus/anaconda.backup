# Anaconda
 * Creator:     Terrence Howard <<chemisus@gmail.com>>
 * Version:     0.1.1
 * Date:        
 * Notes:
 * Location: https://github.com/chemisus/anaconda

## Introduction


## Goals

### Events

### Routes
    <?xml version="1.0"?>
    <routes>
        <route path="module/create" controller="page/ModuleController" method="create">
            <form name="module" value="module:create" />
        </route>
        <route path="module/[method](/[index])" controller="page/ModuleController" method="[method]">
            <form name="module" value="module:[index]" />
            <filter name="method" value="update|delete|index" />
        </route>
        <route path="[controller](/[method])" controller="page/[controller]" method="[method]">
            <default name="controller" value="home" />
            <default name="method" value="index" />
            <filter name="controller" value="" />
        </route>
    </routes>

### Authorization

## Anaconda Roadmap:

 * Configuration

 * Publisher
 * Subscriber

 * Route
 * Controller

 * Subject
 * Role
 * Permission
 * Operation

## Requirements


## Installation


## Configuration


## Examples


## Operating Instructions


## Copywrite

