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
 * Add stylesheet to the backend
 */
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoomimagine/css/contao-om-imagine.css|static';
}


/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['system']['tl_om_imagine'] = [
    'tables' => ['tl_om_imagine', 'tl_om_imagine_action'],
];


/**
 * Backend form fields
 */
$GLOBALS['BE_FFL']['exifWizard'] = 'OMOSde\ContaoOmImagineBundle\ExifWizard';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['postUpload'][] = ['OMOSde\ContaoOmImagineBundle\Hooks', 'handleManipulationsBackend'];
$GLOBALS['TL_HOOKS']['processFormData'][] = ['OMOSde\ContaoOmImagineBundle\Hooks', 'handleManipulationsFrontend'];
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = ['OMOSde\ContaoOmImagineBundle\Hooks', 'addButtonManipulate'];
$GLOBALS['TL_HOOKS']['initializeSystem'][] = ['OMOSde\ContaoOmImagineBundle\Hooks', 'handleButtonManipulate'];


/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_om_imagine'] = 'OMOSde\ContaoOmImagineBundle\OmImagineModel';
$GLOBALS['TL_MODELS']['tl_om_imagine_action'] = 'OMOSde\ContaoOmImagineBundle\OmImagineActionModel';
