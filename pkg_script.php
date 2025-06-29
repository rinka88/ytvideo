<?php
/*
 * @package     Joomla.Package
 * @copyright   Copyright (C) Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace AlekVolsk\Package\Ytvideo\Installer;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Installer\Adapter\PackageAdapter; // Parent adapter for package scripts

class Script extends InstallerScript // Or extends PackageAdapter for more specific package methods
{
    /**
     * Minimum Joomla version required by this package.
     *
     * @var string
     */
    private const MINIMUM_JOOMLA_VERSION = '5.0.0'; // Example, align with targetplatform

    /**
     * Method to run before an install/update/uninstall method
     *
     * @param   string           $type    The type of change (install, update or discover_install)
     * @param   PackageAdapter   $parent  The class calling this method
     *
     * @return  boolean  True on success
     */
    public function preflight($type, $parent)
    {
        if (strtolower($type) === 'uninstall') {
            return true;
        }

        // Check for Joomla version (using constant for clarity)
        $jVersion = new Version();
        if (version_compare($jVersion->getShortVersion(), self::MINIMUM_JOOMLA_VERSION, 'lt')) {
            // JText strings should be defined in the package's sys.ini language file
            Factory::getApplication()->enqueueMessage(
                Text::sprintf('PKG_YTVIDEO_ERROR_JOOMLA_VERSION_TOO_LOW', Text::_($parent->get('name')), self::MINIMUM_JOOMLA_VERSION, $jVersion->getShortVersion()),
                'error'
            );
            return false;
        }

        // Check for PHP version (from manifest)
        $minPhpVersion = (string) $parent->getManifest()->php_minimum;
        if ($minPhpVersion && version_compare(PHP_VERSION, $minPhpVersion, 'lt')) {
            Factory::getApplication()->enqueueMessage(
                Text::sprintf('PKG_YTVIDEO_ERROR_PHP_VERSION_TOO_LOW', Text::_($parent->get('name')), $minPhpVersion, PHP_VERSION),
                'error'
            );
            return false;
        }

        return true;
    }

    // Optional: Implement install, update, uninstall if needed
    // public function install($parent) { Factory::getApplication()->enqueueMessage('Installed ' . $parent->get('name')); return true;}
    // public function update($parent) { Factory::getApplication()->enqueueMessage('Updated ' . $parent->get('name')); return true;}
    // public function uninstall($parent) { Factory::getApplication()->enqueueMessage('Uninstalled ' . $parent->get('name')); return true;}
}
