<p align="center"><img src="http://marketplace-kit.s3.amazonaws.com/logo.png"></p>

## About MarketplaceKit

MarketplaceKit is a platform for building different types of online marketplaces. MarketplaceKit attempts to reduce the development effort required to build product, rental, service and listing marketplaces such as Etsy, AirBnB, Handy and Zillow. It does this by attempting to cover the main components a marketplace needs, including the following:

- Powerful search across multiple fields, geolocation and custom fields
- Geolocalization for users and listings
- Frontend listing creation and browsing
- User profiles
- Direct messaging between users
- Multilingual functionality



MarketplaceKit uses popular, well documented packages without too much overhead to simplify a developers/designers life. Therefore, the following choices were made:

- Based on the popular Laravel Framework
- Leverages Bootstrap 4 for a responsive and mobile-first theme out of the box
- Separates theming logic from development by using Twig
- Avoids JS frameworks that require compilation
- Uses different widgets for different listing types (coming soon)

MarketplaceKit is easy to customize, change the design and supports multiple languages.



## Server Requirements

- PHP 7.2
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Mbstring PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension
  - ImageMagick PHP Extension
- MySQL 5.7
- Node JS (8.9.4) - this is only required for compiling SCSS to CSS
- Git
- ImageMagick
- Nginx

Although MarketplaceKit has been tested on Ubuntu 16.04 LTS. It should work with any OS that satisfies the above requirements. Nginx (https://laravel.com/docs/5.6/deployment#nginx) is recommended as a webserver. Developers should also have knowledge of Laravel, Bootstrap and Twig for extending/building on top of MarketplaceKit.



## Installation

- Download the code via Git

  ```
  git clone https://github.com/marketplacekit/marketplacekit.git marketplacekit
  cd marketplacekit
  ```

- Install the required packages

  ```
  composer install
  ```

- Install the node modules

  ```
  npm install
  ```

- Create a .env file by copying the .env.example

  ```
  cp .env.example .env
  ```

- Add your database details to the .env file

  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=homestead
  DB_USERNAME=homestead
  DB_PASSWORD=secret
  ```

- Create the database tables by running

  ```
  php artisan migrate
  ```

- Seed the database by running

  ```
  php artisan db:seed
  ```

- Configure your Nginx server block or Apache Vhost to point to the /public folder

  e.g. nginx

  ```
  location / {
      try_files $uri $uri/ /index.php?$query_string;
  }
  ```

  e.g. apache

  ```
  Options +FollowSymLinks
  RewriteEngine On
  
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^ index.php [L]
  ```

- Visit your domain login and change the default password

  ```
  Default username: admin
  Default password: changeme
  ```

  

### Facebook login

In order for your users to login via Facebook you need to register for a Facebook key.

- Go to https://developers.facebook.com and register for a developer account.
- Create a Facebook app via https://developers.facebook.com/apps. Instructions for creating a Facebook application can be found here: https://developers.facebook.com/docs/apps/register.
- On the "Product Setup" page, click the Dashboard link on the left-hand side.
- Save the App ID and App Secret values so you can add them to the MarketplaceKit panel



### Google Maps Keys

MarketplaceKit relies on Google Maps for geolocalized searches. Please visit https://developers.google.com/maps/documentation/javascript/get-api-key to generate  your key. You can then add this in the admin panel.



## License

MarketplaceKit Lite is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).