Paginator
=========

PHP class to split large data into smaller chunks for use in web apps

## Requirements

- PHP >= 5.3.3

Main class will work with 5.2 if not using namespaces.


## Usage
Include the class

```php
require_once 'src/Crispin/Paginator.php';

$total = 100; // This will come from your app. Eg. do an SQL count.
$current_page = 1 // This will come from your app. Eg. $current_page = $_GET['page'];

$paginator = new \Crispin\Paginator($total, $current_page);

$sql = sprintf('SELECT * FROM users LIMIT %d,%d', $paginator->get_start_index(), $paginator->get_per_page());

// Do sql query here
```

## Test

- Just run phpunit in the project folder.
- You need to have phpunit installed.