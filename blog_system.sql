-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2024 at 01:02 PM
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
-- Database: `blog_system`
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
(7, 'DEVCOM'),
(9, 'EDITORIAL BOARD'),
(8, 'FOLIO'),
(10, 'KOMIKS'),
(6, 'NEWS'),
(11, 'OPINION'),
(5, 'SIDELINE'),
(4, 'SILONG'),
(3, 'SPORTS');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `user_id`, `category_id`, `image_path`, `views`, `created_at`, `image`) VALUES
(6, '𝗦𝗣𝗢𝗥𝗧𝗦 𝗨𝗣𝗗𝗔𝗧𝗘', '𝗦𝗣𝗢𝗥𝗧𝗦 𝗨𝗣𝗗𝗔𝗧𝗘 | Two silvers and two bronze medals have been successfully added to the #CCAA19 tally of Mindoro State University after its paddlers triumphed in the pingpong battle on November 9 at Gaisano Capital Mall.\\r\\nIt was an &#039;almost gold&#039; for Sabina Elumba, who fell short in the Women&#039;s Singles against DWCC in an intense exchange of powerful chops and coordinated footwork, concluding the finals at 3-0 and settling for second place.\\r\\nMeanwhile, Mary Rose Pangilinan displayed a combination of raging smashes and quick serves, earning herself the bronze in the Women&#039;s Singles.\\r\\nOn the other hand, the duo of Aivy Jean Dela Cruz and Margie Luna claimed the silver medal, while the tandem of Joven Esole and Neil Francis Castor clinched the bronze medal in the Men&#039;s and Women&#039;s Doubles categories, respectively.\\r\\nMinSU concluded the season with a total of 4 medals in the Table Tennis Competition.', 2, 3, NULL, 32, '2024-12-01 10:01:41', 'uploads/674c340502f6c.jpg'),
(7, '𝐓𝐢𝐲𝐚𝐤 𝐚𝐭 𝐒𝐢𝐠𝐮𝐫𝐚𝐝𝐨', '𝐓𝐢𝐲𝐚𝐤 𝐚𝐭 𝐒𝐢𝐠𝐮𝐫𝐚𝐝𝐨 | Michael Angelo Manal\\r\\nSa raming beses nang itinanong sa akin kung bakit ikaw ang pinili,\\r\\nkahit minsa’y hindi ko na rin matantsa kung ano bang sagot ang hinihingi.\\r\\nSabi nila, ‘wag daw kitang piliin dahil no’ng panahong tayo’y nasa publiko\\r\\nat kinailangan ko ang iba,\\r\\nhinayaan mo lang akong etsapwerahin ka at isantabi muna.\\r\\nHindi mo nga raw ako matutulungan pagdating ng pagkakataon,\\r\\nkapag kinailangan ko nang harapin ang reyalidad ng panahon. \\r\\nHindi rin daw kita mapakikinabangan \\r\\ndahil hindi mo raw kayang abutin ang pamantayan. \\r\\nNagtataka nga ako,\\r\\nsa pagbabago pala talaga ng panahon \\r\\npati sa halaga mo, ang iba’y napatatanong.\\r\\nBinago na rin ang konsepto ng pag-ibig na dapat sa iyo’y patuloy na inilalaan.\\r\\nAng nakasanayan ko kasing pagmamahal na para sa iyo ay ‘yung hindi pilit pero tiyak, ‘yun bang simple pero sigurado. \\r\\nHindi kailangang sobra-sobra pero walang paghinto at dapat totoo–hindi lamang purong pagpapanggap. \\r\\nSana’y manahan ang pag-ibig na handang sumugal kahit maraming pamimilian. ‘Yun bang tipong kahit walang takipsilim, mas pipiliin pa ring samahan na tumitig sa kalangitan. Pag-ibig na tulad ng sitwasyong umuulan, handa kang payungan at ayos lang na mabasa basta huwag ka. ‘Yung pagmamahal na hindi man marangya pero hindi rin mapagkait ng katotohanang “ikaw ang wikang Filipino na dapat ay minamahal sa araw-araw”\\r\\nHindi ka man piliin ng lahat pero mas lamang ang pumipili sa’yo tulad ko.\\r\\nAt kahit ang pananaw sa iyo’y pabago-bago,\\r\\nIkaw ay tiyak at \\r\\nsa ‘yo \\r\\nako ay sigurado.', 2, 4, NULL, 38, '2024-12-01 10:04:05', 'uploads/674c349516434.jpg'),
(8, 'FORMER MINSU BEAUTIES DOMINATE BINIBINIBING NAUJAN 2024', '𝗡𝗘𝗪𝗦 | Three former students of Mindoro State University - Calapan City Campus showcased their beauty and intellect, securing the major titles in the recently concluded Bb. Naujan 2024, held last night.\\r\\n\\r\\nRonette Castillo, a graduate of AB Psychology and the representative of Brgy. Pagkakaisa, was crowned Binibining Naujan 2024 and will represent the municipality in the Ms. Oriental Mindoro pageant.\\r\\n\\r\\nMeanwhile, Pamela Khaye Doculan, the reigning Ms. University Olympics from Brgy. Adrialuna, and Raycel Pabelenia, a BSED Filipino graduate from Brgy. Evangelista, were crowned 1st and 2nd runner-up, respectively.', 2, 6, NULL, 24, '2024-12-01 10:07:41', 'uploads/674c356d3a0d5.jpg'),
(9, 'PRINCE KAIDE FUENTES, INIHALAL NA BAGONG MFSC CHAIR, MINSU STUDENT REGENT', '𝗕𝗥𝗘𝗔𝗞𝗜𝗡𝗚 𝗡𝗘𝗪𝗦 | Itinalaga na ng mga opisyales ng USG si Prince Kaide Fuentes bilang pinaka bagong MinSCAT Federation of Student Councils (MFSC) Chair na siya ring uupo bilang Student Regent ng MinSU. \\r\\nSi Fuentes ang kasalukuyang USG President ng MinSU Main Campus na nakatapat ng dalawa pang USG President ng iba pang MinSU campuses.', 2, 6, NULL, 102, '2024-12-01 10:09:33', 'uploads/674c35ddb9c57.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'testing', 'testing@gmail.com', '$2y$10$vJarqtZ9TI.YCNLOu3mUje1KWR0ehGIgt4XmR8Lz5fF5v6m8UoHOK', 'user', '2024-12-01 06:04:52'),
(2, 'admin', 'admin@gmail.com', '$2y$10$F0M7oMb6w2cZn99Ues/Mo.H7jSlSRDQxXMK2HBCVAOSmAQouj8ef.', 'admin', '2024-12-01 06:05:57'),
(3, 'newadmin', 'newadmin@gmail.com', '$2y$10$AWJizicNZkVqOSY9aYUP/e3eFwYQWEIS502BCYaCW1I681HXJMV1e', 'admin', '2024-12-01 11:43:27');

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
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
