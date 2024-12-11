<?php
/**
 * Widget for display products from selected category.
 *
 * @package Mystery Themes
 * @subpackage Easy Store Pro
 * @since 1.0.0
 */

class Rw_Easy_Store_Category_Products extends WP_Widget {

	/**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname'                     => 'rw_easy_store_category_products',
            'description'                   => __( 'Display WooCommerce product lists from selected category.', 'easy-store-pro' ),
            'customize_selective_refresh'   => true,
        );
        parent::__construct( 'rw_easy_store_category_products', __( 'ES: RW Category Products', 'easy-store-pro' ), $widget_ops );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        
        $fields = array(

            'section_title' => array(
                'easy_store_widgets_name'         => 'section_title',
                'easy_store_widgets_title'        => __( 'Section Title', 'easy-store-pro' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_info' => array(
                'easy_store_widgets_name'         => 'section_info',
                'easy_store_widgets_title'        => __( 'Section Info', 'easy-store-pro' ),
                'easy_store_widgets_row'          => 5,  
                'easy_store_widgets_field_type'   => 'textarea'
            ),

            'section_cat_slug' => array(
                'easy_store_widgets_name'         => 'section_cat_slug',
                'easy_store_widgets_title'        => __( 'Select Category', 'easy-store-pro' ),
                'easy_store_widgets_default'      => '',
                'easy_store_widgets_field_type'   => 'woo_category_dropdown'
            ),

            'section_post_count' => array(
                'easy_store_widgets_name'         => 'section_post_count',
                'easy_store_widgets_title'        => __( 'Product Count', 'easy-store-pro' ),
                'easy_store_widgets_default'          => 8,  
                'easy_store_widgets_field_type'   => 'number'
            ),

            'section_view_more_button' => array(
                'easy_store_widgets_name'         => 'section_view_more_button',
                'easy_store_widgets_title'        => __( 'View More Button', 'easy-store-pro' ),
                'easy_store_widgets_description'  => __( 'Empty label will hide button ( permalink for above selected category  )', 'easy-store-pro' ),
                'easy_store_widgets_field_type'   => 'text'
            ),

            'section_view_more_button_link_target' => array(
                'easy_store_widgets_name'         => 'section_view_more_button_link_target',
                'easy_store_widgets_title'        => __( 'Open view more button link in', 'easy-store-pro' ),
                'easy_store_widgets_default'      => '_blank',
                'easy_store_widgets_field_type'   => 'select',
                'easy_store_widgets_field_options'=> array(
                        '_blank'    => __( 'New Tab', 'easy-store-pro' ),
                        '_self'     => __( 'Same Tab', 'easy-store-pro' )
                    )
            ),

            'section_layout' => array(
                'easy_store_widgets_name'         => 'section_layout',
                'easy_store_widgets_title'        => __( 'Section Layouts', 'easy-store-pro' ),
                'easy_store_widgets_default'      => 'layout1',
                'easy_store_widgets_field_type'   => 'selector',
                'easy_store_widgets_field_options' => array(
                    'layout1'  => array(
                        'label'     => esc_html__( 'Layout 1', 'easy-store-pro' ),
                        'img_path'  => get_template_directory_uri() . '/assets/images/category-product-1.png'
                    ),
                    'layout2'  => array(
                        'label'     => esc_html__( 'Layout 2', 'easy-store-pro' ),
                        'img_path'  => get_template_directory_uri() . '/assets/images/category-product-2.png'
                    )
                )
            ),
        );
        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        if( empty( $instance ) ) {
            return ;
        }

        $es_section_title    = empty( $instance['section_title'] ) ? '' : $instance['section_title'];
        $es_section_info     = empty( $instance['section_info'] ) ? '' : $instance['section_info'];
        $es_section_cat_slug = empty( $instance['section_cat_slug'] ) ? '' : $instance['section_cat_slug'];
        $es_product_count    = empty( $instance['section_post_count'] ) ? 8 : $instance['section_post_count'];
        $section_view_more_button    = empty( $instance['section_view_more_button'] ) ? '' : $instance['section_view_more_button'];
        $section_view_more_button_link_target    = empty( $instance['section_view_more_button_link_target'] ) ? '_blank' : $instance['section_view_more_button_link_target'];
        $es_section_layout   = empty( $instance['section_layout'] ) ? 'layout1' : $instance['section_layout'];

        if( !empty( $es_section_title ) || !empty( $es_section_info ) ) {
            $sec_title_class = 'has-title';
        } else {
            $sec_title_class = 'no-title';
        }

        if( $es_section_layout == 'layout1' ) {
            $es_section_class = 'cat-products-grid';
        } else {
            $es_section_class = 'cat-products-carousel';
        }

        $product_args = array(
            'post_type'      => 'product',
            'product_cat'    => esc_attr( $es_section_cat_slug ),
            'posts_per_page' => absint( $es_product_count ),
            'orderby'        => array(
                'menu_order' => 'ASC', // Сначала по menu_order
                'rand'       => '',    // Затем случайно
            ),
        );

        $product_query = new WP_Query( $product_args );
        $total_post = $product_query->post_count;

        echo $before_widget;
?>
            <div class="es-section-wrapper widget-section">
                <div class="mt-container">
                    <div class="section-title-wrapper <?php echo esc_attr( $sec_title_class ); ?>">
                        <div class="section-title-block-wrap es-clearfix">
                            <div class="section-title-block">
                                <?php
                                    if( !empty( $es_section_title ) ) {
                                        echo $before_title . esc_html( $es_section_title ) . $after_title;
                                    }

                                    if( !empty( $es_section_info ) ) {
                                        echo '<span class="section-info">'. esc_html( $es_section_info ) .'</span>';
                                    }
                                ?>
                            </div> <!-- section-title-block -->
                        </div>
                        <?php if( $total_post > 0 && $es_section_layout == 'layout2' ) { ?>
                            <div class="carousel-nav-action">
                                <div class="es-navPrev carousel-controls"><i class="fa fa-angle-left"></i></div>
                                <div class="es-navNext carousel-controls"><i class="fa fa-angle-right"></i></div>
                            </div>
                        <?php } ?>
                    </div><!-- .section-title-wrapper -->
                    <div class="es-cat-products-wrapper <?php echo esc_attr( $es_section_class ); ?>">                        
                        <?php
                            if ( $product_query->have_posts() ) {
                                if( $es_section_layout == 'layout2' ) {
                                    echo '<ul class="catProductsCarousel cS-hidden">';
                                }
                                while ( $product_query->have_posts() ) {
                                    $product_query->the_post();
                                    wc_get_template_part( 'content', 'product' );
                                }
                                if( $es_section_layout == 'layout1' ) {
                                    echo '</ul><!--.catProductsCarousel-->';
                                }
                            } else {
                                easy_store_no_product_found();
                            }
                            wp_reset_postdata();
                        ?>
                    </div><!-- .es-cat-products-wrapper -->
                    <?php
                        if( !empty( $section_view_more_button ) ) {
                    ?>
                            <div class="es-cat-products-view-more">
                                <a href="<?php echo get_term_link( $es_section_cat_slug, 'product_cat' ); ?>" target="<?php echo esc_html( $section_view_more_button_link_target ); ?>" title="<?php echo esc_html( $es_section_cat_slug ); ?>"><?php echo esc_html( $section_view_more_button ); ?></a>
                            </div>
                    <?php
                        }
                    ?>
                </div><!-- .mt-container -->
            </div><!-- .es-promos-wrapper -->
<?php
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param   array   $new_instance   Values just sent to be saved.
     * @param   array   $old_instance   Previously saved values from database.
     *
     * @uses    easy_store_widgets_updated_field_value()      defined in es-widget-fields.php
     *
     * @return  array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            extract( $widget_field );

            // Use helper function to get updated field values
            $instance[$easy_store_widgets_name] = easy_store_widgets_updated_field_value( $widget_field, $new_instance[$easy_store_widgets_name] );
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param   array $instance Previously saved values from database.
     *
     * @uses    easy_store_widgets_show_widget_field()        defined in es-widget-fields.php
     */
    public function form( $instance ) {

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            // Make array elements available as variables
            extract( $widget_field );

            if ( empty( $instance ) && isset( $easy_store_widgets_default ) ) {
                $easy_store_widgets_field_value = $easy_store_widgets_default;
            } elseif( empty( $instance ) ) {
                $easy_store_widgets_field_value = '';
            } else {
                if( isset( $instance[$easy_store_widgets_name] ) ) {
                    $easy_store_widgets_field_value = wp_kses_post( $instance[$easy_store_widgets_name] );
                } else {
                    $easy_store_widgets_field_value = '';
                }
            }
            //$easy_store_widgets_field_value = !empty( $instance[$easy_store_widgets_name] ) ? wp_kses_post( $instance[$easy_store_widgets_name] ) : '';
            easy_store_widgets_show_widget_field( $this, $widget_field, $easy_store_widgets_field_value );
        }
    }
}