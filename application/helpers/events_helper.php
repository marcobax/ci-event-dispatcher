<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (! function_exists('eventsRegister')) {
    /**
     * Registers a new event.
     *
     * @param string $event
     * @param string $class_method
     * @return mixed
     */
    function eventsRegister($event, $class_method)
    {
        return get_instance()->my_events->register($event, $class_method);
    }
}

if (! function_exists('eventsTrigger')) {
    /**
     * Triggers an event.
     *
     * @param string $event
     * @param array $args
     * @return mixed
     */
    function eventsTrigger($event, $args = [])
    {
        return get_instance()->my_events->trigger($event, $args);
    }
}
