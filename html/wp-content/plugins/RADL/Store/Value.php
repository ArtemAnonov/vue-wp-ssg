<?php

namespace RADL\Store;

interface Value
{
    public function render();
    public function get_all_by_one_prop($key, $value);
    public function get_by_id(array $params, $key);
    public function get_all(array $config);
}
