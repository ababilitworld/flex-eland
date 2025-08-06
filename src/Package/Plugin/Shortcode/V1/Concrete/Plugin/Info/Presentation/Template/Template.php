<?php
namespace Ababilithub\FlexELand\Package\Plugin\Shortcode\V1\Concrete\Plugin\Info\Presentation\Template;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
};

use const Ababilithub\{
    FlexELand\PLUGIN_NAME,
    FlexELand\PLUGIN_DIR,
    FlexELand\PLUGIN_URL,
    FlexELand\PLUGIN_FILE,
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_VERSION,
};

class Template 
{
    use StandardMixin, StandardWpMixin;

    private $asset_url;

    public function __construct() 
    {
        //echo "Bismillah";exit;
        $this->asset_url = $this->get_url('Asset/');
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style(
            PLUGIN_PRE_HYPH.'-plugin-info', 
            $this->asset_url.'Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            PLUGIN_PRE_HYPH.'-plugin-info-script', 
            $this->asset_url.'Js/Script.js',
            array(), 
            time(),
            true
        );
    }

}