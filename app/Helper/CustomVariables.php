<?php

namespace App\Helper;

class CustomVariables
{
    /**
     * Store custom variables loaded from config
     */
    private array $variables;

    /**
     * Load config variables
     */
    public function __construct()
    {
        $this->variables = config('custom_variables', []);
    }

    /**
     * Get a variable by key
     *
     * @param string $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get(string $key)
    {
        if (!array_key_exists($key, $this->variables)) {
            throw new \InvalidArgumentException("Custom variable [$key] not found in config.");
        }

        return $this->variables[$key];
    }

    /**
     * Get all variables as array
     */
    public function all(): array
    {
        return $this->variables;
    }
}
