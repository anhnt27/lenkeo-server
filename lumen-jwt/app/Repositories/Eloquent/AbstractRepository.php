<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AbstractInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Validator;

abstract class AbstractRepository implements AbstractInterface
{
    /**
     * The const cache for 1 month.
     *
     * @var int: 60 * 60 * 24 * 30 => 1 month
     */
    const CACHE_MONTH = 2592000;

    /**
     * The model to execute queries on.
     *
     * @var \Model
     */
    protected $model;
    /**
     * Do Cache.
     *
     * @var \boolean
     */
    protected $doCache = false;

    /**
     * The model to execute queries on.
     *
     * @var \Model
     */
    protected $table;

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Set to an array of the filters.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * The current sort field and direction
     *
     * @var array
     */
    protected $currentSort = ['id', 'ASC'];

    /**
     * The current page number & items per page
     *
     * @var array
     */
    protected $currentPage = [1, 20];

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Validation error
     *
     * @var string
     */
    public $errors = [];

    /**
     * Custom messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Total records
     *
     * @var integer
     */
    public $total = 0;

    /**
     * Get all errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    public function setErrors($errors)
    {
        if ($this->errors instanceof MessageBag) {
            return $this->errors->merge($errors);
        }

        return $this->errors = new MessageBag($errors);
    }

    /**
     * Instantiate a new repository instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->table = $this->model->getTable();

        /**
         * Get the table column names
         */
        $this->getColumns();

