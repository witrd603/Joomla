<?php

defined('_JEXEC') or die('Restricted access');

class LovefactorySettings
{
  var $require_fillin                           = 0;
  var $currency_symbol                          = 1;
  var $enable_youtube_integration               = 0;
  var $youtube_api_key                          = '';
  var $create_profile_admin_groups              = array();
  var $registration_membership                  = 0;
  var $restrict_default_membership              = 0;
  var $opposite_gender_search                   = 0;
  var $opposite_gender_display                  = 0;
  var $bootstrap_template                       = 1;
  var $display_user_name                        = array('','');
  var $members_map_show_profile_event           = "mouseover";
  var $members_map_grouped_members_display      = "map";
  var $remove_ratings_on_profile_remove         = 1;
  var $friendship_requests_limit                = 5;
  var $friendship_request_message               = 0;
  var $location_field_gmap_field                = 43;
  var $registration_fields_mapping_username     = 3;
  var $registration_fields_mapping_email        = 12;
  var $registration_fields_mapping_password     = 13;
  var $registration_fields_mapping_name         = 16;
  var $invoice_vat_rate                         = 0;
  var $photo_max_width                          = 600;
  var $photo_max_height                         = 800;
  var $photos_storage_mode                      = 1;
  var $photos_max_size                          = 1;
  var $thumbnail_max_height                     = 100;
  var $thumbnail_max_width                      = 100;
  var $enable_comments                          = 1;
  var $enable_messages                          = 1;
  var $enable_rating                            = 1;
  var $currency                                 = "EUR";
  var $enable_wallpage                          = 1;
  var $wallpage_entries                         = 5;
  var $my_gallery_action_links                  = 1;
  var $show_translation_fields                  = 0;
  var $enable_swfupload_debug                   = 0;
  var $enable_classic_uploader                  = 1;
  var $enable_friends                           = 1;
  var $search_jump_to_results                   = 0;
  var $enable_default_infobar                   = 0;
  var $approval_photos                          = 0;
  var $approval_videos                          = 0;
  var $approval_comments                        = 0;
  var $approval_comments_photo                  = 0;
  var $approval_comments_video                  = 0;
  var $approval_messages                        = 0;
  var $approval_groups                          = 0;
  var $approval_group_threads                   = 0;
  var $approval_groups_posts                    = 0;
  var $approval_profile                         = 0;
  var $enable_invoices                          = 0;
  var $invoice_template                         = "<table class=\"no_border\" style=\"width: 100%;\" border=\"0\">
<tbody>
<tr>
<td class=\"no_padding\" style=\"width: 250px; vertical-align: top;\">
<table style=\"width: 100%;\" border=\"0\">
<tbody>
<tr>
<td class=\"billing_information\" valign=\"top\">Seller Information</td>
</tr>
<tr>
<td class=\"header\">Contact Details</td>
</tr>
<tr>
<td>
<p>%%seller_information%%</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style=\"text-align: center;\" valign=\"bottom\">
<p><span style=\"font-size: xx-large;\"><strong>Invoice</strong></span></p>
<p> </p>
<p>Number: <strong>%%invoice_number%%</strong></p>
<p>Date: <strong>%%invoice_date%%</strong></p>
</td>
<td class=\"no_padding\" style=\"width: 250px; vertical-align: top;\">
<table style=\"width: 100%;\" border=\"0\">
<tbody>
<tr>
<td class=\"billing_information\">Buyer Information</td>
</tr>
<tr>
<td class=\"header\">Contact Details</td>
</tr>
<tr>
<td>%%buyer_information%%</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<p> </p>
<table style=\"width: 100%;\" border=\"0\">
<tbody>
<tr>
<td class=\"billing_information\" colspan=\"3\">Billing Information</td>
</tr>
<tr>
<td class=\"header\">Membership</td>
<td class=\"header\"> </td>
<td class=\"header\" style=\"width: 100px;\">Price</td>
</tr>
<tr>
<td style=\"text-align: left;\">%%membership_title%%</td>
<td style=\"text-align: right;\"> </td>
<td style=\"text-align: left;\">%%membership_price%%</td>
</tr>
<tr>
<td> </td>
<td style=\"text-align: right;\"><strong>VAT</strong></td>
<td style=\"text-align: left;\">%%vat%%</td>
</tr>
<tr>
<td> </td>
<td style=\"text-align: right;\"><strong>TOTAL</strong></td>
<td style=\"text-align: left;\">%%total%%</td>
</tr>
</tbody>
</table>
<p style=\"text-align: center;\"> </p>
<p style=\"text-align: center;\"><strong>www.yoursite.com</strong></p>
<p style=\"text-align: center;\">Lorem ipsum dolor sit amet.<strong> </strong>Suspendisse potenti. Phasellus volutpat.</p>";
  var $invoice_template_seller                  = "<p>Company Details</p>
<p>Full Address</p>
<p>Telephone</p>
<p>Email</p>";
  var $invoice_template_buyer                   = "";
  var $enable_infobar                           = 1;
  var $infobar_location                         = 1;
  var $infobar_refresh_interval                 = 30;
  var $enable_infobar_interactions              = 1;
  var $infobar_interactions_itemid              = 0;
  var $enable_infobar_messages                  = 1;
  var $infobar_messages_itemid                  = 0;
  var $enable_infobar_requests                  = 1;
  var $infobar_requests_itemid                  = 0;
  var $enable_infobar_comments                  = 1;
  var $infobar_comments_itemid                  = 0;
  var $enable_infobar_view_profile              = 1;
  var $infobar_view_profile_itemid              = 0;
  var $enable_infobar_update_profile            = 1;
  var $infobar_update_profile_itemid            = 0;
  var $enable_infobar_gallery                   = 1;
  var $infobar_gallery_itemid                   = 0;
  var $enable_infobar_friends                   = 1;
  var $infobar_friends_itemid                   = 0;
  var $enable_infobar_close                     = 1;
  var $enable_infobar_logout                    = 1;
  var $infobar_show_labels                      = 0;
  var $cron_job_profile_visitors                = 7;
  var $jump_to_results                          = 1;
  var $sort_by_membership                       = 1;
  var $results_columns                          = 1;
  var $display_hidden                           = 0;
  var $gender_pricing                           = 0;
  var $gender_change                            = 1;
  var $profile_status_change                    = 1;
  var $fields_location                          = 0;
  var $update_fields_location                   = 0;
  var $enable_relationships                     = 1;
  var $invalid_membership_action                = 0;
  var $enable_top_friends                       = 1;
  var $results_default_sort_order               = 1;
  var $results_default_sort_by                  = 1;
  var $profile_link_new_window                  = 0;
  var $enable_groups                            = 1;
  var $groups_allow_users_create                = 1;
  var $groups_post_allowed_html                 = "a,b,i,u";
  var $groups_photo_max_width                   = 120;
  var $groups_photo_max_height                  = 200;
  var $groups_list_limit                        = 10;
  var $group_posts_list_limit                   = 10;
  var $members_map_profile_new_link             = 0;
  var $search_radius_profile_new_link           = 0;
  var $enable_token_auth                        = 0;
  var $delete_user_plugin                       = 1;
  var $admin_comments_delete                    = 1;
  var $user_comments_delete                     = 1;
  var $enable_banned_words_filter               = 0;
  var $date_format                              = "ago";
  var $date_custom_format                       = "d/m/Y H:i";
  var $enable_shoutbox                          = 1;
  var $shoutbox_refresh_interval                = 5;
  var $shoutbox_messages                        = 20;
  var $cron_job_shoutbox_messages               = 10;
  var $shoutbox_log                             = 0;
  var $user_delete_comments                     = 1;
  var $user_delete_photo_comments               = 1;
  var $user_delete_video_comments               = 1;
  var $user_delete_profile_visits               = 1;
  var $user_delete_ratings                      = 1;
  var $user_delete_shoutbox                     = 1;
  var $user_delete_interactions                 = 1;
  var $user_delete_payments                     = 1;
  var $user_delete_actions                      = 1;
  var $user_delete_created_groups               = 1;
  var $user_delete_posts_in_groups              = 1;
  var $enable_gravatar_integration              = 0;
  var $enable_chatfactory_integration           = 0;
  var $chatfactory_integration_users_list       = 1;
  var $chatfactory_integration_delete_user      = 0;
  var $enable_blogfactory_integration           = 0;
  var $enable_gmaps                             = 0;
  var $gmaps_api_key                            = "";
  var $gmaps_default_x                          = "0";
  var $gmaps_default_y                          = "0";
  var $gmaps_default_z                          = 0;
  var $location_field_city                      = 0;
  var $location_field_country                   = 0;
  var $location_field_state                     = 0;
  var $videos_pagination_limit                  = 18;
  var $videos_list_pagination_limit             = 10;
  var $videos_comments_pagination_limit         = 10;
  var $videos_embed_allowed_html                = "object,embed,param,iframe";
  var $search_radius_group_users                = 1;
  var $search_radius_group_zoom                 = 10;
  var $search_radius_default_membership_show    = 1;
  var $search_default_membership_show           = 1;
  var $recaptcha_public_key                     = "";
  var $recaptcha_private_key                    = "";
  var $enable_recaptcha                         = 0;
  var $enable_profile_friends                   = 1;
  var $profile_friends_number                   = 5;
  var $profile_friends_sort                     = 0;
  var $profile_friends_top                      = 1;
  var $limit_search_results                     = 0;
  var $distances_unit                           = 0;
  var $max_search_radius                        = 1000;
  var $enable_search_radius                     = 1;
  var $enable_search_radius_sex_filter          = 1;
  var $allow_guest_search_radius                = 0;
  var $enable_members_map                       = 1;
  var $allow_guest_members_map                  = 1;
  var $members_map_group_users                  = 1;
  var $members_map_group_zoom                   = 10;
  var $members_map_default_membership_show      = 1;
  var $members_map_gmap_field                   = 43;
  var $search_radius_gmap_field                 = 43;
  var $enable_status                            = 1;
  var $status_max_length                        = 255;
  var $registration_mode                        = 2;
  var $enable_profile_fillin                    = 1;
  var $registration_email_notifications         = 0;
  var $registration_login_redirect              = 0;
  var $hide_banned_profiles                     = 1;
  var $hide_ignored_profiles                    = 1;
  var $default_photo_extension                  = "";
  var $enable_user_email_notify                 = 1;
  var $enable_smarty                            = 0;
  var $number_search_results_per_page           = 10;
  var $allow_guests_view_profile                = 1;
  var $end_membership_notification              = 1;
  var $cron_password                            = "cronjobpassword123";
  var $end_membership_notify_interval           = "10";
  var $enable_interactions                      = 1;
  var $enable_interaction_kiss                  = 1;
  var $enable_interaction_wink                  = 1;
  var $enable_interaction_hug                   = 1;
  var $wallpage_add_status                      = 1;
  var $wallpage_add_photo                       = 1;
  var $wallpage_add_rating                      = 1;
  var $wallpage_add_comment                     = 1;
  var $wallpage_add_photo_comment               = 1;
  var $wallpage_add_video_comment               = 1;
  var $wallpage_add_video                       = 1;
  var $wallpage_add_friend                      = 1;
  var $wallpage_add_relationship                = 1;
  var $wallpage_create_group                    = 1;
  var $wallpage_join_group                      = 1;
  var $html_notifications                       = 0;
  var $notification_new_comment_enabled         = 1;
  var $notification_new_photo_comment_enabled   = 1;
  var $notification_change_membership_enabled   = 1;
  var $notification_change_membership_receivers = array();
  var $notification_new_interaction_enabled     = 1;
  var $notification_new_message_enabled         = 1;
  var $notification_new_rating_enabled          = 1;
  var $cron_job_wallpage_entries_interval       = 2;
  var $default_membership_access                = array();
  var $profile_fillin_reminder_enable           = 0;
  var $profile_fillin_reminder_interval         = 30;
  var $enable_rating_update                     = 0;
}
