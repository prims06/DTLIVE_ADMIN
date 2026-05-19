-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 19 mai 2026 à 05:07
-- Version du serveur : 11.8.6-MariaDB-log
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u966142760_dtlive`
--

-- --------------------------------------------------------

--
-- Structure de la table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `user_name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'idrisskamdoum007@gmail.com', '$2y$10$XWd.2Mjro0b23k6ycmh7jOq1xvzJBh.GW59CmxvYIKURwoMhw1cU.', 1, '2026-04-03 11:33:45', '2026-04-03 11:33:45');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_avatar`
--

CREATE TABLE `tbl_avatar` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi	',
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_banner`
--

CREATE TABLE `tbl_banner` (
  `id` int(10) UNSIGNED NOT NULL,
  `is_home_screen` int(11) NOT NULL DEFAULT 1 COMMENT '	1- home screen, 2- other screen	',
  `type_id` int(11) NOT NULL COMMENT 'FK = Type Table',
  `video_type` int(11) NOT NULL DEFAULT 1 COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `subvideo_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Movies, 2- TVShow',
  `video_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_banner`
--

INSERT INTO `tbl_banner` (`id`, `is_home_screen`, `type_id`, `video_type`, `subvideo_type`, `video_id`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 1, 0, 1, '2026-04-05 17:30:19', '2026-04-05 17:30:19');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_bookmark`
--

CREATE TABLE `tbl_bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_kids_profile` int(11) NOT NULL COMMENT '0- No, 1- Yes',
  `video_type` int(11) NOT NULL DEFAULT 1 COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '	1- Movies, 2- TVShow',
  `video_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_bookmark`
--

INSERT INTO `tbl_bookmark` (`id`, `user_id`, `is_kids_profile`, `video_type`, `sub_video_type`, `video_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 0, 1, 1, '2026-04-05 14:34:00', '2026-04-05 14:34:00');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_cast`
--

CREATE TABLE `tbl_cast` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `personal_info` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `name`, `storage_type`, `image`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Science-Fiction', 1, '', 1, 1, '2026-04-05 12:40:55', '2026-04-05 12:40:55'),
(2, 'Fantastique', 1, '', 1, 1, '2026-04-05 12:41:11', '2026-04-05 12:41:11'),
(3, 'Aventure', 1, '', 1, 1, '2026-04-05 12:41:20', '2026-04-05 12:41:20');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_channel`
--

CREATE TABLE `tbl_channel` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `portrait_img` varchar(255) NOT NULL,
  `landscape_img` varchar(255) NOT NULL,
  `is_title` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_comment`
--

CREATE TABLE `tbl_comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Movies, 2- TVShow',
  `video_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_coupon`
--

CREATE TABLE `tbl_coupon` (
  `id` int(10) UNSIGNED NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount_type` int(11) NOT NULL COMMENT '1- Price, 2- Percentage',
  `price` varchar(255) NOT NULL,
  `is_use_limit` int(11) NOT NULL DEFAULT 0 COMMENT '0- No,1- Yes',
  `use_limit` int(11) NOT NULL,
  `is_use` int(11) NOT NULL COMMENT '0- Multiple Use, 1- Single Use',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_device_sync`
--

CREATE TABLE `tbl_device_sync` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_type` int(11) NOT NULL DEFAULT 0 COMMENT '	1- Android, 2- iOS, 3- Web, 4- TV	',
  `device_token` varchar(255) NOT NULL,
  `kids_mode` int(11) NOT NULL DEFAULT 0 COMMENT '0- Off, 1- On',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tbl_device_sync`
--

INSERT INTO `tbl_device_sync` (`id`, `user_id`, `device_name`, `device_id`, `device_type`, `device_token`, `kids_mode`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'google cheetah', '59dccf5609700715779c68f6eb75329a9130f5b6180096d5575313333fa7c02d', 1, 'ekyO7VuETFKkTK9LVihuAD:APA91bEiJoNiHhvYv4zJG-ZZ1aYmFrm_6cuzCkArqytqAfczCIId_kpImuIPCrJTjvlAIcWLP4YTw3YZJRDlfIkpFxEAE3oJIz_JOX-gHvHEXQi7iQDl5tw', 0, 1, '2026-04-05 07:05:57', '2026-04-05 07:05:57');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_device_watching`
--

CREATE TABLE `tbl_device_watching` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_general_setting`
--

CREATE TABLE `tbl_general_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` text NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_general_setting`
--

INSERT INTO `tbl_general_setting` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'BiTv', '2022-08-03 12:38:42', '2026-04-04 22:18:34'),
(2, 'app_version', '1.8', '2022-08-03 12:38:42', '2025-08-29 05:33:44'),
(3, 'app_logo', 'app_logo.png', '2022-08-03 12:38:42', '2025-09-25 04:52:09'),
(4, 'app_desripation', 'Divinetechs is an IT company specializing in providing technology solutions and services. The company likely focuses on various aspects of IT, such as software development, web and mobile app development, IT consulting, cloud solutions, and possibly more. Divinetechs aims to leverage technology to drive business growth and efficiency for its clients, offering innovative and customized solutions tailored to meet specific business needs.', '2022-08-03 12:38:42', '2024-08-28 17:02:20'),
(5, 'author', 'DevStack', '2022-08-03 12:38:42', '2026-04-04 22:18:34'),
(6, 'email', 'primsidriss@gmail.com', '2022-08-03 12:38:42', '2026-04-04 22:18:34'),
(7, 'contact', '+237658359950', '2022-08-03 12:38:42', '2026-04-04 22:18:34'),
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
(28, 'onesignal_app_id', 'ebcd8bb8-3a92-4b67-bf3f-583cab45c50d', '2022-08-03 12:38:42', '2026-04-05 18:02:25'),
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
(44, 'vapid_key', 'BFHz7uvrR-SHXFx2BdeskP4PDptYkMVNwqRmT5bYUGmq7a3iRS7Vti-_cXdmzxM1dUHFTIwT_d5OFMNYG00cSCs', '2024-08-12 04:44:05', '2026-04-04 22:15:47'),
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
(61, 'web_client_id', '898692241446-ccolvavis8347l57v93u1agdjbtin0ur.apps.googleusercontent.com', '2025-09-05 05:24:23', '2026-04-04 22:15:39');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_home_section`
--

CREATE TABLE `tbl_home_section` (
  `id` int(10) UNSIGNED NOT NULL,
  `section_type` int(11) NOT NULL COMMENT '1- Manually ,0- Dynamic',
  `is_home_screen` int(11) NOT NULL COMMENT '1- Home Screen, 2- Other Screen	',
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7-  Kids Content, 8- Shorts, 101- Continue Watching, 102- Channel List, 103- Rent content',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Movies, 2- TVShow',
  `type_id` int(11) NOT NULL COMMENT 'FK = Type Table',
  `title` varchar(255) NOT NULL,
  `short_title` varchar(255) NOT NULL,
  `screen_layout` varchar(255) NOT NULL,
  `content_ids` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL DEFAULT 0,
  `order_by_upload` int(11) NOT NULL DEFAULT 0 COMMENT '1- ASC, 2- DESC',
  `order_by_view` int(11) NOT NULL DEFAULT 0 COMMENT '1- ASC, 2- DESC',
  `premium_video` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `no_of_content` int(11) NOT NULL DEFAULT 0 COMMENT '0- All',
  `view_all` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_title` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_language`
--

INSERT INTO `tbl_language` (`id`, `name`, `storage_type`, `image`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Français', 1, 'lang_2026_04_05_69d20a6f9eff4.png', 1, 1, '2026-04-05 12:38:31', '2026-04-05 12:38:31'),
(2, 'English', 1, 'lang_2026_04_05_69d20a9158587.png', 1, 1, '2026-04-05 12:39:05', '2026-04-05 12:39:05');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_like`
--

CREATE TABLE `tbl_like` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Movies, 2- TVShow',
  `video_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_notification_configuration`
--

CREATE TABLE `tbl_notification_configuration` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `send_notification` int(11) NOT NULL,
  `send_mail` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tbl_notification_configuration`
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
-- Structure de la table `tbl_onboarding_screen`
--

CREATE TABLE `tbl_onboarding_screen` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tbl_onboarding_screen`
--

INSERT INTO `tbl_onboarding_screen` (`id`, `title`, `storage_type`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Welcome on board', 1, 'on_board_2026_04_04_69d1411b77bdb.jpg', 'Let\'s get started your journey', 1, '2026-04-04 22:19:31', '2026-04-04 22:19:31');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_package`
--

CREATE TABLE `tbl_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `package_type` int(11) NOT NULL COMMENT '1- Paid, 2- Free',
  `name` varchar(255) NOT NULL,
  `price` double(11,2) NOT NULL,
  `type` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `watch_on_laptop_tv` int(11) NOT NULL,
  `ads_free_content` int(11) NOT NULL,
  `no_of_device_sync` int(11) NOT NULL,
  `android_product_package` varchar(255) NOT NULL,
  `ios_product_package` varchar(255) NOT NULL,
  `web_product_package` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_package_detail`
--

CREATE TABLE `tbl_package_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `package_id` int(11) NOT NULL,
  `package_key` text NOT NULL,
  `package_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_page`
--

CREATE TABLE `tbl_page` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `page_subtitle` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `icon` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tbl_page`
--

INSERT INTO `tbl_page` (`id`, `title`, `description`, `page_subtitle`, `storage_type`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About Us', '', 'Know more about us', 1, '', 1, '2022-09-26 04:31:44', '2026-04-04 06:59:45'),
(2, 'Privacy Policy', '', 'Read our privacy policy', 1, '', 1, '2022-09-26 04:31:44', '2025-09-25 04:45:52'),
(3, 'Terms and Conditions', '', 'Know about terms & conditions', 1, '', 1, '2022-09-26 04:31:44', '2025-09-25 04:45:53'),
(4, 'Refund Policy', '', 'Read our refund policy', 1, '', 1, '2023-01-21 10:21:24', '2025-09-25 04:45:53'),
(5, 'Copy Right Policy', '', 'Copy Right Policy', 1, '', 1, '2025-02-14 05:47:05', '2025-09-25 04:45:54'),
(6, 'Rent Policy', '', 'Rent Policy', 1, '', 1, '2025-02-28 12:58:54', '2026-04-04 06:59:48');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_payment_option`
--

CREATE TABLE `tbl_payment_option` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `visibility` varchar(255) NOT NULL,
  `is_live` varchar(255) NOT NULL,
  `key_1` varchar(255) NOT NULL,
  `key_2` varchar(255) NOT NULL,
  `key_3` varchar(255) NOT NULL,
  `key_4` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_payment_option`
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
-- Structure de la table `tbl_producer`
--

CREATE TABLE `tbl_producer` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `wallet` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_read_notification`
--

CREATE TABLE `tbl_read_notification` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_rent_price_list`
--

CREATE TABLE `tbl_rent_price_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `price` double(11,2) NOT NULL,
  `android_product_package` varchar(255) NOT NULL,
  `ios_product_package` varchar(255) NOT NULL,
  `web_price_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_rent_transaction`
--

CREATE TABLE `tbl_rent_transaction` (
  `id` int(10) UNSIGNED NOT NULL,
  `unique_id` varchar(255) NOT NULL DEFAULT '' COMMENT 'FK = Coupon Table',
  `user_id` int(11) NOT NULL,
  `producer_id` int(11) NOT NULL,
  `video_type` int(11) NOT NULL COMMENT '1- Video, 2- Show, 3- Category, 4-Language, 5- Upcoming, 6- Channel, 7- Kids, 8- Shorts',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Video, 2- Show',
  `video_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `producer_earning` int(11) NOT NULL,
  `commission` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `expiry_date` varchar(255) NOT NULL,
  `transaction_status` int(11) NOT NULL COMMENT '1- Processing, 2- Success, 3- Failed',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Expiry, 1- Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_season`
--

CREATE TABLE `tbl_season` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_season`
--

INSERT INTO `tbl_season` (`id`, `name`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Prison Break Season 1', 0, 1, '2026-04-05 17:31:09', '2026-04-05 17:31:09');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shorts`
--

CREATE TABLE `tbl_shorts` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT 0 COMMENT 'FK = Type Table',
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `producer_id` int(11) NOT NULL DEFAULT 0,
  `category_id` text NOT NULL,
  `language_id` text NOT NULL,
  `cast_id` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) NOT NULL,
  `trailer_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `trailer_type` varchar(255) NOT NULL COMMENT 'server_video, external',
  `trailer_url` text NOT NULL,
  `description` text NOT NULL,
  `is_title` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_comment` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_like` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `total_view` int(11) NOT NULL DEFAULT 0,
  `total_like` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shorts_episode`
--

CREATE TABLE `tbl_shorts_episode` (
  `id` int(10) UNSIGNED NOT NULL,
  `show_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `video_upload_type` varchar(255) NOT NULL COMMENT 'server_video, external',
  `video_320` varchar(255) NOT NULL,
  `video_duration` int(11) NOT NULL DEFAULT 0,
  `is_premium` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_title` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `total_view` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_smtp_setting`
--

CREATE TABLE `tbl_smtp_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `protocol` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- No, 1- Yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tbl_smtp_setting`
--

INSERT INTO `tbl_smtp_setting` (`id`, `protocol`, `host`, `port`, `user`, `pass`, `from_name`, `from_email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'smtp123', 'smtp.gmail.com', '587', 'admin@admin.com', 'admin', 'DTLive-Divinetechs', 'admin@admin.com', 0, '2022-08-03 10:14:04', '2025-09-25 04:43:32');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_social_link`
--

CREATE TABLE `tbl_social_link` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_storage_setting`
--

CREATE TABLE `tbl_storage_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` text NOT NULL,
  `value` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tbl_storage_setting`
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
-- Structure de la table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `id` int(10) UNSIGNED NOT NULL,
  `unique_id` varchar(255) NOT NULL COMMENT 'FK = Coupon Table	',
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `expiry_date` varchar(255) NOT NULL,
  `transaction_status` int(11) NOT NULL COMMENT '1- Processing, 2- Success, 3- Failed',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Expiry, 1- Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_tv_login`
--

CREATE TABLE `tbl_tv_login` (
  `id` int(10) UNSIGNED NOT NULL,
  `unique_code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_tv_show`
--

CREATE TABLE `tbl_tv_show` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT 0 COMMENT 'FK = Type Table',
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `channel_id` int(11) NOT NULL DEFAULT 0,
  `producer_id` int(11) NOT NULL DEFAULT 0,
  `category_id` text NOT NULL,
  `language_id` text NOT NULL,
  `cast_id` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(100) NOT NULL,
  `landscape` varchar(100) NOT NULL,
  `trailer_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `trailer_type` varchar(255) NOT NULL COMMENT 'server_video, external, youtube	',
  `trailer_url` text NOT NULL,
  `description` text NOT NULL,
  `release_date` varchar(255) NOT NULL,
  `is_title` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_comment` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_like` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_rent` int(11) NOT NULL COMMENT '0- No, 1- Yes',
  `price` int(11) NOT NULL DEFAULT 0,
  `rent_day` int(11) NOT NULL DEFAULT 0 COMMENT '1 to 30 Day',
  `total_view` int(11) NOT NULL DEFAULT 0,
  `total_like` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_tv_show_video`
--

CREATE TABLE `tbl_tv_show_video` (
  `id` int(10) UNSIGNED NOT NULL,
  `show_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) NOT NULL,
  `landscape` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `video_upload_type` varchar(255) NOT NULL COMMENT 'server_video, external, youtube, vdocipher_id',
  `video_320` varchar(255) NOT NULL,
  `video_480` varchar(255) NOT NULL,
  `video_720` varchar(255) NOT NULL,
  `video_1080` varchar(255) NOT NULL,
  `video_extension` varchar(255) NOT NULL,
  `video_duration` int(11) NOT NULL DEFAULT 0,
  `subtitle_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `subtitle_type` varchar(255) NOT NULL COMMENT 'server_video, external	',
  `subtitle_lang_1` varchar(255) NOT NULL,
  `subtitle_1` varchar(255) NOT NULL,
  `subtitle_lang_2` varchar(255) NOT NULL,
  `subtitle_2` varchar(255) NOT NULL,
  `subtitle_lang_3` varchar(255) NOT NULL,
  `subtitle_3` varchar(255) NOT NULL,
  `is_premium` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_title` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_download` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `total_view` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_type`
--

CREATE TABLE `tbl_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `icon` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_type`
--

INSERT INTO `tbl_type` (`id`, `name`, `type`, `storage_type`, `icon`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Movies', 1, 1, '', 1, 1, '2025-09-25 04:41:10', '2025-09-25 04:41:46');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `image_type` int(11) NOT NULL COMMENT '1- File Upload, 2- Avatar',
  `image` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '1- OTP, 2- Google, 3- Apple, 4- Normal	',
  `parent_control_status` int(11) NOT NULL DEFAULT 0,
  `parent_control_password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `user_name`, `full_name`, `email`, `password`, `mobile_number`, `storage_type`, `image_type`, `image`, `type`, `parent_control_status`, `parent_control_password`, `status`, `created_at`, `updated_at`) VALUES
(1, '@user_primsidriss828', 'Idriss Kamdoum', 'primsidriss@gmail.com', '', '', 1, 1, 'user_2026_04_05_69d1bc7d2bb77.png', 2, 1, '0890', 1, '2026-04-05 01:35:57', '2026-04-08 22:55:52');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_video`
--

CREATE TABLE `tbl_video` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(11) NOT NULL COMMENT 'FK = Type Table',
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `channel_id` int(11) NOT NULL DEFAULT 0,
  `producer_id` int(11) NOT NULL DEFAULT 0,
  `category_id` text NOT NULL,
  `language_id` text NOT NULL,
  `cast_id` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `thumbnail` varchar(255) NOT NULL,
  `landscape` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `video_upload_type` varchar(255) NOT NULL COMMENT 'server_video, external, youtube, live_stream_url,vdocipher_id',
  `video_320` varchar(255) NOT NULL,
  `video_480` varchar(255) NOT NULL,
  `video_720` varchar(255) NOT NULL,
  `video_1080` varchar(255) NOT NULL,
  `video_extension` varchar(255) NOT NULL,
  `video_duration` int(11) NOT NULL DEFAULT 0,
  `trailer_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `trailer_type` varchar(255) NOT NULL COMMENT 'server_video, external, youtube	',
  `trailer_url` varchar(255) NOT NULL,
  `subtitle_storage_type` int(11) NOT NULL COMMENT '1- Local, 2- AWS S3, 3- Wasabi',
  `subtitle_type` varchar(255) NOT NULL COMMENT 'server_video, external	',
  `subtitle_lang_1` varchar(255) NOT NULL,
  `subtitle_1` varchar(255) NOT NULL,
  `subtitle_lang_2` varchar(255) NOT NULL,
  `subtitle_2` varchar(255) NOT NULL,
  `subtitle_lang_3` varchar(255) NOT NULL,
  `subtitle_3` varchar(255) NOT NULL,
  `release_date` varchar(255) NOT NULL,
  `is_premium` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_title` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_download` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_comment` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_like` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `is_rent` int(11) NOT NULL COMMENT '0- No, 1- Yes',
  `price` int(11) NOT NULL DEFAULT 0,
  `rent_day` int(11) NOT NULL DEFAULT 0 COMMENT '1 to 30 Day',
  `total_view` int(11) NOT NULL DEFAULT 0,
  `total_like` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Hide, 1- Show',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_video`
--

INSERT INTO `tbl_video` (`id`, `type_id`, `video_type`, `channel_id`, `producer_id`, `category_id`, `language_id`, `cast_id`, `name`, `storage_type`, `thumbnail`, `landscape`, `description`, `video_storage_type`, `video_upload_type`, `video_320`, `video_480`, `video_720`, `video_1080`, `video_extension`, `video_duration`, `trailer_storage_type`, `trailer_type`, `trailer_url`, `subtitle_storage_type`, `subtitle_type`, `subtitle_lang_1`, `subtitle_1`, `subtitle_lang_2`, `subtitle_2`, `subtitle_lang_3`, `subtitle_3`, `release_date`, `is_premium`, `is_title`, `is_download`, `is_comment`, `is_like`, `is_rent`, `price`, `rent_day`, `total_view`, `total_like`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 0, '1,2,3', '1,2', '', 'Avatar : De feu et de cendres', 1, 'vid_2026_04_05_69d20caf7d00d.webp', 'vid_2026_04_05_69d20caf7d50c.jpg', 'Après la mort de Neteyam, Jake et Neytiri affrontent leur chagrin tout en faisant face au Peuple des Cendres, une tribu Na’vi redoutable menée par la fougueuse Varang, alors que le conflit sur Pandora s’intensifie et qu’une nouvelle quête morale s’amorce.', 1, 'external', 'https://u14.vidzy.live/v/06/00043/8cj4ppuibcfi_o/Avatar.Fire.and.Ash.2025.H264.AAC.mp4?t=YwJ5O4fROOX0xKGKFgZuv1jpNunWmVxkKKjZwJ0N1Wo&s=1775373248&e=172800&f=218043&sp=1000&i=0.0', 'https://v6.vidzy.live/v/01/00042/xv4ekdqo99td_n/Beauty.in.black.s02e14.multi.1080p.web.x264-higgsboson.mp4', 'https://u14.vidzy.live/v/07/00043/sxszudhn09y6_o/avatar3.mp4?t=ginFIjDezgubwPhp2icoHHEoF4VOrCT7jY0ROV6rhTw&s=1775373220&e=172800&f=217242&sp=1000&i=0.0', 'https://u14.vidzy.live/v/06/00043/jtwfhxry1ti3_n/Avatar.Fire.and.Ash.2025.VOSTFR.1080p.WEB.H264-SUPPLY.mp4?t=CaYPxwov4xonLfV3mBTlSZjiLogXlugq4vNoUVhpaGM&s=1775373294&e=172800&f=216784&sp=1000&i=0.0', '0', 11880000, 1, 'youtube', 'https://youtu.be/nb_fFj_0rq8', 1, 'server_video', '', '', '', '', '', '', '', 0, 0, 0, 1, 1, 0, 0, 0, 1, 0, 1, '2026-04-05 12:48:07', '2026-04-12 13:11:22');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_video_watch`
--

CREATE TABLE `tbl_video_watch` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_kids_profile` int(11) NOT NULL COMMENT '	0- No, 1- Yes',
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Movies, 2- TVShow',
  `video_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `stop_time` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `tbl_video_watch`
--

INSERT INTO `tbl_video_watch` (`id`, `user_id`, `is_kids_profile`, `video_type`, `sub_video_type`, `video_id`, `episode_id`, `stop_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 0, 1, 0, '2190496', 1, '2026-04-05 12:54:16', '2026-04-05 14:30:15');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_view`
--

CREATE TABLE `tbl_view` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_type` int(11) NOT NULL COMMENT '1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts',
  `sub_video_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Movies, 2- TVShow, 3- Episode',
  `video_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tbl_view`
--

INSERT INTO `tbl_view` (`id`, `user_id`, `video_type`, `sub_video_type`, `video_id`, `episode_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 1, 0, 1, '2026-04-05 12:49:11', '2026-04-05 12:49:11');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_withdrawal_request`
--

CREATE TABLE `tbl_withdrawal_request` (
  `id` int(10) UNSIGNED NOT NULL,
  `producer_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0- Pending, 1- Completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_avatar`
--
ALTER TABLE `tbl_avatar`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_bookmark`
--
ALTER TABLE `tbl_bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_cast`
--
ALTER TABLE `tbl_cast`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_channel`
--
ALTER TABLE `tbl_channel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_coupon`
--
ALTER TABLE `tbl_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_device_sync`
--
ALTER TABLE `tbl_device_sync`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_device_watching`
--
ALTER TABLE `tbl_device_watching`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_home_section`
--
ALTER TABLE `tbl_home_section`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_like`
--
ALTER TABLE `tbl_like`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_notification_configuration`
--
ALTER TABLE `tbl_notification_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_onboarding_screen`
--
ALTER TABLE `tbl_onboarding_screen`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_package`
--
ALTER TABLE `tbl_package`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_package_detail`
--
ALTER TABLE `tbl_package_detail`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_page`
--
ALTER TABLE `tbl_page`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_payment_option`
--
ALTER TABLE `tbl_payment_option`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_producer`
--
ALTER TABLE `tbl_producer`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_read_notification`
--
ALTER TABLE `tbl_read_notification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_rent_price_list`
--
ALTER TABLE `tbl_rent_price_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_rent_transaction`
--
ALTER TABLE `tbl_rent_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_season`
--
ALTER TABLE `tbl_season`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_shorts`
--
ALTER TABLE `tbl_shorts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_shorts_episode`
--
ALTER TABLE `tbl_shorts_episode`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_smtp_setting`
--
ALTER TABLE `tbl_smtp_setting`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_social_link`
--
ALTER TABLE `tbl_social_link`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_storage_setting`
--
ALTER TABLE `tbl_storage_setting`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_tv_login`
--
ALTER TABLE `tbl_tv_login`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_tv_show`
--
ALTER TABLE `tbl_tv_show`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_tv_show_video`
--
ALTER TABLE `tbl_tv_show_video`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_type`
--
ALTER TABLE `tbl_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_video`
--
ALTER TABLE `tbl_video`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_video_watch`
--
ALTER TABLE `tbl_video_watch`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_view`
--
ALTER TABLE `tbl_view`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_withdrawal_request`
--
ALTER TABLE `tbl_withdrawal_request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_avatar`
--
ALTER TABLE `tbl_avatar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_bookmark`
--
ALTER TABLE `tbl_bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_cast`
--
ALTER TABLE `tbl_cast`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tbl_channel`
--
ALTER TABLE `tbl_channel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_coupon`
--
ALTER TABLE `tbl_coupon`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_device_sync`
--
ALTER TABLE `tbl_device_sync`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_device_watching`
--
ALTER TABLE `tbl_device_watching`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `tbl_home_section`
--
ALTER TABLE `tbl_home_section`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `tbl_like`
--
ALTER TABLE `tbl_like`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tbl_notification_configuration`
--
ALTER TABLE `tbl_notification_configuration`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `tbl_onboarding_screen`
--
ALTER TABLE `tbl_onboarding_screen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_package`
--
ALTER TABLE `tbl_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_package_detail`
--
ALTER TABLE `tbl_package_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_page`
--
ALTER TABLE `tbl_page`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `tbl_payment_option`
--
ALTER TABLE `tbl_payment_option`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `tbl_producer`
--
ALTER TABLE `tbl_producer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_read_notification`
--
ALTER TABLE `tbl_read_notification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_rent_price_list`
--
ALTER TABLE `tbl_rent_price_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_rent_transaction`
--
ALTER TABLE `tbl_rent_transaction`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_season`
--
ALTER TABLE `tbl_season`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_shorts`
--
ALTER TABLE `tbl_shorts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_shorts_episode`
--
ALTER TABLE `tbl_shorts_episode`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_smtp_setting`
--
ALTER TABLE `tbl_smtp_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_social_link`
--
ALTER TABLE `tbl_social_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_storage_setting`
--
ALTER TABLE `tbl_storage_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_tv_login`
--
ALTER TABLE `tbl_tv_login`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_tv_show`
--
ALTER TABLE `tbl_tv_show`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_tv_show_video`
--
ALTER TABLE `tbl_tv_show_video`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_type`
--
ALTER TABLE `tbl_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_video`
--
ALTER TABLE `tbl_video`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_video_watch`
--
ALTER TABLE `tbl_video_watch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_view`
--
ALTER TABLE `tbl_view`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_withdrawal_request`
--
ALTER TABLE `tbl_withdrawal_request`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
