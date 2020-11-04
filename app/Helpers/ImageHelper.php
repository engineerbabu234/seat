<?php

namespace App\Helpers;

use App;
//use Image;
use url;

class ImageHelper
{
    public static $getProfileImagePath = 'uploads/profiles/';
    public static $getOfficeImagePath = 'uploads/office_image/';
    public static $getSelfieImagePath = 'uploads/selfie_image/';
    public static $getDriverDocumentImagePath = 'uploads/document/';
    public static $getVehicleTypeImagePath = 'uploads/vehicle_type/';
    public static $userPlaceholderImage = 'uploads/others/user_placeholder.png';
    public static $getOfficeAssetsImagePath = 'uploads/office_asset/';

    /*public static function store($path = null , $fileName = null , $sizes = array()){
    Image::make($path.'/'.$fileName)->resize(150, 100)->save($path.'/'.$fileName);
    }*/

    public static function getProfileImage($image)
    {
        if ($image) {
            if (file_exists(static::$getProfileImagePath . $image)) {
                return url(static::$getProfileImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getOfficeImage($image)
    {
        if ($image) {
            if (file_exists(static::$getOfficeImagePath . $image)) {
                return url(static::$getOfficeImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getSelfieImage($image)
    {
        if ($image) {
            if (file_exists(static::$getSelfieImagePath . $image)) {
                return url(static::$getSelfieImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getCategoryImage($image)
    {
        if ($image) {
            if (file_exists(static::$getCategoryImagePath . $image)) {
                return url(static::$getCategoryImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getDriverDocumentImage($image)
    {
        if ($image) {
            if (file_exists(static::$getDriverDocumentImagePath . $image)) {
                return url(static::$getDriverDocumentImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getVehicleTypeImage($image)
    {
        if ($image) {
            if (file_exists(static::$getVehicleTypeImagePath . $image)) {
                return url(static::$getVehicleTypeImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getOfficeAssetsImage($image)
    {
        if ($image) {
            if (file_exists(static::$getOfficeAssetsImagePath . $image)) {
                return url(static::$getOfficeAssetsImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    public static function getPlaceholderImage()
    {
        return url('uploads/others/user_placeholder.png');
    }
    public static function getProductPlaceholderImage()
    {
        return url('uploads/others/placeholder.png');
    }
}
