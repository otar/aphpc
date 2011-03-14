<?php

include_once 'cache.php';

// Initializing (optional)
$cache = Cache::instance();


// Setting cache with default settings
Cache::instance()->set('foo', 'bar');
// Or
$cache->foo = 'bar';


// Setting cache for 5 minutes (in seconds)
Cache::instance()->set('another_foo', 'Another Value', 300);


// Getting value from the cache with default settings
echo Cache::instance()->get('foo'); // bar
// Or
echo $cache->foo; // bar


// Getting vale from the cache (that's saved for five minutes)
echo Cache::instance()->get('another_foo'); // Another Value


// Deleting item from the cache
Cache::instance()->delete('foo');
// Or
unset($cache->foo);


// Cleaning all data from the cache
Cache::instance()->clean();
// Or
$cache->clean();
