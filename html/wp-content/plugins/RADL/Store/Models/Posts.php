<?php

namespace RADL\Store\Models;

class Posts extends BasedModels
{
    public $route_base = 'posts';

    public $type = 'posts';

    public $apiType = '/wp/v2/';
    
    public $specific_params = [

    ];
}
