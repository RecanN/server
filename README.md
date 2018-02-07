server
======

This server Api will provide to the client to :

  Register.
  Logging in.
  Fetch a list of shops.
  Like or dislike a shop.
  Fetch a list of Liked shops.

Installation
============

Clone the project using the line commande: 
  
  $ git clone https://github.com/RecanN/server.git
  
Configure your database in "parameters.yml":
  
  # app/config/parameters.yml
  parameters:
      database_host: 127.0.0.1
      database_port: null
      database_name: database_name
      database_user: user
      database_password: password
      
Install depencies via composer:
  
  $ composer install

Create and Update schema:
  
  $ php bin/console doctrine:database:create
  $ php bin/console doctrine:database:update --force
  
Generate the SSH keys for JWT :

$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem


A Symfony project created on January 31, 2018, 6:18 pm.
