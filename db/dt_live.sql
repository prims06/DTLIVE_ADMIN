-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2025 at 04:52 AM
-- Server version: 8.0.43-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clean_dt_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int UNSIGNED NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `user_name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$YyG5x1Pmbtyqviz4MkVg.OBvcRhsCZ8zZOAYoDgpEayq.ZnACU8nO', 1, '2022-04-14 17:28:24', '2025-07-25 08:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_avatar`
--

CREATE TABLE `tbl_avatar` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi	',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner`
--

CREATE TABLE `tbl_banner` (
  `id` int UNSIGNED NOT NULL,
  `is_home_screen` int NOT NULL DEFAULT '1' COMMENT '	1- home screen, 2- other screen	',
  `type_id` int NOT NULL COMMENT 'FK = Type Table',
  `video_type` int NOT NULL DEFAULT '1' COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `subvideo_type` int NOT NULL DEFAULT '0' COMMENT '1- Movies, 2- TVShow',
  `video_id` int NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookmark`
--

CREATE TABLE `tbl_bookmark` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `is_kids_profile` int NOT NULL COMMENT '0- No, 1- Yes',
  `video_type` int NOT NULL DEFAULT '1' COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '	1- Movies, 2- TVShow',
  `video_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cast`
--

CREATE TABLE `tbl_cast` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `personal_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_channel`
--

CREATE TABLE `tbl_channel` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `portrait_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `landscape_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_title` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comment`
--

CREATE TABLE `tbl_comment` (
  `id` int UNSIGNED NOT NULL,
  `comment_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '1- Movies, 2- TVShow',
  `video_id` int NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coupon`
--

CREATE TABLE `tbl_coupon` (
  `id` int UNSIGNED NOT NULL,
  `unique_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount_type` int NOT NULL COMMENT '1- Price, 2- Percentage',
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_use_limit` int NOT NULL DEFAULT '0' COMMENT '0- No,1- Yes',
  `use_limit` int NOT NULL,
  `is_use` int NOT NULL COMMENT '0- Multiple Use, 1- Single Use',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_device_sync`
--

CREATE TABLE `tbl_device_sync` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `device_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `device_type` int NOT NULL DEFAULT '0' COMMENT '	1- Android, 2- iOS, 3- Web, 4- TV	',
  `device_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kids_mode` int NOT NULL DEFAULT '0' COMMENT '0- Off, 1- On',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_device_watching`
--

CREATE TABLE `tbl_device_watching` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_general_setting`
--

CREATE TABLE `tbl_general_setting` (
  `id` int UNSIGNED NOT NULL,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `tbl_general_setting`
--

INSERT INTO `tbl_general_setting` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'DTLive', '2022-08-03 12:38:42', '2024-08-23 11:38:31'),
(2, 'app_version', '1.8', '2022-08-03 12:38:42', '2025-08-29 05:33:44'),
(3, 'app_logo', 'app_logo.png', '2022-08-03 12:38:42', '2025-09-25 04:52:09'),
(4, 'app_desripation', 'Divinetechs is an IT company specializing in providing technology solutions and services. The company likely focuses on various aspects of IT, such as software development, web and mobile app development, IT consulting, cloud solutions, and possibly more. Divinetechs aims to leverage technology to drive business growth and efficiency for its clients, offering innovative and customized solutions tailored to meet specific business needs.', '2022-08-03 12:38:42', '2024-08-28 17:02:20'),
(5, 'author', 'DivineTechs', '2022-08-03 12:38:42', '2024-03-23 05:00:59'),
(6, 'email', 'support@divinetechs.com', '2022-08-03 12:38:42', '2023-07-03 04:57:48'),
(7, 'contact', '917984798190', '2022-08-03 12:38:42', '2023-07-03 04:57:48'),
(8, 'website', 'https://www.divinetechs.com/', '2022-08-03 12:38:42', '2023-07-03 04:57:48'),
(9, 'currency', 'USD', '2022-08-03 12:38:42', '2025-09-17 10:53:43'),
(10, 'currency_code', '$', '2022-08-03 12:38:42', '2025-09-17 10:53:43'),
(11, 'admob_status', '0', '2022-08-03 12:38:42', '2025-08-29 04:52:43'),
(12, 'banner_ad', '0', '2022-08-03 12:38:42', '2025-09-25 04:48:05'),
(13, 'banner_adid', '', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(14, 'interstital_ad', '0', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(15, 'interstital_adid', '', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(16, 'interstital_adclick', '', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(17, 'reward_ad', '0', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(18, 'reward_adid', '', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(19, 'reward_adclick', '', '2022-08-03 12:38:42', '2025-08-29 04:53:54'),
(20, 'ios_banner_ad', '0', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(21, 'ios_banner_adid', '', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(22, 'ios_interstital_ad', '0', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(23, 'ios_interstital_adid', '', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(24, 'ios_interstital_adclick', '', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(25, 'ios_reward_ad', '0', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(26, 'ios_reward_adid', '', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(27, 'ios_reward_adclick', '', '2022-08-03 12:38:42', '2025-08-29 04:54:03'),
(28, 'onesignal_app_id', '', '2022-08-03 12:38:42', '2025-09-25 04:47:23'),
(29, 'onesignal_rest_key', '', '2022-08-03 12:38:42', '2025-09-25 04:47:24'),
(30, 'tmdb_status', '0', '2023-05-30 10:39:32', '2025-09-04 05:45:20'),
(31, 'tmdb_api_key', '', '2024-03-23 05:03:17', '2025-09-25 04:47:26'),
(32, 'auto_play_trailer', '0', '2024-03-23 05:03:17', '2024-09-27 14:42:50'),
(33, 'parent_control_status', '1', '2024-03-23 05:03:54', '2024-05-06 18:26:09'),
(34, 'multiple_device_sync', '0', '2024-03-23 05:03:54', '2024-06-15 09:34:39'),
(35, 'no_of_device_sync', '0', '2024-03-23 05:04:05', '2024-06-15 09:34:39'),
(36, 'subscription_status', '1', '2024-04-18 05:00:25', '2024-04-18 10:33:01'),
(37, 'active_tv_status', '1', '2024-05-06 11:26:43', '2024-05-06 11:26:43'),
(38, 'watchlist_status', '1', '2024-05-06 11:26:43', '2024-05-06 11:26:43'),
(39, 'download_status', '1', '2024-05-06 11:27:00', '2024-10-11 17:18:50'),
(40, 'continue_watching_status', '1', '2024-05-06 11:27:00', '2024-05-06 11:27:00'),
(41, 'coupon_status', '1', '2024-05-06 11:27:16', '2024-05-06 11:27:16'),
(42, 'rent_status', '1', '2024-05-06 11:27:16', '2024-10-11 17:23:48'),
(43, 'on_boarding_screen_status', '1', '2024-05-06 13:13:15', '2024-06-24 11:39:51'),
(44, 'vapid_key', '', '2024-08-12 04:44:05', '2025-09-25 04:47:19'),
(45, 'page_background_color', '#000000', '2024-08-29 08:11:05', '2025-09-17 11:31:04'),
(46, 'page_title_color', '#99e34f', '2024-08-29 08:11:05', '2025-09-17 11:31:27'),
(47, 'app_login', '1', '2025-02-21 11:01:16', '2025-02-21 11:23:41'),
(48, 'app_logo_storage_type', '1', '2025-02-21 11:14:03', '2025-09-02 07:16:29'),
(49, 'panel_login_page_img', '', '2025-03-07 06:57:21', '2025-09-25 04:47:08'),
(50, 'panel_profile_no_img', '', '2025-03-07 06:57:21', '2025-09-25 04:47:09'),
(51, 'panel_normal_no_img', '', '2025-03-07 06:57:21', '2025-09-25 04:47:10'),
(52, 'panel_portrait_no_img', '', '2025-03-07 06:57:21', '2025-09-25 04:47:11'),
(53, 'panel_landscape_no_img', '', '2025-03-07 06:57:21', '2025-09-25 04:47:12'),
(54, 'commission', '10', '2025-03-07 08:45:36', '2025-09-25 04:48:42'),
(55, 'powered_by_image', '', '2025-04-24 11:30:53', '2025-09-25 04:47:16'),
(56, 'screen_recording_status', '0', '2025-08-29 05:57:07', '2025-09-25 04:50:21'),
(57, 'video_player_ima_ads_status', '0', '2025-08-29 06:17:35', '2025-09-22 06:05:30'),
(58, 'min_withdrawal_amount', '100', '2025-09-04 12:19:09', '2025-09-25 04:48:40'),
(59, 'vdocipher_status', '0', '2025-09-05 05:24:23', '2025-09-05 08:38:04'),
(60, 'vdocipher_api_secret_key', '', '2025-09-05 05:24:23', '2025-09-05 08:40:14'),
(61, 'web_client_id', '', '2025-09-05 05:24:23', '2025-09-05 08:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_home_section`
--

CREATE TABLE `tbl_home_section` (
  `id` int UNSIGNED NOT NULL,
  `section_type` int NOT NULL COMMENT '1- Manually ,0- Dynamic',
  `is_home_screen` int NOT NULL COMMENT '1- Home Screen, 2- Other Screen	',
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7-  Kids Content, 8- Shorts, 101- Continue Watching, 102- Channel List, 103- Rent content',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '1- Movies, 2- TVShow',
  `type_id` int NOT NULL COMMENT 'FK = Type Table',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `short_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `screen_layout` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `category_id` int NOT NULL,
  `language_id` int NOT NULL,
  `channel_id` int NOT NULL DEFAULT '0',
  `order_by_upload` int NOT NULL DEFAULT '0' COMMENT '1- ASC, 2- DESC',
  `order_by_view` int NOT NULL DEFAULT '0' COMMENT '1- ASC, 2- DESC',
  `premium_video` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `no_of_content` int NOT NULL DEFAULT '0' COMMENT '0- All',
  `view_all` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_title` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_like`
--

CREATE TABLE `tbl_like` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '1- Movies, 2- TVShow',
  `video_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_configuration`
--

CREATE TABLE `tbl_notification_configuration` (
  `id` int UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `send_notification` int NOT NULL,
  `send_mail` int NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notification_configuration`
--

INSERT INTO `tbl_notification_configuration` (`id`, `type`, `send_notification`, `send_mail`, `status`, `created_at`, `updated_at`) VALUES
(1, 'add_movies', 0, 0, 0, '2025-02-11 10:00:32', '2025-09-25 04:46:21'),
(2, 'add_tvshow', 0, 0, 0, '2025-02-11 10:00:32', '2025-09-25 04:46:21'),
(3, 'add_upcoming_content', 0, 0, 0, '2025-02-11 10:00:58', '2025-09-25 04:46:21'),
(4, 'add_channel_content', 0, 0, 0, '2025-02-11 10:01:07', '2025-09-25 04:46:21'),
(5, 'add_kids_content', 0, 0, 0, '2025-02-11 10:01:17', '2025-09-25 04:46:21'),
(6, 'login', 0, 0, 0, '2025-02-11 10:01:29', '2025-09-25 04:46:21'),
(7, 'package_buy', 0, 0, 0, '2025-02-11 10:01:51', '2025-09-25 04:46:21'),
(8, 'rent_buy', 0, 0, 0, '2025-02-11 10:02:27', '2025-09-25 04:46:21'),
(9, 'shorts', 0, 0, 0, '2025-09-23 12:37:54', '2025-09-25 04:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_onboarding_screen`
--

CREATE TABLE `tbl_onboarding_screen` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_package`
--

CREATE TABLE `tbl_package` (
  `id` int UNSIGNED NOT NULL,
  `package_type` int NOT NULL COMMENT '1- Paid, 2- Free',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `price` double(11,2) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `watch_on_laptop_tv` int NOT NULL,
  `ads_free_content` int NOT NULL,
  `no_of_device_sync` int NOT NULL,
  `android_product_package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ios_product_package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `web_product_package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_package_detail`
--

CREATE TABLE `tbl_package_detail` (
  `id` int UNSIGNED NOT NULL,
  `package_id` int NOT NULL,
  `package_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `package_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page`
--

CREATE TABLE `tbl_page` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_page`
--

INSERT INTO `tbl_page` (`id`, `title`, `description`, `page_subtitle`, `storage_type`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About Us', '', 'Know more about us', 1, '', 1, '2022-09-26 04:31:44', '2025-09-25 04:45:50'),
(2, 'Privacy Policy', '', 'Read our privacy policy', 1, '', 1, '2022-09-26 04:31:44', '2025-09-25 04:45:52'),
(3, 'Terms and Conditions', '', 'Know about terms & conditions', 1, '', 1, '2022-09-26 04:31:44', '2025-09-25 04:45:53'),
(4, 'Refund Policy', '', 'Read our refund policy', 1, '', 1, '2023-01-21 10:21:24', '2025-09-25 04:45:53'),
(5, 'Copy Right Policy', '', 'Copy Right Policy', 1, '', 1, '2025-02-14 05:47:05', '2025-09-25 04:45:54'),
(6, 'Rent Policy', '', 'Rent Policy', 1, '', 0, '2025-02-28 12:58:54', '2025-09-25 04:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_option`
--

CREATE TABLE `tbl_payment_option` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `visibility` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_live` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `key_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `key_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `key_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `key_4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `tbl_payment_option`
--

INSERT INTO `tbl_payment_option` (`id`, `name`, `visibility`, `is_live`, `key_1`, `key_2`, `key_3`, `key_4`, `created_at`, `updated_at`) VALUES
(1, 'inapppurchage', '0', '0', '', '', '', '', '2022-07-29 06:26:54', '2025-08-28 12:59:01'),
(2, 'paypal', '0', '0', '', '', '', '', '2022-07-29 06:26:54', '2025-09-25 04:45:24'),
(3, 'razorpay', '0', '0', '', '', '', '', '2022-07-29 06:27:09', '2025-09-25 04:45:22'),
(4, 'flutterwave', '0', '0', '', '', '', '', '2022-07-29 06:27:09', '2025-09-25 04:45:21'),
(5, 'payumoney', '0', '0', '', '', '', '', '2022-07-29 06:27:17', '2025-09-25 04:45:26'),
(6, 'paytm', '0', '0', '', '', '', '', '2022-07-29 06:27:17', '2025-09-25 04:45:20'),
(7, 'stripe', '0', '0', '', '', '', '', '2023-05-30 10:40:44', '2025-09-25 04:45:19'),
(8, 'cash', '0', '0', '', '', '', '', '2023-07-03 05:38:01', '2025-08-28 12:57:49'),
(9, 'paystack', '0', '0', '', '', '', '', '2023-09-11 12:11:19', '2025-09-25 04:45:18'),
(10, 'instamojo', '0', '0', '', '', '', '', '2023-09-11 12:11:19', '2025-09-25 04:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_producer`
--

CREATE TABLE `tbl_producer` (
  `id` int UNSIGNED NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mobile_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `wallet` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_read_notification`
--

CREATE TABLE `tbl_read_notification` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `notification_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rent_price_list`
--

CREATE TABLE `tbl_rent_price_list` (
  `id` int UNSIGNED NOT NULL,
  `price` double(11,2) NOT NULL,
  `android_product_package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ios_product_package` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `web_price_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rent_transaction`
--

CREATE TABLE `tbl_rent_transaction` (
  `id` int UNSIGNED NOT NULL,
  `unique_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'FK = Coupon Table',
  `user_id` int NOT NULL,
  `producer_id` int NOT NULL,
  `video_type` int NOT NULL COMMENT '1- Video, 2- Show, 3- Category, 4-Language, 5- Upcoming, 6- Channel, 7- Kids, 8- Shorts',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '1- Video, 2- Show',
  `video_id` int NOT NULL,
  `price` int NOT NULL,
  `producer_earning` int NOT NULL,
  `commission` int NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `expiry_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `transaction_status` int NOT NULL COMMENT '1- Processing, 2- Success, 3- Failed',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Expiry, 1- Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_season`
--

CREATE TABLE `tbl_season` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shorts`
--

CREATE TABLE `tbl_shorts` (
  `id` int UNSIGNED NOT NULL,
  `type_id` int NOT NULL DEFAULT '0' COMMENT 'FK = Type Table',
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `producer_id` int NOT NULL DEFAULT '0',
  `category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `language_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cast_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `trailer_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `trailer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external',
  `trailer_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_title` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_comment` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_like` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `total_view` int NOT NULL DEFAULT '0',
  `total_like` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shorts_episode`
--

CREATE TABLE `tbl_shorts_episode` (
  `id` int UNSIGNED NOT NULL,
  `show_id` int NOT NULL,
  `season_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `video_upload_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external',
  `video_320` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_duration` int NOT NULL DEFAULT '0',
  `is_premium` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_title` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `total_view` int NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_smtp_setting`
--

CREATE TABLE `tbl_smtp_setting` (
  `id` int UNSIGNED NOT NULL,
  `protocol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '0- No, 1- Yes',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_smtp_setting`
--

INSERT INTO `tbl_smtp_setting` (`id`, `protocol`, `host`, `port`, `user`, `pass`, `from_name`, `from_email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'smtp123', 'smtp.gmail.com', '587', 'admin@admin.com', 'admin', 'DTLive-Divinetechs', 'admin@admin.com', 0, '2022-08-03 10:14:04', '2025-09-25 04:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_link`
--

CREATE TABLE `tbl_social_link` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_storage_setting`
--

CREATE TABLE `tbl_storage_setting` (
  `id` int UNSIGNED NOT NULL,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_storage_setting`
--

INSERT INTO `tbl_storage_setting` (`id`, `key`, `value`, `status`, `created_at`, `updated_at`) VALUES
(1, 'storage_type', '1', 1, '2025-09-01 12:07:53', '2025-09-24 11:54:39'),
(2, 's3_access_key', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:38'),
(3, 's3_secret_key', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:39'),
(4, 's3_region', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:40'),
(5, 's3_bucket_name', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:41'),
(6, 's3_endpoint', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:42'),
(7, 'wasabi_access_key', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:43'),
(8, 'wasabi_secret_key', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:44'),
(9, 'wasabi_region', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:45'),
(10, 'wasabi_bucket_name', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:45'),
(11, 'wasabi_endpoint', '', 1, '2025-09-01 12:07:53', '2025-09-25 04:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `id` int UNSIGNED NOT NULL,
  `unique_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'FK = Coupon Table	',
  `user_id` int NOT NULL,
  `package_id` int NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `expiry_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `transaction_status` int NOT NULL COMMENT '1- Processing, 2- Success, 3- Failed',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Expiry, 1- Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tv_login`
--

CREATE TABLE `tbl_tv_login` (
  `id` int UNSIGNED NOT NULL,
  `unique_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tv_show`
--

CREATE TABLE `tbl_tv_show` (
  `id` int UNSIGNED NOT NULL,
  `type_id` int NOT NULL DEFAULT '0' COMMENT 'FK = Type Table',
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `channel_id` int NOT NULL DEFAULT '0',
  `producer_id` int NOT NULL DEFAULT '0',
  `category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `language_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cast_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `landscape` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `trailer_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `trailer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external, youtube	',
  `trailer_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `release_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_title` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_comment` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_like` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_rent` int NOT NULL COMMENT '0- No, 1- Yes',
  `price` int NOT NULL DEFAULT '0',
  `rent_day` int NOT NULL DEFAULT '0' COMMENT '1 to 30 Day',
  `total_view` int NOT NULL DEFAULT '0',
  `total_like` int NOT NULL DEFAULT '0',
  `status` int NOT NULL COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tv_show_video`
--

CREATE TABLE `tbl_tv_show_video` (
  `id` int UNSIGNED NOT NULL,
  `show_id` int NOT NULL,
  `season_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `landscape` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `video_upload_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external, youtube, vdocipher_id',
  `video_320` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_480` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_720` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_1080` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_duration` int NOT NULL DEFAULT '0',
  `subtitle_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `subtitle_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external	',
  `subtitle_lang_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_lang_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_lang_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_premium` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_title` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_download` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `total_view` int NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_type`
--

CREATE TABLE `tbl_type` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `tbl_type`
--

INSERT INTO `tbl_type` (`id`, `name`, `type`, `storage_type`, `icon`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Movies', 1, 1, '', 1, 1, '2025-09-25 04:41:10', '2025-09-25 04:41:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int UNSIGNED NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mobile_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image_type` int NOT NULL COMMENT '1- File Upload, 2- Avatar',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` int NOT NULL DEFAULT '0' COMMENT '1- OTP, 2- Google, 3- Apple, 4- Normal	',
  `parent_control_status` int NOT NULL DEFAULT '0',
  `parent_control_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_video`
--

CREATE TABLE `tbl_video` (
  `id` int UNSIGNED NOT NULL,
  `type_id` int NOT NULL COMMENT 'FK = Type Table',
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `channel_id` int NOT NULL DEFAULT '0',
  `producer_id` int NOT NULL DEFAULT '0',
  `category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `language_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cast_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `landscape` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `video_upload_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external, youtube, live_stream_url,vdocipher_id',
  `video_320` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_480` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_720` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_1080` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `video_duration` int NOT NULL DEFAULT '0',
  `trailer_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `trailer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external, youtube	',
  `trailer_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_storage_type` int NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `subtitle_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'server_video, external	',
  `subtitle_lang_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_lang_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_lang_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subtitle_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `release_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_premium` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_title` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_download` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_comment` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_like` int NOT NULL DEFAULT '0' COMMENT '0- No, 1- Yes',
  `is_rent` int NOT NULL COMMENT '0- No, 1- Yes',
  `price` int NOT NULL DEFAULT '0',
  `rent_day` int NOT NULL DEFAULT '0' COMMENT '1 to 30 Day',
  `total_view` int NOT NULL DEFAULT '0',
  `total_like` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_video_watch`
--

CREATE TABLE `tbl_video_watch` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `is_kids_profile` int NOT NULL COMMENT '	0- No, 1- Yes',
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '1- Movies, 2- TVShow',
  `video_id` int NOT NULL,
  `episode_id` int NOT NULL DEFAULT '0',
  `stop_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_view`
--

CREATE TABLE `tbl_view` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `video_type` int NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int NOT NULL DEFAULT '0' COMMENT '1- Movies, 2- TVShow, 3- Episode',
  `video_id` int NOT NULL,
  `episode_id` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_withdrawal_request`
--

CREATE TABLE `tbl_withdrawal_request` (
  `id` int UNSIGNED NOT NULL,
  `producer_id` int NOT NULL,
  `price` int NOT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0- Pending, 1- Completed',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_avatar`
--
ALTER TABLE `tbl_avatar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_bookmark`
--
ALTER TABLE `tbl_bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cast`
--
ALTER TABLE `tbl_cast`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_channel`
--
ALTER TABLE `tbl_channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_coupon`
--
ALTER TABLE `tbl_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_device_sync`
--
ALTER TABLE `tbl_device_sync`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_device_watching`
--
ALTER TABLE `tbl_device_watching`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_home_section`
--
ALTER TABLE `tbl_home_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_like`
--
ALTER TABLE `tbl_like`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notification_configuration`
--
ALTER TABLE `tbl_notification_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_onboarding_screen`
--
ALTER TABLE `tbl_onboarding_screen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_package`
--
ALTER TABLE `tbl_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_package_detail`
--
ALTER TABLE `tbl_package_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page`
--
ALTER TABLE `tbl_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment_option`
--
ALTER TABLE `tbl_payment_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_producer`
--
ALTER TABLE `tbl_producer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_read_notification`
--
ALTER TABLE `tbl_read_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rent_price_list`
--
ALTER TABLE `tbl_rent_price_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rent_transaction`
--
ALTER TABLE `tbl_rent_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_season`
--
ALTER TABLE `tbl_season`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shorts`
--
ALTER TABLE `tbl_shorts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shorts_episode`
--
ALTER TABLE `tbl_shorts_episode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_smtp_setting`
--
ALTER TABLE `tbl_smtp_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_social_link`
--
ALTER TABLE `tbl_social_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_storage_setting`
--
ALTER TABLE `tbl_storage_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tv_login`
--
ALTER TABLE `tbl_tv_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tv_show`
--
ALTER TABLE `tbl_tv_show`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tv_show_video`
--
ALTER TABLE `tbl_tv_show_video`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_type`
--
ALTER TABLE `tbl_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_video`
--
ALTER TABLE `tbl_video`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_video_watch`
--
ALTER TABLE `tbl_video_watch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_view`
--
ALTER TABLE `tbl_view`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_withdrawal_request`
--
ALTER TABLE `tbl_withdrawal_request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_avatar`
--
ALTER TABLE `tbl_avatar`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_bookmark`
--
ALTER TABLE `tbl_bookmark`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_cast`
--
ALTER TABLE `tbl_cast`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_channel`
--
ALTER TABLE `tbl_channel`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_coupon`
--
ALTER TABLE `tbl_coupon`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_device_sync`
--
ALTER TABLE `tbl_device_sync`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_device_watching`
--
ALTER TABLE `tbl_device_watching`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `tbl_home_section`
--
ALTER TABLE `tbl_home_section`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_like`
--
ALTER TABLE `tbl_like`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notification_configuration`
--
ALTER TABLE `tbl_notification_configuration`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_onboarding_screen`
--
ALTER TABLE `tbl_onboarding_screen`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_package`
--
ALTER TABLE `tbl_package`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_package_detail`
--
ALTER TABLE `tbl_package_detail`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_page`
--
ALTER TABLE `tbl_page`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_payment_option`
--
ALTER TABLE `tbl_payment_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_producer`
--
ALTER TABLE `tbl_producer`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_read_notification`
--
ALTER TABLE `tbl_read_notification`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rent_price_list`
--
ALTER TABLE `tbl_rent_price_list`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rent_transaction`
--
ALTER TABLE `tbl_rent_transaction`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_season`
--
ALTER TABLE `tbl_season`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_shorts`
--
ALTER TABLE `tbl_shorts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_shorts_episode`
--
ALTER TABLE `tbl_shorts_episode`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_smtp_setting`
--
ALTER TABLE `tbl_smtp_setting`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_social_link`
--
ALTER TABLE `tbl_social_link`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_storage_setting`
--
ALTER TABLE `tbl_storage_setting`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_tv_login`
--
ALTER TABLE `tbl_tv_login`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_tv_show`
--
ALTER TABLE `tbl_tv_show`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_tv_show_video`
--
ALTER TABLE `tbl_tv_show_video`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_type`
--
ALTER TABLE `tbl_type`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_video`
--
ALTER TABLE `tbl_video`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_video_watch`
--
ALTER TABLE `tbl_video_watch`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_view`
--
ALTER TABLE `tbl_view`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_withdrawal_request`
--
ALTER TABLE `tbl_withdrawal_request`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
