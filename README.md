# Flintlock Scheduler

### Creating an initial admin user
Other users can then be created through the user editor.
Run `php artisan tinker` and:
```php
$u = new User;
$u->name = "";
$u->email = "";
$u->admin = true;
$u->password = Hash::make('password');
$u->save();
```

### Initial setup
* `php artisan migrate:fresh`
* `php artisan db:seed --class=ProgramSeeder`
* `php artisan db:seed --class=DefaultSessionSeeder`
* create new user as above
* Create other users in admin panel
* Create weeks in admin
* Import scouts wk1
* import Tier 2 wk1
* plan wk1

### Local development
After cloning and running the composer install:
```sh
docker run -it --mount type=bind,source=`pwd`,target=/app -p 8000:8000 php:8.2 sh -c "cd /app && php artisan serve --host=0.0.0.0"
```
