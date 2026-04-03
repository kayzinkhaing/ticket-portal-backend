<?php

namespace App\Repositories;

use App\Contracts\BaseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository implements BaseInterface
{
    protected Model $model;

    /** dropdown / relation metadata */
    protected array $relations = [];

    /** eager loading definitions */
    protected array $eagerLoads = [];

    public function __construct(string $modelName)
    {
        // dd($modelName);//"PermissionRole" // app/Repositories/baseRepository.php:22
        // Instantiate model
        $this->model = app("App\\Models\\{$modelName}");

        // Load config for eager loads / relations
        $config = config('eager.models.' . get_class($this->model), []);

        $this->eagerLoads = $config['eager_load'] ?? [];
        $this->relations  = Arr::except($config, ['eager_load']);
    }

    /* ===============================
       Metadata
       =============================== */

    public function getRelations(): array
    {
        // dd('inside getRelations');
        // dd($this->relations);
        return $this->relations;
    }

    public function getEagerLoads(): array
    {
        return $this->eagerLoads;
    }

    public function getModelInstance(): Model
    {
        // dd($this->model);
        return $this->model;
    }

    /* ===============================
       Queries
       =============================== */

    /**
     * Apply dynamic column selection and eager loads
     */
    protected function applyDefaultSelects($query): void
    {
        // Select main model columns
        if (method_exists($this->model, 'defaultSelectColumns')) {
            $query->select($this->model->defaultSelectColumns());
        }

        // Apply eager loads with configured columns
        if (!empty($this->eagerLoads)) {
            $query->with($this->eagerLoads);
        }
    }

    /**
     * Get all records for a role (optional scope)
     */
    // public function allByRole(string $role)
    // {
    //     $query = $this->model->newQuery();
    //     // dd($query->get());

    //     $this->applyDefaultSelects($query);
    //     return $query->orderBy($this->model->getKeyName(), 'asc')->get();
    // }

    // public function allByRole(string $role)
    // {
    //     $query = $this->model->newQuery();

    //     $this->applyDefaultSelects($query);

    //     // Change ->get() to ->paginate()
    //     // You can pass a number (e.g., 10) to define how many items per page
    //     return $query->orderBy($this->model->getKeyName(), 'asc')->paginate(10);
    // }

    public function allByRole(string $role)
    {
        $query = $this->model->newQuery();
        $this->applyDefaultSelects($query);

        $page = request()->get('page', 1); // use ?page= from URL
        return $query->orderBy($this->model->getKeyName(), 'asc')
            ->paginate(10, ['*'], 'page', $page);
    }

    /**
     * Find by ID with eager loads and selected columns
     */
    public function findById(int $id)
    {
        //dd($id);
        $query = $this->model->newQuery();
        $this->applyDefaultSelects($query);

        return $query->findOrFail($id);
    }

    /**
     * Create new model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update model by ID
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    /**
     * Delete model by ID
     */
    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * Find model by name
     */
    public function findByName(string $name): ?Model
    {
        $query = $this->model->newQuery();
        $this->applyDefaultSelects($query);

        return $query->where('name', $name)->first();
    }
    public function query(): Builder
    {
        return $this->model->newQuery();
    }
}
