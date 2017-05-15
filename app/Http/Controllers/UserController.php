<?php

namespace App\Http\Controllers;
use Mail;
use Hash;
use File;
use Input;
use Image;
use Excel;
use Session;
use Response;
use Auth;
use App\Http\Requests;
use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\AddBodyToUserRequest;
use App\Http\Requests\SuspendUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\BodyMembership;
use App\Models\User;
use App\Models\Role;
use App\Models\AuthToken;
use App\Models\Country;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(User $user, Request $req) {
        $max_permission = $req->get('max_permission');

        //TODO: rewrite search (filtering)
        $users = $user->getFiltered();

        return response()->json($users);
    }

    public function getUser($user_id) {
        //TODO Decide what (if) should be eager loaded.
        $user = User::findOrFail($user_id)->with('address', 'bodies')->get();
        return response()->json($user);
    }

    public function getBodies($user_id) {
        //TODO Decide what (if) should be eager loaded.
        $bodies = User::findOrFail($user_id)->bodies;
        return response()->json($bodies);
    }

    public function getUserByToken() {
        $token = Input::get('token');
        if(empty($token)) {
            $toReturn['success'] = 0;
            return response(json_encode($toReturn), 200);
        }

        $now = date('Y-m-d H:i:s');
        $auth = AuthToken::where('token_generated', $token)
                        ->where(function($query) use($now) {
                            $query->where('expiration', '>', $now)
                                    ->orWhereNull('expiration');
                        })->firstOrFail();

        return $this->getUser($auth->user_id);
    }

    public function updateUser($user_id, SaveUserRequest $req) {
        $user = User::findOrFail($user_id);

        $user->first_name = $req->has('first_name') ? $req->first_name : $user->first_name;
        $user->last_name = $req->has('last_name') ? $req->last_name : $user->last_name;
        $user->date_of_birth = $req->has('date_of_birth') ? $req->date_of_birth : $user->date_of_birth;
        $user->gender = $req->has('gender') ? $req->gender : $user->gender;
        $user->phone = $req->has('phone') ? $req->phone : $user->phone;
        $user->seo_url = $req->has('seo_url') ? $req->seo_url : $user->seo_url;
        $user->password = $req->has('password') ? Hash::make($req->password) : $user->password;
        $user->description = $req->has('description') ? nl2br($req->description) : $user->description;

        $user->contact_email = $req->has('contact_email') ? $req->contact_email : $user->contact_email;
        //TODO Previously there was a hash check as well, but this should already be validated unique by the validation.
        //See SaveUserRequest. Should test if this works properly now.

        $user->address_id = $req->has('address_id') ? Address::findOrFail($req->address_id)->id : $user->address_id;

        $user->save();
        return response()->json($user);
    }

    public function addBodyToUser($user_id, AddBodyToUserRequest $req) {
        $user = User::findOrFail($user_id);

        $membership = BodyMembership::firstOrCreate([
            'user_id'       =>  $user->id,
            'body_id'       =>  $req->body_id,
        ]);

        $membership->start_date = $req->has('start_date') ? $req->start_date : date('Y-m-d H:i:s');
        $membership->end_date = $req->has('end_date') ? $req->end_date : null;

        $membership->save();

        return response()->json($membership);
    }

    public function activateUser($user_id, Role $role, Request $req) {
        $user = User::findOrFail($user_id);
        $currentUser = $req->get('userData');

        if ($req->activate != $req->deactivate) {
            if ($req->activate) {
                if(!empty($user->activated_at)) {
                    return response()->failure("User already activated");
                }

                $user->seo_url = $user->generateSeoUrl();
                $user->activated_at = date('Y-m-d H:i:s');

                $userPass = $user->generateRandomPassword();

                $oAuthActive = $this->isOauthDefined();
                if($oAuthActive) {
                    $domain = $this->getOAuthAllowedDomain();
                    $username = $user->seo_url."@".$domain;

                    $success = $user->oAuthCreateAccount(
                        $this->getOAuthProvider(),
                        $this->getDelegatedAdmin(),
                        $this->getOauthCredentials($currentUser['id']),
                        $domain,
                        $user->seo_url,
                        $userPass
                    );

                    $user->internal_email = $username;

                    if($success !== true) {
                        die("oAuth problem! Error code:".$success);
                    }
                } else {
                    $username = $user->contact_email;
                    $user->password = Hash::make($userPass);
                }

                $user->save();

                $rolesCache = $role->getCache();

                // Now for roles..
                $roles = Input::get('roles', array());
                foreach($roles as $key => $val) {
                    if(!$val || !isset($rolesCache[$key])) { // Role set as false or does not exist..
                        continue;
                    }
                    $tmpRole = new UserRole();
                    $tmpRole->user_id = $user->id;
                    $tmpRole->role_id = $key;
                    $tmpRole->save();
                }

                //TODO Email user with all data..

                return response()->succes($user);
            } else {
                //TODO deactivate?
            }
        } else {
            return response()->failure("Ambigious action");
        }
    }

    public function addUserRoles(Role $role, AddRoleRequest $req) {
        $id = Input::get('user_id');
        $rolesCache = $role->getCache();

        $roles = Input::get('roles');
        foreach ($roles as $key => $val) {
            if(!$val || !isset($rolesCache[$key])) {
                continue;
            }

            UserRole::firstOrCreate([
                'user_id'   =>  $id,
                'role_id'   =>  $key
            ]);
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function deleteRole($role_id, UserRole $obj) {
        $obj = $obj->findOrFail($id);
        $obj->delete();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function suspendUnsuspendAccount($user_id, SuspendUserRequest $req) {
        $user = User::findOrFail($user_id);
        $userData = $req->get('userData');

        if ($req->suspend != $req->unsuspend) {
            if ($req->suspend) {
                $user->suspendAccount($userData->id, $req->reason);
            } else {
                $user->unsuspendAccount($userData->id);
            }
        } else {
            return response()->failure("Ambigious action");
        }

        return response()->success($user);
    }

    public function impersonateUser($user_id, Request $req) {
        $user = User::findOrFail($user_id);
        $userData = $req->get('userData');
        $xAuthToken = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : '';

        $auth = AuthToken::where('token_generated', $xAuthToken)->firstOrFail();
        $auth->user_id = $id; // Switching token to new user..
        $auth->save();

        $userData = $user->getLoginUserArray($xAuthToken);

        Session::put('userData', $userData);
        // Mirroring Laravel session data to PHP native session.. For use with angular partials..
        session_start();
        $_SESSION['userData'] = Session::get('userData');
        session_write_close();

        $toReturn['success'] = 1;
        return response()->success(null, null, "Impersonated user: " . $user->first_name);
    }


    //TODO If I am not mistaken user avatar management can be done a lot simpler using Laravel.
    public function uploadUserAvatar(Request $req) {
        $userData = $req->get('userData');

        $allowedExt = array(
            'png', 'jpg', 'jpeg'
        );

        $path = storage_path();
        $baseDir = $path."/userAvatars/";
        if(!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        $tmpPlace = $path."/userAvatarsTmp/";
        if(!file_exists($tmpPlace)) {
            mkdir($tmpPlace, 0777, true);
        }

        $file = $req->file('avatar');

        $filename = $file->getClientOriginalName();
        $extension = explode('.', $filename);
        $extension = strtolower($extension[count($extension)-1]);
        if(!in_array($extension, $allowedExt)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Extension not allowed!";
            return response(json_encode($toReturn), 200);
        }

        $file->move($tmpPlace, $filename);
        $tmpIm = Image::make($tmpPlace.$filename);
        $tmpIm->fit(300);
        $tmpIm->save($baseDir.$userData->id.".jpg");

        unlink($tmpPlace.$filename);

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getUserAvatar($avatar_id) {
        $fallbackAvatar = storage_path()."/baseFiles/defaultAvatar.jpg";
        $path = storage_path()."/userAvatars/".$avatar_id.".jpg";

        if(!File::exists($path)) {
            $file = File::get($fallbackAvatar);
            $type = File::mimeType($fallbackAvatar);
        } else {
            $file = File::get($path);
            $type = File::mimeType($path);
        }

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
