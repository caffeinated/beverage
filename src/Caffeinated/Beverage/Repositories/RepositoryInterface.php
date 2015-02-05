<?php
namespace Caffeinated\Beverage\Repositories;

interface RepositoryInterface
{
	// Common CRUD methods
	public function getAll($orderBy = array('id', 'asc'));
	public function getAllPaginated($orderBy = array('id', 'asc'), $perPage = 25);
	public function find($id);
	public function store($request);
	public function update($id, $request);
	public function delete($id);

	// Additional candy methods
	public function dropdown($name, $value);
}