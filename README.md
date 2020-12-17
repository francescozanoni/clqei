# CLQEI [![Build Status](https://travis-ci.org/francescozanoni/clqei.svg?branch=master)](https://travis-ci.org/francescozanoni/clqei) [![Maintainability](https://api.codeclimate.com/v1/badges/f5aca4caee1adc796924/maintainability)](https://codeclimate.com/github/francescozanoni/clqei/maintainability) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/de2b5d0b4f8f49aba6ea695f3f20ad07)](https://www.codacy.com/app/francescozanoni/clqei?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=francescozanoni/clqei&amp;utm_campaign=Badge_Grade) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/francescozanoni/clqei/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/francescozanoni/clqei/?branch=master)


The **C**linical **L**earning **Q**uality **E**nvironment **I**nventory is a validated instrument that measures the clinical learning quality as experienced by nursing students, according to five factors:

- quality of the tutorial strategies,
- learning opportunities,
- safety and nursing care quality,
- self-direct learning,
- quality of the learning environment.

More details at URL https://www.ncbi.nlm.nih.gov/m/pubmed/28398391/ .


### Installation

#### Dependency installation

```bash
composer install
npm install
```

#### Set up

```bash
# To use default URL http://localhost:8000
php scripts/setup.php

# To use custom URL
# php scripts/setup.php --application_url=<APPLICATION_URL>

# To add phpLiteAdmin, reachable at URL <APPLICATION_URL>/phpliteadmin/phpliteadmin.php
# php scripts/setup.php --with_phpliteadmin

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

### Uninstallation

#### Dependency uninstallation

```bash
rm -rf vendor
rm -rf node_modules
```

#### Set down

```bash
php scripts/setdown.php
```

### Update

#### Dependency update

```bash
composer update
npm update
npm audit fix
npm audit fix --force # required if fix requires breaking changes
npm update
npm run production
npm run production # required if development dependencies have been installed by previous step
```

### Local deployment

    php artisan serve
