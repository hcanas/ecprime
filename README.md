

# Installation Guide (Windows)
### Prerequisites
- [Docker Desktop](https://docker.com/products/docker-desktop)
- [WSL2 (Ubuntu 22.04)](https://ubuntu.com/wsl)
- [Composer v2.2.6](https://getcomposer.org/doc/00-intro.md#installation-windows)

### Setup
1. Run Docker Desktop and set WSL distro to Ubuntu 22.04

    ```
    C:\>wsl -s Ubuntu-22.04
    
    C:\>wsl -l
    Windows Subsystem for Linux Distributions:
    Ubuntu-22.04 (default)
    docker-desktop-data
    docker-desktop
    ```
2. Clone the repository into WSL home directory
    ```
    C:\>wsl
    
    cd ~
    
    $ git clone https://github.com/hcanas/ecprime
    ```
3. Install php dependencies
    ```
    $ cd ecprime
    
    $ composer install
    ```
4. Install JS dependencies and build project
    ```
   $ ./vendor/bin/sail up -d
   
   $ ./vendor/bin/sail npm install
   
   $ ./vendor/bin/sail npm run dev
   ```
5. Setup environment variables
   ```
   $ cp .env.example .env
   
   $ ./vendor/bin/sail artisan key:generate
   ```
6. Run migrations
   ```
   $ ./vendor/bin/sail artisan migrate --seed
   ```
7. Setup laravel scout
   ```
   $ ./vendor/bin/sail artisan scout:sync-index-settings
   
   // import models for indexing for Meilisearch
   $ ./vendor/bin/sail artisan scout:import "App\Models\User"
   $ ./vendor/bin/sail artisan scout:import "App\Models\UserActivity"
   $ ./vendor/bin/sail artisan scout:import "App\Models\Product"
   $ ./vendor/bin/sail artisan scout:import "App\Models\Quotation"
   $ ./vendor/bin/sail artisan scout:import "App\Models\PurchaseOrder"
   ```
8. Access your app
   ```
   // browser URL
   http://localhost
   
   // admin user demo account
   email: admin@example.com
   password: password
   
   // regular user demo account
   email: regular@example.com
   password: password
   
   // affiliate user demo account
   email: affiliate@example.com
   password: password
   ```
