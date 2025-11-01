<?php
namespace Ababilithub\FlexEland\Package\Plugin\Menu\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Menu\V1\Contract\Menu as MenuContract, 
    FlexWordpress\Package\Menu\V1\Factory\Menu as MenuFactory,
    FlexEland\Package\Plugin\Menu\V1\Concrete\Main\Menu as MainMenu,
    FlexEland\Package\Plugin\Menu\V1\Concrete\Posttype\Deed\Menu as DeedPosttypeMenu,
    FlexEland\Package\Plugin\Menu\V1\Concrete\Shortcode\DeedList\Menu as DeedListShortcodeMenu,
    
};

class  Menu extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }
    
    public function init()
    {
        $this->set_items(
            [
                MainMenu::class,
                DeedPosttypeMenu::class,
                DeedListShortcodeMenu::class,                  
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $item_instance = MenuFactory::get($item);

            if ($item_instance instanceof MenuContract) 
            {
                $item_instance->register();
            }
        }
    }
}