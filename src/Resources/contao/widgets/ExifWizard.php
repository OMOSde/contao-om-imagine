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
 * Provide methods to show exif information.
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ExifWizard extends \Widget
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'be_widget';


    /**
     * Generate the widget and return it as string
     *
     * @return string
     */
    public function generate()
    {
        // supported image file extensions
        $arrExtensions = ['gif', 'jpeg', 'jpg', 'png'];

        // check for file
        $objFile = \FilesModel::findByPath(\Input::get('id'));
        if (!$objFile || !file_exists(TL_ROOT . '/' . $objFile->path))
        {
            return sprintf('<p class="tl_help tl_tip" title="">%s</p>', $GLOBALS['TL_LANG']['tl_files']['exif']['file_not_found']);
        }

        // check file extension
        if (!in_array($objFile->extension, $arrExtensions))
        {
            return '<p class="tl_help tl_tip" title="">' . sprintf($GLOBALS['TL_LANG']['tl_files']['exif']['wrong_extension'], implode(', ', $arrExtensions)) . '</p>';
        }

        // get exif data
        $objImagine = new \Imagine\Gd\Imagine();
        $objImage = $objImagine->open($objFile->path);
        $arrMeta = $objImage->metadata();

        // group data in arrays
        foreach ($arrMeta as $strKey => $mxdValue)
        {
            $arrValues = explode('.', $strKey);
            if (count($arrValues) == 2)
            {
                $arrGroups[$arrValues[0]][] = [$arrValues[1], $mxdValue];
            }
        }

        // check for exif data
        if (!is_array($arrGroups) || empty($arrGroups))
        {
            return '<p class="tl_help tl_tip" title="">' . sprintf($GLOBALS['TL_LANG']['tl_files']['exif']['no_exif_data'], implode(', ', $arrExtensions)) . '</p>';
        }

        // generate html
        $strHtml = '';
        foreach ($arrGroups as $strKey => $arrValues)
        {
            $strHtml .= '<table class="exif">';
            $strHtml .= sprintf('<tr><th colspan="2">%s</th></tr>', $strKey);
            foreach ($arrValues as $mxdValue)
            {
                if (in_array($mxdValue[0], ['ComponentsConfiguration']))
                {
                    $strHtml .= sprintf('<tr><td>%s</td><td>%s</td></tr>', $mxdValue[0], '\x' . implode('\x', str_split(bin2hex($mxdValue[1]), 2)));
                }
                else
                {
                    $strHtml .= sprintf('<tr><td>%s</td><td>%s</td></tr>', $mxdValue[0], $mxdValue[1]);
                }
            }
            $strHtml .= '</table>';
        }
        $strHtml .= sprintf('<p class="tl_help tl_tip">%s</p>', $GLOBALS['TL_LANG']['tl_files']['exif']['success']);

        return $strHtml;
    }
}
