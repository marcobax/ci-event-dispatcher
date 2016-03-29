# CodeIgniter Simple Event Dispatcher
A simple event dispatcher for CodeIgniter v3.x.

## Installation
Step 1. Copy the files to their respective folders.

Step 2. Update `application\config\autoload.php` as follows:

    $autoload['libraries'] = array('events');

    $autoload['helper'] = array('events');

    $autoload['config'] = array('events');

Step 3. Use it!

## Notes
After successfully follow the steps above, you're able to use the Event dispatcher anywhere thanks to the helper functions that are being autoloaded.

The `application\config\events.php` file allows you to globally define a listener for your project (for a log system, for example). See file's DocBlocks for specific examples.

Example to trigger an event:

    eventsTrigger('my_awesome_event', ['argument_1' => 'value_of_argument']);

If you have any question, feel free to follow me on Twitter: @josepostiga

## License
Released under the MIT license.
