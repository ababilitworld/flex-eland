<?php
namespace Ababilithub\FlexELand\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexELand\Package\Plugin\Menu\Menu as ProductionMenu,
};

if (!class_exists(__NAMESPACE__.'\Production')) 
{
    class Production 
    {
        use StandardMixin;
        private $menu;

        public function __construct($data = []) 
        {
            $this->init($data); 
            
        }

        public function init($data) 
        {
            $this->menu = ProductionMenu::getInstance();      
        }
    }
}