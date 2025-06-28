<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Taxonomy\V1\Factory\Taxonomy as TaxonomyFactory,
    FlexWordpress\Package\Taxonomy\V1\Contract\Taxonomy as TaxonomyContract,
    FlexWordpress\Package\Taxonomy\V1\Concrete\Address\District\Taxonomy as DistrictTaxonomy,
    FlexWordpress\Package\Taxonomy\V1\Concrete\Address\Thana\Taxonomy as ThanaTaxonomy,
    FlexWordpress\Package\Taxonomy\V1\Concrete\Multimedia\MediaType\Taxonomy as MediaTypeTaxonomy,
    FlexWordpress\Package\Taxonomy\V1\Concrete\Multimedia\ExtensionType\Taxonomy as ExtensionTypeTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\V1\Land\Deed\Type\Taxonomy as LandDeedTypeTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\V1\Land\Document\Category\Taxonomy as LandDocumentCategoryTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\V1\Land\Document\Type\Taxonomy as LandDocumentTypeTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\V1\Land\Mouza\Taxonomy as LandMouzaTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\V1\Land\Survey\Taxonomy as LandSurveyTaxonomy,
    FlexELand\Package\Plugin\Taxonomy\V1\Land\Type\Taxonomy as LandTypeTaxonomy,    
};

class Taxonomy extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            DistrictTaxonomy::class,
            ThanaTaxonomy::class,
            MediaTypeTaxonomy::class,
            ExtensionTypeTaxonomy::class,
            LandDeedTypeTaxonomy::class,
            LandDocumentCategoryTaxonomy::class,
            LandDocumentTypeTaxonomy::class,
            LandMouzaTaxonomy::class,
            LandSurveyTaxonomy::class,
            LandTypeTaxonomy::class,
            // Add more taxonomy classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $taxonomy = TaxonomyFactory::get($itemClass);

            if ($taxonomy instanceof TaxonomyContract) 
            {
                $taxonomy->register();
                $taxonomy->process_terms();
            }
        }
    }
}
