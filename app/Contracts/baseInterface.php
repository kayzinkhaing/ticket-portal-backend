<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

/** The interface is responsibile for
 * Responsibilities for an Interface is:
 * 1.Define method signatures.
 * 2.Ensure consistency.
 * 3.Promote loose coupling by decoupling.
 * 4.Enable polymorphism.
 * 5.Encourage dependency injection.
 * 6.Facilitate testability by mocking objects and stubs.
 * 7.Allow for multiple implementations of the same functionality.
 */

interface BaseInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function allByRole(string $role);
    public function FindById(int $id);
    public function FindByName(string $name);
    public function getRelations();
    public function getModelInstance():Model;
    public function query();
}
