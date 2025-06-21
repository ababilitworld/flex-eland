<?php
    namespace Ababilithub\FlexELand\Package\Plugin\Posttype\Land\Document\Setting;

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
        FlexELand\Package\Plugin\Posttype\Land\Document\Setting\General\Setting as GeneralSetting
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

    if (!class_exists(__NAMESPACE__.'\Setting')) 
    {
        class Setting 
        {
            use StandardMixin;

            private $posttype;
            private $general_setting;

            public function __construct() 
            {
                $this->init();
            }

            private function init() 
            {
                $this->posttype = 'flexdoc';
                $this->general_setting = GeneralSetting::getInstance();
                add_action('add_meta_boxes', array($this, 'meta_box'));
                        
            }

            public function meta_box() 
            {
                add_meta_box(
                    PLUGIN_PRE_UNDS.'_'.$this->posttype.'_meta_box', 
                    '<span class="fas fa-cogs"></span>' . esc_html__(' Attributes : ', 'flex-eland') . get_the_title(get_the_id()),
                    array($this, 'settings'));
            }
            
            public function settings() 
            {
                $post_id = get_the_ID();
                ?>
                <div class="fpba">
                    <div class="meta-box">
                        <div class="loader-container">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="tab-container">
                            <ul class="tab-menu" role="tablist">
                                <h3><?php esc_html_e('Attribute','flex-eland');?></h3>
                                <?php do_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'setting_tab_item'); ?>
                            </ul>
                            <div class="tab-content-container">
                                <?php do_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'setting_tab_content',$post_id); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }