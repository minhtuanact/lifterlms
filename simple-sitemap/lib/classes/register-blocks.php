<?php

namespace WPGO_Plugins\Simple_Sitemap;

/*
 *    Register blocks
 */

class Register_Blocks
{

    protected $module_roots;

    /* Main class constructor. */
    public function __construct($module_roots, $custom_plugin_data)
    {

        $this->module_roots = $module_roots;
        $this->custom_plugin_data = $custom_plugin_data;
        $this->text_expand_block_default_atts = $this->custom_plugin_data->block_text_expand_attribute_defaults;
        $this->box_expand_block_default_atts = $this->custom_plugin_data->block_box_expand_attribute_defaults;
        $this->accordion_expand_block_default_atts = $this->custom_plugin_data->block_accordion_expand_attribute_defaults;

        $this->block_category_slug = $this->custom_plugin_data->enqueue_prefix;

        add_filter('block_categories', array(&$this, 'add_block_category'), 10, 2);
        add_action('plugins_loaded', array(&$this, 'register_dynamic_block'));
    }

    /**
     * Add custom block category.
     */
    public function add_block_category($categories, $post)
    {
        return array_merge(
            $categories,
            [
                [
                    'slug' => $this->block_category_slug,
                    //'icon' => 'chart-line',
                    'title' => $this->custom_plugin_data->main_menu_label,
                ],
            ]
        );
    }

    /**
     * Register the dynamic block.
     *
     * @since 2.1.0
     *
     * @return void
     */
    public function register_dynamic_block()
    {
        // echo '<pre>';
        // print_r($attr_arr);
        // print_r($this->text_expand_block_default_atts);
        // echo '</pre>';
        // die();

        // register the FAQ blocks

        register_block_type('flexible-faqs/text-expand-faqs', [
            'render_callback' => array(WPGO_FAQ_Shortcode::get_instance(), 'render_faqs'),
            'attributes' => $this->text_expand_block_default_atts,
        ]);

        register_block_type('flexible-faqs/box-expand-faqs', [
            'render_callback' => array(WPGO_FAQ_Shortcode::get_instance(), 'render_faqs'),
            'attributes' => $this->box_expand_block_default_atts,
        ]);

        register_block_type('flexible-faqs/accordion-expand-faqs', [
            'render_callback' => array(WPGO_FAQ_Shortcode::get_instance(), 'render_faqs'),
            'attributes' => $this->accordion_expand_block_default_atts,
        ]);
    }
} /* End class definition */
