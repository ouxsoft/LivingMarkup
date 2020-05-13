<?php

namespace LivingMarkup;

use Laminas\Validator\File\Exists;

/**
 * Class Image
 * checks if the requested image exists in cache using a hash
 * if it is not cached, it checks to see if the image exists as an assets
 * if the image exists, than it generates a resized image and stores it in cache
 * and then serves that image
 *
 * @package LivingMarkup
 */
class Image
{
    const JPEG = 1;
    const JPG = 1;
    const PNG = 2;
    const GIF = 3;

    private $root_dir;

    private $filename;
    private $file_extension;
    private $width;
    private $height;
    private $focal_point_x;
    private $focal_point_y;
    private $image;
    private $cache_dir;
    private $cache_filename;
    private $cache_filepath;
    private $assets_dir;

    /**
     * Image constructor.
     */
    public function __construct()
    {
        // declare directories
        $this->root_dir = dirname(__DIR__, 1) . '/';
        $this->assets_dir = $this->root_dir . 'assets/images/';
        $this->cache_dir = $this->root_dir . 'var/cache/images/';
    }

    /**
     * load by URL
     * @param string|null $request
     * @return bool
     */
    public function loadByURL(string $request = null): bool
    {
        if ($request === null) {
            return false;
        }

        // set cache url
        $this->setCacheURL($request);

        $parameters = Path::Decode($request);

        // set filename
        if (array_key_exists('filename', $parameters)) {
            $filename = substr($parameters['filename'], strlen('/assets/images/'));
            $this->setFilename($filename);
        }

        // set height
        if (array_key_exists('height', $parameters)) {
            $this->setHeight($parameters['height']);
        }

        // set width
        if (array_key_exists('width', $parameters)) {
            $this->setWidth($parameters['width']);
        }

        // set offset x
        if (array_key_exists('offset_x', $parameters)) {
            $this->setFocalPointX($parameters['offset_x']);
        }

        // set offset y
        if (array_key_exists('offset_y', $parameters)) {
            $this->setFocalPointY($parameters['offset_y']);
        }

        return true;
    }

    /**
     * picks the file in assets to use
     * @param $filename
     * @return bool
     */
    public function setFilename(string $filename): bool
    {
        if ($filename == null) {
            return false;
        }

        // set filename
        $this->filename = $filename;

        // set file_extension
        $this->file_extension = strtolower(substr(strrchr($filename, '.'), 1));

        return true;
    }

    /**
     * set cache URL
     * @param string|null $relative_path
     * @return bool
     */
    public function setCacheURL(string $relative_path = null): bool
    {
        if ($relative_path === null) {
            return false;
        }

        // set cache hash of file name
        $this->cache_filename = hash('sha256', $relative_path);
        $this->cache_filepath = $this->cache_dir . $this->cache_filename;

        return true;
    }

    /**
     * Outputs file
     * @return bool
     */
    public function output(): bool
    {
        /*
        // if cache exist, then output it
        if ($this->sendCache()) {
            return true;
        }
*/
        // load file
        if (!$this->load()) {
            // if cannot load file send empty
            $this->sendEmpty();
            return false;
        }

//        $this->crop();
/*
        // save cache
        $this->saveCache();
*/

        // send image
        if ($this->sendImage()) {
            return true;
        }

        return false;
    }

    /**
     * Send empty
     */
    private function sendEmpty()
    {
        header('HTTP/1.0 404 Not Found');
    }

