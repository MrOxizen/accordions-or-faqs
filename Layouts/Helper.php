<?php

namespace OXI_ACCORDIONS_PLUGINS\Layouts;

/**
 * Description of Helper
 *
 * @author Oxizen
 */
use OXI_ACCORDIONS_PLUGINS\Layouts\Admin;
use OXI_ACCORDIONS_PLUGINS\Helper\Controls as Controls;

class Helper extends Admin {

    public function modal_opener() {
        $this->add_substitute_control('', [], [
            'type' => Controls::MODALOPENER,
            'title' => __('Accordions Data Form', OXI_ACCORDIONS_TEXTDOMAIN),
            'sub-title' => __('Open Form', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
            'showing' => TRUE,
        ]);
    }

    public function shortcode_rearrange() {
        $rearrange = $this->Rearrange();
        if (!empty($rearrange)):
            $this->add_substitute_control($rearrange, [], [
                'type' => Controls::REARRANGE,
                'showing' => TRUE,
                'condition' => [
                    'oxi-accordions-content-type' => 'content'
                ],
            ]);
        endif;
    }

    use \OXI_ACCORDIONS_PLUGINS\Helper\Post_Query;

    public function register_controls() {

        $this->start_section_header(
                'shortcode-addons-start-tabs', [
            'options' => [
                'general-settings' => esc_html__('General Settings', OXI_ACCORDIONS_TEXTDOMAIN),
                'heading-settings' => esc_html__('Heading Settings', OXI_ACCORDIONS_TEXTDOMAIN),
                'description-settings' => esc_html__('Description Settings', OXI_ACCORDIONS_TEXTDOMAIN),
                'custom' => esc_html__('Custom CSS', OXI_ACCORDIONS_TEXTDOMAIN),
            ]
                ]
        );
        // General Section
        $this->register_general_parent();

        // Heading Section
        $this->register_heading_parent();

        //Description Section
        $this->register_description_parent();

        //Custom CSS
        $this->register_custom_parent();
    }

    public function register_general_parent() {
        //General Section
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'general-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_gen_general();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_gen_heading();
        $this->register_post_query_settings();

        $this->end_section_devider();
        $this->end_section_tabs();
        //General Section End
        //
    }

    public function register_gen_general() {
        $this->start_controls_section(
                'oxi-accordions-gen', [
            'label' => esc_html__('General Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-content-type', $this->style, [
            'label' => __('Content Type', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'content',
            'options' => [
                'content' => [
                    'title' => __('Content', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                'post' => [
                    'title' => __('Post', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Select Accordion Type as Content or Post.',
                ]
        );
        $this->add_control(
                'oxi-accordions-type', $this->style, [
            'label' => __('Accordions', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'loader' => TRUE,
            'default' => 'oxi-accordions-toggle',
            'options' => [
                'oxi-accordions-toggle' => [
                    'title' => __('Toggle', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                'oxi-accordions-accordions' => [
                    'title' => __('Accordions', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Choose Accordions Type to open content with Toggle or Accordions Method.',
                ]
        );
        $this->add_control(
                'oxi-accordions-trigger', $this->style, [
            'label' => __('Activator Event', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'oxi-accordions-click-event',
            'loader' => TRUE,
            'options' => [
                'oxi-accordions-click-event' => [
                    'title' => __('Click', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                'oxi-accordions-hover-event' => [
                    'title' => __('Hover', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-single-card' => '',
            ],
            'description' => 'Select either your Accordions will open on Click or Hover.',
                ]
        );
        $this->add_control(
                'oxi-accordions-transition-duration', $this->style, [
            'label' => __('Transition Duration', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'ms',
                'size' => '',
            ],
            'range' => [
                'ms' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style .oxi-accordions-single-card > .oxi-accordions-content-card.oxicollapsing' => 'height: 0; overflow: hidden; transition-property: height; transition-duration: {{SIZE}}ms;',
            ],
            'description' => 'Set fixed accordion content panel height.',
                ]
        );
        $this->add_control(
                'oxi-accordions-gen-animation', $this->style, [
            'label' => __('Animation', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => '',
            'loader' => TRUE,
            'options' => [
                'optgroup0' => [true, 'Attention Seekers'],
                '' => __('No Animation', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__bounce' => __('Bounce', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__flash' => __('Flash', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__pulse' => __('Pulse', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__rubberBand' => __('Rubber Band', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__shakeX' => __('ShakeX', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__headShake' => __('Head Shake', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__swing' => __('Swing', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__tada' => __('Tada', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__wobble' => __('Wobble', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__jello' => __('Jello', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__heartBeat' => __('Heart Beat', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup1' => [false],
                'optgroup2' => [true, 'Back Entrances'],
                'animate__backInDown' => __('Back In Down', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__backInLeft' => __('Back In Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__backInRight' => __('Back In Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__backInUp' => __('Back In Up', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup3' => [false],
                'optgroup4' => [true, 'Bouncing Entrances'],
                'animate__bounceIn' => __('Bounce In', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__bounceInDown' => __('Bounce In Down', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__bounceInLeft' => __('Bounce In Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__bounceInRight' => __('Bounce In Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__bounceInUp' => __('Bounce In Up', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup5' => [false],
                'optgroup6' => [true, 'Fading Entrances'],
                'animate__fadeIn' => __('Fade In', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInDown' => __('Fade In Down', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInDownBig' => __('Fade In Down Big', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInLeft' => __('Fade In Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInLeftBig' => __('Fade In Left Big', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInRight' => __('Fade In Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInRightBig' => __('Fade In Right Big', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInUp' => __('Fade In Up', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInUpBig' => __('Fade In Up Big', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInTopLeft' => __('Fade In Top Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInTopRight' => __('Fade In Top Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInBottomLeft' => __('Fade In Bottom Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__fadeInBottomRight' => __('Fade In Bottom Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup7' => [false],
                'optgroup8' => [true, 'Flippers'],
                'animate__flip' => __('Flip', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__flipInX' => __('Flip In X', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__flipInY' => __('Flip In Y', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup9' => [false],
                'optgroup10' => [true, 'Lightspeed'],
                'animate__lightSpeedInRight' => __('Light Speed In Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__lightSpeedInLeft' => __('Light Speed In Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup11' => [false],
                'optgroup12' => [true, 'Rotating Entrances'],
                'animate__rotateIn' => __('Rotate In', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__rotateInDownLeft' => __('Rotate In Down Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__rotateInDownRight' => __('Rotate In Down Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__rotateInUpLeft' => __('Rotate In Up Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__rotateInUpRight' => __('Rotate In Up Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup13' => [false],
                'optgroup14' => [true, 'Specials'],
                'animate__hinge' => __('Hinge', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__jackInTheBox' => __('Jack In The Box', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__rollIn' => __('Roll In', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup15' => [false],
                'optgroup16' => [true, 'Zooming Entrances'],
                'animate__zoomIn' => __('Zoom In', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__zoomInDown' => __('Zoom In Down', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__zoomInLeft' => __('Zoom In Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__zoomInRight' => __('Zoom In Right', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__zoomInUp' => __('Zoom In Up', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup17' => [false],
                'optgroup18' => [true, 'Sliding Entrances'],
                'animate__slideInDown' => __('Slide In Down', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__slideInLeft' => __('Slide In Left', OXI_ACCORDIONS_TEXTDOMAIN),
                'animate__slideInUp' => __('Slide In Up', OXI_ACCORDIONS_TEXTDOMAIN),
                'optgroup19' => [false],
            ],
            'description' => 'Add Animation Effect on Accordions opening.',
                ]
        );
        $this->add_control(
                'oxi-accordions-animation-duration', $this->style, [
            'label' => __('Animation Duration', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 's',
                'size' => '',
            ],
            'range' => [
                's' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.01,
                ],
            ],
            'condition' => [
                'oxi-accordions-gen-animation' => 'EMPTY',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style .oxi-accordions-single-card > .oxi-accordions-content-card > .animate__animated' => '-webkit-animation-duration: {{SIZE}}s;animation-duration: {{SIZE}}s;',
            ],
            'description' => 'Set fixed accordion content panel height.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-general-padding', $this->style, [
            'label' => __('Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space inside each Accordions Body.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-general-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Accordions Body.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_gen_heading() {
        $this->start_controls_section(
                'oxi-accordions-heading', [
            'label' => esc_html__('Accordions Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-content-height', $this->style, [
            'label' => __('Fixed Content Height', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SWITCHER,
            'label_on' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
            'label_off' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
            'return_value' => 'oxi-accordions-content-height',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style .oxi-accordions-content-body' => '',
            ],
            'description' => 'Check to display collapsible accordion content in a limited amount of space.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-content-mx-height', $this->style, [
            'label' => __('Maximum Height', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-content-height' => 'oxi-accordions-content-height',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style .oxi-accordions-content-body.oxi-accordions-content-height' => 'max-height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set fixed accordion content panel height.',
                ]
        );

        $this->add_control(
                'oxi-accordions-preloader', $this->style, [
            'label' => __('Preloader', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SWITCHER,
            'label_on' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
            'label_off' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
            'return_value' => 'yes',
            'description' => 'Accordion will be hidden until page load completed.',
                ]
        );
        $this->add_control(
                'oxi-accordions-expand-collapse', $this->style, [
            'label' => __('Expand & Collapse Icon', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SWITCHER,
            'label_on' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
            'label_off' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
            'return_value' => 'oxi-accordions-expand-collapse-active',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-header-card > .oxi-accordions-header-body .oxi-accordions-expand-collapse' => '',
            ],
            'description' => 'Show/hide expand and collapse icon.',
                ]
        );
        $this->end_controls_section();
    }

    /*
     * @return void
     * Start Post Query for Display Post
     */

    public function register_post_query_settings() {
        $this->start_controls_section(
                'display-post',
                [
                    'label' => esc_html__('Post Query', OXI_ACCORDIONS_TEXTDOMAIN),
                    'showing' => true,
                    'condition' => [
                        'oxi-accordions-content-type' => 'post'
                    ],
                ]
        );
        $this->add_control(
                'display_post_post_type',
                $this->style,
                [
                    'label' => __('Post Type', OXI_ACCORDIONS_TEXTDOMAIN),
                    'loader' => TRUE,
                    'type' => Controls::SELECT,
                    'default' => 'post',
                    'options' => $this->post_type(),
                    'description' => 'Select Post Type for Query.'
                ]
        );
        $this->add_control(
                'display_post_author',
                $this->style,
                [
                    'label' => __('Author', OXI_ACCORDIONS_TEXTDOMAIN),
                    'loader' => TRUE,
                    'type' => Controls::SELECT,
                    'multiple' => true,
                    'options' => $this->post_author(),
                    'description' => 'Confirm Author list if you wanna those author post only.'
                ]
        );
        foreach ($this->post_type() as $key => $value) {
            if ($key != 'page') :
                $this->add_control(
                        $key . '_category',
                        $this->style,
                        [
                            'label' => __(' Category', OXI_ACCORDIONS_TEXTDOMAIN),
                            'type' => Controls::SELECT,
                            'multiple' => true,
                            'loader' => TRUE,
                            'options' => $this->post_category($key),
                            'condition' => [
                                'display_post_post_type' => $key
                            ],
                            'description' => 'Confirm Category list if you wanna those Category post only.',
                        ]
                );
                $this->add_control(
                        $key . '_tag',
                        $this->style,
                        [
                            'label' => __(' Tags', OXI_ACCORDIONS_TEXTDOMAIN),
                            'type' => Controls::SELECT,
                            'multiple' => true,
                            'loader' => TRUE,
                            'options' => $this->post_tags($key),
                            'condition' => [
                                'display_post_post_type' => $key
                            ],
                            'description' => 'Confirm Post Tags if you wanna show those tags post only.',
                        ]
                );
            endif;

            $this->add_control(
                    $key . '_include',
                    $this->style,
                    [
                        'label' => __(' Include Post', OXI_ACCORDIONS_TEXTDOMAIN),
                        'type' => Controls::SELECT,
                        'multiple' => true,
                        'loader' => TRUE,
                        'options' => $this->post_include($key),
                        'condition' => [
                            'display_post_post_type' => $key
                        ],
                        'description' => 'Only those post will viewing in Post list.',
                    ]
            );
            $this->add_control(
                    $key . '_exclude',
                    $this->style,
                    [
                        'label' => __(' Exclude Post', OXI_ACCORDIONS_TEXTDOMAIN),
                        'type' => Controls::SELECT,
                        'multiple' => true,
                        'loader' => TRUE,
                        'options' => $this->post_exclude($key),
                        'condition' => [
                            'display_post_post_type' => $key
                        ],
                        'description' => 'Those Post can\'t viewing.',
                    ]
            );
        }


        $this->add_control(
                'display_post_per_page',
                $this->style,
                [
                    'label' => __('Maximum Post', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::NUMBER,
                    'loader' => TRUE,
                    'min' => 1,
                    'description' => 'How many Post You want to Viewing into page.',
                ]
        );
        $this->add_control(
                'display_post_offset',
                $this->style,
                [
                    'label' => __('Offset', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::NUMBER,
                    'loader' => TRUE,
                    'description' => 'Confirm Excerpt Word Limit.',
                ]
        );
        $this->add_control(
                'display_post_orderby',
                $this->style,
                [
                    'label' => __(' Order By', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::SELECT,
                    'default' => 'ID',
                    'loader' => TRUE,
                    'options' => [
                        'ID' => 'Post ID',
                        'author' => 'Post Author',
                        'title' => 'Title',
                        'date' => 'Date',
                        'modified' => 'Last Modified Date',
                        'parent' => 'Parent Id',
                        'rand' => 'Random',
                        'comment_count' => 'Comment Count',
                        'menu_order' => 'Menu Order',
                    ],
                    'description' => 'Set Post Query Order by Condition.',
                ]
        );

        $this->add_control(
                'display_post_ordertype',
                $this->style,
                [
                    'label' => __('Order Type', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::SELECT,
                    'loader' => TRUE,
                    'options' => [
                        'asc' => 'Ascending',
                        'desc' => 'Descending',
                    ],
                    'description' => 'Set Post Query Order by Condition.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_heading_parent() {
        //Heading Section
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'heading-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_header_general();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_header_title();
        $this->register_header_expand_collapse_icon();
        $this->register_header_sub_title();
        $this->register_header_icon();
        $this->register_header_number();
        $this->register_header_image();
        $this->end_section_devider();
        $this->end_section_tabs();
    }

    public function register_header_general() {
        $this->start_controls_section(
                'oxi-accordions-head-expand-collapse-icon-head', [
            'label' => esc_html__('Header General', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );

        $this->add_control(
                'oxi-accordions-head-aditional-location', $this->style, [
            'label' => __('Title Additional Location', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SWITCHER,
            'label_on' => __('Right', OXI_ACCORDIONS_TEXTDOMAIN),
            'label_off' => __('Left', OXI_ACCORDIONS_TEXTDOMAIN),
            'return_value' => 'oxi-accordions-header-aditional-right-position',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-single-card' => '',
            ],
            'description' => 'Set the Location of Title’s Additionals (Icon, Image, or Number.',
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-start-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );

        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-bg', $this->style, [
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'default' => 'rgba(171, 0, 201, 1)',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the Background of the Header on Normal Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body' => ''
                    ],
                    'description' => 'Customize Border of the Header. Set Type, Width, and Color.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-ac-bg', $this->style, [
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the Background of the Header.on Active Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-hover-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-body' => ''
                    ],
                    'description' => 'Customize Border of the Header. Set Type, Width, and Color.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
                'oxi-accordions-head-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body' => '',
            ],
            'description' => 'Add one or more shadows into Header Section and customize other Box-Shadow Options.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-radius', $this->style, [
            'label' => __('Border Radius', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Header’s Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-padding', $this->style, [
            'label' => __('Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space inside of the Header Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Header Section.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_title() {
        $this->start_controls_section(
                'oxi-accordions-head-title', [
            'label' => esc_html__('Title Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-title-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => '',
            ],
            'description' => 'Customize the Typography options for the Tab’s Title.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-title-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-title-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Title Color on Normal Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-title-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => '',
            ],
            'description' => 'Add one or more shadows into Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-title-ac-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Title Color on Active Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-title-ac-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => '',
            ],
            'description' => 'Add one or more shadows into Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-head-title-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-body  .oxi-accordions-main-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Title.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_sub_title() {
        $this->start_controls_section(
                'oxi-accordions-head-sub-title', [
            'label' => esc_html__('Sub Title Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-sub-title-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-sub-title' => '',
            ],
            'description' => 'Customize the Typography options for the Tab’s Sub Title.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-sub-title-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-sub-title-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-sub-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Sub Title Color on Normal Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-sub-title-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-sub-title' => '',
            ],
            'description' => 'Add one or more shadows into Sub Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-sub-title-ac-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-sub-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Sub Title Color on Active Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-sub-title-ac-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-sub-title' => '',
            ],
            'description' => 'Add one or more shadows into Sub Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-head-sub-title-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-sub-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Sub Title.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_icon() {
        $this->start_controls_section(
                'oxi-accordions-head-icon', [
            'label' => esc_html__('Icon Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-head-icon-interface',
                $this->style,
                [
                    'label' => __('Customization Interface', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'toggle' => true,
                    'default' => 'simple',
                    'options' => [
                        'simple' => [
                            'title' => __('Simple', OXI_ACCORDIONS_TEXTDOMAIN),
                        ],
                        'customizable' => [
                            'title' => __('Customizable', OXI_ACCORDIONS_TEXTDOMAIN),
                        ],
                    ],
                    'description' => 'Set the Icon Customization Interface either Simple or fully Customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-icon-width', $this->style, [
            'label' => __('Width', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Width.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-icon-height', $this->style, [
            'label' => __('Height', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => 'height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Height.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-head-icon-size', $this->style, [
            'label' => __('Icon Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 20,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-icons' => 'font-size:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon Size (PX, % or EM).',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-head-icon-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-icon-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-icons' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon’s Color on Normal Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-icon-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Normal Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-icon-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-icon-ac-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-icons' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon’s Color on Active Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-icon-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Active Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-icon-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-head-icon-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-head-icon-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content  .oxi-accordions-additional-icon' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Icon’s  Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-icon-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Icon.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_header_expand_collapse_icon() {
        $this->start_controls_section(
                'oxi-accordions-head-expand-collapse-icon', [
            'label' => esc_html__('Expand & Collapse Icon', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-expand-collapse' => 'oxi-accordions-expand-collapse-active',
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-location', $this->style, [
            'label' => __('Expand & Collapse Location', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SWITCHER,
            'label_on' => __('Right', OXI_ACCORDIONS_TEXTDOMAIN),
            'label_off' => __('Left', OXI_ACCORDIONS_TEXTDOMAIN),
            'return_value' => 'oxi-accordions-head-expand-collapse-right-position',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-header-card' => '',
            ],
            'description' => 'Set the Location of Expand & Collapse.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-type', $this->style, [
            'label' => __('Expand & Collapse Type', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'oxi-accordions-head-expand-collapse-type-icon',
            'options' => [
                'oxi-accordions-head-expand-collapse-type-icon' => [
                    'title' => __('Icon', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                'oxi-accordions-head-expand-collapse-type-number' => [
                    'title' => __('Number', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-expand-collapse' => '',
            ],
            'description' => 'Select Accordion Type as Content or Post.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-expand-icon', $this->style, [
            'label' => esc_html__('Expand Icon', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::ICON,
            'default' => 'fab fa-facebook-f',
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
            ],
            'description' => 'Select Expand Icon from Font Awesome Icon list Panel.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-collapse-icon', $this->style, [
            'label' => esc_html__('Collapse Icon', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::ICON,
            'default' => 'fab fa-facebook-f',
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
            ],
            'description' => 'Select Collapse Icon from Font Awesome Icon list Panel.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-start-number', $this->style, [
            'label' => esc_html__('Starting Number', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => '0',
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-number',
            ],
            'description' => 'Set Frist Number of Collapse as 1 will increase automaticlly.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-interface',
                $this->style,
                [
                    'label' => __('Customization Interface', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'toggle' => true,
                    'default' => 'simple',
                    'options' => [
                        'simple' => [
                            'title' => __('Simple', OXI_ACCORDIONS_TEXTDOMAIN),
                        ],
                        'customizable' => [
                            'title' => __('Customizable', OXI_ACCORDIONS_TEXTDOMAIN),
                        ],
                    ],
                    'description' => 'Set the Icon Customization Interface either Simple or fully Customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-width', $this->style, [
            'label' => __('Width', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Width.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-height', $this->style, [
            'label' => __('Height', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse' => 'height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Height.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-size', $this->style, [
            'label' => __('Icon Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 20,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse .oxi-icons' => 'font-size:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon Size (PX, % or EM).',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-expand-collapse-number-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse .oxi-accordions-expand-collapse-number' => '',
            ],
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-number',
            ],
            'description' => 'Customize the Typography options for the Number.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-icon-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse .oxi-icons' => 'color: {{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse .oxi-accordions-expand-collapse-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon or Number Color on Normal Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Normal Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-expand-collapse-icon-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse ' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-ac-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse .oxi-icons' => 'color: {{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse .oxi-accordions-expand-collapse-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon’s Color on Active Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Active Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-expand-collapse-icon-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse ' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Icon’s  Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card > .oxi-accordions-header-body > .oxi-accordions-expand-collapse ' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Icon.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_header_number() {
        $this->start_controls_section(
                'oxi-accordions-head-number', [
            'label' => esc_html__('Number Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-head-number-interface',
                $this->style,
                [
                    'label' => __('Customization Interface', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'toggle' => true,
                    'default' => 'simple',
                    'options' => [
                        'simple' => [
                            'title' => __('Simple', OXI_ACCORDIONS_TEXTDOMAIN),
                        ],
                        'customizable' => [
                            'title' => __('Customizable', OXI_ACCORDIONS_TEXTDOMAIN),
                        ],
                    ],
                    'description' => 'Set the Number Customization Interface either Simple or fully Customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-number-width', $this->style, [
            'label' => __('Width', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Number’s Width.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-number-height', $this->style, [
            'label' => __('Height', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Number’s Height.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-number-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => '',
            ],
            'description' => 'Customize the Typography options for the Number.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-number-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Number’s Color on Normal Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-number-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'background:{{VALUE}};',
            ],
            'description' => 'Customize Number Background with Color, Gradient or Image properties for Normal Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-number-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => '',
            ],
            'description' => 'Customize Border of the Number. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-number-ac-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Number’s Color on Active Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-number-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'background:{{VALUE}};',
            ],
            'description' => 'Customize Number Background with Color, Gradient or Image properties for Active Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-number-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-li-number' => '',
            ],
            'description' => 'Customize Border of the Number. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-head-number-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-head-number-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Number’s border.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-number-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Number on the header.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_image() {
        $this->start_controls_section(
                'oxi-accordions-head-image', [
            'label' => esc_html__('Image Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-image-width', $this->style, [
            'label' => __('Width', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-image' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Image’s Width.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        $this->add_group_control(
                'oxi-accordions-head-image-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-image' => '',
            ],
            'description' => 'Customize Border of the Image. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_group_control(
                'oxi-accordions-head-image-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-header-card .oxi-accordions-header-li-image' => '',
            ],
            'description' => 'Customize Border of the Image. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-head-image-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-image' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Image’s Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-image-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-header-card .oxi-accordions-header-li-image' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Image on the header.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_description_parent() {
        //Description Section
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'description-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_desc_general();
        $this->register_desc_tags();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_desc_content();
        $this->register_desc_popular();
        $this->register_desc_recent();
        $this->register_desc_comment();
        $this->end_section_devider();
        $this->end_section_tabs();
    }

    public function register_desc_general() {
        $this->start_controls_section(
                'oxi-accordions-desc-general', [
            'label' => esc_html__('General Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-general-bg', $this->style, [
            'label' => __('Background', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize the Content’s Background with Color, Gradient or Image properties.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-desc-general-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => '',
            ],
            'description' => 'Add one or more shadows into the Content body and customize other Box-Shadow Options.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-general-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => ''
                    ],
                    'description' => 'Customize Border of the Content Body. Set Type, Width, and Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-general-radius', $this->style, [
            'label' => __('Border Radius', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Content’s Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-general-padding', $this->style, [
            'label' => __('Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content Body including background color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-general-margin', $this->style, [
            'label' => __('Margin', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Content Body.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_desc_content() {
        $this->start_controls_section(
                'oxi-accordions-desc-content', [
            'label' => esc_html__('Content Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );

        $this->add_group_control(
                'oxi-accordions-desc-content-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => '',
            ],
            'description' => 'Customize the Typography options for the Tab’s Contents.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-content-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Color of Tab’s Contents.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-content-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => '',
            ],
            'description' => 'Add one or more shadows into the Content Texts and customize other Text-Shadow Options.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-content-padding', $this->style, [
            'label' => __('Content Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust Your Content Padding for Peragraph Tag.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_popular() {
        $this->start_controls_section(
                'oxi-accordions-desc-popular', [
            'label' => esc_html__('Popular Post Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        //content Section
        $this->add_control(
                'oxi-accordions-desc-popular-post', $this->style, [
            'label' => esc_html__('Max Post', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 5,
            'description' => 'Write the Maximum amount of Popular Posts.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-desc-popular-tabs',
                [
                    'options' => [
                        'image' => esc_html__('Image ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'title' => esc_html__('Title', OXI_ACCORDIONS_TEXTDOMAIN),
                        'meta' => esc_html__('Meta', OXI_ACCORDIONS_TEXTDOMAIN),
                        'content' => esc_html__('Content', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        //image Section
        $this->add_control(
                'oxi-accordions-desc-popular-thumb-condi', $this->style, [
            'label' => __('Show Image', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the image under the Popular Post.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-thumb-max', $this->style, [
            'label' => esc_html__('Image Size (px)', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 65,
            'condition' => [
                'oxi-accordions-desc-popular-thumb-condi' => '1'
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-content-body .oxi-accordions-popular-post .oxi-accordions-popular-avatar' => 'max-width:{{VALUE}}px;',
            ],
            'description' => 'Set the Image Size (PX).',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-thumb', $this->style, [
            'label' => __('Image Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'loader' => TRUE,
            'options' => $this->thumbnail_sizes(),
            'condition' => [
                'oxi-accordions-desc-popular-thumb-condi' => '1'
            ],
            'description' => 'Select a Pre-defined Image Thumbnail Size.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //title Section
        $this->add_group_control(
                'oxi-accordions-desc-popular-title-typo', $this->style, [
            'label' => 'Title Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-content-body .oxi-accordions-popular-body .oxi-accordions-popular-meta a' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-title-color', $this->style, [
            'label' => __('Title Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-meta a' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-title-h-color', $this->style, [
            'label' => __('Title Hover Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-meta a:hover' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title in Hover view.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-popular-title-padding', $this->style, [
            'label' => __('Title Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-meta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around at Post Title from other Content.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //meta Section
        $this->add_control(
                'oxi-accordions-desc-popular-meta-date', $this->style, [
            'label' => __('Show Date', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Date in the Post?',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-meta-comment', $this->style, [
            'label' => __('Show Comment', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Comment in the Post?',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-desc-popular-meta-typo', $this->style, [
            'label' => 'Meta Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-postmeta' => '',
                '{{WRAPPER}} .oxi-accordions-popular-postmeta .oxi-accordions-popular-date' => '',
                '{{WRAPPER}} .oxi-accordions-popular-postmeta .oxi-accordions-popular-comment' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Meta.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-meta-color', $this->style, [
            'label' => __('Meta Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-postmeta' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-popular-postmeta .oxi-accordions-popular-date' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-popular-postmeta .oxi-accordions-popular-comment' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Meta Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-popular-meta-padding', $this->style, [
            'label' => __('Meta Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-postmeta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Meta.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //content Section
        $this->add_control(
                'oxi-accordions-desc-popular-content-lenth', $this->style, [
            'label' => esc_html__('Content Lenth', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 90,
            'description' => 'Set the Max Content Length.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-popular-content-typo', $this->style, [
            'label' => 'Content Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-content' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Content.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-popular-content-color', $this->style, [
            'label' => __('Content Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-content' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Content Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-popular-content-padding', $this->style, [
            'label' => __('Content Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-popular-body .oxi-accordions-popular-content' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-desc-popular-padding', $this->style, [
            'label' => __('Content Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-popular-post' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Popular Post.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_recent() {
        $this->start_controls_section(
                'oxi-accordions-desc-recent', [
            'label' => esc_html__('Recent Post Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        //content Section
        $this->add_control(
                'oxi-accordions-desc-recent-post', $this->style, [
            'label' => esc_html__('Max Post', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 5,
            'description' => 'Write the Maximum amount of Recent Posts.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-desc-recent-tabs',
                [
                    'options' => [
                        'image' => esc_html__('Image ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'title' => esc_html__('Title', OXI_ACCORDIONS_TEXTDOMAIN),
                        'meta' => esc_html__('Meta', OXI_ACCORDIONS_TEXTDOMAIN),
                        'content' => esc_html__('Content', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        //image Section
        $this->add_control(
                'oxi-accordions-desc-recent-thumb-condi', $this->style, [
            'label' => __('Show Image', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the image under the Recent Post.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-thumb-max', $this->style, [
            'label' => esc_html__('Image Size (px)', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 65,
            'condition' => [
                'oxi-accordions-desc-recent-thumb-condi' => '1'
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-post .oxi-accordions-recent-avatar' => 'max-width:{{VALUE}}px;',
            ],
            'description' => 'Set the Image Size (PX).',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-thumb', $this->style, [
            'label' => __('Image Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'loader' => TRUE,
            'options' => $this->thumbnail_sizes(),
            'condition' => [
                'oxi-accordions-desc-recent-thumb-condi' => '1'
            ],
            'description' => 'Select a Pre-defined Image Thumbnail Size.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //title Section
        $this->add_group_control(
                'oxi-accordions-desc-recent-title-typo', $this->style, [
            'label' => 'Title Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-meta a' => '',
            ],
            'description' => 'Customize the Typography options for the Recent Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-title-color', $this->style, [
            'label' => __('Title Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-meta a' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-title-h-color', $this->style, [
            'label' => __('Title Hover Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-meta a:hover' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title in Hover view.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-recent-title-padding', $this->style, [
            'label' => __('Title Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-meta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Title.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //meta Section
        $this->add_control(
                'oxi-accordions-desc-recent-meta-date', $this->style, [
            'label' => __('Show Date', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Date in the Recent Post?',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-meta-comment', $this->style, [
            'label' => __('Show Comment', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Comment in the Post?',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-desc-recent-meta-typo', $this->style, [
            'label' => 'Meta Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-postmeta' => '',
                '{{WRAPPER}} .oxi-accordions-recent-postmeta .oxi-accordions-recent-date' => '',
                '{{WRAPPER}} .oxi-accordions-recent-postmeta .oxi-accordions-recent-comment' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Meta.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-meta-color', $this->style, [
            'label' => __('Meta Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-postmeta' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-recent-postmeta .oxi-accordions-recent-date' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-recent-postmeta .oxi-accordions-recent-comment' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Meta Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-recent-meta-padding', $this->style, [
            'label' => __('Meta Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-postmeta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Meta.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //content Section
        $this->add_control(
                'oxi-accordions-desc-recent-content-lenth', $this->style, [
            'label' => esc_html__('Content Lenth', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 90,
            'description' => 'Set the Max Content Length.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-recent-content-typo', $this->style, [
            'label' => 'Content Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-content' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Content.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-recent-content-color', $this->style, [
            'label' => __('Content Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-content' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Content Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-recent-content-padding', $this->style, [
            'label' => __('Content Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-recent-body .oxi-accordions-recent-content' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-desc-recent-padding', $this->style, [
            'label' => __('Content Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-recent-post' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Recent Post.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_comment() {
        $this->start_controls_section(
                'oxi-accordions-desc-tags', [
            'label' => esc_html__('Comment Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-comment-max', $this->style, [
            'label' => esc_html__('Max Comment', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 5,
            'description' => 'Set the maximum amount of Comments.',
                ]
        );

        $this->add_control(
                'oxi-accordions-desc-comment-show-avatar', $this->style, [
            'label' => __('Show Avatar', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 1,
            'options' => [
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the Avatar of comments.',
                ]
        );

        $this->add_control(
                'oxi-accordions-desc-comment-avatar-size', $this->style, [
            'label' => esc_html__('Avatar Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 65,
            'condition' => [
                'oxi-accordions-desc-comment-show-avatar' => '1',
            ],
            'description' => 'Set the Avatar Size.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-comment-comment-lenth', $this->style, [
            'label' => esc_html__('Comment Lenth', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 90,
            'description' => 'Customize the Comment’s Length.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-comment-typho', $this->style, [
            'label' => 'Title Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style span.oxi-accordions-comment-author' => '',
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-comment-meta a' => '',
                '{{WRAPPER}} .oxi-accordions-ultimate-style a span.oxi-accordions-comment-post' => '',
            ],
            'description' => ' Customize the Typography options for the Comment’s Title.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-comment-excerpt-typo', $this->style, [
            'label' => 'Comment Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-comment-body .oxi-accordions-comment-content' => '',
            ],
            'description' => 'Customize the Typography options for the Comment’s Content.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'hover' => esc_html__('Hover', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-desc-comment-title-color', $this->style, [
            'label' => __('Title Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style span.oxi-accordions-comment-author' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-comment-meta a' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style a span.oxi-accordions-comment-post' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Title Color.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-comment-comment-color', $this->style, [
            'label' => __('Comment Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-comment-body .oxi-accordions-comment-content' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Color to the Comment Content.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-desc-comment-title-hover-color', $this->style, [
            'label' => __('Title Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style span.oxi-accordions-comment-author:hover' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-comment-meta a:hover' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style a span.oxi-accordions-comment-post:hover' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Title Color while Hover.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-desc-comment-padding', $this->style, [
            'label' => __('Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-comment' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Comment.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_tags() {
        $this->start_controls_section(
                'oxi-accordions-desc-tags', [
            'label' => esc_html__('Tags Settings', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => false,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-tags-max', $this->style, [
            'label' => esc_html__('Max Tags', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 20,
            'description' => 'Set the maximum amount of Tags.',
                ]
        );

        $this->add_control(
                'oxi-accordions-desc-tags-show-count', $this->style, [
            'label' => __('Show Count', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 1,
            'options' => [
                '0' => [
                    'title' => __('False', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
                '1' => [
                    'title' => __('True', OXI_ACCORDIONS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the tags count.',
                ]
        );

        $this->add_control(
                'oxi-accordions-desc-tags-small', $this->style, [
            'label' => esc_html__('Small Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 12,
            'description' => 'Set the Small Size of the Tags.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-tags-big', $this->style, [
            'label' => esc_html__('Big Size', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 25,
            'description' => 'Set the Big Size of the Tags.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-tags-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            Controls::TYPO_FONTSIZE => false,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-body-tabs .tag-cloud-link' => '',
            ],
            'description' => 'Customize the Typography options for the Tag Text.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_ACCORDIONS_TEXTDOMAIN),
                        'hover' => esc_html__('Hover', OXI_ACCORDIONS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-desc-tags-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-body-tabs .tag-cloud-link' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Color of Tab’s Tag in Normal view.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-desc-tags-hover-color', $this->style, [
            'label' => __('Color', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-body-tabs .tag-cloud-link:hover' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Color of Tab’s Tag in Hover view.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-desc-tags-padding', $this->style, [
            'label' => __('Content Padding', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-body-tabs .tag-cloud-link' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display: inline-block;',
            ],
            'description' => 'Generate some Space around the Tag’s Content including background color.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_custom_parent() {
        ///Custom CSS
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'custom'
            ],
            'padding' => '10px'
                ]
        );

        $this->start_controls_section(
                'oxi-accordions-start-tabs-css', [
            'label' => esc_html__('Custom CSS', OXI_ACCORDIONS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-custom-css', $this->style, [
            'label' => __('', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::TEXTAREA,
            'default' => '',
            'description' => 'Custom CSS Section. You can add custom css into textarea.'
                ]
        );
        $this->end_controls_section();
        $this->end_section_tabs();
    }

    public function modal_form_data() {
        echo '<div class="modal-header">                    
                    <h4 class="modal-title">Accordions Modal Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">';
        $this->add_control(
                'oxi-accordions-modal-default', [], [
            'label' => __('Default Open', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SWITCHER,
            'label_on' => __('Yes', OXI_ACCORDIONS_TEXTDOMAIN),
            'label_off' => __('No', OXI_ACCORDIONS_TEXTDOMAIN),
            'return_value' => 'yes',
            'description' => 'Expand this accordion on page load.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-title', [], [
            'label' => esc_html__('Title', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::TEXT,
            'default' => 'Lorem Ipsum',
            'description' => 'Add Title of your Accordions else Make it Blank.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-sub-title', [], [
            'label' => esc_html__('Sub Title', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::TEXT,
            'description' => 'Add Sub Title of your Accordions else Make it Blank.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-title-additional', [], [
            'label' => __('Title Additional', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => __('None', OXI_ACCORDIONS_TEXTDOMAIN),
                'icon' => __('Icon', OXI_ACCORDIONS_TEXTDOMAIN),
                'number' => __('Number', OXI_ACCORDIONS_TEXTDOMAIN),
                'image' => __('Image', OXI_ACCORDIONS_TEXTDOMAIN),
            ],
            'description' => 'Add the Additional elements beside the Accordions’s Title (Icon, Number or Image).',
                ]
        );

        $this->add_control(
                'oxi-accordions-modal-icon', [], [
            'label' => esc_html__('Icon', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::ICON,
            'default' => 'fab fa-facebook-f',
            'condition' => [
                'oxi-accordions-modal-title-additional' => 'icon',
            ],
            'description' => 'Select Icon from Font Awesome Icon list Panel.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-number', [], [
            'label' => esc_html__('Number', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 1,
            'condition' => [
                'oxi-accordions-modal-title-additional' => 'number',
            ],
            'description' => 'Write the Number as Title Additionals.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-modal-image', [],
                [
                    'label' => __('Image', OXI_ACCORDIONS_TEXTDOMAIN),
                    'type' => Controls::MEDIA,
                    'condition' => [
                        'oxi-accordions-modal-title-additional' => 'image',
                    ],
                    'description' => 'Add an Image from Media Library or Input a custom Image URL.'
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-components-type', [], [
            'label' => __('Choose Components', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => 'wysiwyg',
            'options' => [
                'wysiwyg' => __('WYSIWYG Editor', OXI_ACCORDIONS_TEXTDOMAIN),
                'nested-accordions' => __('Nested Accordions', OXI_ACCORDIONS_TEXTDOMAIN),
                'link' => __('Custom Link', OXI_ACCORDIONS_TEXTDOMAIN),
            ],
            'description' => 'Se the accordions’s Content type as Content or Custom Link.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-modal-link', [], [
            'label' => esc_html__('Link', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::URL,
            'condition' => [
                'oxi-accordions-modal-components-type' => 'link',
            ],
            'description' => 'Add Custom link with opening type.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-desc', [], [
            'label' => __('Description', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::WYSIWYG,
            'default' => '',
            'condition' => [
                'oxi-accordions-modal-components-type' => 'wysiwyg',
            ],
            'description' => 'Add your Tab’s Description.',
                ]
        );

        $this->add_control(
                'oxi-accordions-modal-nested-accordions', [], [
            'label' => __('Select Accordions', OXI_ACCORDIONS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => $this->Get_Nested_Accordions,
            'condition' => [
                'oxi-accordions-modal-components-type' => 'nested-accordions',
            ],
            'description' => 'Select Accordions to Create Nested.',
                ]
        );

        echo '</div>';
    }

}
