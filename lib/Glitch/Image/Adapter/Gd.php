<?php
// require_once 'Zend/Image/Adapter/AdapterAbstract.php';
// require_once 'Zend/Loader.php';

class Glitch_Image_Adapter_Gd extends Glitch_Image_Adapter_AdapterAbstract
{
    /**
     * The path of the image
     *
     * @var string $_path
     */
    protected $_path = null;

    /**
     * The width of the image
     *
     * @var int $_imageWidth
     */
    protected $_imageWidth = null;

    /**
     * The height of the image
     *
     * @var int $_height
     */
    protected $_imageHeight = null;

    /**
     * The type of the image
     *
     * @var string $_imageType
     */
    protected $_imageType = null;

    /**
     * The amount of bits of the image
     *
     * @var int $_imageBits
     */
    protected $_imageBits = null;

    /**
     * The amount of channels of the image
     *
     * @var int $_imageChannels
     */
    protected $_imageChannels = null;

    /**
     * The mime-type of the image
     *
     * @var string $_imageMime
     */
    protected $_imageMime = null;

    /**
     * The name of the adapter
     */
    const NAME = 'Gd';

    /**
     * Checks if the GD-library is available
     *
     * @return boolean True if GD is available
     */
    static function isAvailable() {
        return function_exists ( 'gd_info' );
    }
    

    /**
     * Create/load the handle of this adapter
     * @TODO implement new images (height+width available)
     *
     */
    protected function _loadHandle() {
        if(null !== $this->_path) {
            if(! $this->_setImageInfo($this->_path)) {
                throw new Glitch_Image_Exception('No valid image was specified.');
            }
            
            $this->_handle = imagecreatefromstring(file_get_contents($this->_path));
        } else {
            throw new Glitch_Image_Exception("Could not load handle");
        }
    }
    
    public function getHandle() {
        return $this->_handle;
    }

    /**
     * Set the image info on this adapter
     *
     * @param string $path The path of the image of the info requested
     */
    protected function _setImageInfo($path) {
        $info = getimagesize ($path);
        if(!$info) {
            return false;
        }

        $this->_imageWidth = $info[0];
        $this->_imageHeight = $info[1];
        $this->_imageType = $info[2];
        $this->_imageBits = $info['bits'];
        
        if(isset($info['channels'])) {
            $this->_imageChannels = $info['channels'];
        }
        
        return true;
    }

    /**
     * Get the image as a string.
     *
     * @param string $format (Optional) The format or mimetype to return
     */
    public function getImage($format='png', $savePath = null) {
        switch ($format) {
            case 'image/png':
            case 'png':
                ob_start();
                imagepng($this->getHandle(), $savePath);
                return ob_get_flush();
                break;
       }
    }

    /**
     * Sets the path of an image to the adapter
     *
     * @param string $path The path to the image
     */
    public function setPath($path) {
        $this->_path = $path;
    }
    
    public function getWidth() {
        return $this->_imageWidth;
    }
    
    public function getHeight() {
        return $this->_imageHeight;
    }
    
    public function getName() {
        return 'Gd';
    }
}
