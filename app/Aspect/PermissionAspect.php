<?php

namespace App\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

use Log;

/**
 * Application logging aspect
 */
class PermissionAspect implements Aspect
{
    /**
     * Writes a log info before method execution
     *
     * @param MethodInvocation $invocation
     * @Before("execution(public App\Models\User->method(*))")
     */
    public function beforeMethod(MethodInvocation $invocation)
    {
        Log::debug("AOP: " . $invocation->getArguments());
    }
}
