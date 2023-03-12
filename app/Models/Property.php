<?php

namespace App\Models;

use App\Models\Image;
use App\Models\EkstraProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

   /**
    * Get the TypeProperty associated with the Property
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function TypeProperty()
   {
       return $this->hasOne(Type_Property::class,'id',  'type_property');
   }

   public function User()
   {
       return $this->hasOne(User::class,'id',  'id_user');
   }
   
   /**
    * Get all of the images for the Property
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function images()
   {
        $img = $this->hasMany(Image::class, 'id_property', 'id');
       return $img;
   }

   public function offer()
   {
       return $this->hasMany(Image::class, 'id_property', 'id');
   }

   /**
    * Get the kota associated with the Property
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function kota()
   {
       return $this->hasOne(Kota::class,'intid', 'id_kota');
   }

   public function extra()
   {
        $img = $this->hasMany(EkstraProperty::class, 'id_property', 'id');
       return $img;
   }


}
