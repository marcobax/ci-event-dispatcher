<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sets global event's listeners.
 *
 * Exemple for simple event listener:
 *      ['event_name' => 'class_name@method_name']
 *
 * Exemple for multiple event listeners:
 *      [
 *          'event_name' => [
 *              'first_class_name@method_name',
 *              'second_class_name@method_name'
 *          ]
 *      ]
 */
$config['listeners'] = [];
