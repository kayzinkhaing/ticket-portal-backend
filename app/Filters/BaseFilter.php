<?php
namespace App\Filters;

class BaseFilter
{
    protected $request;
    protected $builder;

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $name => $value) {
            if (!empty($value) && method_exists($this, $name)) {
                $this->$name($value);
            }
        }

        return $this->builder;
    }
}