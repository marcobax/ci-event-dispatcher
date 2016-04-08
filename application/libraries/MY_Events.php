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
        // checks event to trigger
        if (strpos($event, '@') !== false) {
            // creates event name from class/method combo
            $event_name = strtolower(str_replace('@', '_', $event));

            // registers event and triggers on the fly
            if ($this->register($event_name, $event)) {
                return $this->trigger($event_name, $args);
            }

            return null;
        } elseif (! isset($this->listeners[$event])) {
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
        // normalizes class name
        $model_class = ucfirst(strtolower($class));
        $lib_class = strtolower($class);

        // checks if class is already loaded
        if (! isset($this->CI->$model_class) && ! isset($this->CI->$lib_class)) {
            // tries to load a model
            try {
                $this->CI->load->model("{$model_class}_model", $model_class, true);
            } catch (Exception $e) {}

            // tries to load a library
            try {
                $this->CI->load->library($lib_class);
            } catch (Exception $e) {}

            // now is the class loaded? Last call!
            if (! isset($this->CI->$model_class) && ! isset($this->CI->$lib_class)) {
                return false;
            }
        }

        // executes class method
        return isset($this->CI->$model_class) ? $this->CI->$model_class->$method($args) : $this->CI->$lib_class->$method($args);
    }
}
