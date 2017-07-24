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
     * Writes a log info before method execution
     *
     * @param MethodInvocation $invocation
     * Before("@within(App\Aspect\Restrict)") //TODO why does this not work?
     * @Around("execution(public App\Models\Body->address|circles(*))")
     */
    public function beforeMethod(MethodInvocation $invocation)
    {
        //Log the pointcut.
        Log::debug("Pointcut: " . get_class($invocation->getThis()) . " :: " . $invocation->getMethod()->name . " (" . json_encode($invocation->getArguments()) . ")");

        //Check if permission should be granted.
        $permission = $invocation->getThis()->requiresPermission($invocation->getMethod()->name);
        Log::debug("Permission " . ($permission ? "GRANTED" : "DENIED"));

        //Execute the action
        $result = $invocation->proceed();
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
     * Cacheable methods
     *
     * @param MethodInvocation $invocation Invocation
     *
     * @Around("@execution(Annotation\Cacheable)")
     */
    public function aroundCacheable(MethodInvocation $invocation)
    {
        dd("aroundCacheable()");
        static $memoryCache = array();

        $time  = microtime(true);

        $obj   = $invocation->getThis();
        $class = is_object($obj) ? get_class($obj) : $obj;
        $key   = $class . ':' . $invocation->getMethod()->name;
        if (!isset($memoryCache[$key])) {
            $memoryCache[$key] = $invocation->proceed();
        }

        echo "Take ", sprintf("%0.3f", (microtime(true) - $time) * 1e3), "ms to call method<br>", PHP_EOL;
        return $memoryCache[$key];
    }
}
