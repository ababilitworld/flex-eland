<?php

namespace Ababilithub\FlexELand\Package\Plugin\Shortcode\V1\Concrete\StaticFilter;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Shortcode\V1\Base\Shortcode as BaseShortcode
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_UNDS,
};

class Shortcode extends BaseShortcode
{
    public function init(): void
    {
        $this->tag = PLUGIN_PRE_UNDS.'_top_filter'; 

        $this->defaultAttributes = [
            'style' => 'grid',
            'column' => '3',
            'pagination' => 'yes',
            'show' => '10',
            'sort' => 'ASC',
            'sort_by' => 'id',
            'status' => 'published',
            'pagination-style' => 'load_more',
            'search-filter' => 'yes',
            'sidebar-filter' => 'yes',
            'shuffle' => 'no',
        ];

        $this->init_hook();
    }

    public function init_hook()
    {
        add_action(PLUGIN_PRE_UNDS.'_doc_list',[$this, 'doc_list']);
    }

    public function render(array $attributes): string
    {
        $this->set_attributes($attributes);
        $params = $this->get_attributes();        
        ob_start();
        do_action(PLUGIN_PRE_UNDS.'_doc_list', $params);        
        ?>
        <?php        
        return ob_get_clean();
    }

    public function doc_list()
    {
        ?>
        <div style="background-color=black;color:white;">Bismillah</div>
        <?php
    }
}
