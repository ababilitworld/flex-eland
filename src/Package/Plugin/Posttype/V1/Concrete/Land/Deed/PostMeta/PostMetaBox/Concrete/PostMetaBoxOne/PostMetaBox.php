<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\PostMeta\PostMetaBox\Concrete\PostMetaBoxOne;

use Ababilithub\{
    FlexWordpress\Package\PostMetaBox\V1\Base\PostMetaBox as BasePostMetaBox,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Posttype as LandDeedPosttype,
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_PRE_UNDS,
};

class PostMetaBox extends BasePostMetaBox
{
    public function init() : void
    {
        $this->posttype = LandDeedPosttype::POSTTYPE;
        $this->id = PLUGIN_PRE_HYPH.'-'.$this->posttype.'-'.'meta-box';
        $this->title = esc_html__(' Info : ', 'flex-eland') . get_the_title(get_the_ID());
    }

    public function render(): void
    {
        $post_id = get_the_ID();
        // Dynamic title with post name if needed
        $this->title = esc_html__('Info : ', 'flex-eland') . get_the_title($post_id);
        ?>
        <div class="fpba">
            <div class="meta-box">
                <div class="app-container">
                    <div class="vertical-tabs">
                        <div class="tabs-header">
                            <button class="toggle-tabs" id="toggleTabs">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="tabs-title">Info</span>
                        </div>
                        <ul class="tab-items">
                            <?php do_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_item'); ?>
                        </ul>
                    </div>
                    <main class="content-area">
                        <?php do_action(PLUGIN_PRE_UNDS.'_'.$this->posttype.'_'.'meta_box_tab_content', $post_id); ?>
                    </main>
                </div>
            </div>
        </div>
        <?php
    }
}