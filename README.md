# Flintlock Scheduler

### Creating new users
```php
$u = new User;
$u->name = "";
$u->email = "";
$u->admin = true;
$u->password = Hash::make('password'); // you may need to import something for this
$u->save();
```

### Copying files
```sh
scp local.txt root@isaacswift.com:/var/www/remote.txt
```