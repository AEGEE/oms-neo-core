<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Util;
use Log;
use Auth;
use App\Aspect\Restrict;

use App\Traits\RequiresPermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Body extends Model
{
    use RequiresPermission;

    //TODO: Move this down.
    public function getPermissions($user) {
        $permissions = collect([]); //Default permissions go here.
        dd($user->bodies);
        //TODO No clue why $user->bodies result in all bodies...
        if ($user->bodies->contains($this->id) || true) {
            $permissions->push("address");
            $permissions->push("bodyType");
        }
        Log::debug("Found permissions: " . $permissions);
        return $permissions;
    }



    protected $table = "bodies";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function setNameAttribute($value) {
        $this->attributes['name_simple'] = Util::limitCharacters($value);
        $this->attributes['name'] = $value;
    }


    /**
     * Restrict
     */
    public function address() {
        dump(Auth::user()->bodies()->getQuery()->toSql());
        dump(Auth::user()->bodies->count());
        $user = User::find(Auth::user()->id)->first();
        dump($user->bodies()->getQuery()->toSql());
        dump($user->bodies->count());
        $user = User::find(Auth::user()->id)->with('bodies')->first();
        dump($user->bodies()->getQuery()->toSql());
        dump($user->bodies->count());
        /* RESULT:
        "select * from "bodies" inner join "body_memberships" on "bodies"."id" = "body_memberships"."body_id""
        8
        "select * from "bodies" inner join "body_memberships" on "bodies"."id" = "body_memberships"."body_id""
        8
        "select * from "bodies" inner join "body_memberships" on "bodies"."id" = "body_memberships"."body_id""
        2
        */
        /*EXPECTED QUERY:
        "select * from "bodies" inner join "body_memberships" on "bodies"."id" = "body_memberships"."body_id" where "body_memberships"."user_id" = ?"
        gotten from calling `dump(Auth::user()->bodies()->getQuery()->toSql());` in App\Http\Controllers\UserController::getUser()
        */
        die();
        $this->getPermissions(Auth::user());

        return $this->belongsTo('App\Models\Address');
    }

    /**
     * Restrict
     */
    public function users() {
    	return $this->belongsToMany('App\Models\User', 'body_memberships', 'body_id', 'user_id');
    }

    /**
     * Restrict
     */
    public function bodyType() {
        return $this->belongsTo('App\Models\BodyType', 'type_id');
    }

    /**
     * Restrict
     */
    public function circles() {
        return $this->hasMany('App\Models\Circle', 'body_id', 'id');
    }

    public function scopeFilterName($query, $name) {
        if (!empty($name)) {
            return $query->where(DB::raw('LOWER(name)'), 'LIKE', "%" . strtolower($name) . "%");
        } else {
            return $query;
        }
    }

    public function scopeFilterCity($query, $city) {
        if (!empty($city)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->where(DB::raw('LOWER(addresses.city)'), 'LIKE', "%" . strtolower($city) . "%");
        } else {
            return $query;
        }
    }

    public function scopeFilterCountryID($query, $country_id) {
        if (!empty($country_id)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->where('addresses.country_id', $country_id);
        } else {
            return $query;
        }
    }

    public function scopeFilterCountryName($query, $country_name) {
        if (!empty($country_name)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->rightJoin('countries', 'addresses.country_id', '=', 'countries.id')
            ->where('countries.name', $country_name);
        } else {
            return $query;
        }
    }

    public function scopeFilterTypeID($query, $type_id) {
        if (!empty($type_id)) {
            return $query->where('type_id', $type_id);
        } else {
            return $query;
        }
    }

    // Model methods go down here..
    public function scopeFilterArray($query, $search = array()) {
        $query->filterName($search['name'] ?? '')
            ->filterCity($search['city'] ?? '')
            ->filterTypeID($search['type_id'] ?? '')
            ->filterCountryID($search['country_id'] ?? '')
            ->filterCountryName($search['country_name'] ?? '');

        return $query;
    }
}
