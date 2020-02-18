
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

## Set data
for example data

    php bin/console doctrine:fixtures:load
    
## To log in for the first time as administrator

click in "Sign in"

- User Name  = admin
- Password = admin

for reset password 

- Log out
- Sign in
- Forgot Password
- User Name = admin
- click in Ask for reset
- User name = admin
- Change with a secure password

## Functionality of the snowtrick project
- Add trick
- Add a comment on a post trick
- Add assets on a post trick
- Trick management
- Asset management
   

## Stay in touch

- Author - [Fabien HAMAYON](https://www.linkedin.com/in/fabien-hamayon-2b072698/)

- Website - [code assembly dev](http://codeassemblydev.fr/)

- Youtube - [Youtube channel](https://www.youtube.com/channel/UCBB2pQPkS2jmI3LPhUCxYgA)