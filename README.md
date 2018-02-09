# CLQEI [![Build Status](https://travis-ci.org/francescozanoni/clqei.svg?branch=master)](https://travis-ci.org/francescozanoni/clqei)

Clinical Learning Quality Environment Inventory


### Installation

    composer install
    npm install
    npm run production

    php install.php --application_url=<APPLICATION_URL>

    php artisan key:generate
    php artisan migrate --seed

#### Manual steps

- add
  - real administrators and viewers
  - real stage locations and wards
  - an image file **logo.svg** or **logo.png** or **logo.jpg** into **public** folder
- disable
  - example administrators and viewers
  - example stage locations and wards
- within file **.env** customize
  - students' identification number pattern
  - students' e-mail pattern
  - educational institution URL


### Local deployment

    php artisan serve
