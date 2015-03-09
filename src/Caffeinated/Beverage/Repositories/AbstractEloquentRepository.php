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
	 *
	 * @param Container  $app
	 */
	public function __construct(Container $app)
	{
		$this->app = $app;

		$this->loadModel();
	}

	/*
	|--------------------------------------------------------------------------
	| Common CRUD methods
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Find and delete a resource by ID.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id)
	{
		return $this->model->destroy($id);
	}

	/**
	 * Find a resource by ID.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function find($id)
	{
		return $this->newQuery()->find($id);
	}

	/**
	 * Find a resource by its slug.
	 *
	 * @param  string  $slug
	 * @return mixed
	 */
	public function findBySlug($slug)
	{
		return $this->newQuery()->where('slug', $slug)->firstOrFail();
	}

	/**
	 * Get all resources.
	 *
	 * @param  array  $orderBy
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
	 * @param  array  $orderBy
	 * @return mixed
	 */
	public function getAllPaginated($orderBy = array('id', 'asc'), $perPage = 25)
	{
		list($column, $order) = $orderBy;

		return $this->newQuery()->orderBy($column, $order)->paginate($perPage);
	}

	/**
	 * Store a new resource.
	 *
	 * @param  mixed  $request
	 * @return mixed
	 */
	public function store($request)
	{
		return $this->model->create($request);
	}

	/**
	 * Update an existing resource.
	 *
	 * @param  int    $id
	 * @param  mixed  $request
	 * @return mixed
	 */
	public function update($id, $request)
	{
		return $this->find($id)->update($request);
	}

	/**
	 * Assign eager loading relationships.
	 *
	 * @param  string|array  $relationships
	 * @return AbstractEloquentRepository
	 */
	public function with($relationships)
	{
		if (! is_array($relationships)) {
			$relationships = explode(', ', $relationships);
		}

		if (! in_array($relationships, $this->withRelationships)) {
			foreach ($relationships as $with) {
				$this->withRelationships[] = $with;
			}
		}

		return $this;
	}

	/*
	|--------------------------------------------------------------------------
	| Additional Candy Methods
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Returns an array suitable for dropdowns.
	 *
	 * @return array
	 */
	public function dropdown($name, $value)
	{
		return $this->model->lists($name, $value);
	}

	/*
	|--------------------------------------------------------------------------
	| Protected Helper Methods
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * Instantiate the model class.
	 *
	 * @return null
	 */
	protected function loadModel()
	{
		$this->model = $this->app->make($this->namespace);
	}

	/**
	 * Create a new newQuery instance with eager loaded relationships.
	 *
	 * @return newQuery
	 */
	protected function newQuery()
	{
		$query = $this->model->newQuery();

		foreach ($this->withRelationships as $relationship) {
			$query->with($relationship);
		}

		return $query;
	}
}
