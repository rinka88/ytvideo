<?php
/*
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.ytvideo
 * @copyright   Copyright (C) Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */
namespace AlekVolsk\Plugin\EditorsXtd\YtVideobtn\Installer;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Installer\Adapter\PluginAdapter;

class Script extends InstallerScript
{
    /**
     * Method to run after an install/update/uninstall method
     *
     * @param   string            $type    The type of change (install, update or discover_install)
     * @param   PluginAdapter     $parent  The class calling this method
     *
     * @return  boolean  True on success
     */
    public function postflight($type, $parent)
    {
        if ($type === 'uninstall') {
            return true;
        }
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->update('#__extensions')
            ->set('enabled=1')
            ->where('type=' . $db->quote('plugin'))
            ->where('element=' . $db->quote('ytvideobtn'));
        $db->setQuery($query)->execute();
    }
}
