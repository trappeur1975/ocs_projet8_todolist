ToDoList
========
Project basis: Improve an existing project 
https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

# Table of Contents
1.  __[Deliverables](#Deliverables)__
2.  __[Minimum server configuration](#Minimum-server-configuration)__
3.  __[Installation](#Installation)__
4.  __[Test](#Test)__
5.  __[Documentation](#Documentation)__

# Deliverables
 1. Instructions for installing the project (in this README.md file) 
 2. Issues on the GitHub repository 
 3. In a "docs" folder at the root of the project: 
     1. Document explaining how to contribute to the project (file "contribution.md") 
     2. Technical documentation concerning the implementation of authentication (file "document_technique_todoList.pdf") 
          * understand which file(s) need to be modified and why 
          * how authentication works and where users are stored 
     3. The application coverage test. To access it, here is the path "docs > coverageTests" and display the "index.html" page in your browser 
     4. The code quality and performance audit report  (file "audit_de_qualite_et_performance.pdf") 
         * detail the quality process to be used as well as the rules to be respected.

# Minimum server configuration
  * php 8.1
  * apache 2.4.51
  * Mysql 5.7.36
 
# Libraries used
  * composer 2.2.6
  * Symfony 5.4.4
  * Bootstrap v3.3.7  
  * doctrine/orm": "^2.11"
  * doctrine/doctrine-fixtures-bundle": "^3.4"
  * phpunit/phpunit": "^9.5"
  * symfony/phpunit-bridge": "^6.0"

# Installation  
 1. Import the repository by downloading the zip or cloning it with the command  
    https://github.com/trappeur1975/ocs_projet8_todolist.git

 2. Install the libraries with the command  
    composer install

 3. customize its environment variables in the ".env" and ".env.test" file   
    To benefit from a data set and be able to use them in front via the application or in the backend to execute unit and functional tests then in the "env" and ".env.test" file use the same database 

    for example: DATABASE_URL="mysql://root:@127.0.0.1:3306/ocsp8todolist_test" 

 4. Create the database with the command  
    php bin/console doctrine:database:create  
    php bin/console doctrine:migrations:migrate  

 5. run the "reset-data" script" found in the "composer.json" file to create a dataset via fixtures by executing the command  
    composer reset-data   

 6. Start the Symfony server with the command  
    symfony server:start  

# Test
to run the tests I created, run the command: 
    vendor/bin/phpunit

# Documentation
[documentation symfony](https://symfony.com/doc/5.4/setup.html)

[documentation php](https://phpunit.readthedocs.io/en/latest/installation.html#requirements)
