<?php

/**
-------------------------------------------------------------------------
lovefactory - Love Factory 4.4.7
-------------------------------------------------------------------------
 * @author thePHPfactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thePHPfactory.com
 * Technical Support: Forum - http://www.thePHPfactory.com/forum/
-------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

?>

<script>
    Joomla.submitbutton = function (pressbutton) {
        if (undefined != jQuery('#lovefactory-googlemap').LoveFactoryGoogleMap) {
            var map = jQuery('#lovefactory-googlemap').LoveFactoryGoogleMap('map');

            if (map && undefined !== map.getCenter()) {
                $$("#gmaps_default_x").setProperty("value", map.getCenter().lat());
                $$("#gmaps_default_y").setProperty("value", map.getCenter().lng());
                $$("#gmaps_default_z").setProperty("value", map.getZoom());
            }
        }

        Joomla.submitform(pressbutton);
    }
</script>

<table class="paramlist admintable">
    <?php if (!function_exists('curl_init')): ?>
        <p style="color: #ff0000"><?php echo JText::_('SETTINGS_GOOGLE_MAPS_REQUIRES_CURL'); ?></p>
    <?php endif; ?>

    <!-- Enable Google Maps -->
    <?php echo JHtml::_('settings.boolean', 'enable_gmaps', 'SETTINGS_ENABLE_GOOGLE_MAPS', $this->settings->enable_gmaps); ?>

    <!-- Google Maps Api Key -->
    <tr class="gmaps_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="gmaps_api_key"><?php echo JText::_('SETTINGS_GOOGLE_MAPS_API_KEY'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key"><?php echo JText::_('SETTINGS_GOOGLE_MAPS_GET_KEY'); ?></a>
            <br/>
            <input type="text" name="gmaps_api_key" id="gmaps_api_key"
                   value="<?php echo $this->settings->gmaps_api_key; ?>" style="width: 250px;"/>
        </td>
    </tr>

    <!-- Distance_type -->
    <tr class="gmaps_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="distances_unit"><?php echo JText::_('SETTINGS_DISTANCES_UNIT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <select id="distances_unit" name="distances_unit">
                <option
                    value="0" <?php echo 0 == $this->settings->distances_unit ? 'selected' : ''; ?>><?php echo JText::_('SETTINGS_DISTANCES_KM'); ?></option>
                <option
                    value="1" <?php echo 1 == $this->settings->distances_unit ? 'selected' : ''; ?>><?php echo JText::_('SETTINGS_DISTANCES_MI'); ?></option>
            </select>
        </td>
    </tr>

    <!-- Enable Field location -->
    <tr class="gmaps_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="enable_fields_location"><?php echo JText::_('SETTINGS_USE_LOCATION_FIELDS'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <select id="enable_fields_location" name="fields_location">
                <option
                    value="0" <?php echo 0 == $this->settings->fields_location ? 'selected' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                <option
                    value="1" <?php echo 1 == $this->settings->fields_location ? 'selected' : ''; ?>><?php echo JText::_('JYES'); ?></option>
            </select>

            <div class="clr"></div>

            <table>
                <!-- location_field_gmap_field -->
                <tr class="fields_location_related">
                    <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label
                  for="location_field_gmap_field"><?php echo JText::_('SETTINGS_LOCATION_FIELD_GMAP_FIELD'); ?></label>
            </span>
                    </td>
                    <td class="paramlist_value">
                        <?php echo JHtml::_('select.genericlist', $this->gmaps_fields, 'location_field_gmap_field', '', 'value', 'text', $this->settings->location_field_gmap_field); ?>
                    </td>
                </tr>

                <!-- Location field City -->
                <tr class="fields_location_related">
                    <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="location_field_city"><?php echo JText::_('SETTINGS_LOCATION_FIELD_CITY'); ?></label>
            </span>
                    </td>
                    <td class="paramlist_value">
                        <?php echo JHtml::_('select.genericlist', $this->location_fields, 'location_field_city', '', 'value', 'text', $this->settings->location_field_city); ?>
                    </td>
                </tr>

                <!-- Location field State -->
                <tr class="fields_location_related">
                    <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="location_field_state"><?php echo JText::_('SETTINGS_LOCATION_FIELD_STATE'); ?></label>
            </span>
                    </td>
                    <td class="paramlist_value">
                        <?php echo JHtml::_('select.genericlist', $this->location_fields, 'location_field_state', '', 'value', 'text', $this->settings->location_field_state); ?>
                    </td>
                </tr>

                <!-- Location field Country -->
                <tr class="fields_location_related">
                    <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="location_field_country"><?php echo JText::_('SETTINGS_LOCATION_FIELD_COUNTRY'); ?></label>
            </span>
                    </td>
                    <td class="paramlist_value">
                        <?php echo JHtml::_('select.genericlist', $this->location_fields, 'location_field_country', '', 'value', 'text', $this->settings->location_field_country); ?>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <!-- Update Field location -->
    <tr class="gmaps_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="update_fields_location"><?php echo JText::_('SETTINGS_UPDATE_FIELDS_LOCATION_SAVE_PROFILE'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <select id="update_fields_location" name="update_fields_location">
                <option
                    value="0" <?php echo !$this->settings->update_fields_location ? 'selected' : ''; ?>><?php echo JText::_('SETTINGS_UPDATE_FIELDS_LOCATION_SAVE_PROFILE_ALWAYS'); ?></option>
                <option
                    value="1" <?php echo $this->settings->update_fields_location ? 'selected' : ''; ?>><?php echo JText::_('SETTINGS_UDAPTE_FIELDS_LOCATION_SAVE_PROFILE_ON_CHANGE'); ?></option>
            </select>
        </td>
    </tr>

    <!-- Google Maps starting location -->
    <tr class="gmaps_related">
        <td width="40%" class="paramlist_key hasTip"
            title="<?php echo JText::_('SETTINGS_GOOGLE_MAPS_DEFAULT_LOCATION'); ?>::<?php echo JText::_('SETTINGS_GOOGLE_MAPS_DEFAULT_LOCATION_TIP'); ?>">
      <span class="editlinktip">
        <label><?php echo JText::_('SETTINGS_GOOGLE_MAPS_DEFAULT_LOCATION'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <!--      --><?php //if ($this->settings->enable_gmaps && !empty($this->settings->gmaps_api_key)): ?>
            <?php $googleMaps = LoveFactoryGoogleMaps::getInstance($this->settings->gmaps_api_key); ?>
            <?php echo $googleMaps->renderMap('lovefactory-googlemap', array('lat' => $this->settings->gmaps_default_x, 'lng' => $this->settings->gmaps_default_y, 'zoom' => $this->settings->gmaps_default_z), 200, '100%', array('initOnTabOpen' => 'integrations')); ?>
            <input type="hidden" id="gmaps_default_x" name="gmaps_default_x"
                   value="<?php echo $this->settings->gmaps_default_x; ?>"/>
            <input type="hidden" id="gmaps_default_y" name="gmaps_default_y"
                   value="<?php echo $this->settings->gmaps_default_y; ?>"/>
            <input type="hidden" id="gmaps_default_z" name="gmaps_default_z"
                   value="<?php echo $this->settings->gmaps_default_z; ?>"/>
            <!--      --><?php //else: ?>
            <!--        --><?php //echo JText::_('SETTINGS_GOOGLE_MAPS_INFO'); ?>
            <!--      --><?php //endif; ?>
        </td>
    </tr>

</table>
