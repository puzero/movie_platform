<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CheckCrudPermission
{
    public function handle(Request $request, Closure $next)
    {
        // $user = backpack_user();

        // if (!$user) {
        //     return $next($request);
        // }

        // // Определяем сущность (например, 'user', 'role', 'permission')
        // $entity = $this->getEntityFromRoute($request);
        // if (!$entity) {
        //     return $next($request);
        // }

        // // Возможные операции
        // $operations = ['list', 'show', 'create', 'update', 'delete'];

        // // Сначала запрещаем всё
        // foreach ($operations as $operation) {
        //     CRUD::denyAccess($operation);
        // }

        // // Разрешаем только те, на которые есть разрешение у пользователя
        // foreach ($operations as $operation) {
        //     $permission = $entity . '.' . $operation;
        //     if ($user->can($permission)) {
        //         CRUD::allowAccess($operation);
        //     }
        // }

        // return $next($request);
    }

    private function getEntityFromRoute(Request $request): ?string
    {
        // // Получаем имя маршрута (например, user.index, role.create)
        // $routeName = $request->route()->getName();
        // if ($routeName) {
        //     $parts = explode('.', $routeName);
        //     if (count($parts) === 2) {
        //         return $parts[0];
        //     }
        // }

        // // Альтернативный способ: из сегмента URL после префикса админки
        // $segments = $request->segments();
        // $adminPrefix = config('backpack.base.route_prefix', 'admin');
        // $index = array_search($adminPrefix, $segments);
        // if ($index !== false && isset($segments[$index + 1])) {
        //     return $segments[$index + 1];
        // }

        // return null;
    }
}