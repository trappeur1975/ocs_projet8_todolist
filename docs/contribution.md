Contribution
========

To participate in the project, whether to improve existing features, add new ones or fix bugs, please proceed as indicated below :

# Table of Contents
1.  __[Installation](#Installation)__
2.  __[Contribution ](#New-Contribution)__
    1.  __[develop your code changes or new features](##Develop-your-code-changes-or-new-features)__
    2.  __[contribution ](##Contribution)__

# Installation
1. Click on the "Fork" button at the top right of the page and "fork" the project. This creates a copy of the repository on your GitHub account.  

2. Log in to your GitHub account. 

3. Go to your Github account then to the newly forked repository to click on the "Code" button and copy the repository link. 

4. Install the project locally by following the instructions in the [README.md](README.md) file.

For more details see [the GitHub documentation](https://docs.github.com/en/get-started/quickstart/fork-a-repo). 

# Contribution
## develop your code changes or new features
1. Whatever the modifications or novelty envisaged, create a specific branch bearing an explicit name on what it contains. 
To create a branch, use the command: git checkout -b branchname.

2. Begin development of modifications. 

    * Use comments on each to describe classes and methods 
    * Respect [Symfony coding standards](https://symfony.com/doc/5.4/contributing/code/standards.html) 

3. Check that the application is still functional by running the existing PHPUnit tests and if necessary by implementing new ones corresponding to your modifications or creations of new functionalities. 
    * Make sure you get at least 70% code coverage 

4. Make relevant commits: git commit -am "commit message". 

5. check the performance of your code using "Blackfire.io"

## contribution
Push the branch to Github with the command "git push -u origin branchname" and create a pull request 

For more information refer to the following documentation [the GitHub documentation](https://docs.github.com/en/github/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests) 


**If your contribution is relevant it will be integrated into the project and we will be eternally grateful to you.**