    /**
     * Send $this->image or file, if provided
     * @param null $resource
     * @return bool
     */
    private function sendImage($resource = null): bool
    {
        // send cached image
        if ($resource == $this->cache_filepath) {
            $this->sendCached();
        }

        // send $this->image image
        switch ($this->file_extension) {
            case 'gif':
                header('Content-Type: image/gif');
                imagegif($this->image, null);
                break;
            case 'png':
                header('Content-Type: image/png');
                imagepng($this->image, null, 9);
                break;
            case 'jpeg':
            case 'jpg':
                header('Content-type: image/jpeg');
                imagejpeg($this->image, null, 100);
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Send cache of file requested
     * @return bool
     */
    public function sendCache(): bool
    {
        try {
            // check if cache exists
            $cache_validator = new Exists($this->cache_dir);
            if ($cache_validator->isValid($this->cache_filename)) {
                // send cache file
                switch ($this->file_extension) {
                    case 'gif':
                        header('Content-Type: image/gif');
                        header('Content-Length: ' . filesize($this->cache_filepath));
                        readfile($this->cache_filepath);
                        break;
                    case 'png':
                        header('Content-Type: image/png');
                        header('Content-Length: ' . filesize($this->cache_filepath));
                        readfile($this->cache_filepath);
                        break;
                    case 'jpeg':
                    case 'jpg':
                        header('Content-type: image/jpeg');
                        header('Content-Length: ' . filesize($this->cache_filepath));
                        readfile($this->cache_filepath);
                        break;
                    default:
                        return false;
                }

                return true;
            }
        } catch (Exception $exception) {
            // do nothing
        }

        return false;
    }

    /**
     * Loads image for resizing
     * @return bool
     */
    private function load(): bool
    {
        // assets
        $assets_filename = $this->filename;
        $assets_filepath = $this->assets_dir . $assets_filename;
        $assets_validator = new Exists($this->assets_dir);

        if (!$assets_validator->isValid($assets_filename)) {
            //    return false;
        }

        // get width and height
        list($width_original, $height_original) = getimagesize($assets_filepath);

        // if desired width not set, use original
        if ($this->width === null) {
            $this->width = $width_original;
        }

        // if desired height not set, use original
        if ($this->height === null) {
            $this->height = $height_original;
        }

        // determine original ratio
        $ratio_original = $width_original / $height_original;

        if ($this->width / $this->height > $ratio_original) {
            $this->width = $this->height * $ratio_original;
        } else {
            $this->height = $this->width / $ratio_original;
        }

        $this->image = imagecreatetruecolor($this->width, $this->height);

        // create image from file
        switch ($this->file_extension) {
            case 'gif':
                $image_original = imagecreatefromgif($assets_filepath);
                imagecopyresampled(
                    $this->image,
                    $image_original,
                    $this->focal_point_x,
                    $this->focal_point_y,
                    0,
                    0,
                    $this->width,
                    $this->height,
                    $width_original,
                    $height_original
                );
                break;
            case 'png':
                $image_original = imagecreatefrompng($assets_filepath);
                imagealphablending($this->image, false);
                imagesavealpha($this->image, true);
                imagecopyresampled(
                    $this->image,
                    $image_original,
                    $this->focal_point_x,
                    $this->focal_point_y,
                    0,
                    0,
                    $this->width,
                    $this->height,
                    $width_original,
                    $height_original);
                break;
            case 'jpeg':
            case 'jpg':
                $image_original = imagecreatefromjpeg($assets_filepath);
                imagecopyresampled(
                    $this->image,
                    $image_original,
                    $this->focal_point_x,
                    $this->focal_point_y,
                    0,
                    0,
                    $this->width,
                    $this->height,
                    $width_original,
                    $height_original
                );
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Saves $this->image to specific path
     */
    private function saveCache()
    {
        // save $this->image to cache
        switch ($this->file_extension) {
            case 'gif':
                imagegif($this->image, $this->cache_filepath);
                break;
            case 'png':
                imagepng($this->image, $this->cache_filepath, 9);
                break;
            case 'jpeg':
            case 'jpg':
                imagejpeg($this->image, $this->cache_filepath, 100);
                break;
            default:
                return false;
        }
    }

    /**
     * Sets Focal Point X
     * @param mixed $focal_point_x
     */
    public function setFocalPointX($focal_point_x): void
    {
        $this->focal_point_x = $focal_point_x;
    }

    /**
     * Sets Focal Point Y
     * @param $focal_point_y
     */
    public function setFocalPointY($focal_point_y): void
    {
        $this->focal_point_y = $focal_point_y;
    }

    /**
     * Sets Focal Point X & Y
     * @param $focal_point_x
     * @param $focal_point_y
     */
    public function setFocalPoints($focal_point_x, $focal_point_y)
    {
        $this->focal_point_x = $focal_point_x;
        $this->focal_point_y = $focal_point_y;
    }

    /**
     * Sets height
     * @param $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Sets width
     * @param $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Sets both height and width
     * @param $height
     * @param $width
     */
    public function setDimensions($height, $width)
    {
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * Crops image
     * @param null $height
     * @param null $width
     * @param null $focal_point_x
     * @param null $focal_point_y
     */
    public function crop($height = null, $width = null, $focal_point_x = null, $focal_point_y = null)
    {
        if ($height !== null) {
            $this->height = $height;
        }

        if ($width !== null) {
            $this->width = $width;
        }

        if ($focal_point_x !== null) {
            $this->focal_point_x = $focal_point_x;
        }

        if ($focal_point_y !== null) {
            $this->focal_point_x = $focal_point_y;
        }
    }

    /**
     * Return image object
     * @return mixed
     */
    public function get()
    {
        return $this->image;
    }
}
