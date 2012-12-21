<?php

//include_once dirname(__FILE__) . '/../AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoPhoto.php';

/**
 * Client that provides photo related functions.
 *
 * 
 */
class AdferoPhotosClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * Initialises a new instance of the Feeds Client
     * @param string $baseUri Uri of the API provided by your account manager
     */
    function __construct($baseUri) {
        $this->baseUri = $baseUri;
    }

    /**
     * Gets the location of the photo.
     * @param int $id Id of the photo to get location of.
     * @return string Uri location of the photo.
     */
    public function GetLocationUrl($id) {
        if ($id < 0) {
            throw new InvalidArgumentException("id must be a positive integer.");
        }

        $locationUri = $this->GetPhotoBaseUri($id);
        $photo = new AdferoPhoto();
        $photo->id = $id;
        $photo->locationUri = $locationUri;
        return $photo;
    }

    /**
     * Gets the location of the photo with the requested actions.
     * @param int $id Id of the photo to get location of.
     * @param int $cropWidth Width to crop photo by.
     * @param int $cropHeight Height to crop photo by.
     * @param int $focalPointX Focal point on the x axis. Null will use the photo's default value.
     * @param int $focalPointY Focal point on the y axis. Null will use the photo's default value.
     * @return string Uri location of the photo with requested actions.
     */
    public function GetCropLocationUrl($id, $cropWidth, $cropHeight, $focalPointX, $focalPointY) {
        if ($id < 0) {
            throw new InvalidArgumentException("id must be a positive integer.");
        }

        if (!isset($cropWidth) && !isset($cropHeight)) {
            throw new InvalidArgumentException("At least one crop dimension must be provided.");
        }

        if (isset($cropWidth) && $cropWidth <= 0) {
            throw new InvalidArgumentException("cropWidth must be greater than 0.");
        }

        if (isset($cropHeight) && $cropHeight <= 0) {
            throw new InvalidArgumentException("cropHeight must be greater than 0.");
        }

        if (isset($focalPointX) && $focalPointX < 0) {
            throw new InvalidArgumentException("focalPointX must be a positive integer.");
        }

        if (isset($focalPointY) && $focalPointY < 0) {
            throw new InvalidArgumentException("focalPointY must be a positive integer.");
        }

        $uri = $this->GetPhotoBaseUri($id);
        $data = array();
        $data["action"] = "crop";
        $data["crop"] = (isset($cropWidth) ? $cropWidth : "*") . "x" . (isset($cropHeight) ? $cropHeight : "*");
        if (isset($focalPointX) || isset($focalPointY)) {
            $data["focalpoint"] = (isset($focalPointX) ? $focalPointX : "*") . "x" . (isset($focalPointY) ? $focalPointY : "*");
        }
        $querystring = urldecode(http_build_query($data));
        $locationUri = $uri . "?" . $querystring;

        $photo = new AdferoPhoto();
        $photo->id = $id;
        $photo->locationUri = $locationUri;
        return $photo;
    }

    /**
     * Gets the uri of the photo with the requested actions.
     * @param int $id Id of the photo to get.
     * @param AdferoScaleAxis $scaleAxis Axis to scale the photo on.
     * @param type $scale Amount to scale.
     * @return string Uri location of the photo with requested actions.
     */
    public function GetScaleLocationUrl($id, $scaleAxis, $scale) {
        if ($id < 0) {
            throw new InvalidArgumentException("id must be a positive integer.");
        }

        if (isset($scale) && $scale <= 0) {
            throw new InvalidArgumentException("scale must be a greater than 0.");
        }

        $uri = $this->GetPhotoBaseUri($id);
        $data = array();
        $data["action"] = "scale";
        if ($scaleAxis == AdferoScaleAxis::X) {
            $data["scale"] = $scale . "x*";
        } else if ($scaleAxis == AdferoScaleAxis::Y) {
            $data["scale"] = "*x" . $scale;
        }
        $querystring = urldecode(http_build_query($data));
        $locationUri = $uri . "?" . $querystring;

        $photo = new AdferoPhoto();
        $photo->id = $id;
        $photo->locationUri = $locationUri;
        return $photo;
    }

    /**
     *
     * Gets the location of the photo with the requested actions.
     * @param int $id Id of the photo to get location of.
     * @param int $cropWidth Width to crop photo by.
     * @param int $cropHeight Height to crop photo by.
     * @param int $focalPointX Focal point on the x axis. Null will use the photo's default value.
     * @param int $focalPointY Focal point on the y axis. Null will use the photo's default value.
     * @param AdferoScaleAxis $scaleAxis Axis to scale the photo on.
     * @param type $scale Amount to scale.
     * @return string Uri location of the photo with requested actions.
     */
    public function GetCropScaleLocationUrl($id, $cropWidth, $cropHeight, $focalPointX, $focalPointY, $scaleAxis, $scale) {
        if ($id < 0) {
            throw new InvalidArgumentException("id must be a positive integer.");
        }

        if (!isset($cropWidth) && !isset($cropHeight)) {
            throw new InvalidArgumentException("At least one crop dimension must be provided.");
        }

        if (isset($cropWidth) && $cropWidth <= 0) {
            throw new InvalidArgumentException("cropWidth must be greater than 0.");
        }

        if (isset($cropHeight) && $cropHeight <= 0) {
            throw new InvalidArgumentException("cropHeight must be greater than 0.");
        }

        if (isset($focalPointX) && $focalPointX < 0) {
            throw new InvalidArgumentException("focalPointX must be a positive integer.");
        }

        if (isset($focalPointY) && $focalPointY < 0) {
            throw new InvalidArgumentException("focalPointY must be a positive integer.");
        }

        if (isset($scale) && $scale <= 0) {
            throw new InvalidArgumentException("scale must be a greater than 0.");
        }
        
        $uri = $this->GetPhotoBaseUri($id);
        $data = array();
        $data["action"] = "cropscale";
        $data["crop"] = (isset($cropWidth) ? $cropWidth : "*") . "x" . (isset($cropHeight) ? $cropHeight : "*");
        if (isset($focalPointX) || isset($focalPointY)) {
            $data["focalpoint"] = (isset($focalPointX) ? $focalPointX : "*") . "x" . (isset($focalPointY) ? $focalPointY : "*");
        }
        if ($scaleAxis == AdferoScaleAxis::X) {
            $data["scale"] = $scale . "x*";
        } else if ($scaleAxis == AdferoScaleAxis::Y) {
            $data["scale"] = "*x" . $scale;
        }
        $querystring = urldecode(http_build_query($data));
        $locationUri = $uri . "?" . $querystring;

        $photo = new AdferoPhoto();
        $photo->id = $id;
        $photo->locationUri = $locationUri;
        return $photo;
    }

    private function GetPhotoBaseUri($id) {
        return $this->baseUri . "photo/" . $id . "." . "jpg";
    }

}

?>
