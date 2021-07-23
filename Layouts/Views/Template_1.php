<?php

namespace OXI_ACCORDIONS_PLUGINS\Layouts\Views;

/**
 * Description of Template_1
 *
 * @author biplo
 */
use OXI_ACCORDIONS_PLUGINS\Layouts\Template;

class Template_1 extends Template {

    public function default_render($style, $child, $admin) {
        echo '<div class="oxi-accordions-ultimate-style oxi-accordions-ultimate-template-1  oxi-accordions-clearfix oxi-accordions-preloader" data-oxi-accordions=\'' . json_encode($this->attribute) . '\' ' . $this->accordions_preloader . '>';
        $number = 1;

        foreach ($child as $key => $val) {
            $value = json_decode(stripslashes($val['rawdata']), true);
            if (!is_array($value)):

                $value = $this->defualt_value($val['id']);
            endif;
            echo '<div class="oxi-accordions-single-card oxi-accordions-single-card-' . $this->oxiid . ' ' . $this->headerclass . ' oxi-accordions-single-card-' . $this->oxiid . '-' . $number . '">';
            /*
             * Header Child Loop Start
             */

            echo '<div class=\'oxi-accordions-header-card\'>';
            echo '  <div class="oxi-accordions-header-body oxi-accordions-header oxi-accordions-clearfix" data-oxitoggle="oxicollapse" data-oxitarget="#oxi-accordions-content-' . $this->oxiid . '-' . $number . '" ' . $this->accordions_url_render($value) . ' ' . $this->default_open($value) . ' aria-expanded="false">';
            echo '      <div class="oxi-accordions-expand-collapse ' . $style['oxi-accordions-expand-collapse'] . ' ' . $style['oxi-accordions-head-expand-collapse-type'] . '">';
            echo $this->expand_collapse_icon_number_render($style, $number);
            echo '      </div>';
            echo '      <div class="oxi-accordions-header-content">';
            if ($value['oxi-accordions-modal-title-additional'] == 'icon'):
                echo $this->icon_special_rander($value['oxi-accordions-modal-icon']);
            elseif ($value['oxi-accordions-modal-title-additional'] == 'number'):
                echo $this->number_special_charecter($value['oxi-accordions-modal-number']);
            elseif ($value['oxi-accordions-modal-title-additional'] == 'image'):
                echo $this->image_special_render('oxi-accordions-modal-image', $value);
            endif;
            echo $this->title_special_charecter($value, 'oxi-accordions-modal-title', 'oxi-accordions-modal-sub-title');

            echo '      </div>';
            echo '  </div>';
            echo '</div>';

            /*
             * Content Child Loop Start
             */
            $content_height = isset($style['oxi-accordions-content-height']) ? $style['oxi-accordions-content-height'] : '';

            echo '  <div class="oxicollapse oxi-accordions-content-card oxi-accordions-content-card-' . $this->oxiid . '  ' . ($this->admin == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="oxi-accordions-content-' . $this->oxiid . '-' . $number . '">';
            echo '     <div class="oxi-accordions-content-body animate__animated ' . $content_height . '">';
            echo $this->accordions_content_render($style, $value);
            echo $this->admin_edit_panel($val['id']);
            echo '      </div>';
            echo '  </div>';

            echo '</div>';
            $number++;
        }

        echo '</div>';
    }

}
