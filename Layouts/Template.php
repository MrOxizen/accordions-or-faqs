<?php

namespace OXI_ACCORDIONS_PLUGINS\Layouts;

/**
 * Description of Template
 *
 * @author biplo
 */
class Template {

    /**
     * Current Elements id
     *
     * @since 2.0.1
     */
    public $oxiid;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.1
     */
    public $style = [];

    /**
     * Current Elements Style Full
     *
     * @since 2.0.1
     */
    public $dbdata = [];

    /**
     * Current Elements multiple list data
     *
     * @since 2.0.1
     */
    public $child = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.1
     */
    public $CSSDATA = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.1
     */
    public $inline_css;

    /**
     * Current Elements Global JS Handle
     *
     * @since 2.0.1
     */
    public $JSHANDLE = 'oxi-accordions-ultimate';

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.1
     */
    public $WRAPPER;

    /**
     * Current Elements Admin Control
     *
     * @since 2.0.1
     */
    public $admin;

    /**
     * load constructor
     *
     * @since 2.0.1
     */

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;

    /**
     * Database Style Name
     *
     * @since 2.0.1
     */
    public $style_name;

    /**
     * Public Attribute
     *
     * @since 2.0.1
     */
    public $attribute;

    /**
     * Public Accordions Preloader
     *
     * @since 2.0.1
     */
    public $accordions_preloader;

    /**
     * Public Header Class
     *
     * @since 2.0.1
     */
    public $headerclass;

    /**
     * Define Accordions Type as Toggle or Accordions 
     *
     * @since 2.0.1
     */
    public $accordions_type;

    /**
     * Public arg
     *
     * @since 2.0.1
     */
    public $arg;

    /**
     * Public keys
     *
     * @since 2.0.1
     */
    public $keys;

    public function __construct(array $dbdata = [], array $child = [], $admin = 'user', array $arg = [], array $keys = []) {
        if (count($dbdata) > 0):
            $this->dbdata = $dbdata;
            $this->child = $child;
            $this->admin = $admin;
            $this->arg = $arg;
            $this->keys = $keys;
            $this->style_name = ucfirst($dbdata['style_name']);
            $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
            if (array_key_exists('id', $this->dbdata)):
                $this->oxiid = $this->dbdata['id'];
            else:
                $this->oxiid = rand(100000, 200000);
            endif;
            $this->loader();
        endif;
    }

    /**
     * Current element loader
     *
     * @since 2.0.1
     */
    public function loader() {
        $this->style = json_decode(stripslashes($this->dbdata['rawdata']), true);
        $this->CSSDATA = $this->dbdata['stylesheet'];
        $this->WRAPPER = 'oxi-accordions-wrapper-' . $this->dbdata['id'];
        $this->hooks();
    }

