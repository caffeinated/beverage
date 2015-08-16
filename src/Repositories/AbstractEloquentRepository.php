<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Repositories;

use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;

/**
 * Abstract Eloquent repository class
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
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
     * @var array
     */
    protected $fireEvents = [];

    /**
     * Constructor method.
     *
     * @param Container  $app
     */
    public function __construct(Container $app, Dispatcher $event)
    {
        $this->app   = $app;
        $this->event = $event;

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
        $this->fireEvent('deleting', [$id]);

        $deleted = $this->model->destroy($id);

        $this->fireEvent('deleted', [$id, $deleted]);

        return $deleted;
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
        return $this->newQuery()->where('slug', $slug)->first();
    }

    /**
     * Get the first resource or fail.
     *
     * @return mixed
     */
    public function firstOrFail()
    {
        return $this->newQuery()->firstOrFail();
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
        $this->fireEvent('creating', [$request]);

        $created = $this->model->create($request);

        $this->fireEvent('created', [$created]);

        return $created;
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
        $this->fireEvent('updating', [$id, $request]);

        $updated = $this->find($id)->update($request);

        $this->fireEvent('updated', [$id, $updated]);

        return $updated;
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

    /**
     * Fire off an event if one is defined.
     *
     * @param  string  $event
     * @param  array   $data
     * @return mixed
     */
    protected function fireEvent($event, $data)
    {
        $fireableEvent = isset($this->fireEvents[$event]) ? $this->fireEvents[$event] : null;

        if (! is_null($fireableEvent)) {
            return $this->event->fire($fireableEvent, $data);
        }

        return null;
    }
}
