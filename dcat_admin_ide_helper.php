<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */

namespace Dcat\Admin {

    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection batch
     * @property Grid\Column|Collection migration
     * @property Grid\Column|Collection account
     * @property Grid\Column|Collection target_account
     * @property Grid\Column|Collection content
     * @property Grid\Column|Collection likes
     * @property Grid\Column|Collection comment
     * @property Grid\Column|Collection new
     * @property Grid\Column|Collection to_account
     * @property Grid\Column|Collection target_new
     * @property Grid\Column|Collection host
     * @property Grid\Column|Collection target_account_id
     * @property Grid\Column|Collection target_name
     * @property Grid\Column|Collection target_user_id
     * @property Grid\Column|Collection body
     * @property Grid\Column|Collection readed
     * @property Grid\Column|Collection subject
     * @property Grid\Column|Collection token
     * @property Grid\Column|Collection group
     * @property Grid\Column|Collection flag
     * @property Grid\Column|Collection comment_color
     * @property Grid\Column|Collection mod_level
     * @property Grid\Column|Collection comment_history_state
     * @property Grid\Column|Collection friend_request_state
     * @property Grid\Column|Collection message_state
     * @property Grid\Column|Collection twitch
     * @property Grid\Column|Collection twitter
     * @property Grid\Column|Collection youtube
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection email_verified_at
     * @property Grid\Column|Collection collect_count
     * @property Grid\Column|Collection reward_count
     * @property Grid\Column|Collection contest
     * @property Grid\Column|Collection level
     * @property Grid\Column|Collection submitted
     * @property Grid\Column|Collection desc
     * @property Grid\Column|Collection expired_at
     * @property Grid\Column|Collection owner
     * @property Grid\Column|Collection author_name
     * @property Grid\Column|Collection disabled
     * @property Grid\Column|Collection download_url
     * @property Grid\Column|Collection hash
     * @property Grid\Column|Collection size
     * @property Grid\Column|Collection song_id
     * @property Grid\Column|Collection uploader
     * @property Grid\Column|Collection time
     * @property Grid\Column|Collection percent
     * @property Grid\Column|Collection level1
     * @property Grid\Column|Collection level2
     * @property Grid\Column|Collection level3
     * @property Grid\Column|Collection level4
     * @property Grid\Column|Collection level5
     * @property Grid\Column|Collection bar_color
     * @property Grid\Column|Collection coins
     * @property Grid\Column|Collection difficulty
     * @property Grid\Column|Collection levels
     * @property Grid\Column|Collection stars
     * @property Grid\Column|Collection text_color
     * @property Grid\Column|Collection featured
     * @property Grid\Column|Collection rating
     * @property Grid\Column|Collection user
     * @property Grid\Column|Collection auto
     * @property Grid\Column|Collection coin_verified
     * @property Grid\Column|Collection demon
     * @property Grid\Column|Collection demon_difficulty
     * @property Grid\Column|Collection epic
     * @property Grid\Column|Collection featured_score
     * @property Grid\Column|Collection times
     * @property Grid\Column|Collection attempts
     * @property Grid\Column|Collection audio_track
     * @property Grid\Column|Collection downloads
     * @property Grid\Column|Collection extra_string
     * @property Grid\Column|Collection game_version
     * @property Grid\Column|Collection ldm
     * @property Grid\Column|Collection length
     * @property Grid\Column|Collection level_info
     * @property Grid\Column|Collection objects
     * @property Grid\Column|Collection original
     * @property Grid\Column|Collection requested_stars
     * @property Grid\Column|Collection song
     * @property Grid\Column|Collection two_player
     * @property Grid\Column|Collection unlisted
     * @property Grid\Column|Collection ip
     * @property Grid\Column|Collection author_id
     * @property Grid\Column|Collection acc_ball
     * @property Grid\Column|Collection acc_bird
     * @property Grid\Column|Collection acc_dart
     * @property Grid\Column|Collection acc_explosion
     * @property Grid\Column|Collection acc_glow
     * @property Grid\Column|Collection acc_icon
     * @property Grid\Column|Collection acc_robot
     * @property Grid\Column|Collection acc_ship
     * @property Grid\Column|Collection acc_spider
     * @property Grid\Column|Collection binary_version
     * @property Grid\Column|Collection chest1count
     * @property Grid\Column|Collection chest1time
     * @property Grid\Column|Collection chest2count
     * @property Grid\Column|Collection chest2time
     * @property Grid\Column|Collection color1
     * @property Grid\Column|Collection color2
     * @property Grid\Column|Collection creator_points
     * @property Grid\Column|Collection demons
     * @property Grid\Column|Collection diamonds
     * @property Grid\Column|Collection icon_type
     * @property Grid\Column|Collection special
     * @property Grid\Column|Collection user_coins
     * @property Grid\Column|Collection udid
     * @property Grid\Column|Collection uuid
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection queue
     *
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection batch(string $label = null)
     * @method Grid\Column|Collection migration(string $label = null)
     * @method Grid\Column|Collection account(string $label = null)
     * @method Grid\Column|Collection target_account(string $label = null)
     * @method Grid\Column|Collection content(string $label = null)
     * @method Grid\Column|Collection likes(string $label = null)
     * @method Grid\Column|Collection comment(string $label = null)
     * @method Grid\Column|Collection new(string $label = null)
     * @method Grid\Column|Collection to_account(string $label = null)
     * @method Grid\Column|Collection target_new(string $label = null)
     * @method Grid\Column|Collection host(string $label = null)
     * @method Grid\Column|Collection target_account_id(string $label = null)
     * @method Grid\Column|Collection target_name(string $label = null)
     * @method Grid\Column|Collection target_user_id(string $label = null)
     * @method Grid\Column|Collection body(string $label = null)
     * @method Grid\Column|Collection readed(string $label = null)
     * @method Grid\Column|Collection subject(string $label = null)
     * @method Grid\Column|Collection token(string $label = null)
     * @method Grid\Column|Collection group(string $label = null)
     * @method Grid\Column|Collection flag(string $label = null)
     * @method Grid\Column|Collection comment_color(string $label = null)
     * @method Grid\Column|Collection mod_level(string $label = null)
     * @method Grid\Column|Collection comment_history_state(string $label = null)
     * @method Grid\Column|Collection friend_request_state(string $label = null)
     * @method Grid\Column|Collection message_state(string $label = null)
     * @method Grid\Column|Collection twitch(string $label = null)
     * @method Grid\Column|Collection twitter(string $label = null)
     * @method Grid\Column|Collection youtube(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection email_verified_at(string $label = null)
     * @method Grid\Column|Collection collect_count(string $label = null)
     * @method Grid\Column|Collection reward_count(string $label = null)
     * @method Grid\Column|Collection contest(string $label = null)
     * @method Grid\Column|Collection level(string $label = null)
     * @method Grid\Column|Collection submitted(string $label = null)
     * @method Grid\Column|Collection desc(string $label = null)
     * @method Grid\Column|Collection expired_at(string $label = null)
     * @method Grid\Column|Collection owner(string $label = null)
     * @method Grid\Column|Collection author_name(string $label = null)
     * @method Grid\Column|Collection disabled(string $label = null)
     * @method Grid\Column|Collection download_url(string $label = null)
     * @method Grid\Column|Collection hash(string $label = null)
     * @method Grid\Column|Collection size(string $label = null)
     * @method Grid\Column|Collection song_id(string $label = null)
     * @method Grid\Column|Collection uploader(string $label = null)
     * @method Grid\Column|Collection time(string $label = null)
     * @method Grid\Column|Collection percent(string $label = null)
     * @method Grid\Column|Collection level1(string $label = null)
     * @method Grid\Column|Collection level2(string $label = null)
     * @method Grid\Column|Collection level3(string $label = null)
     * @method Grid\Column|Collection level4(string $label = null)
     * @method Grid\Column|Collection level5(string $label = null)
     * @method Grid\Column|Collection bar_color(string $label = null)
     * @method Grid\Column|Collection coins(string $label = null)
     * @method Grid\Column|Collection difficulty(string $label = null)
     * @method Grid\Column|Collection levels(string $label = null)
     * @method Grid\Column|Collection stars(string $label = null)
     * @method Grid\Column|Collection text_color(string $label = null)
     * @method Grid\Column|Collection featured(string $label = null)
     * @method Grid\Column|Collection rating(string $label = null)
     * @method Grid\Column|Collection user(string $label = null)
     * @method Grid\Column|Collection auto(string $label = null)
     * @method Grid\Column|Collection coin_verified(string $label = null)
     * @method Grid\Column|Collection demon(string $label = null)
     * @method Grid\Column|Collection demon_difficulty(string $label = null)
     * @method Grid\Column|Collection epic(string $label = null)
     * @method Grid\Column|Collection featured_score(string $label = null)
     * @method Grid\Column|Collection times(string $label = null)
     * @method Grid\Column|Collection attempts(string $label = null)
     * @method Grid\Column|Collection audio_track(string $label = null)
     * @method Grid\Column|Collection downloads(string $label = null)
     * @method Grid\Column|Collection extra_string(string $label = null)
     * @method Grid\Column|Collection game_version(string $label = null)
     * @method Grid\Column|Collection ldm(string $label = null)
     * @method Grid\Column|Collection length(string $label = null)
     * @method Grid\Column|Collection level_info(string $label = null)
     * @method Grid\Column|Collection objects(string $label = null)
     * @method Grid\Column|Collection original(string $label = null)
     * @method Grid\Column|Collection requested_stars(string $label = null)
     * @method Grid\Column|Collection song(string $label = null)
     * @method Grid\Column|Collection two_player(string $label = null)
     * @method Grid\Column|Collection unlisted(string $label = null)
     * @method Grid\Column|Collection ip(string $label = null)
     * @method Grid\Column|Collection author_id(string $label = null)
     * @method Grid\Column|Collection acc_ball(string $label = null)
     * @method Grid\Column|Collection acc_bird(string $label = null)
     * @method Grid\Column|Collection acc_dart(string $label = null)
     * @method Grid\Column|Collection acc_explosion(string $label = null)
     * @method Grid\Column|Collection acc_glow(string $label = null)
     * @method Grid\Column|Collection acc_icon(string $label = null)
     * @method Grid\Column|Collection acc_robot(string $label = null)
     * @method Grid\Column|Collection acc_ship(string $label = null)
     * @method Grid\Column|Collection acc_spider(string $label = null)
     * @method Grid\Column|Collection binary_version(string $label = null)
     * @method Grid\Column|Collection chest1count(string $label = null)
     * @method Grid\Column|Collection chest1time(string $label = null)
     * @method Grid\Column|Collection chest2count(string $label = null)
     * @method Grid\Column|Collection chest2time(string $label = null)
     * @method Grid\Column|Collection color1(string $label = null)
     * @method Grid\Column|Collection color2(string $label = null)
     * @method Grid\Column|Collection creator_points(string $label = null)
     * @method Grid\Column|Collection demons(string $label = null)
     * @method Grid\Column|Collection diamonds(string $label = null)
     * @method Grid\Column|Collection icon_type(string $label = null)
     * @method Grid\Column|Collection special(string $label = null)
     * @method Grid\Column|Collection user_coins(string $label = null)
     * @method Grid\Column|Collection udid(string $label = null)
     * @method Grid\Column|Collection uuid(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     */
    class Grid
    {
    }

    class MiniGrid extends Grid
    {
    }

    /**
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection id
     * @property Show\Field|Collection name
     * @property Show\Field|Collection type
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection version
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection order
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection value
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection password
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection username
     * @property Show\Field|Collection batch
     * @property Show\Field|Collection migration
     * @property Show\Field|Collection account
     * @property Show\Field|Collection target_account
     * @property Show\Field|Collection content
     * @property Show\Field|Collection likes
     * @property Show\Field|Collection comment
     * @property Show\Field|Collection new
     * @property Show\Field|Collection to_account
     * @property Show\Field|Collection target_new
     * @property Show\Field|Collection host
     * @property Show\Field|Collection target_account_id
     * @property Show\Field|Collection target_name
     * @property Show\Field|Collection target_user_id
     * @property Show\Field|Collection body
     * @property Show\Field|Collection readed
     * @property Show\Field|Collection subject
     * @property Show\Field|Collection token
     * @property Show\Field|Collection group
     * @property Show\Field|Collection flag
     * @property Show\Field|Collection comment_color
     * @property Show\Field|Collection mod_level
     * @property Show\Field|Collection comment_history_state
     * @property Show\Field|Collection friend_request_state
     * @property Show\Field|Collection message_state
     * @property Show\Field|Collection twitch
     * @property Show\Field|Collection twitter
     * @property Show\Field|Collection youtube
     * @property Show\Field|Collection email
     * @property Show\Field|Collection email_verified_at
     * @property Show\Field|Collection collect_count
     * @property Show\Field|Collection reward_count
     * @property Show\Field|Collection contest
     * @property Show\Field|Collection level
     * @property Show\Field|Collection submitted
     * @property Show\Field|Collection desc
     * @property Show\Field|Collection expired_at
     * @property Show\Field|Collection owner
     * @property Show\Field|Collection author_name
     * @property Show\Field|Collection disabled
     * @property Show\Field|Collection download_url
     * @property Show\Field|Collection hash
     * @property Show\Field|Collection size
     * @property Show\Field|Collection song_id
     * @property Show\Field|Collection uploader
     * @property Show\Field|Collection time
     * @property Show\Field|Collection percent
     * @property Show\Field|Collection level1
     * @property Show\Field|Collection level2
     * @property Show\Field|Collection level3
     * @property Show\Field|Collection level4
     * @property Show\Field|Collection level5
     * @property Show\Field|Collection bar_color
     * @property Show\Field|Collection coins
     * @property Show\Field|Collection difficulty
     * @property Show\Field|Collection levels
     * @property Show\Field|Collection stars
     * @property Show\Field|Collection text_color
     * @property Show\Field|Collection featured
     * @property Show\Field|Collection rating
     * @property Show\Field|Collection user
     * @property Show\Field|Collection auto
     * @property Show\Field|Collection coin_verified
     * @property Show\Field|Collection demon
     * @property Show\Field|Collection demon_difficulty
     * @property Show\Field|Collection epic
     * @property Show\Field|Collection featured_score
     * @property Show\Field|Collection times
     * @property Show\Field|Collection attempts
     * @property Show\Field|Collection audio_track
     * @property Show\Field|Collection downloads
     * @property Show\Field|Collection extra_string
     * @property Show\Field|Collection game_version
     * @property Show\Field|Collection ldm
     * @property Show\Field|Collection length
     * @property Show\Field|Collection level_info
     * @property Show\Field|Collection objects
     * @property Show\Field|Collection original
     * @property Show\Field|Collection requested_stars
     * @property Show\Field|Collection song
     * @property Show\Field|Collection two_player
     * @property Show\Field|Collection unlisted
     * @property Show\Field|Collection ip
     * @property Show\Field|Collection author_id
     * @property Show\Field|Collection acc_ball
     * @property Show\Field|Collection acc_bird
     * @property Show\Field|Collection acc_dart
     * @property Show\Field|Collection acc_explosion
     * @property Show\Field|Collection acc_glow
     * @property Show\Field|Collection acc_icon
     * @property Show\Field|Collection acc_robot
     * @property Show\Field|Collection acc_ship
     * @property Show\Field|Collection acc_spider
     * @property Show\Field|Collection binary_version
     * @property Show\Field|Collection chest1count
     * @property Show\Field|Collection chest1time
     * @property Show\Field|Collection chest2count
     * @property Show\Field|Collection chest2time
     * @property Show\Field|Collection color1
     * @property Show\Field|Collection color2
     * @property Show\Field|Collection creator_points
     * @property Show\Field|Collection demons
     * @property Show\Field|Collection diamonds
     * @property Show\Field|Collection icon_type
     * @property Show\Field|Collection special
     * @property Show\Field|Collection user_coins
     * @property Show\Field|Collection udid
     * @property Show\Field|Collection uuid
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection queue
     *
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection batch(string $label = null)
     * @method Show\Field|Collection migration(string $label = null)
     * @method Show\Field|Collection account(string $label = null)
     * @method Show\Field|Collection target_account(string $label = null)
     * @method Show\Field|Collection content(string $label = null)
     * @method Show\Field|Collection likes(string $label = null)
     * @method Show\Field|Collection comment(string $label = null)
     * @method Show\Field|Collection new(string $label = null)
     * @method Show\Field|Collection to_account(string $label = null)
     * @method Show\Field|Collection target_new(string $label = null)
     * @method Show\Field|Collection host(string $label = null)
     * @method Show\Field|Collection target_account_id(string $label = null)
     * @method Show\Field|Collection target_name(string $label = null)
     * @method Show\Field|Collection target_user_id(string $label = null)
     * @method Show\Field|Collection body(string $label = null)
     * @method Show\Field|Collection readed(string $label = null)
     * @method Show\Field|Collection subject(string $label = null)
     * @method Show\Field|Collection token(string $label = null)
     * @method Show\Field|Collection group(string $label = null)
     * @method Show\Field|Collection flag(string $label = null)
     * @method Show\Field|Collection comment_color(string $label = null)
     * @method Show\Field|Collection mod_level(string $label = null)
     * @method Show\Field|Collection comment_history_state(string $label = null)
     * @method Show\Field|Collection friend_request_state(string $label = null)
     * @method Show\Field|Collection message_state(string $label = null)
     * @method Show\Field|Collection twitch(string $label = null)
     * @method Show\Field|Collection twitter(string $label = null)
     * @method Show\Field|Collection youtube(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection email_verified_at(string $label = null)
     * @method Show\Field|Collection collect_count(string $label = null)
     * @method Show\Field|Collection reward_count(string $label = null)
     * @method Show\Field|Collection contest(string $label = null)
     * @method Show\Field|Collection level(string $label = null)
     * @method Show\Field|Collection submitted(string $label = null)
     * @method Show\Field|Collection desc(string $label = null)
     * @method Show\Field|Collection expired_at(string $label = null)
     * @method Show\Field|Collection owner(string $label = null)
     * @method Show\Field|Collection author_name(string $label = null)
     * @method Show\Field|Collection disabled(string $label = null)
     * @method Show\Field|Collection download_url(string $label = null)
     * @method Show\Field|Collection hash(string $label = null)
     * @method Show\Field|Collection size(string $label = null)
     * @method Show\Field|Collection song_id(string $label = null)
     * @method Show\Field|Collection uploader(string $label = null)
     * @method Show\Field|Collection time(string $label = null)
     * @method Show\Field|Collection percent(string $label = null)
     * @method Show\Field|Collection level1(string $label = null)
     * @method Show\Field|Collection level2(string $label = null)
     * @method Show\Field|Collection level3(string $label = null)
     * @method Show\Field|Collection level4(string $label = null)
     * @method Show\Field|Collection level5(string $label = null)
     * @method Show\Field|Collection bar_color(string $label = null)
     * @method Show\Field|Collection coins(string $label = null)
     * @method Show\Field|Collection difficulty(string $label = null)
     * @method Show\Field|Collection levels(string $label = null)
     * @method Show\Field|Collection stars(string $label = null)
     * @method Show\Field|Collection text_color(string $label = null)
     * @method Show\Field|Collection featured(string $label = null)
     * @method Show\Field|Collection rating(string $label = null)
     * @method Show\Field|Collection user(string $label = null)
     * @method Show\Field|Collection auto(string $label = null)
     * @method Show\Field|Collection coin_verified(string $label = null)
     * @method Show\Field|Collection demon(string $label = null)
     * @method Show\Field|Collection demon_difficulty(string $label = null)
     * @method Show\Field|Collection epic(string $label = null)
     * @method Show\Field|Collection featured_score(string $label = null)
     * @method Show\Field|Collection times(string $label = null)
     * @method Show\Field|Collection attempts(string $label = null)
     * @method Show\Field|Collection audio_track(string $label = null)
     * @method Show\Field|Collection downloads(string $label = null)
     * @method Show\Field|Collection extra_string(string $label = null)
     * @method Show\Field|Collection game_version(string $label = null)
     * @method Show\Field|Collection ldm(string $label = null)
     * @method Show\Field|Collection length(string $label = null)
     * @method Show\Field|Collection level_info(string $label = null)
     * @method Show\Field|Collection objects(string $label = null)
     * @method Show\Field|Collection original(string $label = null)
     * @method Show\Field|Collection requested_stars(string $label = null)
     * @method Show\Field|Collection song(string $label = null)
     * @method Show\Field|Collection two_player(string $label = null)
     * @method Show\Field|Collection unlisted(string $label = null)
     * @method Show\Field|Collection ip(string $label = null)
     * @method Show\Field|Collection author_id(string $label = null)
     * @method Show\Field|Collection acc_ball(string $label = null)
     * @method Show\Field|Collection acc_bird(string $label = null)
     * @method Show\Field|Collection acc_dart(string $label = null)
     * @method Show\Field|Collection acc_explosion(string $label = null)
     * @method Show\Field|Collection acc_glow(string $label = null)
     * @method Show\Field|Collection acc_icon(string $label = null)
     * @method Show\Field|Collection acc_robot(string $label = null)
     * @method Show\Field|Collection acc_ship(string $label = null)
     * @method Show\Field|Collection acc_spider(string $label = null)
     * @method Show\Field|Collection binary_version(string $label = null)
     * @method Show\Field|Collection chest1count(string $label = null)
     * @method Show\Field|Collection chest1time(string $label = null)
     * @method Show\Field|Collection chest2count(string $label = null)
     * @method Show\Field|Collection chest2time(string $label = null)
     * @method Show\Field|Collection color1(string $label = null)
     * @method Show\Field|Collection color2(string $label = null)
     * @method Show\Field|Collection creator_points(string $label = null)
     * @method Show\Field|Collection demons(string $label = null)
     * @method Show\Field|Collection diamonds(string $label = null)
     * @method Show\Field|Collection icon_type(string $label = null)
     * @method Show\Field|Collection special(string $label = null)
     * @method Show\Field|Collection user_coins(string $label = null)
     * @method Show\Field|Collection udid(string $label = null)
     * @method Show\Field|Collection uuid(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     */
    class Show
    {
    }

    /**
     */
    class Form
    {
    }

}

namespace Dcat\Admin\Grid {
    /**
     */
    class Column
    {
    }

    /**
     */
    class Filter
    {
    }
}

namespace Dcat\Admin\Show {
    /**
     */
    class Field
    {
    }
}
