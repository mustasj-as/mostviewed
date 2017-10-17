<?php
namespace Craft;

/**
 * Entry Count Plugin
 */
class MostViewedPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Most Viewed');
    }

    public function getVersion()
    {
        return '0.1.0';
    }

    public function getDeveloper()
    {
        return 'Mustasj';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.mustasj.no';
    }

    protected function defineSettings()
    {
        return array(
            'ignoreLoggedInUsers' => array(AttributeType::Bool, 'default' => 0),
			'daysToKeep' => array(AttributeType::Number, 'default' => 30)
        );
    }

    public function getSettingsHtml()
    {
       return craft()->templates->render('mostviewed/settings', array(
           'settings' => $this->getSettings()
       ));
    }

    public function hasCpSection()
    {
        return true;
    }
}
