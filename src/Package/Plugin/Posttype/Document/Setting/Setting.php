<?php
    namespace Ababilithub\FlexELand\Package\Document\Setting;

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
        FlexELand\Package\Document\Setting\General\Setting as GeneralSetting
    };

    use const Ababilithub\{
        FlexPortfolio\PLUGIN_NAME,
        FlexPortfolio\PLUGIN_DIR,
        FlexPortfolio\PLUGIN_URL,
        FlexPortfolio\PLUGIN_FILE,
        FlexPortfolio\PLUGIN_PRE_UNDS,
        FlexPortfolio\PLUGIN_PRE_HYPH,
        FlexPortfolio\PLUGIN_VERSION
    };

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

    if (!class_exists(__NAMESPACE__.'\Setting')) 
    {
        class Setting 
        {
            use StandardMixin;

            private $posttype;
            private $general_setting;

            public function __construct(array $data = null) 
            {
                $this->init($data);
            }

            private function init($data) 
            {
                $this->posttype = $data['posttype']??'';
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
                $portfolio_id = get_the_ID();
                ?>
                <div class="fpba">
                    <div class="meta-box">
                        <div class="loader-container">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="tab-container">
                            <ul class="tab-menu">
                                <h3><?php esc_html_e('Attribute','flex-eland');?></h3>
                                <?php do_action('setting_tab_item'); ?>
                            </ul>
                            <div class="tab-content-container">
                                <?php do_action('setting_tab_content',$portfolio_id); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }