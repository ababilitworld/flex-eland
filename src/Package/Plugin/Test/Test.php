<?php
namespace Ababilithub\FlexEland\Package\Plugin\Test;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexEland\Package\Plugin\Menu\Menu as TestMenu,
};

use const Ababilithub\{
    FlexEland\PLUGIN_NAME,
    FlexEland\PLUGIN_DIR,
    FlexEland\PLUGIN_URL,
    FlexEland\PLUGIN_FILE,
    FlexEland\PLUGIN_PRE_UNDS,
    FlexEland\PLUGIN_PRE_HYPH,
    FlexEland\PLUGIN_VERSION
};

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

if (!class_exists(__NAMESPACE__.'\Test')) 
{
    class Test 
    {
        use StandardMixin;
        private $menu;

        public function __construct($data = []) 
        {
            $this->init($data); 
            
        }

        public function init($data) 
        {
            $this->menu = TestMenu::getInstance();      
        }
    }
}