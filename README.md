
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
Config your own passphrase and ttl (for token expiration)
```yaml
# app/config/config.yml
...
parameters:
    locale: en
    jwt_passphrase: passphrase
    jwt_token_ttl: 36000 #in seconds
```  
Generate the SSH keys : 
```
$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```

See also the documentation for [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

A Symfony project created on January 31, 2018, 6:18 pm.
