<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Repositories;

/**
 * Base Repository contract
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
interface RepositoryInterface
{
    /*
	|--------------------------------------------------------------------------
	| Common CRUD methods
	|--------------------------------------------------------------------------
	|
	*/

    public function delete($id);
    public function find($id);
    public function findBySlug($slug);
    public function getAll($orderBy = array('id', 'asc'));
    public function getAllPaginated($orderBy = array('id', 'asc'), $perPage = 25);
    public function store($request);
    public function update($id, $request);
    public function with($relationships);

    /*
	|--------------------------------------------------------------------------
	| Additional Candy Methods
	|--------------------------------------------------------------------------
	|
	*/

    public function dropdown($name, $value);
}
