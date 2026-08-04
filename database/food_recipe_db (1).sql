-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 24, 2026 lúc 02:52 PM
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
-- Cơ sở dữ liệu: `food_recipe_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `recipe_id`, `created_at`) VALUES
(1, 2, 11, '2026-01-25 08:00:00'),
(2, 2, 14, '2026-02-18 10:00:00'),
(3, 2, 19, '2026-03-18 03:00:00'),
(4, 3, 10, '2026-01-20 04:00:00'),
(5, 3, 14, '2026-02-19 04:00:00'),
(6, 3, 17, '2026-03-08 07:00:00'),
(7, 4, 12, '2026-02-05 02:00:00'),
(8, 4, 16, '2026-03-05 02:00:00'),
(9, 4, 19, '2026-03-18 03:00:00'),
(10, 5, 10, '2026-01-22 03:00:00'),
(11, 5, 15, '2026-02-26 07:00:00'),
(12, 5, 14, '2026-02-20 05:00:00'),
(13, 6, 11, '2026-01-27 04:00:00'),
(14, 6, 18, '2026-03-12 04:00:00'),
(15, 7, 12, '2026-02-06 03:00:00'),
(16, 7, 15, '2026-02-25 05:00:00'),
(17, 7, 14, '2026-02-21 03:00:00'),
(18, 8, 13, '2026-02-13 08:00:00'),
(19, 8, 19, '2026-03-19 04:00:00'),
(20, 1, 19, '2026-05-17 18:27:28'),
(26, 9, 21, '2026-05-22 11:10:34'),
(27, 9, 14, '2026-05-23 18:54:14'),
(28, 2, 65, '2026-05-24 11:54:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT 'fa fa-utensils',
  `color` varchar(20) DEFAULT '#FCD34D',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `icon`, `color`, `display_order`, `created_at`) VALUES
(1, 'Breakfast & Brunch', 'breakfast-brunch', 'Start your day right with delicious breakfast recipes', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 1, '2026-04-11 17:42:54'),
(2, 'Lunch', 'lunch', 'Quick and satisfying lunch ideas', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 2, '2026-04-11 17:42:54'),
(3, 'Dinner', 'dinner', 'Hearty dinner recipes for the family', 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 3, '2026-04-11 17:42:54'),
(4, 'Appetizers & Snacks', 'appetizers-snacks', 'Perfect starters and snack ideas', 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 4, '2026-04-11 17:42:54'),
(5, 'Desserts', 'desserts', 'Sweet treats and desserts', 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 5, '2026-04-11 17:42:54'),
(6, 'Drinks & Cocktails', 'drinks-cocktails', 'Refreshing beverages and cocktails', 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 6, '2026-04-11 17:42:54'),
(7, 'Side Dishes', 'side-dishes', 'Perfect accompaniments to any meal', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 7, '2026-04-11 17:42:54'),
(8, 'Quick & Easy', 'quick-easy', 'Fast, simple recipes for busy weeknights', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 0, '2026-05-22 18:55:56'),
(9, 'Asian Flavors', 'asian-flavors', 'Explore delicious Asian noodle dishes and more', 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 0, '2026-05-22 18:55:56'),
(10, 'Comfort Food', 'comfort-food', 'Rich, cozy dishes that wrap you in warmth', 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 0, '2026-05-22 18:55:56'),
(11, 'Soups', 'soups', 'Warming soups for every season', 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&h=500&fit=crop', 'fa fa-utensils', '#FCD34D', 0, '2026-05-22 18:55:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT 'For nested comments/replies',
  `comment_text` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `recipe_id`, `user_id`, `parent_id`, `comment_text`, `is_approved`, `created_at`, `updated_at`) VALUES
(21, 27, 9, NULL, 'ngon lắm like like', 1, '2026-05-23 10:10:08', '2026-05-23 10:10:08'),
(22, 27, 9, 21, 'ngon', 1, '2026-05-23 10:10:23', '2026-05-23 10:10:23'),
(23, 27, 9, NULL, 'bạn có nhận dịch vụ qua nấu không', 1, '2026-05-23 18:09:59', '2026-05-23 18:09:59'),
(24, 30, 9, NULL, 'em có muốn làm vợ anh không', 1, '2026-05-23 18:11:07', '2026-05-23 18:11:07'),
(257, 10, 5, NULL, 'Wow, món này nhìn healthy quá, rất hợp với thực đơn giảm cân của mình.', 1, '2026-05-12 13:45:36', '2026-05-23 18:45:36'),
(258, 10, 8, NULL, 'Công thức tuyệt vời quá, mình làm thử hôm qua cả nhà ai cũng khen ngon! Cảm ơn bạn đã chia sẻ nha.', 1, '2026-04-24 13:45:36', '2026-05-23 18:45:36'),
(259, 11, 5, NULL, 'Món này ăn kèm với cơm trắng hay bún thì hợp hơn vậy mọi người?', 1, '2026-05-01 13:45:36', '2026-05-23 18:45:36'),
(260, 11, 6, 259, 'Mình thấy ăn với cơm nóng là chuẩn bài nhất, hao cơm cực kỳ!', 1, '2026-05-23 13:45:36', '2026-05-23 18:45:36'),
(261, 11, 8, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-05-13 13:45:36', '2026-05-23 18:45:36'),
(262, 12, 5, NULL, 'Bày trí đẹp như nhà hàng 5 sao vậy, ngưỡng mộ quá!', 1, '2026-05-05 13:45:36', '2026-05-23 18:45:36'),
(263, 12, 3, NULL, 'Nấu xong chụp lên story ai cũng vào hỏi xin công thức luôn, thích ghê.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(264, 13, 3, NULL, 'Bạn ơi cho mình hỏi nếu không ăn cay được thì bớt ớt đi có ảnh hưởng đến hương vị đặc trưng của món không?', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(265, 13, 5, 264, 'Bớt ớt vô tư nhé, trẻ con ăn cũng được luôn á.', 1, '2026-05-10 13:45:36', '2026-05-23 18:45:36'),
(266, 13, 4, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(267, 14, 5, NULL, 'Wow, món này nhìn healthy quá, rất hợp với thực đơn giảm cân của mình.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(268, 15, 4, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(269, 16, 6, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(270, 16, 5, 269, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(271, 17, 3, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-05 13:45:36', '2026-05-23 18:45:36'),
(272, 17, 5, 271, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-14 13:45:36', '2026-05-23 18:45:36'),
(273, 17, 7, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-17 13:45:36', '2026-05-23 18:45:36'),
(274, 17, 8, 273, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(275, 18, 6, NULL, 'Bé nhà mình lười ăn rau mà nấu kiểu này cu cậu ăn tì tì luôn, cảm ơn chủ thớt nha.', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(276, 18, 3, NULL, 'Tuyệt cú mèo! Mình tìm công thức chuẩn món này lâu lắm rồi.', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(277, 19, 2, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-05-10 13:45:36', '2026-05-23 18:45:36'),
(278, 20, 4, NULL, 'Bày trí đẹp như nhà hàng 5 sao vậy, ngưỡng mộ quá!', 1, '2026-05-06 13:45:36', '2026-05-23 18:45:36'),
(279, 20, 8, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(280, 20, 5, 279, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-23 13:45:36', '2026-05-23 18:45:36'),
(281, 21, 8, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-10 13:45:36', '2026-05-23 18:45:36'),
(282, 21, 7, 281, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-14 13:45:36', '2026-05-23 18:45:36'),
(283, 22, 5, NULL, 'Bạn ơi cho mình hỏi nếu không ăn cay được thì bớt ớt đi có ảnh hưởng đến hương vị đặc trưng của món không?', 1, '2026-04-28 13:45:36', '2026-05-23 18:45:36'),
(284, 22, 7, 283, 'Bớt ớt vô tư nhé, trẻ con ăn cũng được luôn á.', 1, '2026-05-01 13:45:36', '2026-05-23 18:45:36'),
(285, 23, 8, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-05-09 13:45:36', '2026-05-23 18:45:36'),
(286, 24, 8, NULL, 'Hình chụp đẹp quá, nhìn là thấy thèm rồi. Cuối tuần này phải triển ngay mới được.', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(287, 25, 3, NULL, 'Mình làm thử mà lỡ tay cho hơi nhiều muối, lần sau rút kinh nghiệm sẽ hoàn hảo hơn.', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(288, 25, 5, 287, 'Tuyệt vời, chúc bạn thành công mỹ mãn nhé!', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(289, 26, 7, NULL, 'Tuyệt cú mèo! Mình tìm công thức chuẩn món này lâu lắm rồi.', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(290, 26, 6, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-04-24 13:45:36', '2026-05-23 18:45:36'),
(291, 26, 2, 290, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(292, 27, 8, NULL, 'Wow, món này nhìn healthy quá, rất hợp với thực đơn giảm cân của mình.', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(293, 28, 6, NULL, 'Hình chụp đẹp quá, nhìn là thấy thèm rồi. Cuối tuần này phải triển ngay mới được.', 1, '2026-05-12 13:45:36', '2026-05-23 18:45:36'),
(294, 28, 7, NULL, 'Đỉnh cao ẩm thực là đây chứ đâu, quá xuất sắc!', 1, '2026-05-03 13:45:36', '2026-05-23 18:45:36'),
(295, 29, 2, NULL, 'Bé nhà mình lười ăn rau mà nấu kiểu này cu cậu ăn tì tì luôn, cảm ơn chủ thớt nha.', 1, '2026-05-01 13:45:36', '2026-05-23 18:45:36'),
(296, 29, 8, 295, 'Ui cảm ơn bạn, hi vọng cả nhà bạn sẽ thích.', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(297, 29, 7, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-04-28 13:45:36', '2026-05-23 18:45:36'),
(298, 29, 2, 297, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(299, 30, 5, NULL, 'Nấu xong chụp lên story ai cũng vào hỏi xin công thức luôn, thích ghê.', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(300, 30, 7, 299, 'Tuyệt vời, chúc bạn thành công mỹ mãn nhé!', 1, '2026-05-23 13:45:36', '2026-05-23 18:45:36'),
(301, 31, 7, NULL, 'Món này nếu mình không có lò nướng thì dùng nồi chiên không dầu được không ạ?', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(302, 31, 3, 301, 'Được nha bạn, nồi chiên không dầu set 180 độ trong 15 phút là ngon lành.', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(303, 32, 6, NULL, 'Giao diện web đẹp, công thức chi tiết, 10 điểm không có nhưng!', 1, '2026-05-13 13:45:36', '2026-05-23 18:45:36'),
(304, 33, 4, NULL, 'Bày trí đẹp như nhà hàng 5 sao vậy, ngưỡng mộ quá!', 1, '2026-05-01 13:45:36', '2026-05-23 18:45:36'),
(305, 33, 2, 304, 'Cảm ơn bạn nhiều nha! Chúc gia đình ngon miệng.', 1, '2026-05-05 13:45:36', '2026-05-23 18:45:36'),
(306, 34, 5, NULL, 'Mình có thể thay thế dầu oliu bằng dầu bơ được không bếp ơi?', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(307, 34, 8, 306, 'Dùng dầu bơ càng thơm nha, mình thử rồi.', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(308, 34, 6, NULL, 'Mình vừa nấu xong mẻ đầu tiên, thơm nức nở cả gian bếp luôn á.', 1, '2026-05-13 13:45:36', '2026-05-23 18:45:36'),
(309, 35, 4, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(310, 35, 8, 309, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(311, 35, 3, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-05-03 13:45:36', '2026-05-23 18:45:36'),
(312, 35, 4, 311, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-05 13:45:36', '2026-05-23 18:45:36'),
(313, 36, 7, NULL, 'Nhìn thôi đã ứa nước miếng rồi, lưu lại cuối tuần trổ tài luôn!', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(314, 36, 3, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-05-20 13:45:36', '2026-05-23 18:45:36'),
(315, 37, 4, NULL, 'Nấu xong chụp lên story ai cũng vào hỏi xin công thức luôn, thích ghê.', 1, '2026-05-10 13:45:36', '2026-05-23 18:45:36'),
(316, 37, 4, NULL, 'Hình chụp đẹp quá, nhìn là thấy thèm rồi. Cuối tuần này phải triển ngay mới được.', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(317, 38, 3, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-03 13:45:36', '2026-05-23 18:45:36'),
(318, 38, 6, 317, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(319, 39, 3, NULL, 'Mình có thể thay thế dầu oliu bằng dầu bơ được không bếp ơi?', 1, '2026-04-28 13:45:36', '2026-05-23 18:45:36'),
(320, 39, 5, 319, 'Dùng dầu bơ càng thơm nha, mình thử rồi.', 1, '2026-05-17 13:45:36', '2026-05-23 18:45:36'),
(321, 39, 8, NULL, 'Bé nhà mình lười ăn rau mà nấu kiểu này cu cậu ăn tì tì luôn, cảm ơn chủ thớt nha.', 1, '2026-04-26 13:45:36', '2026-05-23 18:45:36'),
(322, 40, 2, NULL, 'Wow, món này nhìn healthy quá, rất hợp với thực đơn giảm cân của mình.', 1, '2026-05-14 13:45:36', '2026-05-23 18:45:36'),
(323, 40, 5, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(324, 41, 3, NULL, 'Wow, món này nhìn healthy quá, rất hợp với thực đơn giảm cân của mình.', 1, '2026-05-09 13:45:36', '2026-05-23 18:45:36'),
(325, 42, 5, NULL, 'Mình có thể thay thế dầu oliu bằng dầu bơ được không bếp ơi?', 1, '2026-05-04 13:45:36', '2026-05-23 18:45:36'),
(326, 42, 6, 325, 'Dùng dầu bơ càng thơm nha, mình thử rồi.', 1, '2026-05-05 13:45:36', '2026-05-23 18:45:36'),
(327, 42, 8, NULL, 'Bày trí đẹp như nhà hàng 5 sao vậy, ngưỡng mộ quá!', 1, '2026-05-04 13:45:36', '2026-05-23 18:45:36'),
(328, 43, 7, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-05-18 13:45:36', '2026-05-23 18:45:36'),
(329, 43, 3, 328, 'Ui cảm ơn bạn, hi vọng cả nhà bạn sẽ thích.', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(330, 43, 8, NULL, 'Đỉnh cao ẩm thực là đây chứ đâu, quá xuất sắc!', 1, '2026-05-17 13:45:36', '2026-05-23 18:45:36'),
(331, 44, 5, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-05-12 13:45:36', '2026-05-23 18:45:36'),
(332, 44, 5, NULL, 'Nhìn thôi đã ứa nước miếng rồi, lưu lại cuối tuần trổ tài luôn!', 1, '2026-05-03 13:45:36', '2026-05-23 18:45:36'),
(333, 45, 5, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(334, 45, 3, 333, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(335, 45, 5, NULL, 'Nhìn thôi đã ứa nước miếng rồi, lưu lại cuối tuần trổ tài luôn!', 1, '2026-05-02 13:45:36', '2026-05-23 18:45:36'),
(336, 46, 4, NULL, 'Mình có thể thay thế dầu oliu bằng dầu bơ được không bếp ơi?', 1, '2026-05-02 13:45:36', '2026-05-23 18:45:36'),
(337, 46, 7, 336, 'Dùng dầu bơ càng thơm nha, mình thử rồi.', 1, '2026-05-14 13:45:36', '2026-05-23 18:45:36'),
(338, 47, 7, NULL, 'Món này nếu mình không có lò nướng thì dùng nồi chiên không dầu được không ạ?', 1, '2026-04-23 13:45:36', '2026-05-23 18:45:36'),
(339, 47, 6, 338, 'Được nha bạn, nồi chiên không dầu set 180 độ trong 15 phút là ngon lành.', 1, '2026-05-23 13:45:36', '2026-05-23 18:45:36'),
(340, 47, 3, NULL, 'Mình vừa nấu xong mẻ đầu tiên, thơm nức nở cả gian bếp luôn á.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(341, 48, 3, NULL, 'Món này ăn kèm với cơm trắng hay bún thì hợp hơn vậy mọi người?', 1, '2026-04-24 13:45:36', '2026-05-23 18:45:36'),
(342, 48, 8, 341, 'Mình thấy ăn với cơm nóng là chuẩn bài nhất, hao cơm cực kỳ!', 1, '2026-05-06 13:45:36', '2026-05-23 18:45:36'),
(343, 49, 8, NULL, 'Hình chụp đẹp quá, nhìn là thấy thèm rồi. Cuối tuần này phải triển ngay mới được.', 1, '2026-05-04 13:45:36', '2026-05-23 18:45:36'),
(344, 49, 3, 343, 'Ui cảm ơn bạn, hi vọng cả nhà bạn sẽ thích.', 1, '2026-05-08 13:45:36', '2026-05-23 18:45:36'),
(345, 49, 2, NULL, 'Nấu xong chụp lên story ai cũng vào hỏi xin công thức luôn, thích ghê.', 1, '2026-04-24 13:45:36', '2026-05-23 18:45:36'),
(346, 50, 8, NULL, 'Mình có thể thay thế dầu oliu bằng dầu bơ được không bếp ơi?', 1, '2026-05-13 13:45:36', '2026-05-23 18:45:36'),
(347, 50, 3, 346, 'Dùng dầu bơ càng thơm nha, mình thử rồi.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(348, 50, 3, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-04 13:45:36', '2026-05-23 18:45:36'),
(349, 50, 2, 348, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(350, 51, 3, NULL, 'Món này nếu mình không có lò nướng thì dùng nồi chiên không dầu được không ạ?', 1, '2026-05-06 13:45:36', '2026-05-23 18:45:36'),
(351, 51, 7, 350, 'Được nha bạn, nồi chiên không dầu set 180 độ trong 15 phút là ngon lành.', 1, '2026-05-18 13:45:36', '2026-05-23 18:45:36'),
(352, 51, 3, NULL, 'Công thức tuyệt vời quá, mình làm thử hôm qua cả nhà ai cũng khen ngon! Cảm ơn bạn đã chia sẻ nha.', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(353, 52, 3, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(354, 52, 8, 353, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(355, 53, 6, NULL, 'Món này ăn kèm với cơm trắng hay bún thì hợp hơn vậy mọi người?', 1, '2026-05-09 13:45:36', '2026-05-23 18:45:36'),
(356, 53, 5, 355, 'Mình thấy ăn với cơm nóng là chuẩn bài nhất, hao cơm cực kỳ!', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(357, 54, 4, NULL, 'Món này ăn kèm với cơm trắng hay bún thì hợp hơn vậy mọi người?', 1, '2026-04-24 13:45:36', '2026-05-23 18:45:36'),
(358, 54, 2, 357, 'Mình thấy ăn với cơm nóng là chuẩn bài nhất, hao cơm cực kỳ!', 1, '2026-04-25 13:45:36', '2026-05-23 18:45:36'),
(359, 54, 8, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-04-24 13:45:36', '2026-05-23 18:45:36'),
(360, 54, 4, 359, 'Cảm ơn bạn nhiều nha! Chúc gia đình ngon miệng.', 1, '2026-04-29 13:45:36', '2026-05-23 18:45:36'),
(361, 55, 6, NULL, 'Bạn ơi cho mình hỏi nếu không ăn cay được thì bớt ớt đi có ảnh hưởng đến hương vị đặc trưng của món không?', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(362, 55, 5, 361, 'Bớt ớt vô tư nhé, trẻ con ăn cũng được luôn á.', 1, '2026-05-05 13:45:36', '2026-05-23 18:45:36'),
(363, 55, 7, NULL, 'Hình chụp đẹp quá, nhìn là thấy thèm rồi. Cuối tuần này phải triển ngay mới được.', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(364, 56, 7, NULL, 'Món này nếu mình không có lò nướng thì dùng nồi chiên không dầu được không ạ?', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(365, 56, 6, 364, 'Được nha bạn, nồi chiên không dầu set 180 độ trong 15 phút là ngon lành.', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(366, 57, 8, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-09 13:45:36', '2026-05-23 18:45:36'),
(367, 57, 7, 366, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-18 13:45:36', '2026-05-23 18:45:36'),
(368, 57, 6, NULL, 'Bé nhà mình lười ăn rau mà nấu kiểu này cu cậu ăn tì tì luôn, cảm ơn chủ thớt nha.', 1, '2026-05-14 13:45:36', '2026-05-23 18:45:36'),
(369, 57, 2, 368, 'Ui cảm ơn bạn, hi vọng cả nhà bạn sẽ thích.', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(370, 58, 6, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-04-30 13:45:36', '2026-05-23 18:45:36'),
(371, 59, 5, NULL, 'Công thức này dùng cho mấy người ăn vậy chủ thớt?', 1, '2026-05-21 13:45:36', '2026-05-23 18:45:36'),
(372, 59, 3, 371, 'Theo định lượng này thì tầm 3-4 người ăn vừa vặn nhé bạn.', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(373, 59, 6, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-05-17 13:45:36', '2026-05-23 18:45:36'),
(374, 59, 7, 373, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-23 13:45:36', '2026-05-23 18:45:36'),
(375, 60, 6, NULL, 'Món này nếu mình không có lò nướng thì dùng nồi chiên không dầu được không ạ?', 1, '2026-05-02 13:45:36', '2026-05-23 18:45:36'),
(376, 60, 7, 375, 'Được nha bạn, nồi chiên không dầu set 180 độ trong 15 phút là ngon lành.', 1, '2026-05-09 13:45:36', '2026-05-23 18:45:36'),
(377, 60, 8, NULL, 'Wow, món này nhìn healthy quá, rất hợp với thực đơn giảm cân của mình.', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(378, 60, 5, 377, 'Cảm ơn bạn nhiều nha! Chúc gia đình ngon miệng.', 1, '2026-05-22 13:45:36', '2026-05-23 18:45:36'),
(379, 61, 3, NULL, 'Công thức tuyệt vời quá, mình làm thử hôm qua cả nhà ai cũng khen ngon! Cảm ơn bạn đã chia sẻ nha.', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(380, 61, 3, NULL, 'Giao diện web đẹp, công thức chi tiết, 10 điểm không có nhưng!', 1, '2026-05-16 13:45:36', '2026-05-23 18:45:36'),
(381, 62, 5, NULL, 'Giao diện web đẹp, công thức chi tiết, 10 điểm không có nhưng!', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(382, 62, 4, 381, 'Tuyệt vời, chúc bạn thành công mỹ mãn nhé!', 1, '2026-05-13 13:45:36', '2026-05-23 18:45:36'),
(383, 63, 6, NULL, 'Bé nhà mình lười ăn rau mà nấu kiểu này cu cậu ăn tì tì luôn, cảm ơn chủ thớt nha.', 1, '2026-05-09 13:45:36', '2026-05-23 18:45:36'),
(384, 64, 4, NULL, 'Cho mình hỏi nước sốt này có thể làm nhiều rồi cất tủ lạnh dùng dần được không?', 1, '2026-04-23 13:45:36', '2026-05-23 18:45:36'),
(385, 64, 2, 384, 'Được nhé, bạn cất hũ thủy tinh để tủ mát dùng trong 3-4 ngày là ngon nhất.', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(386, 65, 4, NULL, 'Đỉnh cao ẩm thực là đây chứ đâu, quá xuất sắc!', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(387, 65, 7, NULL, 'Tuyệt cú mèo! Mình tìm công thức chuẩn món này lâu lắm rồi.', 1, '2026-04-26 13:45:36', '2026-05-23 18:45:36'),
(388, 66, 2, NULL, 'Tuyệt cú mèo! Mình tìm công thức chuẩn món này lâu lắm rồi.', 1, '2026-05-07 13:45:36', '2026-05-23 18:45:36'),
(389, 67, 4, NULL, 'Lưu lại để tết nấu cho gia đình thưởng thức, nhìn hấp dẫn quá.', 1, '2026-04-25 13:45:36', '2026-05-23 18:45:36'),
(390, 67, 3, 389, 'Cảm ơn bạn nhiều nha! Chúc gia đình ngon miệng.', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(391, 68, 6, NULL, 'Bạn ơi cho mình hỏi nếu không ăn cay được thì bớt ớt đi có ảnh hưởng đến hương vị đặc trưng của món không?', 1, '2026-05-14 13:45:36', '2026-05-23 18:45:36'),
(392, 68, 8, 391, 'Bớt ớt vô tư nhé, trẻ con ăn cũng được luôn á.', 1, '2026-05-19 13:45:36', '2026-05-23 18:45:36'),
(393, 68, 8, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-05-15 13:45:36', '2026-05-23 18:45:36'),
(394, 69, 2, NULL, 'Mình đã làm theo đúng định lượng này và thành công mỹ mãn. Nước sốt đậm đà lắm ạ.', 1, '2026-05-11 13:45:36', '2026-05-23 18:45:36'),
(395, 69, 4, NULL, 'Nấu xong chụp lên story ai cũng vào hỏi xin công thức luôn, thích ghê.', 1, '2026-04-26 13:45:36', '2026-05-23 18:45:36'),
(396, 65, 2, NULL, 'ngon lắm loon', 1, '2026-05-24 11:54:39', '2026-05-24 11:54:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `comment_id`, `user_id`, `created_at`) VALUES
(1, 21, 9, '2026-05-23 18:09:31'),
(324, 258, 3, '2026-05-23 18:45:36'),
(325, 258, 5, '2026-05-23 18:45:36'),
(326, 258, 8, '2026-05-23 18:45:36'),
(327, 259, 4, '2026-05-23 18:45:36'),
(328, 259, 5, '2026-05-23 18:45:36'),
(329, 259, 8, '2026-05-23 18:45:36'),
(330, 261, 7, '2026-05-23 18:45:36'),
(331, 264, 8, '2026-05-23 18:45:36'),
(332, 266, 4, '2026-05-23 18:45:36'),
(333, 267, 3, '2026-05-23 18:45:36'),
(334, 267, 5, '2026-05-23 18:45:36'),
(335, 267, 7, '2026-05-23 18:45:36'),
(336, 268, 3, '2026-05-23 18:45:36'),
(337, 268, 5, '2026-05-23 18:45:36'),
(338, 268, 6, '2026-05-23 18:45:36'),
(339, 269, 2, '2026-05-23 18:45:36'),
(340, 269, 3, '2026-05-23 18:45:36'),
(341, 269, 5, '2026-05-23 18:45:36'),
(342, 273, 7, '2026-05-23 18:45:36'),
(343, 275, 3, '2026-05-23 18:45:36'),
(344, 276, 4, '2026-05-23 18:45:36'),
(345, 276, 5, '2026-05-23 18:45:36'),
(346, 276, 8, '2026-05-23 18:45:36'),
(347, 277, 2, '2026-05-23 18:45:36'),
(348, 277, 5, '2026-05-23 18:45:36'),
(349, 277, 8, '2026-05-23 18:45:36'),
(350, 278, 3, '2026-05-23 18:45:36'),
(351, 279, 7, '2026-05-23 18:45:36'),
(352, 279, 8, '2026-05-23 18:45:36'),
(353, 281, 5, '2026-05-23 18:45:36'),
(354, 283, 6, '2026-05-23 18:45:36'),
(355, 283, 8, '2026-05-23 18:45:36'),
(356, 285, 4, '2026-05-23 18:45:36'),
(357, 285, 5, '2026-05-23 18:45:36'),
(358, 286, 3, '2026-05-23 18:45:36'),
(359, 286, 8, '2026-05-23 18:45:36'),
(360, 287, 5, '2026-05-23 18:45:36'),
(361, 287, 6, '2026-05-23 18:45:36'),
(362, 289, 3, '2026-05-23 18:45:36'),
(363, 289, 4, '2026-05-23 18:45:36'),
(364, 289, 6, '2026-05-23 18:45:36'),
(365, 290, 2, '2026-05-23 18:45:36'),
(366, 290, 3, '2026-05-23 18:45:36'),
(367, 292, 6, '2026-05-23 18:45:36'),
(368, 293, 4, '2026-05-23 18:45:36'),
(369, 295, 3, '2026-05-23 18:45:36'),
(370, 295, 4, '2026-05-23 18:45:36'),
(371, 295, 6, '2026-05-23 18:45:36'),
(372, 301, 3, '2026-05-23 18:45:36'),
(373, 301, 8, '2026-05-23 18:45:36'),
(374, 303, 2, '2026-05-23 18:45:36'),
(375, 303, 4, '2026-05-23 18:45:36'),
(376, 306, 2, '2026-05-23 18:45:36'),
(377, 309, 5, '2026-05-23 18:45:36'),
(378, 309, 7, '2026-05-23 18:45:36'),
(379, 309, 8, '2026-05-23 18:45:36'),
(380, 311, 5, '2026-05-23 18:45:36'),
(381, 313, 3, '2026-05-23 18:45:36'),
(382, 314, 3, '2026-05-23 18:45:36'),
(383, 314, 7, '2026-05-23 18:45:36'),
(384, 314, 8, '2026-05-23 18:45:36'),
(385, 315, 7, '2026-05-23 18:45:36'),
(386, 316, 2, '2026-05-23 18:45:36'),
(387, 317, 6, '2026-05-23 18:45:36'),
(388, 317, 8, '2026-05-23 18:45:36'),
(389, 321, 4, '2026-05-23 18:45:36'),
(390, 321, 5, '2026-05-23 18:45:36'),
(391, 321, 7, '2026-05-23 18:45:36'),
(392, 322, 3, '2026-05-23 18:45:36'),
(393, 322, 5, '2026-05-23 18:45:36'),
(394, 323, 2, '2026-05-23 18:45:36'),
(395, 323, 6, '2026-05-23 18:45:36'),
(396, 323, 7, '2026-05-23 18:45:36'),
(397, 325, 3, '2026-05-23 18:45:36'),
(398, 325, 5, '2026-05-23 18:45:36'),
(399, 325, 8, '2026-05-23 18:45:36'),
(400, 328, 5, '2026-05-23 18:45:36'),
(401, 328, 6, '2026-05-23 18:45:36'),
(402, 328, 7, '2026-05-23 18:45:36'),
(403, 330, 3, '2026-05-23 18:45:36'),
(404, 330, 4, '2026-05-23 18:45:36'),
(405, 330, 7, '2026-05-23 18:45:36'),
(406, 331, 4, '2026-05-23 18:45:36'),
(407, 331, 6, '2026-05-23 18:45:36'),
(408, 332, 4, '2026-05-23 18:45:36'),
(409, 332, 5, '2026-05-23 18:45:36'),
(410, 333, 2, '2026-05-23 18:45:36'),
(411, 333, 6, '2026-05-23 18:45:36'),
(412, 333, 7, '2026-05-23 18:45:36'),
(413, 335, 3, '2026-05-23 18:45:36'),
(414, 335, 5, '2026-05-23 18:45:36'),
(415, 338, 3, '2026-05-23 18:45:36'),
(416, 338, 8, '2026-05-23 18:45:36'),
(417, 341, 7, '2026-05-23 18:45:36'),
(418, 345, 2, '2026-05-23 18:45:36'),
(419, 345, 3, '2026-05-23 18:45:36'),
(420, 346, 2, '2026-05-23 18:45:36'),
(421, 346, 3, '2026-05-23 18:45:36'),
(422, 346, 5, '2026-05-23 18:45:36'),
(423, 348, 6, '2026-05-23 18:45:36'),
(424, 348, 8, '2026-05-23 18:45:36'),
(425, 352, 5, '2026-05-23 18:45:36'),
(426, 352, 6, '2026-05-23 18:45:36'),
(427, 352, 7, '2026-05-23 18:45:36'),
(428, 353, 6, '2026-05-23 18:45:36'),
(429, 353, 7, '2026-05-23 18:45:36'),
(430, 355, 3, '2026-05-23 18:45:36'),
(431, 355, 6, '2026-05-23 18:45:36'),
(432, 361, 2, '2026-05-23 18:45:36'),
(433, 361, 6, '2026-05-23 18:45:36'),
(434, 361, 7, '2026-05-23 18:45:36'),
(435, 363, 2, '2026-05-23 18:45:36'),
(436, 363, 5, '2026-05-23 18:45:36'),
(437, 363, 7, '2026-05-23 18:45:36'),
(438, 364, 2, '2026-05-23 18:45:36'),
(439, 364, 7, '2026-05-23 18:45:36'),
(440, 366, 5, '2026-05-23 18:45:36'),
(441, 366, 8, '2026-05-23 18:45:36'),
(442, 368, 3, '2026-05-23 18:45:36'),
(443, 368, 4, '2026-05-23 18:45:36'),
(444, 368, 5, '2026-05-23 18:45:36'),
(445, 370, 7, '2026-05-23 18:45:36'),
(446, 371, 8, '2026-05-23 18:45:36'),
(447, 375, 6, '2026-05-23 18:45:36'),
(448, 377, 3, '2026-05-23 18:45:36'),
(449, 377, 4, '2026-05-23 18:45:36'),
(450, 377, 8, '2026-05-23 18:45:36'),
(451, 379, 4, '2026-05-23 18:45:36'),
(452, 380, 2, '2026-05-23 18:45:36'),
(453, 380, 3, '2026-05-23 18:45:36'),
(454, 383, 2, '2026-05-23 18:45:36'),
(455, 383, 3, '2026-05-23 18:45:36'),
(456, 383, 6, '2026-05-23 18:45:36'),
(457, 386, 3, '2026-05-23 18:45:36'),
(458, 386, 4, '2026-05-23 18:45:36'),
(459, 386, 7, '2026-05-23 18:45:36'),
(460, 387, 3, '2026-05-23 18:45:36'),
(461, 387, 5, '2026-05-23 18:45:36'),
(462, 388, 4, '2026-05-23 18:45:36'),
(463, 388, 6, '2026-05-23 18:45:36'),
(464, 388, 8, '2026-05-23 18:45:36'),
(465, 389, 2, '2026-05-23 18:45:36'),
(466, 389, 4, '2026-05-23 18:45:36'),
(467, 389, 7, '2026-05-23 18:45:36'),
(468, 391, 2, '2026-05-23 18:45:36'),
(469, 391, 5, '2026-05-23 18:45:36'),
(470, 393, 4, '2026-05-23 18:45:36'),
(471, 393, 7, '2026-05-23 18:45:36'),
(472, 393, 8, '2026-05-23 18:45:36'),
(473, 394, 5, '2026-05-23 18:45:36'),
(474, 395, 2, '2026-05-23 18:45:36'),
(475, 395, 4, '2026-05-23 18:45:36'),
(476, 395, 6, '2026-05-23 18:45:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL COMMENT 'e.g., Vegetables, Meat, Dairy, etc.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `slug`, `category`, `created_at`) VALUES
(1, 'Chicken Breast', 'chicken-breast', 'Meat', '2025-12-31 17:00:00'),
(2, 'Garlic', 'garlic', 'Vegetables', '2025-12-31 17:00:00'),
(3, 'Olive Oil', 'olive-oil', 'Oils', '2025-12-31 17:00:00'),
(4, 'Salt', 'salt', 'Seasonings', '2025-12-31 17:00:00'),
(5, 'Black Pepper', 'black-pepper', 'Seasonings', '2025-12-31 17:00:00'),
(6, 'Onion', 'onion', 'Vegetables', '2025-12-31 17:00:00'),
(7, 'Tomato', 'tomato', 'Vegetables', '2025-12-31 17:00:00'),
(8, 'Rice', 'rice', 'Grains', '2025-12-31 17:00:00'),
(9, 'Soy Sauce', 'soy-sauce', 'Sauces', '2025-12-31 17:00:00'),
(10, 'Butter', 'butter', 'Dairy', '2025-12-31 17:00:00'),
(11, 'Eggs', 'eggs', 'Dairy', '2025-12-31 17:00:00'),
(12, 'Flour', 'flour', 'Grains', '2025-12-31 17:00:00'),
(13, 'Sugar', 'sugar', 'Baking', '2025-12-31 17:00:00'),
(14, 'Milk', 'milk', 'Dairy', '2025-12-31 17:00:00'),
(15, 'Pasta', 'pasta', 'Grains', '2025-12-31 17:00:00'),
(16, 'Parmesan Cheese', 'parmesan-cheese', 'Dairy', '2025-12-31 17:00:00'),
(17, 'Cream', 'cream', 'Dairy', '2025-12-31 17:00:00'),
(18, 'Lemon', 'lemon', 'Fruits', '2025-12-31 17:00:00'),
(19, 'Ginger', 'ginger', 'Vegetables', '2025-12-31 17:00:00'),
(20, 'Salmon Fillet', 'salmon-fillet', 'Seafood', '2025-12-31 17:00:00'),
(21, 'Asparagus', 'asparagus', 'Vegetables', '2025-12-31 17:00:00'),
(22, 'Mushrooms', 'mushrooms', 'Vegetables', '2025-12-31 17:00:00'),
(23, 'Spinach', 'spinach', 'Vegetables', '2025-12-31 17:00:00'),
(24, 'Bacon', 'bacon', 'Meat', '2025-12-31 17:00:00'),
(25, 'Avocado', 'avocado', 'Fruits', '2025-12-31 17:00:00'),
(26, 'Bread', 'bread', 'Grains', '2025-12-31 17:00:00'),
(27, 'Honey', 'honey', 'Sweeteners', '2025-12-31 17:00:00'),
(28, 'Balsamic Vinegar', 'balsamic-vinegar', 'Sauces', '2025-12-31 17:00:00'),
(29, 'Dark Chocolate', 'dark-chocolate', 'Baking', '2025-12-31 17:00:00'),
(30, 'Vanilla Extract', 'vanilla-extract', 'Baking', '2025-12-31 17:00:00'),
(31, 'buttermilk', '', NULL, '2026-05-22 15:43:21'),
(33, 'English muffins', 'english-muffins-6a1079b5bc03e', NULL, '2026-05-22 15:43:49'),
(34, 'Canadian bacon', 'canadian-bacon-6a1079b5bd5fc', NULL, '2026-05-22 15:43:49'),
(35, 'hollandaise sauce', 'hollandaise-sauce-6a1079b5c001f', NULL, '2026-05-22 15:43:49'),
(36, 'chives', 'chives-6a1079b5c39c4', NULL, '2026-05-22 15:43:49'),
(37, 'açaí packets', 'aa-packets-6a1079b5cda24', NULL, '2026-05-22 15:43:49'),
(38, 'banana', 'banana-6a1079b5d037f', NULL, '2026-05-22 15:43:49'),
(39, 'almond milk', 'almond-milk-6a1079b5d164a', NULL, '2026-05-22 15:43:49'),
(40, 'granola', 'granola-6a1079b5d32af', NULL, '2026-05-22 15:43:49'),
(41, 'blueberries', 'blueberries-6a1079b5d49d6', NULL, '2026-05-22 15:43:49'),
(42, 'parmesan', 'parmesan-6a1079b5e645d', NULL, '2026-05-22 15:43:49'),
(43, 'chicken', 'chicken-6a1079b5eb761', NULL, '2026-05-22 15:43:49'),
(44, 'fettuccine', 'fettuccine-6a1079b600300', NULL, '2026-05-22 15:43:50'),
(45, 'cajun spice', 'cajun-spice-6a1079b60261c', NULL, '2026-05-22 15:43:50'),
(46, 'spaghetti', 'spaghetti-6a1079b60940b', NULL, '2026-05-22 15:43:50'),
(47, 'guanciale', 'guanciale-6a1079b60aac6', NULL, '2026-05-22 15:43:50'),
(48, 'pecorino romano', 'pecorino-romano-6a1079b6111fd', NULL, '2026-05-22 15:43:50'),
(49, 'trofie pasta', 'trofie-pasta-6a1079b6194d2', NULL, '2026-05-22 15:43:50'),
(50, 'basil', 'basil-6a1079b61c245', NULL, '2026-05-22 15:43:50'),
(51, 'pine nuts', 'pine-nuts-6a1079b61edb3', NULL, '2026-05-22 15:43:50'),
(52, 'lasagna sheets', 'lasagna-sheets-6a1079b626f28', NULL, '2026-05-22 15:43:50'),
(53, 'ground beef', 'ground-beef-6a1079b627e45', NULL, '2026-05-22 15:43:50'),
(54, 'tomatoes', 'tomatoes-6a1079b628e77', NULL, '2026-05-22 15:43:50'),
(55, 'béchamel', 'bchamel-6a1079b62a8ed', NULL, '2026-05-22 15:43:50'),
(56, 'mozzarella', 'mozzarella-6a1079b62cc9c', NULL, '2026-05-22 15:43:50'),
(57, 'lettuce', 'lettuce-6a1079b631ad7', NULL, '2026-05-22 15:43:50'),
(58, 'corn', 'corn-6a1079b635718', NULL, '2026-05-22 15:43:50'),
(59, 'black beans', 'black-beans-6a1079b636a11', NULL, '2026-05-22 15:43:50'),
(60, 'tortilla chips', 'tortilla-chips-6a1079b63855d', NULL, '2026-05-22 15:43:50'),
(61, 'salsa', 'salsa-6a1079b63ac9e', NULL, '2026-05-22 15:43:50'),
(62, 'arborio rice', 'arborio-rice-6a1079b63f0dd', NULL, '2026-05-22 15:43:50'),
(63, 'mixed mushrooms', 'mixed-mushrooms-6a1079b640882', NULL, '2026-05-22 15:43:50'),
(64, 'white wine', 'white-wine-6a1079b641b01', NULL, '2026-05-22 15:43:50'),
(65, 'fresh mozzarella', 'fresh-mozzarella-6a1079b646345', NULL, '2026-05-22 15:43:50'),
(66, 'heirloom tomatoes', 'heirloom-tomatoes-6a1079b647d9e', NULL, '2026-05-22 15:43:50'),
(67, 'balsamic', 'balsamic-6a1079b64a0e8', NULL, '2026-05-22 15:43:50'),
(68, 'eggplant', 'eggplant-6a1079b64e50e', NULL, '2026-05-22 15:43:50'),
(69, 'marinara sauce', 'marinara-sauce-6a1079b650214', NULL, '2026-05-22 15:43:50'),
(70, 'breadcrumbs', 'breadcrumbs-6a1079b651ee4', NULL, '2026-05-22 15:43:50'),
(71, 'red lentils', 'red-lentils-6a1079b655f42', NULL, '2026-05-22 15:43:50'),
(72, 'cumin', 'cumin-6a1079b6584e4', NULL, '2026-05-22 15:43:50'),
(73, 'thyme', 'thyme-6a1079b6612fe', NULL, '2026-05-22 15:43:50'),
(74, 'strip steak', 'strip-steak-6a1079b66543b', NULL, '2026-05-22 15:43:50'),
(75, 'rosemary', 'rosemary-6a1079b66692b', NULL, '2026-05-22 15:43:50'),
(76, 'pepper', 'pepper-6a1079b6692a3', NULL, '2026-05-22 15:43:50'),
(77, 'tortillas', 'tortillas-6a1079b66cdc0', NULL, '2026-05-22 15:43:50'),
(78, 'cheese', 'cheese-6a1079b66dff3', NULL, '2026-05-22 15:43:50'),
(79, 'peppers', 'peppers-6a1079b66ed60', NULL, '2026-05-22 15:43:50'),
(80, 'onions', 'onions-6a1079b6708b7', NULL, '2026-05-22 15:43:50'),
(81, 'beef bones', 'beef-bones-6a1079b673d67', NULL, '2026-05-22 15:43:50'),
(82, 'rice noodles', 'rice-noodles-6a1079b674e22', NULL, '2026-05-22 15:43:50'),
(83, 'beef slices', 'beef-slices-6a1079b6760b2', NULL, '2026-05-22 15:43:50'),
(84, 'star anise', 'star-anise-6a1079b676f1b', NULL, '2026-05-22 15:43:50'),
(85, 'bean sprouts', 'bean-sprouts-6a1079b678d32', NULL, '2026-05-22 15:43:50'),
(86, 'ramen noodles', 'ramen-noodles-6a1079b67cd94', NULL, '2026-05-22 15:43:50'),
(87, 'miso paste', 'miso-paste-6a1079b67da58', NULL, '2026-05-22 15:43:50'),
(88, 'pork belly', 'pork-belly-6a1079b67e70a', NULL, '2026-05-22 15:43:50'),
(89, 'soft egg', 'soft-egg-6a1079b67f314', NULL, '2026-05-22 15:43:50'),
(90, 'nori', 'nori-6a1079b68080a', NULL, '2026-05-22 15:43:50'),
(91, 'green onions', 'green-onions-6a1079b681db3', NULL, '2026-05-22 15:43:50'),
(92, 'fresh peas', 'fresh-peas-6a1079b685cb8', NULL, '2026-05-22 15:43:50'),
(93, 'mint', 'mint-6a1079b686a41', NULL, '2026-05-22 15:43:50'),
(94, 'vegetable stock', 'vegetable-stock-6a1079b687fda', NULL, '2026-05-22 15:43:50'),
(95, 'baby spinach', 'baby-spinach-6a1079b68e776', NULL, '2026-05-22 15:43:50'),
(96, 'strawberries', 'strawberries-6a1079b68f4f6', NULL, '2026-05-22 15:43:50'),
(97, 'goat cheese', 'goat-cheese-6a1079b690c26', NULL, '2026-05-22 15:43:50'),
(98, 'walnuts', 'walnuts-6a1079b6922a2', NULL, '2026-05-22 15:43:50'),
(99, 'balsamic dressing', 'balsamic-dressing-6a1079b6931b3', NULL, '2026-05-22 15:43:50'),
(100, 'puff pastry', 'puff-pastry-6a1079b6969f3', NULL, '2026-05-22 15:43:50'),
(101, 'ricotta', 'ricotta-6a1079b698320', NULL, '2026-05-22 15:43:50'),
(102, 'lemon zest', 'lemon-zest-6a1079b699240', NULL, '2026-05-22 15:43:50'),
(103, 'sourdough', 'sourdough-6a1079b69f2f4', NULL, '2026-05-22 15:43:50'),
(104, 'cultured butter', 'cultured-butter-6a1079b6a131a', NULL, '2026-05-22 15:43:50'),
(105, 'radishes', 'radishes-6a1079b6a20b3', NULL, '2026-05-22 15:43:50'),
(106, 'sea salt', 'sea-salt-6a1079b6a2cb2', NULL, '2026-05-22 15:43:50'),
(107, 'corn cobs', 'corn-cobs-6a1079b6a8ed3', NULL, '2026-05-22 15:43:50'),
(108, 'chili powder', 'chili-powder-6a1079b6aa569', NULL, '2026-05-22 15:43:50'),
(109, 'lime', 'lime-6a1079b6ab18c', NULL, '2026-05-22 15:43:50'),
(110, 'cotija cheese', 'cotija-cheese-6a1079b6ad010', NULL, '2026-05-22 15:43:50'),
(111, 'cilantro', 'cilantro-6a1079b6ae46b', NULL, '2026-05-22 15:43:50'),
(112, 'lamb chops', 'lamb-chops-6a1079b6b16e2', NULL, '2026-05-22 15:43:50'),
(113, 'mint sauce', 'mint-sauce-6a1079b6b4908', NULL, '2026-05-22 15:43:50'),
(114, 'romaine', 'romaine-6a1079b6b8086', NULL, '2026-05-22 15:43:50'),
(115, 'croutons', 'croutons-6a1079b6ba2d5', NULL, '2026-05-22 15:43:50'),
(116, 'caesar dressing', 'caesar-dressing-6a1079b6bae94', NULL, '2026-05-22 15:43:50'),
(117, 'anchovies', 'anchovies-6a1079b6bc15a', NULL, '2026-05-22 15:43:50'),
(118, 'savoiardi', 'savoiardi-6a1079b6c038b', NULL, '2026-05-22 15:43:50'),
(119, 'mascarpone', 'mascarpone-6a1079b6c1e0f', NULL, '2026-05-22 15:43:50'),
(120, 'espresso', 'espresso-6a1079b6c30f2', NULL, '2026-05-22 15:43:50'),
(121, 'cocoa powder', 'cocoa-powder-6a1079b6c45dc', NULL, '2026-05-22 15:43:50'),
(122, 'heavy cream', 'heavy-cream-6a1079b6c815c', NULL, '2026-05-22 15:43:50'),
(123, 'egg yolks', 'egg-yolks-6a1079b6c961d', NULL, '2026-05-22 15:43:50'),
(124, 'vanilla bean', 'vanilla-bean-6a1079b6caa57', NULL, '2026-05-22 15:43:50'),
(125, 'beef broth', 'beef-broth-6a1079b6ceec5', NULL, '2026-05-22 15:43:50'),
(126, 'gruyere cheese', 'gruyere-cheese-6a1079b6d057d', NULL, '2026-05-22 15:43:50'),
(127, 'shrimp', 'shrimp-6a1079b6d5284', NULL, '2026-05-22 15:43:50'),
(128, 'lemongrass', 'lemongrass-6a1079b6d6a8e', NULL, '2026-05-22 15:43:50'),
(129, 'kaffir lime', 'kaffir-lime-6a1079b6d797c', NULL, '2026-05-22 15:43:50'),
(130, 'galangal', 'galangal-6a1079b6d85c1', NULL, '2026-05-22 15:43:50'),
(131, 'chili', 'chili-6a1079b6da24d', NULL, '2026-05-22 15:43:50'),
(132, 'watermelon', 'watermelon-6a1079b6de7a2', NULL, '2026-05-22 15:43:50'),
(133, 'lime juice', 'lime-juice-6a1079b6dfbdf', NULL, '2026-05-22 15:43:50'),
(134, 'sparkling water', 'sparkling-water-6a1079b6e1772', NULL, '2026-05-22 15:43:50'),
(135, 'cheddar cheese', 'cheddar-cheese-6a10a8e32d90f', NULL, '2026-05-22 19:05:07'),
(136, 'brioche buns', 'brioche-buns-6a10a8e32e74a', NULL, '2026-05-22 19:05:07'),
(137, 'mixed greens', 'mixed-greens-6a10a8e333257', NULL, '2026-05-22 19:05:07'),
(138, 'cherry tomatoes', 'cherry-tomatoes-6a10a8e33414d', NULL, '2026-05-22 19:05:07'),
(139, 'balsamic vinaigrette', 'balsamic-vinaigrette-6a10a8e33511c', NULL, '2026-05-22 19:05:07'),
(140, 'sushi rice', 'sushi-rice-6a10a8e33873d', NULL, '2026-05-22 19:05:07'),
(141, 'canned tuna', 'canned-tuna-6a10a8e3398bb', NULL, '2026-05-22 19:05:07'),
(142, 'mayonnaise', 'mayonnaise-6a10a8e33b3bc', NULL, '2026-05-22 19:05:07'),
(143, 'nori seaweed', 'nori-seaweed-6a10a8e33c3a9', NULL, '2026-05-22 19:05:07'),
(144, 'baguette', 'baguette-6a10a8e34058b', NULL, '2026-05-22 19:05:07'),
(145, 'parsley', 'parsley-6a10a8e342c74', NULL, '2026-05-22 19:05:07'),
(146, 'russet potatoes', 'russet-potatoes-6a10a8e34669f', NULL, '2026-05-22 19:05:07'),
(147, 'lemon juice', 'lemon-juice-6a10a8e34e4a4', NULL, '2026-05-22 19:05:07'),
(148, 'white rum', 'white-rum-6a10a8e353111', NULL, '2026-05-22 19:05:07'),
(149, 'fresh mint', 'fresh-mint-6a10a8e354477', NULL, '2026-05-22 19:05:07'),
(150, 'simple syrup', 'simple-syrup-6a10a8e355a51', NULL, '2026-05-22 19:05:07'),
(151, 'club soda', 'club-soda-6a10a8e357200', NULL, '2026-05-22 19:05:07'),
(152, 'ripe mango', 'ripe-mango-6a10a8e35a5dd', NULL, '2026-05-22 19:05:07'),
(153, 'greek yogurt', 'greek-yogurt-6a10a8e35c575', NULL, '2026-05-22 19:05:07'),
(154, 'black tea bags', 'black-tea-bags-6a10a8e360bc6', NULL, '2026-05-22 19:05:07'),
(155, 'fresh peaches', 'fresh-peaches-6a10a8e3619fa', NULL, '2026-05-22 19:05:07'),
(156, 'water', 'water-6a10a8e362e9c', NULL, '2026-05-22 19:05:07'),
(157, 'ice', 'ice-6a10a8e364065', NULL, '2026-05-22 19:05:07'),
(158, 'chicken wings', 'chicken-wings-6a10a8e366e5c', NULL, '2026-05-22 19:05:07'),
(159, 'cornstarch', 'cornstarch-6a10a8e367ec8', NULL, '2026-05-22 19:05:07'),
(160, 'gochujang', 'gochujang-6a10a8e368aec', NULL, '2026-05-22 19:05:07'),
(161, 'macaroni', 'macaroni-6a10a8e36c880', NULL, '2026-05-22 19:05:07'),
(162, 'beef chuck', 'beef-chuck-6a10a8e3714a6', NULL, '2026-05-22 19:05:07'),
(163, 'potatoes', 'potatoes-6a10a8e3722b7', NULL, '2026-05-22 19:05:07'),
(164, 'carrots', 'carrots-6a10a8e373091', NULL, '2026-05-22 19:05:07'),
(165, 'tomato paste', 'tomato-paste-6a10a8e3750fb', NULL, '2026-05-22 19:05:07'),
(166, 'extra virgin olive oil', 'extra-virgin-olive-oil-6a10a8e3788ef', NULL, '2026-05-22 19:05:07'),
(167, 'chili flakes', 'chili-flakes-6a10a8e379639', NULL, '2026-05-22 19:05:07'),
(168, 'day-old rice', 'dayold-rice-6a10a8e37d2fc', NULL, '2026-05-22 19:05:07'),
(169, 'smoked sausage', 'smoked-sausage-6a10a8e37e16c', NULL, '2026-05-22 19:05:07'),
(170, 'red onion', 'red-onion-6a10a8e382fa8', NULL, '2026-05-22 19:05:07'),
(171, 'pizza dough', 'pizza-dough-6a10a8e387607', NULL, '2026-05-22 19:05:07'),
(172, 'pepperoni', 'pepperoni-6a10a8e38a180', NULL, '2026-05-22 19:05:07'),
(173, 'yellow onions', 'yellow-onions-6a10a8e38d7a4', NULL, '2026-05-22 19:05:07'),
(174, 'baking powder', 'baking-powder-6a10a8e38ed6a', NULL, '2026-05-22 19:05:07'),
(175, 'oil for frying', 'oil-for-frying-6a10a8e390ef8', NULL, '2026-05-22 19:05:07'),
(176, 'pumpkin puree', 'pumpkin-puree-6a10a8e393d59', NULL, '2026-05-22 19:05:07'),
(177, 'vegetable broth', 'vegetable-broth-6a10a8e39548d', NULL, '2026-05-22 19:05:07'),
(178, 'nutmeg', 'nutmeg-6a10a8e3970ce', NULL, '2026-05-22 19:05:07'),
(179, 'chicken broth', 'chicken-broth-6a10a8e39b264', NULL, '2026-05-22 19:05:07'),
(180, 'truffle oil', 'truffle-oil-6a10a8e39cd65', NULL, '2026-05-22 19:05:07'),
(181, 'Garlic Cloves', 'garlic-cloves', NULL, '2026-05-23 19:39:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `newsletter_subscriptions`
--

CREATE TABLE `newsletter_subscriptions` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unsubscribed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `newsletter_subscriptions`
--

INSERT INTO `newsletter_subscriptions` (`id`, `email`, `is_active`, `subscribed_at`, `unsubscribed_at`) VALUES
(1, 'linh@gmail.com', 1, '2026-01-10 01:00:00', NULL),
(2, 'thuhoa@gmail.com', 1, '2026-01-20 03:00:00', NULL),
(3, 'camanh@gmail.com', 1, '2026-02-05 02:00:00', NULL),
(4, 'foodlover123@gmail.com', 1, '2026-02-15 07:00:00', NULL),
(5, 'cooking_fan@yahoo.com', 1, '2026-03-01 04:00:00', NULL),
(6, 'healthy_eater@gmail.com', 0, '2026-01-25 09:00:00', NULL),
(7, 'nguyenlinh230809@gmail.com', 1, '2026-05-23 18:11:27', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'e.g., new_comment, new_follower, recipe_liked',
  `title` varchar(200) NOT NULL,
  `message` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'recipe', 'Công thức mới', 'Nguyễn Linh đã đăng một công thức mới: Açaí Breakfast Bowl', NULL, 0, '2026-05-22 15:43:49'),
(2, 1, 'recipe', 'Công thức mới', 'Nguyễn Linh đã đăng một công thức mới: Creamy Chicken and Bacon Pasta', NULL, 0, '2026-05-22 15:43:49'),
(3, 1, 'recipe', 'Công thức mới', 'Nguyễn Linh đã đăng một công thức mới: Classic Cheeseburger', NULL, 0, '2026-05-22 19:05:07'),
(4, 1, 'recipe', 'Công thức mới', 'Nguyễn Linh đã đăng một công thức mới: Roasted Asparagus', NULL, 0, '2026-05-22 19:05:07'),
(5, 1, 'recipe', 'Công thức mới', 'Nguyễn Linh đã đăng một công thức mới: Truffle Mushroom Soup', NULL, 0, '2026-05-22 19:05:07'),
(6, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Classic French Toast', NULL, 0, '2026-02-01 01:00:00'),
(7, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Chocolate Lava Cake', NULL, 0, '2026-02-15 09:00:00'),
(8, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Cajun Chicken Alfredo', NULL, 0, '2026-05-22 15:43:49'),
(9, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Chips and Salsa Salad', NULL, 0, '2026-05-22 15:43:50'),
(10, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Lentil Soup', NULL, 0, '2026-05-22 15:43:50'),
(11, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Chicken Quesadillas', NULL, 0, '2026-05-22 15:43:50'),
(12, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Grilled Lamb Chops', NULL, 0, '2026-05-22 15:43:50'),
(13, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Tom Yum Soup', NULL, 0, '2026-05-22 15:43:50'),
(14, 1, 'recipe', 'Công thức mới', 'Gordon Ramsay đã đăng một công thức mới: Baked Macaroni and Cheese', NULL, 0, '2026-05-22 19:05:07'),
(15, 1, 'recipe', 'Công thức mới', 'Thu Hòa đã đăng một công thức mới: Classic Spaghetti Carbonara', NULL, 0, '2026-05-22 15:43:50'),
(16, 1, 'recipe', 'Công thức mới', 'Thu Hòa đã đăng một công thức mới: Asparagus and Lemon Tart', NULL, 0, '2026-05-22 15:43:50'),
(17, 1, 'recipe', 'Công thức mới', 'Thu Hòa đã đăng một công thức mới: Mango Banana Smoothie', NULL, 0, '2026-05-22 19:05:07'),
(18, 1, 'recipe', 'Công thức mới', 'Thu Hòa đã đăng một công thức mới: Spaghetti Aglio e Olio', NULL, 0, '2026-05-22 19:05:07'),
(19, 1, 'recipe', 'Công thức mới', 'Cẩm Anh đã đăng một công thức mới: Beef Teriyaki Bowl', NULL, 0, '2026-02-20 04:00:00'),
(20, 1, 'recipe', 'Công thức mới', 'Cẩm Anh đã đăng một công thức mới: Caprese Salad', NULL, 0, '2026-05-22 15:43:50'),
(21, 1, 'recipe', 'Công thức mới', 'Cẩm Anh đã đăng một công thức mới: Caesar Salad', NULL, 0, '2026-05-22 15:43:50'),
(22, 1, 'recipe', 'Công thức mới', 'Cẩm Anh đã đăng một công thức mới: Garlic Butter Bread', NULL, 0, '2026-05-22 19:05:07'),
(23, 1, 'recipe', 'Công thức mới', 'Cẩm Anh đã đăng một công thức mới: Classic Mojito', NULL, 0, '2026-05-22 19:05:07'),
(24, 1, 'recipe', 'Công thức mới', 'Cẩm Anh đã đăng một công thức mới: Creamy Pumpkin Soup', NULL, 0, '2026-05-22 19:05:07'),
(25, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Caprese Bruschetta', NULL, 0, '2026-03-10 03:00:00'),
(26, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Creamy Chicken Mushroom Florentine Pasta', NULL, 0, '2026-05-22 15:43:49'),
(27, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Pesto Genovese Pasta', NULL, 0, '2026-05-22 15:43:50'),
(28, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Lasagna Bolognese', NULL, 0, '2026-05-22 15:43:50'),
(29, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: New York Strip Steak', NULL, 0, '2026-05-22 15:43:50'),
(30, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Vietnamese Pho Bo', NULL, 0, '2026-05-22 15:43:50'),
(31, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Grilled Corn on the Cob', NULL, 0, '2026-05-22 15:43:50'),
(32, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Watermelon Mint Cooler', NULL, 0, '2026-05-22 15:43:50'),
(33, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Korean Fried Chicken', NULL, 0, '2026-05-22 19:05:07'),
(34, 1, 'recipe', 'Công thức mới', 'Rachael Ray đã đăng một công thức mới: Beef Stew', NULL, 0, '2026-05-22 19:05:07'),
(35, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Grilled Salmon with Asparagus', NULL, 0, '2026-01-15 03:00:00'),
(36, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Chicken Marsala', NULL, 0, '2026-02-10 05:00:00'),
(37, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Avocado Toast with Poached Egg', NULL, 0, '2026-03-01 00:30:00'),
(38, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Pad Thai Noodles', NULL, 0, '2026-03-05 06:00:00'),
(39, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Eggs Benedict', NULL, 0, '2026-05-22 15:43:49'),
(40, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Crème Brûlée', NULL, 0, '2026-05-22 15:43:50'),
(41, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Grilled Chicken Salad', NULL, 0, '2026-05-22 19:05:07'),
(42, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Japanese Onigiri (Rice Balls)', NULL, 0, '2026-05-22 19:05:07'),
(43, 1, 'recipe', 'Công thức mới', 'Minh Đức đã đăng một công thức mới: Creamy Mashed Potatoes', NULL, 0, '2026-05-22 19:05:07'),
(44, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Creamy Chicken Mushroom Pasta', NULL, 0, '2026-01-20 07:00:00'),
(45, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Fluffy Buttermilk Pancakes', NULL, 0, '2026-05-22 15:43:21'),
(46, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Mushroom Risotto', NULL, 0, '2026-05-22 15:43:50'),
(47, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Eggplant Parmesan', NULL, 0, '2026-05-22 15:43:50'),
(48, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Miso Ramen', NULL, 0, '2026-05-22 15:43:50'),
(49, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Classic Tiramisu', NULL, 0, '2026-05-22 15:43:50'),
(50, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Sausage Fried Rice', NULL, 0, '2026-05-22 19:05:07'),
(51, 1, 'recipe', 'Công thức mới', 'Jamie Oliver đã đăng một công thức mới: Crispy Onion Rings', NULL, 0, '2026-05-22 19:05:07'),
(52, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: BBQ Smoked Ribs', NULL, 0, '2026-03-15 02:00:00'),
(53, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: Strawberry Spinach Salad', NULL, 0, '2026-05-22 15:43:50'),
(54, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: Radish and Butter Tartine', NULL, 0, '2026-05-22 15:43:50'),
(55, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: French Onion Soup', NULL, 0, '2026-05-22 15:43:50'),
(56, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: Avocado Tuna Salad', NULL, 0, '2026-05-22 19:05:07'),
(57, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: Homemade Pizza Bites', NULL, 0, '2026-05-22 19:05:07'),
(58, 1, 'recipe', 'Công thức mới', ' đã đăng một công thức mới: bún riêu cua', NULL, 0, '2026-05-23 19:39:10'),
(59, 9, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về BBQ Smoked Ribs', NULL, 1, '2026-05-10 13:45:36'),
(60, 4, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Classic Spaghetti Carbonara', NULL, 0, '2026-05-16 13:45:36'),
(61, 3, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Chips and Salsa Salad', NULL, 0, '2026-05-01 13:45:36'),
(62, 3, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Chips and Salsa Salad', NULL, 0, '2026-05-07 13:45:36'),
(63, 3, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Lentil Soup', NULL, 0, '2026-05-05 13:45:36'),
(64, 9, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Strawberry Spinach Salad', NULL, 1, '2026-05-14 13:45:36'),
(65, 3, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Tom Yum Soup', NULL, 0, '2026-04-24 13:45:36'),
(66, 6, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Watermelon Mint Cooler', NULL, 0, '2026-05-19 13:45:36'),
(67, 5, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Garlic Butter Bread', NULL, 0, '2026-04-25 13:45:36'),
(68, 5, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Classic Mojito', NULL, 0, '2026-05-22 13:45:36'),
(69, 8, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Sausage Fried Rice', NULL, 0, '2026-05-11 13:45:36'),
(70, 9, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Homemade Pizza Bites', NULL, 1, '2026-05-07 13:45:36'),
(71, 7, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Chicken Marsala', NULL, 0, '2026-05-07 13:45:36'),
(72, 7, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Pad Thai Noodles', NULL, 0, '2026-05-05 13:45:36'),
(73, 6, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Caprese Bruschetta', NULL, 0, '2026-05-16 13:45:36'),
(74, 5, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Caprese Salad', NULL, 0, '2026-05-22 13:45:36'),
(75, 6, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về New York Strip Steak', NULL, 0, '2026-05-03 13:45:36'),
(76, 8, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Miso Ramen', NULL, 0, '2026-05-03 13:45:36'),
(77, 1, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Spring Pea and Mint Soup', NULL, 0, '2026-04-28 13:45:36'),
(78, 4, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Asparagus and Lemon Tart', NULL, 0, '2026-05-09 13:45:36'),
(79, 6, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Grilled Corn on the Cob', NULL, 0, '2026-05-19 13:45:36'),
(80, 5, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Caesar Salad', NULL, 0, '2026-05-21 13:45:36'),
(81, 7, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Crème Brûlée', NULL, 0, '2026-05-15 13:45:36'),
(82, 9, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về French Onion Soup', NULL, 1, '2026-04-24 13:45:36'),
(83, 6, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Watermelon Mint Cooler', NULL, 0, '2026-05-15 13:45:36'),
(84, 6, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Watermelon Mint Cooler', NULL, 0, '2026-05-04 13:45:36'),
(85, 2, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Classic Cheeseburger', NULL, 0, '2026-05-06 13:45:36'),
(86, 2, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Classic Cheeseburger', NULL, 0, '2026-04-30 13:45:36'),
(87, 7, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Grilled Chicken Salad', NULL, 0, '2026-05-16 13:45:36'),
(88, 1, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Iced Peach Tea', NULL, 0, '2026-05-22 13:45:36'),
(89, 8, 'comment', 'Bình luận mới', 'Gordon Ramsay đã bình luận về Crispy Onion Rings', NULL, 0, '2026-05-11 13:45:36'),
(90, 7, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Chicken Marsala', NULL, 0, '2026-04-30 13:45:36'),
(91, 5, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Beef Teriyaki Bowl', NULL, 0, '2026-05-15 13:45:36'),
(92, 8, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Fluffy Buttermilk Pancakes', NULL, 0, '2026-05-06 13:45:36'),
(93, 3, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Lentil Soup', NULL, 0, '2026-05-01 13:45:36'),
(94, 6, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về New York Strip Steak', NULL, 0, '2026-05-16 13:45:36'),
(95, 6, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về New York Strip Steak', NULL, 0, '2026-05-05 13:45:36'),
(96, 6, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Vietnamese Pho Bo', NULL, 0, '2026-05-10 13:45:36'),
(97, 6, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Vietnamese Pho Bo', NULL, 0, '2026-05-19 13:45:36'),
(98, 8, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Classic Tiramisu', NULL, 0, '2026-05-02 13:45:36'),
(99, 5, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Garlic Butter Bread', NULL, 0, '2026-04-24 13:45:36'),
(100, 5, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Garlic Butter Bread', NULL, 0, '2026-04-29 13:45:36'),
(101, 6, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Beef Stew', NULL, 0, '2026-05-13 13:45:36'),
(102, 8, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Sausage Fried Rice', NULL, 0, '2026-04-23 13:45:36'),
(103, 9, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Avocado Tuna Salad', NULL, 1, '2026-05-07 13:45:36'),
(104, 8, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Crispy Onion Rings', NULL, 0, '2026-04-25 13:45:36'),
(105, 2, 'comment', 'Bình luận mới', 'Thu Hòa đã bình luận về Truffle Mushroom Soup', NULL, 0, '2026-04-26 13:45:36'),
(106, 7, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Grilled Salmon with Asparagus', NULL, 0, '2026-05-12 13:45:36'),
(107, 8, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Creamy Chicken Mushroom Pasta', NULL, 0, '2026-05-01 13:45:36'),
(108, 3, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Classic French Toast', NULL, 0, '2026-05-05 13:45:36'),
(109, 7, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Chicken Marsala', NULL, 0, '2026-05-10 13:45:36'),
(110, 3, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Chocolate Lava Cake', NULL, 0, '2026-05-15 13:45:36'),
(111, 7, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Avocado Toast with Poached Egg', NULL, 0, '2026-05-22 13:45:36'),
(112, 7, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Pad Thai Noodles', NULL, 0, '2026-05-14 13:45:36'),
(113, 8, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Fluffy Buttermilk Pancakes', NULL, 0, '2026-05-23 13:45:36'),
(114, 2, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Açaí Breakfast Bowl', NULL, 0, '2026-04-28 13:45:36'),
(115, 3, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Cajun Chicken Alfredo', NULL, 0, '2026-05-22 13:45:36'),
(116, 8, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Mushroom Risotto', NULL, 0, '2026-05-22 13:45:36'),
(117, 1, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Chicken Breast in Honey-Balsamic Glaze', NULL, 0, '2026-05-19 13:45:36'),
(118, 1, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Spring Pea and Mint Soup', NULL, 0, '2026-05-17 13:45:36'),
(119, 9, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Strawberry Spinach Salad', NULL, 1, '2026-05-15 13:45:36'),
(120, 9, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Radish and Butter Tartine', NULL, 1, '2026-05-04 13:45:36'),
(121, 3, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Grilled Lamb Chops', NULL, 0, '2026-05-12 13:45:36'),
(122, 3, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Grilled Lamb Chops', NULL, 0, '2026-05-03 13:45:36'),
(123, 7, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Japanese Onigiri (Rice Balls)', NULL, 0, '2026-05-11 13:45:36'),
(124, 7, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Creamy Mashed Potatoes', NULL, 0, '2026-05-05 13:45:36'),
(125, 1, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Iced Peach Tea', NULL, 0, '2026-05-21 13:45:36'),
(126, 6, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Korean Fried Chicken', NULL, 0, '2026-05-22 13:45:36'),
(127, 6, 'comment', 'Bình luận mới', 'Cẩm Anh đã bình luận về Beef Stew', NULL, 0, '2026-05-07 13:45:36'),
(128, 8, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Creamy Chicken Mushroom Pasta', NULL, 0, '2026-05-23 13:45:36'),
(129, 7, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Avocado Toast with Poached Egg', NULL, 0, '2026-05-19 13:45:36'),
(130, 4, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Classic Spaghetti Carbonara', NULL, 0, '2026-04-24 13:45:36'),
(131, 8, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Eggplant Parmesan', NULL, 0, '2026-05-13 13:45:36'),
(132, 1, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Chicken Breast in Honey-Balsamic Glaze', NULL, 0, '2026-05-13 13:45:36'),
(133, 8, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Miso Ramen', NULL, 0, '2026-05-11 13:45:36'),
(134, 9, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Radish and Butter Tartine', NULL, 1, '2026-05-05 13:45:36'),
(135, 7, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Crème Brûlée', NULL, 0, '2026-05-23 13:45:36'),
(136, 7, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Japanese Onigiri (Rice Balls)', NULL, 0, '2026-05-09 13:45:36'),
(137, 7, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Creamy Mashed Potatoes', NULL, 0, '2026-04-30 13:45:36'),
(138, 2, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Roasted Asparagus', NULL, 0, '2026-05-21 13:45:36'),
(139, 5, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Classic Mojito', NULL, 0, '2026-05-14 13:45:36'),
(140, 4, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Mango Banana Smoothie', NULL, 0, '2026-04-30 13:45:36'),
(141, 1, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Iced Peach Tea', NULL, 0, '2026-05-17 13:45:36'),
(142, 4, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Spaghetti Aglio e Olio', NULL, 0, '2026-05-09 13:45:36'),
(143, 5, 'comment', 'Bình luận mới', 'Rachael Ray đã bình luận về Creamy Pumpkin Soup', NULL, 0, '2026-05-14 13:45:36'),
(144, 2, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Açaí Breakfast Bowl', NULL, 0, '2026-05-01 13:45:36'),
(145, 4, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Classic Spaghetti Carbonara', NULL, 0, '2026-04-30 13:45:36'),
(146, 6, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Lasagna Bolognese', NULL, 0, '2026-05-03 13:45:36'),
(147, 3, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Chips and Salsa Salad', NULL, 0, '2026-04-28 13:45:36'),
(148, 8, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Mushroom Risotto', NULL, 0, '2026-05-23 13:45:36'),
(149, 5, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Caprese Salad', NULL, 0, '2026-05-21 13:45:36'),
(150, 3, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Chicken Quesadillas', NULL, 0, '2026-05-07 13:45:36'),
(151, 6, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Grilled Corn on the Cob', NULL, 0, '2026-05-18 13:45:36'),
(152, 8, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Classic Tiramisu', NULL, 0, '2026-05-14 13:45:36'),
(153, 2, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Classic Cheeseburger', NULL, 0, '2026-05-18 13:45:36'),
(154, 2, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Roasted Asparagus', NULL, 0, '2026-05-07 13:45:36'),
(155, 5, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Classic Mojito', NULL, 0, '2026-05-18 13:45:36'),
(156, 1, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Iced Peach Tea', NULL, 0, '2026-05-23 13:45:36'),
(157, 6, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Korean Fried Chicken', NULL, 0, '2026-05-09 13:45:36'),
(158, 9, 'comment', 'Bình luận mới', 'Minh Đức đã bình luận về Avocado Tuna Salad', NULL, 1, '2026-04-26 13:45:36'),
(159, 7, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Grilled Salmon with Asparagus', NULL, 0, '2026-04-24 13:45:36'),
(160, 7, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Pad Thai Noodles', NULL, 0, '2026-05-19 13:45:36'),
(161, 7, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Eggs Benedict', NULL, 0, '2026-05-10 13:45:36'),
(162, 6, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Creamy Chicken Mushroom Florentine Pasta', NULL, 0, '2026-05-09 13:45:36'),
(163, 2, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Creamy Chicken and Bacon Pasta', NULL, 0, '2026-04-30 13:45:36'),
(164, 6, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Pesto Genovese Pasta', NULL, 0, '2026-05-07 13:45:36'),
(165, 3, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Chips and Salsa Salad', NULL, 0, '2026-05-16 13:45:36'),
(166, 1, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Chicken Breast in Honey-Balsamic Glaze', NULL, 0, '2026-05-21 13:45:36'),
(167, 6, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về New York Strip Steak', NULL, 0, '2026-05-21 13:45:36'),
(168, 1, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Spring Pea and Mint Soup', NULL, 0, '2026-04-26 13:45:36'),
(169, 9, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Radish and Butter Tartine', NULL, 1, '2026-05-04 13:45:36'),
(170, 6, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Grilled Corn on the Cob', NULL, 0, '2026-05-17 13:45:36'),
(171, 9, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về French Onion Soup', NULL, 1, '2026-05-06 13:45:36'),
(172, 3, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Tom Yum Soup', NULL, 0, '2026-05-04 13:45:36'),
(173, 6, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Watermelon Mint Cooler', NULL, 0, '2026-05-13 13:45:36'),
(174, 7, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Grilled Chicken Salad', NULL, 0, '2026-05-21 13:45:36'),
(175, 5, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Garlic Butter Bread', NULL, 0, '2026-04-24 13:45:36'),
(176, 5, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Classic Mojito', NULL, 0, '2026-05-09 13:45:36'),
(177, 6, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Korean Fried Chicken', NULL, 0, '2026-05-16 13:45:36'),
(178, 5, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Creamy Pumpkin Soup', NULL, 0, '2026-05-19 13:45:36'),
(179, 5, 'comment', 'Bình luận mới', 'Jamie Oliver đã bình luận về Creamy Pumpkin Soup', NULL, 0, '2026-05-15 13:45:36'),
(180, 6, 'comment', 'Bình luận mới', ' đã bình luận về Pesto Genovese Pasta', NULL, 0, '2026-05-23 10:10:08'),
(181, 6, 'comment', 'Bình luận mới', ' đã bình luận về Pesto Genovese Pasta', NULL, 0, '2026-05-23 10:10:23'),
(182, 6, 'comment', 'Bình luận mới', ' đã bình luận về Pesto Genovese Pasta', NULL, 0, '2026-05-23 18:09:59'),
(183, 8, 'comment', 'Bình luận mới', ' đã bình luận về Mushroom Risotto', NULL, 0, '2026-05-23 18:11:07'),
(184, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Grilled Salmon with Asparagus', NULL, 0, '2026-05-22 19:22:46'),
(185, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Creamy Chicken Mushroom Pasta', NULL, 0, '2026-05-22 19:23:36'),
(186, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Classic French Toast', NULL, 0, '2026-05-22 19:23:36'),
(187, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Chicken Marsala', NULL, 0, '2026-05-22 19:23:36'),
(188, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Chocolate Lava Cake', NULL, 0, '2026-05-22 19:23:36'),
(189, 5, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Beef Teriyaki Bowl', NULL, 0, '2026-05-22 19:23:36'),
(190, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Avocado Toast with Poached Egg', NULL, 0, '2026-05-22 19:23:36'),
(191, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Pad Thai Noodles', NULL, 0, '2026-05-22 19:23:36'),
(192, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Caprese Bruschetta', NULL, 0, '2026-05-22 19:23:36'),
(193, 9, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho BBQ Smoked Ribs', NULL, 1, '2026-05-22 19:23:36'),
(194, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Fluffy Buttermilk Pancakes', NULL, 0, '2026-05-22 19:23:36'),
(195, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Eggs Benedict', NULL, 0, '2026-05-22 19:23:36'),
(196, 2, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Açaí Breakfast Bowl', NULL, 0, '2026-05-22 19:23:36'),
(197, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Creamy Chicken Mushroom Florentine Pasta', NULL, 0, '2026-05-22 19:23:36'),
(198, 2, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Creamy Chicken and Bacon Pasta', NULL, 0, '2026-05-22 19:23:36'),
(199, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Cajun Chicken Alfredo', NULL, 0, '2026-05-22 19:23:36'),
(200, 4, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Classic Spaghetti Carbonara', NULL, 0, '2026-05-22 19:23:36'),
(201, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Pesto Genovese Pasta', NULL, 0, '2026-05-22 19:23:36'),
(202, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Lasagna Bolognese', NULL, 0, '2026-05-22 19:23:36'),
(203, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Chips and Salsa Salad', NULL, 0, '2026-05-22 19:23:36'),
(204, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Mushroom Risotto', NULL, 0, '2026-05-22 19:23:36'),
(205, 5, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Caprese Salad', NULL, 0, '2026-05-22 19:23:36'),
(206, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Eggplant Parmesan', NULL, 0, '2026-05-22 19:23:36'),
(207, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Lentil Soup', NULL, 0, '2026-05-22 19:23:36'),
(208, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho New York Strip Steak', NULL, 0, '2026-05-22 19:23:36'),
(209, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Chicken Quesadillas', NULL, 0, '2026-05-22 19:23:36'),
(210, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Vietnamese Pho Bo', NULL, 0, '2026-05-22 19:23:36'),
(211, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Miso Ramen', NULL, 0, '2026-05-22 19:23:36'),
(212, 9, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Strawberry Spinach Salad', NULL, 1, '2026-05-22 19:23:36'),
(213, 4, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Asparagus and Lemon Tart', NULL, 0, '2026-05-22 19:23:36'),
(214, 9, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Radish and Butter Tartine', NULL, 1, '2026-05-22 19:23:36'),
(215, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Grilled Corn on the Cob', NULL, 0, '2026-05-22 19:23:36'),
(216, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Grilled Lamb Chops', NULL, 0, '2026-05-22 19:23:36'),
(217, 5, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Caesar Salad', NULL, 0, '2026-05-22 19:23:36'),
(218, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Classic Tiramisu', NULL, 0, '2026-05-22 19:23:36'),
(219, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Crème Brûlée', NULL, 0, '2026-05-22 19:23:36'),
(220, 9, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho French Onion Soup', NULL, 1, '2026-05-22 19:23:36'),
(221, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Tom Yum Soup', NULL, 0, '2026-05-22 19:23:36'),
(222, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Watermelon Mint Cooler', NULL, 0, '2026-05-22 19:23:36'),
(223, 2, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Classic Cheeseburger', NULL, 0, '2026-05-22 19:23:36'),
(224, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Grilled Chicken Salad', NULL, 0, '2026-05-22 19:23:36'),
(225, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Japanese Onigiri (Rice Balls)', NULL, 0, '2026-05-22 19:23:36'),
(226, 5, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Garlic Butter Bread', NULL, 0, '2026-05-22 19:23:36'),
(227, 7, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Creamy Mashed Potatoes', NULL, 0, '2026-05-22 19:23:36'),
(228, 2, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Roasted Asparagus', NULL, 0, '2026-05-22 19:23:36'),
(229, 5, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Classic Mojito', NULL, 0, '2026-05-22 19:23:36'),
(230, 4, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Mango Banana Smoothie', NULL, 0, '2026-05-22 19:23:36'),
(231, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Korean Fried Chicken', NULL, 0, '2026-05-22 19:23:37'),
(232, 3, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Baked Macaroni and Cheese', NULL, 0, '2026-05-22 19:23:37'),
(233, 6, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Beef Stew', NULL, 0, '2026-05-22 19:23:37'),
(234, 4, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Spaghetti Aglio e Olio', NULL, 0, '2026-05-22 19:23:37'),
(235, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 4 sao cho Sausage Fried Rice', NULL, 0, '2026-05-22 19:23:37'),
(236, 9, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Avocado Tuna Salad', NULL, 1, '2026-05-22 19:23:37'),
(237, 9, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Homemade Pizza Bites', NULL, 1, '2026-05-22 19:23:37'),
(238, 8, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 5 sao cho Crispy Onion Rings', NULL, 0, '2026-05-22 19:23:37'),
(239, 5, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Creamy Pumpkin Soup', NULL, 0, '2026-05-22 19:23:37'),
(240, 2, 'rating', 'Đánh giá mới', 'Admin Chef đã đánh giá 3 sao cho Truffle Mushroom Soup', NULL, 0, '2026-05-22 19:23:37'),
(241, 8, 'rating', 'Đánh giá mới', 'Nguyễn Linh đã đánh giá 5 sao cho Creamy Chicken Mushroom Pasta', NULL, 0, '2026-01-25 07:00:00'),
(242, 7, 'rating', 'Đánh giá mới', 'Nguyễn Linh đã đánh giá 4 sao cho Chicken Marsala', NULL, 0, '2026-02-12 05:00:00'),
(243, 3, 'rating', 'Đánh giá mới', 'Nguyễn Linh đã đánh giá 5 sao cho Chocolate Lava Cake', NULL, 0, '2026-02-18 09:00:00'),
(244, 7, 'rating', 'Đánh giá mới', 'Nguyễn Linh đã đánh giá 5 sao cho Pad Thai Noodles', NULL, 0, '2026-03-08 06:00:00'),
(245, 9, 'rating', 'Đánh giá mới', 'Nguyễn Linh đã đánh giá 5 sao cho BBQ Smoked Ribs', NULL, 1, '2026-03-18 02:00:00'),
(246, 7, 'rating', 'Đánh giá mới', 'Gordon Ramsay đã đánh giá 5 sao cho Grilled Salmon with Asparagus', NULL, 0, '2026-01-20 03:00:00'),
(247, 5, 'rating', 'Đánh giá mới', 'Gordon Ramsay đã đánh giá 4 sao cho Beef Teriyaki Bowl', NULL, 0, '2026-02-25 04:00:00'),
(248, 6, 'rating', 'Đánh giá mới', 'Gordon Ramsay đã đánh giá 4 sao cho Caprese Bruschetta', NULL, 0, '2026-03-12 03:00:00'),
(249, 9, 'rating', 'Đánh giá mới', 'Gordon Ramsay đã đánh giá 5 sao cho BBQ Smoked Ribs', NULL, 1, '2026-03-19 03:00:00'),
(250, 7, 'rating', 'Đánh giá mới', 'Thu Hòa đã đánh giá 4 sao cho Grilled Salmon with Asparagus', NULL, 0, '2026-01-21 04:00:00'),
(251, 8, 'rating', 'Đánh giá mới', 'Thu Hòa đã đánh giá 5 sao cho Creamy Chicken Mushroom Pasta', NULL, 0, '2026-01-26 09:00:00'),
(252, 3, 'rating', 'Đánh giá mới', 'Thu Hòa đã đánh giá 5 sao cho Chocolate Lava Cake', NULL, 0, '2026-02-20 04:00:00'),
(253, 7, 'rating', 'Đánh giá mới', 'Thu Hòa đã đánh giá 5 sao cho Avocado Toast with Poached Egg', NULL, 0, '2026-03-05 01:00:00'),
(254, 7, 'rating', 'Đánh giá mới', 'Cẩm Anh đã đánh giá 5 sao cho Grilled Salmon with Asparagus', NULL, 0, '2026-01-22 02:00:00'),
(255, 7, 'rating', 'Đánh giá mới', 'Cẩm Anh đã đánh giá 4 sao cho Avocado Toast with Poached Egg', NULL, 0, '2026-03-06 02:00:00'),
(256, 9, 'rating', 'Đánh giá mới', 'Cẩm Anh đã đánh giá 5 sao cho BBQ Smoked Ribs', NULL, 1, '2026-03-20 04:00:00'),
(257, 8, 'rating', 'Đánh giá mới', 'Rachael Ray đã đánh giá 4 sao cho Creamy Chicken Mushroom Pasta', NULL, 0, '2026-01-27 03:00:00'),
(258, 7, 'rating', 'Đánh giá mới', 'Rachael Ray đã đánh giá 5 sao cho Avocado Toast with Poached Egg', NULL, 0, '2026-03-07 03:00:00'),
(259, 3, 'rating', 'Đánh giá mới', 'Minh Đức đã đánh giá 4 sao cho Classic French Toast', NULL, 0, '2026-02-06 02:00:00'),
(260, 3, 'rating', 'Đánh giá mới', 'Minh Đức đã đánh giá 4 sao cho Chocolate Lava Cake', NULL, 0, '2026-02-21 02:00:00'),
(261, 7, 'rating', 'Đánh giá mới', 'Jamie Oliver đã đánh giá 5 sao cho Chicken Marsala', NULL, 0, '2026-02-13 07:00:00'),
(262, 6, 'rating', 'Đánh giá mới', 'Jamie Oliver đã đánh giá 4 sao cho Caprese Bruschetta', NULL, 0, '2026-03-13 04:00:00'),
(263, 6, 'rating', 'Đánh giá mới', ' đã đánh giá 3 sao cho Pesto Genovese Pasta', NULL, 0, '2026-05-23 09:15:35'),
(264, 6, 'rating', 'Đánh giá mới', ' đã đánh giá 4 sao cho Lasagna Bolognese', NULL, 0, '2026-05-23 18:10:21'),
(265, 3, 'rating', 'Đánh giá mới', ' đã đánh giá 5 sao cho Chips and Salsa Salad', NULL, 0, '2026-05-23 18:10:35'),
(266, 9, 'bookmark', 'Lượt lưu mới', 'Nguyễn Linh đã lưu công thức Avocado Tuna Salad của bạn', '/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=65', 1, '2026-05-24 11:54:17'),
(267, 9, 'comment', 'Bình luận mới', 'Nguyễn Linh đã bình luận về Avocado Tuna Salad', '/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=65', 1, '2026-05-24 11:54:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ratings`
--

