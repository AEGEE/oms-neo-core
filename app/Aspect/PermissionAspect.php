<?php

namespace App\Aspect;

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
     *
     * Around("execution(public App\Models\Body->address(*))")
     * Before("execution(public App\Models\Body->getRelationValue(*))")
     */
    public function aroundCacheable(MethodInvocation $invocation)
    {
        //Log the pointcut.
        Log::debug("Pointcut: " . get_class($invocation->getThis()) . " :: " . $invocation->getMethod()->name . " (" . json_encode($invocation->getArguments()) . ")");
        //Check if permission should be granted.
        $permission = $invocation->getThis()->requiresPermission($invocation->getMethod()->name);
        Log::debug("Permission " . ($permission ? "GRANTED" : "DENIED"));
        //dump($invocation->getThis());

        //Execute the action
        $result = $invocation->proceed();
        dd($result);
        //TODO: Return empty result without having to proceed.
        if (!$permission) {
            //No permission, alter result -> return an empty result.
            $result->addConstraints();
            $result->getQuery()->getQuery()->whereRaw("false");
            //TODO: Do this in a way that the database query can be skipped entirely.
        }
        return $result;
    }

    /**
     * @Around("execution(public App\Models\Body->*(*))")
     */
     public function logMethodCall(MethodInvocation $invocation)
     {
         Log::debug("Pointcut: " . get_class($invocation->getThis()) . "(" . spl_object_hash($invocation->getThis()) . ") :: " . $invocation->getMethod()->name . " (" . json_encode($invocation->getArguments()) . ")");

         //Log the pointcut.
         if($invocation->getMethod()->name == "getAttribute") {
             dump("Pointcut: " . get_class($invocation->getThis()) . "(" . spl_object_hash($invocation->getThis()) . ") :: " . $invocation->getMethod()->name . " (" . json_encode($invocation->getArguments()) . ")");

             $result = $invocation->proceed();
             dump($result);
         }
         return $invocation->proceed();
     }
}
