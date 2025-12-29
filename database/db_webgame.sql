-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 29, 2025 lúc 06:33 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_webgame`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `media_type` varchar(255) NOT NULL DEFAULT 'image',
  `video_path` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `type` enum('slider','sidebar') NOT NULL DEFAULT 'slider',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `media_type`, `video_path`, `description`, `link`, `order`, `type`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Chào mừng đến ShopGame', NULL, 'image', NULL, 'Khám phá hàng ngàn game và phần mềm chất lượng cao', NULL, 0, 'slider', 1, 0, '2025-12-26 07:55:39', '2025-12-26 12:00:38'),
(2, 'Giảm giá lên đến 70%', NULL, 'image', NULL, 'Săn sale ngay hôm nay', NULL, 0, 'slider', 2, 0, '2025-12-26 07:55:39', '2025-12-26 12:00:45'),
(3, 'Giáng sinh 2025', NULL, 'video', 'banners/videos/LSeV8rjgIz15SQkzdqvVnQFkM7K7c56b9WjJ3Lmv.webm', 'Giảm giá game nhân dịp Giáng sinh!!!', NULL, 0, 'slider', 0, 1, '2025-12-26 12:01:27', '2025-12-27 01:25:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `session_id`, `game_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(8, 4, NULL, 4, 1, 199000.00, '2025-12-27 04:34:30', '2025-12-27 04:34:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Hành động', 'hanh-dong', '2025-12-26 07:55:39', '2025-12-26 07:55:39'),
(2, 'RPG', 'rpg', '2025-12-26 07:55:39', '2025-12-26 07:55:39'),
(3, 'Phiêu lưu', 'phieu-luu', '2025-12-26 07:55:39', '2025-12-26 07:55:39'),
(4, 'Chiến thuật', 'chien-thuat', '2025-12-26 07:55:39', '2025-12-26 07:55:39'),
(5, 'Thể thao', 'the-thao', '2025-12-26 07:55:39', '2025-12-26 07:55:39'),
(6, 'Đua xe', 'dua-xe', '2025-12-26 07:55:39', '2025-12-26 07:55:39'),
(7, 'Nấu ăn', 'cook', '2025-12-28 05:56:00', '2025-12-28 05:56:00'),
(8, 'Kinh dị', 'kinh-di', '2025-12-28 05:56:13', '2025-12-28 05:56:13'),
(9, 'Trang trại', 'trang-trai', '2025-12-28 06:12:58', '2025-12-28 06:12:58'),
(10, 'Moba', 'moba', '2025-12-28 06:38:00', '2025-12-28 06:38:00'),
(11, 'Bắn súng', 'ban-sung', '2025-12-28 07:14:49', '2025-12-28 07:14:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_sale` decimal(10,2) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `system_requirements` text DEFAULT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `games`
--

INSERT INTO `games` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `price_sale`, `thumbnail`, `images`, `system_requirements`, `developer`, `is_active`, `is_free`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cyberpunk 2077', 'cyberpunk-2077', 'Game nhập vai thế giới mở trong tương lai đầy cyberpunk', 1299000.00, 649000.00, 'games/thumbnails/GqYvbhHiTyKxrB1iicyVpPKysLn9t5xyxJWSMvAR.jpg', '[]', NULL, 'CD Projekt Red', 1, 0, '2025-12-26 07:55:39', '2025-12-28 10:49:36'),
(2, 2, 'The Witcher 3', 'the-witcher-3', 'Hành trình của thợ săn quái vật Geralt of Rivia', 599000.00, 299000.00, 'games/thumbnails/iPO4K80VsFpQTtqR16Y6t1046YvDJyzJj2x2aqSu.jpg', '[]', NULL, 'CD Projekt Red', 1, 0, '2025-12-26 07:55:39', '2025-12-26 10:54:55'),
(3, 2, 'Elden Ring', 'elden-ring', 'Thế giới fantasy khổng lồ từ FromSoftware', 1299000.00, 899000.00, 'games/thumbnails/s0LJq0paXPvwGhBf47Zb9hKxj3NkoQmmCQOSgTSN.png', '[]', NULL, 'FromSoftware', 1, 0, '2025-12-26 07:55:39', '2025-12-26 10:55:43'),
(4, 1, 'GTA V', 'gta-v', 'GTA 5 (Grand Theft Auto V) là một trò chơi hành động phiêu lưu thế giới mở cực kỳ nổi tiếng, nơi người chơi khám phá thành phố ảo Los Santos (dựa trên Los Angeles), thực hiện nhiệm vụ, cướp bóc, lái xe và nhiều hoạt động tội phạm khác, xoay quanh câu chuyện của ba nhân vật chính: Michael, Franklin và Trevor. Game có gameplay tự do, cốt truyện hấp dẫn, đồ họa sống động và cả phiên bản online GTA Online cho phép chơi nhiều người.', 499000.00, 199000.00, 'games/thumbnails/7hXsMLhgi4L2RjYnGgdqnvb6Rk6QATEAv1XGMgkw.jpg', '[]', NULL, 'Rockstar Games', 1, 0, '2025-12-26 07:55:39', '2025-12-28 11:48:19'),
(5, 2, 'Baldur\'s Gate 3', 'baldurs-gate-3', 'RPG chiến thuật lượt với câu chuyện sâu sắc', 1299000.00, NULL, 'games/thumbnails/E0xqmzWxbnomNI5WIGwRIsKzjjiD7G2l2N71AGlo.jpg', '[]', NULL, 'Larian Studios', 1, 0, '2025-12-26 07:55:39', '2025-12-26 10:56:43'),
(6, 2, 'Starfield', 'starfield', 'Khám phá vũ trụ trong RPG khoa học viễn tưởng', 1499000.00, NULL, 'games/thumbnails/LzubyyBpUXAp0wTeraRRbmC72pemzkHPQBlzSz7C.jpg', '[]', NULL, 'Bethesda Game Studios', 1, 0, '2025-12-26 07:55:39', '2025-12-26 10:57:46'),
(7, 3, 'Hogwarts Legacy', 'hogwarts-legacy', 'Trải nghiệm thế giới phép thuật Harry Potter', 999000.00, NULL, 'games/thumbnails/IPcpehnzECpwkQRdl77JWjm3FBG6vV0yQcwY4qIp.jpg', '[]', NULL, 'Avalanche Software', 1, 0, '2025-12-26 07:55:39', '2025-12-26 10:58:17'),
(8, 1, 'Resident Evil 4', 'resident-evil-4', 'Phiên bản remake của tựa game kinh dị huyền thoại', 899000.00, NULL, 'games/thumbnails/YGG7ZskeMxF5VmK48F2D6apVokElw4pX4RaxlSAd.jpg', '[]', NULL, 'Capcom', 1, 0, '2025-12-26 07:55:39', '2025-12-26 10:58:44'),
(9, 3, 'Minecraft', 'mine-craft', 'Minecraft là một trò chơi điện tử dạng thế giới mở (sandbox) cực kỳ nổi tiếng, nơi người chơi có thể khám phá, thu thập tài nguyên, chế tạo vật phẩm và xây dựng mọi thứ, từ những công trình đơn giản đến phức tạp, bằng các khối lập phương 3D trong một thế giới ảo vô tận, có nhiều chế độ chơi (sinh tồn, sáng tạo,...) và được phát triển bởi Mojang (thuộc Microsoft).', 299000.00, 219000.00, 'games/thumbnails/RCLFagapoRXT4wZc30MzKJ7044wd9yKRAsCmyw6X.png', NULL, NULL, 'Mojang', 1, 0, '2025-12-26 11:01:12', '2025-12-26 11:01:12'),
(10, 2, 'GTA Vice City', 'gta-vice-city', 'GTA Vice City (tên đầy đủ: Grand Theft Auto: Vice City) là một trò chơi điện tử hành động phiêu lưu thế giới mở kinh điển của Rockstar Games, lấy bối cảnh thành phố hư cấu Vice City (dựa trên Miami) những năm 1980, nơi người chơi vào vai Tommy Vercetti để xây dựng đế chế tội phạm, nổi bật với âm nhạc thập niên 80, yếu tố bạo lực và lối chơi tự do khám phá.', 100000.00, NULL, 'games/thumbnails/JBEqYuP5tiqZnkJQhbZ88DPHVfRPeOnVeCjQUAlz.jpg', '[]', NULL, 'Rockstar Games', 1, 0, '2025-12-27 05:18:42', '2025-12-27 05:20:47'),
(11, 9, 'Hay Day', 'hay-day', 'Hay Day là một trò chơi nông trại mô phỏng miễn phí, cực kỳ phổ biến trên di động (iOS & Android) do Supercell phát triển, nơi người chơi vào vai nông dân trồng trọt, chăn nuôi, chế biến và buôn bán sản phẩm để xây dựng và trang trí nông trại mơ ước, đồng thời kết nối với bạn bè. Tên game kết hợp từ \"hay\" (cỏ khô) và \"heyday\" (thời kỳ hoàng kim), thể hiện sự thư giãn và phát triển thịnh vượng.', 3290000.00, NULL, 'games/thumbnails/Oy5p8ocqyAQ1KYi3IegP5HdF60Z1wIMMvryShpGV.jpg', '[]', NULL, 'Supercell', 1, 0, '2025-12-27 06:13:45', '2025-12-28 06:13:13'),
(12, 7, 'Overcooked 2', 'Over-cooked-2', 'Overcooked 2 là một tựa game mô phỏng nấu ăn hợp tác siêu vui nhộn, nơi bạn và tối đa 3 người chơi khác cùng nhau phối hợp trong những căn bếp hỗn loạn để chuẩn bị món ăn cho khách hàng và giải cứu thế giới khỏi sự đói khát của The Unbread. Điểm đặc biệt là lối chơi nhanh, đề cao teamwork, cho phép ném nguyên liệu (thay vì chỉ để bàn) và có nhiều màn chơi đa dạng, từ xe bán hàng di động, mỏ, đến trên biển.', 300000.00, NULL, 'games/thumbnails/M4C0Suu73YrPp1zxCjRQIHSd1D4M53iJd1yjYmPE.jpg', NULL, NULL, 'Ghost Town Games Ltd', 1, 0, '2025-12-28 06:04:24', '2025-12-28 06:04:24'),
(13, 7, 'Overcooked 1', 'Over-cooked-1', 'Overcooked 1 là trò chơi mô phỏng nấu ăn hợp tác, hỗn loạn ra mắt năm 2016, nơi người chơi điều khiển các đầu bếp để chuẩn bị món ăn trong các căn bếp đầy chướng ngại vật, đòi hỏi phối hợp cực kỳ ăn ý để hoàn thành đơn hàng trong thời gian giới hạn, giải cứu thế giới khỏi ngày tận thế bởi Bóng Thịt khổng lồ (Onion King).', 178000.00, NULL, 'games/thumbnails/ojp5kfzccz9i8yBVyyhHJkxr9nKB4JZB0GxteotH.jpg', NULL, NULL, 'Ghost Town Games Ltd.', 1, 0, '2025-12-28 06:06:50', '2025-12-28 06:06:50'),
(14, 8, 'Outlast', 'out-last', 'Outlast có thể là tựa game kinh dị sinh tồn nổi tiếng của nhà phát triển Red Barrels, hoặc chất liệu vải ứng dụng công nghệ điều hòa nhiệt độ cơ thể, hoặc đơn giản là từ tiếng Anh nghĩa là \"tồn tại lâu hơn, bền bỉ hơn\". Trong game, Outlast nổi tiếng với lối chơi góc nhìn thứ nhất, không có khả năng chiến đấu, tập trung vào chạy trốn và khám phá các bệnh viện tâm thần rùng rợn.', 150000.00, NULL, 'games/thumbnails/tI5DnDRWVIpog1Bft2Vnh1UhnKXzscm9L48sYy5Z.jpg', NULL, NULL, 'Red Barrels', 1, 0, '2025-12-28 06:08:48', '2025-12-28 06:08:48'),
(15, 8, 'Left 4 Dead 2', 'l-4-d-2', 'Left 4 Dead 2 (L4D2) là một trò chơi bắn súng góc nhìn thứ nhất (FPS) kinh dị phối hợp, lấy bối cảnh ngày tận thế zombie, do Valve phát triển, nơi người chơi cùng nhóm 3 người khác (hoặc AI) phải chiến đấu chống lại các bầy zombie khổng lồ, các zombie đặc biệt và tìm đường sống sót qua các chiến dịch ở Savannah, New Orleans, từ các thành phố đến đầm lầy, với nhiều vũ khí cận chiến và hơn thế nữa.', 142000.00, NULL, 'games/thumbnails/91QQgYxQbumqkNziUKA3X3KsMCpar45uJ5CW935S.png', NULL, NULL, 'Valve', 1, 0, '2025-12-28 06:11:59', '2025-12-28 06:11:59'),
(16, 6, 'Forza Horizon 5', 'f-h-5', 'Forza Horizon 5 (FH5) là một trò chơi đua xe thế giới mở (open-world) cực kỳ thành công, lấy bối cảnh tại Mexico giả tưởng, nổi bật với đồ họa đẹp mắt, thế giới rộng lớn đa dạng (sa mạc, rừng rậm, thành phố) và lối chơi tự do hấp dẫn, cho phép người chơi khám phá, tham gia các cuộc đua, thử thách tốc độ với hàng trăm siêu xe, nhận được đánh giá cao và đạt nhiều giải thưởng lớn.', 990000.00, NULL, 'games/thumbnails/c3FCz5yVD3C7iK7FHXu0pELzexUqAWDEgROg6N7P.jpg', NULL, NULL, 'Playground Games', 1, 0, '2025-12-28 06:16:20', '2025-12-28 06:16:20'),
(17, 10, 'Liên Quân (Arena of Valor)', 'lien-quan', 'Liên Quân (Arena of Valor) là một trò chơi chiến thuật đấu trường (MOBA) nhiều người chơi trên di động, do <Garena phát hành, <Tencent phát triển và <ra mắt vào cuối năm 2016. Người chơi điều khiển các vị tướng có kỹ năng riêng biệt, phối hợp với đồng đội để phá hủy nhà chính đối phương, đòi hỏi sự nhạy bén, tư duy chiến thuật và phản xạ nhanh.', 0.00, NULL, 'games/thumbnails/qXvwPgmvsmAh4wKdL3gGxni6h6qF0e6C1f0oHsji.jpg', '[]', NULL, 'Tencent', 1, 1, '2025-12-28 06:49:50', '2025-12-28 06:52:16'),
(18, 10, 'League of Legends - LoL', 'l-o-l', 'Liên Minh Huyền Thoại (LMHT, tiếng Anh: League of Legends - LoL) là một game chiến thuật đấu trường trực tuyến nhiều người chơi (MOBA) miễn phí, nơi 2 đội 5 tướng đối đầu nhau để phá hủy Nhà Chính của đối phương, nổi tiếng với hơn 140+ tướng độc đáo và tính cạnh tranh cao, trở thành một trong những môn thể thao điện tử (esports) lớn nhất thế giới.', 0.00, NULL, 'games/thumbnails/nadhrvgRS5HXho46uoJdEVHFHEUV2wiX1wOC73nf.jpg', '[]', NULL, 'Riot', 1, 1, '2025-12-28 07:03:34', '2025-12-28 10:06:13'),
(19, 10, 'Dota 2', 'Dota-2', 'Dota 2 là một game MOBA (Đấu trường trực tuyến nhiều người chơi) miễn phí, nơi hai đội 5 người đối đầu nhau, mỗi người điều khiển một \"Tướng\" (Hero) độc đáo, với mục tiêu phá hủy công trình \"Ancient\" của đối phương trong khi bảo vệ công trình của mình, đòi hỏi chiến thuật sâu sắc, kỹ năng cá nhân và sự phối hợp đồng đội để giành chiến chiến thắng.', 0.00, NULL, 'games/thumbnails/DlqSwlFsF6bbRNmkRmngbdxyvTdosU1dYIJISJEc.jpg', '[]', NULL, 'Valve Corporation', 1, 1, '2025-12-28 07:05:34', '2025-12-28 10:06:42'),
(20, 4, 'Age of Empires (Đế Chế)', 'a-o-e', 'AOE (viết tắt của Age of Empires) là dòng game chiến thuật thời gian thực (RTS) kinh điển, ở Việt Nam còn gọi thân thuộc là \"Đế Chế\". Người chơi điều khiển một nền văn minh cổ đại, phát triển từ thời Tiền sử lên các thời đại sau, thu thập tài nguyên, xây dựng đế chế, phát triển công nghệ và quân đội để chiến đấu, mở rộng bờ cõi.', 450000.00, NULL, 'games/thumbnails/u8Z4A7KRTyeSGp3k3Is2kZM22lxxocH9e8rwdb3d.jpg', NULL, NULL, 'World\'s Edge', 1, 0, '2025-12-28 07:12:25', '2025-12-28 07:12:25'),
(21, 11, 'Crossfire (CF)', 'c-f', 'Crossfire (CF), hay còn gọi là Đột Kích tại Việt Nam, là một game bắn súng góc nhìn thứ nhất (FPS) trực tuyến nổi tiếng, nơi người chơi tham gia các trận đấu súng kịch tính giữa hai phe phái Global Risk và Black List, với nhiều chế độ chơi đa dạng như Đặt bom, Đấu đội, Zombie, và kho vũ khí phong phú.', 0.00, NULL, 'games/thumbnails/wz6sE8L4dZubyk8LaRLzjxEtkgeawunxiSsBDA3c.jpg', '[]', NULL, 'Smilegate Entertainment', 1, 1, '2025-12-28 07:14:41', '2025-12-28 07:14:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `game_keys`
--

CREATE TABLE `game_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `key_code` varchar(255) NOT NULL,
  `status` enum('available','sold','reserved') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `game_keys`
--

INSERT INTO `game_keys` (`id`, `game_id`, `key_code`, `status`, `created_at`, `updated_at`) VALUES
(3, 9, 'MINE-6610-FD44-E728', 'available', '2025-12-27 07:47:52', '2025-12-27 08:37:14'),
(4, 9, 'MINE-C7AA-A00A-BB4A', 'available', '2025-12-27 08:04:19', '2025-12-27 08:37:06'),
(5, 9, 'MINE-70EA-96EC-6EAB', 'sold', '2025-12-27 08:07:25', '2025-12-27 08:07:25'),
(6, 4, 'GTAV-643D-47B0-2EEB', 'sold', '2025-12-27 08:22:20', '2025-12-28 09:43:49'),
(7, 2, 'THEW-8ACC-C184-47B0', 'sold', '2025-12-27 08:37:34', '2025-12-27 08:37:34'),
(8, 18, 'LEAG-8952-BB4D-C003', 'sold', '2025-12-28 09:33:31', '2025-12-28 09:33:31'),
(9, 18, 'LEAG-BC3E-D55B-8852', 'sold', '2025-12-28 09:34:00', '2025-12-28 09:34:00'),
(10, 19, 'DOTA-7D1C-89B5-4098', 'sold', '2025-12-28 21:12:21', '2025-12-28 21:12:21'),
(11, 3, 'ELDE-D5F1-0FAB-83A6', 'reserved', '2025-12-28 21:12:21', '2025-12-28 21:12:21'),
(12, 2, 'THEW-69A3-5AFE-A792', 'reserved', '2025-12-28 21:12:21', '2025-12-28 21:12:21'),
(13, 6, 'STAR-36CB-8E94-4C4B', 'reserved', '2025-12-28 21:12:21', '2025-12-28 21:12:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `invoices`
--

INSERT INTO `invoices` (`id`, `order_id`, `invoice_number`, `total_amount`, `issued_at`, `created_at`, `updated_at`) VALUES
(1, 9, 'INV-20251227-000009', 240900.00, '2025-12-27 07:47:52', '2025-12-27 07:47:52', '2025-12-27 07:47:52'),
(2, 10, 'INV-20251227-000010', 240900.00, '2025-12-27 08:04:19', '2025-12-27 08:04:19', '2025-12-27 08:04:19'),
(3, 11, 'INV-20251227-000011', 240900.00, '2025-12-27 08:05:31', '2025-12-27 08:05:31', '2025-12-27 08:05:31'),
(4, 12, 'INV-20251227-000012', 240900.00, '2025-12-27 08:07:25', '2025-12-27 08:07:25', '2025-12-27 08:07:25'),
(5, 13, 'INV-20251227-000013', 218900.00, '2025-12-27 08:22:20', '2025-12-27 08:22:20', '2025-12-27 08:22:20'),
(6, 14, 'INV-20251227-000014', 328900.00, '2025-12-27 08:37:34', '2025-12-27 08:37:34', '2025-12-27 08:37:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `libraries`
--

CREATE TABLE `libraries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `purchased_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `libraries`
--

INSERT INTO `libraries` (`id`, `user_id`, `game_id`, `order_id`, `purchased_at`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 17, '2025-12-28 09:43:49', '2025-12-28 09:43:49', '2025-12-28 09:43:49'),
(2, 2, 9, 12, '2025-12-27 08:07:25', '2025-12-28 09:57:23', '2025-12-28 09:57:23'),
(3, 5, 18, 15, '2025-12-28 09:33:31', '2025-12-28 09:57:23', '2025-12-28 09:57:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` text DEFAULT NULL,
  `message` text NOT NULL,
  `is_from_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `subject`, `message`, `is_from_admin`, `is_read`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'test123', 0, 1, 'open', '2025-12-28 08:55:13', '2025-12-28 08:55:46'),
(2, 5, NULL, 'test123', 0, 1, 'open', '2025-12-28 08:55:13', '2025-12-28 08:55:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_26_144354_create_all_app_tables', 1),
(5, '2025_12_26_150520_add_is_admin_to_users_table', 2),
(6, '2025_12_26_180614_create_cart_items_table', 3),
(7, '2025_12_26_185121_add_media_fields_to_banners_table', 4),
(8, '2025_12_27_092227_create_sales_table', 5),
(9, '2025_12_28_124447_add_is_active_to_users_table', 6),
(10, '2025_12_28_134123_add_is_free_to_games_table', 7),
(11, '2025_12_28_134133_add_is_free_to_games_table', 7),
(12, '2025_12_28_120000_update_messages_table_add_chat_flags', 8),
(13, '2025_12_28_120001_make_messages_subject_nullable', 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(9, 2, 240900.00, 'bank_transfer', 'cancelled', '2025-12-27 07:47:52', '2025-12-27 08:05:11'),
(10, 2, 240900.00, 'bank_transfer', 'cancelled', '2025-12-27 08:04:19', '2025-12-27 08:37:06'),
(11, 2, 240900.00, 'bank_transfer', 'cancelled', '2025-12-27 08:05:31', '2025-12-27 08:37:14'),
(12, 2, 240900.00, 'bank_transfer', 'paid', '2025-12-27 08:07:25', '2025-12-27 08:13:32'),
(13, 2, 218900.00, 'bank_transfer', 'cancelled', '2025-12-27 08:22:20', '2025-12-27 08:37:19'),
(14, 2, 328900.00, 'bank_transfer', 'pending', '2025-12-27 08:37:34', '2025-12-27 08:37:34'),
(15, 5, 0.00, 'bank_transfer', 'paid', '2025-12-28 09:33:31', '2025-12-28 09:33:31'),
(16, 5, 0.00, 'bank_transfer', 'paid', '2025-12-28 09:34:00', '2025-12-28 09:34:00'),
(17, 5, 218900.00, 'bank_transfer', 'paid', '2025-12-28 09:43:17', '2025-12-28 09:43:49'),
(18, 2, 2966700.00, 'bank_transfer', 'pending', '2025-12-28 21:12:21', '2025-12-28 21:12:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `game_key_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `game_id`, `game_key_id`, `price`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 9, 9, 3, 219000.00, 1, '2025-12-27 07:47:52', '2025-12-27 07:47:52'),
(4, 10, 9, 4, 219000.00, 1, '2025-12-27 08:04:19', '2025-12-27 08:04:19'),
(5, 11, 9, 3, 219000.00, 1, '2025-12-27 08:05:31', '2025-12-27 08:05:31'),
(6, 12, 9, 5, 219000.00, 1, '2025-12-27 08:07:25', '2025-12-27 08:07:25'),
(7, 13, 4, 6, 199000.00, 1, '2025-12-27 08:22:20', '2025-12-27 08:22:20'),
(8, 14, 2, 7, 299000.00, 1, '2025-12-27 08:37:34', '2025-12-27 08:37:34'),
(9, 15, 18, 8, 0.00, 1, '2025-12-28 09:33:31', '2025-12-28 09:33:31'),
(10, 16, 18, 9, 0.00, 1, '2025-12-28 09:34:00', '2025-12-28 09:34:00'),
(11, 17, 4, 6, 199000.00, 1, '2025-12-28 09:43:17', '2025-12-28 09:43:17'),
(12, 18, 19, 10, 0.00, 1, '2025-12-28 21:12:21', '2025-12-28 21:12:21'),
(13, 18, 3, 11, 899000.00, 1, '2025-12-28 21:12:21', '2025-12-28 21:12:21'),
(14, 18, 2, 12, 299000.00, 1, '2025-12-28 21:12:21', '2025-12-28 21:12:21'),
(15, 18, 6, 13, 1499000.00, 1, '2025-12-28 21:12:21', '2025-12-28 21:12:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `game_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 5, 'Game chơi rất vui', '2025-12-28 21:22:34', '2025-12-28 21:22:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `game_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_percent` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sales`
--

INSERT INTO `sales` (`id`, `name`, `description`, `game_id`, `category_id`, `discount_percent`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Auto Sale: Cyberpunk 2077', 'Nhập từ giá sale cũ của game', 1, NULL, 50, '2025-12-27 09:50:06', '2026-01-26 09:50:06', 1, '2025-12-27 02:50:06', '2025-12-27 02:50:06'),
(2, 'Auto Sale: The Witcher 3', 'Nhập từ giá sale cũ của game', 2, NULL, 50, '2025-12-27 09:50:06', '2026-01-26 09:50:06', 1, '2025-12-27 02:50:06', '2025-12-27 02:50:06'),
(3, 'Auto Sale: Elden Ring', 'Nhập từ giá sale cũ của game', 3, NULL, 31, '2025-12-27 09:50:06', '2026-01-26 09:50:06', 1, '2025-12-27 02:50:06', '2025-12-27 02:50:06'),
(4, 'Auto Sale: GTA V', 'Nhập từ giá sale cũ của game', 4, NULL, 60, '2025-12-27 09:50:06', '2026-01-26 09:50:06', 1, '2025-12-27 02:50:06', '2025-12-27 02:50:06'),
(5, 'Auto Sale: Minecraft', 'Nhập từ giá sale cũ của game', 9, NULL, 27, '2025-12-27 09:50:00', '2026-01-26 09:50:00', 1, '2025-12-27 02:50:06', '2025-12-27 02:50:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6XHl1EfMp0UYBkN1Oq30ZviUYxkWNPF2NlsCkepR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.1 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOU5zcElTaXJxWWtiV3ZXMXZHMG5pSUxXQTFnYlhaelg3dllXa0tQMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766932493),
('gFdozFwGU0kLmg8ODheXR9aKwfppQ9lcgagunnYV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.1 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFk3ZU83RmpudmZqSU9aODdHRkh5ZFBFNWp1VEl1YlladFhDbkVkUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWQ9MTUyOGIyNmMtMjVhZS00OGU4LWIxNGUtNDcxNjkwOWFjYTkwJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY2OTMyNDU5NDg0IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766932459),
('GrKuRp19VXuGD3hYk5aawMcOwso5FMdw2RqQYjpU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.1 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0h5VkR6OFJvM1lIQzMwV1dMMlBMaVp1cmx2UGp1RzhDamZlNk1EdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWQ9OWMyMzdiYzktNjUxMC00ZDU2LTg0MTAtYTE2MjQwMGI3OWFmJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY2OTMyMjYyODAzIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766932263),
('NhyTFWchhWYXPW6gzUAEnzHcZcYFhyov8Y0w2UND', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWmY3R3M5SENUdVJvUEh5SDBKV2RyS0lsTmgyRTFLNFpJU2NYNndtOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbWVzc2FnZXMiO3M6NToicm91dGUiO3M6MTY6ImFwaS5tZXNzYWdlcy5nZXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1766932658),
('xC9COr9rTsZnBYU2X1Od3DpKzSm23RGWHZXd1DiJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.107.1 Chrome/142.0.7444.175 Electron/39.2.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFR3aTUxZjJvazBqbTlraU93T1BZSVVYVUdLTGZKVXRxRXVaRzVPMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWQ9MTUyOGIyNmMtMjVhZS00OGU4LWIxNGUtNDcxNjkwOWFjYTkwJnZzY29kZUJyb3dzZXJSZXFJZD0xNzY2OTMyNTAwNTU5IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1766932500);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@shopgame.com', '2025-12-26 07:55:39', '$2y$12$juHiUfwH5r7p6AWzTngRQuS.Yy2480Y7GFcJUB01Hw/pRpdN5K4YC', 1, 1, 'xOTJfvwRpq', '2025-12-26 07:55:39', '2025-12-28 05:53:50'),
(2, 'Nguyễn Minh Khởi', 'khoiminh.071204@gmail.com', NULL, '$2y$12$3mMnpQnMauzSvjNVHOh8Fe5UahhvVTMwueGaz4QGieMjsNe1UGR4e', 1, 1, NULL, '2025-12-26 07:59:31', '2025-12-26 07:59:31'),
(4, 'Đặng Trần Khánh Linh', 'bbiswt@gmail.com', NULL, '$2y$12$vvhwVeni//6Crh235pUtNufNTM1uGk63X1gSapTrZfITqI/JTgXAK', 0, 1, NULL, '2025-12-26 08:15:24', '2025-12-28 05:43:16'),
(5, 'user1', 'us1@gmail.com', NULL, '$2y$12$pcTHu9Amv9gtkUOzebNS6eJQ84.NvR595FNdL3IrExVVf4jJqXylq', 0, 1, NULL, '2025-12-28 07:36:13', '2025-12-28 07:36:13');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_game_id_foreign` (`game_id`),
  ADD KEY `cart_items_user_id_game_id_index` (`user_id`,`game_id`),
  ADD KEY `cart_items_session_id_index` (`session_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `games_slug_unique` (`slug`),
  ADD KEY `games_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `game_keys`
--
ALTER TABLE `game_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `game_keys_key_code_unique` (`key_code`),
  ADD KEY `game_keys_game_id_foreign` (`game_id`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `libraries`
--
ALTER TABLE `libraries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libraries_user_id_game_id_unique` (`user_id`,`game_id`),
  ADD KEY `libraries_game_id_foreign` (`game_id`),
  ADD KEY `libraries_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_game_id_foreign` (`game_id`),
  ADD KEY `order_items_game_key_id_foreign` (`game_key_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_game_id_unique` (`user_id`,`game_id`),
  ADD KEY `reviews_game_id_foreign` (`game_id`);

--
-- Chỉ mục cho bảng `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_game_id_foreign` (`game_id`),
  ADD KEY `sales_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `game_keys`
--
ALTER TABLE `game_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `libraries`
--
ALTER TABLE `libraries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `game_keys`
--
ALTER TABLE `game_keys`
  ADD CONSTRAINT `game_keys_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `libraries`
--
ALTER TABLE `libraries`
  ADD CONSTRAINT `libraries_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `libraries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `libraries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_game_key_id_foreign` FOREIGN KEY (`game_key_id`) REFERENCES `game_keys` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
