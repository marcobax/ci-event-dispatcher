<?php

defined("BASEPATH") or die("No direct access to file");

class MY_Events
{
    /**
     * CodeIgniter instance.
     *
     * @var object
     */
    protected $CI;

    /**
     * Event listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * Initiates class and loads dependencies.
     */
    public function __construct()
    {
        // gets app instance
        $this->CI =& get_instance();

        // sets base listeners from config file
        $this->listeners = $this->CI->config->item('listeners');
    }

    /**
     * Registers a new event listener.
     *
     * @param string $event
     * @param string $class
     * @return bool
     */
    public function register($event, $class)
    {
        // checks if event name already exists
        if (isset($this->listeners[$event])) {
            return false;
        }

        // adds model event to listeners array
        $this->listeners[$event] = $class;

        return true;
    }

    /**
     * Prepares registered event for execution.
     *
     * @param string $event
     * @param array $args
     * @return mixed
     */
    public function trigger($event, $args = [])
    {
        // checks if event exists
        if (! isset($this->listeners[$event])) {
            return null;
        }

        // checks if the event matches an array of listeners
        if (is_array($this->listeners[$event])) {
            foreach ($this->listeners[$event] as $listener) {
                // gets handler class/method combo
                $handler = explode('@', $listener);

                // executes
                $this->propagate($handler[0], $handler[1], $args);
            }

            // terminates execution
            return;
        }

        // gets handler class/method combo
        $handler = explode('@', $this->listeners[$event]);

        // executes
        return $this->propagate($handler[0], $handler[1], $args);
    }

    /**
     * Executes registered event.
     *
     * @param string $class
     * @param string $method
     * @param array $args
     * @return mixed
     */
    protected final function propagate($class, $method = 'index', $args = [])
    {
        // checks if class is already loaded
        if (! $this->CI->load->is_loaded($class)) {
            // checks for a model
            if (file_exists(APPPATH."models/{}$class}_model.php")) {
                $this->CI->load->model("{$class}_model", $class, true);
            }

            // checks for a library
            if (file_exists(APPPATH."libraries/{$class}.php")) {
                $this->CI->load->library($class);
            }
        }

        // executes class method
        return $this->CI->{$class}->{$method}($args);
    }
}
