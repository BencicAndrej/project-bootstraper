<?php namespace Norm\Services\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractEloquentRepository implements AbstractRepositoryInterface {

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * Alias for finding an entity with specific ID.
	 *
	 * @param int $id
	 * @return \Illuminate\Support\Collection|null|static
	 */
	public function find($id) {
		return $this->findBy('id', $id);
	}

	/**
	 * Find a single entity by key value.
	 *
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @return Model|null|static
	 */
	public function findBy($key, $value, array $with = []) {
		return $this->make($with)->where($key, '=', $value)->first();
	}

	/**
	 * Find many entities by key value.
	 *
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @return Collection|static[]
	 */
	public function getBy($key, $value, array $with = []) {
		return $this->make($with)->where($key, $value)->get();
	}

	/**
	 * Returns table data required for pagination.
	 *
	 * @param int $page
	 * @param int $limit
	 * @param string $orderBy
	 * @param string $orderDirection
	 * @param array $with
	 * @return \StdClass
	 */
	public function getAllByPage($page = 1, $limit = 10, $orderBy = 'id', $orderDirection = 'asc', array $with = []) {
		$result = new \StdClass;
		$result->page = $page;
		$result->limit = $limit;
		$result->totalItems = 0;
		$result->items = [];

		$model = $this->make($with)->orderBy($orderBy, $orderDirection)
			->skip($limit * ($page - 1))->take($limit)->get();

		$result->totalItems = $this->model->count();
		$result->items = $model->all();

		return $result;
	}

	public function has($relation, array $with = []) {
		return $this->make($with)->has($relation)->get();
	}

	/**
	 * Alias for getAll method.
	 *
	 * @param array $with
	 * @return Collection
	 */
	public function all(array $with = []) {
		return $this->getAll($with);
	}

	/**
	 * Return all entries.
	 *
	 * @param array $with
	 * @return Collection
	 */
	public function getAll(array $with = []) {
		return $this->make($with)->all();
	}

	/**
	 * Make a new instance of the entity to query on.
	 *
	 * @param array $with
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function make(array $with = []) {
		return $this->model->with($with);
	}

	/**
	 * Persist a model.
	 *
	 * @param Model $model
	 * @return Model
	 */
	public function save($model) {
		$model->save();

		return $model;
	}
}