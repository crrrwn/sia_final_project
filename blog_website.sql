-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2024 at 09:02 AM
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
-- Database: `blog_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(8, 25, 3, 'thanks!', '2024-10-29 04:56:09'),
(9, 38, 8, 'wow', '2024-10-29 08:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `views` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `category`, `created_at`, `updated_at`, `views`) VALUES
(10, 'Mindful Meditation', 'Start your day with 10-15 minutes of mindfulness meditation. It helps reduce stress, improves focus, and boosts emotional health.', 'DALL·E 2024-10-18 21.53.33 - A serene and calming scene depicting someone practicing mindful meditation, sitting peacefully in a relaxed position, perhaps in a natural setting lik.jpg', 'Wellness Practices', '2024-10-18 13:22:46', '2024-10-28 12:50:59', 5),
(11, 'Regular Exercise', 'Whether it’s a brisk walk, yoga, or strength training, moving your body daily promotes cardiovascular health, increases energy, and enhances mood.', 'DALL·E 2024-10-18 21.23.52 - A serene outdoor scene of a person engaging in regular exercise, such as jogging or yoga, in a peaceful park setting. The background includes natural .jpg', 'Wellness Practices', '2024-10-18 13:24:43', '2024-10-28 14:33:17', 14),
(12, 'Adequate Sleep', 'Aim for 7-9 hours of quality sleep each night to allow your body to recover and function optimally. Create a bedtime routine and limit screen time before bed to improve sleep quality.', 'DALL·E 2024-10-18 21.25.57 - A peaceful bedroom scene showing a person sleeping comfortably in a cozy bed. The setting should be calm and serene, with soft lighting, comfortable b.jpg', 'Wellness Practices', '2024-10-18 13:27:11', '2024-10-28 14:33:08', 17),
(13, 'Balanced Meals', 'Include a variety of whole grains, lean proteins, healthy fats, and colorful vegetables in your meals. This ensures you\'re getting all the essential nutrients.', 'DALL·E 2024-10-18 21.30.57 - A vibrant, appetizing plate of balanced meals, featuring a colorful assortment of vegetables, whole grains, lean proteins, and healthy fats. The setti.jpg', 'Healthy Eating', '2024-10-18 13:33:44', '2024-10-18 13:36:20', 2),
(14, 'Hydration', 'Drinking enough water throughout the day is crucial for digestion, circulation, and maintaining energy levels. Aim for at least 8 glasses of water per day.', 'DALL·E 2024-10-18 21.37.51 - A refreshing scene showcasing hydration, with a clear glass of water surrounded by fresh fruits like lemon and cucumber. The setting should evoke a se.jpg', 'Healthy Eating', '2024-10-18 13:38:18', '2024-10-29 04:55:29', 14),
(15, 'Mindful Eating', 'Pay attention to your body’s hunger and fullness cues. Eat slowly, savor your food, and avoid distractions like watching TV during meals.', 'DALL·E 2024-10-18 21.39.33 - A serene scene showing mindful eating, with a person slowly enjoying a colorful and healthy meal, paying attention to each bite. The setting is calm, .jpg', 'Healthy Eating', '2024-10-18 13:39:56', '2024-10-18 13:39:56', 0),
(16, 'Journaling', 'Spend a few minutes each day writing down your thoughts, feelings, and goals. Journaling is a great way to process emotions and reflect on your personal growth.', 'DALL·E 2024-10-18 21.41.13 - A peaceful scene showing someone journaling, with a notebook open on a cozy table setting. The person is writing thoughtfully, and the setting include.jpg', 'Self Care', '2024-10-18 13:42:33', '2024-10-18 13:42:33', 0),
(17, 'Pamper Yourself', 'Treat yourself to a relaxing bath, skincare routine, or even a spa day. Doing something that makes you feel good can recharge your mind and body.', 'DALL·E 2024-10-18 21.44.33 - A serene, relaxing scene showing someone pampering themselves. The setting includes self-care items like a facial mask, skincare products, a bath with.jpg', 'Self Care', '2024-10-18 13:45:22', '2024-10-18 13:45:22', 0),
(18, 'Set Boundaries', 'Learning to say \"no\" when necessary and creating boundaries is a form of self-care that protects your energy and mental health.', 'DALL·E 2024-10-18 21.46.33 - A thoughtful scene representing the act of setting boundaries. It shows a person in a calm, personal space, maybe writing in a journal or gently holdi.jpg', 'Self Care', '2024-10-18 13:47:19', '2024-10-18 13:47:19', 0),
(19, 'Digital Detox', 'Take regular breaks from social media and screen time to reduce anxiety and reclaim your focus. Consider dedicating one day a week to disconnecting from devices.', 'DALL·E 2024-10-18 21.48.24 - A peaceful scene representing a digital detox. It shows a person relaxing in a natural environment, with no electronic devices in sight, possibly read.jpg', 'Lifestyle Tips', '2024-10-18 13:49:04', '2024-10-18 13:49:04', 0),
(20, 'Gratitude Practice', 'Start or end your day by writing down three things you’re grateful for. Practicing gratitude shifts your mindset toward positivity and appreciation.', 'DALL·E 2024-10-18 21.49.53 - A calming scene depicting someone engaging in a gratitude practice. The person is sitting in a peaceful, cozy environment, perhaps writing in a gratit.jpg', 'Lifestyle Tips', '2024-10-18 13:50:20', '2024-10-18 13:50:20', 0),
(21, 'Stay Socially Connected', 'Maintaining strong relationships with friends and family is key to mental and emotional well-being. Make time for meaningful interactions, even if it’s a quick call or text.', 'DALL·E 2024-10-18 21.51.10 - A warm, cheerful scene showing a group of friends or family members staying socially connected, either through an in-person gathering or a virtual cal.jpg', 'Lifestyle Tips', '2024-10-18 13:51:57', '2024-10-28 12:43:37', 3),
(22, 'Morning Meditation', 'Start your day with a clear mind by meditating for 10-15 minutes each morning. Sit comfortably, close your eyes, and focus on your breath. Meditation helps reduce stress and increase focus, setting the tone for a productive and calm day ahead. You can also use a guided meditation app if you\'re just starting out, which will help you stay focused and make the practice easier to maintain.\r\n', 'DALL·E 2024-10-28 21.08.53 - A serene morning scene featuring a person sitting cross-legged on a yoga mat by a window. Sunlight is streaming in, creating a peaceful, warm atmosphe.jpg', 'Wellness Practices', '2024-10-28 13:10:01', '2024-10-28 14:33:04', 14),
(23, 'Nature Walks', 'Spending time in nature can significantly improve your mood and energy. Whether it’s a walk in a local park or a hike through the woods, being surrounded by greenery helps you disconnect from daily stress and connect to something greater. Walking in nature also provides light exercise, which releases endorphins—the body\'s natural mood elevators. Make it a habit to walk without any distractions, such as headphones, and immerse yourself in the sights and sounds of nature.', 'DALL·E 2024-10-28 21.11.52 - A winding forest path with lush green trees, dappled sunlight filtering through the leaves, creating a peaceful and natural atmosphere. Alternatively,.webp', 'Wellness Practices', '2024-10-28 13:13:09', '2024-10-29 04:53:27', 11),
(24, 'Yoga', 'Practicing yoga even for 20 minutes can bring you both mental relaxation and physical vitality. It strengthens your muscles, increases flexibility, and improves balance, while also encouraging mindfulness through breath control. Yoga also promotes better posture and body awareness, which can help alleviate tension, particularly for those who sit at desks for long periods. Consider trying different styles of yoga, such as Hatha for gentle stretching or Vinyasa for a more dynamic flow.', 'DALL·E 2024-10-28 21.14.40 - A person practicing a simple yoga pose, specifically Tree Pose, in a peaceful garden or a serene yoga studio. The background is calm, with either a lu.jpg', 'Wellness Practices', '2024-10-28 13:15:58', '2024-10-29 04:53:24', 7),
(25, 'Aromatherapy', 'Essential oils like lavender, eucalyptus, or peppermint can have profound effects on your mood and stress levels. Use a diffuser or simply add a few drops of essential oil to a warm bath to unwind. Aromatherapy works by stimulating your olfactory system, which directly impacts the part of your brain that regulates emotions. Incorporate aromatherapy into your nighttime routine to promote relaxation and better sleep, or use invigorating scents like citrus during the day for an energy boost.', 'DALL·E 2024-10-28 21.17.01 - An essential oil diffuser on a wooden table, with mist gently rising from it. Nearby, there are a few small bottles of essential oils. The setting is .jpg', 'Wellness Practices', '2024-10-28 13:17:56', '2024-11-22 08:57:05', 14),
(26, 'Eat More Whole Foods', 'Incorporate more whole foods, such as fruits, vegetables, whole grains, and legumes, into your diet. Whole foods are nutrient-dense, providing your body with the vitamins and minerals it needs without added preservatives or unhealthy fats. Eating a variety of colorful produce ensures you get a wide range of nutrients. Whole foods are also rich in fiber, which helps maintain healthy digestion and keeps you feeling full longer, reducing the likelihood of overeating.', 'DALL·E 2024-10-28 21.19.24 - A vibrant display of colorful fruits and vegetables laid out on a kitchen counter. The scene is filled with a variety of fresh produce, including appl.jpg', 'Healthy Eating', '2024-10-28 13:21:44', '2024-10-28 13:21:44', 0),
(27, 'Practice Portion Control', 'Understanding portion sizes helps prevent overeating and encourages mindful eating. Use smaller plates or bowls to serve food, and take the time to savor each bite. Eating slowly helps you recognize when you\'re full, which can help maintain a healthy weight. Portion control also allows you to enjoy all the foods you love without feeling deprived, promoting a healthy relationship with food and preventing guilt after meals.\r\n', 'DALL·E 2024-10-28 21.23.31 - A well-portioned meal on a small plate with a balanced mix of vegetables, protein, and grains. The plate includes colorful vegetables, a piece of gril.jpg', 'Healthy Eating', '2024-10-28 13:25:17', '2024-10-28 13:25:17', 0),
(29, 'Plan Your Meals', 'Meal planning can help you make healthier choices and avoid last-minute takeout. Set aside time each week to plan balanced meals, focusing on a variety of food groups. Preparing ingredients in advance makes it easier to cook nutritious meals, even on busy days. Meal planning also saves money and reduces food waste, allowing you to be more mindful about the ingredients you buy and use. It helps you maintain consistency in your diet and prevents unhealthy eating patterns.', 'DALL·E 2024-10-28 21.38.39 - A weekly meal plan written on a chalkboard, with neatly arranged days of the week and meal details, or alternatively, a collection of pre-portioned in.jpg', 'Healthy Eating', '2024-10-28 13:40:47', '2024-10-29 04:55:05', 9),
(30, 'Get Enough Sleep', 'Quality sleep is vital for your body and mind to function optimally. Aim for 7-8 hours of restful sleep every night to recharge and be ready for the next day. Create a relaxing bedtime routine by limiting screen time, avoiding caffeine late in the day, and creating a calming sleep environment. Good sleep hygiene can significantly improve your mood, focus, and energy.\r\n', 'DALL·E 2024-10-28 22.06.24 - A cozy bedroom setting with soft lighting, fluffy pillows on a comfortable bed, and a book resting on the nightstand. The room is warm and inviting, w.jpg', 'Self Care', '2024-10-28 14:07:16', '2024-10-28 14:07:16', 0),
(31, 'Unplug and Disconnect', 'et aside time each day to disconnect from technology. Being constantly connected can be overwhelming, and unplugging can help you regain mental clarity. Take time to go for a walk, read a book, or simply enjoy quiet moments without any notifications. This practice allows you to be more present and enjoy life without distractions.', 'DALL·E 2024-10-28 22.07.53 - A person reading a book in a garden, sitting comfortably among lush greenery and flowers. Their phone is placed face down on a small table nearby, emp.jpg', 'Self Care', '2024-10-28 14:08:39', '2024-10-28 14:08:39', 0),
(32, 'Eat Mindfully', 'Pay attention to what you eat and savor each bite. Eating mindfully can improve digestion, increase satisfaction, and reduce overeating. Avoid eating in front of the TV or while scrolling through your phone; instead, create a peaceful environment where you can enjoy your meal without distractions. Mindful eating helps you become more aware of your hunger and fullness cues, making it easier to avoid overeating and build a positive relationship with food.', 'DALL·E 2024-10-28 21.26.16 - A person sitting at a dining table, smiling while slowly enjoying a meal. The setting is cozy, with a warm and inviting atmosphere. The table is neatl.jpg', 'Healthy Eating', '2024-10-28 14:12:01', '2024-10-29 04:55:22', 27),
(33, 'Practice Self-Compassion', 'Treat yourself with kindness, especially during challenging times. Avoid negative self-talk and recognize that it’s okay to make mistakes. Practicing self-compassion means acknowledging your feelings without judgment and being patient with yourself. It encourages a positive mindset, reduces anxiety, and fosters emotional resilience.\r\n', 'Untitled design.jpg', 'Self Care', '2024-10-28 14:12:22', '2024-10-28 14:12:22', 0),
(34, 'Set Boundaries', 'Establishing healthy boundaries is essential for maintaining your mental well-being. Learn to say no when needed, and prioritize your own needs without feeling guilty. Whether it\'s work or personal life, boundaries help you manage your time effectively and protect your emotional energy. Setting limits with others also allows you to focus on what’s important for you, promoting a healthy work-life balance.', 'Untitled design (1).jpg', 'Self Care', '2024-10-28 14:15:21', '2024-10-28 14:15:31', 1),
(35, 'Make Time for Physical Activity', 'Regular exercise not only benefits physical health but also greatly impacts mental health. Aim for at least 30 minutes of physical activity each day, whether it’s walking, running, dancing, or practicing yoga. Exercise releases endorphins—your body’s natural mood boosters—that can help reduce anxiety and improve overall happiness. Pick activities that you enjoy to keep yourself motivated.', 'afss.jpg', 'Lifestyle Tips', '2024-10-28 14:18:53', '2024-10-28 14:18:53', 0),
(36, 'Create a Soothing Environment', 'Your environment has a big impact on your mental health. Make your home a place of comfort by decluttering, adding cozy decorations, or using calming scents like lavender. A soothing environment helps promote relaxation, reduces stress, and creates a sense of peace.', 'b.jpg', 'Lifestyle Tips', '2024-10-28 14:23:22', '2024-10-28 14:23:22', 0),
(37, 'Practice Deep Breathing', 'Deep breathing exercises are a simple yet effective way to reduce stress. Spend a few minutes each day focusing on deep breaths—inhale deeply through your nose, hold for a few seconds, and exhale slowly through your mouth. This activates your parasympathetic nervous system, calming your body and mind.', 'c.jpg', 'Lifestyle Tips', '2024-10-28 14:24:59', '2024-10-28 14:24:59', 0),
(38, 'Nurture Social Connections', 'Healthy social relationships are key to emotional well-being. Make an effort to connect with loved ones—whether it’s through a heartfelt conversation, a video call, or spending quality time together. Feeling connected and supported can improve your mood and provide you with a sense of belonging.\r\n', 'd.jpg', 'Lifestyle Tips', '2024-10-28 14:26:10', '2024-10-29 08:30:50', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'user', 'user@gmail.com', '$2y$10$H1c/NJ3b7BqKYNEg4M5.4O4OGANuJOG0P/6qAwCFlEpOznMkk2m7W', 'user'),
(2, 'user', 'user@gmail.com', '$2y$10$kVMlwQkVJuFhYBxDZ0s9mOeSVUhG2sZQqF1EmTcVFPgpr27dIAY4.', 'user'),
(3, 'admin', '', '$2y$10$mgmJj5iZq6xOZA4M.dvOl.k./7Trp2YI25Gc5VViL8NM8IqTEaqiO', 'admin'),
(4, 'admin', 'admin@gmail.com', '$2y$10$B.EyE.j1Ei6YWWbluK82re88DZ4ZvtrFnOemd1Lc8DjwbEkyPl8nu', 'admin'),
(5, 'Admin@2', 'admin2@gmail.com', '$2y$10$RP1alVHDdUVu80Lrgz9KEeXXIeXh.5u/Nf8AszqkhKVrxbTJrrXPm', 'admin'),
(6, 'Admin@3', 'admin3@gmail.com', '$2y$10$DE9tKhMJesk/yKcfHXupue/pqxZtkPKvPbXrRaMnBi/Fb9OjGKcqi', 'admin'),
(7, 'admin@4', 'admin4@gmail.com', '$2y$10$tNFmZnAmabqN3NmHMl7bqupOuBBqXQrbB86ArtIuI5I8gw1YMG9qq', 'admin'),
(8, 'bagonguser', 'user@gmail.com', '$2y$10$TD.JW4XDFTcvkxIuTgA4w.BHqC7g4fAL/H0OUv70rOjtFy/ImhohO', 'user'),
(9, 'bagongadmin', 'admin@gmail.com', '$2y$10$Ht3KJ7LzZO55/6sktMKnhe1bAkW/PFDEMzFwEk8PXm1NR7A.2ZN92', 'admin');

--
-- Indexes for dumped tables
--

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
