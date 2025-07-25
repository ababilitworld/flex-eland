<?php
namespace Ababilithub\FlexELand\Package\Plugin\Posttype\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Posttype\V1\Factory\Posttype as PosttypeFactory,
    FlexWordpress\Package\Posttype\V1\Contract\Posttype as PosttypeContract, 
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Website\Setting\ContactInfo\Posttype as ContactInfoPosttype,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Document\Posttype as LandDocumentPosttype,
    FlexELand\Package\Plugin\Posttype\V1\Concrete\Land\Deed\Posttype as LandDeedPosttype,
       
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
            ContactInfoPosttype::class,
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
