<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\V1\Land\Survey;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Taxonomy\V1\Base\Taxonomy as BaseTaxonomy
};

use const Ababilithub\{
    FlexWordpress\PLUGIN_PRE_UNDS
};

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy extends BaseTaxonomy
    {
        public function init(): void
        {
            $this->taxonomy = 'land-survey';
            $this->slug = 'land-survey';

            $this->set_labels([
                'name'              => _x('Land Surveys', 'taxonomy general name', 'flex-eland'),
                'singular_name'     => _x('Land Survey', 'taxonomy singular name', 'flex-eland'),
                'search_items'      => __('Search Land Surveys', 'flex-eland'),
                'all_items'         => __('All Land Surveys', 'flex-eland'),
                'parent_item'       => __('Parent Land Survey', 'flex-eland'),
                'parent_item_colon' => __('Parent Land Survey:', 'flex-eland'),
                'edit_item'         => __('Edit Land Survey', 'flex-eland'),
                'update_item'       => __('Update Land Survey', 'flex-eland'),
                'add_new_item'      => __('Add New Land Survey', 'flex-eland'),
                'new_item_name'     => __('New Land Survey Name', 'flex-eland'),
                'menu_name'         => __('Land Surveys', 'flex-eland'),
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
                    'cs',
                    'CS (Cadastral Survey)',
                    'Original survey conducted during British period (1880-1940)',
                    ['year_start' => 1880, 'year_end' => 1940]
                ),
                $this->generate_term_data(
                    'sa',
                    'SA (State Acquisition Survey)',
                    'Conducted after abolition of zamindari system (1950-1962)',
                    ['year_start' => 1950, 'year_end' => 1962]
                ),
                $this->generate_term_data(
                    'rs',
                    'RS (Revisional Survey)',
                    'First revision survey (1960-1980)',
                    ['year_start' => 1960, 'year_end' => 1980]
                ),
                $this->generate_term_data(
                    'bs',
                    'BS (Bangladesh Survey)',
                    'Conducted after independence (1980-present)',
                    ['year_start' => 1980, 'year_end' => 0]
                ),
                $this->generate_term_data(
                    'mrs',
                    'MRS (Modern Revisional Survey)',
                    'Digital survey using modern techniques (2010-present)',
                    ['year_start' => 2010, 'year_end' => 0, 'digital' => true]
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
        }

        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'parent-slug',
                'page_title' => __('Land Survey', 'flex-eland'),
                'menu_title' => __('Land Survey', 'flex-eland'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }
    }
}