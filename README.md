# CLQEI

Clinical Learning Quality Environment Inventory


### Installation

    composer install
    
    php install.php
    
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    
#### Manual steps

- add stage locations
- add stage wards
- add non-student users


### Local deployment

    php artisan serve


### Production deployment

Change password of user administrator@example.com.


### Test

    cp phpunit.xml.example phpunit.xml
