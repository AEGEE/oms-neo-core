<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    
    public function handle($request, Closure $next)
    {
        $result = $next($request);
        $data = $result->getInnerData()->get();
        $permissions = [];

        $i = 0;
        foreach ($data as $object) {
            if (method_exists($object, 'getAcquiredPermissions')) {
                $this->buildMeta($request, $permissions, $object->getAcquiredPermissions()->toArray(), $i);
            }
            $i++;
        }

        $result->addInnerMeta('permissions', $permissions);
        return $result;
    }

    private function buildMeta($request, &$permissions, $objectPermissions, $index = null) {
        if (!$index) {
            $permissions = $objectPermissions;
        } else {
            $permissions[$index] = $objectPermissions;
        }
    }
}
