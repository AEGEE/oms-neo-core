<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $result = $next($request);
        $data = $result->getOriginalContent()['data']->get();
        $permissions = [];

        $i = 0;
        foreach ($data as $object) {
            if (method_exists($object, 'getAcquiredPermissions')) {
                $this->buildMeta($request, $permissions, $object->getAcquiredPermissions()->toArray(), $i);
            }
            $i++;
        }
        $response = json_decode($result->getContent());
        $response->meta['permissions'] = $permissions;
        $result->setContent(json_encode($response));
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
