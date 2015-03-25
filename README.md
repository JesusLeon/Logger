# Logger
A practical logging tool based on Monolog.

# Installation
```shell
composer require jesus-leon/logger "dev-master"
```

# Usage

### Basic:
```php
use Logger\Logger;

$logger = new Logger();

$logger->info('Hello World!');
```

### Backtrace:
Logger automatically adds backtrace information to each logging call as a message context. So with each message, you can see something like this:
```
[2015-03-25 11:16:42] Logger.INFO: Hello World! 
{
    "_backtrace_level_0": "/Users/Logger/vendor/jesus-leon/logger/src/Logger/Logger.php:189",
    "_backtrace_level_1": "/Users/Logger/vendor/jesus-leon/logger/src/Logger/Logger.php:104",
    "_backtrace_level_2": "/Users/Logger/hello-world.php:5"
} []
```

You can adjust the backtrace length and offset with the ```setBacktraceLength($length, $offset)``` method:
```php
$logger->setBacktraceLength(1, 2);

$logger->info('Hello again!');
```

And you will see this:
```
[2015-03-25 11:18:01] Logger.INFO: Hello again! 
{
    "_backtrace_level_2": "/Users/Logger/hello-world.php:5"
} []
```

**NOTE:** Logger has a ~~default offset of 2~~ will come in an update :)

### Logger name:
You can set the logger name when constructing the object:
```php
$logger = new Logger('Testing-Logger');

$logger->info('yo, hi... ¬¬');
```

And you will see this:
```
[2015-03-25 11:18:42] Testing-Logger.INFO: yo, hi... ¬¬ 
{...} []
```

# License
The MIT License (MIT) - See LICENSE file within this package.
