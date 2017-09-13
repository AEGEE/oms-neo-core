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
     * Main permission interception.
     * Pointcuts on a relation of a Model being edited and initialises the permission lookup.
     *
     * @Around("execution(public App\Models\Body->setRelation(*)) || execution(public App\Models\User->setRelation(*))")
     */
     public function logMethodCall(MethodInvocation $invocation)
     {
         //Log the pointcut.
         Log::debug("Pointcut: " . get_class($invocation->getThis()) . "(" . spl_object_hash($invocation->getThis()) . ") :: " . $invocation->getMethod()->name . " (" . json_encode($invocation->getArguments()) . ")");

         //Check if permission should be granted.
         $permission = get_class($invocation->getThis()) . "." . $invocation->getArguments()[0];
         $granted = $invocation->getThis()->requiresPermission($permission);
         Log::debug("Permission ($permission)" . $granted ? "GRANTED" : "DENIED");


         if ($granted) {
             $invocation->getThis()->addAcquiredPermission($permission);
             return $invocation->proceed();
         }
         return $invocation->getThis();
     }

     /*
        The below is an initial example implemenation of permissions.
        It defines default permissions (that everyone has) for both Users and Bodies.
        Additionally it grants Users the ability to see their own address,
        as well as granting same-body members to see each other's address + circles.

        This should eventually be changed in a database query,
        this would allow each Object to set it's own permissions for others,
        as well as the (authorization) delegates it trusts.

        (Authorization) delegates work as follows:
        A delegate is an object that you (the object) grant the (full!) power
        to grant users permission over you (the object).
        Trusting an object to be a delegate, means you trust all the delegate's delegates as well.

        Right now it is not possible to declare permissions contextual
        on an object's instance, you can only apply it contextual to an object's class.
        However, once permissions (and delegates) are dynamic, ie the DB query as above,
        the system already is capable of some highly complex systems.

        Permissions currently do not work on atributes YET, only (eloquent) relations.
      */


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
          }
          Log::debug("Found permissions: " . $permissions);
          return $permissions;
      }

       /**
        * @Around("execution(public App\Models\Body->getCascadingUserPermissions(*))")
        */
        public function bodyGetCascadingUserPermissions(MethodInvocation $invocation) {
            $user = $invocation->getArguments()[0];

            $permissions = collect([]);
            if ($user->bodies()->pluck('bodies.id')->contains($invocation->getThis()->id)) {
                //If member
                $permissions->push("App\Models\User.circles");
                $permissions->push("App\Models\User.address");
            }
            Log::debug("Found permissions: " . $permissions);
            return $permissions;
        }

      /**
       * @Around("execution(public App\Models\User->getUserPermissions(*))")
       */
      public function userGetUserPermissions(MethodInvocation $invocation) {
          $user = $invocation->getArguments()[0];

          $permissions = $invocation->proceed();
          if ($user->id == $invocation->getThis()->id) {
              //If same user
              $permissions->push("App\Models\User.address");
          }
          Log::debug("Found permissions: " . $permissions);
          return $permissions;
      }

    /**
     * @Around("execution(public App\Models\User->getDefaultPermissions(*))")
     */
    public function userGetDefaultPermissions(MethodInvocation $invocation) {
        return collect(["App\Models\User.bodies", "App\Models\User.pivot"]);
    }

      /**
       * @Around("execution(public App\Models\User->getAuthorizationDelegates(*))")
       */
      public function userGetAuthorizationDelegates(MethodInvocation $invocation) {
          return $invocation->getThis()->bodies;
      }
}
