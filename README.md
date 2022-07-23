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

### Copying files
```sh
scp local.txt root@isaacswift.com:/var/www/remote.txt
```

### Initial setup
* `php artisan migrate:fresh`
* create new user as above
* Create other users in admin panel
* Create programs in admin, or run ProgramSeeder
* Create default sessions in admin, or run DefaultSessionSeeder
* Create weeks in admin
* Import scouts wk1
* plan wk1
