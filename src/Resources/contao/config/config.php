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
 * Backend modules
 */
$GLOBALS['BE_MOD']['system']['tl_om_imagine'] = [
    'tables' => ['tl_om_imagine', 'tl_om_imagine_action'],
];


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['postUpload'][] = ['OMOSde\ContaoOmImagineBundle\Hooks', 'handleManipulationsBackend'];
$GLOBALS['TL_HOOKS']['processFormData'][] = ['OMOSde\ContaoOmImagineBundle\Hooks', 'handleManipulationsFrontend'];


/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_om_imagine'] = 'OMOSde\ContaoOmImagineBundle\OmImagineModel';
$GLOBALS['TL_MODELS']['tl_om_imagine_action'] = 'OMOSde\ContaoOmImagineBundle\OmImagineActionModel';
