<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Trait CrudPermissionTrait
 *
 * Provides a method to set CRUD access based on Spatie permissions.
 * Permissions should be named as: "{operation} {entity_plural}".
 * Example: "list users", "show users", "create users", etc.
 */
trait CrudPermissionTrait
{
    /**
     * Set CRUD access based on user permissions.
     * This method should be called after CRUD is configured (model, route, entity names).
     *
     * @return void
     */
    public function setAccessUsingPermissions(): void
    {
        $user = backpack_user();
        if (!$user) {
            return;
        }

        $entity = CRUD::getEntityNamePlural();

        $operations = ['list', 'show', 'create', 'update', 'delete'];

        foreach ($operations as $operation) {
            $permission = $operation . ' ' . $entity;

            if ($user->can($permission)) {
                CRUD::allowAccess($operation);
            } else {
                CRUD::denyAccess($operation);
            }
        }
    }
}