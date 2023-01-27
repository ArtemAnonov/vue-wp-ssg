<?php

namespace RADL\Store\Models\WP;
use RADL\Store\Models\ModelWP;

class Banners extends ModelWP
{
	public $route_base = 'banners';

	public $type = 'banners';

	public $specific_params = [
		// "per_page" => 10,
		"banner_categories" => ''
	];
}
