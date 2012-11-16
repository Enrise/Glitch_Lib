<?php
// require_once 'Zend/Image/Color.php';
// require_once 'Zend/Image/Adapter/Gd/Action/ActionAbstract.php';

// require_once 'Zend/Image/Point.php';
// require_once 'Zend/Image/Action/DrawPolygon.php';
// require_once 'Zend/Image/Adapter/Gd/Action/DrawPolygon.php';

class Glitch_Image_Adapter_Gd_Action_DrawLine
    extends Glitch_Image_Adapter_Gd_Action_ActionAbstract
{
    /**
     * Draw a line on the image, returns the GD-handle
     *
     * @param  GD image resource    $handle Image to work on
     * @param  Glitch_Image_Action_DrawLine   $lineObject The object containing all settings needed for drawing a line.
     * @return void
     */
    public function perform(Glitch_Image_Adapter_Gd $adapter,
       Glitch_Image_Action_DrawLine $lineObject)
    {
        $handle = $adapter->getHandle();
        $strokeColor = $lineObject->getStrokeColor();
        $color = $strokeColor->getRgb();
        $colorAlphaAlloc =     imagecolorallocatealpha($handle,
                                                       $color['red'],
                                                       $color['green'],
                                                       $color['blue'],
                                                       $lineObject->getStrokeAlpha());

        if($lineObject->getStrokeWidth()==1) {
            // Simple line
            imageline($handle, $lineObject->getPointStart()->getX(),
                               $lineObject->getPointStart()->getY(),
                               $lineObject->getPointEnd()->getX(),
                               $lineObject->getPointEnd()->getY(),
//                               IMG_COLOR_STYLED);
                               $colorAlphaAlloc);

        } elseif($lineObject->getPointStart()->getX() == $lineObject->getPointEnd()->getX() ||
                 $lineObject->getPointStart()->getY() == $lineObject->getPointEnd()->getY())
        {
            // Simple thick line
            $x1 = round(min($lineObject->getPointStart()->getX(), $lineObject->getPointEnd()->getX()) - ( $lineObject->getStrokeWidth() / 2 - 0.5 ));
            $y1 = round(min($lineObject->getPointStart()->getY(), $lineObject->getPointEnd()->getY()) - ( $lineObject->getStrokeWidth() / 2 - 0.5 ));
            $x2 = round(max($lineObject->getPointStart()->getX(), $lineObject->getPointEnd()->getX()) + ( $lineObject->getStrokeWidth() / 2 - 0.5 ));
            $y2 = round(max($lineObject->getPointStart()->getY(), $lineObject->getPointEnd()->getY()) + ( $lineObject->getStrokeWidth() / 2 - 0.5 ));

            if($lineObject->isFilled()) {
                imagefilledrectangle($handle, $x1, $y1, $x2, $y2,$colorAlphaAlloc);
            } else {
                imagerectangle($handle, $x1, $y1, $x2, $y2,$colorAlphaAlloc);
            }

        } else {
            // Not horizontal nor vertical thick line
            $polygonObject = new Glitch_Image_Action_DrawPolygon();

            $slope = ($lineObject->getPointEnd()->getY() - $lineObject->getPointStart()->getY())
                     / ($lineObject->getPointEnd()->getX() - $lineObject->getPointStart()->getX()); // y = ax + b
            $a = ($lineObject->getStrokeWidth() / 2 - 0.5) / sqrt(1 + pow($slope,2));
            $points = array(new Glitch_Image_Point(round($lineObject->getPointStart()->getX() - (1+$slope)*$a),
                                                 round($lineObject->getPointStart()->getY() + (1-$slope)*$a)),
                            new Glitch_Image_Point(round($lineObject->getPointStart()->getX() - (1-$slope)*$a),
                                                 round($lineObject->getPointStart()->getY() - (1+$slope)*$a)),
                            new Glitch_Image_Point(round($lineObject->getPointEnd()->getX() + (1+$slope)*$a),
                                                 round($lineObject->getPointEnd()->getY() - (1-$slope)*$a)),
                            new Glitch_Image_Point(round($lineObject->getPointEnd()->getX() + (1-$slope)*$a),
                                                 round($lineObject->getPointEnd()->getY() + (1+$slope)*$a)));

            //Draw polygon
            $polygonObject = new Glitch_Image_Action_DrawPolygon(array('points'=>$points, 'strokeColor' => $strokeColor));
            $handler = new Glitch_Image_Adapter_Gd_Action_DrawPolygon();
            $handle = $handler->perform($adapter, $polygonObject);
        }

        return $handle;
    }
}
