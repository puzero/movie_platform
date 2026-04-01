<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\PermissionManager\app\Models\Permission;

trait CrudPermissionTrait
{
    /**
     * Operations supported by the CRUD controller.
     *
     * @var array
     */
    public array $operations = ['list', 'show', 'create', 'update', 'delete'];

    /**
     * Set CRUD access based on user's Spatie permissions.
     *
     * Expected permission format: "{table}.{operation}", where operation is one of:
     * 'list', 'show', 'create', 'update', 'delete'.
     *
     * @return void
     */
    // public function setAccessUsingPermissions()
    // {
    //     // Initially deny access to all operations
    //     $this->crud->denyAccess($this->operations);

    //     $user = backpack_user();
    //     if (!$user) return;

    //     $operations = Permission::pluck('name');

        

    //     foreach ($operations as $operation) {
    //         $permission = explode('.',$operation)[1];
    //         if ($user->can($operation)) {
    //             CRUD::allowAccess($permission);
    //         } else {
    //             CRUD::denyAccess($permission);
    //         }
    //     }
    // }
        public function setAccessUsingPermissions()
    {
        // Запрещаем доступ ко всем операциям по умолчанию
        $this->crud->denyAccess($this->operations);

        $user = backpack_user();
        if (!$user) {
            return;
        }
        
        $table = CRUD::getModel()->getTable();
        // Разрешаем доступ для каждой операции, если есть соответствующее право
        foreach ($this->operations as $operation) {
            $permission = "{$table}.{$operation}";
            if ($user->can($permission)) {
                CRUD::allowAccess($operation);
            }
        }
    }
}