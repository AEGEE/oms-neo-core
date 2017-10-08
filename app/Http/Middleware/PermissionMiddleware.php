<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{

    public function handle($request, Closure $next)
    {
        $result = $next($request);
        $data = $result->getInnerData();
        if (is_a($data, "Illuminate\Database\Eloquent\Builder")) {
            $data = $data->get();
        }
        $permissions = [];

        if (is_iterable($data)) {
            $i = 0;
            foreach ($data as $object) {
                if (method_exists($object, 'getAcquiredPermissions')) {
                    $this->buildMeta($request, $permissions, $object->getAcquiredPermissions()->toArray(), $i);
                }
                $i++;
            }
        } else {
            if (method_exists($data, 'getAcquiredPermissions')) {
                $this->buildMeta($request, $permissions, $data->getAcquiredPermissions()->toArray());
            }
        }

        if (!empty($permissions)) {
            $result->addInnerMeta('permissions', $permissions);
        }
        return $result;
    }

    private function buildMeta($request, &$permissions, $objectPermissions, $index = null) {
        if ($index === null) {
            $permissions = $objectPermissions;
        } else {
            $permissions[$index] = $objectPermissions;
        }
    }
}
