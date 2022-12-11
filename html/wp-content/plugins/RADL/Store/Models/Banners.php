<?php

namespace RADL\Store\Models;

class Banners extends BasedModels
{
	public $route_base = 'banners';

	public $type = 'banners';

	public $apiType = '/wp/v2/';

	public $specific_params = [
		"per_page" => 10,
		"banner_categories" => ''
	];
}
