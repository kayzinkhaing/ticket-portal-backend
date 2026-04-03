<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CacheObserver;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::observe(CacheObserver::class);
    }
    public function defaultSelectColumns(): array
    {
        $columns = [$this->getKeyName()];
        $columns = array_merge($columns, $this->getFillable());

        foreach ($this->getRelations() as $relationName => $relationConfig) {
            $relation = $this->$relationName();
            if (method_exists($relation, 'getForeignKeyName')) {
                $columns[] = $relation->getForeignKeyName();
            }
        }

        return array_unique($columns);
    }

}
