<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\Land\Document;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Posttype\V1\Base\Posttype as BasePosttype
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS
};

class Posttype extends BasePosttype
{
    use StandardMixin;
    protected function init(): void
    {
        $this->posttype = 'fldoc';
        $this->slug = 'fldoc';

        $this->use_block_editor = true;
        
        $this->init_hook();
        $this->init_service();
    }

    protected function init_hook(): void
    {  
        add_action('after_setup_theme', [$this, 'init_theme_supports']);
        add_filter('use_block_editor_for_post_type', [$this, 'use_block_editor_for_posttye'], 10, 2);
        add_action('init', [$this, 'init_posttype'], 30);
        add_action('init', [$this, 'register_post_type'], 31);
        //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);            
    }

    protected function init_service(): void
    {
        //
    }

    public function add_menu_items($menu_items = [])
    {
        $menu_items[] = [
            'type' => 'submenu',
            'parent_slug' => 'flex-eland',
            'page_title' => __('Land Doc', 'flex-eland'),
            'menu_title' => __('Land Doc', 'flex-eland'),
            'capability' => 'manage_options',
            'menu_slug' => 'edit.php?post_type='.$this->posttype,
            'callback' => null,
            'position' => 9,
        ];

        return $menu_items;
    }

    public function init_theme_supports()
    {
        add_theme_support('post-thumbnails', [$this->posttype]);
        add_theme_support('editor-color-palette', [
            [
                'name'  => 'Primary Blue',
                'slug'  => 'primary-blue',
                'color' => '#3366FF',
            ],
        ]);
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');
    }

    public function init_posttype()
    {
        
        $this->set_labels([
            'name' => esc_html__('Land Docs', 'flex-eland'),
            'singular_name' => esc_html__('Land Doc', 'flex-eland'),
            'menu_name' => esc_html__('Land Docs', 'flex-eland'),
            'name_admin_bar' => esc_html__('Land Docs', 'flex-eland'),
            'archives' => esc_html__('Land Doc List', 'flex-eland'),
            'attributes' => esc_html__('Land Doc List', 'flex-eland'),
            'parent_item_colon' => esc_html__('Land Doc Item : ', 'flex-eland'),
            'all_items' => esc_html__('All Land Doc', 'flex-eland'),
            'add_new_item' => esc_html__('Add new Land Doc', 'flex-eland'),
            'add_new' => esc_html__('Add new Land Doc', 'flex-eland'),
            'new_item' => esc_html__('New Land Doc', 'flex-eland'),
            'edit_item' => esc_html__('Edit Land Doc', 'flex-eland'),
            'update_item' => esc_html__('Update Land Doc', 'flex-eland'),
            'view_item' => esc_html__('View Land Doc', 'flex-eland'),
            'view_items' => esc_html__('View Land Docs', 'flex-eland'),
            'search_items' => esc_html__('Search Land Docs', 'flex-eland'),
            'not_found' => esc_html__('Land Doc Not found', 'flex-eland'),
            'not_found_in_trash' => esc_html__('Land Doc Not found in Trash', 'flex-eland'),
            'featured_image' => esc_html__('Land Doc Feature Image', 'flex-eland'),
            'set_featured_image' => esc_html__('Set Land Doc Feature Image', 'flex-eland'),
            'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-eland'),
            'use_featured_image' => esc_html__('Use as Land Doc featured image', 'flex-eland'),
            'insert_into_item' => esc_html__('Insert into Land Doc', 'flex-eland'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-eland'),
            'items_list' => esc_html__('Land Doc list', 'flex-eland'),
            'items_list_navigation' => esc_html__('Land Doc list navigation', 'flex-eland'),
            'filter_items_list' => esc_html__('Filter Land Doc List', 'flex-eland')
        ]);

        $this->set_posttype_supports(
            array('title', 'thumbnail', 'editor', 'custom-fields')
        );

        $this->set_taxonomies(
            array('media-type','extension-type')
        );

        $this->set_args([
            'public' => true, // Changed to true
            'show_ui' => true,
            'show_in_menu' => false, // Don't show in menu by default
            'labels' => $this->labels,
            'menu_icon' => "dashicons-admin-post",
            'rewrite' => array('slug' => $this->slug),
            'supports' => $this->posttype_supports,
            'taxonomies' => $this->taxonomies,
        ]);

        $this->set_metas([
            $this->generate_meta_definition(
               [
                    'key' => '_doc_description',
                    'type' => 'string',
                    'description' => 'short description of the '.$this->slug,
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => null,
                    'auth_callback' => null,
                ]
            ),
            $this->generate_meta_definition(
                [
                    'key' => '_doc_author',
                    'type' => 'string',
                    'description' => 'short description of the '.$this->slug,
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => null,
                    'auth_callback' => null,
                ]
            ),
            $this->generate_meta_definition(
                [
                    'key' => '__short_description',
                    'type' => 'string',
                    'description' => 'short description of the '.$this->slug,
                    'single' => true,
                    'show_in_rest' => true,
                    'sanitize_callback' => null,
                    'auth_callback' => null,
                ]
            ),
        ]);

    }
}