<?php namespace Norm\Services\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryInterface {

	/**
	 * Alias for finding an entity with specific ID.
	 *
	 * @param int $id
	 * @return \Illuminate\Support\Collection|null|static
	 */
	public function find($id);

	/**
	 * Find a single entity by key value.
	 *
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @return Model|null|static
	 */
	public function findBy($key, $value, array $with = []);

	/**
	 * Find many entities by key value.
	 *
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @return Collection|static[]
	 */
	public function getBy($key, $value, array $with = []);

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
	public function getAllByPage($page = 1, $limit = 10, $orderBy = 'id', $orderDirection = 'asc', array $with = []);

	public function has($relation, array $with = []);

	/**
	 * Alias for getAll method.
	 *
	 * @param array $with
	 * @return Collection
	 */
	public function all(array $with = []);

	/**
	 * Return all entries.
	 *
	 * @param array $with
	 * @return Collection
	 */
	public function getAll(array $with = []);

	/**
	 * Make a new instance of the entity to query on.
	 *
	 * @param array $with
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function make(array $with = []);

	/**
	 * Persist a model.
	 *
	 * @param Model $model
	 * @return Model
	 */
	public function save($model);

}