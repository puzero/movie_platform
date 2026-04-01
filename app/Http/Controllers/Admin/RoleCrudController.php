<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController as OriginalRoleCrudController;
use App\Traits\CrudPermissionTrait;

class RoleCrudController extends OriginalRoleCrudController
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

        CRUD::setRoute(config('backpack.base.route_prefix') . '/role');
        CRUD::setEntityNameStrings('role', 'roles');
        parent::setup();
        $this->setAccessUsingPermissions();
    }
}