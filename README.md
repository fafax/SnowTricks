
<p  align="center">

<img  src="https://www.supinfo.com/articles/resources/143096/1784/0.png"  alt="PHP Logo svg vector"  width="400px">
</p>
 
## Description

SnowTricks was developed in php with use the symfony framework.

## Installation
  
Recover the project

    git clone https://github.com/fafax/SnowTricks.git
    composer install

Create database

Change line DATABASE_URL=mysql:// in .env file with your setting database

    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate

## Stay in touch

- Author - [Fabien HAMAYON](https://www.linkedin.com/in/fabien-hamayon-2b072698/)

- Website - [code assembly dev](http://codeassemblydev.fr/)

- Youtube - [Youtube channel](https://www.youtube.com/channel/UCBB2pQPkS2jmI3LPhUCxYgA)