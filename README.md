## Install package

```
composer require pietrantonio/nova-mail-manager
```

## Database

```
php artisan migrate
```

## File manager

```
php artisan vendor:publish --tag=lfm_config
php artisan vendor:publish --tag=lfm_public
```