-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 02:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swimming_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Tên thương hiệu',
  `logo` varchar(255) DEFAULT NULL COMMENT 'Link ảnh logo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `created_at`) VALUES
(1, 'Speedo', 'speedo.png', '2025-12-09 00:54:48'),
(2, 'Arena', 'arena.png', '2025-12-09 00:54:48'),
(3, 'Phoenix', 'phoenix.png', '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Tên danh mục',
  `description` text DEFAULT NULL COMMENT 'Mô tả danh mục',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Kính Bơi', 'Kính bơi cận, kính bơi thi đấu, kính trẻ em', '2025-12-09 00:54:48'),
(2, 'Đồ Bơi Nam', 'Quần bơi jammer, quần bơi boxer', '2025-12-09 00:54:48'),
(3, 'Đồ Bơi Nữ', 'Bikini, đồ bơi liền thân', '2025-12-09 00:54:48'),
(4, 'Phụ Kiện Bơi', 'Mũ bơi, bịt tai, kẹp mũi, phao bơi', '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Mã nhập (VD: SUMMER2025)',
  `discount_type` enum('percent','fixed') NOT NULL COMMENT 'Giảm theo % hoặc tiền',
  `discount_value` decimal(10,0) NOT NULL COMMENT 'Giá trị giảm',
  `min_order_value` decimal(10,0) DEFAULT 0 COMMENT 'Đơn tối thiểu',
  `quantity` int(11) DEFAULT 0 COMMENT 'Số lượng mã phát hành',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Đang chạy, 0: Hết hạn/Tắt',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `min_order_value`, `quantity`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'CHAOHE2025', 'percent', 10, 200000, 100, '2025-05-01 00:00:00', '2025-08-31 00:00:00', 1, '2025-12-09 00:54:48'),
(2, 'FREESHIP', 'fixed', 30000, 500000, 50, '2025-01-01 00:00:00', '2025-12-31 00:00:00', 1, '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'ID khách hàng (NULL nếu là khách vãng lai)',
  `order_code` varchar(20) NOT NULL COMMENT 'Mã đơn hàng để tra cứu',
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` enum('COD','VNPAY','MOMO','BANKING') DEFAULT 'COD',
  `payment_status` tinyint(1) DEFAULT 0 COMMENT '0: Chưa TT, 1: Đã TT',
  `shipping_fee` decimal(10,0) DEFAULT 0 COMMENT 'Phí vận chuyển',
  `discount_amount` decimal(10,0) DEFAULT 0 COMMENT 'Số tiền được giảm',
  `total_money` decimal(12,0) NOT NULL COMMENT 'Tổng tiền phải trả',
  `status` enum('pending','processing','shipping','completed','cancelled') DEFAULT 'pending' COMMENT 'Pending: Mới, Processing: Đang chuẩn bị, Shipping: Đang giao, Completed: Hoàn thành, Cancelled: Hủy',
  `note` text DEFAULT NULL COMMENT 'Ghi chú của khách',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `customer_name`, `customer_phone`, `customer_email`, `shipping_address`, `payment_method`, `payment_status`, `shipping_fee`, `discount_amount`, `total_money`, `status`, `note`, `created_at`) VALUES
(1, 3, 'DH001', 'Nguyễn Văn Khách', '0901234569', NULL, '123 Cầu Giấy, Hà Nội', 'COD', 0, 0, 0, 620000, 'pending', NULL, '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL COMMENT 'Mua biến thể nào (Size/Màu)',
  `product_name` varchar(255) NOT NULL COMMENT 'Lưu tên SP tại thời điểm mua',
  `size` varchar(20) NOT NULL,
  `color` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL COMMENT 'Số lượng mua',
  `price` decimal(10,0) NOT NULL COMMENT 'Giá bán tại thời điểm mua',
  `total_price` decimal(12,0) NOT NULL COMMENT 'Thành tiền (price * quantity)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_variant_id`, `product_name`, `size`, `color`, `quantity`, `price`, `total_price`) VALUES
(1, 1, 1, 'Kính bơi Speedo Biofuse 2.0', 'FreeSize', 'Xanh Dương', 1, 590000, 590000);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Người viết bài',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên sản phẩm',
  `sku_code` varchar(50) DEFAULT NULL COMMENT 'Mã sản phẩm chung',
  `price` decimal(10,0) NOT NULL COMMENT 'Giá gốc',
  `sale_price` decimal(10,0) DEFAULT 0 COMMENT 'Giá khuyến mãi',
  `image` varchar(255) DEFAULT NULL COMMENT 'Ảnh đại diện chính',
  `description` longtext DEFAULT NULL COMMENT 'Mô tả chi tiết (HTML)',
  `view_count` int(11) DEFAULT 0 COMMENT 'Lượt xem',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Đang bán, 0: Ngừng bán',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `sku_code`, `price`, `sale_price`, `image`, `description`, `view_count`, `is_active`, `created_at`) VALUES
(1, 1, 1, 'Kính bơi Speedo Biofuse 2.0', 'KB-SP01', 650000, 590000, 'kinh_speedo.jpg', '<p>Công nghệ Biofuse bán chạy nhất...</p>', 0, 1, '2025-12-09 00:54:48'),
(2, 2, 2, 'Quần bơi nam Arena Tập Luyện', 'QB-AR01', 900000, 0, 'quan_arena.jpg', '<p>Chất liệu vải MaxLife bền bỉ...</p>', 0, 1, '2025-12-09 00:54:48'),
(3, 1, 3, 'Kính bơi cận Phoenix 203', 'KB-PN03', 250000, 220000, 'kinh_can_phoenix.jpg', '<p>Dành cho người cận thị từ 2-6 diop...</p>', 0, 1, '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) NOT NULL COMMENT 'S, M, L, XL, FreeSize',
  `color` varchar(50) NOT NULL COMMENT 'Xanh, Đỏ, Đen...',
  `stock_quantity` int(11) DEFAULT 0 COMMENT 'Số lượng tồn kho thực tế',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `color`, `stock_quantity`, `created_at`) VALUES
(1, 1, 'FreeSize', 'Xanh Dương', 50, '2025-12-09 00:54:48'),
(2, 1, 'FreeSize', 'Đen', 30, '2025-12-09 00:54:48'),
(3, 2, 'M', 'Đen', 10, '2025-12-09 00:54:48'),
(4, 2, 'L', 'Đen', 15, '2025-12-09 00:54:48'),
(5, 2, 'XL', 'Xanh Navy', 5, '2025-12-09 00:54:48'),
(6, 3, 'FreeSize', 'Đen', 20, '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5 COMMENT 'Số sao 1-5',
  `comment` text DEFAULT NULL COMMENT 'Nội dung đánh giá',
  `reply_content` text DEFAULT NULL COMMENT 'Admin trả lời',
  `status` tinyint(1) DEFAULT 0 COMMENT '0: Chờ duyệt (Ẩn), 1: Đã duyệt (Hiện)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `reply_content`, `status`, `created_at`) VALUES
(1, 3, 1, 5, 'Kính đeo rất êm, góc nhìn rộng, giao hàng nhanh!', NULL, 1, '2025-12-09 00:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL COMMENT 'Link khi click vào banner',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL COMMENT 'Họ tên',
  `email` varchar(100) NOT NULL COMMENT 'Email đăng nhập',
  `password` varchar(255) NOT NULL COMMENT 'Mật khẩu đã mã hóa',
  `phone_number` varchar(15) DEFAULT NULL COMMENT 'Số điện thoại',
  `address` text DEFAULT NULL COMMENT 'Địa chỉ mặc định',
  `role` enum('admin','staff','member') DEFAULT 'member' COMMENT 'Phân quyền: Admin tối cao, Nhân viên, Thành viên',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Hoạt động, 0: Bị khóa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone_number`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Quản Trị Viên', 'admin@swimming.com', '$2y$10$HashedPasswordHere', '0901234567', NULL, 'admin', 1, '2025-12-09 00:54:48', '2025-12-09 00:54:48'),
(2, 'Nhân Viên Bán Hàng', 'staff@swimming.com', '$2y$10$HashedPasswordHere', '0901234568', NULL, 'staff', 1, '2025-12-09 00:54:48', '2025-12-09 00:54:48'),
(3, 'Nguyễn Văn Khách', 'khachhang@gmail.com', '$2y$10$HashedPasswordHere', '0901234569', NULL, 'member', 1, '2025-12-09 00:54:48', '2025-12-09 00:54:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku_code` (`sku_code`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
