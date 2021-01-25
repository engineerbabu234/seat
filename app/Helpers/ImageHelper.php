<?php

namespace App\Helpers;

use App;
//use Image;
use url;

class ImageHelper
{
    public static $getLogoImagePath = 'uploads/logo/';
    public static $getProfileImagePath = 'uploads/profiles/';
    public static $getOfficeImagePath = 'uploads/office_image/';
    public static $getSelfieImagePath = 'uploads/selfie_image/';
    public static $getDriverDocumentImagePath = 'uploads/document/';
    public static $getVehicleTypeImagePath = 'uploads/vehicle_type/';
    public static $userPlaceholderImage = 'uploads/others/user_placeholder.png';
    public static $LogoPlaceholderImage = 'uploads/others/logo.png';
    public static $getOfficeAssetsImagePath = 'uploads/office_asset/';

    /*public static function store($path = null , $fileName = null , $sizes = array()){
    Image::make($path.'/'.$fileName)->resize(150, 100)->save($path.'/'.$fileName);
    }*/

    /**
     * [getLogoImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getLogoImage($image)
    {
        if ($image) {
            if (file_exists(static::$getLogoImagePath . $image)) {
                return url(static::$getLogoImagePath . $image);
            }
        }
        return url(static::$LogoPlaceholderImage);
    }

    /**
     * [getProfileImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getProfileImage($image)
    {
        if ($image) {
            if (file_exists(static::$getProfileImagePath . $image)) {
                return url(static::$getProfileImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getOfficeImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getOfficeImage($image)
    {
        if ($image) {
            if (file_exists(static::$getOfficeImagePath . $image)) {
                return url(static::$getOfficeImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getSelfieImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getSelfieImage($image)
    {
        if ($image) {
            if (file_exists(static::$getSelfieImagePath . $image)) {
                return url(static::$getSelfieImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getCategoryImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getCategoryImage($image)
    {
        if ($image) {
            if (file_exists(static::$getCategoryImagePath . $image)) {
                return url(static::$getCategoryImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getDriverDocumentImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getDriverDocumentImage($image)
    {
        if ($image) {
            if (file_exists(static::$getDriverDocumentImagePath . $image)) {
                return url(static::$getDriverDocumentImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getVehicleTypeImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getVehicleTypeImage($image)
    {
        if ($image) {
            if (file_exists(static::$getVehicleTypeImagePath . $image)) {
                return url(static::$getVehicleTypeImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getOfficeAssetsImage description]
     * @param  [type] $image [description]
     * @return [type]        [description]
     */
    public static function getOfficeAssetsImage($image)
    {
        if ($image) {
            if (file_exists(static::$getOfficeAssetsImagePath . $image)) {
                return url(static::$getOfficeAssetsImagePath . $image);
            }
        }
        return url(static::$userPlaceholderImage);
    }

    /**
     * [getPlaceholderImage description]
     * @return [type] [description]
     */
    public static function getPlaceholderImage()
    {
        return url('uploads/others/user_placeholder.png');
    }

    /**
     * [getProductPlaceholderImage description]
     * @return [type] [description]
     */
    public static function getProductPlaceholderImage()
    {
        return url('uploads/others/placeholder.png');
    }
}
