<?php
namespace Ababilithub\FlexELand\Package\Plugin\Shortcode\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Shortcode\V1\Factory\Shortcode as ShortcodeFactory,
    FlexWordpress\Package\Shortcode\V1\Contract\Shortcode as ShortcodeContract,
    FlexELand\Package\Plugin\Shortcode\V1\Concrete\Plugin\Info\Shortcode as PluginInfoShortcode,
    FlexELand\Package\Plugin\Shortcode\V1\Concrete\Land\Deed\List\Shortcode as DeedListShortcode,
    FlexELand\Package\Plugin\Shortcode\V1\Concrete\Land\Document\List\Shortcode as DocumentListShortcode,
    
    
};

class Shortcode extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            PluginInfoShortcode::class,
            DeedListShortcode::class,
            DocumentListShortcode::class,
            // Add more shortcode classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $shortcode = ShortcodeFactory::get($itemClass);

            if ($shortcode instanceof ShortcodeContract) 
            {
                $shortcode->register();
            }
        }
    }
}
