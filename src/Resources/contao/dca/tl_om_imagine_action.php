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
 * Table tl_om_imagine_action
 */
$GLOBALS['TL_DCA']['tl_om_imagine_action'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'ptable'           => 'tl_om_imagine',
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'index'
            ]
        ]
    ],

    // List
    'list'        => [
        'sorting'           => [
            'mode'                  => 4,
            'fields'                => ['sorting'],
            'flag'                  => 1,
            'panelLayout'           => 'filter;sort,search,limit',
            'headerFields'          => ['title'],
            'child_record_callback' => ['tl_om_imagine_action', 'childRecordCallback']
        ],
        'label'             => [
            'fields' => ['title']
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'toggle' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['tl_om_imagine_action', 'toggleIcon']
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ]
        ]
    ],

    // Palettes
    'palettes'    => [
        '__selector__' => ['type', 'effectType'],
        'default'      => '{title_legend},title;{type_legend},type',
        'watermark'    => '{title_legend},title;{type_legend},type;{watermark_legend},watermark;{position_legend},position,margins;{active_legend},active',
        'effect'       => '{title_legend},title;{type_legend},type;{effect_legend},effectType;{active_legend},active',
        'text'         => '{title_legend},title;{type_legend},type;{text_legend},text,fontfile,fontsize,color;{position_legend},position,margins;{active_legend},active',
    ],

    // Subpalettes
    'subpalettes' => [
        'effectType_colorize' => 'color',
        'effectType_blur'     => 'sigma',
        'effectType_gamma'    => 'gamma'
    ],

    // Fields
    'fields'      => [
        'id'         => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid'        => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'tstamp'     => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'sorting'    => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'type'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['type'],
            'inputType' => 'select',
            'options'   => ['watermark', 'effect', 'text'],
            'reference' => $GLOBALS['TL_LANG']['tl_om_imagine_action']['types'],
            'eval'      => ['mandatory' => true, 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => false, 'tl_class' => 'w50'],
            'sql'       => "varchar(24) NOT NULL default ''"
        ],
        'title'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['title'],
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
            'sql'       => "varchar(64) NOT NULL default ''"
        ],
        'watermark'  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['watermark'],
            'inputType' => 'fileTree',
            'eval'      => ['fieldType' => 'radio', 'files' => true, 'extensions' => 'jpg,gif,png', 'filesOnly' => true, 'mandatory' => true, 'tl_class' => 'clr'],
            'sql'       => "binary(16) NULL"
        ],
        'effectType' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['effectType'],
            'inputType' => 'select',
            'options'   => ['grayscale', 'colorize', 'blur', 'negative', 'gamma'],
            'reference' => $GLOBALS['TL_LANG']['tl_om_imagine_action']['effectTypes'],
            'eval'      => ['mandatory' => true, 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => false, 'tl_class' => 'w50'],
            'sql'       => "varchar(32) NOT NULL default ''"
        ],
        'position'   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['position'],
            'inputType' => 'select',
            'options'   => ['top-left', 'top-center', 'top-right', 'center-left', 'center-center', 'center-right', 'bottom-left', 'bottom-center', 'bottom-right'],
            'reference' => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['positions'],
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "varchar(20) NOT NULL default ''"
        ],
        'margins'    => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['margins'],
            'inputType' => 'text',
            'eval'      => ['size' => 2, 'multiple' => true, 'mandatory' => true, 'maxlength' => 16, 'tl_class' => 'w50'],
            'sql'       => "varchar(32) NOT NULL default ''"
        ],
        'color'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['color'],
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 6, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'clr w50 wizard'],
            'sql'       => "varchar(8) NOT NULL default ''"
        ],
        'sigma'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['sigma'],
            'default'   => 1,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'tl_class' => 'clr w50'],
            'sql'       => "int(10) NOT NULL default '0'"
        ],
        'gamma'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['gamma'],
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 12, 'tl_class' => 'clr w50'],
            'sql'       => "varchar(12) NOT NULL default '0'"
        ],
        'text'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['text'],
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 256, 'tl_class' => 'clr long'],
            'sql'       => "varchar(256) NOT NULL default ''"
        ],
        'fontfile'   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['fontfile'],
            'default'   => 1,
            'inputType' => 'fileTree',
            'eval'      => ['mandatory' => true, 'files' => true, 'filesOnly' => true, 'extension' => 'ttf', 'tl_class' => 'clr'],
            'sql'       => "binary(16) NULL"
        ],
        'fontsize'   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['fontsize'],
            'default'   => 12,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'tl_class' => 'clr w50'],
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ],
        'active'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine_action']['active'],
            'inputType' => 'checkbox',
            'eval'      => ['doNotCopy' => true],
            'sql'       => "char(1) NOT NULL default ''"
        ]
    ]
];


/**
 * Class tl_om_imagine_action
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_om_imagine_action extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();

        $this->import('BackendUser', 'User');
    }


    /**
     * @param $arrRow
     */
    public function childRecordCallback($arrRow)
    {
        return $arrRow['title'];
    }


    /**
     * Return the "toggle visibility" button
     *
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_imagine_action::active', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['active'] ? '' : 1);

        if (!$row['active'])
        {
            $icon = 'invisible.gif';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['active'] ? 0 : 1) . '"') . '</a> ';
    }


    /**
     * Disable/enable a action
     *
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_imagine_action::active', 'alexf'))
        {
            \System::getContainer()->get('monolog.logger.contao')->log(LogLevel::ERRO, sprintf('Not enough permissions to publish/unpublish image manipulation action ID "' . $intId . '"', 'tl_om_imagine_action toggleVisibility', $intId));
            $this->redirect('contao/main.php?act=error');
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_om_imagine_action SET tstamp=" . time() . ", active='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);
    }
}
