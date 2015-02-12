<?php
namespace Caffeinated\Beverage\Repositories;

use Illuminate\Contracts\Container\Container;

abstract class AbstractEloquentRepository implements RepositoryInterface
{
	/**
	 * @var Container
	 */
	protected $app;

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @var array
	 */
	protected $withRelationships = [];

	/**
	 * Constructor method.
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;

		$this->loadModel();
	}

	/**
	 * Instantiate the model class.
	 *
	 * @return null
	 */
	protected function loadModel()
	{
		$this->model = $this->app->make($this->namespace);
	}

	protected function newQuery()
	{
		$query = $this->model->newQuery();

		foreach ($this->withRelationships as $relationship) {
			$query->with($relationship);
		}

		return $query;
	}

	public function with($relationship)
	{
		if (! in_array($relationship, $this->withRelationships)) {
			$this->withRelationships[] = $relationship;
		}

		return $this;
	}

	/**
	 * Get all resources.
	 *
	 * @param  array $orderBy
	 * @return mixed
	 */
	public function getAll($orderBy = array('id', 'asc'))
	{
		list($column, $order) = $orderBy;

		return $this->newQuery()->orderBy($column, $order)->get();
	}

	/**
	 * Get all resources.
	 *
	 * @param  array $orderBy
	 * @return mixed
	 */
	public function getAllPaginated($orderBy = array('id', 'asc'), $perPage = 25)
	{
		list($column, $order) = $orderBy;

		return $this->newQuery()->orderBy($column, $order)->paginate($perPage);
	}

	/**
	 * Find a resource by ID.
	 *
	 * @param  int $id
	 * @return mixed
	 */
	public function find($id)
	{
		return $this->newQuery()->find($id);
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