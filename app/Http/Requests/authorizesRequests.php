<?php

namespace App\Http\Requests;

trait authorizesRequests
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 1. Allow admin users to bypass permission checks
        if ($this->user()->hasRole('Admin')) {
            return true;  // Admins are always authorized
        }

        // 2. For non-admin users, check permissions based on action
        $action = $this->getActionBasedOnMethod();
        return $this->user()->hasPermissionTo($action);
    }

    /**
     * Determine the action based on the HTTP request method.
     */
    protected function getActionBasedOnMethod(): string
    {
        if ($this->isMethod('post')) {
            return 'create ' . $this->getResourceName();
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return 'update ' . $this->getResourceName();
        }

        if ($this->isMethod('delete')) {
            return 'delete ' . $this->getResourceName();
        }

        return '';
    }

    /**
     * Get the resource name dynamically
     */
    protected function getResourceName(): string
    {
        $className = class_basename($this);
        return strtolower(str_replace('Request', '', $className));
    }
}
