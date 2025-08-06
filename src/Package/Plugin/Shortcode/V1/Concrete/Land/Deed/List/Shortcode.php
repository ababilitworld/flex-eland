<?php

namespace Ababilithub\FlexELand\Package\Plugin\Shortcode\V1\Concrete\Land\Deed\List;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Shortcode\V1\Base\Shortcode as BaseShortcode,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Posttype as LandDeedPosttype,
    FlexELand\Package\Plugin\Shortcode\V1\Concrete\Land\Deed\List\Presentation\Template\List\PremiumCard\Template as PosttypeListTemplate
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_PRE_HYPH,
};

class Shortcode extends BaseShortcode
{
    public function init(): void
    {
        $this->set_tag(PLUGIN_PRE_HYPH.'-'.LandDeedPosttype::POSTTYPE.'-list'); 

        $this->set_default_attributes([
            'style' => 'grid',
            'columns' => '3',
            'pagination' => 'yes',
            'show' => '10',
            'sort' => 'DESC',
            'sort_by' => 'date',
            'status' => 'publish',
            'pagination_style' => 'load_more',
            'search_filter' => 'yes',
            'sidebar_filter' => 'yes',
            'deed_type' => '',
            'district' => '',
            'thana' => '',
            'debug' => 'no'
        ]);

        $this->init_hook();
        $this->init_service();
    }

    public function init_hook(): void
    {
        add_action(PLUGIN_PRE_UNDS.'_deed_list', [$this, 'deed_list']);
    }

    public function init_service(): void
    {
        new PosttypeListTemplate();
    }

    public function render(array $attributes): string
    {
        $this->set_attributes($attributes);
        $params = $this->get_attributes();
        
        ob_start();
        do_action(PLUGIN_PRE_UNDS.'_deed_list', $params);
        return ob_get_clean();
    }

    public function deed_list(array $params): void
    {
        try {
            // Build query args
            $args = [
                'post_type' => LandDeedPosttype::POSTTYPE,
                'posts_per_page' => (int)$params['show'],
                'orderby' => sanitize_text_field($params['sort_by']),
                'order' => sanitize_text_field($params['sort']),
                'post_status' => sanitize_text_field($params['status'])
            ];

            // Add taxonomy filters
            $tax_queries = [];

            if (!empty($params['deed_type'])) {
                $tax_queries[] = [
                    'taxonomy' => 'land-deed-type',
                    'field' => 'slug',
                    'terms' => array_map('sanitize_text_field', explode(',', $params['deed_type']))
                ];
            }

            if (!empty($params['district'])) {
                $tax_queries[] = [
                    'taxonomy' => 'district',
                    'field' => 'slug',
                    'terms' => array_map('sanitize_text_field', explode(',', $params['district']))
                ];
            }

            if (!empty($params['thana'])) {
                $tax_queries[] = [
                    'taxonomy' => 'thana',
                    'field' => 'slug',
                    'terms' => array_map('sanitize_text_field', explode(',', $params['thana']))
                ];
            }

            if (!empty($tax_queries)) {
                $args['tax_query'] = $tax_queries;
                if (count($tax_queries) > 1) {
                    $args['tax_query']['relation'] = 'AND';
                }
            }

            // Debug output if enabled
            if ($params['debug'] === 'yes') {
                echo '<pre>Query Args: ' . print_r($args, true) . '</pre>';
            }

            // Get posts
            $posts = get_posts($args);

            if (empty($posts)) {
                echo '<div class="deed-list-notice">' . esc_html__('No land deeds found matching your criteria.', 'flex-eland') . '</div>';
                return;
            }

            // Render the list with template
            echo PosttypeListTemplate::deed_list($posts);

        } catch (Exception $e) {
            if ($params['debug'] === 'yes') {
                echo '<div class="deed-list-error">' . esc_html__('Error: ', 'flex-eland') . esc_html($e->getMessage()) . '</div>';
            } else {
                echo '<div class="deed-list-error">' . esc_html__('Unable to display land deeds at this time.', 'flex-eland') . '</div>';
            }
        }
    }
}