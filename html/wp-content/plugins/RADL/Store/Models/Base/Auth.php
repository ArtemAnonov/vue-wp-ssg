<?php

namespace RADL\Store\Models\Base;
use RADL\Store\Models\BaseModel;

class Auth extends BaseModel
{
  public $route_base = 'auth';

  public $type = 'auth';

  public $apiType = '/jwt-auth/v1/';
}
