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

     /**
      * @Around("execution(public App\Models\Body->getUserPermissions(*))")
      */
      public function bodyGetUserPermissions(MethodInvocation $invocation) {
          $user = $invocation->getArguments()[0];

          $permissions = collect(["App\Models\Body.address", "App\Models\Body.bodyType", "App\Models\Body.pivot"]);
          if ($user->bodies()->pluck('bodies.id')->contains($invocation->getThis()->id)) {
              //If member
              $permissions->push("App\Models\Body.circles");
              $permissions->push("App\Models\Body.users");
              $permissions->push("App\Models\User.circles");
          }
          Log::debug("Found permissions: " . $permissions);
          return $permissions;
      }


      /**
       * @Around("execution(public App\Models\User->getUserPermissions(*))")
       */
      public function userGetUserPermissions(MethodInvocation $invocation) {
          $user = $invocation->getArguments()[0];

          $permissions = collect(["App\Models\User.bodies", "App\Models\User.pivot"]);
          if ($user->id == $invocation->getThis()->id) {
              //If same user
              $permissions->push("App\Models\User.address");
          }
          Log::debug("Found permissions: " . $permissions);
          return $permissions;
      }

      /**
       * @Around("execution(public App\Models\User->getGrantingParents(*))")
       */
      public function userGetGrantingParents(MethodInvocation $invocation) {
          return $invocation->getThis()->bodies;
      }
}
