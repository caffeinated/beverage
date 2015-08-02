<?php
namespace Caffeinated\Beverage\Traits;

use Exception;
use Caffeinated\Beverage\Contracts\Validable;

/**
 * Validable Service Trait
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
trait ValidableService
{
	/**
	 * @var array
	 */
	protected $validators;

	/**
	 * Get the validators
	 *
	 * @return mixed
	 */
	public function getValidators()
	{
		return $this->validators;
	}

	/**
	 * Sets the validators
	 *
	 * @param  mixed  $validators
	 * @return ValidableService
	 */
	public function setValidators($validators)
	{
		$this->validators = $validators;

		return $this;
	}

	/**
	 * Run the validation checks
	 *
	 * @param  array  $data
	 * @return bool|null
	 * @throws \Exception
	 */
	public function runValidationChecks(array $data)
	{
		foreach ($this->validators as $validator) {
			if ($validator instanceof Validable) {
				if (! $validator->with($data)->passes()) {
					$this->errors = $validator->errors();
				}
			} else {
				throw new Exception("{$validator} is not an instance of Caffeinated\\Beverage\\Contracts\\Validable");
			}
		}

		if ($this->errors->isEmpty()) {
			return true;
		}
	}
}