<?php
namespace Ababilithub\FlexEland\Package\Plugin\Menu\V1\Concrete\Shortcode\DeedList;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Menu\V1\Base\Menu as BaseMenu,
    FlexEland\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Posttype as LandDeedPosttype,
};

use const Ababilithub\{
    FlexEland\PLUGIN_PRE_UNDS,
    FlexEland\PLUGIN_PRE_HYPH,
};

if (!class_exists(__NAMESPACE__.'\Menu')) 
{

    class Menu extends BaseMenu
    {
        private $option_box;

        public function init(array $data = []) : static
        {
            $this->menu_filter_name = PLUGIN_PRE_UNDS.'_admin_menu';
            $this->init_service();
            $this->init_hook();
            return $this;
        }

        public function init_service() : void
        {
            //
        }

        public function init_hook() : void
        {
            // Add filter to collect menu items
            add_filter($this->menu_filter_name, [$this, 'add_menu_items']);
            
        }

        /**
         * Add default menu items
         */
        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'flex-eland',
                'page_title' => 'Deed List',
                'menu_title' => 'Deed List',
                'capability' => 'manage_options',
                'menu_slug' => 'flex-eland-deed-list',
                'callback' => [$this,'render'],
                'position' => 6,
            ];

            return $menu_items;
        }

        /**
         * Custom main page render
         */
        public function render(): void
        {
            echo do_shortcode('['.PLUGIN_PRE_HYPH.'-'.LandDeedPosttype::POSTTYPE.'-'.'list'.']');
        }
        
    }
}
