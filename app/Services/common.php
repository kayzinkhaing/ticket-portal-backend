<?php

namespace App\Services;

use App\Contracts\BaseInterface;
use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{ BelongsTo, BelongsToMany, MorphToMany };
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class Common
{
    use HasMedia;
    protected bool $withJournal = false;

    protected BaseInterface $repository;

    public function __construct(BaseInterface $repository)
    {
        $this->repository = $repository;
    }

    public function allByRole(?string $role = null)
    {
        $role ??= Auth::user()?->cachedRoleNames[0]
            ?? Auth::user()?->roles->pluck('name')->first()
            ?? 'guest';

        return $this->repository->allByRole($role);
    }

    public function findById(int $id)
    {
        return $this->repository->findById((int) $id);
    }

    public function create(array $data): Model
    {
        $media = Arr::pull($data, 'image');
        $journal = Arr::pull($data, 'journal');
        $relations = $this->extractRelations($data);

        $model = DB::transaction(function () use ($data, $media, $relations, $journal) {

            $model = $this->repository->create($data);

            $this->handleMedia($model, $media);
            $this->syncRelations($model, $relations);


            return $model;
        });

        return $model;
    }

    public function update(int $id, array $data): Model
    {
        $media = Arr::pull($data, 'image');
        $relations = $this->extractRelations($data);
        // dd($relations);

        $model = $this->repository->update($id, $data);

        $this->handleMedia($model, $media, true);
        $this->syncRelations($model, $relations);

        return $model;
    }

    public function delete(int $id): bool
    {

        $model = $this->repository->findById($id);
        // dd($model);

        if (method_exists($model, 'media')) {
            $this->deleteMedia($model);
        }

        return $this->repository->delete($id);
    }

    public function getRelations(): array
    {
        return $this->repository->getRelations();
    }

    public function getModelInstance()
    {
        // dd("hi");
        return $this->repository->getModelInstance();
    }

    /* ===============================
        Internal helpers
       =============================== */

    protected function extractRelations(array &$data): array
    {
        $relations = [];

        foreach ($this->repository->getRelations() as $relation => $meta) {
            if (array_key_exists($relation, $data)) {
                $relations[$relation] = Arr::pull($data, $relation);
            }
        }
        // dd($relations);
        return $relations;
    }

    protected function syncRelations(Model $model, array $relations): void
    {
        foreach ($relations as $relation => $value) {
            if (!method_exists($model, $relation)) continue;

            $relationInstance = $model->$relation();

            if (
                $relationInstance instanceof BelongsToMany ||
                $relationInstance instanceof MorphToMany
            ) {

                $relationInstance->sync((array) $value);
            }

            if ($relationInstance instanceof BelongsTo) {
                $foreignKey = $relationInstance->getForeignKeyName();
                $model->update([
                    $foreignKey => is_array($value) ? ($value[0] ?? null) : $value
                ]);
            }
        }
    }

    protected function handleMedia(Model $model, $media, bool $update = false): void
    {
        if (!$media instanceof UploadedFile) return;
        if (!method_exists($model, 'media')) return;

        $update
            ? $this->updateMedia($model, $media)
            : $this->saveMedia($model, [$media]);
    }
}
