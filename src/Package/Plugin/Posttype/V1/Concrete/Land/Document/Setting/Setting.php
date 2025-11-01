<?php
    namespace Ababilithub\FlexEland\Package\Plugin\Posttype\V1\Concrete\Land\Document\Setting;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
        FlexEland\Package\Plugin\Posttype\V1\Concrete\Land\Document\Setting\General\Setting as GeneralSetting
    };

    use const Ababilithub\{
        FlexEland\PLUGIN_NAME,
        FlexEland\PLUGIN_DIR,
        FlexEland\PLUGIN_URL,
        FlexEland\PLUGIN_FILE,
        FlexEland\PLUGIN_PRE_UNDS,
        FlexEland\PLUGIN_PRE_HYPH,
        FlexEland\PLUGIN_VERSION,
        FlexEland\Package\Plugin\Posttype\V1\Concrete\Land\Document\POSTTYPE,
    };

    
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
                $this->posttype = POSTTYPE;
                $this->general_setting = GeneralSetting::getInstance();
                add_action('add_meta_boxes', array($this, 'meta_box'));       
            }

            public function meta_box($post_type) 
            {
                if ($post_type === POSTTYPE) 
                {
                    add_meta_box(
                        PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_meta_box', 
                        '<span class="fas fa-cogs"></span>' . esc_html__(' Attributes : ', 'flex-eland') . get_the_title(get_the_id()),
                        array($this, 'settings'));
                }
            }
            
            public function settings() 
            {
                $post_id = get_the_ID();
                ?>
                <div class="fpba">
                    <div class="meta-box">
                        <div class="app-container">
                            <div class="vertical-tabs">
                                <div class="tabs-header">
                                    <button class="toggle-tabs" id="toggleTabs">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="tabs-title">Attributes</span>
                                </div>
                                <ul class="tab-items">
                                    <!-- <li class="tab-item active" data-tab="dashboard">
                                        <a href="#" class="tab-link">
                                            <i class="fas fa-home"></i>
                                            <span class="tab-text">Dashboard</span>
                                        </a>
                                    </li> -->
                                    <?php do_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_tab_item'); ?>
                                </ul>
                            </div>
                            <main class="content-area">
                                <!-- <div class="tab-content active" id="dashboard">
                                    <h1>Dashboard</h1>
                                    <p>Welcome to your dashboard. This is the main content area.</p>
                                </div> -->
                                <?php do_action(PLUGIN_PRE_UNDS.'_'.POSTTYPE.'_'.'setting_tab_content',$post_id); ?>
                            </main>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }