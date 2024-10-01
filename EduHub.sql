-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 06:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','moderator') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blood_donation`
--

CREATE TABLE `blood_donation` (
  `request_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `patient_problem` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `status` enum('pending','accepted','denied') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_donation`
--

INSERT INTO `blood_donation` (`request_id`, `student_id`, `blood_type`, `request_date`, `patient_problem`, `location`, `phone_number`, `status`) VALUES
(2, 1, 'A+', '2024-09-17 03:35:43', 'Dengue', 'Natun Bazar', '12345678', 'accepted'),
(3, 1, '', '2024-09-17 11:28:32', '', '', '', 'denied'),
(5, 3, 'A+', '2024-09-21 10:58:29', 'Dengue', 'Asuliya', '1234567', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `blood_find`
--

CREATE TABLE `blood_find` (
  `blood_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `location` enum('Mirpur','Dhanmondi','Khilkhet','Uttara','Banani','Gulshan','Motijheel','Badda','Farmgate','Mohammadpur','Shahbagh') NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `last_donation_date` varchar(50) DEFAULT 'Not Yet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_find`
--

INSERT INTO `blood_find` (`blood_id`, `student_id`, `location`, `blood_type`, `phone_number`, `last_donation_date`) VALUES
(1, 2, 'Mirpur', 'B+', '01760060543', 'Not Yet'),
(2, 2, 'Mirpur', 'B+', '01760060543', 'Not Yet');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `conditions` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `issold` enum('yes','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `student_id`, `title`, `author`, `price`, `conditions`, `image`, `file`, `status`, `created_at`, `image_path`, `file_path`, `issold`) VALUES
(12, 1, 'Islam', 'sumi', 1000.00, 'ok', NULL, NULL, 'approved', '2024-09-18 16:09:41', 'uploads/images/image.png', 'uploads/files/011212090_Ai_Lab_Mid_assignment .pdf', 'yes'),
(14, 1, 'Islam', 'sumi', 1000.00, 'ok', NULL, NULL, 'approved', '2024-09-18 16:39:43', 'uploads/images/image.png', 'uploads/files/011212090_Ai_Lab_Mid_assignment .pdf', 'yes'),
(15, 1, 'Islam', 'sumi', 1000.00, 'ok', NULL, NULL, 'approved', '2024-09-18 16:42:50', 'uploads/images/image.png', 'uploads/files/011212090_Ai_Lab_Mid_assignment .pdf', 'yes'),
(16, 1, 'sss', 'sssasa', 500.00, 'good', NULL, NULL, 'approved', '2024-09-20 03:54:46', 'uploads/images/image.png', 'uploads/files/011212090_Ai_Lab_Mid_assignment .pdf', 'yes'),
(17, 1, 'aa', 'bb', 80.00, 'better', NULL, NULL, 'approved', '2024-09-20 04:11:13', 'uploads/images/image.png', 'uploads/files/notesstrk.pdf', 'yes'),
(18, 1, 'aa', 'bb', 80.00, 'better', NULL, NULL, 'approved', '2024-09-20 05:25:13', 'uploads/images/image.png', 'uploads/files/notesstrk.pdf', 'yes'),
(20, 1, 'Islam', 'Sumi', 600.00, 'better', NULL, NULL, 'approved', '2024-09-21 09:32:09', 'uploads/images/motivation.jpeg', NULL, 'yes'),
(21, 1, 'baler boi', 'baler writer', 999.00, 'sera', NULL, NULL, 'approved', '2024-09-21 09:52:24', 'uploads/images/mirr.jpg', 'uploads/files/BDRAILWAY_TICKET202405141803465136.pdf', 'yes'),
(22, 1, 'shauya', 'jony', 50.00, 'gu', NULL, NULL, 'approved', '2024-09-21 09:55:43', 'uploads/images/foodibd_logo.jpeg', NULL, 'yes'),
(23, 3, 'dhon', 'bal', 10.00, 'kao', NULL, NULL, 'approved', '2024-09-21 10:01:18', 'uploads/images/image.png', 'uploads/files/assignment-1hci.pdf', 'yes'),
(24, 1, 'shauyaaaaaaaaa', 'jony', 50.00, 'gu', NULL, NULL, 'approved', '2024-09-21 10:02:36', 'uploads/images/WhatsApp Image 2024-09-16 at 23.20.20_e4e54b4e.jpg', NULL, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `book_accounts`
--

CREATE TABLE `book_accounts` (
  `account_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_accounts`
--

INSERT INTO `book_accounts` (`account_id`, `buyer_id`, `seller_id`, `book_id`, `transaction_id`, `purchase_date`, `amount`, `payment_status`) VALUES
(16, 3, 1, 12, NULL, '2024-09-18 16:10:12', 1000.00, 'completed'),
(17, 3, 1, 16, NULL, '2024-09-20 03:55:25', 500.00, 'completed'),
(19, 3, 1, 17, NULL, '2024-09-20 04:11:41', 80.00, 'completed'),
(20, 3, 1, 12, NULL, '2024-09-21 09:46:53', 1000.00, 'completed'),
(21, 3, 1, 14, NULL, '2024-09-21 09:47:13', 1000.00, 'completed'),
(22, 3, 1, 20, NULL, '2024-09-21 09:47:18', 600.00, 'completed'),
(24, 3, 1, 15, NULL, '2024-09-21 09:47:25', 1000.00, 'completed'),
(25, 3, 1, 18, NULL, '2024-09-21 09:47:29', 80.00, 'completed'),
(26, 3, 1, 21, NULL, '2024-09-21 09:53:18', 999.00, 'completed'),
(27, 3, 1, 22, NULL, '2024-09-21 09:56:06', 50.00, 'completed'),
(28, 3, 1, 24, NULL, '2024-09-21 10:03:15', 50.00, 'completed'),
(29, 1, 3, 23, NULL, '2024-09-27 04:01:54', 10.00, 'completed');

--
-- Triggers `book_accounts`
--
DELIMITER $$
CREATE TRIGGER `after_book_accounts_insert` AFTER INSERT ON `book_accounts` FOR EACH ROW BEGIN
    DECLARE book_title VARCHAR(255);
    
    -- Get the title of the book from the books table
    SELECT title INTO book_title
    FROM books
    WHERE book_id = NEW.book_id;
    
    -- Insert the data into the wallet table
    INSERT INTO wallet (student_id, balance, material, `from`, created_at)
    VALUES (
        NEW.seller_id,          -- student_id
        NEW.amount,             -- balance
        book_title,             -- material (book title)
        NEW.buyer_id,           -- from (buyer)
        NOW()                   -- created_at
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_book_accounts_insert_cost` AFTER INSERT ON `book_accounts` FOR EACH ROW BEGIN
    DECLARE book_title VARCHAR(255);
    
    -- Get the title of the book from the books table
    SELECT title INTO book_title
    FROM books
    WHERE book_id = NEW.book_id;
    
    -- Insert the data into the wallet2 table
    INSERT INTO wallet2 (student_id, balance, material, `from`, created_at)
    VALUES (
        NEW.buyer_id,          -- Using buyer_id as student_id
        NEW.amount,            -- balance
        book_title,            -- material (book title)
        NEW.seller_id,         -- from (seller)
        NOW()                  -- created_at
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `book_transactions`
--

CREATE TABLE `book_transactions` (
  `transaction_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('rent','sold','shared') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`chat_id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(1, 1, 2, 'hi', '2024-09-23 19:22:30'),
(2, 2, 1, 'hello', '2024-09-23 19:23:16'),
(3, 1, 2, 'ki koris?', '2024-09-23 19:23:29'),
(4, 1, 2, 'hey', '2024-09-23 19:26:45'),
(5, 2, 1, 'hello', '2024-09-23 19:27:01'),
(6, 2, 1, 'kisu na', '2024-09-23 19:29:22'),
(7, 1, 1, 'hello', '2024-09-25 09:52:03'),
(8, 4, 1, 'hello', '2024-09-25 09:52:57'),
(9, 1, 4, 'hi', '2024-09-25 09:53:08'),
(10, 1, 4, 'kemon acho?', '2024-09-25 09:53:22'),
(11, 4, 1, 'valo na', '2024-09-25 09:53:38'),
(12, 4, 1, 'hi', '2024-09-25 09:59:28'),
(13, 4, 1, 'hii', '2024-09-25 09:59:36'),
(14, 1, 1, 'hi', '2024-09-25 09:59:58'),
(15, 1, 4, 'hi', '2024-09-25 10:02:39'),
(16, 1, 4, 'hi', '2024-09-25 10:04:02'),
(17, 4, 1, 'hii', '2024-09-25 10:04:12'),
(18, 1, 4, 'ki holo?', '2024-09-25 10:05:10'),
(19, 4, 1, 'kichu na', '2024-09-25 10:09:32'),
(20, 1, 4, 'ok', '2024-09-25 10:09:38'),
(21, 4, 1, 'what ok?', '2024-09-25 10:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_name` enum('PowerPoint','Word','Excel','Web Frontend','Web Backend','Web Fullstack','Electronics Projects') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `instructor_id`, `course_name`, `created_at`) VALUES
(1, 14, 'Excel', '2024-09-26 06:16:06');

-- --------------------------------------------------------

--
-- Table structure for table `freelancing`
--

CREATE TABLE `freelancing` (
  `freelancing_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `skill_id` int(11) DEFAULT NULL,
  `skill_level` int(11) DEFAULT NULL CHECK (`skill_level` between 1 and 5),
  `cv_link` varchar(255) DEFAULT NULL,
  `freelance_opportunities` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `job_experience` text DEFAULT NULL,
  `available_courses` enum('PowerPoint','Word','Excel','Web Frontend','Web Backend','Web Fullstack','Electronics Projects') NOT NULL,
  `expected_money` decimal(10,2) NOT NULL,
  `class_hour` int(11) NOT NULL,
  `pdf_upload_path` varchar(255) DEFAULT NULL,
  `video_upload_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `student_id`, `full_name`, `job_experience`, `available_courses`, `expected_money`, `class_hour`, `pdf_upload_path`, `video_upload_path`, `created_at`, `status`) VALUES
(13, 1, 'Tashrif Rashid Sourav', '5', 'PowerPoint', 5000.00, 6, 'uploads/pdf/rijuda-somogro-1.pdf', 'uploads/video/chatting.mp4', '2024-09-21 08:48:58', 'approved'),
(14, 1, 'John Doe', '5 years of teaching', 'Excel', 500.00, 10, '/path/to/pdf', '/path/to/video', '2024-09-26 06:16:06', 'approved');

--
-- Triggers `instructors`
--
DELIMITER $$
CREATE TRIGGER `after_instructor_insert` AFTER INSERT ON `instructors` FOR EACH ROW BEGIN
    INSERT INTO courses (instructor_id, course_name)
    VALUES (NEW.instructor_id, NEW.available_courses);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_instructor_update` AFTER UPDATE ON `instructors` FOR EACH ROW BEGIN
    UPDATE courses
    SET course_name = NEW.available_courses
    WHERE instructor_id = NEW.instructor_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author_or_brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `conditions` varchar(50) DEFAULT NULL,
  `category` enum('Electronics','Study Materials','Computer Components') DEFAULT NULL,
  `pdf_link_or_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `issold` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `student_id`, `title`, `author_or_brand`, `price`, `conditions`, `category`, `pdf_link_or_image`, `status`, `created_at`, `issold`) VALUES
(5, 1, 'arduino', 'new', 600.00, 'ok', 'Electronics', 'uploads/images/image.png', 'approved', '2024-09-20 06:16:19', 'yes'),
(6, 1, 'arduino', 'new', 600.00, 'ok', 'Electronics', 'uploads/images/image.png', 'approved', '2024-09-20 06:19:12', 'yes'),
(7, 1, 'arduino', 'new', 600.00, 'ok', 'Electronics', 'uploads/images/image.png', 'approved', '2024-09-20 06:21:12', 'yes'),
(8, 1, 'arduino', 'new', 600.00, 'ok', 'Electronics', 'uploads/images/image.png', 'approved', '2024-09-20 08:02:15', 'yes'),
(9, 1, 'arduino', 'new', 600.00, 'ok', 'Electronics', 'uploads/images/image.png', 'approved', '2024-09-20 08:05:19', 'yes'),
(10, 1, 'arduino', 'new', 600.00, 'ok', 'Electronics', 'uploads/images/image.png', 'rejected', '2024-09-20 08:08:00', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `item_accounts`
--

CREATE TABLE `item_accounts` (
  `account_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_accounts`
--

INSERT INTO `item_accounts` (`account_id`, `buyer_id`, `seller_id`, `item_id`, `transaction_id`, `purchase_date`, `amount`, `payment_status`) VALUES
(4, 3, 1, 5, NULL, '2024-09-20 06:17:01', 600.00, 'completed'),
(5, 3, 1, 6, NULL, '2024-09-20 06:19:40', 600.00, 'completed'),
(6, 3, 1, 7, NULL, '2024-09-20 08:01:15', 600.00, 'completed'),
(7, 3, 1, 8, NULL, '2024-09-20 08:03:43', 600.00, 'completed'),
(8, 3, 1, 9, NULL, '2024-09-20 08:06:00', 600.00, 'completed'),
(9, 3, 1, 10, NULL, '2024-09-20 08:08:30', 600.00, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `item_transactions`
--

CREATE TABLE `item_transactions` (
  `transaction_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('sold','shared') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(14, 1, 2, 'hi', '2024-09-23 18:00:01'),
(15, 1, 1, 'hello', '2024-09-23 18:01:28'),
(16, 1, 1, 'ye', '2024-09-23 18:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `category` enum('educational','entertainment','professional') NOT NULL,
  `picture_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `student_id`, `content`, `post_date`, `status`, `category`, `picture_path`) VALUES
(1, 1, 'helloi', '2024-09-20 10:50:12', 'approved', 'educational', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE `ranking` (
  `ranking_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `rank_type` varchar(50) DEFAULT NULL,
  `rank_value` int(11) DEFAULT NULL CHECK (`rank_value` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_index`
--

CREATE TABLE `search_index` (
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_rank` decimal(3,2) DEFAULT NULL,
  `career_guidance` text DEFAULT NULL,
  `personalized_suggestions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `isadmin` enum('yes','no') DEFAULT 'no',
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `email`, `password`, `profile_rank`, `career_guidance`, `personalized_suggestions`, `created_at`, `isadmin`, `profile_picture`) VALUES
(1, 'Tashrif Rashid Sourav', 's@w', '$2y$10$Xv12mYMMi94IfppHudGNPe7ZP5VS8LKW6kaUHBuyJZDQomGkfPcIu', NULL, '', '', '2024-09-17 02:15:06', 'yes', 'uploads/profile_pictures/Screenshot 2024-09-24 094941.png'),
(2, 'Tashrif', 'd@d', '$2y$10$DoCEQLbg4efTw1OJKa2s1.vrSGinBYuS.No9iicF5pPG9mD1cr2mK', NULL, NULL, NULL, '2024-09-17 02:59:42', 'no', NULL),
(3, 'e', 'e@eu', '$2y$10$auzzxDAZBCdmNZDp3.LAs.Q5nNPlSc1VyYkVKAjHsJzaQf9czlYw.', NULL, '', '', '2024-09-18 15:37:41', 'no', NULL),
(4, 'trs', 'u@U', '$2y$10$FPHSpvpO.N7f0wJptQcDbut.lm.smevuYbev.YTrC7Xx/8eexUJs2', NULL, NULL, NULL, '2024-09-22 05:23:33', 'no', NULL),
(5, 'akash', 'a@k', '$2y$10$QDIf5.z0jTCfxwcw66DmJetSKvAqq3tB0noFEiW/Ch50v2kvfi4KK', NULL, NULL, NULL, '2024-09-27 04:06:31', 'no', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_skills`
--

CREATE TABLE `student_skills` (
  `skill_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `work_experience_years` int(11) DEFAULT NULL,
  `demo_project` varchar(255) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_skills`
--

INSERT INTO `student_skills` (`skill_id`, `student_id`, `skill_name`, `description`, `work_experience_years`, `demo_project`, `portfolio`) VALUES
(2, 1, 'Word', 'Skilled in document creation and editing.', 3, NULL, NULL),
(3, 1, 'Excel', 'Experienced in data analysis and spreadsheet management.', 1, NULL, NULL),
(4, 1, 'Web Development', 'Knowledgeable in HTML, CSS, and JavaScript.', 4, NULL, NULL),
(5, 1, 'Frontend', 'Expert in user interface development.', 2, NULL, NULL),
(6, 1, 'Fullstack', 'Proficient in both frontend and backend development.', 5, NULL, NULL),
(8, 1, 'Word', 'Skilled in document creation and editing.', 3, '', ''),
(9, 1, 'Excel', 'Experienced in data analysis and spreadsheet management.', 1, '', ''),
(10, 1, 'Web Development', 'Knowledgeable in HTML, CSS, and JavaScript.', 4, '', ''),
(11, 1, 'Frontend', 'Expert in user interface development.', 2, '', ''),
(12, 1, 'Fullstack', 'Proficient in both frontend and backend development.', 5, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tuitions`
--

CREATE TABLE `tuitions` (
  `tuition_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_level` enum('1','2','3','4','5','6','7','8','9','10','11','12') DEFAULT NULL,
  `subject` enum('Science','Math','English','Biology','Economics','Chemistry','Physics') DEFAULT NULL,
  `location` enum('Mirpur','Dhanmondi','Khilkhet','Banani','Uttara','Mohammadpur','Bashundhara','Gulshan') DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `preferred_time` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tuitions`
--

INSERT INTO `tuitions` (`tuition_id`, `student_id`, `class_level`, `subject`, `location`, `institution`, `phone_number`, `preferred_time`, `created_at`) VALUES
(1, 1, '12', 'Chemistry', 'Mirpur', 'Buet', '1235878', '6pm', '2024-09-17 11:58:07'),
(4, 1, '12', 'Chemistry', 'Mirpur', 'Buet', '1235878', '6pm', '2024-09-17 12:00:28'),
(5, 1, '12', 'Chemistry', 'Mirpur', 'Buet', '1235878', '6pm', '2024-09-17 12:00:50'),
(6, 1, '11', 'Biology', 'Mirpur', 'Buet', '856654', '7pm', '2024-09-17 12:22:40'),
(7, 1, '12', 'Science', 'Mirpur', 'DU', '0158989752', '6pm', '2024-09-22 04:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_suggestions`
--

CREATE TABLE `tuition_suggestions` (
  `suggestion_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `tuition_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `tutor_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_range_start` enum('1','2','3','4','5','6','7','8','9','10','11','12') DEFAULT NULL,
  `class_range_end` enum('1','2','3','4','5','6','7','8','9','10','11','12') DEFAULT NULL,
  `subject` enum('Science','Math','English','Biology','Economics','Chemistry','Physics') DEFAULT NULL,
  `location` enum('Mirpur','Dhanmondi','Khilkhet','Banani','Uttara','Mohammadpur','Bashundhara','Gulshan') DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`tutor_id`, `student_id`, `class_range_start`, `class_range_end`, `subject`, `location`, `phone_number`, `created_at`) VALUES
(2, 1, '11', '12', 'Physics', 'Mirpur', '852', '2024-09-17 12:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `wallet_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `buyer_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`wallet_id`, `student_id`, `balance`, `material`, `from`, `buyer_name`, `created_at`) VALUES
(1, 1, 600.00, 'arduino', 3, 'e', '2024-09-20 08:22:00'),
(2, 1, 600.00, 'arduino', 3, 'e', '2024-09-20 08:22:00'),
(3, 1, 600.00, 'arduino', 3, 'e', '2024-09-20 08:22:00'),
(4, 1, 600.00, 'arduino', 3, 'e', '2024-09-20 08:22:00'),
(5, 1, 600.00, 'arduino', 3, 'e', '2024-09-20 08:22:00'),
(6, 1, 600.00, 'arduino', 3, 'e', '2024-09-20 08:22:00'),
(7, 1, 1000.00, 'Islam', 3, 'e', '2024-09-20 08:22:00'),
(8, 1, 500.00, 'sss', 3, 'e', '2024-09-20 08:22:00'),
(9, 1, 80.00, 'aa', 3, 'e', '2024-09-20 08:22:00'),
(10, 1, 1000.00, 'Islam', 3, NULL, '2024-09-21 09:50:08'),
(11, 1, 500.00, 'sss', 3, NULL, '2024-09-21 09:50:08'),
(12, 1, 80.00, 'aa', 3, NULL, '2024-09-21 09:50:08'),
(13, 1, 1000.00, 'Islam', 3, NULL, '2024-09-21 09:50:08'),
(14, 1, 1000.00, 'Islam', 3, NULL, '2024-09-21 09:50:08'),
(15, 1, 600.00, 'Islam', 3, NULL, '2024-09-21 09:50:08'),
(16, 1, 600.00, 'Islam', 3, NULL, '2024-09-21 09:50:08'),
(17, 1, 1000.00, 'Islam', 3, NULL, '2024-09-21 09:50:08'),
(18, 1, 80.00, 'aa', 3, NULL, '2024-09-21 09:50:08'),
(25, 1, 50.00, 'shauyaaaaaaaaa', 3, NULL, '2024-09-21 10:03:15'),
(26, 3, 10.00, 'dhon', 1, NULL, '2024-09-27 04:01:54');

-- --------------------------------------------------------

--
-- Table structure for table `wallet2`
--

CREATE TABLE `wallet2` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `material` varchar(255) NOT NULL,
  `from` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet2`
--

INSERT INTO `wallet2` (`id`, `student_id`, `balance`, `material`, `from`, `created_at`) VALUES
(1, 3, 1000.00, 'Islam', 1, '2024-09-21 10:14:54'),
(2, 3, 500.00, 'sss', 1, '2024-09-21 10:14:54'),
(3, 3, 80.00, 'aa', 1, '2024-09-21 10:14:54'),
(4, 3, 1000.00, 'Islam', 1, '2024-09-21 10:14:54'),
(5, 3, 1000.00, 'Islam', 1, '2024-09-21 10:14:54'),
(6, 3, 600.00, 'Islam', 1, '2024-09-21 10:14:54'),
(7, 3, 600.00, 'Islam', 1, '2024-09-21 10:14:54'),
(8, 3, 1000.00, 'Islam', 1, '2024-09-21 10:14:54'),
(9, 3, 80.00, 'aa', 1, '2024-09-21 10:14:54'),
(10, 3, 999.00, 'baler boi', 1, '2024-09-21 10:14:54'),
(11, 3, 50.00, 'shauya', 1, '2024-09-21 10:14:54'),
(12, 3, 50.00, 'shauyaaaaaaaaa', 1, '2024-09-21 10:14:54'),
(13, 1, 10.00, 'dhon', 3, '2024-09-27 04:01:54');

-- --------------------------------------------------------

--
-- Table structure for table `works`
--

CREATE TABLE `works` (
  `work_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `skill_requirement` enum('PowerPoint','Word','Excel','Web Development','Frontend','Fullstack') NOT NULL,
  `experience_requirement_year` int(11) NOT NULL,
  `details` text NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `works`
--

INSERT INTO `works` (`work_id`, `student_id`, `skill_requirement`, `experience_requirement_year`, `details`, `salary`, `created_at`) VALUES
(3, 4, 'PowerPoint', 2, 'good', 500.00, '2024-09-25 11:29:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `blood_donation`
--
ALTER TABLE `blood_donation`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `blood_find`
--
ALTER TABLE `blood_find`
  ADD PRIMARY KEY (`blood_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `book_accounts`
--
ALTER TABLE `book_accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `book_accounts_ibfk_3` (`book_id`);

--
-- Indexes for table `book_transactions`
--
ALTER TABLE `book_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `freelancing`
--
ALTER TABLE `freelancing`
  ADD PRIMARY KEY (`freelancing_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`instructor_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `item_accounts`
--
ALTER TABLE `item_accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `item_accounts_ibfk_3` (`item_id`);

--
-- Indexes for table `item_transactions`
--
ALTER TABLE `item_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`ranking_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `search_index`
--
ALTER TABLE `search_index`
  ADD KEY `student_id` (`student_id`);
ALTER TABLE `search_index` ADD FULLTEXT KEY `name` (`name`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD UNIQUE KEY `skill_id` (`skill_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_skills`
--
ALTER TABLE `student_skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tuitions`
--
ALTER TABLE `tuitions`
  ADD PRIMARY KEY (`tuition_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tuition_suggestions`
--
ALTER TABLE `tuition_suggestions`
  ADD PRIMARY KEY (`suggestion_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`tutor_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`wallet_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `from` (`from`);

--
-- Indexes for table `wallet2`
--
ALTER TABLE `wallet2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`work_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=787;

--
-- AUTO_INCREMENT for table `blood_donation`
--
ALTER TABLE `blood_donation`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blood_find`
--
ALTER TABLE `blood_find`
  MODIFY `blood_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `book_accounts`
--
ALTER TABLE `book_accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `book_transactions`
--
ALTER TABLE `book_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `freelancing`
--
ALTER TABLE `freelancing`
  MODIFY `freelancing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item_accounts`
--
ALTER TABLE `item_accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `item_transactions`
--
ALTER TABLE `item_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ranking`
--
ALTER TABLE `ranking`
  MODIFY `ranking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_skills`
--
ALTER TABLE `student_skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tuitions`
--
ALTER TABLE `tuitions`
  MODIFY `tuition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tuition_suggestions`
--
ALTER TABLE `tuition_suggestions`
  MODIFY `suggestion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `tutor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `wallet2`
--
ALTER TABLE `wallet2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `works`
--
ALTER TABLE `works`
  MODIFY `work_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_donation`
--
ALTER TABLE `blood_donation`
  ADD CONSTRAINT `blood_donation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `blood_find`
--
ALTER TABLE `blood_find`
  ADD CONSTRAINT `blood_find_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `book_accounts`
--
ALTER TABLE `book_accounts`
  ADD CONSTRAINT `book_accounts_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `book_accounts_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `book_accounts_ibfk_3` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_accounts_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `book_transactions` (`transaction_id`);

--
-- Constraints for table `book_transactions`
--
ALTER TABLE `book_transactions`
  ADD CONSTRAINT `book_transactions_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `book_transactions_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `freelancing`
--
ALTER TABLE `freelancing`
  ADD CONSTRAINT `freelancing_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `freelancing_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`);

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `item_accounts`
--
ALTER TABLE `item_accounts`
  ADD CONSTRAINT `item_accounts_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `item_accounts_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `item_accounts_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_accounts_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `item_transactions` (`transaction_id`);

--
-- Constraints for table `item_transactions`
--
ALTER TABLE `item_transactions`
  ADD CONSTRAINT `item_transactions_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `item_transactions_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `ranking_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `search_index`
--
ALTER TABLE `search_index`
  ADD CONSTRAINT `search_index_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `student_skills`
--
ALTER TABLE `student_skills`
  ADD CONSTRAINT `student_skills_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `tuitions`
--
ALTER TABLE `tuitions`
  ADD CONSTRAINT `tuitions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `tuition_suggestions`
--
ALTER TABLE `tuition_suggestions`
  ADD CONSTRAINT `tuition_suggestions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutors_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `wallet_ibfk_2` FOREIGN KEY (`from`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `works_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
