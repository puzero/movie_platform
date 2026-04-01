<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController as OriginalPermissionCrudController;
use App\Traits\CrudPermissionTrait;

class PermissionCrudController extends OriginalPermissionCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use CrudPermissionTrait;
    use CrudPermissionTrait;

    public function setup()
    {

        CRUD::setRoute(config('backpack.base.route_prefix') . '/permission');
        CRUD::setEntityNameStrings('permission', 'permissions');
        parent::setup();
        $this->setAccessUsingPermissions();
    }
}