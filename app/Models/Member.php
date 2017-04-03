<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AccessControlledModel;

use DB;

class Member extends AccessControlledModel
{

    protected $permissions = array(
      'read' => array(
        'default' => array("id", "is_superadmin", "department_id"),
        'aegee' => array("first_name", "last_name", "gender", "gender_text", "antenna_id", "seo_url"),
        'samebody' => array("contact_email", "university", "studies_type_id", "studies_field_id", "phone", "address", "city", "zipcode", "is_suspended", "internal_email", "date_of_birth"),
        'self' => array("password"),
        'board' => array("internal_email", "contact_email"),
        'cd' => array(),
      ),
      'write' => array(
        'default' => array(),
        'aegee' => array(),
        'samebody' => array(),
        'self' => array("contact_email", "first_name", "last_name", "gender", "contact_email", "university", "studies_type_id", "studies_field_id", "phone", "address", "city", "zipcode"),
        'board' => array("internal_email", "is_suspended"),
        'cd' => array("antenna_id"),
      ),
    );

    protected $table = "members";

    protected $dates = ['created_at', 'updated_at', 'date_of_birth', 'activated_at'];

    protected $hidden = ['password', 'oauth_token', 'oauth_expiration'];

    // Relationships..
    public function antenna() {
    	return $this->belongsTo('App\Models\Body');
    }

    public function auth() {
    	return $this->hasMany('App\Models\Auth');
    }

    public function boardMember() {
    	return $this->hasMany('App\Models\BoardMember');
    }

    public function department() {
    	return $this->belongsTo('App\Models\Department');
    }

    public function fees() {
        return $this->belongsToMany('App\Models\Fee', 'fee_users', 'user_id', 'fee_id')
                    ->withPivot('date_paid', 'expiration_date');
    }

    public function feeUser() {
    	return $this->hasMany('App\Models\FeeUser');
    }

    public function recrutedUser() {
        return $this->belongsTo('App\Models\RecrutedUser');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'user_roles', 'user_id', 'role_id');
    }

    public function studyField() {
    	return $this->belongsTo('App\Models\StudyField', 'studies_field_id');
    }

    public function studyType() {
    	return $this->belongsTo('App\Models\StudyType', 'studies_type_id');
    }

    public function userRole() {
    	return $this->hasMany('App\Models\MemberRole');
    }

    public function userWorkingGroup() {
    	return $this->hasMany('App\Models\MemberWorkingGroup');
    }

    public function workingGroups() {
        return $this->belongsToMany('App\Models\WorkingGroup', 'user_working_groups', 'user_id', 'work_group_id')
                    ->withPivot('start_date', 'end_date');
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

    public function getInternalEmailAttribute($value) {
        return empty($value) ? "No internal email assigned!" : $value;
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

    public function getEmailAddress() {
        return empty($this->getOriginal('internal_email')) ? $this->contact_email : $this->internal_email;
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

    public function getFiltered($search = array(), $onlyTotal = false) {
        $users = $this
                        ->with('antenna')
                        ->with('department')
                        ->with('studyField')
                        ->with('StudyType');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $users = $users->where(DB::raw('LOWER(CONCAT (first_name, \' \', last_name))'), 'LIKE', '%'.strtolower($search['name']).'%');
        }

        if(isset($search['date_of_birth']) && !empty($search['date_of_birth'])) {
            $users = $users->where('date_of_birth', $search['date_of_birth']);
        }

        if(isset($search['contact_email']) && !empty($search['contact_email'])) {
            $users = $users->where('contact_email', $search['contact_email']);
        }

        if(isset($search['gender']) && !empty($search['gender'])) {
            $users = $users->where('gender', $search['gender']);
        }

        if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
            $users = $users->where('antenna_id', $search['antenna_id']);
        }

        if(isset($search['department_id']) && !empty($search['department_id'])) {
            $users = $users->where('department_id', $search['department_id']);
        }

        if(isset($search['internal_email']) && !empty($search['internal_email'])) {
            $users = $users->where('internal_email', $search['internal_email']);
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
        // END filters..

        if($onlyTotal) {
            return $users->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $users = $users->orderBy('last_name', $search['sord'])->orderBy('first_name', $search['sord']);
                    break;
                case 'date_of_birth':
                case 'contact_email':
                case 'gender':
                case 'internal_email':
                    $users = $users->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $users = $users->orderBy('last_name', $search['sord'])->orderBy('first_name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $users = $users->take($limit)->skip($from);
        }

        return $users->get();
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

    public function suspendAccount($reason, $suspender = 'System') {
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
