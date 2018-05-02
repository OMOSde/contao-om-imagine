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
use \Wa72\HtmlPageDom\HtmlPageCrawler;


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


    /**
     * Handle manipulations selected by backend buttons
     */
    public function handleButtonManipulate()
    {
        // handle button edit
        if ($_POST['FORM_SUBMIT'] == 'tl_files' && $_POST['manipulate'] == '')
        {
            // manipulate the selected image
            Imagine::handleButtonEdit([TL_ROOT.'/'.$_GET['id']], $_POST['manipulation']);
        }

        // handle button edit all
        if ($_POST['FORM_SUBMIT'] == 'tl_select' && $_POST['manipulate'] == '')
        {
            if (is_array($_POST['IDS']) && !empty($_POST['IDS']))
            {
                foreach ($_POST['IDS'] as $strFile)
                {
                    $arrFiles[] = TL_ROOT . '/' . $strFile;
                }

                // manipulate the selected files
                Imagine::handleButtonEdit($arrFiles, $_POST['manipulation']);

                // redirect
                $arrUrlParts = explode('&', \Environment::get('request'));
                \Controller::redirect($arrUrlParts[0]);
            }
        }
    }


    /**
     * Add a button on edit an image file
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return string
     */
    public function addButtonManipulate($strContent, $strTemplate)
    {
        // check for template and edit page
        if ($strTemplate != 'be_main' || \Input::get('do') != 'files')
        {
            return $strContent;
        }

        // check for action
        if (\Input::get('act') != 'edit' && \Input::get('act') != 'select')
        {
            return $strContent;
        }

        // handle different actions
        $strButtonText = (\Input::get('act') == 'edit') ? 'manipulate_image' : 'manipulate_images';

        // get manipulations
        $objManipulations = OmImagineModel::findByPublished(1);
        if (!$objManipulations)
        {
            return $strContent;
        }

        // create html
        $strHtml = '<div class="contao-om-imagine"><select class="tl_select" name="manipulation">';
        $strHtml .= '<option value="">-</option>';
        foreach ($objManipulations as $objManipulation)
        {
            $strHtml .= sprintf('<option value="%s">%s</option>', $objManipulation->id, $objManipulation->title);
        }
        $strHtml .= sprintf('</select><button type="submit" name="manipulate" id="manipulate" class="tl_submit" accesskey="s">%s</button></div>', $GLOBALS['TL_LANG']['MSC'][$strButtonText]);

        // add html to dom
        $objCrawler = HtmlPageCrawler::create($strContent);
        $objCrawler->filter('.tl_submit_container')->append($strHtml);

        // return new html
        return $objCrawler->saveHTML();
    }
}
