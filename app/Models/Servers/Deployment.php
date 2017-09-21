<?php

namespace App\Models\Servers;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
 /**
  * The attributes that aren't mass assignable.
  *
  * @var array
  */
  protected $guarded = [];

  public static function saveDeployment(array $data)
  {
    $deployment = ( isset($data['id']) ) ? Deployment::find($data['id']) : new Deployment;
    $deployment->fill( $data );
    $deployment->save();
    return $deployment;
  }
}
