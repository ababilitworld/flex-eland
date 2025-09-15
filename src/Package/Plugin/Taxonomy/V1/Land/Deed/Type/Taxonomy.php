<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\V1\Land\Deed\Type;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Taxonomy\V1\Base\Taxonomy as BaseTaxonomy
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS
};

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy extends BaseTaxonomy
    {
        public function init(): void
        {
            $this->taxonomy = 'land-deed-type';
            $this->slug = 'land-deed-type';

            $this->set_labels([
                'name'              => _x('Land Deed Types', 'taxonomy general name', 'flex-eland'),
                'singular_name'     => _x('Land Deed Type', 'taxonomy singular name', 'flex-eland'),
                'search_items'      => __('Search Land Deed Types', 'flex-eland'),
                'all_items'         => __('All Land Deed Types', 'flex-eland'),
                'parent_item'       => __('Parent Land Deed Type', 'flex-eland'),
                'parent_item_colon' => __('Parent Land Deed Type:', 'flex-eland'),
                'edit_item'         => __('Edit Land Deed Type', 'flex-eland'),
                'update_item'       => __('Update Land Deed Type', 'flex-eland'),
                'add_new_item'      => __('Add New Land Deed Type', 'flex-eland'),
                'new_item_name'     => __('New Land Deed Type Name', 'flex-eland'),
                'menu_name'         => __('Land Deed Types', 'flex-eland'),
            ]);

            $this->set_args([
                'hierarchical' => true,
                'labels' => $this->labels,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => $this->slug],
                'show_in_quick_edit' => true,
                'show_in_rest' => true,
                'meta_box_cb' => 'post_categories_meta_box',
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
            ]);

            $this->set_terms([
                $this->generate_term_data(
                    'sale_deed',
                    'Sale Deed (বিক্রয় দলিল)',
                    'Legal document for permanent transfer of ownership',
                    [
                        'legal_effect' => 'permanent_transfer',
                        'stamp_duty' => 5.0,
                        'registration_fee' => 2.0,
                        'requires_witness' => true
                    ]
                ),
                $this->generate_term_data(
                    'gift_deed',
                    'Gift Deed (উপহার দলিল)',
                    'Voluntary transfer without monetary consideration',
                    [
                        'legal_effect' => 'voluntary_transfer',
                        'stamp_duty' => 1.0,
                        'registration_fee' => 1.0,
                        'revocable' => false
                    ]
                ),
                $this->generate_term_data(
                    'lease_deed',
                    'Lease Deed (ইজারা দলিল)',
                    'Temporary transfer of possession for fixed period',
                    [
                        'legal_effect' => 'temporary_possession',
                        'max_duration' => 99,
                        'renewable' => true,
                        'stamp_duty' => 'slab_rate'
                    ]
                ),
                $this->generate_term_data(
                    'mortgage_deed',
                    'Mortgage Deed (বন্ধকী দলিল)',
                    'Security interest in land for loan collateral',
                    [
                        'legal_effect' => 'security_interest',
                        'redemption_period' => 15,
                        'foreclosure_possible' => true
                    ]
                ),
                $this->generate_term_data(
                    'partition_deed',
                    'Partition Deed (বিভাজন দলিল)',
                    'Division of jointly held property among co-owners',
                    [
                        'legal_effect' => 'division_of_property',
                        'requires_consent' => true,
                        'minimum_coowners' => 2
                    ]
                ),
                $this->generate_term_data(
                    'power_of_attorney',
                    'Power of Attorney (মুক্তিয়ারনামা)',
                    'Authorization to act on behalf of property owner',
                    [
                        'legal_effect' => 'authorization',
                        'revocable' => true,
                        'types' => ['general', 'special']
                    ]
                )
            ]);

            $this->init_service();
            $this->init_hook();
            
        }

        protected function init_service(): void
        {
            //
        }

        protected function init_hook(): void
        {
            //add_action('init', [$this, 'init_taxonomy'], 97);
            //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
            add_filter($this->taxonomy.'_row_actions', [$this, 'row_action_view_details'], 10, 2);
            
        }

        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'parent-slug',
                'page_title' => __('Land Deed Type', 'flex-eland'),
                'menu_title' => __('Land Deed Type', 'flex-eland'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }
    }
}