<?php
namespace Caffeinated\Beverage\Models;

use Config;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
	public function __call($method, $parameters)
	{
		$className = class_basename($this);

		$config = implode('.', ['relationship', $className, $method]);

		if (Config::has($config)) {
			$function = Config::get($config);

			return $function($this);
		}

		return parent::__call($method, $parameters);
	}
}