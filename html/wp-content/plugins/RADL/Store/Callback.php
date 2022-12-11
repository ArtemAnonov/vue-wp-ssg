<?php

namespace RADL\Store;

use Exception;

class Callback implements Value
{
    /**
     * Identifies callback
     * @var callable
     */
    private $callable;
    /**
     * If callback has already been called
     * @var boolean
     */
    private $called = false;
    /**
     * Callback output
     * @var mixed
     */
    public $returned;

    public function __construct( callable $callable )
    {
        $this->callable = $callable;
    }

    private function call( array $args = array() )
    {
        $this->returned = call_user_func_array( $this->callable, $args );
        $this->called = true;
    }

    public function get($args, $key = null)
    {
        if ( !$this->called ) {
            $this->call( $args );
        }
        return $this->returned;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function render()
    {
        if ( !$this->called )  $this->call();
        return $this; //->returned
    }

    public function get_all_by_one_prop($key, $value)
    {
        new Exception('Метод не определён');
    }

    public function get_all($params)
    {
        if ( !$this->called ) $this->call( $params );
        return $this->returned;
    }

    public function get_by_id($key, $value)
    {
        new Exception('Метод не определён');
    }

    
}
