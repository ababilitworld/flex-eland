<?php
namespace Ababilithub\FlexELand\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexELand\Package\Plugin\Menu\V1\Manager\Menu as MenuManager,
    FlexELand\Package\Plugin\Route\V1\Manager\Route as RouteManager,
    FlexELand\Package\Plugin\Taxonomy\V1\Manager\Taxonomy as TaxonomyManager,
    FlexELand\Package\Plugin\Posttype\V1\Manager\Posttype as PosttypeManager,
    FlexELand\Package\Plugin\Shortcode\V1\Manager\Shortcode as ShortcodeManager, 
};

if (!class_exists(__NAMESPACE__.'\Production')) 
{
    class Production 
    {
        use StandardMixin;

        public function __construct($data = []) 
        {
            $this->init();      
        }

        public function init() 
        {

            add_action('init', function () {
                (new TaxonomyManager())->boot();
            });

            add_action('init', function () {
                (new PosttypeManager())->boot();
            });

            add_action('init', function () {
                (new ShortcodeManager())->boot();
            });

            add_action('init', function () {
                (new MenuManager())->boot();
            });
        }
        
    }
}