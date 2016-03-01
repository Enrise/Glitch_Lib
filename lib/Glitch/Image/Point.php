<?php
class Glitch_Image_Point {
    /**
     * The X-coordinate of this point
     *
     * @var int $_x
     */
    protected $_x = null;

    /**
     * The Y-coordinate of this point
     *
     * @var int $_y
     */
    protected $_y = null;

    /**
     * Sets the location of the point
     *
     * @param Glitch_Image_point|integer $param1 (Optional)   A point, or coordinate
     * @param integer                  $param2 (Optional)   The Y-coordinate
     */
    public function __construct($param1 = null, $param2 = null) {
        $this->setLocation($param1, $param2);
    }

    /**
     * Returns the X-coordinate
     *
     * @return integer X-coordinate of this point
     */
    public function getX() {
        return $this->_x;
    }

    /**
     * Returns the Y-coordinate
     *
     * @return integer Y-coordinate of this point
     */
    public function getY() {
        return $this->_y;
    }

    /**
     * Sets the X-coordinate
     *
     * @param integer $x The X-coordinate of this point
     */
    public function setX($x) {
        $this->_x = (int) $x;
    }

    /**
     * Sets the Y-coordinate
     *
     * @param integer $y The Y-coordinate of this point
     */
    public function setY($y) {
        $this->_y = (int) $y;
    }

    /**
     * Sets the location of the point
     *
     * @param Glitch_Image_point|integer $param1              A point or X-coordinate
     * @param integer                  $param2 (Optional)   The Y-coordinate
     */
    public function setLocation($param1,$param2 = null) {
        if($param1 instanceof Glitch_Image_Point) {
            $param2 = $param1->getY();
            $param1 = $param1->getX();
        }

        if($param1 !== null) {
            $this->setX($param1);
        }

        if($param2 !== null) {
            $this->setY($param2);
        }
    }

    public function getLocation() {
        return array('x' => $this->getX(),
                     'y' => $this->getY());
    }
}
