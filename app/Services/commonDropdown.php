<?php

namespace App\Services;

use App\Repositories\dropdownRepository;

class commonDropdown
{
    protected $dropdownRepository;

    public function __construct(dropdownRepository $dropdownRepository)
    {
        $this->dropdownRepository = $dropdownRepository;
    }

    /**
     * Get the options for a dropdown (generic).
     *
     * @param  string  $modelClass
     * @param  string  $valueField
     * @param  string  $labelField
     * @return array
     */
    public function getDropdownOptions(string $modelClass, string $valueField = 'id', string $labelField = 'name'): array
    {
        // Validate that the class is a valid model
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, \Illuminate\Database\Eloquent\Model::class)) {
            throw new \InvalidArgumentException('Invalid model class provided.');
        }

        // Fetch the data from the repository
        return $this->dropdownRepository->getOptions($modelClass, $valueField, $labelField);
    }
}
