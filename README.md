# CLQEI [![Build Status](https://travis-ci.org/francescozanoni/clqei.svg?branch=master)](https://travis-ci.org/francescozanoni/clqei) [![Maintainability](https://api.codeclimate.com/v1/badges/f5aca4caee1adc796924/maintainability)](https://codeclimate.com/github/francescozanoni/clqei/maintainability) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/de2b5d0b4f8f49aba6ea695f3f20ad07)](https://www.codacy.com/app/francescozanoni/clqei?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=francescozanoni/clqei&amp;utm_campaign=Badge_Grade)


Clinical Learning Quality Environment Inventory


### Installation

```bash
composer install
npm install

# To install with default URL http://localhost:8000
php install.php

# To install with custom URL
# php install.php --application_url=<APPLICATION_URL>

# To install with phpLiteAdmin, available at <APPLICATION_URL>/phpliteadmin
# php install.php --with_phpliteadmin

php artisan migrate --seed
```

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
