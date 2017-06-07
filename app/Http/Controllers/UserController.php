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
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\AddBodyToUserRequest;
use App\Http\Requests\SuspendUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\BodyMembership;
use App\Models\User;
use App\Models\Role;
use App\Models\Address;
use App\Models\AuthToken;
use App\Models\Country;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(Request $req) {
        $max_permission = $req->get('max_permission');

        // Extract URL arguments to filter on.
        $search = [
            'name'          => $req->name,
            'date_of_birth' => $req->date_of_birth,
            'contact_email' => $req->contact_email,
            'gender'        => $req->gender,
            'status'        => $req->status,
            'body_id'       => $req->body_id,
            'body_name'     => $req->body_name,
            ];

        $users = User::filterArray($search)->get();

        return response()->success($users);
    }

    public function getUser($user_id) {
        $user = User::where('id', $user_id)->with('address', 'bodies')->get();
        return response()->success($user);
    }

    public function getBodies($user_id) {
        //TODO Decide what (if) should be eager loaded.
        $bodies = User::findOrFail($user_id)->bodies;
        return response()->success($bodies);
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

    public function createUser(CreateUserRequest $req) {
        $arr = $this->getUpdateArray($req, ['address_id', 'first_name', 'last_name', 'date_of_birth', 'contact_email', 'gender', 'phone', 'description', 'password']);
        $arr['password'] = Hash::make($req->password);
        $user = User::create($arr);
        return response()->success($user, null, 'User created');
    }

    public function updateUser($user_id, UpdateUserRequest $req) {
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

        $user->address_id = $req->has('address_id') ? Address::findOrFail($req->address_id)->id : $user->address_id;

        $user->save();
        return response()->success($user);
    }

    public function addBodyToUser($user_id, AddBodyToUserRequest $req) {
        $user = User::findOrFail($user_id);

        $membership = BodyMembership::create([
            'user_id'       =>  $user->id,
            'body_id'       =>  $req->body_id,
            'start_date'    =>  $req->has('start_date') ? $req->start_date : date('Y-m-d H:i:s'),
            'end_date'      =>  $req->has('end_date') ? $req->end_date : null,
        ]);
        return response()->success($membership);
    }

    public function activateUser($user_id, Role $role, Request $req) {
        $user = User::findOrFail($user_id);
        $currentUser = Auth::user();

        if ($req->activate != $req->deactivate) {
            if ($req->activate) {
                if(!empty($user->activated_at)) {
                    return response()->failure("User already activated");
                }

                $user->seo_url = $user->generateSeoUrl();
                $user->activated_at = date('Y-m-d H:i:s');

                //TODO restructure oAuth implementation.
                $oAuthActive = false; //$this->isOauthDefined();
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

                return response()->success($user, null, 'User activated');
            } else {
                return response()->notImplemented();
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
        $userData = Auth::user();

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
        $userData = Auth::user();
        $xAuthToken = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : '';

        $auth = AuthToken::where('token_generated', $xAuthToken)->firstOrFail();
        $auth->user_id = $user_id; // Switching token to new user..
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
        $userData = Auth::user();

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