INSERT INTO `ratings` (`id`, `recipe_id`, `user_id`, `rating`, `created_at`, `updated_at`) VALUES
(1, 10, 3, 5, '2026-01-20 03:00:00', '2026-05-17 18:08:42'),
(2, 10, 4, 4, '2026-01-21 04:00:00', '2026-05-17 18:08:42'),
(3, 10, 5, 5, '2026-01-22 02:00:00', '2026-05-17 18:08:42'),
(4, 11, 2, 5, '2026-01-25 07:00:00', '2026-05-17 18:08:42'),
(5, 11, 4, 5, '2026-01-26 09:00:00', '2026-05-17 18:08:42'),
(6, 11, 6, 4, '2026-01-27 03:00:00', '2026-05-17 18:08:42'),
(7, 12, 3, 5, '2026-02-05 01:00:00', '2026-05-17 18:08:42'),
(8, 12, 7, 4, '2026-02-06 02:00:00', '2026-05-17 18:08:42'),
(9, 13, 2, 4, '2026-02-12 05:00:00', '2026-05-17 18:08:42'),
(10, 13, 8, 5, '2026-02-13 07:00:00', '2026-05-17 18:08:42'),
(11, 14, 2, 5, '2026-02-18 09:00:00', '2026-05-17 18:08:42'),
(12, 14, 3, 5, '2026-02-19 03:00:00', '2026-05-17 18:08:42'),
(13, 14, 4, 5, '2026-02-20 04:00:00', '2026-05-17 18:08:42'),
(14, 14, 7, 4, '2026-02-21 02:00:00', '2026-05-17 18:08:42'),
(15, 15, 3, 4, '2026-02-25 04:00:00', '2026-05-17 18:08:42'),
(16, 15, 5, 5, '2026-02-26 06:00:00', '2026-05-17 18:08:42'),
(17, 16, 4, 5, '2026-03-05 01:00:00', '2026-05-17 18:08:42'),
(18, 16, 5, 4, '2026-03-06 02:00:00', '2026-05-17 18:08:42'),
(19, 16, 6, 5, '2026-03-07 03:00:00', '2026-05-17 18:08:42'),
(20, 17, 2, 5, '2026-03-08 06:00:00', '2026-05-17 18:08:42'),
(21, 17, 7, 4, '2026-03-09 07:00:00', '2026-05-17 18:08:42'),
(22, 18, 3, 4, '2026-03-12 03:00:00', '2026-05-17 18:08:42'),
(23, 18, 8, 4, '2026-03-13 04:00:00', '2026-05-17 18:08:42'),
(24, 19, 2, 5, '2026-03-18 02:00:00', '2026-05-17 18:08:42'),
(25, 19, 3, 5, '2026-03-19 03:00:00', '2026-05-17 18:08:42'),
(26, 19, 5, 5, '2026-03-20 04:00:00', '2026-05-17 18:08:42'),
(30, 10, 1, 5, '2026-05-22 19:22:46', '2026-05-22 19:22:46'),
(33, 11, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(34, 12, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(35, 13, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(36, 14, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(37, 15, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(38, 16, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(39, 17, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(40, 18, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(41, 19, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(42, 20, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(43, 21, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(44, 22, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(45, 23, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(46, 24, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(47, 25, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(48, 26, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(49, 27, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(50, 28, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(51, 29, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(52, 30, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(53, 31, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(54, 32, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(55, 33, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(56, 34, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(57, 35, 1, 3, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(58, 36, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(59, 37, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(60, 38, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(61, 39, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(62, 40, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(63, 41, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(64, 42, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(65, 43, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(66, 44, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(67, 45, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(68, 46, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(69, 47, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(70, 48, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(71, 49, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(72, 50, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(73, 51, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(74, 52, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(75, 53, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(76, 54, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(77, 55, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(78, 56, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(79, 57, 1, 4, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(80, 58, 1, 5, '2026-05-22 19:23:36', '2026-05-22 19:23:36'),
(81, 59, 1, 4, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(82, 60, 1, 5, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(83, 61, 1, 4, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(84, 62, 1, 5, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(85, 63, 1, 4, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(86, 64, 1, 4, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(87, 65, 1, 3, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(88, 66, 1, 5, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(89, 67, 1, 5, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(90, 68, 1, 3, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(91, 69, 1, 3, '2026-05-22 19:23:37', '2026-05-22 19:23:37'),
(92, 27, 9, 3, '2026-05-23 09:15:35', '2026-05-23 09:19:32'),
(93, 28, 9, 4, '2026-05-23 18:10:21', '2026-05-23 18:10:29'),
(94, 29, 9, 5, '2026-05-23 18:10:35', '2026-05-23 18:10:38'),
(95, 40, 9, 5, '2026-05-23 19:26:57', '2026-05-23 19:27:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `prep_time` int(11) DEFAULT NULL COMMENT 'Preparation time in minutes',
  `cook_time` int(11) DEFAULT NULL COMMENT 'Cooking time in minutes',
  `total_time` int(11) DEFAULT NULL COMMENT 'Total time in minutes',
  `servings` int(11) DEFAULT 4,
  `difficulty` enum('Easy','Medium','Hard') DEFAULT 'Medium',
  `main_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `category_id`, `title`, `slug`, `description`, `prep_time`, `cook_time`, `total_time`, `servings`, `difficulty`, `main_image`, `is_published`, `is_featured`, `view_count`, `created_at`, `updated_at`) VALUES
(10, 7, 1, 'Grilled Salmon with Asparagus', 'grilled-salmon-asparagus', 'Perfectly grilled salmon with fresh asparagus and lemon butter sauce. A healthy and elegant dinner.', 10, 25, 35, 2, 'Easy', 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400&h=300&fit=crop', 1, 1, 1250, '2026-01-15 03:00:00', '2026-05-23 19:00:41'),
(11, 8, 3, 'Creamy Chicken Mushroom Pasta', 'creamy-chicken-mushroom-pasta', 'Rich and creamy pasta with tender chicken, sautéed mushrooms and fresh spinach in a parmesan cream sauce.', 15, 25, 40, 4, 'Medium', 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&h=300&fit=crop', 1, 1, 2100, '2026-01-20 07:00:00', '2026-05-23 19:00:41'),
(12, 3, 1, 'Classic French Toast', 'classic-french-toast', 'Golden crispy french toast with maple syrup and fresh berries. Perfect weekend breakfast.', 5, 10, 15, 2, 'Easy', 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=400&h=300&fit=crop', 1, 0, 2200, '2026-02-01 01:00:00', '2026-05-23 19:00:41'),
(13, 7, 3, 'Chicken Marsala', 'chicken-marsala-wine', 'Classic Italian-American dish with marsala wine sauce, mushrooms and herbs.', 15, 30, 45, 3, 'Medium', 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=400&h=300&fit=crop', 1, 0, 1800, '2026-02-10 05:00:00', '2026-05-23 19:00:41'),
(14, 3, 5, 'Chocolate Lava Cake', 'chocolate-lava-cake', 'Decadent chocolate cake with a molten center. The ultimate dessert for chocolate lovers.', 15, 12, 27, 4, 'Medium', 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=400&h=300&fit=crop', 1, 1, 3500, '2026-02-15 09:00:00', '2026-05-23 19:00:41'),
(15, 5, 3, 'Beef Teriyaki Bowl', 'beef-teriyaki-bowl', 'Asian-inspired rice bowl with teriyaki glazed beef, steamed broccoli and sesame seeds.', 10, 20, 30, 2, 'Easy', 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400&h=300&fit=crop', 1, 0, 1900, '2026-02-20 04:00:00', '2026-05-23 19:00:41'),
(16, 7, 1, 'Avocado Toast with Poached Egg', 'avocado-toast-poached-egg', 'Creamy avocado on sourdough topped with a perfectly poached egg and chili flakes.', 5, 5, 10, 1, 'Easy', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=400&h=300&fit=crop', 1, 0, 3100, '2026-03-01 00:30:00', '2026-05-23 19:00:41'),
(17, 7, 3, 'Pad Thai Noodles', 'pad-thai-noodles', 'Classic Thai stir-fried rice noodles with shrimp, peanuts and tamarind sauce.', 15, 15, 30, 2, 'Medium', 'https://images.unsplash.com/photo-1559314809-0d155014e29e?w=400&h=300&fit=crop', 1, 0, 1850, '2026-03-05 06:00:00', '2026-05-23 19:00:41'),
(18, 6, 4, 'Caprese Bruschetta', 'caprese-bruschetta', 'Crispy toasted baguette with fresh tomatoes, mozzarella, basil and balsamic glaze.', 10, 5, 15, 4, 'Easy', 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?w=400&h=300&fit=crop', 1, 0, 920, '2026-03-10 03:00:00', '2026-05-23 19:00:41'),
(19, 9, 3, 'BBQ Smoked Ribs', 'bbq-smoked-ribs', 'Fall-off-the-bone smoked ribs with homemade BBQ sauce. Low and slow for maximum flavor.', 30, 180, 210, 4, 'Hard', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400&h=300&fit=crop', 1, 1, 2800, '2026-03-15 02:00:00', '2026-05-23 19:00:41'),
(20, 8, 1, 'Fluffy Buttermilk Pancakes', 'fluffy-buttermilk-pancakes-6a10799990a60', 'Light and fluffy pancakes with butter and maple syrup.', NULL, NULL, 25, 4, 'Easy', 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop', 1, 1, 2800, '2026-05-22 15:43:21', '2026-05-23 19:00:41'),
(21, 7, 1, 'Eggs Benedict', 'eggs-benedict-6a1079b5bb462', 'Classic brunch staple with hollandaise sauce and Canadian bacon.', NULL, NULL, 30, 2, 'Medium', 'https://images.unsplash.com/photo-1608039829572-78524f79c4c7?w=400&h=300&fit=crop', 1, 0, 1600, '2026-05-22 15:43:49', '2026-05-23 19:00:41'),
(22, 2, 1, 'Açaí Breakfast Bowl', 'aa-breakfast-bowl-6a1079b5cb0da', 'Vibrant açaí bowl packed with superfoods and fresh fruit.', NULL, NULL, 10, 1, 'Easy', 'https://images.unsplash.com/photo-1590301157890-4810ed352733?w=400&h=300&fit=crop', 1, 0, 1900, '2026-05-22 15:43:49', '2026-05-23 19:00:41'),
(23, 6, 3, 'Creamy Chicken Mushroom Florentine Pasta', 'creamy-chicken-mushroom-florentine-pasta-6a1079b5d95b0', 'Rich and creamy pasta with chicken, mushrooms and spinach.', NULL, NULL, 40, 4, 'Medium', 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&h=300&fit=crop', 1, 1, 2100, '2026-05-22 15:43:49', '2026-05-23 19:00:41'),
(24, 2, 3, 'Creamy Chicken and Bacon Pasta', 'creamy-chicken-and-bacon-pasta-6a1079b5e9d02', 'Indulgent pasta with chicken, bacon and cream sauce.', NULL, NULL, 35, 4, 'Medium', 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&h=300&fit=crop', 1, 1, 1600, '2026-05-22 15:43:49', '2026-05-23 19:00:41'),
(25, 3, 3, 'Cajun Chicken Alfredo', 'cajun-chicken-alfredo-6a1079b5f33c4', 'Spicy Cajun chicken over creamy Alfredo pasta.', NULL, NULL, 40, 3, 'Medium', 'https://images.unsplash.com/photo-1645112411341-6c4fd023714a?w=400&h=300&fit=crop', 1, 0, 1400, '2026-05-22 15:43:49', '2026-05-23 19:00:41'),
(26, 4, 3, 'Classic Spaghetti Carbonara', 'classic-spaghetti-carbonara-6a1079b608b32', 'Authentic Roman carbonara with eggs, pecorino and guanciale.', NULL, NULL, 20, 2, 'Medium', 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=400&h=300&fit=crop', 1, 1, 3400, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(27, 6, 3, 'Pesto Genovese Pasta', 'pesto-genovese-pasta-6a1079b6188a2', 'Vibrant basil pesto tossed with fresh trofie pasta.', NULL, NULL, 25, 4, 'Easy', 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=400&h=300&fit=crop', 1, 0, 2000, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(28, 6, 3, 'Lasagna Bolognese', 'lasagna-bolognese-6a1079b625a33', 'Layered pasta with rich meat ragu and creamy béchamel.', NULL, NULL, 90, 8, 'Hard', 'https://images.unsplash.com/photo-1619895092538-128341789043?w=400&h=300&fit=crop', 1, 1, 2700, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(29, 3, 7, 'Chips and Salsa Salad', 'chips-and-salsa-salad-6a1079b630cfa', 'Fresh Mexican-inspired salad with crispy tortilla chips.', NULL, NULL, 20, 4, 'Easy', 'https://images.unsplash.com/photo-1546549032-9571cd6b27df?w=400&h=300&fit=crop', 1, 0, 800, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(30, 8, 7, 'Mushroom Risotto', 'mushroom-risotto-6a1079b63de14', 'Creamy arborio rice with wild mushrooms and parmesan.', NULL, NULL, 45, 4, 'Medium', 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=400&h=300&fit=crop', 1, 1, 2300, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(31, 5, 7, 'Caprese Salad', 'caprese-salad-6a1079b645c0c', 'Classic Italian salad with fresh mozzarella, tomatoes, and basil.', NULL, NULL, 10, 4, 'Easy', 'https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5?w=400&h=300&fit=crop', 1, 0, 1100, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(32, 8, 7, 'Eggplant Parmesan', 'eggplant-parmesan-6a1079b64dbb4', 'Breaded and baked eggplant with marinara and melted mozzarella.', NULL, NULL, 60, 6, 'Medium', 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?w=400&h=300&fit=crop', 1, 0, 1500, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(33, 3, 7, 'Lentil Soup', 'lentil-soup-6a1079b655829', 'Hearty and warming red lentil soup with cumin and lemon.', NULL, NULL, 35, 6, 'Easy', 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&h=300&fit=crop', 1, 0, 980, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(34, 1, 3, 'Chicken Breast in Honey-Balsamic Glaze', 'chicken-breast-in-honeybalsamic-glaze-6a1079b65d8da', 'Sweet and tangy glazed chicken breast.', NULL, NULL, 30, 2, 'Easy', 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?w=400&h=300&fit=crop', 1, 0, 950, '2026-05-22 15:43:50', '2026-05-22 15:43:50'),
(35, 6, 3, 'New York Strip Steak', 'new-york-strip-steak-6a1079b664505', 'Perfectly cooked strip steak with herb butter.', NULL, NULL, 25, 2, 'Medium', 'https://images.unsplash.com/photo-1558030006-450675393462?w=400&h=300&fit=crop', 1, 1, 3200, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(36, 3, 3, 'Chicken Quesadillas', 'chicken-quesadillas-6a1079b66c61d', 'Crispy quesadillas filled with chicken and cheese.', NULL, NULL, 25, 2, 'Easy', 'https://images.unsplash.com/photo-1618040996337-56904b7850b9?w=400&h=300&fit=crop', 1, 0, 1100, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(37, 6, 3, 'Vietnamese Pho Bo', 'vietnamese-pho-bo-6a1079b6731c0', 'Aromatic Vietnamese beef noodle soup with fresh herbs.', NULL, NULL, 120, 4, 'Hard', 'https://images.unsplash.com/photo-1576577445504-6af96477db52?w=400&h=300&fit=crop', 1, 1, 2900, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(38, 8, 3, 'Miso Ramen', 'miso-ramen-6a1079b67c353', 'Rich and warming Japanese ramen with miso broth.', NULL, NULL, 45, 2, 'Medium', 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=300&fit=crop', 1, 0, 2600, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(39, 1, 2, 'Spring Pea and Mint Soup', 'spring-pea-and-mint-soup-6a1079b684de6', 'Bright and fresh pea soup celebrating spring flavors.', NULL, NULL, 20, 4, 'Easy', 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&h=300&fit=crop', 1, 0, 870, '2026-05-22 15:43:50', '2026-05-22 15:43:50'),
(40, 9, 2, 'Strawberry Spinach Salad', 'strawberry-spinach-salad-6a1079b68df5d', 'Refreshing spring salad with strawberries and balsamic.', NULL, NULL, 15, 4, 'Easy', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop', 1, 0, 760, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(41, 4, 2, 'Asparagus and Lemon Tart', 'asparagus-and-lemon-tart-6a1079b6962f0', 'Elegant tart with asparagus, ricotta, and lemon zest.', NULL, NULL, 50, 6, 'Medium', 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&h=300&fit=crop', 1, 0, 680, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(42, 9, 2, 'Radish and Butter Tartine', 'radish-and-butter-tartine-6a1079b69e156', 'Classic French open sandwich with radishes and cultured butter.', NULL, NULL, 10, 2, 'Easy', 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?w=400&h=300&fit=crop', 1, 0, 540, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(43, 6, 3, 'Grilled Corn on the Cob', 'grilled-corn-on-the-cob-6a1079b6a66aa', 'Sweet charred corn with chili butter and lime.', NULL, NULL, 20, 4, 'Easy', 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=400&h=300&fit=crop', 1, 0, 1400, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(44, 3, 3, 'Grilled Lamb Chops', 'grilled-lamb-chops-6a1079b6b0d3f', 'Herb-marinated lamb chops grilled to perfection.', NULL, NULL, 30, 2, 'Medium', 'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=400&h=300&fit=crop', 1, 0, 1700, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(45, 5, 2, 'Caesar Salad', 'caesar-salad-6a1079b6b7605', 'Classic caesar with house-made dressing and crunchy croutons.', NULL, NULL, 15, 4, 'Easy', 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=400&h=300&fit=crop', 1, 0, 1300, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(46, 8, 5, 'Classic Tiramisu', 'classic-tiramisu-6a1079b6bf9b6', 'The ultimate Italian no-bake dessert with espresso and mascarpone.', NULL, NULL, 30, 8, 'Medium', 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&h=300&fit=crop', 1, 1, 3100, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(47, 7, 5, 'Crème Brûlée', 'crme-brle-6a1079b6c79b8', 'Silky vanilla custard with caramelized sugar crust.', NULL, NULL, 60, 4, 'Hard', 'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=400&h=300&fit=crop', 1, 0, 1800, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(48, 9, 4, 'French Onion Soup', 'french-onion-soup-6a1079b6cdb89', 'Rich onion soup with melted Gruyere cheese.', NULL, NULL, 60, 4, 'Medium', 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=400&h=300&fit=crop', 1, 0, 1400, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(49, 3, 4, 'Tom Yum Soup', 'tom-yum-soup-6a1079b6d459c', 'Spicy and sour Thai shrimp soup with lemongrass.', NULL, NULL, 30, 4, 'Medium', 'https://images.unsplash.com/photo-1562802378-063ec186a863?w=400&h=300&fit=crop', 1, 0, 1650, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(50, 6, 6, 'Watermelon Mint Cooler', 'watermelon-mint-cooler-6a1079b6ddfae', 'Refreshing summer drink with watermelon and fresh mint.', NULL, NULL, 10, 4, 'Easy', 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=400&h=300&fit=crop', 1, 0, 940, '2026-05-22 15:43:50', '2026-05-23 19:00:41'),
(51, 2, 2, 'Classic Cheeseburger', 'classic-cheeseburger-6a10a8e32c5d2', 'A juicy grilled beef patty topped with melted cheddar cheese on a toasted brioche bun.', NULL, NULL, 20, 2, 'Easy', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(52, 7, 2, 'Grilled Chicken Salad', 'grilled-chicken-salad-6a10a8e331dce', 'Fresh mixed greens topped with perfectly grilled chicken breast and balsamic dressing.', NULL, NULL, 15, 1, 'Easy', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(53, 7, 2, 'Japanese Onigiri (Rice Balls)', 'japanese-onigiri-rice-balls-6a10a8e337c13', 'Traditional Japanese rice balls filled with savory tuna mayo and wrapped in nori.', NULL, NULL, 30, 4, 'Medium', 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(54, 5, 7, 'Garlic Butter Bread', 'garlic-butter-bread-6a10a8e33fc19', 'Crispy baguette slices coated in rich garlic herb butter and toasted to perfection.', NULL, NULL, 15, 4, 'Easy', 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(55, 7, 7, 'Creamy Mashed Potatoes', 'creamy-mashed-potatoes-6a10a8e345df0', 'Smooth and buttery mashed potatoes, the ultimate comforting side dish.', NULL, NULL, 25, 4, 'Easy', 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(56, 2, 7, 'Roasted Asparagus', 'roasted-asparagus-6a10a8e34bc99', 'Tender asparagus spears roasted with olive oil and a hint of lemon.', NULL, NULL, 12, 2, 'Easy', 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(57, 5, 6, 'Classic Mojito', 'classic-mojito-6a10a8e352836', 'Refreshing Cuban cocktail made with fresh mint, lime, white rum, and sparkling water.', NULL, NULL, 5, 1, 'Easy', 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(58, 4, 6, 'Mango Banana Smoothie', 'mango-banana-smoothie-6a10a8e359b4f', 'A sweet and tropical blended drink packed with vitamins and natural sweetness.', NULL, NULL, 5, 2, 'Easy', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(59, 1, 6, 'Iced Peach Tea', 'iced-peach-tea-6a10a8e360123', 'Cold brewed black tea sweetened with homemade peach syrup.', NULL, NULL, 15, 4, 'Medium', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-22 19:05:07'),
(60, 6, 10, 'Korean Fried Chicken', 'korean-fried-chicken-6a10a8e366559', 'Extra crispy twice-fried chicken coated in a sweet and spicy gochujang glaze.', NULL, NULL, 45, 4, 'Hard', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(61, 3, 10, 'Baked Macaroni and Cheese', 'baked-macaroni-and-cheese-6a10a8e36bff0', 'The ultimate comfort food with a rich, cheesy sauce and a crispy breadcrumb topping.', NULL, NULL, 40, 6, 'Medium', 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(62, 6, 10, 'Beef Stew', 'beef-stew-6a10a8e370c02', 'Slow-cooked beef chunks with carrots and potatoes in a thick, savory broth.', NULL, NULL, 120, 4, 'Medium', 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(63, 4, 8, 'Spaghetti Aglio e Olio', 'spaghetti-aglio-e-olio-6a10a8e377128', 'A classic Neapolitan pasta dish made simply with garlic, olive oil, and chili flakes.', NULL, NULL, 15, 2, 'Easy', 'https://images.unsplash.com/photo-1552611052-33e04de081de?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(64, 8, 8, 'Sausage Fried Rice', 'sausage-fried-rice-6a10a8e37c349', 'A lightning-fast weeknight meal using leftover rice and smoked sausage.', NULL, NULL, 10, 2, 'Easy', 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(65, 9, 8, 'Avocado Tuna Salad', 'avocado-tuna-salad-6a10a8e38177d', 'A healthy, mayo-free tuna salad made with creamy avocado and lemon juice.', NULL, NULL, 5, 2, 'Easy', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(66, 9, 4, 'Homemade Pizza Bites', 'homemade-pizza-bites-6a10a8e386d91', 'Bite-sized mini pizzas perfect for parties and snacking.', NULL, NULL, 20, 6, 'Easy', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(67, 8, 4, 'Crispy Onion Rings', 'crispy-onion-rings-6a10a8e38ce3c', 'Thick-cut onion rings battered and fried until golden and crispy.', NULL, NULL, 30, 4, 'Medium', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(68, 5, 11, 'Creamy Pumpkin Soup', 'creamy-pumpkin-soup-6a10a8e392d5e', 'A warm and cozy velvety pumpkin soup spiced with nutmeg and cinnamon.', NULL, NULL, 35, 4, 'Easy', 'https://images.unsplash.com/photo-1603569283847-aa295f0d016a?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41'),
(69, 2, 11, 'Truffle Mushroom Soup', 'truffle-mushroom-soup-6a10a8e399ac7', 'Luxurious and earthy mushroom soup finished with a drizzle of truffle oil.', NULL, NULL, 40, 4, 'Medium', 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?w=400&h=300&fit=crop', 1, 0, 0, '2026-05-22 19:05:07', '2026-05-23 19:00:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recipe_directions`
--

CREATE TABLE `recipe_directions` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `step_number` int(11) NOT NULL,
  `instruction` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recipe_directions`
--

INSERT INTO `recipe_directions` (`id`, `recipe_id`, `step_number`, `instruction`, `image_url`, `created_at`) VALUES
(1, 10, 1, 'Season salmon fillets with salt, pepper and a squeeze of lemon juice. Let rest 10 minutes.', NULL, '2026-05-17 18:08:42'),
(2, 10, 2, 'Heat grill to medium-high. Brush salmon with olive oil.', NULL, '2026-05-17 18:08:42'),
(3, 10, 3, 'Grill salmon 5-6 minutes per side until cooked through.', NULL, '2026-05-17 18:08:42'),
(4, 10, 4, 'Meanwhile, steam asparagus for 3-4 minutes until tender-crisp.', NULL, '2026-05-17 18:08:42'),
(5, 10, 5, 'Melt butter with minced garlic, add lemon zest. Drizzle over salmon and asparagus. Serve immediately.', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(6, 11, 1, 'Cook pasta according to package directions. Reserve 1 cup pasta water before draining.', NULL, '2026-05-17 18:08:42'),
(7, 11, 2, 'Season chicken breast and cook in olive oil until golden, about 6 min per side. Slice.', NULL, '2026-05-17 18:08:42'),
(8, 11, 3, 'In the same pan, sauté sliced mushrooms until golden brown, about 5 minutes.', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(9, 11, 4, 'Add minced garlic, cook 30 seconds. Add cream and bring to a simmer.', NULL, '2026-05-17 18:08:42'),
(10, 11, 5, 'Toss in spinach until wilted. Add parmesan, pasta, and enough pasta water to coat. Serve with sliced chicken on top.', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(11, 12, 1, 'Whisk together eggs, milk, vanilla and a pinch of sugar in a shallow bowl.', NULL, '2026-05-17 18:08:42'),
(12, 12, 2, 'Dip each bread slice into the egg mixture, coating both sides evenly.', NULL, '2026-05-17 18:08:42'),
(13, 12, 3, 'Melt butter in a non-stick pan over medium heat.', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(14, 12, 4, 'Cook bread slices 2-3 minutes per side until golden brown.', NULL, '2026-05-17 18:08:42'),
(15, 12, 5, 'Serve with maple syrup, fresh berries and a dusting of powdered sugar.', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(16, 13, 1, 'Pound chicken breasts to even thickness. Season with salt and pepper, dredge in flour.', NULL, '2026-05-17 18:08:42'),
(17, 13, 2, 'Heat butter and oil in a large skillet. Cook chicken 4 minutes per side. Remove and set aside.', NULL, '2026-05-17 18:08:42'),
(18, 13, 3, 'Add sliced mushrooms to the pan, cook until browned, about 5 minutes.', NULL, '2026-05-17 18:08:42'),
(19, 13, 4, 'Add marsala wine and chicken broth. Simmer until reduced by half.', NULL, '2026-05-17 18:08:42'),
(20, 13, 5, 'Return chicken to pan, simmer 5 minutes until sauce thickens. Garnish with fresh parsley.', NULL, '2026-05-17 18:08:42'),
(21, 14, 1, 'Preheat oven to 220°C. Grease 4 ramekins with butter and dust with cocoa powder.', NULL, '2026-05-17 18:08:42'),
(22, 14, 2, 'Melt dark chocolate and butter together in a double boiler or microwave.', NULL, '2026-05-17 18:08:42'),
(23, 14, 3, 'Whisk eggs and sugar until thick and pale, about 3 minutes.', NULL, '2026-05-17 18:08:42'),
(24, 14, 4, 'Fold chocolate mixture into eggs. Gently fold in flour.', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(25, 14, 5, 'Pour into ramekins. Bake exactly 12 minutes — centers should be jiggly. Invert onto plates and serve immediately.', NULL, '2026-05-17 18:08:42'),
(26, 15, 1, 'Cook rice according to package directions.', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(27, 15, 2, 'Mix soy sauce, honey, ginger and garlic for the teriyaki sauce.', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(28, 15, 3, 'Slice beef thinly. Stir-fry in hot oil for 2-3 minutes until browned.', NULL, '2026-05-17 18:08:42'),
(29, 15, 4, 'Pour teriyaki sauce over beef, cook 1 minute until glazed.', NULL, '2026-05-17 18:08:42'),
(30, 15, 5, 'Assemble bowls: rice, beef, steamed broccoli. Top with sesame seeds and green onions.', NULL, '2026-05-17 18:08:42'),
(31, 16, 1, 'Toast sourdough bread until golden and crispy.', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(32, 16, 2, 'Mash avocado with lemon juice, salt and chili flakes.', NULL, '2026-05-17 18:08:42'),
(33, 16, 3, 'Bring water to a gentle simmer, create a whirlpool, drop in egg. Poach 3 minutes.', NULL, '2026-05-17 18:08:42'),
(34, 16, 4, 'Spread mashed avocado on toast, top with poached egg. Season with salt, pepper and extra chili flakes.', NULL, '2026-05-17 18:08:42'),
(35, 17, 1, 'Soak rice noodles in warm water for 20 minutes until soft. Drain.', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(36, 17, 2, 'Mix sauce: soy sauce, sugar, lime juice and fish sauce.', NULL, '2026-05-17 18:08:42'),
(37, 17, 3, 'Heat oil in a wok. Scramble eggs, push to side. Add shrimp, cook 2 minutes.', NULL, '2026-05-17 18:08:42'),
(38, 17, 4, 'Add noodles and sauce. Toss everything together over high heat for 2 minutes.', NULL, '2026-05-17 18:08:42'),
(39, 17, 5, 'Serve topped with crushed peanuts, bean sprouts and lime wedges.', NULL, '2026-05-17 18:08:42'),
(40, 18, 1, 'Slice baguette into 1cm rounds. Brush with olive oil.', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(41, 18, 2, 'Toast under broiler or on grill until golden, about 2 minutes.', NULL, '2026-05-17 18:08:42'),
(42, 18, 3, 'Rub each toast with a cut garlic clove.', NULL, '2026-05-17 18:08:42'),
(43, 18, 4, 'Dice tomatoes and fresh mozzarella. Mix with torn basil, olive oil, salt and pepper.', NULL, '2026-05-17 18:08:42'),
(44, 18, 5, 'Spoon tomato mixture onto toasts. Drizzle with balsamic glaze. Serve immediately.', 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(45, 19, 1, 'Remove membrane from back of ribs. Mix dry rub: brown sugar, paprika, garlic powder, salt, pepper, cumin.', NULL, '2026-05-17 18:08:42'),
(46, 19, 2, 'Coat ribs generously with dry rub. Wrap in foil and refrigerate overnight (or at least 2 hours).', NULL, '2026-05-17 18:08:42'),
(47, 19, 3, 'Preheat oven to 135°C. Place wrapped ribs on baking sheet. Cook 3 hours.', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-17 18:08:42'),
(48, 19, 4, 'Unwrap ribs, brush generously with BBQ sauce.', NULL, '2026-05-17 18:08:42'),
(49, 19, 5, 'Increase oven to 200°C or use broiler. Cook 10 minutes until sauce caramelizes. Rest 10 minutes before cutting.', NULL, '2026-05-17 18:08:42'),
(100, 21, 1, 'Make hollandaise', NULL, '2026-05-22 15:43:49'),
(101, 21, 2, 'Toast muffins', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(102, 21, 3, 'Cook bacon', NULL, '2026-05-22 15:43:49'),
(103, 21, 4, 'Poach eggs', NULL, '2026-05-22 15:43:49'),
(104, 21, 5, 'Assemble', NULL, '2026-05-22 15:43:49'),
(105, 22, 1, 'Blend açaí with banana', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(106, 22, 2, 'Pour into bowl', NULL, '2026-05-22 15:43:49'),
(107, 22, 3, 'Add toppings', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(108, 22, 4, 'Drizzle honey', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(109, 23, 1, 'Cook pasta', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(110, 23, 2, 'Sauté chicken', NULL, '2026-05-22 15:43:49'),
(111, 23, 3, 'Add mushrooms and spinach', 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(112, 23, 4, 'Pour cream sauce', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(113, 24, 1, 'Cook pasta', NULL, '2026-05-22 15:43:49'),
(114, 24, 2, 'Crisp bacon', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(115, 24, 3, 'Cook chicken', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(116, 24, 4, 'Combine with cream sauce', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 15:43:49'),
(117, 25, 1, 'Season chicken with cajun', NULL, '2026-05-22 15:43:50'),
(118, 25, 2, 'Cook pasta', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(119, 25, 3, 'Make alfredo sauce', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(120, 25, 4, 'Combine', 'https://images.unsplash.com/photo-1506280754576-f6fa8a873550?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(121, 26, 1, 'Cook pasta al dente', NULL, '2026-05-22 15:43:50'),
(122, 26, 2, 'Fry guanciale', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(123, 26, 3, 'Mix eggs and cheese', NULL, '2026-05-22 15:43:50'),
(124, 26, 4, 'Combine off heat', NULL, '2026-05-22 15:43:50'),
(125, 27, 1, 'Blend pesto', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(126, 27, 2, 'Cook pasta', NULL, '2026-05-22 15:43:50'),
(127, 27, 3, 'Toss together', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(128, 27, 4, 'Garnish with parmesan', NULL, '2026-05-22 15:43:50'),
(129, 28, 1, 'Make bolognese', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(130, 28, 2, 'Make béchamel', NULL, '2026-05-22 15:43:50'),
(131, 28, 3, 'Layer everything', NULL, '2026-05-22 15:43:50'),
(132, 28, 4, 'Bake 45 minutes', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(133, 29, 1, 'Chop vegetables', NULL, '2026-05-22 15:43:50'),
(134, 29, 2, 'Mix salad', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(135, 29, 3, 'Add chips', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(136, 29, 4, 'Top with salsa', NULL, '2026-05-22 15:43:50'),
(137, 30, 1, 'Sauté mushrooms', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(138, 30, 2, 'Toast rice', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(139, 30, 3, 'Add stock gradually', NULL, '2026-05-22 15:43:50'),
(140, 30, 4, 'Finish with parmesan', NULL, '2026-05-22 15:43:50'),
(141, 31, 1, 'Slice tomatoes and mozzarella', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(142, 31, 2, 'Layer alternately', NULL, '2026-05-22 15:43:50'),
(143, 31, 3, 'Add basil', NULL, '2026-05-22 15:43:50'),
(144, 31, 4, 'Dress with oil', NULL, '2026-05-22 15:43:50'),
(145, 32, 1, 'Bread eggplant slices', NULL, '2026-05-22 15:43:50'),
(146, 32, 2, 'Bake until golden', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(147, 32, 3, 'Layer with sauce', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(148, 32, 4, 'Broil with cheese', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(149, 33, 1, 'Sauté aromatics', 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(150, 33, 2, 'Add lentils and water', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(151, 33, 3, 'Simmer 25 min', NULL, '2026-05-22 15:43:50'),
(152, 33, 4, 'Blend partially', NULL, '2026-05-22 15:43:50'),
(153, 34, 1, 'Season chicken', NULL, '2026-05-22 15:43:50'),
(154, 34, 2, 'Sear until golden', NULL, '2026-05-22 15:43:50'),
(155, 34, 3, 'Add glaze ingredients', NULL, '2026-05-22 15:43:50'),
(156, 34, 4, 'Reduce until thick', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(157, 35, 1, 'Season generously', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(158, 35, 2, 'Sear 4 min per side', NULL, '2026-05-22 15:43:50'),
(159, 35, 3, 'Add butter and herbs', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(160, 35, 4, 'Rest 5 min', NULL, '2026-05-22 15:43:50'),
(161, 36, 1, 'Cook chicken', NULL, '2026-05-22 15:43:50'),
(162, 36, 2, 'Fill tortillas', NULL, '2026-05-22 15:43:50'),
(163, 36, 3, 'Grill until crispy', NULL, '2026-05-22 15:43:50'),
(164, 36, 4, 'Cut and serve', NULL, '2026-05-22 15:43:50'),
(165, 37, 1, 'Simmer broth 2h', NULL, '2026-05-22 15:43:50'),
(166, 37, 2, 'Strain and season', NULL, '2026-05-22 15:43:50'),
(167, 37, 3, 'Cook noodles', NULL, '2026-05-22 15:43:50'),
(168, 37, 4, 'Assemble with toppings', NULL, '2026-05-22 15:43:50'),
(169, 38, 1, 'Make miso broth', NULL, '2026-05-22 15:43:50'),
(170, 38, 2, 'Prep toppings', NULL, '2026-05-22 15:43:50'),
(171, 38, 3, 'Cook noodles', NULL, '2026-05-22 15:43:50'),
(172, 38, 4, 'Assemble bowls', 'https://images.unsplash.com/photo-1506280754576-f6fa8a873550?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(173, 39, 1, 'Sauté onion', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(174, 39, 2, 'Add peas and stock', 'https://images.unsplash.com/photo-1506280754576-f6fa8a873550?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(175, 39, 3, 'Simmer 10 min', NULL, '2026-05-22 15:43:50'),
(176, 39, 4, 'Blend with mint', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(177, 40, 1, 'Wash greens', NULL, '2026-05-22 15:43:50'),
(178, 40, 2, 'Slice strawberries', NULL, '2026-05-22 15:43:50'),
(179, 40, 3, 'Crumble cheese', NULL, '2026-05-22 15:43:50'),
(180, 40, 4, 'Toss with dressing', NULL, '2026-05-22 15:43:50'),
(181, 41, 1, 'Roll pastry', NULL, '2026-05-22 15:43:50'),
(182, 41, 2, 'Spread ricotta mix', NULL, '2026-05-22 15:43:50'),
(183, 41, 3, 'Arrange asparagus', NULL, '2026-05-22 15:43:50'),
(184, 41, 4, 'Bake 30 min', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(185, 42, 1, 'Toast bread', 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(186, 42, 2, 'Spread butter generously', NULL, '2026-05-22 15:43:50'),
(187, 42, 3, 'Layer radishes', NULL, '2026-05-22 15:43:50'),
(188, 42, 4, 'Finish with salt', NULL, '2026-05-22 15:43:50'),
(189, 43, 1, 'Grill corn direct', 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(190, 43, 2, 'Make chili butter', 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(191, 43, 3, 'Brush generously', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(192, 43, 4, 'Add toppings', NULL, '2026-05-22 15:43:50'),
(193, 44, 1, 'Marinate 1 hour', NULL, '2026-05-22 15:43:50'),
(194, 44, 2, 'Grill 4 min per side', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(195, 44, 3, 'Rest 5 min', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(196, 44, 4, 'Serve with mint', 'https://images.unsplash.com/photo-1506280754576-f6fa8a873550?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(197, 45, 1, 'Make dressing', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(198, 45, 2, 'Tear lettuce', NULL, '2026-05-22 15:43:50'),
(199, 45, 3, 'Toss everything', NULL, '2026-05-22 15:43:50'),
(200, 45, 4, 'Top with parmesan', NULL, '2026-05-22 15:43:50'),
(201, 46, 1, 'Whip cream', NULL, '2026-05-22 15:43:50'),
(202, 46, 2, 'Dip ladyfingers in coffee', NULL, '2026-05-22 15:43:50'),
(203, 46, 3, 'Layer in dish', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(204, 46, 4, 'Chill 4 hours', NULL, '2026-05-22 15:43:50'),
(205, 47, 1, 'Warm cream with vanilla', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(206, 47, 2, 'Temper egg yolks', NULL, '2026-05-22 15:43:50'),
(207, 47, 3, 'Bake in water bath', NULL, '2026-05-22 15:43:50'),
(208, 47, 4, 'Caramelize sugar', NULL, '2026-05-22 15:43:50'),
(209, 48, 1, 'Caramelize onions', NULL, '2026-05-22 15:43:50'),
(210, 48, 2, 'Add broth', NULL, '2026-05-22 15:43:50'),
(211, 48, 3, 'Top with bread', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(212, 48, 4, 'Broil with cheese', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(213, 49, 1, 'Simmer broth with aromatics', NULL, '2026-05-22 15:43:50'),
(214, 49, 2, 'Add mushrooms and shrimp', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(215, 49, 3, 'Season with fish sauce', NULL, '2026-05-22 15:43:50'),
(216, 49, 4, 'Finish with lime', NULL, '2026-05-22 15:43:50'),
(217, 50, 1, 'Blend watermelon', NULL, '2026-05-22 15:43:50'),
(218, 50, 2, 'Strain juice', NULL, '2026-05-22 15:43:50'),
(219, 50, 3, 'Mix with lime and honey', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 15:43:50'),
(220, 50, 4, 'Add sparkling water', NULL, '2026-05-22 15:43:50'),
(221, 51, 1, 'Form beef into patties', NULL, '2026-05-22 19:05:07'),
(222, 51, 2, 'Grill for 4 minutes per side', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(223, 51, 3, 'Add cheese to melt', 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(224, 51, 4, 'Toast buns and assemble', NULL, '2026-05-22 19:05:07'),
(225, 52, 1, 'Grill chicken until cooked', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(226, 52, 2, 'Chop vegetables', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(227, 52, 3, 'Slice chicken', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(228, 52, 4, 'Toss salad with dressing', 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(229, 53, 1, 'Cook sushi rice', NULL, '2026-05-22 19:05:07'),
(230, 53, 2, 'Mix tuna with mayo', NULL, '2026-05-22 19:05:07'),
(231, 53, 3, 'Shape rice into triangles with filling inside', NULL, '2026-05-22 19:05:07'),
(232, 53, 4, 'Wrap with nori', 'https://images.unsplash.com/photo-1506280754576-f6fa8a873550?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(233, 54, 1, 'Preheat oven', NULL, '2026-05-22 19:05:07'),
(234, 54, 2, 'Mix softened butter with minced garlic and herbs', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(235, 54, 3, 'Spread on sliced bread', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(236, 54, 4, 'Bake until golden', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(237, 55, 1, 'Peel and boil potatoes', NULL, '2026-05-22 19:05:07'),
(238, 55, 2, 'Drain and mash well', NULL, '2026-05-22 19:05:07'),
(239, 55, 3, 'Stir in melted butter and hot cream', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(240, 55, 4, 'Season to taste', NULL, '2026-05-22 19:05:07'),
(241, 56, 1, 'Trim asparagus ends', 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(242, 56, 2, 'Toss with oil and salt', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(243, 56, 3, 'Roast at 400F for 10 mins', NULL, '2026-05-22 19:05:07'),
(244, 56, 4, 'Sprinkle with lemon and cheese', NULL, '2026-05-22 19:05:07'),
(245, 57, 1, 'Muddle mint and lime juice', NULL, '2026-05-22 19:05:07'),
(246, 57, 2, 'Add ice and rum', NULL, '2026-05-22 19:05:07'),
(247, 57, 3, 'Top with club soda', NULL, '2026-05-22 19:05:07'),
(248, 57, 4, 'Garnish with a mint sprig', NULL, '2026-05-22 19:05:07'),
(249, 58, 1, 'Chop fruits', NULL, '2026-05-22 19:05:07'),
(250, 58, 2, 'Add everything to a blender', NULL, '2026-05-22 19:05:07'),
(251, 58, 3, 'Blend until smooth', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(252, 58, 4, 'Serve chilled', NULL, '2026-05-22 19:05:07'),
(253, 59, 1, 'Brew black tea and let cool', NULL, '2026-05-22 19:05:07'),
(254, 59, 2, 'Simmer peaches with sugar to make syrup', NULL, '2026-05-22 19:05:07'),
(255, 59, 3, 'Mix tea and syrup over ice', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(256, 59, 4, 'Garnish with peach slices', 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(257, 60, 1, 'Coat chicken in cornstarch', NULL, '2026-05-22 19:05:07'),
(258, 60, 2, 'Deep fry once, let rest, then fry again', 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(259, 60, 3, 'Simmer sauce ingredients', NULL, '2026-05-22 19:05:07'),
(260, 60, 4, 'Toss crispy chicken in sauce', NULL, '2026-05-22 19:05:07'),
(261, 61, 1, 'Boil pasta', NULL, '2026-05-22 19:05:07'),
(262, 61, 2, 'Make a roux and add milk', NULL, '2026-05-22 19:05:07'),
(263, 61, 3, 'Melt cheese into the sauce', NULL, '2026-05-22 19:05:07'),
(264, 61, 4, 'Mix pasta, top with crumbs, and bake', NULL, '2026-05-22 19:05:07'),
(265, 62, 1, 'Brown the beef', NULL, '2026-05-22 19:05:07'),
(266, 62, 2, 'Sauté onions and carrots', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(267, 62, 3, 'Add broth and simmer 1.5 hours', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(268, 62, 4, 'Add potatoes and cook until tender', NULL, '2026-05-22 19:05:07'),
(269, 63, 1, 'Cook pasta in salted water', NULL, '2026-05-22 19:05:07'),
(270, 63, 2, 'Sauté sliced garlic and chili in oil', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(271, 63, 3, 'Toss pasta with the oil', NULL, '2026-05-22 19:05:07'),
(272, 63, 4, 'Garnish with parsley', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(273, 64, 1, 'Scramble eggs and set aside', NULL, '2026-05-22 19:05:07'),
(274, 64, 2, 'Fry sliced sausage', NULL, '2026-05-22 19:05:07'),
(275, 64, 3, 'Add rice and soy sauce, stir fry well', NULL, '2026-05-22 19:05:07'),
(276, 64, 4, 'Mix in eggs and green onions', NULL, '2026-05-22 19:05:07'),
(277, 65, 1, 'Mash avocado in a bowl', NULL, '2026-05-22 19:05:07'),
(278, 65, 2, 'Flake the tuna into the bowl', NULL, '2026-05-22 19:05:07'),
(279, 65, 3, 'Add diced onion and lemon juice', NULL, '2026-05-22 19:05:07'),
(280, 65, 4, 'Mix well and season', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(281, 66, 1, 'Cut dough into small circles', NULL, '2026-05-22 19:05:07'),
(282, 66, 2, 'Top with sauce and cheese', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(283, 66, 3, 'Add pepperoni', 'https://images.unsplash.com/photo-1569562211093-4ed0d0758f12?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(284, 66, 4, 'Bake at 425F for 10 mins', NULL, '2026-05-22 19:05:07'),
(285, 67, 1, 'Slice onions into rings', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(286, 67, 2, 'Dip in batter, then breadcrumbs', NULL, '2026-05-22 19:05:07'),
(287, 67, 3, 'Fry until golden brown', NULL, '2026-05-22 19:05:07'),
(288, 67, 4, 'Drain on paper towels', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(289, 68, 1, 'Sauté onion until soft', NULL, '2026-05-22 19:05:07'),
(290, 68, 2, 'Add pumpkin and broth, simmer 15 mins', 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(291, 68, 3, 'Stir in cream and spices', NULL, '2026-05-22 19:05:07'),
(292, 68, 4, 'Blend until completely smooth', 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(293, 69, 1, 'Cook mushrooms and garlic until browned', NULL, '2026-05-22 19:05:07'),
(294, 69, 2, 'Add broth and simmer', NULL, '2026-05-22 19:05:07'),
(295, 69, 3, 'Blend half the soup', 'https://images.unsplash.com/photo-1603105037880-880cd4edfb0d?w=600&h=400&fit=crop', '2026-05-22 19:05:07'),
(296, 69, 4, 'Stir in cream and truffle oil', NULL, '2026-05-22 19:05:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recipe_images`
--

CREATE TABLE `recipe_images` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` varchar(200) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recipe_images`
--

INSERT INTO `recipe_images` (`id`, `recipe_id`, `image_url`, `caption`, `display_order`, `created_at`) VALUES
(1, 10, 'https://loremflickr.com/600/400/food,dish?lock=101', 'Delicious details', 0, '2026-05-23 20:09:11'),
(2, 10, 'https://loremflickr.com/600/400/food,cooking?lock=102', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(3, 10, 'https://loremflickr.com/600/400/food,meal?lock=103', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(4, 11, 'https://loremflickr.com/600/400/food,dish?lock=111', 'Delicious details', 0, '2026-05-23 20:09:11'),
(5, 11, 'https://loremflickr.com/600/400/food,cooking?lock=112', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(6, 11, 'https://loremflickr.com/600/400/food,meal?lock=113', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(7, 12, 'https://loremflickr.com/600/400/food,dish?lock=121', 'Delicious details', 0, '2026-05-23 20:09:11'),
(8, 12, 'https://loremflickr.com/600/400/food,cooking?lock=122', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(9, 12, 'https://loremflickr.com/600/400/food,meal?lock=123', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(10, 13, 'https://loremflickr.com/600/400/food,dish?lock=131', 'Delicious details', 0, '2026-05-23 20:09:11'),
(11, 13, 'https://loremflickr.com/600/400/food,cooking?lock=132', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(12, 13, 'https://loremflickr.com/600/400/food,meal?lock=133', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(13, 14, 'https://loremflickr.com/600/400/food,dish?lock=141', 'Delicious details', 0, '2026-05-23 20:09:11'),
(14, 14, 'https://loremflickr.com/600/400/food,cooking?lock=142', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(15, 14, 'https://loremflickr.com/600/400/food,meal?lock=143', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(16, 15, 'https://loremflickr.com/600/400/food,dish?lock=151', 'Delicious details', 0, '2026-05-23 20:09:11'),
(17, 15, 'https://loremflickr.com/600/400/food,cooking?lock=152', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(18, 15, 'https://loremflickr.com/600/400/food,meal?lock=153', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(19, 16, 'https://loremflickr.com/600/400/food,dish?lock=161', 'Delicious details', 0, '2026-05-23 20:09:11'),
(20, 16, 'https://loremflickr.com/600/400/food,cooking?lock=162', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(21, 16, 'https://loremflickr.com/600/400/food,meal?lock=163', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(22, 17, 'https://loremflickr.com/600/400/food,dish?lock=171', 'Delicious details', 0, '2026-05-23 20:09:11'),
(23, 17, 'https://loremflickr.com/600/400/food,cooking?lock=172', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(24, 17, 'https://loremflickr.com/600/400/food,meal?lock=173', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(25, 18, 'https://loremflickr.com/600/400/food,dish?lock=181', 'Delicious details', 0, '2026-05-23 20:09:11'),
(26, 18, 'https://loremflickr.com/600/400/food,cooking?lock=182', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(27, 18, 'https://loremflickr.com/600/400/food,meal?lock=183', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(28, 19, 'https://loremflickr.com/600/400/food,dish?lock=191', 'Delicious details', 0, '2026-05-23 20:09:11'),
(29, 19, 'https://loremflickr.com/600/400/food,cooking?lock=192', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(30, 19, 'https://loremflickr.com/600/400/food,meal?lock=193', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(31, 20, 'https://loremflickr.com/600/400/food,dish?lock=201', 'Delicious details', 0, '2026-05-23 20:09:11'),
(32, 20, 'https://loremflickr.com/600/400/food,cooking?lock=202', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(33, 20, 'https://loremflickr.com/600/400/food,meal?lock=203', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(34, 21, 'https://loremflickr.com/600/400/food,dish?lock=211', 'Delicious details', 0, '2026-05-23 20:09:11'),
(35, 21, 'https://loremflickr.com/600/400/food,cooking?lock=212', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(36, 21, 'https://loremflickr.com/600/400/food,meal?lock=213', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(37, 22, 'https://loremflickr.com/600/400/food,dish?lock=221', 'Delicious details', 0, '2026-05-23 20:09:11'),
(38, 22, 'https://loremflickr.com/600/400/food,cooking?lock=222', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(39, 22, 'https://loremflickr.com/600/400/food,meal?lock=223', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(40, 23, 'https://loremflickr.com/600/400/food,dish?lock=231', 'Delicious details', 0, '2026-05-23 20:09:11'),
(41, 23, 'https://loremflickr.com/600/400/food,cooking?lock=232', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(42, 23, 'https://loremflickr.com/600/400/food,meal?lock=233', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(43, 24, 'https://loremflickr.com/600/400/food,dish?lock=241', 'Delicious details', 0, '2026-05-23 20:09:11'),
(44, 24, 'https://loremflickr.com/600/400/food,cooking?lock=242', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(45, 24, 'https://loremflickr.com/600/400/food,meal?lock=243', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(46, 25, 'https://loremflickr.com/600/400/food,dish?lock=251', 'Delicious details', 0, '2026-05-23 20:09:11'),
(47, 25, 'https://loremflickr.com/600/400/food,cooking?lock=252', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(48, 25, 'https://loremflickr.com/600/400/food,meal?lock=253', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(49, 26, 'https://loremflickr.com/600/400/food,dish?lock=261', 'Delicious details', 0, '2026-05-23 20:09:11'),
(50, 26, 'https://loremflickr.com/600/400/food,cooking?lock=262', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(51, 26, 'https://loremflickr.com/600/400/food,meal?lock=263', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(52, 27, 'https://loremflickr.com/600/400/food,dish?lock=271', 'Delicious details', 0, '2026-05-23 20:09:11'),
(53, 27, 'https://loremflickr.com/600/400/food,cooking?lock=272', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(54, 27, 'https://loremflickr.com/600/400/food,meal?lock=273', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(55, 28, 'https://loremflickr.com/600/400/food,dish?lock=281', 'Delicious details', 0, '2026-05-23 20:09:11'),
(56, 28, 'https://loremflickr.com/600/400/food,cooking?lock=282', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(57, 28, 'https://loremflickr.com/600/400/food,meal?lock=283', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(58, 29, 'https://loremflickr.com/600/400/food,dish?lock=291', 'Delicious details', 0, '2026-05-23 20:09:11'),
(59, 29, 'https://loremflickr.com/600/400/food,cooking?lock=292', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(60, 29, 'https://loremflickr.com/600/400/food,meal?lock=293', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(61, 30, 'https://loremflickr.com/600/400/food,dish?lock=301', 'Delicious details', 0, '2026-05-23 20:09:11'),
(62, 30, 'https://loremflickr.com/600/400/food,cooking?lock=302', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(63, 30, 'https://loremflickr.com/600/400/food,meal?lock=303', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(64, 31, 'https://loremflickr.com/600/400/food,dish?lock=311', 'Delicious details', 0, '2026-05-23 20:09:11'),
(65, 31, 'https://loremflickr.com/600/400/food,cooking?lock=312', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(66, 31, 'https://loremflickr.com/600/400/food,meal?lock=313', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(67, 32, 'https://loremflickr.com/600/400/food,dish?lock=321', 'Delicious details', 0, '2026-05-23 20:09:11'),
(68, 32, 'https://loremflickr.com/600/400/food,cooking?lock=322', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(69, 32, 'https://loremflickr.com/600/400/food,meal?lock=323', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(70, 33, 'https://loremflickr.com/600/400/food,dish?lock=331', 'Delicious details', 0, '2026-05-23 20:09:11'),
(71, 33, 'https://loremflickr.com/600/400/food,cooking?lock=332', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(72, 33, 'https://loremflickr.com/600/400/food,meal?lock=333', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(73, 34, 'https://loremflickr.com/600/400/food,dish?lock=341', 'Delicious details', 0, '2026-05-23 20:09:11'),
(74, 34, 'https://loremflickr.com/600/400/food,cooking?lock=342', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(75, 34, 'https://loremflickr.com/600/400/food,meal?lock=343', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(76, 35, 'https://loremflickr.com/600/400/food,dish?lock=351', 'Delicious details', 0, '2026-05-23 20:09:11'),
(77, 35, 'https://loremflickr.com/600/400/food,cooking?lock=352', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(78, 35, 'https://loremflickr.com/600/400/food,meal?lock=353', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(79, 36, 'https://loremflickr.com/600/400/food,dish?lock=361', 'Delicious details', 0, '2026-05-23 20:09:11'),
(80, 36, 'https://loremflickr.com/600/400/food,cooking?lock=362', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(81, 36, 'https://loremflickr.com/600/400/food,meal?lock=363', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(82, 37, 'https://loremflickr.com/600/400/food,dish?lock=371', 'Delicious details', 0, '2026-05-23 20:09:11'),
(83, 37, 'https://loremflickr.com/600/400/food,cooking?lock=372', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(84, 37, 'https://loremflickr.com/600/400/food,meal?lock=373', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(85, 38, 'https://loremflickr.com/600/400/food,dish?lock=381', 'Delicious details', 0, '2026-05-23 20:09:11'),
(86, 38, 'https://loremflickr.com/600/400/food,cooking?lock=382', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(87, 38, 'https://loremflickr.com/600/400/food,meal?lock=383', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(88, 39, 'https://loremflickr.com/600/400/food,dish?lock=391', 'Delicious details', 0, '2026-05-23 20:09:11'),
(89, 39, 'https://loremflickr.com/600/400/food,cooking?lock=392', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(90, 39, 'https://loremflickr.com/600/400/food,meal?lock=393', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(91, 40, 'https://loremflickr.com/600/400/food,dish?lock=401', 'Delicious details', 0, '2026-05-23 20:09:11'),
(92, 40, 'https://loremflickr.com/600/400/food,cooking?lock=402', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(93, 40, 'https://loremflickr.com/600/400/food,meal?lock=403', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(94, 41, 'https://loremflickr.com/600/400/food,dish?lock=411', 'Delicious details', 0, '2026-05-23 20:09:11'),
(95, 41, 'https://loremflickr.com/600/400/food,cooking?lock=412', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(96, 41, 'https://loremflickr.com/600/400/food,meal?lock=413', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(97, 42, 'https://loremflickr.com/600/400/food,dish?lock=421', 'Delicious details', 0, '2026-05-23 20:09:11'),
(98, 42, 'https://loremflickr.com/600/400/food,cooking?lock=422', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(99, 42, 'https://loremflickr.com/600/400/food,meal?lock=423', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(100, 43, 'https://loremflickr.com/600/400/food,dish?lock=431', 'Delicious details', 0, '2026-05-23 20:09:11'),
(101, 43, 'https://loremflickr.com/600/400/food,cooking?lock=432', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(102, 43, 'https://loremflickr.com/600/400/food,meal?lock=433', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(103, 44, 'https://loremflickr.com/600/400/food,dish?lock=441', 'Delicious details', 0, '2026-05-23 20:09:11'),
(104, 44, 'https://loremflickr.com/600/400/food,cooking?lock=442', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(105, 44, 'https://loremflickr.com/600/400/food,meal?lock=443', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(106, 45, 'https://loremflickr.com/600/400/food,dish?lock=451', 'Delicious details', 0, '2026-05-23 20:09:11'),
(107, 45, 'https://loremflickr.com/600/400/food,cooking?lock=452', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(108, 45, 'https://loremflickr.com/600/400/food,meal?lock=453', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(109, 46, 'https://loremflickr.com/600/400/food,dish?lock=461', 'Delicious details', 0, '2026-05-23 20:09:11'),
(110, 46, 'https://loremflickr.com/600/400/food,cooking?lock=462', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(111, 46, 'https://loremflickr.com/600/400/food,meal?lock=463', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(112, 47, 'https://loremflickr.com/600/400/food,dish?lock=471', 'Delicious details', 0, '2026-05-23 20:09:11'),
(113, 47, 'https://loremflickr.com/600/400/food,cooking?lock=472', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(114, 47, 'https://loremflickr.com/600/400/food,meal?lock=473', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(115, 48, 'https://loremflickr.com/600/400/food,dish?lock=481', 'Delicious details', 0, '2026-05-23 20:09:11'),
(116, 48, 'https://loremflickr.com/600/400/food,cooking?lock=482', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(117, 48, 'https://loremflickr.com/600/400/food,meal?lock=483', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(118, 49, 'https://loremflickr.com/600/400/food,dish?lock=491', 'Delicious details', 0, '2026-05-23 20:09:11'),
(119, 49, 'https://loremflickr.com/600/400/food,cooking?lock=492', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(120, 49, 'https://loremflickr.com/600/400/food,meal?lock=493', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(121, 50, 'https://loremflickr.com/600/400/food,dish?lock=501', 'Delicious details', 0, '2026-05-23 20:09:11'),
(122, 50, 'https://loremflickr.com/600/400/food,cooking?lock=502', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(123, 50, 'https://loremflickr.com/600/400/food,meal?lock=503', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(124, 51, 'https://loremflickr.com/600/400/food,dish?lock=511', 'Delicious details', 0, '2026-05-23 20:09:11'),
(125, 51, 'https://loremflickr.com/600/400/food,cooking?lock=512', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(126, 51, 'https://loremflickr.com/600/400/food,meal?lock=513', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(127, 52, 'https://loremflickr.com/600/400/food,dish?lock=521', 'Delicious details', 0, '2026-05-23 20:09:11'),
(128, 52, 'https://loremflickr.com/600/400/food,cooking?lock=522', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(129, 52, 'https://loremflickr.com/600/400/food,meal?lock=523', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(130, 53, 'https://loremflickr.com/600/400/food,dish?lock=531', 'Delicious details', 0, '2026-05-23 20:09:11'),
(131, 53, 'https://loremflickr.com/600/400/food,cooking?lock=532', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(132, 53, 'https://loremflickr.com/600/400/food,meal?lock=533', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(133, 54, 'https://loremflickr.com/600/400/food,dish?lock=541', 'Delicious details', 0, '2026-05-23 20:09:11'),
(134, 54, 'https://loremflickr.com/600/400/food,cooking?lock=542', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(135, 54, 'https://loremflickr.com/600/400/food,meal?lock=543', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(136, 55, 'https://loremflickr.com/600/400/food,dish?lock=551', 'Delicious details', 0, '2026-05-23 20:09:11'),
(137, 55, 'https://loremflickr.com/600/400/food,cooking?lock=552', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(138, 55, 'https://loremflickr.com/600/400/food,meal?lock=553', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(139, 56, 'https://loremflickr.com/600/400/food,dish?lock=561', 'Delicious details', 0, '2026-05-23 20:09:11'),
(140, 56, 'https://loremflickr.com/600/400/food,cooking?lock=562', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(141, 56, 'https://loremflickr.com/600/400/food,meal?lock=563', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(142, 57, 'https://loremflickr.com/600/400/food,dish?lock=571', 'Delicious details', 0, '2026-05-23 20:09:11'),
(143, 57, 'https://loremflickr.com/600/400/food,cooking?lock=572', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(144, 57, 'https://loremflickr.com/600/400/food,meal?lock=573', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(145, 58, 'https://loremflickr.com/600/400/food,dish?lock=581', 'Delicious details', 0, '2026-05-23 20:09:11'),
(146, 58, 'https://loremflickr.com/600/400/food,cooking?lock=582', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(147, 58, 'https://loremflickr.com/600/400/food,meal?lock=583', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(148, 59, 'https://loremflickr.com/600/400/food,dish?lock=591', 'Delicious details', 0, '2026-05-23 20:09:11'),
(149, 59, 'https://loremflickr.com/600/400/food,cooking?lock=592', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(150, 59, 'https://loremflickr.com/600/400/food,meal?lock=593', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(151, 60, 'https://loremflickr.com/600/400/food,dish?lock=601', 'Delicious details', 0, '2026-05-23 20:09:11'),
(152, 60, 'https://loremflickr.com/600/400/food,cooking?lock=602', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(153, 60, 'https://loremflickr.com/600/400/food,meal?lock=603', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(154, 61, 'https://loremflickr.com/600/400/food,dish?lock=611', 'Delicious details', 0, '2026-05-23 20:09:11'),
(155, 61, 'https://loremflickr.com/600/400/food,cooking?lock=612', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(156, 61, 'https://loremflickr.com/600/400/food,meal?lock=613', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(157, 62, 'https://loremflickr.com/600/400/food,dish?lock=621', 'Delicious details', 0, '2026-05-23 20:09:11'),
(158, 62, 'https://loremflickr.com/600/400/food,cooking?lock=622', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(159, 62, 'https://loremflickr.com/600/400/food,meal?lock=623', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(160, 63, 'https://loremflickr.com/600/400/food,dish?lock=631', 'Delicious details', 0, '2026-05-23 20:09:11'),
(161, 63, 'https://loremflickr.com/600/400/food,cooking?lock=632', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(162, 63, 'https://loremflickr.com/600/400/food,meal?lock=633', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(163, 64, 'https://loremflickr.com/600/400/food,dish?lock=641', 'Delicious details', 0, '2026-05-23 20:09:11'),
(164, 64, 'https://loremflickr.com/600/400/food,cooking?lock=642', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(165, 64, 'https://loremflickr.com/600/400/food,meal?lock=643', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(166, 65, 'https://loremflickr.com/600/400/food,dish?lock=651', 'Delicious details', 0, '2026-05-23 20:09:11'),
(167, 65, 'https://loremflickr.com/600/400/food,cooking?lock=652', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(168, 65, 'https://loremflickr.com/600/400/food,meal?lock=653', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(169, 66, 'https://loremflickr.com/600/400/food,dish?lock=661', 'Delicious details', 0, '2026-05-23 20:09:11'),
(170, 66, 'https://loremflickr.com/600/400/food,cooking?lock=662', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(171, 66, 'https://loremflickr.com/600/400/food,meal?lock=663', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(172, 67, 'https://loremflickr.com/600/400/food,dish?lock=671', 'Delicious details', 0, '2026-05-23 20:09:11'),
(173, 67, 'https://loremflickr.com/600/400/food,cooking?lock=672', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(174, 67, 'https://loremflickr.com/600/400/food,meal?lock=673', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(175, 68, 'https://loremflickr.com/600/400/food,dish?lock=681', 'Delicious details', 0, '2026-05-23 20:09:11'),
(176, 68, 'https://loremflickr.com/600/400/food,cooking?lock=682', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(177, 68, 'https://loremflickr.com/600/400/food,meal?lock=683', 'Perfect presentation', 0, '2026-05-23 20:09:11'),
(178, 69, 'https://loremflickr.com/600/400/food,dish?lock=691', 'Delicious details', 0, '2026-05-23 20:09:11'),
(179, 69, 'https://loremflickr.com/600/400/food,cooking?lock=692', 'Fresh from the kitchen', 0, '2026-05-23 20:09:11'),
(180, 69, 'https://loremflickr.com/600/400/food,meal?lock=693', 'Perfect presentation', 0, '2026-05-23 20:09:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id`, `recipe_id`, `ingredient_id`, `quantity`, `unit`, `notes`, `display_order`) VALUES
(1, 10, 20, '2', 'fillets', NULL, 0),
(2, 10, 21, '200', 'g', NULL, 1),
(3, 10, 18, '1', 'unit', NULL, 2),
(4, 10, 3, '2', 'tbsp', NULL, 3),
(5, 10, 2, '3', 'cloves', NULL, 4),
(6, 10, 10, '30', 'g', NULL, 5),
(7, 11, 15, '400', 'g', NULL, 0),
(8, 11, 1, '300', 'g', NULL, 1),
(9, 11, 22, '200', 'g', NULL, 2),
(10, 11, 23, '100', 'g', NULL, 3),
(11, 11, 17, '200', 'ml', NULL, 4),
(12, 11, 16, '50', 'g', NULL, 5),
(13, 11, 2, '3', 'cloves', NULL, 6),
(14, 12, 26, '4', 'slices', NULL, 0),
(15, 12, 11, '2', 'unit', NULL, 1),
(16, 12, 14, '60', 'ml', NULL, 2),
(17, 12, 30, '1', 'tsp', NULL, 3),
(18, 12, 10, '20', 'g', NULL, 4),
(19, 12, 13, '1', 'tbsp', NULL, 5),
(20, 13, 1, '500', 'g', NULL, 0),
(21, 13, 22, '200', 'g', NULL, 1),
(22, 13, 12, '50', 'g', NULL, 2),
(23, 13, 10, '40', 'g', NULL, 3),
(24, 13, 2, '4', 'cloves', NULL, 4),
(25, 14, 29, '200', 'g', NULL, 0),
(26, 14, 10, '100', 'g', NULL, 1),
(27, 14, 11, '3', 'unit', NULL, 2),
(28, 14, 13, '80', 'g', NULL, 3),
(29, 14, 12, '30', 'g', NULL, 4),
(30, 15, 8, '300', 'g', NULL, 0),
(31, 15, 9, '60', 'ml', NULL, 1),
(32, 15, 19, '1', 'tbsp', NULL, 2),
(33, 15, 27, '2', 'tbsp', NULL, 3),
(34, 15, 2, '2', 'cloves', NULL, 4),
(35, 16, 26, '2', 'slices', NULL, 0),
(36, 16, 25, '1', 'unit', NULL, 1),
(37, 16, 11, '1', 'unit', NULL, 2),
(38, 16, 18, '0.5', 'unit', NULL, 3),
(39, 16, 4, '1', 'pinch', NULL, 4),
(40, 17, 8, '200', 'g', NULL, 0),
(41, 17, 11, '2', 'unit', NULL, 1),
(42, 17, 9, '3', 'tbsp', NULL, 2),
(43, 17, 13, '1', 'tbsp', NULL, 3),
(44, 17, 18, '1', 'unit', NULL, 4),
(45, 18, 26, '1', 'baguette', NULL, 0),
(46, 18, 7, '4', 'unit', NULL, 1),
(47, 18, 3, '2', 'tbsp', NULL, 2),
(48, 18, 2, '2', 'cloves', NULL, 3),
(49, 18, 28, '1', 'tbsp', NULL, 4),
(50, 19, 13, '50', 'g', NULL, 0),
(51, 19, 4, '2', 'tbsp', NULL, 1),
(52, 19, 5, '1', 'tbsp', NULL, 2),
(53, 19, 2, '6', 'cloves', NULL, 3),
(54, 19, 27, '60', 'ml', NULL, 4),
(100, 20, 12, '1', 'portion', NULL, 0),
(101, 20, 31, '1', 'portion', NULL, 0),
(102, 20, 11, '1', 'portion', NULL, 0),
(103, 21, 33, '1', 'portion', NULL, 0),
(104, 21, 34, '1', 'portion', NULL, 0),
(105, 21, 11, '1', 'portion', NULL, 0),
(106, 21, 35, '1', 'portion', NULL, 0),
(107, 21, 36, '1', 'portion', NULL, 0),
(108, 22, 37, '1', 'portion', NULL, 0),
(109, 22, 38, '1', 'portion', NULL, 0),
(110, 22, 39, '1', 'portion', NULL, 0),
(111, 22, 40, '1', 'portion', NULL, 0),
(112, 22, 41, '1', 'portion', NULL, 0),
(113, 22, 27, '1', 'portion', NULL, 0),
(114, 23, 15, '1', 'portion', NULL, 0),
(115, 23, 1, '1', 'portion', NULL, 0),
(116, 23, 22, '1', 'portion', NULL, 0),
(117, 23, 23, '1', 'portion', NULL, 0),
(118, 23, 17, '1', 'portion', NULL, 0),
(119, 23, 42, '1', 'portion', NULL, 0),
(120, 24, 15, '1', 'portion', NULL, 0),
(121, 24, 43, '1', 'portion', NULL, 0),
(122, 24, 24, '1', 'portion', NULL, 0),
(123, 24, 17, '1', 'portion', NULL, 0),
(124, 24, 42, '1', 'portion', NULL, 0),
(125, 24, 2, '1', 'portion', NULL, 0),
(126, 25, 44, '1', 'portion', NULL, 0),
(127, 25, 43, '1', 'portion', NULL, 0),
(128, 25, 45, '1', 'portion', NULL, 0),
(129, 25, 17, '1', 'portion', NULL, 0),
(130, 25, 10, '1', 'portion', NULL, 0),
(131, 25, 42, '1', 'portion', NULL, 0),
(132, 26, 46, '1', 'portion', NULL, 0),
(133, 26, 47, '1', 'portion', NULL, 0),
(134, 26, 11, '1', 'portion', NULL, 0),
(135, 26, 48, '1', 'portion', NULL, 0),
(136, 26, 5, '1', 'portion', NULL, 0),
(137, 27, 49, '1', 'portion', NULL, 0),
(138, 27, 50, '1', 'portion', NULL, 0),
(139, 27, 51, '1', 'portion', NULL, 0),
(140, 27, 2, '1', 'portion', NULL, 0),
(141, 27, 42, '1', 'portion', NULL, 0),
(142, 27, 3, '1', 'portion', NULL, 0),
(143, 28, 52, '1', 'portion', NULL, 0),
(144, 28, 53, '1', 'portion', NULL, 0),
(145, 28, 54, '1', 'portion', NULL, 0),
(146, 28, 55, '1', 'portion', NULL, 0),
(147, 28, 42, '1', 'portion', NULL, 0),
(148, 28, 56, '1', 'portion', NULL, 0),
(149, 29, 57, '1', 'portion', NULL, 0),
(150, 29, 54, '1', 'portion', NULL, 0),
(151, 29, 58, '1', 'portion', NULL, 0),
(152, 29, 59, '1', 'portion', NULL, 0),
(153, 29, 60, '1', 'portion', NULL, 0),
(154, 29, 61, '1', 'portion', NULL, 0),
(155, 30, 62, '1', 'portion', NULL, 0),
(156, 30, 63, '1', 'portion', NULL, 0),
(157, 30, 6, '1', 'portion', NULL, 0),
(158, 30, 64, '1', 'portion', NULL, 0),
(159, 30, 42, '1', 'portion', NULL, 0),
(160, 30, 10, '1', 'portion', NULL, 0),
(161, 31, 65, '1', 'portion', NULL, 0),
(162, 31, 66, '1', 'portion', NULL, 0),
(163, 31, 50, '1', 'portion', NULL, 0),
(164, 31, 3, '1', 'portion', NULL, 0),
(165, 31, 67, '1', 'portion', NULL, 0),
(166, 31, 4, '1', 'portion', NULL, 0),
(167, 32, 68, '1', 'portion', NULL, 0),
(168, 32, 69, '1', 'portion', NULL, 0),
(169, 32, 56, '1', 'portion', NULL, 0),
(170, 32, 42, '1', 'portion', NULL, 0),
(171, 32, 70, '1', 'portion', NULL, 0),
(172, 32, 11, '1', 'portion', NULL, 0),
(173, 33, 71, '1', 'portion', NULL, 0),
(174, 33, 6, '1', 'portion', NULL, 0),
(175, 33, 2, '1', 'portion', NULL, 0),
(176, 33, 72, '1', 'portion', NULL, 0),
(177, 33, 54, '1', 'portion', NULL, 0),
(178, 33, 18, '1', 'portion', NULL, 0),
(179, 33, 3, '1', 'portion', NULL, 0),
(180, 34, 1, '1', 'portion', NULL, 0),
(181, 34, 27, '1', 'portion', NULL, 0),
(182, 34, 28, '1', 'portion', NULL, 0),
(183, 34, 2, '1', 'portion', NULL, 0),
(184, 34, 73, '1', 'portion', NULL, 0),
(185, 35, 74, '1', 'portion', NULL, 0),
(186, 35, 10, '1', 'portion', NULL, 0),
(187, 35, 75, '1', 'portion', NULL, 0),
(188, 35, 2, '1', 'portion', NULL, 0),
(189, 35, 4, '1', 'portion', NULL, 0),
(190, 35, 76, '1', 'portion', NULL, 0),
(191, 36, 77, '1', 'portion', NULL, 0),
(192, 36, 43, '1', 'portion', NULL, 0),
(193, 36, 78, '1', 'portion', NULL, 0),
(194, 36, 79, '1', 'portion', NULL, 0),
(195, 36, 80, '1', 'portion', NULL, 0),
(196, 37, 81, '1', 'portion', NULL, 0),
(197, 37, 82, '1', 'portion', NULL, 0),
(198, 37, 83, '1', 'portion', NULL, 0),
(199, 37, 84, '1', 'portion', NULL, 0),
(200, 37, 19, '1', 'portion', NULL, 0),
(201, 37, 85, '1', 'portion', NULL, 0),
(202, 38, 86, '1', 'portion', NULL, 0),
(203, 38, 87, '1', 'portion', NULL, 0),
(204, 38, 88, '1', 'portion', NULL, 0),
(205, 38, 89, '1', 'portion', NULL, 0),
(206, 38, 90, '1', 'portion', NULL, 0),
(207, 38, 91, '1', 'portion', NULL, 0),
(208, 39, 92, '1', 'portion', NULL, 0),
(209, 39, 93, '1', 'portion', NULL, 0),
(210, 39, 94, '1', 'portion', NULL, 0),
(211, 39, 17, '1', 'portion', NULL, 0),
(212, 39, 6, '1', 'portion', NULL, 0),
(213, 39, 2, '1', 'portion', NULL, 0),
(214, 40, 95, '1', 'portion', NULL, 0),
(215, 40, 96, '1', 'portion', NULL, 0),
(216, 40, 97, '1', 'portion', NULL, 0),
(217, 40, 98, '1', 'portion', NULL, 0),
(218, 40, 99, '1', 'portion', NULL, 0),
(219, 41, 100, '1', 'portion', NULL, 0),
(220, 41, 21, '1', 'portion', NULL, 0),
(221, 41, 101, '1', 'portion', NULL, 0),
(222, 41, 102, '1', 'portion', NULL, 0),
(223, 41, 42, '1', 'portion', NULL, 0),
(224, 41, 11, '1', 'portion', NULL, 0),
(225, 42, 103, '1', 'portion', NULL, 0),
(226, 42, 104, '1', 'portion', NULL, 0),
(227, 42, 105, '1', 'portion', NULL, 0),
(228, 42, 106, '1', 'portion', NULL, 0),
(229, 42, 36, '1', 'portion', NULL, 0),
(230, 43, 107, '1', 'portion', NULL, 0),
(231, 43, 10, '1', 'portion', NULL, 0),
(232, 43, 108, '1', 'portion', NULL, 0),
(233, 43, 109, '1', 'portion', NULL, 0),
(234, 43, 110, '1', 'portion', NULL, 0),
(235, 43, 111, '1', 'portion', NULL, 0),
(236, 44, 112, '1', 'portion', NULL, 0),
(237, 44, 2, '1', 'portion', NULL, 0),
(238, 44, 75, '1', 'portion', NULL, 0),
(239, 44, 18, '1', 'portion', NULL, 0),
(240, 44, 3, '1', 'portion', NULL, 0),
(241, 44, 113, '1', 'portion', NULL, 0),
(242, 45, 114, '1', 'portion', NULL, 0),
(243, 45, 42, '1', 'portion', NULL, 0),
(244, 45, 115, '1', 'portion', NULL, 0),
(245, 45, 116, '1', 'portion', NULL, 0),
(246, 45, 18, '1', 'portion', NULL, 0),
(247, 45, 117, '1', 'portion', NULL, 0),
(248, 46, 118, '1', 'portion', NULL, 0),
(249, 46, 119, '1', 'portion', NULL, 0),
(250, 46, 11, '1', 'portion', NULL, 0),
(251, 46, 120, '1', 'portion', NULL, 0),
(252, 46, 13, '1', 'portion', NULL, 0),
(253, 46, 121, '1', 'portion', NULL, 0),
(254, 47, 122, '1', 'portion', NULL, 0),
(255, 47, 123, '1', 'portion', NULL, 0),
(256, 47, 124, '1', 'portion', NULL, 0),
(257, 47, 13, '1', 'portion', NULL, 0),
(258, 48, 80, '1', 'portion', NULL, 0),
(259, 48, 125, '1', 'portion', NULL, 0),
(260, 48, 126, '1', 'portion', NULL, 0),
(261, 48, 26, '1', 'portion', NULL, 0),
(262, 48, 10, '1', 'portion', NULL, 0),
(263, 49, 127, '1', 'portion', NULL, 0),
(264, 49, 128, '1', 'portion', NULL, 0),
(265, 49, 129, '1', 'portion', NULL, 0),
(266, 49, 130, '1', 'portion', NULL, 0),
(267, 49, 22, '1', 'portion', NULL, 0),
(268, 49, 131, '1', 'portion', NULL, 0),
(269, 50, 132, '1', 'portion', NULL, 0),
(270, 50, 93, '1', 'portion', NULL, 0),
(271, 50, 133, '1', 'portion', NULL, 0),
(272, 50, 27, '1', 'portion', NULL, 0),
(273, 50, 134, '1', 'portion', NULL, 0),
(274, 51, 53, '1', 'portion', NULL, 0),
(275, 51, 135, '1', 'portion', NULL, 0),
(276, 51, 136, '1', 'portion', NULL, 0),
(277, 51, 57, '1', 'portion', NULL, 0),
(278, 51, 7, '1', 'portion', NULL, 0),
(279, 52, 1, '1', 'portion', NULL, 0),
(280, 52, 137, '1', 'portion', NULL, 0),
(281, 52, 138, '1', 'portion', NULL, 0),
(282, 52, 139, '1', 'portion', NULL, 0),
(283, 53, 140, '1', 'portion', NULL, 0),
(284, 53, 141, '1', 'portion', NULL, 0),
(285, 53, 142, '1', 'portion', NULL, 0),
(286, 53, 143, '1', 'portion', NULL, 0),
(287, 53, 4, '1', 'portion', NULL, 0),
(288, 54, 144, '1', 'portion', NULL, 0),
(289, 54, 10, '1', 'portion', NULL, 0),
(290, 54, 2, '1', 'portion', NULL, 0),
(291, 54, 145, '1', 'portion', NULL, 0),
(292, 54, 16, '1', 'portion', NULL, 0),
(293, 55, 146, '1', 'portion', NULL, 0),
(294, 55, 10, '1', 'portion', NULL, 0),
(295, 55, 122, '1', 'portion', NULL, 0),
(296, 55, 4, '1', 'portion', NULL, 0),
(297, 55, 5, '1', 'portion', NULL, 0),
(298, 56, 21, '1', 'portion', NULL, 0),
(299, 56, 3, '1', 'portion', NULL, 0),
(300, 56, 147, '1', 'portion', NULL, 0),
(301, 56, 4, '1', 'portion', NULL, 0),
(302, 56, 42, '1', 'portion', NULL, 0),
(303, 57, 148, '1', 'portion', NULL, 0),
(304, 57, 149, '1', 'portion', NULL, 0),
(305, 57, 133, '1', 'portion', NULL, 0),
(306, 57, 150, '1', 'portion', NULL, 0),
(307, 57, 151, '1', 'portion', NULL, 0),
(308, 58, 152, '1', 'portion', NULL, 0),
(309, 58, 38, '1', 'portion', NULL, 0),
(310, 58, 153, '1', 'portion', NULL, 0),
(311, 58, 39, '1', 'portion', NULL, 0),
(312, 58, 27, '1', 'portion', NULL, 0),
(313, 59, 154, '1', 'portion', NULL, 0),
(314, 59, 155, '1', 'portion', NULL, 0),
(315, 59, 13, '1', 'portion', NULL, 0),
(316, 59, 156, '1', 'portion', NULL, 0),
(317, 59, 157, '1', 'portion', NULL, 0),
(318, 60, 158, '1', 'portion', NULL, 0),
(319, 60, 159, '1', 'portion', NULL, 0),
(320, 60, 160, '1', 'portion', NULL, 0),
(321, 60, 9, '1', 'portion', NULL, 0),
(322, 60, 27, '1', 'portion', NULL, 0),
(323, 60, 2, '1', 'portion', NULL, 0),
(324, 61, 161, '1', 'portion', NULL, 0),
(325, 61, 135, '1', 'portion', NULL, 0),
(326, 61, 14, '1', 'portion', NULL, 0),
(327, 61, 10, '1', 'portion', NULL, 0),
(328, 61, 12, '1', 'portion', NULL, 0),
(329, 61, 70, '1', 'portion', NULL, 0),
(330, 62, 162, '1', 'portion', NULL, 0),
(331, 62, 163, '1', 'portion', NULL, 0),
(332, 62, 164, '1', 'portion', NULL, 0),
(333, 62, 125, '1', 'portion', NULL, 0),
(334, 62, 6, '1', 'portion', NULL, 0),
(335, 62, 165, '1', 'portion', NULL, 0),
(336, 63, 46, '1', 'portion', NULL, 0),
(337, 63, 2, '1', 'portion', NULL, 0),
(338, 63, 166, '1', 'portion', NULL, 0),
(339, 63, 167, '1', 'portion', NULL, 0),
(340, 63, 145, '1', 'portion', NULL, 0),
(341, 64, 168, '1', 'portion', NULL, 0),
(342, 64, 169, '1', 'portion', NULL, 0),
(343, 64, 11, '1', 'portion', NULL, 0),
(344, 64, 9, '1', 'portion', NULL, 0),
(345, 64, 91, '1', 'portion', NULL, 0),
(346, 65, 141, '1', 'portion', NULL, 0),
(347, 65, 25, '1', 'portion', NULL, 0),
(348, 65, 170, '1', 'portion', NULL, 0),
(349, 65, 147, '1', 'portion', NULL, 0),
(350, 65, 111, '1', 'portion', NULL, 0),
(351, 66, 171, '1', 'portion', NULL, 0),
(352, 66, 69, '1', 'portion', NULL, 0),
(353, 66, 56, '1', 'portion', NULL, 0),
(354, 66, 172, '1', 'portion', NULL, 0),
(355, 67, 173, '1', 'portion', NULL, 0),
(356, 67, 12, '1', 'portion', NULL, 0),
(357, 67, 174, '1', 'portion', NULL, 0),
(358, 67, 14, '1', 'portion', NULL, 0),
(359, 67, 70, '1', 'portion', NULL, 0),
(360, 67, 175, '1', 'portion', NULL, 0),
(361, 68, 176, '1', 'portion', NULL, 0),
(362, 68, 177, '1', 'portion', NULL, 0),
(363, 68, 122, '1', 'portion', NULL, 0),
(364, 68, 6, '1', 'portion', NULL, 0),
(365, 68, 178, '1', 'portion', NULL, 0),
(366, 69, 63, '1', 'portion', NULL, 0),
(367, 69, 2, '1', 'portion', NULL, 0),
(368, 69, 179, '1', 'portion', NULL, 0),
(369, 69, 17, '1', 'portion', NULL, 0),
(370, 69, 180, '1', 'portion', NULL, 0),
(371, 69, 73, '1', 'portion', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recipe_tags`
--

CREATE TABLE `recipe_tags` (
  `recipe_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recipe_tags`
--

INSERT INTO `recipe_tags` (`recipe_id`, `tag_id`) VALUES
(10, 1),
(10, 6),
(11, 7),
(12, 1),
(12, 10),
(13, 7),
(14, 7),
(15, 1),
(16, 1),
(16, 6),
(17, 1),
(18, 1),
(18, 2),
(19, 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reported_type` enum('recipe','comment','user') NOT NULL,
  `reported_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','reviewed','resolved','dismissed') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `reported_type`, `reported_id`, `reason`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 3, 'recipe', 15, 'Hình ảnh không đúng với mô tả công thức', 'resolved', NULL, '2026-03-01 03:00:00', '2026-05-24 10:49:16'),
(2, 5, 'comment', 6, 'Bình luận spam, không liên quan đến công thức', 'dismissed', NULL, '2026-03-05 07:00:00', '2026-05-24 10:52:46'),
(3, 7, 'recipe', 18, 'Công thức bị trùng lặp với một bài đã đăng trước đó', 'resolved', NULL, '2026-03-12 02:00:00', '2026-05-24 10:52:42'),
(4, 4, 'user', 7, 'Tài khoản có hành vi spam bình luận', 'dismissed', NULL, '2026-03-15 04:00:00', '2026-05-17 18:08:42'),
(5, 9, 'recipe', 27, 'công thức không dúng', 'resolved', NULL, '2026-05-24 11:15:46', '2026-05-24 11:17:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'site_name', 'Smart Recipe', '2026-05-22 08:16:06'),
(2, 'admin_email', 'admin@smartrecipe.com', '2026-05-22 08:16:06'),
(3, 'theme_mode', 'light', '2026-05-22 08:16:06'),
(4, 'smtp_server', 'smtp.example.com', '2026-05-22 08:16:06'),
(5, 'smtp_port', '587', '2026-05-22 08:16:06'),
(6, 'smtp_user', 'user@example.com', '2026-05-22 08:16:06'),
(7, 'smtp_pass', '', '2026-05-22 08:16:06'),
(8, 'moderation_enabled', 'true', '2026-05-22 08:16:06'),
(9, 'user_registration', 'true', '2026-05-22 08:16:06'),
(10, 'daily_backup', 'false', '2026-05-22 08:16:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Quick & Easy', 'quick-easy', '2026-04-11 17:42:54'),
(2, 'Vegetarian', 'vegetarian', '2026-04-11 17:42:54'),
(3, 'Vegan', 'vegan', '2026-04-11 17:42:54'),
(4, 'Gluten-Free', 'gluten-free', '2026-04-11 17:42:54'),
(5, 'Low-Carb', 'low-carb', '2026-04-11 17:42:54'),
(6, 'Healthy', 'healthy', '2026-04-11 17:42:54'),
(7, 'Comfort Food', 'comfort-food', '2026-04-11 17:42:54'),
(8, 'Seasonal', 'seasonal', '2026-04-11 17:42:54'),
(9, 'Party Food', 'party-food', '2026-04-11 17:42:54'),
(10, 'Kid-Friendly', 'kid-friendly', '2026-04-11 17:42:54'),
(11, 'Asian', 'asian', '2026-05-23 19:39:10'),
(12, 'Dinner', 'dinner', '2026-05-23 19:39:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notification_prefs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_prefs`)),
  `privacy_prefs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`privacy_prefs`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `display_name`, `bio`, `date_of_birth`, `profile_image`, `role`, `is_active`, `created_at`, `updated_at`, `notification_prefs`, `privacy_prefs`) VALUES
(1, 'admin', 'admin@food.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Admin Chef', 'System Administrator & Head Chef', '1990-01-15', '/smart-recipes/frontend/assets/images/users/user_1_1779042535.png', 'admin', 1, '2025-12-31 17:00:00', '2026-05-17 18:28:55', NULL, NULL),
(2, 'linh_nguyen', 'linh@gmail.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Nguyễn Linh', 'Đam mê nấu ăn từ nhỏ, thích chia sẻ công thức gia đình', '2004-08-23', '/smart-recipes/frontend/assets/images/users/user_2_1779042846.jpg', 'user', 1, '2026-01-05 01:30:00', '2026-05-17 18:34:06', NULL, NULL),
(3, 'chef_gordon', 'gordon@food.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Gordon Ramsay', 'Professional chef with 20 years experience', '1985-03-10', NULL, 'user', 1, '2026-01-10 07:00:00', '2026-05-17 18:25:14', NULL, NULL),
(4, 'thu_hoa', 'thuhoa@gmail.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Thu Hòa', 'Yêu bếp, yêu gia đình', '1998-12-05', NULL, 'user', 1, '2026-01-15 03:00:00', '2026-05-17 18:25:18', NULL, NULL),
(5, 'cam_anh', 'camanh@gmail.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Cẩm Anh', 'Food blogger & recipe creator', '2000-02-21', NULL, 'user', 1, '2026-02-01 02:00:00', '2026-05-17 18:25:29', NULL, NULL),
(6, 'rachael_ray', 'rachael@food.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Rachael Ray', '30-minute meals specialist', '1992-07-18', NULL, 'user', 1, '2026-02-10 04:30:00', '2026-05-17 18:25:36', NULL, NULL),
(7, 'minh_duc', 'minhduc@gmail.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Minh Đức', 'Sinh viên thích nấu ăn tiết kiệm', '2002-05-30', NULL, 'user', 1, '2026-02-20 09:00:00', '2026-05-17 18:25:42', NULL, NULL),
(8, 'jamie_oliver', 'jamie@food.com', '$2y$10$qrAkYK.vlr9v2wF16AZhwu/fpNE2Ht8UFHCgFO2DF4XHJyRH1rJvy', 'Jamie Oliver', 'Healthy food advocate', '1988-11-22', NULL, 'user', 1, '2026-03-01 01:00:00', '2026-05-17 18:25:48', NULL, NULL),
(9, 'linhluclac', 'nguyenlinh230809@gmail.com', '$2y$10$gRVE4MVyBsmRAHjXqaGV7OBmuPuM3yfkM5pK8pmc0bdBph2Jrl.di', NULL, NULL, NULL, '/smart-recipes/frontend/assets/images/users/user_9_1779296953.jpg', 'user', 1, '2026-05-17 18:24:15', '2026-05-21 10:00:10', NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_recipe` (`user_id`,`recipe_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_recipe_id` (`recipe_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipe_id` (`recipe_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_recipe` (`recipe_id`,`user_id`),
  ADD KEY `idx_recipe_id` (`recipe_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `recipe_directions`
--
ALTER TABLE `recipe_directions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipe_id` (`recipe_id`);

--
-- Chỉ mục cho bảng `recipe_images`
--
ALTER TABLE `recipe_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipe_id` (`recipe_id`);

--
-- Chỉ mục cho bảng `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipe_id` (`recipe_id`),
  ADD KEY `idx_ingredient_id` (`ingredient_id`);

--
-- Chỉ mục cho bảng `recipe_tags`
--
ALTER TABLE `recipe_tags`
  ADD PRIMARY KEY (`recipe_id`,`tag_id`),
  ADD KEY `idx_recipe_id` (`recipe_id`),
  ADD KEY `idx_tag_id` (`tag_id`);

--
-- Chỉ mục cho bảng `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `idx_reported_type_id` (`reported_type`,`reported_id`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;

--
-- AUTO_INCREMENT cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;

--
-- AUTO_INCREMENT cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT cho bảng `newsletter_subscriptions`
--
ALTER TABLE `newsletter_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT cho bảng `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT cho bảng `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `recipe_directions`
--
ALTER TABLE `recipe_directions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT cho bảng `recipe_images`
--
ALTER TABLE `recipe_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT cho bảng `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT cho bảng `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `recipe_directions`
--
ALTER TABLE `recipe_directions`
  ADD CONSTRAINT `recipe_directions_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `recipe_images`
--
ALTER TABLE `recipe_images`
  ADD CONSTRAINT `recipe_images_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `recipe_tags`
--
ALTER TABLE `recipe_tags`
  ADD CONSTRAINT `recipe_tags_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