    /**
     * load css and js hooks
     *
     * @since 2.0.1
     */
    public function hooks() {
        $this->public_jquery();
        $this->public_css();
        $this->public_frontend_loader();
        $this->render();
        $inlinecss = $this->inline_public_css() . $this->inline_css . (array_key_exists('oxi-accordions-custom-css', $this->style) ? $this->style['oxi-accordions-custom-css'] : '');
        $inlinejs = $this->inline_public_jquery();
        if ($this->CSSDATA == '' && $this->admin == 'admin') {
            $cls = '\OXI_ACCORDIONS_PLUGINS\Layouts\Admin\\' . str_replace('-', '_', $this->style_name);
            $CLASS = new $cls('admin');
            $inlinecss .= $CLASS->inline_template_css_render($this->style);
        } else {
            echo $this->font_familly_validation(json_decode(($this->dbdata['font_family'] != '' ? $this->dbdata['font_family'] : "[]"), true));
            $inlinecss .= $this->CSSDATA;
        }
        if ($inlinejs != ''):
            if ($this->admin == 'admin'):
                echo _('<script>
                        (function ($) {
                            setTimeout(function () {');
                echo $inlinejs;
                echo _('    }, 2000);
                        })(jQuery)</script>');
            else:
                $jquery = '(function ($) {' . $inlinejs . '})(jQuery);';
                wp_add_inline_script($this->JSHANDLE, $jquery);
            endif;
        endif;
        if ($inlinecss != ''):
            $inlinecss = html_entity_decode($inlinecss);
            if ($this->admin == 'admin'):
                //only load while ajax called
                echo _('<style>');
                echo $inlinecss;
                echo _('</style>');
            else:
                wp_add_inline_style('oxi-accordions-ultimate', $inlinecss);
            endif;
        endif;
    }

    /**
     * front end loader css and js
     *
     * @since 2.0.1
     */
    public function public_frontend_loader() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxi-accordions-ultimate', OXI_ACCORDIONS_URL . 'assets/frontend/css/style.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_style('oxi-plugin-animate', OXI_ACCORDIONS_URL . 'assets/frontend/css/animate.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_style('oxi-accordions-' . strtolower(str_replace('_', '-', $this->style_name)), OXI_ACCORDIONS_URL . 'assets/frontend/css/' . strtolower(str_replace('_', '-', $this->style_name)) . '.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_script('oxi-accordions-collapse.min', OXI_ACCORDIONS_URL . 'assets/frontend/js/collapse.js', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_script('oxi-accordions-ultimate', OXI_ACCORDIONS_URL . 'assets/frontend/js/accordions.js', false, OXI_ACCORDIONS_PLUGIN_VERSION);
    }

    /**
     * load current element render since 2.0.1
     *
     * @since 2.0.1
     */
    public function render() {

        echo '<div class="oxi-addons-container oxi-accordions-wrapper ' . $this->WRAPPER . '" id="' . $this->WRAPPER . '">
                 <div class="oxi-addons-row">';
        if ($this->admin == 'admin'):
            echo '<input type="hidden" id="oxi-addons-iframe-background-color" name="oxi-addons-iframe-background-color" value="' . (is_array($this->style) ? array_key_exists('oxilab-preview-color', $this->style) ? $this->style['oxilab-preview-color'] : '#FFF' : '#FFF') . '">';
        endif;
        $this->default_render($this->style, $this->child, $this->admin);
        echo '   </div>
              </div>';
    }

    /**
     * load current element render since 2.0.1
     *
     * @since 2.0.1
     */
    public function public_attribute() {
        $this->style;
        $data = ' data-oxi-trigger="' . (array_key_exists('oxi-accordions-trigger', $this->style) ? $this->style['oxi-accordions-trigger'] . '' : 'click') . '" ';
        $data .= 'data-oxi-accordions-type="' . (array_key_exists('oxi-accordions-type', $this->style) ? '' . $this->style['oxi-accordions-type'] . '' : 'toggle') . '" ';
        $data .= 'data-oxi-auto-play="' . (array_key_exists('oxi-accordions-auto-play-duration-size', $this->style) ? '' . $this->style['oxi-accordions-auto-play-duration-size'] . '' : '3000') . '" ';

        $this->headerclass = ( $this->style['oxi-accordions-head-expand-collapse-location'] != false ? $this->style['oxi-accordions-head-expand-collapse-location'] : '') . ' '
                . ( $this->style['oxi-accordions-head-aditional-location'] != false ? $this->style['oxi-accordions-head-aditional-location'] : '');

        $this->accordions_preloader = isset($this->style['oxi-accordions-preloader']) && $this->style['oxi-accordions-preloader'] == 'yes' ? 'style="opacity:0"' : '';
        $this->accordions_type = isset($this->style['oxi-accordions-type']) && $this->style['oxi-accordions-type'] == 'accordions' ? 'data-parent="#' . $this->WRAPPER . '"' : '';

        if (isset($this->style['oxi-accordions-content-type']) && $this->style['oxi-accordions-content-type'] === 'post'):
            $this->post_query();
        endif;
        return $data;
    }

    /**
     * load public jquery
     *
     * @since 2.0.1
     */
    public function public_jquery() {
        echo '';
    }

    /**
     * load public css
     *
     * @since 2.0.1
     */
    public function public_css() {
        echo '';
    }

    /**
     * load inline public jquery
     *
     * @since 2.0.1
     */
    public function inline_public_jquery() {
        echo '';
    }

    /**
     * load inline public css
     *
     * @since 2.0.1
     */
    public function inline_public_css() {
        echo '';
    }

    /**
     * load default render
     *
     * @since 2.0.1
     */
    public function default_render($style, $child, $admin) {
        echo '';
    }

    /**
     * load default render
     *
     * @since 2.0.1
     */
    public function Json_Decode($rawdata) {
        return $rawdata != '' ? json_decode(stripcslashes($rawdata), true) : [];
    }

    public function font_familly_validation($data = []) {
        $api = get_option('oxi_addons_google_font');
        if ($api == 'no'):
            return;
        endif;
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }

    public function array_render($id, $style) {
        if (array_key_exists($id, $style)):
            return $style[$id];
        endif;
    }

    public function text_render($data) {
        return do_shortcode(str_replace('spTac', '&nbsp;', str_replace('spBac', '<br>', html_entity_decode($data))), $ignore_html = false);
    }

    public function url_render($id, $style) {
        $link = [];
        if (array_key_exists($id . '-url', $style) && $style[$id . '-url'] != ''):
            $link['url'] = $style[$id . '-url'];
            if (array_key_exists($id . '-target', $style) && $style[$id . '-target'] != '0'):
                $link['target'] = $style[$id . '-target'];
            else:
                $link['target'] = '';
            endif;
        endif;
        return $link;
    }

    public function media_render($id, $style) {
        $url = '';
        if (array_key_exists($id . '-select', $style)):
            if ($style[$id . '-select'] == 'media-library'):
                $url = $style[$id . '-image'];
            else:
                $url = $style[$id . '-url'];
            endif;
            if (array_key_exists($id . '-image-alt', $style) && $style[$id . '-image-alt'] != ''):
                $r = 'src="' . $url . '" alt="' . $style[$id . '-image-alt'] . '" ';
            else:
                $r = 'src="' . $url . '" ';
            endif;
            return $r;
        endif;
    }

    public function excerpt($limit = 10) {
        $limit++;
        $excerpt = explode(' ', get_the_excerpt(), $limit);
        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
        return $excerpt;
    }

    public function post_title($limit = 10) {
        $limit++;
        $title = explode(' ', get_the_title(), $limit);
        if (count($title) >= $limit) {
            array_pop($title);
            $title = implode(" ", $title) . '...';
        } else {
            $title = implode(" ", $title);
        }
        return $title;
    }

    public function truncate($str, $length = 24) {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length) . '...';
        } else {
            return $str;
        }
    }

    public function accordions_url_render($style) {
        if (isset($style['oxi-accordions-modal-components-type']) && $style['oxi-accordions-modal-components-type'] == 'link'):
            $data = $this->url_render('oxi-accordions-modal-link', $style);
            if (count($data) >= 1):
                echo $data;
                return ' data-link=\'' . json_encode($data) . '\'';
            endif;
        endif;
    }

    public function default_open($value) {
        if (isset($value['oxi-accordions-modal-default']) && $value['oxi-accordions-modal-default'] == 'yes'):
            return 'show';
        endif;
    }

    public function accordions_content_render_tag($style, $child) {

        $number = array_key_exists('oxi-accordions-desc-tags-max', $style) ? $style['oxi-accordions-desc-tags-max'] : 10;
        $smallest = array_key_exists('oxi-accordions-desc-tags-small', $style) ? $style['oxi-accordions-desc-tags-small'] : 10;
        $largest = array_key_exists('oxi-accordions-desc-tags-big', $style) ? $style['oxi-accordions-desc-tags-big'] : 10;
        $show_count = array_key_exists('oxi-accordions-desc-tags-show-count', $style) ? $style['oxi-accordions-desc-tags-show-count'] : 1;

        $tags = get_tags();
        $args = array(
            'smallest' => $smallest,
            'largest' => $largest,
            'unit' => 'px',
            'number' => $number,
            'format' => 'flat',
            'separator' => " ",
            'orderby' => 'count',
            'order' => 'DESC',
            'show_count' => $show_count,
            'echo' => false
        );
        return wp_generate_tag_cloud($tags, $args);
    }

    public function accordions_content_render_commment($style, $child) {
        $number = array_key_exists('oxi-accordions-desc-comment-max', $style) ? $style['oxi-accordions-desc-comment-max'] : 5;
        $show_avatar = array_key_exists('oxi-accordions-desc-comment-show-avatar', $style) ? $style['oxi-accordions-desc-comment-show-avatar'] : 1;
        $avatar_size = array_key_exists('oxi-accordions-desc-comment-avatar-size', $style) ? $style['oxi-accordions-desc-comment-avatar-size'] : 65;
        $comment_length = array_key_exists('oxi-accordions-desc-comment-comment-lenth', $style) ? $style['oxi-accordions-desc-comment-comment-lenth'] : 90;

        $recent_comments = get_comments(array(
            'number' => $number,
            'status' => 'approve',
            'post_status' => 'publish'
        ));
        $public = '';
        if ($recent_comments) : foreach ($recent_comments as $comment) :
                $public .= '<div class="oxi-accordions-comment">';
                if ($show_avatar) :
                    $public .= ' <div class="oxi-accordions-comment-avatar">
                                    <a href="' . get_comment_link($comment->comment_ID) . '">
                                        ' . get_avatar($comment->comment_author_email, $avatar_size) . '
                                    </a>
                                </div>';
                endif;
                $public .= '<div class="oxi-accordions-comment-body">
                                <div class=oxi-accordions-comment-meta">
                                    <a href="' . get_comment_link($comment->comment_ID) . '">
                                        <span class="oxi-accordions-comment-author">' . get_comment_author($comment->comment_ID) . ' </span> - <span class="oxi-accordions-comment-post">' . get_the_title($comment->comment_post_ID) . '</span>
                                    </a>
                                </div>
                                <div class="oxi-accordions-comment-content">
                                    ' . $this->truncate(strip_tags(apply_filters('get_comment_text', $comment->comment_content)), $comment_length) . '
                                </div>
                            </div>
                            </div>';
            endforeach;
        else :
            $public .= ' <div class="oxi-accordions-comment">
                            <div class="no-comments">No comments yet</div>
                        </div>';
        endif;
        return $public;
    }

    public function accordions_content_render_recent($style, $child) {
        $show_thumb = array_key_exists('oxi-accordions-desc-recent-thumb-condi', $style) ? $style['oxi-accordions-desc-recent-thumb-condi'] : 1;
        $thumb_size = array_key_exists('oxi-accordions-desc-recent-thumb', $style) ? $style['oxi-accordions-desc-recent-thumb'] : 65;
        $date = array_key_exists('oxi-accordions-desc-recent-meta-date', $style) ? $style['oxi-accordions-desc-recent-meta-date'] : 1;
        $comment = array_key_exists('oxi-accordions-desc-recent-meta-comment', $style) ? $style['oxi-accordions-desc-recent-meta-comment'] : 1;
        $content = array_key_exists('oxi-accordions-desc-recent-content-lenth', $style) ? $style['oxi-accordions-desc-recent-content-lenth'] : 90;
        $number = array_key_exists('oxi-accordions-desc-recent-post', $style) ? $style['oxi-accordions-desc-recent-post'] : 5;
        $public = '';

        $query = new \WP_Query('posts_per_page=' . $number);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $extra = '';
                if ($date):
                    $extra .= '    <div class="oxi-accordions-recent-date">
                                       ' . get_the_date('M d, Y') . '
                                    </div>';
                endif;

                if ($comment):
                    if (!empty($extra)):
                        $extra .= '&nbsp&bull;&nbsp';
                    endif;
                    $number = (int) get_comments_number($query->post->ID);
                    $extra .= '    <div class="oxi-accordions-recent-comment">
                                        ' . ($number > 1 ? $number . ' Comment' : ($number > 0 ? 'One Comment' : 'No Comment')) . '
                                    </div>';
                endif;
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_size);
                $public .= '<div class="oxi-accordions-recent-post">';
                if ($show_thumb) {
                    $image = isset($image_url[0]) && $image_url[0] != '' ? $image_url[0] : '';
                    $public .= '    <div class="oxi-accordions-recent-avatar">
                                        <a href="' . get_permalink($query->post->ID) . '">
                                           <img class="oxi-image" src="' . $image . '">
                                        </a>
                                    </div>';
                }
                $public .= '<div class="oxi-accordions-recent-body">
                                <div class="oxi-accordions-recent-meta">
                                    <a href="' . get_permalink($query->post->ID) . '">
                                        ' . get_the_title($query->post->ID) . '
                                    </a>
                                </div>
                                ' . (!empty($extra) ? '<div class="oxi-accordions-recent-postmeta">' . $extra . '</div>' : '') . '
                                <div class="oxi-accordions-recent-content">
                                    ' . $this->truncate(strip_tags(get_the_content()), $content) . '
                                </div>
                            </div>';
                $public .= '</div>';
                $extra = '';
            }
            wp_reset_postdata();
        }

        return $public;
    }

