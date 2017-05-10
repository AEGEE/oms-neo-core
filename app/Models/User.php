<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class User extends Model
{
    protected $table = "users";

    protected $dates = ['created_at', 'updated_at', 'date_of_birth', 'activated_at'];

    protected $hidden = ['password', 'oauth_token', 'oauth_expiration'];

    // Relationships..
    public function bodies() {
    	return $this->belongsToMany('App\Models\Body', 'body_memberships', 'user_id', 'body_id');
    }

    public function auth() {
    	return $this->hasMany('App\Models\Auth');
    }

    public function address() {
    	return $this->belongsTo('App\Models\Address');
    }

    public function bodyMemberships() {
    	return $this->hasMany('App\Models\BodyMembership');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Roles', 'user_roles', 'user_id', 'role_id');
    }

    public function studies() {
    	return $this->hasMany('App\Models\Study');
    }

    public function studyFields() {
    	return $this->belongsToMany('App\Models\StudyField', 'studies', 'user_id', 'study_field_id');
    }

    public function studyTypes() {
    	return $this->belongsToMany('App\Models\StudyType', 'studies', 'user_id', 'study_type_id');
    }

    public function universities() {
    	return $this->belongsToMany('App\Models\University', 'studies', 'user_id', 'university_id');
    }

    public function userRole() {
    	return $this->hasMany('App\Models\UserRole');
    }

    // Accessors..
    public function getGenderTextAttribute($value) {
        $genderText = "";

        switch ($this->gender) {
            case '1':
                $genderText = "Male";
                break;
            case '2':
                $genderText = "Female";
                break;
            case '3':
                $genderText = "Other";
                break;
        }
        return $genderText;
    }

    public function getStatusTextAttribute($value) {
        $suspended = empty($this->is_suspended) ? false : true;
        $active = empty($this->activated_at) ? false : true;
        $status = "Active";
        if($suspended) {
            $status = "Suspended";
        } elseif(!$active) {
            $status = "Inactive";
        }

        return $status;
    }

    public function getStatusAttribute($value) {
        $suspended = empty($this->is_suspended) ? false : true;
        $active = empty($this->activated_at) ? false : true;
        $status = "1"; // Active
        if($suspended) {
            $status = "3"; // Suspended
        } elseif(!$active) {
            $status = "2";
        }

        return $status;
    }

    // Model methods go down here..
    public function getEmailHash($email) {
        $emailHash = strtolower($email);

        $emailSplit = explode('@', $emailHash);
        $emailUsername = $emailSplit[0];
        $emailTld = $emailSplit[1];

        // We remove any labels we have in the email name (everything that's after +)
        $emailUsername = preg_replace('/\+.*/', "", $emailUsername);

        // Special check for gmail accounts..
        if(preg_match('/gmail/', $emailTld)) {
            // If it's gmail, then dots (.) don't represent anything in the email username, so we just remove them..
            $emailUsername = preg_replace('/\./', '', $emailUsername);
        }

        $emailHash = $emailUsername."@".$emailTld;
        return $emailHash;
    }

    public function checkEmailHash($emailHash, $exceptId = 0) {
        return $this->where('email_hash', $emailHash)->where('id', '!=', $exceptId)->count() >= 1;
    }

    public function scopeFilterName($query, $name) {
        if (!empty($name)) {
            return $query->where(DB::raw('LOWER(CONCAT (first_name, \' \', last_name))'), 'LIKE', '%'.strtolower($name).'%');
        } else {
            return $query;
        }
    }


    public function scopeFilterDateOfBirth($query, $date_of_birth) {
        if (!empty($date_of_birth)) {
            return $query->whereDate('date_of_birth', $date_of_birth);
        } else {
            return $query;
        }
    }


    public function scopeFilterContactEmail($query, $contact_email) {
        if (!empty($date_of_birth)) {
            return $query->where('contact_email', $contact_email);
        } else {
            return $query;
        }
    }


    public function scopeFilterGender($query, $gender) {
        if (!empty($gender)) {
            return $query->where('gender', $gender);
        } else {
            return $query;
        }
    }

    public function scopeFilterBodyID($query, $body_id) {
        if (!empty($body_id)) {
            //TODO user_id and body_id are currently in output, should be removed.
            return $query->rightJoin('body_memberships', 'users.id', '=', 'body_memberships.user_id')->where('body_memberships.body_id', $body_id)->with('bodies');
        } else {
            return $query;
        }
    }

    public function getFiltered($search = array()) {
        //TODO rework filtering.
        dump(User::filterBodyID(1)->toSql());
        dd(User::filterBodyID(1)->get()->toArray());
        $search['body_id'] = '1';
        $query = $this
            ->filterName($search['name'] ?? '')
            ->filterDateOfBirth($search['date_of_birth'] ?? '')
            ->filterContactEmail($search['contact_email'] ?? '')
            ->filterGender($search['gender'] ?? '')
            ->with(['bodies' => function ($q) { $q->where('bodies.id', 1);}, 'address' => function ($q) { $q->with('country');}])
            ->filterBodyID('bodies.id', 1);
        dd($query->get()->toArray());

        if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
            $users = $users->where('antenna_id', $search['antenna_id']);
        }

        if(isset($search['studies_type_id']) && !empty($search['studies_type_id'])) {
            $users = $users->where('studies_type_id', $search['studies_type_id']);
        }

        if(isset($search['studies_field_id']) && !empty($search['studies_field_id'])) {
            $users = $users->where('studies_field_id', $search['studies_field_id']);
        }

        if(isset($search['status']) && !empty($search['status'])) {
            switch ($search['status']) {
                case '1':
                    $users = $users->whereNull('is_suspended')->whereNotNull('activated_at');
                    break;
                case '2':
                    $users = $users->whereNull('activated_at');
                    break;
                case '3':
                    $users = $users->whereNotNull('is_suspended');
                    break;
            }
        }


        return User::all();
    }

    public function generateRandomPassword($max_length = 8) {
        $str = "qwertyuiopasdfghjklzxcvbnm1234567890";
        $shuffled = str_shuffle($str);
        $password = substr($shuffled, 0, $max_length);
        return $password."#P".$max_length;
    }

    public function generateSeoUrl() {
        $url = preg_replace('/ /', '.', $this->first_name).".".preg_replace('/ /', '.', $this->last_name);
        $url = strtolower($url);

        $specialChars = array(
            'ă' =>  'a',
            'â' =>  'a',
            'ș' =>  's',
            'ş' =>  's',
            'ț' =>  't',
            'ţ' =>  't',
            'Ș' =>  's',
            'î' =>  'i',
            '-' =>  '.'
        );

        $url = strtr($url, $specialChars);

        $url_final = $url;
        $counter = 1;
        while(!$this->checkSeoUrlIsAvailable($url_final)) {
            $url_final = $url.".".$counter;
            $counter++;
        }

        return $url_final;
    }

    public function checkSeoUrlIsAvailable($url) {
        return $this->where('seo_url', $url)->count() == 0;
    }

    public function suspendAccount($suspender = 'System', $reason = 'no reason given') {
        $this->is_suspended = 1;
        $this->suspended_reason = $reason." Suspended by: ".$suspender." on ".date('Y-m-d H:i:s');
        $this->save();

        // Todo send email..
    }

    public function unsuspendAccount() {
        $this->is_suspended = null;
        $this->suspended_reason = null;
        $this->save();

        // Todo send email..
    }

    public function getLoginUserArray($authToken) {
        return array(
            'id'                =>  $this->id,
            'username'          =>  empty($this->internal_email) ? $this->contact_email : $this->internal_email,
            'fullname'          =>  $this->first_name." ".$this->last_name,
            'is_superadmin'     =>  $this->is_superadmin,
            'department_id'     =>  $this->department_id,
            'logged_in'         =>  true,
            'authToken'         =>  $authToken,
            'seo_url'           =>  $this->seo_url,
            'is_suspended'      =>  !empty($this->is_suspended) ? true : false,
            'suspended_reason'  =>  $this->suspended_reason
        );
    }

    // oAuth methods..
    public function oAuthCreateAccount($provider, $delegatedAdmin, $oAuthCredentials, $domain, $seo, $password) {
        switch ($provider) {
            case 'google':
                return $this->createGoogleAppsAccount($delegatedAdmin, $oAuthCredentials, $domain, $seo, $password);
                break;
            case 'azure':
                return $this->createAzureAdAccount($oAuthCredentials, $domain, $seo, $password);
                break;

            default:
                return false;
                break;
        }
    }

    private function createGoogleAppsAccount($delegatedAdmin, $oAuthCredentials, $domain, $seo, $password) {
        $scopes = array(
            'https://www.googleapis.com/auth/admin.directory.user'
        );

        $client = new \Google_Client();
        $client->setScopes($scopes);
        $client->setSubject($delegatedAdmin);

        $client->setAuthConfig($oAuthCredentials);

        $dir = new \Google_Service_Directory($client);

        $userInstance = new \Google_Service_Directory_User();
        $nameInstance = new \Google_Service_Directory_UserName();

        $nameInstance->setGivenName($this->first_name);
        $nameInstance->setFamilyName($this->last_name);

        $userInstance->setName($nameInstance);
        $userInstance->setHashFunction("MD5");
        $userInstance->setPrimaryEmail($seo."@".$domain);
        $userInstance->setPassword(hash("md5", $password));
        $userInstance->setChangePasswordAtNextLogin(true);

        $createUserResult = $dir->users->insert($userInstance);

        return true;
    }

    private function createAzureAdAccount($oAuthCredentials, $domain, $seo, $password) {
        $userData = array(
            'displayName'           =>  $this->first_name." ".$this->last_name,
            'userPrincipalName'     =>  $seo."@".$domain,
            'mailNickname'          =>  $seo,
            'passwordProfile'       =>  array(
                                            'password'  =>  $password,
                                            'forceChangePasswordNextLogin'  =>  true
                                        ),
            'accountEnabled'        =>  true
        );

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://graph.windows.net/myorganization/users', [
            'query' => [
                'api-version' => '1.5'
            ],
            'headers'   =>  [
                'Authorization' =>  'Bearer '.$oAuthCredentials['token'],
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($userData)
        ]);

        if($response->getStatusCode() == 201) {
            return true;
        }

        return $response->getStatusCode();
    }
}
