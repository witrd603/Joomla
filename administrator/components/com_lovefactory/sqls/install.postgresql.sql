DROP TABLE IF EXISTS "#__lovefactory_activity";
CREATE TABLE "#__lovefactory_activity" (
  "id" serial NOT NULL,
  "event" varchar(50) NOT NULL DEFAULT 0,
  "sender_id" bigint DEFAULT 0 NOT NULL,
  "receiver_id" bigint NOT NULL DEFAULT 0,
  "item_id" bigint NOT NULL DEFAULT 0,
  "params" text,
  "deleted_by_sender" smallint NOT NULL DEFAULT 0,
  "deleted_by_receiver" smallint NOT NULL DEFAULT 0,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_admin_profile_tokens";
CREATE TABLE "#__lovefactory_admin_profile_tokens" (
  "id" serial NOT NULL,
  "admin_id" bigint NOT NULL,
  "user_id" bigint NOT NULL,
  "token" varchar(50) NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "updated_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_adsense";
CREATE TABLE "#__lovefactory_adsense" (
  "id" serial NOT NULL,
  "title" varchar(255) NOT NULL,
  "script" text NOT NULL,
  "rows" smallint NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_approvals";
CREATE TABLE "#__lovefactory_approvals" (
  "id" serial NOT NULL,
  "type" varchar(20) NOT NULL,
  "item_id" bigint NOT NULL,
  "user_id" bigint NOT NULL,
  "message" text NOT NULL,
  "approved" smallint NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_approvals_idx_type" on "#__lovefactory_approvals" ("type");
CREATE INDEX "#__lovefactory_approvals_idx_item_id" on "#__lovefactory_approvals" ("item_id");
CREATE INDEX "#__lovefactory_approvals_idx_user_id" on "#__lovefactory_approvals" ("user_id");

DROP TABLE IF EXISTS "#__lovefactory_blacklist";
CREATE TABLE "#__lovefactory_blacklist" (
  "id" serial NOT NULL,
  "sender_id" bigint NOT NULL,
  "receiver_id" bigint NOT NULL,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_blacklist_idx_sender_id" on "#__lovefactory_blacklist" ("sender_id");
CREATE INDEX "#__lovefactory_blacklist_idx_receiver_id" on "#__lovefactory_blacklist" ("receiver_id");

DROP TABLE IF EXISTS "#__lovefactory_fields";
CREATE TABLE "#__lovefactory_fields" (
  "id" serial NOT NULL,
  "title" varchar(255) NOT NULL,
  "type" varchar(100) NOT NULL,
  "params" text,
  "descriptions" text,
  "css" text,
  "labels" text,
  "required" smallint NOT NULL DEFAULT 0,
  "searchable" smallint NOT NULL DEFAULT 0,
  "visibility" smallint NOT NULL DEFAULT 0,
  "user_visibility" smallint NOT NULL DEFAULT 0,
  "admin_only_viewable" smallint NOT NULL DEFAULT 0,
  "admin_only_editable" smallint NOT NULL DEFAULT 0,
  "lock_after_save" smallint NOT NULL DEFAULT 0,
  "published" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_friends";
CREATE TABLE "#__lovefactory_friends" (
  "id" serial NOT NULL,
  "type" smallint DEFAULT 0 NOT NULL,
  "sender_id" bigint NOT NULL,
  "receiver_id" bigint NOT NULL,
  "date" date NOT NULL,
  "message" text,
  "sender_status" smallint NOT NULL DEFAULT 0,
  "receiver_status" smallint NOT NULL DEFAULT 0,
  "pending" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_friends_idx_sender_id" on "#__lovefactory_friends" ("sender_id");
CREATE INDEX "#__lovefactory_friends_idx_receiver_id" on "#__lovefactory_friends" ("receiver_id");

DROP TABLE IF EXISTS "#__lovefactory_friends_requests";
CREATE TABLE "#__lovefactory_friends" (
  "sender_id" bigint NOT NULL,
  "receiver_id" bigint NOT NULL,
  "created_at" date NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_friends_idx_sender_id" on "#__lovefactory_friends" ("sender_id");
CREATE INDEX "#__lovefactory_friends_idx_receiver_id" on "#__lovefactory_friends" ("receiver_id");
CREATE INDEX "#__lovefactory_friends_idx_created_at" on "#__lovefactory_friends" ("created_at");

DROP TABLE IF EXISTS "#__lovefactory_gateways";
CREATE TABLE "#__lovefactory_gateways" (
  "id" serial NOT NULL,
  "element" varchar(20) DEFAULT NULL,
  "title" varchar(255) DEFAULT NULL,
  "published" smallint DEFAULT 0 NOT NULL ,
  "logo" varchar(255) DEFAULT NULL,
  "ordering" bigint DEFAULT 0 NOT NULL,
  "params" text,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_group_bans";
CREATE TABLE "#__lovefactory_group_bans" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL,
  "group_id" bigint NOT NULL,
  "description" text,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_group_bans_idx_user_id" on "#__lovefactory_group_bans" ("user_id");
CREATE INDEX "#__lovefactory_group_bans_idx_group_id" on "#__lovefactory_group_bans" ("group_id");

DROP TABLE IF EXISTS "#__lovefactory_group_members";
CREATE TABLE "#__lovefactory_group_members" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL,
  "group_id" bigint NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_group_members_idx_user_id" on "#__lovefactory_group_members" ("user_id");
CREATE INDEX "#__lovefactory_group_members_idx_group_id" on "#__lovefactory_group_members" ("group_id");

DROP TABLE IF EXISTS "#__lovefactory_group_posts";
CREATE TABLE "#__lovefactory_group_posts" (
  "id" serial NOT NULL,
  "group_id" bigint NOT NULL,
  "thread_id" bigint NOT NULL,
  "user_id" bigint NOT NULL,
  "text" text NOT NULL,
  "reported" smallint DEFAULT 0 NOT NULL,
  "approved" smallint DEFAULT 0 NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_group_posts_idx_user_id" on "#__lovefactory_group_posts" ("user_id");
CREATE INDEX "#__lovefactory_group_posts_idx_group_id" on "#__lovefactory_group_posts" ("group_id");

DROP TABLE IF EXISTS "#__lovefactory_group_threads";
CREATE TABLE "#__lovefactory_group_threads" (
  "id" serial NOT NULL,
  "group_id" bigint NOT NULL,
  "user_id" bigint NOT NULL,
  "title" varchar(255) NOT NULL,
  "text" text NOT NULL,
  "approved" smallint NOT NULL DEFAULT 0,
  "reported" smallint NOT NULL DEFAULT 0,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_groups";
CREATE TABLE "#__lovefactory_groups" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL,
  "private" smallint DEFAULT 0 NOT NULL,
  "title" varchar(255) NOT NULL,
  "description" text,
  "thumbnail" varchar(255),
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "approved" smallint NOT NULL DEFAULT 0,
  "reported" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_groups_idx_user_id" on "#__lovefactory_groups" ("user_id");

DROP TABLE IF EXISTS "#__lovefactory_interactions";
CREATE TABLE "#__lovefactory_interactions" (
  "id" serial NOT NULL,
  "sender_id" bigint NOT NULL,
  "receiver_id" bigint NOT NULL,
  "type_id" smallint NOT NULL,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "seen" smallint NOT NULL DEFAULT 0,
  "responded" smallint NOT NULL DEFAULT 0,
  "deleted_by_sender" smallint NOT NULL DEFAULT 0,
  "deleted_by_receiver" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_interactions_idx_sender_id" on "#__lovefactory_interactions" ("sender_id");
CREATE INDEX "#__lovefactory_interactions_idx_receiver_id" on "#__lovefactory_interactions" ("receiver_id");

DROP TABLE IF EXISTS "#__lovefactory_invoices";
CREATE TABLE "#__lovefactory_invoices" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL,
  "seller" text NOT NULL,
  "buyer" text NOT NULL,
  "membership" varchar(255) NOT NULL,
  "price" decimal(10,2) NOT NULL,
  "currency" varchar(10) NOT NULL,
  "vat_rate" decimal(10,2) NOT NULL,
  "vat_value" decimal(10,2) NOT NULL,
  "total" decimal(10,2) NOT NULL,
  "issued_at" bigint NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_ips";
CREATE TABLE "#__lovefactory_ips" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL,
  "ip" varchar(15) NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "updated_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "visits" bigint NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_ips_idx_user_id" on "#__lovefactory_ips" ("user_id");

DROP TABLE IF EXISTS "#__lovefactory_item_comments";
CREATE TABLE "#__lovefactory_item_comments" (
  "id" serial NOT NULL,
  "item_type" varchar(20) NOT NULL,
  "item_id" bigint NOT NULL DEFAULT 0,
  "item_user_id" bigint NOT NULL DEFAULT 0,
  "user_id" bigint NOT NULL DEFAULT 0,
  "message" text NOT NULL,
  "approved" smallint NOT NULL DEFAULT 0,
  "reported" smallint NOT NULL DEFAULT 0,
  "read" smallint NOT NULL DEFAULT 0,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_item_comments_idx_asset_type" on "#__lovefactory_item_comments" ("item_type");
CREATE INDEX "#__lovefactory_item_comments_idx_asset_id" on "#__lovefactory_item_comments" ("item_id");
CREATE INDEX "#__lovefactory_item_comments_idx_user_id" on "#__lovefactory_item_comments" ("user_id");
CREATE INDEX "#__lovefactory_item_comments_idx_item_user_id" on "#__lovefactory_item_comments" ("item_user_id");

DROP TABLE IF EXISTS "#__lovefactory_memberships";
CREATE TABLE "#__lovefactory_memberships" (
  "id" serial NOT NULL,
  "title" varchar(255) NOT NULL,
  "published" smallint NOT NULL DEFAULT 0,
  "ordering" bigint NOT NULL DEFAULT 0,
  "default" smallint NOT NULL DEFAULT 0,
  "icon_extension" varchar(20),
  "max_photos" bigint NOT NULL DEFAULT 0,
  "max_videos" bigint NOT NULL DEFAULT 0,
  "max_friends" bigint NOT NULL DEFAULT 0,
  "max_messages_per_day" bigint NOT NULL DEFAULT 0,
  "max_interactions_per_day" bigint NOT NULL DEFAULT 0,
  "shoutbox" smallint NOT NULL DEFAULT 0,
  "chatfactory" smallint NOT NULL DEFAULT 0,
  "blogfactory" smallint NOT NULL DEFAULT 0,
  "top_friends" bigint NOT NULL DEFAULT 0,
  "groups_create" bigint NOT NULL DEFAULT 0,
  "groups_join" bigint NOT NULL DEFAULT 0,
  "same_gender_interaction" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_memberships_sold";
CREATE TABLE "#__lovefactory_memberships_sold" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL,
  "membership_id" bigint NOT NULL DEFAULT 0,
  "payment_id" bigint NOT NULL DEFAULT 0,
  "start_membership" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "end_membership" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "end_notification" smallint NOT NULL DEFAULT 0,
  "expired" smallint NOT NULL DEFAULT 0,
  "months" bigint NOT NULL DEFAULT 0,
  "title" varchar(255) NOT NULL,
  "default" smallint NOT NULL DEFAULT 0,
  "trial" bigint NOT NULL DEFAULT 0,
  "max_photos" bigint NOT NULL DEFAULT 0,
  "max_videos" bigint NOT NULL DEFAULT 0,
  "max_friends" bigint NOT NULL DEFAULT 0,
  "max_messages_per_day" bigint NOT NULL DEFAULT 0,
  "max_interactions_per_day" bigint NOT NULL DEFAULT 0,
  "shoutbox" smallint NOT NULL DEFAULT 0,
  "chatfactory" smallint NOT NULL DEFAULT 0,
  "blogfactory" smallint NOT NULL DEFAULT 0,
  "top_friends" bigint NOT NULL DEFAULT 0,
  "groups_create" bigint NOT NULL DEFAULT 0,
  "groups_join" bigint NOT NULL DEFAULT 0,
  "same_gender_interaction" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_memberships_sold_idx_membership_id" on "#__lovefactory_memberships_sold" ("membership_id");
CREATE INDEX "#__lovefactory_memberships_sold_idx_user_id" on "#__lovefactory_memberships_sold" ("user_id");

DROP TABLE IF EXISTS "#__lovefactory_messages";
CREATE TABLE "#__lovefactory_messages" (
  "id" serial NOT NULL,
  "sender_id" bigint NOT NULL,
  "receiver_id" bigint NOT NULL,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "title" varchar(255) NOT NULL,
  "text" text NOT NULL,
  "unread" smallint NOT NULL DEFAULT 0,
  "reported" smallint NOT NULL DEFAULT 0,
  "deleted_by_sender" smallint NOT NULL DEFAULT 0,
  "deleted_by_receiver" smallint NOT NULL DEFAULT 0,
  "approved" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_messages_idx_sender_id" on "#__lovefactory_messages" ("sender_id");
CREATE INDEX "#__lovefactory_messages_idx_receiver_id" on "#__lovefactory_messages" ("receiver_id");

DROP TABLE IF EXISTS "#__lovefactory_notifications";
CREATE TABLE "#__lovefactory_notifications" (
  "id" serial NOT NULL,
  "type" varchar(255) NOT NULL,
  "subject" varchar(255) NOT NULL,
  "body" text NOT NULL,
  "lang_code" varchar(10) NOT NULL,
  "groups" text,
  "published" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_orders";
CREATE TABLE "#__lovefactory_orders" (
  "id" serial NOT NULL,
  "title" varchar(255) NOT NULL,
  "user_id" bigint NOT NULL DEFAULT 0,
  "membership_id" bigint NOT NULL DEFAULT 0,
  "price_id" bigint NOT NULL DEFAULT 0,
  "amount" decimal(10,2) NOT NULL,
  "currency" varchar(10) NOT NULL,
  "gateway" bigint NOT NULL DEFAULT 0,
  "paid" smallint NOT NULL DEFAULT 0,
  "status" smallint NOT NULL DEFAULT 0,
  "membership" text NOT NULL DEFAULT 0,
  "price" text NOT NULL,
  "created_at" bigint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_pages";
CREATE TABLE "#__lovefactory_pages" (
  "id" serial NOT NULL,
  "type" varchar(50) NOT NULL,
  "title" varchar(255) NOT NULL,
  "fields" text NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_payments";
CREATE TABLE "#__lovefactory_payments" (
  "id" serial NOT NULL,
  "order_id" bigint NOT NULL DEFAULT 0,
  "user_id" bigint NOT NULL DEFAULT 0,
  "received_at" bigint NOT NULL DEFAULT 0,
  "payment_date" varchar(255) NOT NULL,
  "amount" decimal(10,2) NOT NULL,
  "currency" varchar(10) NOT NULL,
  "gateway" bigint NOT NULL DEFAULT 0,
  "refnumber" varchar(255) NOT NULL,
  "status" smallint NOT NULL DEFAULT 0,
  "data" text NOT NULL,
  "errors" text NOT NULL,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_photos";
CREATE TABLE "#__lovefactory_photos" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL DEFAULT 0,
  "filename" varchar(255) NOT NULL,
  "ordering" bigint NOT NULL DEFAULT 0,
  "date_added" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "status" smallint NOT NULL DEFAULT 0,
  "approved" smallint NOT NULL DEFAULT 0,
  "reported" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_photos_idx_user_id" on "#__lovefactory_photos" ("user_id");

DROP TABLE IF EXISTS "#__lovefactory_pricing";
CREATE TABLE "#__lovefactory_pricing" (
  "id" serial NOT NULL,
  "published" smallint NOT NULL DEFAULT 0,
  "membership_id" smallint NOT NULL DEFAULT 0,
  "price" decimal(10,2) NOT NULL,
  "months" bigint NOT NULL DEFAULT 0,
  "gender_prices" text,
  "is_trial" smallint NOT NULL DEFAULT 0,
  "available_interval" smallint NOT NULL DEFAULT 0,
  "available_from" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "available_until" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "new_trial" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_profile_updates";
CREATE TABLE "#__lovefactory_profile_updates" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL DEFAULT 0,
  "profile" text NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "pending" smallint NOT NULL DEFAULT 0,
  "approved" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);

CREATE INDEX "#__lovefactory_profile_updates_idx_user_id" on "#__lovefactory_profile_updates" ("user_id");
CREATE INDEX "#__lovefactory_profile_updates_idx_pending" on "#__lovefactory_profile_updates" ("pending");
CREATE INDEX "#__lovefactory_profile_updates_idx_approved" on "#__lovefactory_profile_updates" ("approved");

DROP TABLE IF EXISTS "#__lovefactory_profile_visitors";
CREATE TABLE "#__lovefactory_profile_visitors" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL DEFAULT 0,
  "visitor_id" bigint NOT NULL DEFAULT 0,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_profile_visitors_idx_user_id" on "#__lovefactory_profile_visitors" ("user_id");
CREATE INDEX "#__lovefactory_profile_visitors_idx_visitor_id" on "#__lovefactory_profile_visitors" ("visitor_id");

DROP TABLE IF EXISTS "#__lovefactory_profiles";
CREATE TABLE "#__lovefactory_profiles" (
  "user_id" bigint NOT NULL,
  "online" smallint NOT NULL DEFAULT 0,
  "validated" smallint NOT NULL DEFAULT 0,
  "banned" smallint NOT NULL DEFAULT 0,
  "membership_sold_id" bigint NOT NULL DEFAULT 1,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "loggedin" smallint NOT NULL DEFAULT 0,
  "lastvisit" bigint NOT NULL DEFAULT 0,
  "alerts" smallint NOT NULL DEFAULT 0,
  "rating" decimal(4,2) NOT NULL DEFAULT 0,
  "votes" bigint NOT NULL DEFAULT 0,
  "date_format" varchar(20) NOT NULL DEFAULT 0,
  "infobar" smallint NOT NULL DEFAULT 0,
  "main_photo" bigint NOT NULL DEFAULT 0,
  "relationship" bigint NOT NULL DEFAULT 0,
  "trials" bigint NOT NULL DEFAULT 0,
  "status" text,
  "sex" text,
  "sex_visibility" smallint DEFAULT NULL,
  "looking" text,
  "looking_visibility" smallint DEFAULT NULL,
  "field_6" text,
  "field_6_visibility" smallint DEFAULT NULL,
  "field_16" text,
  "field_16_visibility" smallint DEFAULT NULL,
  "field_18" text,
  "field_18_visibility" smallint DEFAULT NULL,
  "field_19" text,
  "field_19_visibility" smallint DEFAULT NULL,
  "field_22" text,
  "field_22_visibility" smallint DEFAULT NULL,
  "field_43_lat" decimal(15,12) DEFAULT NULL,
  "field_43_lng" decimal(15,12) DEFAULT NULL,
  "field_43_zoom" smallint DEFAULT NULL,
  "field_43_visibility" smallint DEFAULT NULL,
  "field_46" text,
  "field_46_visibility" smallint DEFAULT NULL,
  "field_45" text,
  "field_45_visibility" smallint DEFAULT NULL,
  "field_47" text,
  "field_47_visibility" smallint DEFAULT NULL,
  PRIMARY KEY ("user_id")
);
CREATE INDEX "#__lovefactory_profiles_idx_online" on "#__lovefactory_profiles" ("online");
CREATE INDEX "#__lovefactory_profiles_idx_validated" on "#__lovefactory_profiles" ("validated");
CREATE INDEX "#__lovefactory_profiles_idx_banned" on "#__lovefactory_profiles" ("banned");
CREATE INDEX "#__lovefactory_profiles_idx_membership_sold_id" on "#__lovefactory_profiles" ("membership_sold_id");
CREATE INDEX "#__lovefactory_profiles_idx_loggedin" on "#__lovefactory_profiles" ("loggedin");
CREATE INDEX "#__lovefactory_profiles_idx_lastvisit" on "#__lovefactory_profiles" ("lastvisit");
CREATE INDEX "#__lovefactory_profiles_idx_main_photo" on "#__lovefactory_profiles" ("main_photo");

DROP TABLE IF EXISTS "#__lovefactory_ratings";
CREATE TABLE "#__lovefactory_ratings" (
  "id" serial NOT NULL,
  "sender_id" bigint NOT NULL DEFAULT 0,
  "receiver_id" bigint NOT NULL DEFAULT 0,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "rating" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_ratings_idx_sender_id" on "#__lovefactory_ratings" ("sender_id");
CREATE INDEX "#__lovefactory_ratings_idx_receiver_id" on "#__lovefactory_ratings" ("receiver_id");

DROP TABLE IF EXISTS "#__lovefactory_reports";
CREATE TABLE "#__lovefactory_reports" (
  "id" serial NOT NULL,
  "reporting_id" bigint NOT NULL DEFAULT 0,
  "element" varchar(100) NOT NULL,
  "type" varchar(100) NOT NULL,
  "date" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "comment" text NOT NULL,
  "reported_id" bigint NOT NULL DEFAULT 0,
  "user_id" bigint NOT NULL DEFAULT 0,
  "status" smallint NOT NULL DEFAULT 0,
  "text" text,
  PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "#__lovefactory_shoutbox";
CREATE TABLE "#__lovefactory_shoutbox" (
  "id" serial NOT NULL,
  "sender_id" bigint NOT NULL DEFAULT 0,
  "message" varchar(255) NOT NULL,
  "created_at" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_shoutbox_idx_sender_id" on "#__lovefactory_shoutbox" ("sender_id");

DROP TABLE IF EXISTS "#__lovefactory_statistics_per_day";
CREATE TABLE "#__lovefactory_statistics_per_day" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL DEFAULT 0,
  "date_messages" date,
  "messages" bigint NOT NULL DEFAULT 0,
  "date_interactions" date,
  "interactions" bigint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_statistics_per_day_idx_user_id" on "#__lovefactory_statistics_per_day" ("user_id");

DROP TABLE IF EXISTS "#__lovefactory_videos";
CREATE TABLE "#__lovefactory_videos" (
  "id" serial NOT NULL,
  "user_id" bigint NOT NULL DEFAULT 0,
  "code" text NOT NULL,
  "thumbnail" varchar(255) NOT NULL,
  "title" varchar(255) NOT NULL,
  "description" text NOT NULL,
  "status" smallint NOT NULL DEFAULT 0,
  "ordering" bigint NOT NULL DEFAULT 0,
  "date_added" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "approved" smallint NOT NULL DEFAULT 0,
  "reported" smallint NOT NULL DEFAULT 0,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__lovefactory_videos_idx_user_id" on "#__lovefactory_videos" ("user_id");

INSERT INTO "#__lovefactory_fields" VALUES (1,'Membership','Membership','','','','',0,0,1,0,0,0,0,1),(2,'Main photo','ProfilePhoto','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(3,'Username','Username','{\"ajax_check\":\"1\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(4,'Gender','Gender','{\"choices\":{\"default\":[\"Male\",\"Female\",\"Couple\",\"Other\"]}}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(5,'Looking for','Looking','{\"type\":\"Checkbox\",\"min_choices\":0,\"max_choices\":0,\"display_mode\":\"row\",\"blank_choice\":\"0\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(6,'Date of birth','Birthdate','{\"min_age\":\"18\",\"max_age\":\"40\",\"month_format\":\"text\",\"format\":\"dmY\",\"separator\":\"\\/\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"Age\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Age between\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(7,'Online','Online','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(8,'Photos','Photos','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(9,'Last seen','LastSeen','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(12,'Email','Email','{\"ajax_check\":\"1\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(13,'Password','Password','{\"confirmation_for\":\"\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(14,'Repeat password','Password','{\"confirmation_for\":\"13\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(16,'Name','Text','{\"min_length\":\"\",\"max_length\":\"\",\"validation\":\"\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(18,'About me','Textarea','{\"rows\":\"5\",\"cols\":\"\",\"allowed_tags\":\"iframe, a, object\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(19,'Relationship Status','Checkbox','{\"choices\":{\"default\":[\"Single\",\"In a relationship\",\"Long term relationship\",\"Engaged\",\"Married\",\"Divorced\"]},\"min_choices\":0,\"max_choices\":0,\"display_mode\":\"row\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(22,'Country','Select','{\"choices\":{\"default\":[\"Afghanistan\",\"\\u00c5land Islands\",\"Albania\",\"Algeria\",\"American Samoa\",\"Andorra\",\"Angola\",\"Anguilla\",\"Antarctica\",\"Antigua and Barbuda\",\"Argentina\",\"Armenia\",\"Aruba\",\"Australia\",\"Austria\",\"Azerbaijan\",\"Bahamas\",\"Bahrain\",\"Bangladesh\",\"Barbados\",\"Belarus\",\"Belgium\",\"Belize\",\"Benin\",\"Bermuda\",\"Bhutan\",\"Bolivia\",\"Bosnia and Herzegovina\",\"Botswana\",\"Bouvet Island\",\"Brazil\",\"British Indian Ocean Territory\",\"Brunei Darussalam\",\"Bulgaria\",\"Burkina Faso\",\"Burundi\",\"Cambodia\",\"Cameroon\",\"Canada\",\"Cape Verde\",\"Cayman Islands\",\"Central African Republic\",\"Chad\",\"Chile\",\"China\",\"Christmas Island\",\"Cocos (Keeling) Islands\",\"Colombia\",\"Comoros\",\"Congo\",\"Congo, The Democratic Republic of The\",\"Cook Islands\",\"Costa Rica\",\"Cote Divoire\",\"Croatia\",\"Cuba\",\"Cyprus\",\"Czech Republic\",\"Denmark\",\"Djibouti\",\"Dominica\",\"Dominican Republic\",\"Ecuador\",\"Egypt\",\"El Salvador\",\"Equatorial Guinea\",\"Eritrea\",\"Estonia\",\"Ethiopia\",\"Falkland Islands (Malvinas)\",\"Faroe Islands\",\"Fiji\",\"Finland\",\"France\",\"French Guiana\",\"French Polynesia\",\"French Southern Territories\",\"Gabon\",\"Gambia\",\"Georgia\",\"Germany\",\"Ghana\",\"Gibraltar\",\"Greece\",\"Greenland\",\"Grenada\",\"Guadeloupe\",\"Guam\",\"Guatemala\",\"Guernsey\",\"Guinea\",\"Guinea-bissau\",\"Guyana\",\"Haiti\",\"Heard Island and Mcdonald Islands\",\"Holy See (Vatican City State)\",\"Honduras\",\"Hong Kong\",\"Hungary\",\"Iceland\",\"India\",\"Indonesia\",\"Iran, Islamic Republic of\",\"Iraq\",\"Ireland\",\"Isle of Man\",\"Israel\",\"Italy\",\"Jamaica\",\"Japan\",\"Jersey\",\"Jordan\",\"Kazakhstan\",\"Kenya\",\"Kiribati\",\"Korea, Democratic Peoples Republic of\",\"Korea, Republic of\",\"Kuwait\",\"Kyrgyzstan\",\"Lao Peoples Democratic Republic\",\"Latvia\",\"Lebanon\",\"Lesotho\",\"Liberia\",\"Libyan Arab Jamahiriya\",\"Liechtenstein\",\"Lithuania\",\"Luxembourg\",\"Macao\",\"Macedonia, The Former Yugoslav Republic of\",\"Madagascar\",\"Malawi\",\"Malaysia\",\"Maldives\",\"Mali\",\"Malta\",\"Marshall Islands\",\"Martinique\",\"Mauritania\",\"Mauritius\",\"Mayotte\",\"Mexico\",\"Micronesia, Federated States of\",\"Moldova, Republic of\",\"Monaco\",\"Mongolia\",\"Montenegro\",\"Montserrat\",\"Morocco\",\"Mozambique\",\"Myanmar\",\"Namibia\",\"Nauru\",\"Nepal\",\"Netherlands\",\"Netherlands Antilles\",\"New Caledonia\",\"New Zealand\",\"Nicaragua\",\"Niger\",\"Nigeria\",\"Niue\",\"Norfolk Island\",\"Northern Mariana Islands\",\"Norway\",\"Oman\",\"Pakistan\",\"Palau\",\"Palestinian Territory, Occupied\",\"Panama\",\"Papua New Guinea\",\"Paraguay\",\"Peru\",\"Philippines\",\"Pitcairn\",\"Poland\",\"Portugal\",\"Puerto Rico\",\"Qatar\",\"Reunion\",\"Romania\",\"Russian Federation\",\"Rwanda\",\"Saint Helena\",\"Saint Kitts and Nevis\",\"Saint Lucia\",\"Saint Pierre and Miquelon\",\"Saint Vincent and The Grenadines\",\"Samoa\",\"San Marino\",\"Sao Tome and Principe\",\"Saudi Arabia\",\"Senegal\",\"Serbia\",\"Seychelles\",\"Sierra Leone\",\"Singapore\",\"Slovakia\",\"Slovenia\",\"Solomon Islands\",\"Somalia\",\"South Africa\",\"South Georgia and The South Sandwich Islands\",\"Spain\",\"Sri Lanka\",\"Sudan\",\"Suriname\",\"Svalbard and Jan Mayen\",\"Swaziland\",\"Sweden\",\"Switzerland\",\"Syrian Arab Republic\",\"Taiwan, Province of China\",\"Tajikistan\",\"Tanzania, United Republic of\",\"Thailand\",\"Timor-leste\",\"Togo\",\"Tokelau\",\"Tonga\",\"Trinidad and Tobago\",\"Tunisia\",\"Turkey\",\"Turkmenistan\",\"Turks and Caicos Islands\",\"Tuvalu\",\"Uganda\",\"Ukraine\",\"United Arab Emirates\",\"United Kingdom\",\"United States\",\"United States Minor Outlying Islands\",\"Uruguay\",\"Uzbekistan\",\"Vanuatu\",\"Venezuela\",\"Viet Nam\",\"Virgin Islands, British\",\"Virgin Islands, U.S.\",\"Wallis and Futuna\",\"Western Sahara\",\"Yemen\",\"Zambia\",\"Zimbabwe\"]},\"blank_choice\":\"1\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(23,'Friends','Friends','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(43,'Google Maps Location','GoogleMapsLocation','{\"edit_height\":\"200\",\"edit_width\":\"100%\",\"view_height\":\"200\",\"view_width\":\"100%\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(25,'Distance','Distance','{\"google_maps_field\":\"43\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Maximum distance\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(26,'Relationship','Relationship','{\"search_mode\":\"0\",\"display_mode\":\"photo\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"This is your chance to find single members in your area!\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(27,'ReCaptcha','ReCaptcha','{\"theme\":\"red\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(28,'Spacer','Spacer','{\"height\":\"50\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(29,'Terms and Conditions','Terms','{\"article\":\"1\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',1,0,1,0,0,0,0,1),(30,'Profile rating','Rating','{\"view_mode\":\"1\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(41,'Search Multiple','SearchMultiple','{\"searchable_fields\":[\"16\",\"3\"]}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Name and Username\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(42,'Radius Search','Radius','{\"google_maps_field\":\"43\",\"max_radius\":\"1000\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Enter the radius you want to search for members in.\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Radius\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(44,'Big Spacer','Spacer','{\"height\":\"200\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(45,'Here for','Checkbox','{\"choices\":{\"default\":[\"Friends\",\"Fun\",\"Dating\",\"Relationship\",\"Sex\"]},\"min_choices\":0,\"max_choices\":0,\"display_mode\":\"row\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(46,'City','Text','{\"min_length\":\"\",\"max_length\":\"\",\"validation\":\"\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1),(47,'Surname','Text','{\"min_length\":\"\",\"max_length\":\"\",\"validation\":\"\"}','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}','','{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',0,0,1,0,0,0,0,1);
INSERT INTO "#__lovefactory_memberships" VALUES (1,'Free',1,1,1,'',0,0,0,0,0,0,0,0,0,0,0),(2,'Starter',1,2,0,'',1,1,1,1,1,0,0,0,0,0,0),(3,'Basic',1,3,0,'',3,3,3,5,5,0,0,1,0,0,0),(4,'Advanced',1,4,0,'',5,5,5,10,10,0,0,3,0,0,0),(5,'Expert',1,5,0,'',10,10,10,50,50,0,0,5,0,0,0),(6,'Unlimited',1,6,0,'',-1,-1,-1,-1,-1,2,0,-1,-1,-1,0);
INSERT INTO "#__lovefactory_memberships_sold" VALUES (1,0,1,0,'1970-01-01 00:00:00','1970-01-01 00:00:00',0,0,0,'Free',0,0,0,0,0,0,0,0,0,0,0,0,0);
INSERT INTO "#__lovefactory_notifications" VALUES (15,'comment_received','New comment received!','<p>Hello %%receiver_username%%!</p><p>You have received a new comment from %%sender_username%%:</p><p>\"%%message%%\"</p><p>Regards,<br />Love Factory Team</p>','*','',1),(14,'signup_with_user_activation','Account Details for %%name%% at %%site_url%%','<p>Hello %%name%%,</p><p>Thank you for registering at <a href=\"%%site_url%%\">%%site_name%%</a>. Your account is created and must be activated before you can use it.</p><p> </p><p>To activate the account click on the following link or copy-paste it in your browser:</p><p><a href=\"%%activation_link%%\">%%activation_link%%</a></p><p> </p><p>After activation you may login to <a href=\"%%site_url%%\">%%site_url%%</a> using the following username and password:</p><p>Username: %%username%%</p><p>Password: %%password%%</p>','*','',1),(20,'comment_received','New comment received!','<p>Hello %%receiver_username%%!</p><p>You have received a new comment from %%sender_username%%:</p><p>\"%%message%%\"</p><p>Regards,<br />Love Factory Team</p>','en-GB','',1),(21,'interaction_received','New interaction received!','<p>Hello %%receiver_username%%!</p><p>You have received a new interaction from %%sender_username%%!</p><p><br />Regards,</p><p>Love Factory Team!</p>','*','',1),(22,'message_received','New message received!','<p>Hello %%receiver_username%%!</p><p>You have received a new message from %%sender_username%%:</p><p>\"%%message_body%%\"</p><p>Regards,</p><p>Love Factory Team!</p>','*','',1),(23,'comment_photo_received','New photo comment received!','<p>Hello %%receiver_username%%!</p><p> </p><p>You have received a new photo comment from %%sender_username%%!</p><p>\"%%message%%\"</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>','*','',1),(24,'comment_video_received','New video comment received!','<p>Hello %%receiver_username%%!</p><p> </p><p>You have received a new video comment from %%sender_username%%!</p><p>\"%%message%%\"</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>','*','',1),(25,'rating_received','New rating received!','<p>Hello %%receiver_username%%!</p><p><br />You have received a new rating of %%rating%% from %%sender_username%%!</p><p><br />Regards,</p><p>Love Factory Team!</p>','*','',1),(26,'membership_ending','Membership ending!','<p>Hello %%receiver_username%%!</p><p> </p><p>Your membership is about to expire in less than %%days%% day(s).</p><p> </p><p>Regards,<br />Love Factory Team</p>','*','',1),(27,'signup_without_activation','Account Details for %%name%% at %%site_name%%','<p>Hello %%name%%,</p><p> </p><p>Thank you for registering at <a href=\"%%site_url%%\">%%site_name%%</a>.</p><p>You may now log in to <a href=\"%%site_url%%\">%%site_url%%</a> using the username and password you registered with.</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>','*','',1),(28,'signup_with_admin_activation','Account Details for %%name%% at %%site_name%% ','<p>Hello %%name%%,</p><p>Thank you for registering at <a href=\"%%site_url%%\">%%site_name%%</a>. Your account is created and must be verified before you can use it.</p><p>To verify the account click on the following link or copy-paste it in your browser:</p><p><a href=\"%%activation_link%%\">%%activation_link%%</a></p><p> </p><p>After verification an administrator will be notified to activate your account. You will receive a confirmation when it is done.</p><p>Once that account has been activated you may login to <a href=\"%%site_url%%\">%%site_url%%</a> using the following username and password:</p><p>Username: %%username%%</p><p>Password: %%password%%</p>','*','',1),(29,'offline_payment','Offline payment details','<p>Hello %%receiver_username%%!</p><p> </p><p>Here are the bank details for the order %%order_id%%:</p><p>%%bank_details%%</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>','*','',1),(30,'item_pending_approval','New item pending approval!','<p>Hello %%receiver_username%%!</p><p> </p><p>A new item of type <strong>%%item_type%%</strong> is pending approval!</p><p> </p><p>Regards,</p><p>Love Factory Team</p>','*','{\"0\":\"8\"}',1);
INSERT INTO "#__lovefactory_pages" VALUES (1,'registration','Registration','{\"0\":{\"titles\":{\"default\":\"Account Details\",\"en-GB\":\"\"},\"setup\":[[\"3\",\"12\",\"13\",\"14\"]]},\"1\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"16\",\"47\",\"4\",\"5\",\"6\"],[\"19\",\"45\",\"2\"]]},\"2\":{\"titles\":{\"default\":\"Location\",\"en-GB\":\"\"},\"setup\":[[\"46\",\"22\"]]},\"3\":{\"titles\":{\"default\":\"Terms and Security\",\"en-GB\":\"\"},\"setup\":[[\"27\",\"29\"]]}}'),(2,'profile_edit','Profile Edit','{\"0\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"16\",\"47\",\"4\"],[\"6\",\"5\"]]},\"1\":{\"titles\":{\"default\":\"More Information\",\"en-GB\":\"\"},\"setup\":[[\"19\",\"45\"],[\"46\",\"22\"]]},\"2\":{\"titles\":{\"default\":\"About Me\",\"en-GB\":\"\"},\"setup\":[[\"18\"]]}}'),(3,'search_quick','Search','{\"0\":{\"titles\":{\"default\":\"Search\",\"en-GB\":\"\"},\"setup\":[[\"41\"]]}}'),(4,'search_advanced','Search for a match','{\"0\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"4\",\"5\",\"6\"],[\"7\",\"8\"]]},\"1\":{\"titles\":{\"default\":\"More information\",\"en-GB\":\"\"},\"setup\":[[\"19\",\"45\"],[\"46\",\"22\"]]}}'),(5,'profile_results','Profile Search','{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\"],[\"7\",\"16\",\"6\"],[\"4\",\"5\"],[\"46\",\"22\",\"9\"]]}}'),(6,'profile_view','Profile View','{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\"],[\"16\",\"6\",\"9\",\"5\"],[\"47\",\"4\",\"7\"]]},\"1\":{\"titles\":{\"default\":\"More Information\",\"en-GB\":\"\"},\"setup\":[[\"19\",\"46\",\"23\",\"1\"],[\"45\",\"22\",\"8\",\"3\"]]},\"2\":{\"titles\":{\"default\":\"About Me\",\"en-GB\":\"\"},\"setup\":[[\"18\"]]}}'),(7,'friends_view','Friends list view','{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\"],[\"7\",\"16\",\"6\"],[\"4\",\"5\"],[\"46\",\"22\",\"9\"]]}}'),(8,'profile_fillin','Profile Fillin','{\"0\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"4\",\"5\",\"6\"],[\"2\"]]},\"1\":{\"titles\":{\"default\":\"Basic Information\",\"en-GB\":\"\"},\"setup\":[[\"16\",\"47\"],[\"19\",\"45\"]]},\"2\":{\"titles\":{\"default\":\"Location\",\"en-GB\":\"\"},\"setup\":[[\"46\",\"22\"]]}}'),(9,'profile_map','Map member info','{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\"],[\"16\",\"4\",\"5\"]]}}'),(10,'radius_search','Radius search','{\"0\":{\"titles\":{\"default\":\"\",\"en-GB\":\"\"},\"setup\":[[\"42\",\"4\"]]}}');

SELECT setval('#__lovefactory_fields_id_seq', (SELECT MAX(id) FROM #__lovefactory_fields) + 1);
SELECT setval('#__lovefactory_memberships_id_seq', (SELECT MAX(id) FROM #__lovefactory_memberships) + 1);
SELECT setval('#__lovefactory_memberships_sold_id_seq', (SELECT MAX(id) FROM #__lovefactory_memberships_sold) + 1);
SELECT setval('#__lovefactory_notifications_id_seq', (SELECT MAX(id) FROM #__lovefactory_notifications) + 1);
SELECT setval('#__lovefactory_pages_id_seq', (SELECT MAX(id) FROM #__lovefactory_pages) + 1);
