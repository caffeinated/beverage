<?php
namespace Caffeinated\Beverage\Repositories;

interface Repository
{
	// Common CRUD methods
	public function getAll(array $orderBy = array('id', 'asc'));
	public function getAllPaginated(array $orderBy = array('id', 'asc'), $perPage = 25);
	public function find($id);
	public function store(array $request);
	public function update($id, array $request);
	public function delete($id);

	// Additional candy methods
	public function dropdown($name, $value);
}