        $this->filters = array_merge($this->columns, ['keyword' => '']);
        $this->filters = array_fill_keys(array_keys($this->filters), '');
    }

    /**
     * Instantiate a new model instance.
     *
     * @param  array $attributes
     *
     * @return \App\Models\Model
     */
    public function getNew(array $attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Save new model
     *
     * @param array $data
     *
     * @return \App\Models\Model
     */
    public function save(array $data = [])
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = $this->findById($data['id']);
        } else {
            $model = $this->getNew();
        }

        $this->populateModelAttribute($model, $data);
        /**
         * Save into database
         */
        $model->save();

        return $model;
    }

    /**
     * Find all model entity.
     *
     * @return \App\Models\Model
     */
    public function findAll()
    {
        if (!$this->doCache) {
            return $this->model->all();
        }

        $this->doCache = false;

        return Cache::remember('find_all_' . $this->table, static::CACHE_MONTH, function () {
            return $this->model->all();
        });
    }

    /**
     * Find all model entity.
     *
     * @param  string $name
     * @param  string $id
     *
     * @return \App\Models\Model
     */
    public function listAll($name = 'name', $id = 'id')
    {
        $lists = [];

        if (!$this->doCache) {
            $lists = $this->model->pluck($name, $id)->all();
        }

        $this->doCache = false;

        $lists = Cache::remember('list_all_' . $this->table, static::CACHE_MONTH, function () use ($name, $id) {
            return $this->model->pluck($name, $id)->all();
        });

        $returns = [];

        foreach ($lists as $key => $value) {
            $returns[] = [$id => $key, $name => $value];
        }

        return $returns;
    }

    /**
     * Find all model entity with pagination.
     *
     * @param  array             $filters
     * @param  \App\Models\Model $query
     *
     * @return \App\Models\Model
     */
    public function paginate($filters = [], $query = null)
    {
        $this->sortBy($filters);

        list($sort, $order) = $this->currentSort;

        if (is_null($query)) {
            $query = $this->model;
        }

        if (is_array($filters)) {

            foreach ($filters as $key => $value) {
                if ($key == 'keyword' && !empty($value)) {

                    $keywords = $this->columns;

                    if (isset($this->model->searchColumns)) {
                        $keywords = array_flip($this->model->searchColumns);
                    }

                    $query = $query->where(function ($query) use ($keywords, $value) {
                        foreach ($keywords as $field => $key) {
                            $query->orWhere($this->table . '.' . $field, 'LIKE', '%' . $value . '%');
                        }
                    });
                    continue;
                }

                if (!isset($this->filters[$key]) || empty($value) || $value == -1) {
                    continue;
                }

                $query = $query->where($this->table . '.' . $key, $value);
            }
        }

        $query = $query->orderBy($sort, $order);

        if ($this->currentPage[1]) {
            $results = $query->paginate($this->currentPage[1]);
        } else {
            $results = $query->get();
        }

        $items = [
            'count'             => $results->count(),
            'current_page'      => $results->currentPage(),
            'first_item'        => $results->firstItem(),
            'has_more_pages'    => $results->hasMorePages(),
            'last_item'         => $results->lastItem(),
            'last_page'         => $results->lastPage(),
            'next_page_url'     => $results->nextPageUrl(),
            'per_page'          => $this->currentPage[1],
            'previous_page_url' => $results->previousPageUrl(),
            'total'             => $results->total(),
            'url'               => $results->url($results->currentPage()),
            'items'             => $results->items()
        ];

        return $items;
    }

    /**
     * Find a record by id.
     *
     * @param  mixed $id
     *
     * @return \App\Models\Model
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a record by field.
     *
     * @param  array  $whereClause
     * @param  string $select
     *
     * @return \App\Models\Model
     */
    public function findOneByFields(array $whereClause = null, $select = '*')
    {
        if (is_null($whereClause)) {
            return null;
        }

        return $this->model->select(explode(', ', $select))->where($whereClause)->first();
    }

    /**
     * Find all records by field.
     *
     * @param  array  $whereClause
     * @param  string $select
     *
     * @return \App\Models\Model
     */
    public function findByFields(array $whereClause = null, $select = '*')
    {
        if (is_null($whereClause)) {
            return null;
        }

        return $this->model->select($select)->where($whereClause[0], $whereClause[1], $whereClause[2]);
    }

    /**
     * Delete the specified record from the database.
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function delete($id)
    {
        $model = $this->findById($id);
        $model->delete();
    }

    /**
     * Delete all records by field.
     *
     * @param  array $whereClause
     *
     * @return \App\Models\Model
     *
     */
    public function deleteWhere(array $whereClause = null)
    {
        if (is_null($whereClause)) {
            return null;
        }

        return $this->model->where($whereClause)->delete();
    }

    /**
     * Delete the specified cache.
     *
     * @param  mixed $key
     *
     * @return void
     */
    public function deleteCache($key)
    {
        Cache::forget($key);
    }

    /**
     * Add a custom sort column
     *
     * @param sring $column
     *
     * @return void
     */
    protected function addSortColumn($column)
    {
        $this->columns[$column] = 1;
    }

    /**
     * Sets how the results are sorted
     *
     * @param array $filters The field being sorted
     *
     * @return EloquenRepository The current instance
     */
    protected function sortBy($filters)
    {
        $sort = $this->currentSort[0];
        $order = $this->currentSort[1];

        if (isset($filters['sort'])) {
            $sort = $filters['sort'];
        }

        if (!isset($this->columns[$sort])) {
            $sort = $this->currentSort[0];
        }

        if (strpos($sort, '.') === true) {
            $sort = $this->table . '.' . $sort;
        }

        if (isset($filters['order'])) {
            $order = $filters['order'];
        }

        $order = (strtoupper($order) == 'ASC') ? 'ASC' : 'DESC';
        $this->currentSort = [$sort, $order];

        return $this;
    }

    /**
     * Set the current page & items per page
     *
     * @param array $filters
     *
     * @return void
     */
    public function setPage(array $data)
    {
        list($page, $itemsPerPage) = $this->currentPage;

        if (isset($data['page'])) {
            $page = $data['page'];
        }

        if (isset($data['items'])) {
            $itemsPerPage = $data['items'];

            // ensure only a maximum of 100 records can be retrieved
            if ($itemsPerPage > 100) {
                $itemsPerPage = 100;
            }
        }

        $this->currentPage = [$page, $itemsPerPage];
    }

    /**
     * Get the filter values.
     */
    public function getFilters($filters)
    {
        $nFilters = $this->getRawFilters($filters);

        return array_replace($this->filters, $nFilters);
    }

    /**
     * Get the raw filter values.
     */
    public function getRawFilters($filters, $depend = true)
    {
        if (!isset($filters['filters'])) {
            return $depend == true ? array_replace($this->filters, []) : [];
        }

        $inputs = explode('--', $filters['filters']);
        $filters = [];

        foreach ($inputs as $key => $value) {
            $values = explode('-', $value);

            if (!isset($values[1])) {
                continue;
            }

            $filters[$values[0]] = trim(str_replace('+', ' ', $values[1]));
        }

        return $filters;
    }

    /**
     * Popular data
     *
     * @param  mixed $model
     * @param  mixed $data
     *
     * @return \App\Models\Model
     */
    protected function populateModelAttribute(&$model, array $data)
    {
        foreach ($data as $key => $value) {
            if (!isset($this->columns[$key])) {
                continue;
            }

            $model->{$key} = $value;
        }
    }

    /**
     * Init data
     *
     * @param  mixed $model
     * @param  mixed $data
     *
     * @return \App\Models\Model
     * @throws Exception
     */
    protected function init()
    {
        $model = $this->getNew();
        $columns = array_fill_keys(array_keys($this->columns), null);
        /**
         * Popular data for entity
         */
        $this->populateModelAttribute($model, $columns);

        return $model;
    }

    /**
     * Get the table columns
     *
     * @return \App\Models\Model
     */
    private function getColumns()
    {
        $this->columns = DB::getSchemaBuilder()->getColumnListing($this->table);
        $this->columns = array_flip($this->columns);
    }

    /**
     * Set the filter values
     *
     * @return \App\Models\Model
     */
    public function setFilters($query, $filters = [])
    {
        if (is_array($filters)) {
            $this->sortBy($filters);

            list($sort, $order) = $this->currentSort;

            foreach ($filters as $key => $value) {

                if (!isset($this->filters[$key]) || empty($value) || $value == -1) {
                    continue;
                }

                $quoted = preg_match('/^(["\']).*\1$/m', $value);

                if ($quoted) {
                    $value = preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $value);
                    $query = $query->where($this->table . '.' . $key, '=', $value);
                } else {
                    $query = $query->where($this->table . '.' . $key, 'LIKE', '%' . $value . '%');
                }
            }
            $query = $query->orderBy($sort, $order);
        }

        return $query;
    }

    /**
     * Count entities in model
     *
     * @return integer
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Find all activated records
     *
     * @return \App\Models\Model
     *
     */
    public function findActivatedItems()
    {
        return $this->model->where('activated', $this->model->getActivatedCode())->get();
    }

    /**
     * Find a record by id.
     *
     * @param  int $id
     *
     * @return \App\Models\Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Validate input data with rules
     *
     * @param  array $data
     *
     * @return boolean
     */
    protected function validate(array $data)
    {
        $validator = validator::make($data, $this->rules(), $this->messages());

        if ($validator->fails()) {
            $this->errors = $validator->errors();

            return false;
        }

        return true;
    }

    /**
     * Get rules
     *
     * @return array
     */
    protected function rules()
    {
        return $this->rules;
    }

    /**
     * Check if there is error happen when validate data.
     *
     * @return boolean
     */
    public function isError()
    {
        return empty($this->errors);
    }

    /**
     * Get message after validate inputs
     *
     * @return string
     */
    public function getMessageValidatedInput()
    {
        $error = [];

        if ($this->isError()) {
            return $error;
        }

        $keys = $this->errors->keys();

        $key = reset($keys);

        $value = $this->errors->get($key);

        $error = [
            'key'   => $key,
            'value' => reset($value)
        ];

        return $error;
    }

    /**
     * Get custom messages.
     *
     * @return array
     */
    protected function messages()
    {
        return $this->messages;
    }

    public function cleanUp($expiredDate)
    {
        $this->model
            ->where('created_at', '<=', $expiredDate)
            ->delete();
    }
}
