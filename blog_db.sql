-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2024 at 07:15 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(3, 'DEVCOM'),
(14, 'FOLIO'),
(13, 'SPORTS');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(13, 24, 1, 'dfgftrdeswaesrdgffg', '2024-12-10 16:09:13', '2024-12-10 16:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `start_date`, `end_date`, `created_by`, `created_at`) VALUES
(1, 'sdfgh', 'adsfghhfd', '2024-12-10', '2024-12-10', 2, '2024-12-10 07:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `status` enum('draft','pending','published') NOT NULL,
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `author_id`, `category_id`, `status`, `views`, `created_at`, `updated_at`, `image`) VALUES
(24, '\'𝗔𝗡𝗚 𝗟𝗜𝗪𝗔𝗡𝗔𝗚 𝗡𝗚 𝗠𝗜𝗡𝗦𝗨 𝗖𝗔𝗟𝗔𝗣𝗔𝗡\'', '𝗗𝗘𝗩𝗖𝗢𝗠 | Mindoro State University - Calapan City Campus is now enjoying the recently installed solar street lights along the vicinity of the campus highway, extending to the front entrance of the campus.\r\nThis project is part of the lighting initiative for Calapan South Road, funded by the Department of Public Works and Highways (DPWH) through the efforts of Rep. Arnan C. Panaligan.\r\nThese new solar-powered lights enliven the once dimly lit pathway, providing a safer environment for the MinSU community at night.\r\nMinSU finds this installation a significant step towards creating a more secure and welcoming environment for the student body.\r\nvia Myko Piolo Feudo\r\n', 3, 3, 'published', 87, '2024-12-10 13:36:29', '2024-12-10 18:47:45', '/uploads/cd0c29bf188e95e7600478e159d59b85.jpg'),
(29, 'SPORTS ', 'P1: 𝗦𝗣𝗢𝗥𝗧𝗦 𝗨𝗣𝗗𝗔𝗧𝗘 | Two silvers and two bronze medals have been successfully added to the #CCAA19 tally of Mindoro State University after its paddlers triumphed in the pingpong battle on November 9 at Gaisano Capital Mall.\r\nIt was an \'almost gold\' for Sabina Elumba, who fell short in the Women\'s Singles against DWCC in an intense exchange of powerful chops and coordinated footwork, concluding the finals at 3-0 and settling for second place.\r\nMeanwhile, Mary Rose Pangilinan displayed a combination of raging smashes and quick serves, earning herself the bronze in the Women\'s Singles.\r\nOn the other hand, the duo of Aivy Jean Dela Cruz and Margie Luna claimed the silver medal, while the tandem of Joven Esole and Neil Francis Castor clinched the bronze medal in the Men\'s and Women\'s Doubles categories, respectively.\r\nMinSU concluded the season with a total of 4 medals in the Table Tennis Competition.\r\n', 3, 13, 'published', 53, '2024-12-10 18:15:45', '2024-12-10 18:48:29', '/uploads/ab0c4e7245a2732bd13a7b0f1225987d.jpg'),
(30, 'MinSU Lady Knights dismantle LGC FOXES, 72-50; hold four in a row dub', 'P2: 𝗦𝗣𝗢𝗥𝗧𝗦 𝗨𝗣𝗗𝗔𝗧𝗘 | MinSU Lady Knights returned to five-on-five action, winning in dominant fashion, 72-50, over the LGC Golden Foxes just a week after their stellar gold outing in women’s 3x3 basketball at the STRASUC Olympics in Palawan.\r\nCuasay led the charge with 21 points, 7 rebounds, 8 assists, 5 steals, and 4 blocks, showcasing the team’s aggressive play on both ends.\r\nThis win marks their fourth straight victory, keeping their undefeated record intact as they continue their red-hot run in the #CCAA19 campaign.\r\n', 2, 13, 'published', 58, '2024-12-10 18:29:33', '2024-12-10 18:49:01', '/uploads/f7278cb5d42d449977aa06de82916798.jpg'),
(31, 'US MONSTER', '𝐔𝐬 𝐌𝐨𝐧𝐬𝐭𝐞𝐫𝐬\r\n| James Andrei Casin\r\nThy hand as light as a cloud,\r\nchoking thine oval lies.\r\nThou breathless doll of mud and clay,\r\nshalt be exposed to sunlight\'s ray.\r\nThine eyes so pure,\r\nbut heart\'s so dark. \r\nThy mind so clear,\r\nyet thou act with fear.\r\nI am thee, thou is I.\r\nThere is nothing now to hide.\r\nThine evil acts and my darkest thoughts,\r\nbrimmed the bottle of sins thou brought.\r\nMirrored we are yet act so close.\r\nThy selfish ways like a red thorned rose.\r\nMy deepest secrets I buried in thee.\r\nWhy thou act bad, dig and thou shall see.\r\nI am thine evil thoughts within.\r\nThou must know! Thou must see!\r\nI bestowed thee a life of death and sin, \r\nwander around, thou will soon be.\r\nA monster of yourself... and me.\r\n', 2, 14, 'published', 12, '2024-12-10 18:53:46', '2024-12-10 19:14:01', '/uploads/2cee7c348d22f2ea441bb575bde56087.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','writer','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'testuser', 'testuser@gmail.com', '$2y$10$HFnNK6w0QvY.YuJqpJYyeus9.5SK09IefKwBkcsIL6tG35ZPgHXJS', 'user', '2024-12-10 07:37:05'),
(2, 'testadmin', 'testadmin@gmail.com', '$2y$10$Cv2Vc7h8zy3Tm9nEuN7.puM.l4QbBSERJf/Tcs10uktENBxlow/zS', 'admin', '2024-12-10 07:37:53'),
(3, 'testwriter', 'testwriter@gmail.com', '$2y$10$JaWSenOhHQQIaHYzSkHbmeNQaW7RVwksBD/Ez6aSXSfuKQ7YKaKl.', 'writer', '2024-12-10 07:39:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
