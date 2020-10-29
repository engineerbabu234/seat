<?php

namespace App\Helpers;

use Session;
use App;
use Carbon\Carbon;
//use Image;
use url;

class ImageHelper {
  public static $getProfileImagePath  = 'uploads/profiles/';
  public static $getOfficeImagePath  = 'uploads/office_image/';
  public static $getSelfieImagePath = 'uploads/selfie_image/';
  public static $getDriverDocumentImagePath = 'uploads/document/';
  public static $getVehicleTypeImagePath = 'uploads/vehicle_type/';
  public static $userPlaceholderImage = 'uploads/others/user_placeholder.png';

  /*public static function store($path = null , $fileName = null , $sizes = array()){
     Image::make($path.'/'.$fileName)->resize(150, 100)->save($path.'/'.$fileName);
  }*/

  public static function getProfileImage($image){
    if($image){
      if(file_exists(STATIC::$getProfileImagePath.$image)){
        return url(STATIC::$getProfileImagePath.$image);
      }
    }
    return url(STATIC::$userPlaceholderImage);
  }

  public static function getOfficeImage($image){
    if($image){
      if(file_exists(STATIC::$getOfficeImagePath.$image)){
        return url(STATIC::$getOfficeImagePath.$image);
      }
    }
    return url(STATIC::$userPlaceholderImage);
  }

  public static function getSelfieImage($image){
    if($image){
      if(file_exists(STATIC::$getSelfieImagePath.$image)){
        return url(STATIC::$getSelfieImagePath.$image);
      }
    }
    return url(STATIC::$userPlaceholderImage);
  }

  public static function getCategoryImage($image){
    if($image){
      if(file_exists(STATIC::$getCategoryImagePath.$image)){
        return url(STATIC::$getCategoryImagePath.$image);
      }
    }
    return url(STATIC::$userPlaceholderImage);
  }


  public static function getDriverDocumentImage($image){
    if($image){
      if(file_exists(STATIC::$getDriverDocumentImagePath.$image)){
        return url(STATIC::$getDriverDocumentImagePath.$image);
      }
    }
    return url(STATIC::$userPlaceholderImage);
  }

  public static function getVehicleTypeImage($image){
    if($image){
      if(file_exists(STATIC::$getVehicleTypeImagePath.$image)){
        return url(STATIC::$getVehicleTypeImagePath.$image);
      }
    }
    return url(STATIC::$userPlaceholderImage);
  }


  public static function getPlaceholderImage(){
    return url('uploads/others/user_placeholder.png');
  }
  public static function getProductPlaceholderImage(){
    return url('uploads/others/placeholder.png');
  }
}
