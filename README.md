symfony 1.4 DI support
======================

Here explains how to use the Symfony/DependencyInjection with Composer in your symfony 1.4 project.

(!) Caution
-----------

The symfony 1.4 is not recommended as creating a new project. If you wanna create a new project, you should use the Symfony2 framework.  
This repository just explains how to use modern PHP libraries in your project.

Try it
------

First, Clone this repository.

    clone git@github.com:issei-m/symfony14-di-support.git

Next, Execute following command to get the necessary some libraries with Composer.

    php composer selfupdate && php composer install

OK! Everything is ready.

Now, you can use following command:

    php symfony do:test
