<?php
namespace Caffeinated\Beverage\Contracts;

interface Validable
{
	/**
	 * Returns if the validation passed or not.
	 *
	 * @return bool
	 */
	public function passes();

	/**
	 * Returns all validation errors.
	 *
	 * @return \Illuminate\Support\MessageBag
	 */
	public function errors();

	/**
	 * Passes along data with instance.
	 *
	 * @param  array  $data
	 * @return $this
	 */
	public function with(array $data);
}