<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\commonDropdown;

class Dropdown extends Component
{
    public $modelClass;
    public $valueField;
    public $labelField;
    public $options;
    public $align;
    public $width;
    public $contentClasses;
    public $trigger;

    public function __construct(
        string $modelClass = null,  // Make modelClass optional
        string $valueField = 'id',
        string $labelField = 'name',
        string $align = 'right',
        string $width = '48',
        string $contentClasses = 'py-1 bg-white',
        string $trigger = 'Click Here'
    ) {
        // If modelClass is not passed, leave it as null
        $this->modelClass = $modelClass;
        $this->valueField = $valueField;
        $this->labelField = $labelField;
        $this->align = $align;
        $this->width = $width;
        $this->contentClasses = $contentClasses;
        $this->trigger = $trigger;

        // Only fetch dropdown options if modelClass is provided
        if ($this->modelClass) {
            $dropdownService = app(commonDropdown::class);
            $this->options = $dropdownService->getDropdownOptions($modelClass, $valueField, $labelField);
        }
    }

    public function render()
    {
        return view('components.dropdown');
    }
}
