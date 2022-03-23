ToDoList
========
Base du projet #8 : Améliorez un projet existant
https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

--------------------------
Dans le fichier "composer.json" un script (que j ai nommé « reset-data ») a été crée pour remettre a zero ma base de donnée, surtout utile pour l'utilisation d'un jeu de donnée via des fixtures. Pour l executer il suffit d'executer la commande suivante :
            
            composer reset-data

--------------------------

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