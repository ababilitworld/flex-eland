<?php
namespace Ababilithub\FlexELand\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    // FlexWordpress\Package\Taxonomy\Factory\Taxonomy as TaxonomyFactory,
    // FlexWordpress\Package\Taxonomy\Concrete\Address\District\Taxonomy as DistrictTaxonomy,
    // FlexWordpress\Package\Taxonomy\Concrete\Address\Thana\Taxonomy as ThanaTaxonomy,
    // FlexWordpress\Package\Taxonomy\Concrete\Multimedia\MediaType\Taxonomy as MediaTypeTaxonomy,
    // FlexWordpress\Package\Taxonomy\Concrete\Multimedia\ExtensionType\Taxonomy as ExtensionTypeTaxonomy,
    // FlexELand\Package\Plugin\Taxonomy\Land\Deed\Type\Taxonomy as LandDeedTypeTaxonomy,
    // FlexELand\Package\Plugin\Taxonomy\Land\Document\Type\Taxonomy as LandDocumentTypeTaxonomy,
    // FlexELand\Package\Plugin\Taxonomy\Land\Mouza\Taxonomy as LandMouzaTaxonomy,
    // FlexELand\Package\Plugin\Taxonomy\Land\Survey\Taxonomy as LandSurveyTaxonomy,
    // FlexELand\Package\Plugin\Taxonomy\Land\Type\Taxonomy as LandTypeTaxonomy,
    // FlexWordpress\Package\Posttype\V1\Factory\Posttype as PosttypeFactory,   
    // FlexELand\Package\Plugin\Posttype\Land\Document\Posttype as DocumentPosttype,
    // FlexELand\Package\Plugin\Posttype\Land\Deed\Posttype as DeedPosttype,
    // FlexELand\Package\Plugin\Menu\Menu as ProductionMenu,
    FlexELand\Package\Plugin\Taxonomy\V1\Manager\Taxonomy as TaxonomyManager,
    FlexELand\Package\Plugin\Posttype\V1\Manager\Posttype as PosttypeManager,
    FlexELand\Package\Plugin\Shortcode\V1\Manager\Shortcode as ShortcodeManager, 
};

if (!class_exists(__NAMESPACE__.'\Production')) 
{
    class Production 
    {
        use StandardMixin;

        public function __construct($data = []) 
        {
            $this->init();      
        }

        public function init() 
        {            
            add_action('init', function () {
                (new TaxonomyManager())->boot();
            });

            add_action('init', function () {
                (new PosttypeManager())->boot();
            });
            
            // ProductionMenu::getInstance();
            // $document = PosttypeFactory::get(DocumentPosttype::class);
            //PosttypeFactory::get(DeedPosttype::class);

            add_action('init', function () {
                (new ShortcodeManager())->boot();
            });
        }
        
    }
}