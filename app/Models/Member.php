<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AccessControlledModel;

use App\Models\Interfaces\HasUser;

use DB;

class Member extends AccessControlledModel implements HasUser
{

    protected $permissions = array(
      'read' => array(
        'default' => array("id", "user_id", "is_superadmin", "department_id"),
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

    public function checkStillValid(){
      if(empty($user->is_superadmin)) {
          $fee->checkFeesForSuspention($user);
      }
    }

    // Relationships..
    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    public function bodyRole(Body $body) {
      return $this->hasMany('App\Models\MemberBodyRelation')->where('body_id', '=', $body->id);
    }

    public function bodyRoles() {
      return $this->hasMany('App\Models\MemberBodyRelation');
    }

    public function getBodiesQuery() {
      //dump($this->hasManyThrough('App\Models\Body', 'App\Models\MemberBodyRelation', 'id1', 'id2', 'id')->toSql());
      //dump($this->hasMany('App\Models\MemberBodyRelation')->with('body')->get());
      //TODO tidy this function up: respect laravel conventions.
      //Investigate ->with()
      $query =  DB::table('member_body_relations')
        ->where('member_id', $this->id)
        ->join('bodies', 'member_body_relations.body_id', '=', 'bodies.id')
        ->select('bodies.*');
      return $query;
    }

    public function getBodies() {
      return $this->getBodiesQuery()->get();
    }

    public function boardMember() {
    	return $this->hasMany('App\Models\BoardMember');
    }

    public function department() {
    	return $this->belongsTo('App\Models\Department');
    }

    public function fees() {
        return $this->belongsToMany('App\Models\Fee', 'fee_members', 'member_id', 'fee_id')
                    ->withPivot('date_paid', 'expiration_date');
    }

    public function FeeMember() {
    	return $this->hasMany('App\Models\FeeMember');
    }

    public function recruitedMember() {
        return $this->belongsTo('App\Models\RecruitedMember');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'member_roles', 'member_id', 'role_id');
    }

    public function studyField() {
    	return $this->belongsTo('App\Models\StudyField', 'studies_field_id');
    }

    public function studyType() {
    	return $this->belongsTo('App\Models\StudyType', 'studies_type_id');
    }

    public function memberRole() {
    	return $this->hasMany('App\Models\MemberRole');
    }

    public function memberWorkingGroup() {
    	return $this->hasMany('App\Models\MemberWorkingGroup');
    }

    public function workingGroups() {
        return $this->belongsToMany('App\Models\WorkingGroup', 'member_working_groups', 'member_id', 'work_group_id')
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
        $emailMembername = $emailSplit[0];
        $emailTld = $emailSplit[1];

        // We remove any labels we have in the email name (everything that's after +)
        $emailMembername = preg_replace('/\+.*/', "", $emailMembername);

        // Special check for gmail accounts..
        if(preg_match('/gmail/', $emailTld)) {
            // If it's gmail, then dots (.) don't represent anything in the email membername, so we just remove them..
            $emailMembername = preg_replace('/\./', '', $emailMembername);
        }

        $emailHash = $emailMembername."@".$emailTld;
        return $emailHash;
    }

    public function checkEmailHash($emailHash, $exceptId = 0) {
        return $this->where('email_hash', $emailHash)->where('id', '!=', $exceptId)->count() >= 1;
    }

    public function getFiltered($search = array(), $onlyTotal = false) {
        $members = $this
                        ->with('antenna')
                        ->with('department')
                        ->with('studyField')
                        ->with('StudyType');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $members = $members->where(DB::raw('LOWER(CONCAT (first_name, \' \', last_name))'), 'LIKE', '%'.strtolower($search['name']).'%');
        }

        if(isset($search['date_of_birth']) && !empty($search['date_of_birth'])) {
            $members = $members->where('date_of_birth', $search['date_of_birth']);
        }

        if(isset($search['contact_email']) && !empty($search['contact_email'])) {
            $members = $members->where('contact_email', $search['contact_email']);
        }

        if(isset($search['gender']) && !empty($search['gender'])) {
            $members = $members->where('gender', $search['gender']);
        }

        if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
            $members = $members->where('antenna_id', $search['antenna_id']);
        }

