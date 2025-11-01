<?php
namespace Ababilithub\FlexEland\Package\Plugin\Posttype\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Posttype\V1\Factory\Posttype as PosttypeFactory,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract, 
    FlexEland\Package\Plugin\Posttype\V1\Concrete\Land\Document\Posttype as LandDocumentPosttype,
    FlexEland\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Posttype as LandDeedPosttype,
       
};

class Posttype extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            LandDocumentPosttype::class,
            LandDeedPosttype::class,
            // Add more posttype classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $posttype = PosttypeFactory::get($itemClass);

            if ($posttype instanceof PosttypeContract) 
            {
                $posttype->register();
            }
        }
    }
}
