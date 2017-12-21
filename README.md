# CLQEI

Clinical Learning Quality Environment Inventory

### Installation
    composer install
    
    cp .env.example .env
    # populate APP_KEY parameter within .env
    # if SQLite is used as database engine, populate DB_DATABASE parameter with the absolute path of the database file
    
    chmod -R 777 storage/*
    chmod -R 777 bootstrap/cache
    
    touch database/database.sqlite
    chmod 777 database/database.sqlite
    
    php artisan migrate

### Local deployment
    cp public/.htaccess.example .htaccess
    php artisan serve
