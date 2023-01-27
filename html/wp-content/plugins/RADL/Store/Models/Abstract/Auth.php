<?php

namespace RADL\Store\Models\Abstract;
use RADL\Store\Models\AbstractModel;

class Auth extends AbstractModel
{
  public $route_base = 'auth';

  public $type = 'auth';

  public $apiType = '/jwt-auth/v1/';
}
