<?php

class Elementor_Widget_miga_sticky_images extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        wp_register_script(
            "miga_sticky_images",
            plugins_url("../scripts/main.js", __FILE__),
            [],
            "1.0.0",
            true
        );

        wp_register_style(
            "miga_sticky_images_styles",
            plugins_url("../styles/main.css", __FILE__)
        );
    }

    public function get_name()
    {
        return "miga_sticky_images";
    }

    public function get_title()
    {
        return __("Sticky images", "miga_sticky_images");
    }

    public function get_icon()
    {
        return "eicon-image";
    }

    public function get_categories()
    {
        return ["general"];
    }

    protected function _register_controls()
    {
        $this->start_controls_section("sec1", [
            "label" => __("Settings", "miga_sticky_images"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("onlyOne", [
            "label" => esc_html__("Show only one image", "miga_sticky_images"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_sticky_images"),
            "label_off" => esc_html__("No", "miga_sticky_images"),
            "return_value" => "yes",
            "default" => "yes",
        ]);

        $this->add_control("stickyLast", [
            "label" => esc_html__("Keep last image", "miga_sticky_images"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_sticky_images"),
            "label_off" => esc_html__("No", "miga_sticky_images"),
            "return_value" => "yes",
            "default" => "yes",
        ]);

        $this->add_control("fade", [
            "label" => esc_html__("Fade between images", "miga_sticky_images"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_sticky_images"),
            "label_off" => esc_html__("No", "miga_sticky_images"),
            "return_value" => "yes",
            "default" => "no",
        ]);

        $this->add_control("margin", [
            "label" => esc_html__("Margin between images", "miga_sticky_images"),
            "type" => \Elementor\Controls_Manager::DIMENSIONS,
            "size_units" => ["px", "%", "em"],
            "selectors" => [
                "{{WRAPPER}} .miga_sticky_images__image" =>
                    "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
            ],
        ]);

        $this->add_responsive_control("marginContainer", [
            "label" => esc_html__("Container Margin", "miga_sticky_images"),
            "type" => \Elementor\Controls_Manager::DIMENSIONS,
            "size_units" => ["px", "%", "em"],
            "devices" => ["desktop", "tablet", "mobile"],
            "selectors" => [
                "{{WRAPPER}} .pinit__container" =>
                    "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
            ],
        ]);

        $this->add_control("gallery", [
            "label" => esc_html__("Add Images", "miga_sticky_images"),
            "type" => \Elementor\Controls_Manager::GALLERY,
            "default" => [],
        ]);

        $this->end_controls_section();
    }

    public function get_script_depends()
    {
        return ["miga_sticky_images"];
    }

    public function get_style_depends()
    {
        return ["miga_sticky_images_styles"];
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $onlyOne = "yes" === $settings["onlyOne"] ? true : false;
        $fade = "yes" === $settings["fade"] ? true : false;
        $stickyLast = "yes" === $settings["stickyLast"] ? true : false;
        $imgLen = sizeOf($settings["gallery"]);
        $i = 0;

        echo '<div class="miga_sticky_images__container ' . ($fade ? "fade" : "") . '">';
        foreach ($settings["gallery"] as $image) {
            $stickClass =
                $imgLen - 1 == $i && !$stickyLast ? " pinit_last " : "";
            echo '<div class="miga_sticky_images__image ' .
                ($onlyOne ? "onlyOne" : "") .
                esc_attr($stickClass) .
                '"><img id="pintit_img' .
                esc_attr($i) .
                '" src="' .
                esc_attr($image["url"]) .
                '"></div>';
            $i++;
        }
        echo "</div>";
    }
}
