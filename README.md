
server
=======

This server Api will provide to the client to :

- Register.
- Logging in.
- Fetch a list of shops .
- Like or dislike a shop.
- Fetch a list of Liked shops.

# Installation

### Clone the project using git command line : 

```command
$ git clone https://github.com/RecanN/server.git
```

### configure your database : 

```yaml
# app/config/parameters.yml
parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: database_name
    database_user: user
    database_password: password
```

### install dependencies via composer 
I used 3 bundles in the project :
LexikJWTAuthenticationBundle : for JWT generation, FOSUserBundle : for user management and JmsSerializer for serializer services.
at root project
```command
$ composer.phar install
```

### Create database and update schema :
```
$ bin/console doctrine:database:create
$ bin/console doctrine:schema:update --force
```

### JWT configuration :
Generate the SSH keys : 
```
$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```
Configure the SSH keys path in your config.yml :
```yaml
# app/config/config.yml
...
lexik_jwt_authentication:
    private_key_path: '%jwt_private_key_path%'
    public_key_path:  '%jwt_public_key_path%'
    pass_phrase:      '%jwt_key_pass_phrase%'
    token_ttl:        '%jwt_token_ttl%'
```  
Configure your parameters.dist :
```yaml
# app/config/parameters.yml
...
parameters:
    jwt_private_key_path: '%kernel.root_dir%/../var/jwt/private.pem' # ssh private key path
    jwt_public_key_path:  '%kernel.root_dir%/../var/jwt/public.pem'  # ssh public key path
    jwt_key_pass_phrase:  ''                                         # ssh key pass phrase
    jwt_token_ttl:        3600                                       # 3600 sec = 1 hour
```  

See also the documentation for [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

A Symfony project created on January 31, 2018, 6:18 pm.
