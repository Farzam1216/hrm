<?php

namespace App\Domain\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Poll\Models\PollQuestion;
use App\Domain\Poll\Models\Employee;
use App\Domain\Poll\Models\PollAnswer;
use App\Domain\ACL\Models\Role;


class Poll extends Model
{
  use HasFactory;

  protected $fillable = [
    'title', 'poll_description', 'poll_start_date', 'poll_end_date'
  ];


  public function pollQuestion()
  {
    return $this->hasMany(PollQuestion::class, 'poll_id');
  }

  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  public function pollAnswer()
  {
    return $this->hasMany(PollAnswer::class);
  }

  //Get permissions of logged-in user related to Handbook.
  public static function getPermissionsWithAccessLevel($user)
  {
    $poll = null;
    $roles = $user->roles()->get();
    foreach ($roles as $role) {
      $roleWithSubRole[] = $role;
      //If user is not an "employee" he might have a sub_role (employee) also
      if ($role->type != "employee") {
        if (isset($role->sub_role)) {
          $roleWithSubRole[] = Role::where('id', $role->sub_role)->first();
        }
      }
      foreach ($roleWithSubRole as $role) {
        $permissions = $role->permissions()->get();
        foreach ($permissions as $permission) {
          if (stripos(strtolower($permission->name), 'manage poll') !== false) {
            $poll[$user->id][$permission->pivot->access_level_id][] = $permission->name;
          }
        }
      }
    }
    return $poll;
  }

}
