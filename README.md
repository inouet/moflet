Moflet
======

Moflet is a Simple and Fast PHP framework.


Installation
------

### Setup composer

    $ cd /www/HOST/     # Project Directory
    $ curl -s https://getcomposer.org/installer | php


### Create composer.json 

    $ vi composer.json 
    
    {
        "require": {
            "moflet/moflet": "dev-master"
         }
    }

### Install Moflet

    $ php composer.phar install

### Setup Project

    $ php vendor/moflet/moflet/bin/mof-init


### Apache configuration

    $ vi httpd.conf

    <VirtualHost *:80>
        DocumentRoot /www/HOST/public/
        ServerName HOST
        ErrorLog  logs/HOST.error_log
        CustomLog logs/HOST.access_log common

        <Directory /www/HOST/public/>
            RewriteEngine on
            RewriteBase /
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond $1 !^(index\.php|images|robots\.txt|favicon\.ico)
            RewriteRule ^(.*)$ /index.php/$1 [L]
        </Directory>
    </VirtualHost>
