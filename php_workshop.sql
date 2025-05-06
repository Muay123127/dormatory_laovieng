-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 03:54 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_workshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `Name`, `phone`) VALUES
('001', 'ບ່າຍ', '13:30-16:20'),
('002', 'ເຊົ້າ', '8:30-11:30'),
('003', 'ຄໍ່າ', '17:30-20:20');

-- --------------------------------------------------------

--
-- Table structure for table `dept`
--

CREATE TABLE `dept` (
  `dno` varchar(6) NOT NULL,
  `name` varchar(25) NOT NULL,
  `loc` varchar(255) NOT NULL,
  `incentive` decimal(9,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dept`
--

INSERT INTO `dept` (`dno`, `name`, `loc`, `incentive`) VALUES
('001', 'ບໍລິຫານ', 'Laovieng', '2000000'),
('002', 'ອານາໄມ', 'Laovieng', '2000000'),
('003', 'ວິຊາການ', 'Laovieng', '2000000'),
('004', 'ເລຂາ', 'ວິທະຍາໄລ ລາວວຽງ', '200000');

-- --------------------------------------------------------

--
-- Table structure for table `emp`
--

CREATE TABLE `emp` (
  `empno` varchar(6) NOT NULL,
  `name` varchar(25) NOT NULL,
  `gender` char(1) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `incentive` decimal(9,0) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `picture` varchar(255) NOT NULL,
  `grade` varchar(3) DEFAULT NULL,
  `dno` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp`
--

INSERT INTO `emp` (`empno`, `name`, `gender`, `dateOfBirth`, `address`, `incentive`, `language`, `picture`, `grade`, `dno`) VALUES
('EMP001', 'ລັດ', 'ຍ', '2023-12-07', '', '200000', 'ອັງກິດ,ຈີນ', '1701954708312707604_5375769625885577_3812188698591333003_n.jpg', 'T02', '002'),
('EMP002', 'ສິດ', 'ຊ', '2024-01-08', 'ພະຂາວ', '300000', 'ອັງກິດ,ຈີນ', '1705921766312707604_5375769625885577_3812188698591333003_n.jpg', 'T01', '001'),
('EMP003', 'ໝວຍ', 'ຍ', '2001-03-02', 'hhh', '200000', 'ອັງກິດ,ຈີນ,ອື່ນໆ...', '1711367302312707604_5375769625885577_3812188698591333003_n.jpg', 'T02', '003');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `Room_ID` char(11) NOT NULL,
  `R_number` char(5) NOT NULL,
  `Build` int(11) NOT NULL,
  `Meter_electrict` int(11) DEFAULT NULL,
  `Price` decimal(12,0) NOT NULL,
  `Persons` char(5) NOT NULL,
  `Statuss` varchar(15) NOT NULL,
  `TotalCapacity` char(5) NOT NULL,
  `RoomType` varchar(10) NOT NULL,
  `booked` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`Room_ID`, `R_number`, `Build`, `Meter_electrict`, `Price`, `Persons`, `Statuss`, `TotalCapacity`, `RoomType`, `booked`) VALUES
('001', '1', 1, 1234, '700000', '0', 'ວ່າງ', '5', 'ຍ', '0');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE `salary` (
  `grade` varchar(3) NOT NULL,
  `sal` decimal(9,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary`
--

INSERT INTO `salary` (`grade`, `sal`) VALUES
('T01', '2300000'),
('T02', '2500000');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `Stu_ID` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Stu_name` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_birth` date NOT NULL,
  `S_id` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cid` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Sets` int(11) NOT NULL,
  `Gen` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Parent` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Parent_Tell` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tell` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`Stu_ID`, `Stu_name`, `gender`, `date_birth`, `S_id`, `cid`, `Sets`, `Gen`, `address`, `Parent`, `Parent_Tell`, `status`, `tell`) VALUES
('STU0002', 'ປີຍະພົງ ວົງດາຮັກ', 'ຊ', '2002-03-09', '001', '002', 1, 'G4', 'ບ ຫ້ວຍຫົງ ຈັນທະບູລີ ນະຄອນຫຼວງວຽງຈັນ', 'ນາງ ສົດໃສ', '020 12345678', 'ຍັງບໍ່ເຂົ້າພັກ', '020 12345678'),
('STU0003', 'ປີຍະພົງ ວົງດາຮັກ', 'ຊ', '2002-05-02', '001', '003', 1, 'G4', 'ພພພພ', 'ນາງ ສົດໃສ', '020 12345678', 'ຍັງບໍ່ເຂົ້າພັກ', '020 12345678');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `S_id` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addres` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`S_id`, `name`, `addres`) VALUES
('001', 'ໄອທີ', 'IT'),
('002', 'ພາສາອັງກິດ', 'English');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `tel`, `username`, `password`) VALUES
(1, 'ສຸກສະຫວັນ', '96887222', 'souk@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(6, 'Muay', '97187354', 'Muay@gmail.com', 'd9cae81a2088c17f5e911498b7ddbf98'),
(7, 'Muay', '97187354', 'Mil@gmail.com', '202cb962ac59075b964b07152d234b70'),
(8, 'ໝວຍ', '97187354', 'M@gmail.com', '202cb962ac59075b964b07152d234b70'),
(9, 'Muay', '97187354', 'Phetsamai@1234', '81dc9bdb52d04dc20036dbd8313ed055'),
(10, 'ໝວຍ ສິງຂາວເພັດ', '020 97187354', 'phet@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`dno`);

--
-- Indexes for table `emp`
--
ALTER TABLE `emp`
  ADD PRIMARY KEY (`empno`),
  ADD KEY `dno` (`dno`),
  ADD KEY `grade` (`grade`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`Room_ID`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`grade`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`Stu_ID`),
  ADD KEY `std_ibfk_1` (`S_id`),
  ADD KEY `std_ibfk_2` (`cid`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`S_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `emp`
--
ALTER TABLE `emp`
  ADD CONSTRAINT `emp_ibfk_1` FOREIGN KEY (`dno`) REFERENCES `dept` (`dno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `emp_ibfk_2` FOREIGN KEY (`grade`) REFERENCES `salary` (`grade`) ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `std_ibfk_1` FOREIGN KEY (`S_id`) REFERENCES `supplier` (`S_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `std_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
