<?php
namespace Caffeinated\Beverage\Repositories;

abstract class EloquentRepository implements Repository
{
	/**
	 * @var Model $model
	 */
	protected $model;

	/**
	 * @var string $namespace
	 */
	protected $namespace;

	/**
	 * Constructor method.
	 */
	public function __construct()
	{
		$this->loadModel();
	}

	/**
	 * Instantiate the model class.
	 *
	 * @return null
	 */
	protected function loadModel()
	{
		if (class_exists) {
			$this->model = new $this->namespace;
		}
	}

	/**
	 * Get all resources.
	 *
	 * @param  array $orderBy
	 * @return mixed
	 */
	public function getAll(array $orderBy = array('id', 'asc'))
	{
		lists($column, $order) = $orderBy;

		return $this->model->orderBy($column, $order)->get();
	}

	/**
	 * Get all resources.
	 *
	 * @param  array $orderBy
	 * @return mixed
	 */
	public function getAllPaginated(array $orderBy = array('id', 'asc'), $perPage = 25)
	{
		lists($column, $order) = $orderBy;

		return $this->model->orderBy($column, $order)->paginate($perPage);
	}

	/**
	 * Find a resource by ID.
	 *
	 * @param  int $id
	 * @return mixed
	 */
	public function find($id)
	{
		return $this->model->find($id);
	}

	/**
	 * Store a new resource.
	 *
	 * @param  mixed $request
	 * @return mixed
	 */
	public function store($request)
	{
		return $this->model->create($request);
	}

	/**
	 * Update an existing resource.
	 *
	 * @param  int   $id
	 * @param  mixed $request
	 * @return mixed
	 */
	public function update($id, $request)
	{
		return $this->find($id)->update($request);
	}

	/**
	 * Find and delete a resource by ID.
	 *
	 * @param  int $id
	 * @return bool
	 */
	public function delete($id)
	{
		return $this->model->destroy($id);
	}

	/**
	 * Returns an array suitable for dropdowns.
	 *
	 * @return array
	 */
	public function dropdown($name, $value)
	{
		return $this->model->lists($name, $value);
	}
}