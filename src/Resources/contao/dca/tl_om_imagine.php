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
 * Table tl_om_imagine
 */
$GLOBALS['TL_DCA']['tl_om_imagine'] = [

    // Config
    'config'   => [
        'dataContainer'    => 'Table',
        'ctable'           => ['tl_om_imagine_action'],
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    // List
    'list'     => [
        'sorting'           => [
            'mode'        => 1,
            'fields'      => ['title'],
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label'             => [
            'fields' => ['title'],
            'format' => '%s'
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
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine']['edit'],
                'href'  => 'table=tl_om_imagine_action',
                'icon'  => 'edit.gif'
            ],
            'header' => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine']['header'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif'
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_om_imagine']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_om_imagine']['delete_confirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'toggle' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_om_imagine']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['tl_om_imagine', 'toggleIcon']
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_imagine']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ]
        ]
    ],

    // Palettes
    'palettes' => [
        '__selector__' => ['addSave'],
        'default'      => '{title_legend},title;{directory_legend},directory;{watermark_legend},watermark;{setting_legend};{publish_legend},published'
    ],

    // Subpalettes
    'subpalettes' => [
        'addSave' => 'saveDirectory'
    ],

    // Fields
    'fields'   => [
        'id'        => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp'    => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['title'],
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 64],
            'sql'       => "varchar(64) NOT NULL default ''"
        ],
        'directory' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['directory'],
            'inputType' => 'fileTree',
            'eval'      => ['multiple' => true, 'fieldType' => 'checkbox', 'files' => false, 'mandatory' => true],
            'sql'       => "blob NULL"
        ],
        'addSave'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['addSave'],
            'inputType' => 'checkbox',
            'eval'      => ['fieldType' => 'checkbox', 'submitOnChange' => true, 'tl_class' => 'clr'],
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'saveDirectory'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['saveDirectory'],
            'inputType' => 'fileTree',
            'eval'      => ['fieldType' => 'checkbox', 'files' => false, 'mandatory' => true, 'tl_class' => 'clr'],
            'sql'       => "blob NULL"
        ],
        'log'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['log'],
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr w50'],
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'overwrite' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['overwrite'],
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr w50'],
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'published' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_imagine']['published'],
            'inputType' => 'checkbox',
            'eval'      => ['doNotCopy' => true],
            'sql'       => "char(1) NOT NULL default ''"
        ]
    ]
];


/**
 * Class tl_om_imagine
 *
 * @copyright OMOS.de <https://www.omos.de>
 * @author    René Fehrmann
 */
class tl_om_imagine extends Backend
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_imagine::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }


    /**
     * Disable/enable an backend link
     *
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_imagine::published', 'alexf'))
        {
            // logging & redirect
            \System::getContainer()->get('monolog.logger.contao')->log(LogLevel::ERRO, sprintf('Not enough permissions to publish/unpublish image manipulation ID "%s"', $intId));

            $this->redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_om_imagine', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_om_imagine']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_om_imagine']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $this);
                }
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_om_imagine SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);

        // create a new version
        $objVersions->create();

        // logging
        \System::getContainer()->get('monolog.logger.contao')->log(LogLevel::INFO, sprintf('A new version of record "tl_om_imagine.id=%s" has been created %s', $intId, $this->getParentEntries('tl_om_imagine', $intId)));
    }
}
