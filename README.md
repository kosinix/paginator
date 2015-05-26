Paginator
=========

A generic PHP class to split large data into smaller chunks for use in web apps

## Requirements

- PHP >= 5.3.3

## Usage
Include the class and pass the required parameters

```php
require_once 'src/Kosinix/Paginator.php';

$total = 23; // This will come from your app. Eg. do an SQL count: 'SELECT COUNT(*) AS `total` FROM user'
$current_page = 2; // This will come from your app. Eg. $current_page = $_GET['page'];
$per_page = 10; // This will also come from your app. 

$paginator = new \Kosinix\Paginator($total, $current_page, $per_page);

$sql = sprintf('SELECT * FROM users LIMIT %d,%d', $paginator->get_start_index(), $paginator->get_per_page());

// Run sql query here
```


The constructor accepts the following parameters:

- **total** - The total number of records.
- **current_page** - The current page to display. Defaults to 1.
- **per_page** - The number of records in a page. Defaults to 10. 
- **pages_width** - The number of pages to show on left and right of the current page. Defaults to null. null will display all pages. This is useful when there are too many pages.

Terms are best explained by this image

![alt tag](info.jpg)


## Test

- Go to the project folder and run phpunit in the command line.
- You need to have phpunit installed globally.

## License

- MIT

