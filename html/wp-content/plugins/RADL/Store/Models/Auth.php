<?php

namespace RADL\Store\Models;

class Auth extends BasedModels
{
  public $route_base = 'auth';

  public $type = 'auth';

  public $apiType = '/jwt-auth/v1/';

  public $specific_params = [];
}
