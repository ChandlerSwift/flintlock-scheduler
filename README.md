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
