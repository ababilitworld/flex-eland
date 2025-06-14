<?php
    namespace Ababilithub\FlexELand\Package\Plugin\Posttype\Document;

    use Ababilithub\{
        FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
        FlexWordpressByAbabilithub\Package\Pagination\Concrete\Pagination as PaginationService,
        FlexELand\Package\Document\Setting\Setting as Setting,
        FlexELand\Package\Document\Service\Service as DocumentService,
        FlexELand\Package\Document\Presentation\Template\Template as DocumentTemplate
    };

    use const Ababilithub\{
        FlexELand\PLUGIN_NAME,
        FlexELand\PLUGIN_DIR,
        FlexELand\PLUGIN_URL,
        FlexELand\PLUGIN_FILE,
        FlexELand\PLUGIN_PRE_UNDS,
        FlexELand\PLUGIN_PRE_HYPH,
        FlexELand\PLUGIN_VERSION
    };

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

    if (!class_exists(__NAMESPACE__.'\Document')) 
    {
        class Document 
        {
            use StandardMixin;

            private $posttype;
            private $pagination_service;
            private $portfolio_service;
            private $portfolio_template;
            private $settings;

            public function __construct()
            {
                $this->init();
            }

            private function init()
            {
                $this->posttype = $this->posttype;
                $this->init_hooks();
                $this->init_services();
            }

            private function init_hooks()
            {
                add_action('init', [$this, 'post_type']);
                add_action('wp_loaded', [$this, 'page']);
                add_action('admin_menu', [$this, 'admin_menu']);
                add_shortcode('flex-eland-list', [$this, 'render']);
                add_action('wp_ajax_load_portfolio_by_category', [$this, 'load_portfolio_by_category']);
                add_action('wp_ajax_nopriv_load_portfolio_by_category', [$this, 'load_portfolio_by_category']);
                add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
            }

            private function init_services()
            {
                $this->pagination_service = PaginationService::instance();
                $this->portfolio_service = DocumentService::instance();
                $this->portfolio_template = DocumentTemplate::instance();
                $this->settings = Setting::instance();
            }

            public function post_type() 
            {
                add_theme_support('post-thumbnails', array($this->posttype));
                //add_theme_support('editor-color-palette', array($this->posttype));

                $post_menu_icon = "dashicons-admin-post";
                $post_slug = "flexdoc";

                $labels = [
                    'name' => esc_html__('Documents', 'flex-eland'),
                    'singular_name' => esc_html__('Document', 'flex-eland'),
                    'menu_name' => esc_html__('Documents', 'flex-eland'),
                    'name_admin_bar' => esc_html__('Documents', 'flex-eland'),
                    'archives' => esc_html__('Document List', 'flex-eland'),
                    'attributes' => esc_html__('Document List', 'flex-eland'),
                    'parent_item_colon' => esc_html__('Document Item : ', 'flex-eland'),
                    'all_items' => esc_html__('All Document', 'flex-eland'),
                    'add_new_item' => esc_html__('Add new Document', 'flex-eland'),
                    'add_new' => esc_html__('Add new Document', 'flex-eland'),
                    'new_item' => esc_html__('New Document', 'flex-eland'),
                    'edit_item' => esc_html__('Edit Document', 'flex-eland'),
                    'update_item' => esc_html__('Update Document', 'flex-eland'),
                    'view_item' => esc_html__('View Document', 'flex-eland'),
                    'view_items' => esc_html__('View Documents', 'flex-eland'),
                    'search_items' => esc_html__('Search Documents', 'flex-eland'),
                    'not_found' => esc_html__('Document Not found', 'flex-eland'),
                    'not_found_in_trash' => esc_html__('Document Not found in Trash', 'flex-eland'),
                    'featured_image' => esc_html__('Document Feature Image', 'flex-eland'),
                    'set_featured_image' => esc_html__('Set Document Feature Image', 'flex-eland'),
                    'remove_featured_image' => esc_html__('Remove Feature Image', 'flex-eland'),
                    'use_featured_image' => esc_html__('Use as Document featured image', 'flex-eland'),
                    'insert_into_item' => esc_html__('Insert into Document', 'flex-eland'),
                    'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'flex-eland'),
                    'items_list' => esc_html__('Document list', 'flex-eland'),
                    'items_list_navigation' => esc_html__('Document list navigation', 'flex-eland'),
                    'filter_items_list' => esc_html__('Filter Document List', 'flex-eland')
                ];

                $args = array(
                    'public' => true,
                    'labels' => $labels,
                    'menu_icon' => $post_menu_icon,
                    'rewrite' => array('slug' => $this->posttype),
                    'supports' => array('title', 'thumbnail', 'editor'), // 'thumbnail' ensures featured image support
                    'taxonomies' => array('category', 'post_tag'),
                );

                register_post_type($this->posttype, $args);

                register_taxonomy_for_object_type('category', $this->posttype);
                register_taxonomy_for_object_type('post_tag', $this->posttype);
            }

            public function page()
            {
                $portfolio_page = get_page_by_path(PLUGIN_PRE_HYPH.'-list');

                if (!$portfolio_page) 
                {
                    $portfolio_page_args = array(
                        'post_type' => 'page',
                        'post_name' => PLUGIN_PRE_HYPH.'-list',
                        'post_title' => 'Document List',
                        'post_content' => '[flex-eland-list]',
                        'post_status' => 'publish',
                    );

                    wp_reset_postdata();
                    wp_insert_post($portfolio_page_args);
                    wp_reset_postdata();
                }
            }

            public function admin_menu()
            {
                add_submenu_page(
                    'edit.php?post_type=flexdoc', 
                    esc_html__('Document List', 'flex-eland'), 
                    esc_html__('Document List', 'flex-eland'),
                    'manage_options',
                    'flex-eland',
                    array($this, 'render'),
                    98
                );
            }

            private function get_attributes()
            {
                // Default panel as front-end
                $panel = 'front';
                $paged = 1; // Default page number

                // Detect HTTP Method
                $method = $_SERVER['REQUEST_METHOD'];

                if (is_admin()) 
                {
                    $panel = 'admin';

                    // Admin panel pagination based on HTTP method
                    if ($method === 'POST') 
                    {
                        $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
                    }
                    else if ($method === 'GET')
                    {
                        $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
                    }

                } 
                else 
                {
                    // Front-end pagination handling for archives, shortcodes, and custom loops
                    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

                    // Fallback to 'page' query var if 'paged' is not set
                    if ($paged === 1) {
                        $paged = get_query_var('page') ? absint(get_query_var('page')) : 1;
                    }
                }

                // Get current active theme name
                $current_theme = wp_get_theme();
                $current_theme_name = $current_theme->get('Name');

                // Prepare attributes for portfolio list
                return array(
                    'theme_name'     => $current_theme_name,
                    'panel'          => $panel,
                    'post_type'      => $this->posttype,
                    'posts_per_page' => 2,
                    'paged'          => $paged,
                    'page'           => 'flex-eland',
                    'admin_url'      => admin_url('edit.php?post_type=flexdoc&page=flex-eland'),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
            }


            public function render()
            {
               $attribute = $this->get_attributes();

                // Debug: Check attributes
                //echo "<pre>"; print_r($attribute); echo "</pre>";

                // Output the portfolio list (assuming this method returns HTML)
                echo $this->portfolio_list($attribute);
            }


            public function portfolio_list($attribute) 
            {
                $query = $this->portfolio_service::wp_query($attribute);

                //echo "<pre>";print_r($query);echo "</pre>";exit;
                
                ob_start();
                ?>
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilithub")
                {
                ?>
                    <div class="ababilitworld">
                <?php
                }
                ?>
                    
                    <div class="fpba">
                        <div class="portfolio-template-wrap">
                            <!-- <div class="header">
                                <h3>Our Document</h3>
                            </div> -->
                        <?php
                        if ($query->have_posts()) 
                        {
                            //echo "<pre>"; print_r($query); echo "</pre>";

                            $this->portfolio_template::category_list($query);
                            ?>
                            <div class="portfolio-wrap">
                            <?php
                            $this->portfolio_template::portfolio_default_list($query);
                            $this->pagination_service->init(array('query'=>$query,'attribute'=>$attribute));
                            $this->pagination_service->paginate();
                            ?>
                            </div>
                            <?php
                            $this->portfolio_template::lightbox();
                            wp_reset_postdata();
                        }
                        else
                        {
                            ?>
                            <div>No portfolios found</div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilithub")
                {
                ?>
                    </div>
                <?php
                }
                ?>
                <?php
                return ob_get_clean();
            }

            public function category_portfolio_list($attribute) 
            {
                $query = $this->portfolio_service::wp_query($attribute);

                //echo "<pre>";print_r($query);echo "</pre>";            
                
                ob_start();
                ?>
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilithub")
                {
                ?>
                    <div class="ababilitworld">
                <?php
                }
                ?>
                
                    
                    <div class="fpba">
                        <div class="portfolio-template-wrap">
                            <!-- <div class="header">
                                <h3>Our Document</h3>
                            </div> -->
                        <?php
                        if ($query->have_posts()) 
                        {
                            //echo "<pre>"; print_r($query); echo "</pre>";

                            //$this->portfolio_template::category_list($query);
                            ?>
                            <div class="portfolio-wrap">
                            <?php
                            $this->portfolio_template::portfolio_default_list($query);
                            $this->pagination_service->init(array('query'=>$query,'attribute'=>$attribute));
                            $this->pagination_service->paginate();
                            ?>
                            </div>
                            <?php
                            $this->portfolio_template::lightbox();
                            wp_reset_postdata();
                        }
                        else
                        {
                            ?>
                            <div>No portfolios found</div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilithub")
                {
                ?>
                    </div>
                <?php
                }
                ?>
                <?php
                return ob_get_clean();
            }

            public function load_portfolio_by_category()
            {
                
                $attribute = $this->get_attributes();

                $category_id = intval($_POST['category_id']);

                if(is_int($category_id))
                {
                    $attribute['category_id']= $category_id;
                }
                //echo "<pre>";print_r($attribute);echo "</pre>";
                echo $this->category_portfolio_list($attribute);
                exit;
            }

            public function disable_gutenberg($current_status, $post_type)
            {
                if ($post_type === $this->posttype) 
                {
                    return false;
                }
                return $current_status;
            }
        }
    }