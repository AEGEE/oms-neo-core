<?php

namespace App\Aspect;

use App;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Aop\Intercept\Joinpoint;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Around;

use Log;
use Auth;


/**
 * Application logging aspect
 */
class PermissionAspect implements Aspect
{
    /**
     * @Around("execution(public App\Models\Body->setRelation(*)) || execution(public App\Models\User->setRelation(*))")
     */
     public function logMethodCall(MethodInvocation $invocation)
     {
         //Log the pointcut.
         Log::debug("Pointcut: " . get_class($invocation->getThis()) . "(" . spl_object_hash($invocation->getThis()) . ") :: " . $invocation->getMethod()->name . " (" . json_encode($invocation->getArguments()) . ")");

         //Check if permission should be granted.
         $permission = $invocation->getThis()->requiresPermission(get_class($invocation->getThis()) . "." . $invocation->getArguments()[0]);
         Log::debug("Permission " . ($permission ? "GRANTED" : "DENIED"));


         if ($permission) {
             $result = $invocation->proceed();
         }
         return $invocation->getThis();
     }
}
