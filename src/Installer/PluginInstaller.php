<?php

namespace Croogo\Composer\Installer\PluginInstaller;

use Composer\Installer\LibraryInstaller;
use Composer\Script\Event;

class PluginInstaller extends LibraryInstaller
{

	public static function postAutoloadDump(Event $event)
	{
		print_r('Hello!');
	}

}