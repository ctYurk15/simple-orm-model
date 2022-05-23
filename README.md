# Description

Simple, but usefull ORM class

# Installation

To install this package you need to:
1. Install composer
2. Add line to your composer.json file in project folder:
```
"minimum-stability": "dev"
```
3. Go to your project folder with console
4. Write down next command:
```
composer require ctyurk15/simple-orm-model
```

Done!

# Usage
Here example of usage

```php
//connect class
require __DIR__.'/vendor/autoload.php';
use ctyurk15\SimpleOrmModel\Model;

// create wrapper for database connection data (this is )
// temprorarly solution
class ORMWrapper extends Model
{
    public static $dbdata_path = 'dbdata.json';
}

//create class for your table
class ORM1 extends ORMWrapper
{
    public static $table = 'orm1';
    public static $index_column = 'id';
}

//initialize database connection
ORM1::init_conn();

//use it
$records = ORM1::all();

foreach($records as $record)
{
    echo $record->getId().' - '.$record->get('title').'<br>';
}
```

Database data connection
```json
{
    "host": "localhost",
    "pass": "root",
    "dbname": "testing-packages",
    "user": "root"
}   echo $record->getId().' - '.$record->get('title').'<br>';
```
