<?php
    namespace Ababilithub\FlexELand\Package\Plugin\Posttype\Document\Setting\General;

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
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

            public function __construct() 
            {
                $this->posttype = 'flexdoc';
                $this->init();
            }

            private function init() 
            {
                add_action('admin_enqueue_scripts', array($this, 'enqueue'));
                add_action($this->posttype.'_setting_tab_item', array($this, 'tab_item'));
                add_action($this->posttype.'_setting_tab_content', array($this, 'tab_content'));
                add_action($this->posttype.'_setting_general_info', array($this, 'general_info'));
                add_action('save_post', array($this, 'save'));
                
            }

            public function enqueue() 
            {
                wp_enqueue_media();
                wp_enqueue_script(PLUGIN_PRE_HYPH.'document-image-script', PLUGIN_URL . '/src/Package/Document/Presentation/Template/Asset/js/image.js', array(), time(), true);
            }

            public function tab_item() 
            {
                ?>
                    <li class="tab-item active" data-tabs-target="#setting_general_info"><?php esc_html_e('Document Images','flex-eland');?></li>
                <?php
            }
            
            public function tab_content($post_id) 
            {
                ?>
                <div class="tab-content active" id="setting_general_info">
                    <?php do_action($this->posttype.'_setting_general_info',$post_id); ?>
                </div>
                <?php
            }

            public function general_info($post_id) 
            {
                $images = get_post_meta($post_id, 'document_images', true);
                if(is_array($images))
                {
                    $images = array_map('sanitize_text_field', $images);
                }
                

                ?>
                <div class="panel">
                    <div class="fpba-form-group">
                        <label for="document-images">Document Images:</label>
                        <input type="button" class="button" id="upload-images-button" value="Upload Images">
                        <ul id="document-images-preview">
                            <?php
                            if ($images) {
                                foreach ($images as $image) {
                                    echo '<li><img src="' . wp_get_attachment_url($image) . '" style="max-width: 150px;"><input type="hidden" name="document-images[]" value="' . $image . '"><a href="#" class="remove-image">Remove</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php
            }

            

            public function save($post_id) 
            {
                if (get_post_type($post_id) == $this->posttype) 
                {
                    if (isset($_POST['document-images']) && is_array($_POST['document-images'])) 
                    {
                        $images = array_map('sanitize_text_field', $_POST['document-images']);
                        update_post_meta($post_id, 'document_images', $images);
                    }
                }
            }
        }
    }