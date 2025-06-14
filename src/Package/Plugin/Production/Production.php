<?php
namespace Ababilithub\FlexELand\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Taxonomy\Factory\Taxonomy as TaxonomyFactory,
    FlexWordpress\Package\Taxonomy\Concrete\Address\District\Taxonomy as DistrictTaxonomy,
    FlexWordpress\Package\Taxonomy\Concrete\Address\Thana\Taxonomy as ThanaTaxonomy,
    FlexWordpress\Package\Taxonomy\Concrete\Multimedia\MediaType\Taxonomy as MediaTypeTaxonomy,
    FlexWordpress\Package\Taxonomy\Concrete\Multimedia\ExtensionType\Taxonomy as ExtensionTypeTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\Land\Deed\Type\Taxonomy as LandDeedTypeTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\Land\Document\Type\Taxonomy as LandDocumentTypeTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\Land\Mouza\Taxonomy as LandMouzaTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\Land\Survey\Taxonomy as LandSurveyTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\Land\Type\Taxonomy as LandTypeTaxonomy,    
    FlexELand\Package\Plugin\Posttype\Land\Document\Posttype as DocumentPosttype,
    FlexELand\Package\Plugin\Posttype\Land\Deed\Posttype as DeedPosttype,
    FlexELand\Package\Plugin\Menu\Menu as ProductionMenu,
};

if (!class_exists(__NAMESPACE__.'\Production')) 
{
    class Production 
    {
        use StandardMixin;

        public function __construct($data = []) 
        {
            $this->init(); 
            //add_action('init', [$this, 'init'], 0);           
        }

        public function init() 
        {
            
            TaxonomyFactory::get(LandDeedTypeTaxonomy::class); 
            TaxonomyFactory::get(LandDocumentTypeTaxonomy::class);  
                      
            TaxonomyFactory::get(LandMouzaTaxonomy::class); 
            TaxonomyFactory::get(LandSurveyTaxonomy::class);
            TaxonomyFactory::get(LandTypeTaxonomy::class);
            TaxonomyFactory::get(DistrictTaxonomy::class); 
            TaxonomyFactory::get(ThanaTaxonomy::class);
            TaxonomyFactory::get(MediaTypeTaxonomy::class); 
            TaxonomyFactory::get(ExtensionTypeTaxonomy::class);
            
            ProductionMenu::getInstance();
            DocumentPosttype::getInstance();
            DeedPosttype::getInstance();
        }
        
    }
}