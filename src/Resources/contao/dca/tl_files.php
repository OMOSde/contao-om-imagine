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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_files']['palettes']['default'] .= ';{exif_legend},exif';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_files']['fields']['exif'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_files']['exif'],
    'inputType' => 'exifWizard'
];
