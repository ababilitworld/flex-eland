<?php
    namespace Ababilithub\FlexELand\Package\Document;

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
        FlexWordpressByAbabilithub\Package\Pagination\Concrete\Pagination as PaginationService,
        FlexELand\Package\Document\Setting\Setting as Setting,
        FlexELand\Package\Document\Service\Service as DocumentService,
        FlexELand\Package\Document\Presentation\Template\Template as DocumentTemplate
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

    if (!class_exists(__NAMESPACE__.'\Document')) 
    {
        class Document 
        {
            use StandardMixin;

            private $posttype;
            private $pagination_service;
            private $portfolio_service;
            private $portfolio_template;
            private $settings;

            public function __construct()
            {
                $this->init();
            }

            private function init()
            {
                $this->posttype = 'flexdoc';
                $this->init_hooks();
                $this->init_services();
            }

            private function init_hook()
            {
                add_action('init', [$this, 'register_post_type']);
                add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
            }

            private function init_service()
            {
                $this->pagination_service = PaginationService::instance();
                $this->portfolio_service = DocumentService::instance();
                $this->portfolio_template = DocumentTemplate::instance();
                $this->settings = Setting::getInstance();
            }

            public function register_post_type() 
            {
                add_theme_support('post-thumbnails', array($this->posttype));
                //add_theme_support('editor-color-palette', array($this->posttype));

                $labels = [
                    'name' => esc_html__('Documents', 'flex-eland'),
                    'singular_name' => esc_html__('Document', 'flex-eland'),
                    'menu_name' => esc_html__('Documents', 'flex-eland'),
                    'name_admin_bar' => esc_html__('Documents', 'flex-eland'),
                    'archives' => esc_html__('Document List', 'flex-eland'),
                    'attributes' => esc_html__('Document List', 'flex-eland'),
                    'parent_item_colon' => esc_html__('Document Item : ', 'flex-eland'),
                    'all_items' => esc_html__('All Document', 'flex-eland'),
                    'add_new_item' => esc_html__('Add new Document', 'flex-eland'),
                    'add_new' => esc_html__('Add new Document', 'flex-eland'),
                    'new_item' => esc_html__('New Document', 'flex-eland'),
                    'edit_item' => esc_html__('Edit Document', 'flex-eland'),
                    'update_item' => esc_html__('Update Document', 'flex-eland'),
                    'view_item' => esc_html__('View Document', 'flex-eland'),
                    'view_items' => esc_html__('View Documents', 'flex-eland'),
                    'search_items' => esc_html__('Search Documents', 'flex-eland'),
                    'not_found' => esc_html__('Document Not found', 'flex-eland'),
                    'not_found_in_trash' => esc_html__('Document Not found in Trash', 'flex-eland'),
                    'featured_image' => esc_html__('Document Feature Image', 'flex-eland'),
                    'set_featured_image' => esc_html__('Set Document Feature Image', 'flex-eland'),
                    'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-eland'),
                    'use_featured_image' => esc_html__('Use as Document featured image', 'flex-eland'),
                    'insert_into_item' => esc_html__('Insert into Document', 'flex-eland'),
                    'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-eland'),
                    'items_list' => esc_html__('Document list', 'flex-eland'),
                    'items_list_navigation' => esc_html__('Document list navigation', 'flex-eland'),
                    'filter_items_list' => esc_html__('Filter Document List', 'flex-eland')
                ];

                $args = array(
                    'public' => true,
                    'labels' => $labels,
                    'menu_icon' => "dashicons-admin-post",
                    'rewrite' => array('slug' => $this->posttype),
                    'supports' => array('title', 'thumbnail', 'editor'),
                    'taxonomies' => array('category', 'post_tag'),
                );

                register_post_type($this->posttype, $args);

                register_taxonomy_for_object_type('category', $this->posttype);
                register_taxonomy_for_object_type('post_tag', $this->posttype);
            }

            public function disable_gutenberg($current_status, $post_type)
            {
                if ($post_type === $this->posttype) 
                {
                    return false;
                }
                return $current_status;
            }
        }
    }