ToDoList
========
Project basis: Improve an existing project 
https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

# Table of Contents
1.  __[Deliverables ](#Deliverables)__
2.  __[Minimum server configuration](#Minimum-server-configuration)__
3.  __[Installation ](#Installation)__
4.  __[Test ](#Test)__
4.  __[Documentation ](#Documentation)__

# Deliverables
1. Instructions for installing the project (in this README.md file) 
2. Issues on the GitHub repository 
3. In a "docs" folder at the root of the project: 
    1. Document explaining how to contribute to the project (file "contribution.md") 
    2. Technical documentation concerning the implementation of authentication (file "document_technique_todoList.pdf") 
        * understand which file(s) need to be modified and why 
        * how authentication works and where users are stored 
    3. The application coverage test. To access it, here is the path "docs > coverageTests" and display the "index.html" page in your browser 
    4. The code quality and performance audit report
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

---

16/01/2022 : creation of the github repository and update of the original symfony project in 3.1 to symfony 3.4 (correction of deprecations)  
27/01/2022 : migration from symfony 3.4 to symfony 5.4.2 and correction of depreciation

29/01/2022 : corrections of anomalies => management of roles for users  
29/01/2022 : corrections of anomalies => task management (attach an author to a task and display it in the task list in front) (task management without author displayed anonymously)  
29/01/2022 : bug fix in my code for saving roles in the "UserController.php" file

30/01/2022 : resolution of the personal display bug to display the task icon closed or not completed  
30/01/2022 : update of TaskType.php and edit.html.twig file  
30/01/2022 : personal code modification => mapping of the "roles" field in "userType.php"  
30/01/2022 : modification of personal code => "userType.php" file so that when editing a user, the role select is positioned on the role to which it belongs  

06/02/2022 : addition of {{ path('homepage') }} in "base.html.twig"  
06/02/2022 : added @IsGranted("ROLE_ADMIN") rule for the listAction function

07/02/2022 : addition of @IsGranted("DELETE_TASK") rule in controller taskcontroller function "deleteTaskAction" and creation of "TaskVoter" in security > voter

08/02/2022 : modify "taskVoter.php" function "voteOnAttribute" for an admin to delete tasks of which anonymous is the author

19/02/2022 : TASKTEST unit test

20/02/2022 : unit test for entities (task, user) and composer upgrade  
20/02/2022 : creation of the CoverageTest folder to test the code coverage rate

25/02/2022 : refactoring of unit test code (taskTest and UserTest) and functional test defaultControllerTest.php

26/02/2022 : functional test SecurityControllerTest.php

03/03/2022 : functional test UserControllerTest.php

04/03/2022 : functional test TaskControllerTest.php

10/03/2022 : correction of the depreciations appeared during the tests

11/03/2022 : create fixtures (UserFixtures, Taskfixtures)

13/03/2022 : modification of tests following fixtures

14/03/2022 : installation and configuration of doctrine-test-bundle to rollback transactions in my tests  
14/03/2022 : modifying userFixtures.php to use UserPasswordHasherInterface

22/03/2022 : delete branche dev_documentation

23/03/2022 : create branche dev_document

24/03/2022 : markdown readme document  
24/03/2022 : update readme.md  
24/03/2022 : create contribution.md  
24/03/2022 : update contribution.md  
24/03/2022 : update 2 contribution.md  
24/03/2022 : update 3 contribution.md

25/03/2022 : update of project documentation

26/03/2022 : update documentation, security.yaml, securityController  
26/03/2022 : integration "document_technique_todoList"

08/04/2022 : integration "blackfire"  
08/04/2022 : integration "codeSniffer en dev"  
08/04/2022 : modification "codeSniffer en dev"  
