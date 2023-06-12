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
 * Use
 */
use Contao\Backend;
use Imagine\Image\Point;
use Imagine\Image\Palette\RGB;
use Imagine\Gd\Font;


/**
 * Class Hooks
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Imagine extends Backend
{
    /**
     * Hook postUpload
     *
     * @param $arrFiles
     * @param $strType
     */
    public static function handleFiles($arrFiles, $strType)
    {
        // do if nothing if no file in array
        if (count($arrFiles) == 0)
        {
            return;
        }

        // get all active manipulations and do nothing if none exists
        $objImagine = OmImagineModel::findBy(['published=?', 'directory<>""'], [1]);
        if (!is_object($objImagine))
        {
            return;
        }

        // do for all active manipulations
        foreach ($objImagine as $objManipulation)
        {
            // get all active actions for this manipulation and check next if none exists
            $objActions = OmImagineActionModel::findBy(['pid=?', 'active=1'], [$objManipulation->id], ['order' => 'sorting ASC']);
            if (!$objActions)
            {
                continue;
            }

            // check for directories
            $objDirectories = \FilesModel::findMultipleByIds(deserialize($objManipulation->directory, true));
            if (!$objDirectories)
            {
                continue;
            }

            // create an array with the target directories
            foreach ($objDirectories as $directory)
            {
                $arrDirectories[] = $directory->path;
            }

            // handle all files
            foreach ($arrFiles as $strFile)
            {
                // handle type
                if ($strType == 'backend')
                {
                    $strFile = TL_ROOT . '/' . $strFile;
                }

                // check if the file exists
                if (!file_exists($strFile))
                {
                    continue;
                }

                // get path info of file
                $arrPathInfo = pathinfo($strFile);

                // check file extension
                if (!in_array(strtolower($arrPathInfo['extension']), ['gif', 'jpg', 'png']))
                {
                    continue;
                }

                //
                foreach ($arrDirectories as $strDirectory)
                {
                    //
                    if (!strpos($arrPathInfo['dirname'], $strDirectory) !== false)
                    {
                        continue;
                    }

                    (new Imagine)->handleActions($strFile, $objManipulation, $objActions);
                }
            }
        }
    }


    /**
     * Handle button edit
     *
     * @param $arrFiles
     * @param $intManipulation
     */
    public static function handleButtonEdit($arrFiles, $intManipulation)
    {
        // do if nothing if no file in array
        if (count($arrFiles) == 0)
        {
            return;
        }

        // get manipulation
        $objManipulation = OmImagineModel::findByPk($intManipulation);
        if (!is_object($objManipulation))
        {
            return;
        }

        // get all active actions for this manipulation and check next if none exists
        $objActions = OmImagineActionModel::findBy(['pid=?', 'active=1'], [$objManipulation->id], ['order' => 'sorting ASC']);
        if (!$objActions)
        {
            return;
        }

        // handle all files
        foreach ($arrFiles as $strFile)
        {
            // check if the file exists
            if (!file_exists($strFile))
            {
                continue;
            }

            // get path info of file
            $arrPathInfo = pathinfo($strFile);

            // check file extension
            if (!in_array(strtolower($arrPathInfo['extension']), ['gif', 'jpg', 'png']))
            {
                continue;
            }

            // do it
            (new Imagine)->handleActions($strFile, $objManipulation, $objActions);
        }
    }


    /**
     * Handle all manipulation actions
     *
     * @param $strFile
     * @param $objManipulation
     * @param $objActions
     */
    protected function handleActions($strFile, $objManipulation, $objActions)
    {
        foreach ($objActions as $objAction)
        {
            switch ($objAction->type)
            {
                // handle type watermark
                case 'watermark':
                    $this->addWatermark($strFile, $objManipulation, $objAction);
                    break;

                // handle type effect
                case 'effect':
                    $this->addEffect($strFile, $objManipulation, $objAction);
                    break;

                // handle type text
                case 'text':
                    $this->addText($strFile, $objManipulation, $objAction);
                    break;

                default:
                    break;
            }
        }
    }


    /**
     * Add an image element to the uploaded image
     *
     * @param $strFile
     * @param $objManipulation
     * @param $objAction
     */
    protected function addWatermark($strFile, $objManipulation, $objAction)
    {
        $objWatermark = \FilesModel::findByUuid($objAction->watermark);

        // file exists?
        if (!file_exists(TL_ROOT . '/' . $objWatermark->path))
        {
            //continue;
        }

        //
        $imagine = new \Imagine\Gd\Imagine();

        // create image objects
        $objImage = $imagine->open($strFile);
        $objImageWatermark = $imagine->open(TL_ROOT . '/' . $objWatermark->path);

        // get margins
        $arrMargin = deserialize($objAction->margins);

        // get position on image
        switch ($objAction->position)
        {
            case 'top-left':
                $objPosition = new Point((int) $arrMargin[0], (int) $arrMargin[1]);
                break;
            case 'top-center':
                $objPosition = new Point($objImage->getSize()->getWidth() / 2 - $objImageWatermark->getSize()->getWidth() / 2, (int) $arrMargin[1]);
                break;
            case 'top-right':
                $objPosition = new Point($objImage->getSize()->getWidth() - $objImageWatermark->getSize()->getWidth() - (int) $arrMargin[0], (int) $arrMargin[1]);
                break;
            case 'center-left':
                $objPosition = new Point((int) $arrMargin[0], $objImage->getSize()->getHeight() / 2 - $objImageWatermark->getSize()->getHeight() / 2);
                break;
            case 'center-right':
                $objPosition = new Point($objImage->getSize()->getWidth() - $objImageWatermark->getSize()->getWidth() - (int) $arrMargin[0], $objImage->getSize()->getHeight() / 2 - $objImageWatermark->getSize()->getHeight() / 2);
                break;
            case 'bottom-left':
                $objPosition = new Point((int) $arrMargin[0], $objImage->getSize()->getHeight() - $objImageWatermark->getSize()->getHeight() - (int) $arrMargin[1]);
                break;
            case 'bottom-center':
                $objPosition = new Point($objImage->getSize()->getWidth() / 2 - $objImageWatermark->getSize()->getWidth() / 2, $objImage->getSize()->getHeight() - $objImageWatermark->getSize()->getHeight() - (int) $arrMargin[1]);
                break;
            case 'bottom-right':
                $objPosition = new Point($objImage->getSize()->getWidth() - $objImageWatermark->getSize()->getWidth() - (int) $arrMargin[0],
                    $objImage->getSize()->getHeight() - $objImageWatermark->getSize()->getHeight() - (int) $arrMargin[1]);
                break;
            case 'center-center':
            default:
                $objPosition = new Point($objImage->getSize()->getWidth() / 2 - $objImageWatermark->getSize()->getWidth() / 2, $objImage->getSize()->getHeight() / 2 - $objImageWatermark->getSize()->getHeight() / 2);
                break;
        }

        // add watermark
        $objImage->paste($objImageWatermark, $objPosition);

        // save file
        $objImage->save($strFile);
    }


    /**
     * Add an effect to the image
     *
     * @param $strFile
     * @param $objManipulation
     * @param $objAction
     */
    protected function addEffect($strFile, $objManipulation, $objAction)
    {
        // open image
        $imagine = new \Imagine\Gd\Imagine();
        $objImage = $imagine->open($strFile);

        // handle effects
        switch ($objAction->effectType)
        {
            case 'grayscale':
                $objImage->effects()->grayscale();
                break;

            case 'colorize':
                $objColor = $objImage->palette()->color('#' . $objAction->color);
                $objImage->effects()->colorize($objColor);
                break;

            case 'blur':
                $objImage->effects()->blur((int) $objAction->sigma);
                break;

            case 'negative':
                $objImage->effects()->negative();
                break;

            case 'gamma':
                $objImage->effects()->gamma((float) $objAction->gamma);
                break;

            default:
                break;
        }

        // save image
        $objImage->save($strFile);
    }


    /**
     * Add text to the image
     *
     * @param $strFile
     * @param $objManipulation
     * @param $objAction
     */
    protected function addText($strFile, $objManipulation, $objAction)
    {
        // get fontfile and check if file exists
        $objFontFile = \FilesModel::findByUuid($objAction->fontfile);
        if (!$objFontFile || !file_exists($objFontFile->path))
        {
            return;
        }

        // open image
        $imagine = new \Imagine\Gd\Imagine();
        $objImage = $imagine->open($strFile);

        // handle font
        $objPalette = new RGB();
        $objFont = new Font(TL_ROOT . '/' . $objFontFile->path, $objAction->fontsize, $objPalette->color('#' . $objAction->color, 100));
        $objTextBox = $objFont->box($objAction->text);

        // get margins
        $arrMargin = deserialize($objAction->margins);

        // get position on image
        switch ($objAction->position)
        {
            case 'top-left':
                $objPosition = new Point((int) $arrMargin[0], (int) $arrMargin[1]);
                break;
            case 'top-center':
                $objPosition = new Point($objImage->getSize()->getWidth() / 2 - $objTextBox->getWidth() / 2, (int) $arrMargin[1]);
                break;
            case 'top-right':
                $objPosition = new Point($objImage->getSize()->getWidth() - $objTextBox->getWidth() - (int) $arrMargin[0], (int) $arrMargin[1]);
                break;
            case 'center-left':
                $objPosition = new Point((int) $arrMargin[0], $objImage->getSize()->getHeight() / 2 - $objTextBox->getHeight() / 2);
                break;
            case 'center-right':
                $objPosition = new Point($objImage->getSize()->getWidth() - $objTextBox->getWidth() - (int) $arrMargin[0], $objImage->getSize()->getHeight() / 2 - $objTextBox->getHeight() / 2);
                break;
            case 'bottom-left':
                $objPosition = new Point((int) $arrMargin[0], $objImage->getSize()->getHeight() - $objTextBox->getHeight() - (int) $arrMargin[1]);
                break;
            case 'bottom-center':
                $objPosition = new Point($objImage->getSize()->getWidth() / 2 - $objTextBox->getWidth() / 2, $objImage->getSize()->getHeight() - $objTextBox->getHeight() - (int) $arrMargin[1]);
                break;
            case 'bottom-right':
                $objPosition = new Point($objImage->getSize()->getWidth() - $objTextBox->getWidth() - (int) $arrMargin[0], $objImage->getSize()->getHeight() - $objTextBox->getHeight() - (int) $arrMargin[1]);
                break;
            case 'center-center':
            default:
                $objPosition = new Point($objImage->getSize()->getWidth() / 2 - $objTextBox->getWidth() / 2, $objImage->getSize()->getHeight() / 2 - $objTextBox->getHeight() / 2);
                break;
        }

        // set text on image
        $objImage->draw()->text($objAction->text, $objFont, $objPosition);

        // save image
        $objImage->save($strFile);
    }
}