        if(isset($search['department_id']) && !empty($search['department_id'])) {
            $members = $members->where('department_id', $search['department_id']);
        }

        if(isset($search['internal_email']) && !empty($search['internal_email'])) {
            $members = $members->where('internal_email', $search['internal_email']);
        }

        if(isset($search['studies_type_id']) && !empty($search['studies_type_id'])) {
            $members = $members->where('studies_type_id', $search['studies_type_id']);
        }

        if(isset($search['studies_field_id']) && !empty($search['studies_field_id'])) {
            $members = $members->where('studies_field_id', $search['studies_field_id']);
        }

        if(isset($search['status']) && !empty($search['status'])) {
            switch ($search['status']) {
                case '1':
                    $members = $members->whereNull('is_suspended')->whereNotNull('activated_at');
                    break;
                case '2':
                    $members = $members->whereNull('activated_at');
                    break;
                case '3':
                    $members = $members->whereNotNull('is_suspended');
                    break;
            }
        }
        // END filters..

        if($onlyTotal) {
            return $members->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $members = $members->orderBy('last_name', $search['sord'])->orderBy('first_name', $search['sord']);
                    break;
                case 'date_of_birth':
                case 'contact_email':
                case 'gender':
                case 'internal_email':
                    $members = $members->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $members = $members->orderBy('last_name', $search['sord'])->orderBy('first_name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $members = $members->take($limit)->skip($from);
        }

        return $members->get();
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
            'https://www.googleapis.com/auth/admin.directory.member'
        );

        $client = new \Google_Client();
        $client->setScopes($scopes);
        $client->setSubject($delegatedAdmin);

        $client->setAuthConfig($oAuthCredentials);

        $dir = new \Google_Service_Directory($client);

        $memberInstance = new \Google_Service_Directory_Member();
        $nameInstance = new \Google_Service_Directory_MemberName();

        $nameInstance->setGivenName($this->first_name);
        $nameInstance->setFamilyName($this->last_name);

        $memberInstance->setName($nameInstance);
        $memberInstance->setHashFunction("MD5");
        $memberInstance->setPrimaryEmail($seo."@".$domain);
        $memberInstance->setPassword(hash("md5", $password));
        $memberInstance->setChangePasswordAtNextLogin(true);

        $createMemberResult = $dir->members->insert($memberInstance);

        return true;
    }

    private function createAzureAdAccount($oAuthCredentials, $domain, $seo, $password) {
        $memberData = array(
            'displayName'           =>  $this->first_name." ".$this->last_name,
            'memberPrincipalName'     =>  $seo."@".$domain,
            'mailNickname'          =>  $seo,
            'passwordProfile'       =>  array(
                                            'password'  =>  $password,
                                            'forceChangePasswordNextLogin'  =>  true
                                        ),
            'accountEnabled'        =>  true
        );

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://graph.windows.net/myorganization/members', [
            'query' => [
                'api-version' => '1.5'
            ],
            'headers'   =>  [
                'Authorization' =>  'Bearer '.$oAuthCredentials['token'],
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($memberData)
        ]);

        if($response->getStatusCode() == 201) {
            return true;
        }

        return $response->getStatusCode();
    }


    // Interface methods...
    public function getUserName() {
      return empty($this->tryGet('internal_email',"error, no internal email")) ? $this->tryGet('trycontact_email') : $this->tryGet('internal_email');
    }

    public function getName() {
      return $this->tryGet('first_name')." ".$this->tryGet('last_name');
    }

    public function getSEOURL() {
      return $this->tryGet('seo_url');
    }
}
