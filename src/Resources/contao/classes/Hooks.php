<?php

/**
 * Contao bundle contao-om-imagine
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */


/**
 * Namespace
 */
namespace OMOSde\ContaoOmImagineBundle;


/**
 * Class Hooks
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Hooks extends \Backend
{
    /**
     * Hooks constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Handle manipulations for frontend forms
     *
     * @param $arrPost
     * @param $arrForm
     * @param $arrFiles
     */
    public function handleManipulationsFrontend($arrPost, $arrForm, $arrFiles)
    {
        // check file array
        if (count($arrFiles) > 0)
        {
            // put files in array
            foreach ($arrFiles as $file)
            {
                $arrFormFiles[] = $file['tmp_name'];
            }

            // execute manipulations
            \OMOSde\ContaoOmImagineBundle\Imagine::handleFiles($arrFormFiles, 'frontend');
        }
    }


    /**
     * Handle manipulations for backend file uploads
     *
     * @param $arrFiles
     */
    public function handleManipulationsBackend($arrFiles)
    {
        // check file array
        if (count($arrFiles) > 0)
        {
            // execute manipulations
            \OMOSde\ContaoOmImagineBundle\Imagine::handleFiles($arrFiles, 'backend');
        }
    }
}
