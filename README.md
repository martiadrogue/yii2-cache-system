Yii 2 Basic Project Template
============================

Sandbox to experiment with Yii2 and caches. I little introduction to a cache's
implementation. It's at BackofficeController::class. To run this website you
must use a VirtualHost like this:

```
<VirtualHost *:80>
        ServerName yii2advancedfront.local
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/yii2/frontend/web

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        <Directory "/var/www/yii2/frontend/web">
            RewriteEngine on
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule . index.php
        </Directory>
</VirtualHost>
```
