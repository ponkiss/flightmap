
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `flights` (
  `id` int(11) NOT NULL,
  `flight_number` varchar(10) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_reserved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `flights` (`id`, `flight_number`, `origin`, `destination`, `departure_time`, `arrival_time`, `price`, `is_reserved`) VALUES
(1, '1', 'Ciudad Juarez', 'Cartagena', '2024-09-20 19:20:00', '2024-09-20 08:20:00', 3500.00, 0),
(5, '4', 'Manitoba', 'Paris', '2024-10-17 06:59:00', '2024-10-11 08:56:00', 3000.00, 1),
(10, '10', 'Pekin', 'Singapur', '2024-09-21 16:45:00', '2024-09-21 16:45:00', 1000.00, 0),
(11, '42', 'Chile', 'Afghanistan', '2024-09-21 17:01:00', '2025-03-12 05:01:00', 10.00, 0);

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `reservations` (`id`, `user_id`, `flight_id`, `reservation_date`) VALUES
(20, 2, 1, '2024-09-21 23:20:03');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$oBvxq7//liMWK6TlhsXqVOAgglel1KlLiJRlQSc3f4T4LRbPUICte', 'admin@gmail.com', 'admin'),
(2, 'user', '$2y$10$H6baoWq64ZoxZC1GKowH.u8MK2NGnuxyvTfJC9lwEah5qk/loxrGm', 'user@gmail.com', 'user'),
(3, 'zamora', '$2y$10$6ALeQ.bzxsd26RuiXXq14ufVIJE2YoTaoi9R8gHphi0DHnPHBY5Mi', 'zamora@gmail.com', 'user'),
(4, 'karin', '$2y$10$mH38zJE7QMOdny3103hMAeLTGCcdHsZcj.etzJGLTKFduIbjjMVWm', 'karin@gmail.com', 'user'),
(5, 'caleb', '$2y$10$/cKGl8Lp381P4BQZX3oUlu0kVOf57oZoqzCcMl9XC8Aclrvi33hSm', 'caleb@gmail.com', 'user'),
(6, 'fer', '$2y$10$Y2uIzCti19nzLiDxbS2y9urOfwbxH5xczqXMaVBXqy.SY4WLiEWam', 'fer@gmail.com', 'user'),
(7, 'user1', '$2y$10$SWyWMC206fha06x8KNS1DexWR/e/nr955NEpffkzNaUjSsY0DunFG', 'user1@gmail.com', 'user'),
(8, 'user2', '$2y$10$MyN2DnPLwzplqgxUQZYxJ.vtecnALtieoQoDEID6zZBuVaS8P0Vw2', 'user2@gmail.com', 'user');

ALTER TABLE `flights`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `flight_id` (`flight_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id`);

COMMIT;