    public function accordions_content_render_popular($style, $child) {
        $show_thumb = array_key_exists('oxi-accordions-desc-popular-thumb-condi', $style) ? $style['oxi-accordions-desc-popular-thumb-condi'] : 1;
        $thumb_size = array_key_exists('oxi-accordions-desc-popular-thumb', $style) ? $style['oxi-accordions-desc-popular-thumb'] : 65;
        $date = array_key_exists('oxi-accordions-desc-popular-meta-date', $style) ? $style['oxi-accordions-desc-popular-meta-date'] : 1;
        $comment = array_key_exists('oxi-accordions-desc-popular-meta-comment', $style) ? $style['oxi-accordions-desc-popular-meta-comment'] : 1;
        $content = array_key_exists('oxi-accordions-desc-popular-content-lenth', $style) ? $style['oxi-accordions-desc-popular-content-lenth'] : 90;
        $number = array_key_exists('oxi-accordions-desc-popular-post', $style) ? $style['oxi-accordions-desc-popular-post'] : 5;
        $public = '';

        $query = new \WP_Query(
                array('ignore_sticky_posts' => 1,
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => '_oxi_post_view_count',
            'order' => 'desc')
        );
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $extra = '';
                if ($date):
                    $extra .= '    <div class="oxi-accordions-popular-date">
                                       ' . get_the_date('M d, Y') . '
                                    </div>';
                endif;

                if ($comment):
                    if (!empty($extra)):
                        $extra .= '&nbsp&bull;&nbsp';
                    endif;
                    $number = (int) get_comments_number($query->post->ID);
                    $extra .= '    <div class="oxi-accordions-popular-comment">
                                        ' . ($number > 1 ? $number . ' Comment' : ($number > 0 ? 'One Comment' : 'No Comment')) . '
                                    </div>';
                endif;
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_size);
                $public .= '<div class="oxi-accordions-popular-post">';
                if ($show_thumb) {
                    $image = isset($image_url[0]) && $image_url[0] != '' ? $image_url[0] : '';
                    $public .= '    <div class="oxi-accordions-popular-avatar">
                                        <a href="' . get_permalink($query->post->ID) . '">
                                           <img class="oxi-image" src="' . $image . '">
                                        </a>
                                    </div>';
                }
                $public .= '<div class="oxi-accordions-popular-body">
                                <div class="oxi-accordions-popular-meta">
                                    <a href="' . get_permalink($query->post->ID) . '">
                                        ' . get_the_title($query->post->ID) . '
                                    </a>
                                </div>
                                ' . (!empty($extra) ? '<div class="oxi-accordions-popular-postmeta">' . $extra . '</div>' : '') . '
                                <div class="oxi-accordions-popular-content">
                                    ' . $this->truncate(strip_tags(get_the_content()), $content) . '
                                </div>
                            </div>';
                $public .= '</div>';
                $extra = '';
            }
            wp_reset_postdata();
        }

        return $public;
    }

    public function accordions_content_render($style, $child) {
        if ($child['oxi-accordions-modal-components-type'] == 'popular-post'):
            return $this->accordions_content_render_popular($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'recent-post'):
            return $this->accordions_content_render_recent($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'recent-comment'):
            return $this->accordions_content_render_commment($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'tag'):
            return $this->accordions_content_render_tag($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'nested-tabs'):
            return $this->accordions_content_render_nested_tabs($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'nested-accordions'):
            return $this->accordions_content_render_nested_accordions($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'post'):
            return $this->accordions_content_render_post($style, $child);
        else:
            return $this->special_charecter($child['oxi-accordions-modal-desc']);
        endif;
    }

    public function accordions_content_render_post($style, $child) {
        $id = isset($child['oxi-accordions-modal-components-post']) ? $child['oxi-accordions-modal-components-post'] : false;
        if ($id != false):
            $content = apply_filters('the_content', get_post_field('post_content', $id));
            return $content;
        endif;
        return;
    }

    public function accordions_content_render_nested_tabs($style, $child) {
        $shortcode = array_key_exists('oxi-accordions-modal-nested-tabs', $child) ? $child['oxi-accordions-modal-nested-tabs'] : '';
        if ($shortcode > 0):
            ob_start();
            echo \OXI_TABS_PLUGINS\Classes\Bootstrap::instance()->shortcode_render($shortcode, 'user');
            return ob_get_clean();
        endif;
        return;
    }

    public function accordions_content_render_nested_accordions($style, $child) {
        $shortcode = array_key_exists('oxi-accordions-modal-nested-accordions', $child) ? $child['oxi-accordions-modal-nested-accordions'] : '';
        if ($shortcode > 0):
            ob_start();
            echo \OXI_TABS_PLUGINS\Classes\Bootstrap::instance()->shortcode_render($shortcode, 'user');
            return ob_get_clean();
        endif;
        return;
    }

    public function special_charecter($data) {
        $data = html_entity_decode($data);
        $data = str_replace("\'", "'", $data);
        $data = str_replace('\"', '"', $data);
        $data = do_shortcode($data, $ignore_html = false);
        return $data;
    }

    public function title_special_charecter($array, $title, $subtitle) {
        $r = '<div class=\'oxi-accordions-header-title\'>';
        $t = false;
        if (!empty($array[$title]) && $array[$title] != ''):
            $t = true;
            $r .= '<div class=\'oxi-accordions-main-title\'>' . $this->special_charecter($array[$title]) . '</div>';
        endif;
        if (!empty($array[$subtitle]) && $array[$subtitle] != ''):
            $t = true;
            $r .= '<div class=\'oxi-accordions-sub-title\'>' . $this->special_charecter($array[$subtitle]) . '</div>';
        endif;
        $r .= '</div>';
        if ($t):
            return $r;
        endif;
    }

    public function number_special_charecter($data) {
        if (!empty($data) && $data != ''):
            return '<div class=\'oxi-accordions-header-li-number\'>' . $this->special_charecter($data) . '</div>';
        endif;
    }

    public function font_awesome_render($data) {
        if (empty($data) || $data == ''):
            return;
        endif;
        $fadata = get_option('oxi_addons_font_awesome');
        if ($fadata == 'yes'):
            wp_enqueue_style('font-awsome.min', OXI_ACCORDIONS_URL . 'assets/frontend/css/font-awsome.min.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        endif;
        $files = '<i class="' . $data . ' oxi-icons"></i>';
        return $files;
    }

    public function image_special_render($id = '', $array = []) {
        $value = $this->media_render($id, $array);
        if (!empty($value)):
            return ' <img  class=\'oxi-accordions-header-li-image\' ' . $value . '>';
        endif;
    }

    public function icon_special_rander($id = '') {
        $value = $this->font_awesome_render($id);
        if (!empty($value)):
            return ' <div class="oxi-accordions-additional-icon"> ' . $value . '</div>';
        endif;
    }

    public function expand_collapse_icon_number_render($style = [], $number) {
        $data = '';
        if (isset($style['oxi-accordions-head-start-number'])):
            $data .= '<div class="oxi-accordions-expand-collapse-number">' . ($style['oxi-accordions-head-start-number'] + $number - 1) . '</div>';
        endif;
        if (isset($style['oxi-accordions-head-expand-icon']) && isset($style['oxi-accordions-head-collapse-icon'])):
            $data .= '<div class="oxi-accordions-expand-collapse-icon">
                        <div class="oxi-accordions-expand-icon">
                        ' . $this->font_awesome_render($style['oxi-accordions-head-expand-icon']) . '
                        </div>
                        <div class="oxi-accordions-collapse-icon">
                         ' . $this->font_awesome_render($style['oxi-accordions-head-collapse-icon']) . '
                        </div>
                    </div>';
        endif;
        return $data;
    }

    public function admin_edit_panel($id) {
        $data = '';
        if ($this->admin == 'admin'):
            $data = '   <div class="oxi-addons-admin-absulote">
                            <div class="oxi-addons-admin-absulate-edit">
                                <button class="btn btn-primary shortcode-addons-template-item-edit" type="button" value="' . $id . '">Edit</button>
                            </div>
                            <div class="oxi-addons-admin-absulate-delete">
                                <button class="btn btn-danger shortcode-addons-template-item-delete" type="submit" value="' . $id . '">Delete</button>
                            </div>
                        </div>';
        endif;
        return $data;
    }

    public function defualt_value($id) {
        return [
            'oxi-accordions-modal-default' => '',
            'oxi-accordions-modal-title' => '',
            'oxi-accordions-modal-sub-title' => '',
            'oxi-accordions-modal-title-additional' => '',
            'oxi-accordions-modal-icon' => '',
            'oxi-accordions-modal-number' => '',
            'oxi-accordions-modal-image-select' => 'media-library',
            'oxi-accordions-modal-image-image' => '',
            'oxi-accordions-modal-image-image-alt' => '',
            'oxi-accordions-modal-image-url' => '',
            'oxi-accordions-modal-components-type' => 'wysiwyg',
            'oxi-accordions-modal-link-url' => '',
            'oxi-accordions-modal-desc' => '',
            'shortcodeitemid' => $id,
            'oxi-accordions-modal-link-target' => '0',
            'oxi-accordions-modal-nested-tabs' => '',
            'oxi-accordions-modal-nested-accordions' => ''
        ];
    }

    public function post_query() {
        $style = $this->style;
        $args = [
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'post_type' => $style['display_post_post_type'],
            'orderby' => $style['display_post_orderby'],
            'order' => $style['display_post_ordertype'],
            'posts_per_page' => $style['display_post_per_page'],
            'offset' => $style['display_post_offset'],
            'tax_query' => [],
        ];
        if (!empty($style['display_post_author'])):
            $args['author__in'] = $style['display_post_author'];
        endif;

        $type = $style['display_post_post_type'];

        if (!empty($style[$type . '_exclude'])) {
            $args['post__not_in'] = $style[$type . '_exclude'];
        }
        if (!empty($style[$type . '_include'])) {
            $args['post__in'] = $style[$type . '_include'];
        }
        if ($type != 'page') :
            if (!empty($style[$type . '_category'])) :
                $args['tax_query'][] = [
                    'taxonomy' => $type == 'post' ? 'category' : $type . '_category',
                    'field' => 'term_id',
                    'terms' => $style[$type . '_category'],
                ];
            endif;
            if (!empty($style[$type . '_tag'])) :
                $args['tax_query'][] = [
                    'taxonomy' => $type . '_tag',
                    'field' => 'term_id',
                    'terms' => $style[$type . '_tag'],
                ];
            endif;
            if (!empty($args['tax_query'])) :
                $args['tax_query']['relation'] = 'OR';
            endif;
        endif;

        $query = new \WP_Query($args);
        $postdata = [];
        $i = 1;
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $data = $this->defualt_value($i);
                $query->the_post();
                $data['shortcodeitemid'] = $this->oxiid;
                $data['oxi-accordions-modal-title'] = get_the_title();
                $data['oxi-accordions-modal-components-type'] = 'post';
                $data['oxi-accordions-modal-components-post'] = get_the_ID();
                $postdata[$i] = [
                    'id' => '',
                    'styleid' => '',
                    'rawdata' => json_encode($data),
                ];
                $i++;
            }
        }
        $this->child = $postdata;
    }

}
