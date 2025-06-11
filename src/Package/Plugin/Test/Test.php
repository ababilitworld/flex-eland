<?php
namespace Ababilithub\FlexELand\Package\Plugin\Test;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexELand\Package\Plugin\Menu\Menu as TestMenu,
};

use const Ababilithub\{
    FlexELand\PLUGIN_NAME,
    FlexELand\PLUGIN_DIR,
    FlexELand\PLUGIN_URL,
    FlexELand\PLUGIN_FILE,
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_VERSION
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