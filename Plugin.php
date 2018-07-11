<?php namespace Aldea\Travel;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
	public function registerMailTemplates()
    {
        return [
            'aldea.travel::mail.package' => 'Default template for sending email',
        ];
    }
}