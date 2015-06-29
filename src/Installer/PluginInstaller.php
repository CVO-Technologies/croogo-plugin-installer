<?php

namespace Croogo\Composer\Installer;

use Cake\Composer\Installer\PluginInstaller as CakePluginInstaller;
use Composer\Installer\LibraryInstaller;
use Composer\Script\Event;

class PluginInstaller extends LibraryInstaller
{

    protected static $_corePlugins = [
        'Acl', 'Blocks', 'Core', 'Comments', 'Contacts', 'Dashboards', 'Example', 'Extensions', 'FileManager', 'Install',
        'Menus', 'Meta', 'Nodes', 'Settings', 'Taxonomy', 'Translate', 'Users', 'Wysiwyg'
    ];

    public static function postAutoloadDump(Event $event)
    {
        $composer = $event->getComposer();
        $config = $composer->getConfig();

        $vendorDir = realpath($config->get('vendor-dir'));

        $packages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();
        $pluginsDir = dirname($vendorDir) . DIRECTORY_SEPARATOR . 'plugins';

        $plugins = CakePluginInstaller::determinePlugins($packages, $pluginsDir, $vendorDir);

        foreach (self::$_corePlugins as $plugin) {
            static::addPlugin($plugin, $vendorDir, $plugins);
        }

        $configFile = static::_configFile($vendorDir);
        CakePluginInstaller::writeConfigFile($configFile, $plugins);
    }

    public static function addPlugin($plugin, $vendorDir, &$plugins)
    {
        $plugins['Croogo/' . $plugin] = static::_pluginPath($vendorDir, $plugin);
    }

    protected static function _pluginPath($vendorDir, $plugin)
    {
        return $vendorDir . DIRECTORY_SEPARATOR . 'croogo' . DIRECTORY_SEPARATOR . 'croogo' . DIRECTORY_SEPARATOR  . $plugin;
    }

    /**
     * Path to the plugin config file
     *
     * @param string $vendorDir path to composer-vendor dir
     * @return string absolute file path
     */
    protected static function _configFile($vendorDir)
    {
        return $vendorDir . DIRECTORY_SEPARATOR . 'cakephp-plugins.php';
    }

}
