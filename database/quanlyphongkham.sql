-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 29, 2026 lúc 08:05 PM
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
-- Cơ sở dữ liệu: `quanlyphongkham`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benhan`
--

CREATE TABLE `benhan` (
  `MaBenhAn` int(11) NOT NULL,
  `MaBenhAnCode` varchar(20) NOT NULL,
  `MaBenhNhan` int(11) NOT NULL,
  `NgayMo` date NOT NULL,
  `NgayDong` date DEFAULT NULL,
  `ChanDoanNoiTru` text DEFAULT NULL,
  `TinhTrang` varchar(50) NOT NULL DEFAULT 'MO',
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `benhan`
--

INSERT INTO `benhan` (`MaBenhAn`, `MaBenhAnCode`, `MaBenhNhan`, `NgayMo`, `NgayDong`, `ChanDoanNoiTru`, `TinhTrang`, `GhiChu`) VALUES
(17001, 'BA00001', 121, '2026-04-15', NULL, 'Viêm loét dạ dày tá tràng mạn tính – đang điều trị ngoại trú', 'DANG_DIEU_TRI', 'Theo dõi định kỳ 3 tháng/lần'),
(17002, 'BA00002', 2, '2026-04-16', '2026-05-11', 'Hậu phẫu ruột thừa cấp – đã hồi phục hoàn toàn', 'RA_VIEN', 'Đã rút chỉ, cho xuất viện'),
(17003, 'BA00003', 9, '2026-04-25', NULL, 'Tăng huyết áp độ 2 – nghi thiếu máu cơ tim cục bộ, đang theo dõi', 'DANG_DIEU_TRI', 'Khám tim mạch định kỳ 1 tháng/lần'),
(17004, 'BA00004', 9, '2026-05-09', NULL, 'Tăng huyết áp độ 2 – điều trị nội khoa', 'DANG_DIEU_TRI', 'Uống thuốc đều đặn, đo huyết áp hàng ngày'),
(17005, 'BA00005', 4, '2026-04-18', NULL, 'Thai kỳ lần 2 – 20 tuần, theo dõi thai kỳ định kỳ', 'DANG_DIEU_TRI', 'Tái khám định kỳ 4 tuần/lần'),
(17006, 'BA00006', 10, '2026-04-26', NULL, 'Migraine không có aura – điều trị nội khoa', 'DANG_DIEU_TRI', 'Điều trị phòng ngừa và cắt cơn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benhan_phieukham`
--

CREATE TABLE `benhan_phieukham` (
  `MaBenhAn` int(11) NOT NULL,
  `MaPhieuKham` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `benhan_phieukham`
--

INSERT INTO `benhan_phieukham` (`MaBenhAn`, `MaPhieuKham`) VALUES
(17001, 1),
(17001, 21),
(17001, 41),
(17002, 2),
(17002, 22),
(17002, 42),
(17003, 9),
(17003, 29),
(17004, 19),
(17005, 4),
(17005, 24),
(17005, 34),
(17006, 10),
(17006, 20),
(17006, 30);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benhnhan`
--

CREATE TABLE `benhnhan` (
  `MaBenhNhan` int(11) NOT NULL,
  `MaTaiKhoan` int(11) DEFAULT NULL,
  `MaBN` varchar(20) NOT NULL,
  `CCCD` varchar(20) DEFAULT NULL,
  `HoTen` varchar(100) NOT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` char(1) DEFAULT NULL CHECK (`GioiTinh` in ('M','F','O')),
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DiaChi` varchar(500) DEFAULT NULL,
  `SoBHYT` varchar(30) DEFAULT NULL,
  `NhomMau` varchar(5) DEFAULT NULL,
  `DiUng` text DEFAULT NULL,
  `TrangThai` tinyint(4) NOT NULL DEFAULT 0,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `benhnhan`
--

INSERT INTO `benhnhan` (`MaBenhNhan`, `MaTaiKhoan`, `MaBN`, `CCCD`, `HoTen`, `NgaySinh`, `GioiTinh`, `SoDienThoai`, `Email`, `DiaChi`, `SoBHYT`, `NhomMau`, `DiUng`, `TrangThai`, `NgayTao`, `NgayCapNhat`) VALUES
(1, NULL, 'BN00001', NULL, 'Nguyễn Văn Hải', '1985-04-12', 'M', '0901234561', 'hai.nv@gmail.com', '12 Lê Lợi, Quy Nhơn', 'GD4790123456001', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(2, NULL, 'BN00002', NULL, 'Trần Thị Mai', '1992-08-23', 'F', '0912345672', 'mai.tt@gmail.com', '45 Nguyễn Huệ, Quy Nhơn', 'HT4790123456002', 'A+', 'Hải sản', 0, '2026-05-28 00:00:00', NULL),
(3, NULL, 'BN00003', NULL, 'Lê Hoàng Long', '1978-11-05', 'M', '0923456783', 'long.lh@gmail.com', '78 Trần Hưng Đạo, Quy Nhơn', 'DN4790123456003', 'B+', 'Penicillin', 0, '2026-05-28 00:00:00', NULL),
(4, NULL, 'BN00004', NULL, 'Phạm Thanh Thảo', '2000-01-15', 'F', '0934567894', 'thao.pt@gmail.com', '102 Tăng Bạt Hổ, Quy Nhơn', 'CH4790123456004', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(5, NULL, 'BN00005', NULL, 'Hoàng Minh Tuấn', '1965-06-30', 'M', '0945678905', 'tuan.hm@gmail.com', '15 Xuân Diệu, Quy Nhơn', 'TE4790123456005', 'O-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(6, NULL, 'BN00006', NULL, 'Vũ Thị Hương', '1989-09-18', 'F', '0956789016', 'huong.vt@gmail.com', '88 Tây Sơn, Quy Nhơn', 'GD4790123456006', 'A-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(7, NULL, 'BN00007', NULL, 'Phan Văn Đức', '1995-03-25', 'M', '0967890127', 'duc.pv@gmail.com', '34 An Dương Vương, Quy Nhơn', 'HT4790123456007', 'O+', 'Bụi nhà', 0, '2026-05-28 00:00:00', NULL),
(8, NULL, 'BN00008', NULL, 'Đỗ Thúy Hằng', '2002-07-14', 'F', '0978901238', 'hang.dt@gmail.com', '56 Hùng Vương, Quy Nhơn', 'DN4790123456008', 'B-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(9, NULL, 'BN00009', NULL, 'Bùi Anh Tú', '1982-12-01', 'M', '0989012349', 'tu.ba@gmail.com', '21 Ngô Mây, Quy Nhơn', 'CH4790123456009', 'AB-', 'Phấn hoa', 0, '2026-05-28 00:00:00', NULL),
(10, NULL, 'BN00010', NULL, 'Đặng Ngọc Ánh', '1997-05-20', 'F', '0990123450', 'anh.dn@gmail.com', '67 Nguyễn Thái Học, Quy Nhơn', 'TE4790123456010', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(11, NULL, 'BN00011', NULL, 'Nguyễn Hoàng Giang', '1973-02-28', 'M', '0321234561', 'giang.nh@gmail.com', '143 Hoàng Văn Thụ, Quy Nhơn', 'GD4790123456011', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(12, NULL, 'BN00012', NULL, 'Trịnh Bích Phương', '1994-10-10', 'F', '0332345672', 'phuong.tb@gmail.com', '19 Nguyễn Lữ, Quy Nhơn', 'HT4790123456012', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(13, NULL, 'BN00013', NULL, 'Võ Minh Quang', '1988-04-05', 'M', '0343456783', 'quang.vm@gmail.com', '52 Đống Đa, Quy Nhơn', 'DN4790123456013', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(14, NULL, 'BN00014', NULL, 'Lý Thu Thủy', '1991-06-17', 'F', '0354567894', 'thuy.lt@gmail.com', '83 Bạch Đằng, Quy Nhơn', 'CH4790123456014', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(15, NULL, 'BN00015', NULL, 'Trần Đình Phong', '1980-09-22', 'M', '0365678905', 'phong.td@gmail.com', '99 Lê Hồng Phong, Quy Nhơn', 'TE4790123456015', 'O+', 'Aspirin', 0, '2026-05-28 00:00:00', NULL),
(16, NULL, 'BN00016', NULL, 'Nguyễn Thị Dung', '1968-11-30', 'F', '0376789016', 'dung.nt@gmail.com', '120 Trần Cao Vân, Quy Nhơn', 'GD4790123456016', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(17, NULL, 'BN00017', NULL, 'Lê Khánh Duy', '2005-08-14', 'M', '0387890127', 'duy.lk@gmail.com', '14 Vũ Bảo, Quy Nhơn', 'HT4790123456017', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(18, NULL, 'BN00018', NULL, 'Phạm Minh Châu', '1999-03-09', 'F', '0398901238', 'chau.pm@gmail.com', '75 Diên Hồng, Quy Nhơn', 'DN4790123456018', 'O-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(19, NULL, 'BN00019', NULL, 'Tạ Văn Nam', '1984-07-25', 'M', '0701112223', 'nam.tv@gmail.com', '36 Lý Thường Kiệt, Quy Nhơn', 'CH4790123456019', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(20, NULL, 'BN00020', NULL, 'Đỗ Hồng Nhung', '1993-01-01', 'F', '0762223334', 'nhung.dh@gmail.com', '118 Lê Duẩn, Quy Nhơn', 'TE4790123456020', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(21, NULL, 'BN00021', NULL, 'Nguyễn Quốc Bảo', '1976-05-19', 'M', '0773334445', 'bao.nq@gmail.com', '205 Nguyễn Thị Định, Quy Nhơn', 'GD4790123456021', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(22, NULL, 'BN00022', NULL, 'Trần Thu Hà', '1987-10-15', 'F', '0784445556', 'ha.tt@gmail.com', '42 Đô Đốc Bảo, Quy Nhơn', 'HT4790123456022', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(23, NULL, 'BN00023', NULL, 'Dương Công Vinh', '1990-12-12', 'M', '0795556667', 'vinh.dc@gmail.com', '89 Biên Cương, Quy Nhơn', 'DN4790123456023', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(24, NULL, 'BN00024', NULL, 'Lê Thị Diệu', '1996-02-28', 'F', '0816667778', 'dieu.lt@gmail.com', '134 Chương Dương, Quy Nhơn', 'CH4790123456024', 'AB-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(25, NULL, 'BN00025', NULL, 'Phạm Hồng Sơn', '1981-08-08', 'M', '0827778889', 'son.ph@gmail.com', '16 Tố Hữu, Quy Nhơn', 'TE4790123456025', 'O+', 'Thuốc nhuộm tóc', 0, '2026-05-28 00:00:00', NULL),
(26, NULL, 'BN00026', NULL, 'Nguyễn Thanh Vân', '2001-04-30', 'F', '0838889990', 'van.nt@gmail.com', '49 Phạm Hùng, Quy Nhơn', 'GD4790123456026', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(27, NULL, 'BN00027', NULL, 'Trần Văn Hoàng', '1970-07-21', 'M', '0849990001', 'hoang.tv@gmail.com', '72 Nhơn Lý, Quy Nhơn', 'HT4790123456027', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(28, NULL, 'BN00028', NULL, 'Bùi Mỹ Linh', '1995-11-11', 'F', '0851234567', 'linh.bm@gmail.com', '10 Nhơn Hải, Quy Nhơn', 'DN4790123456028', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(29, NULL, 'BN00029', NULL, 'Vũ Tiến Đạt', '1986-03-03', 'M', '0862345678', 'dat.vt@gmail.com', '55 Trần Quang Diệu, Quy Nhơn', 'CH4790123456029', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(30, NULL, 'BN00030', NULL, 'Hoàng Kim Ngân', '1998-09-05', 'F', '0883456789', 'ngan.hk@gmail.com', '12 Phú Tài, Quy Nhơn', 'TE4790123456030', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(31, NULL, 'BN00031', NULL, 'Nguyễn Minh Triết', '1993-02-14', 'M', '0894567890', 'triet.nm@gmail.com', '27 Bùi Thị Xuân, Quy Nhơn', 'GD4790123456031', 'A-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(32, NULL, 'BN00032', NULL, 'Phan Thị Tuyết', '1962-05-24', 'F', '0905678901', 'tuyet.pt@gmail.com', '64 Phước Mỹ, Quy Nhơn', 'HT4790123456032', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(33, NULL, 'BN00033', NULL, 'Lê Nhật Huy', '2004-12-25', 'M', '0916789012', 'huy.ln@gmail.com', '91 Đống Đa, Quy Nhơn', 'DN4790123456033', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(34, NULL, 'BN00034', NULL, 'Nguyễn Diệu Hiền', '1991-07-07', 'F', '0927890123', 'hien.nd@gmail.com', '140 Hàm Nghi, Quy Nhơn', 'CH4790123456034', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(35, NULL, 'BN00035', NULL, 'Trần Minh Khôi', '1983-10-19', 'M', '0938901234', 'khoi.tm@gmail.com', '33 Hoa Lư, Quy Nhơn', 'TE4790123456035', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(36, NULL, 'BN00036', NULL, 'Đỗ Thị Thắm', '1975-08-11', 'F', '0949012345', 'tham.dt@gmail.com', '82 Quy Nhơn', 'GD4790123456036', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(37, NULL, 'BN00037', NULL, 'Phạm Đức Thắng', '1990-04-01', 'M', '0960123456', NULL, '123, Xã Trung Phúc Cường, Huyện Nam Đàn, Tỉnh Nghệ An', 'HT4790123456037', NULL, 'Không', 0, '2026-05-28 00:00:00', '2026-05-26 20:43:48'),
(38, NULL, 'BN00038', NULL, 'Nguyễn Bảo Trâm', '1997-03-15', 'F', '0971234567', 'tram.nb@gmail.com', '15 Lê Hồng Phong, Quy Nhơn', 'DN4790123456038', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(39, NULL, 'BN00039', NULL, 'Lê Thanh Bình', '1984-11-20', 'M', '0982345678', NULL, '123, Xã Kỳ Tân, Huyện Kỳ Anh, Tỉnh Hà Tĩnh', NULL, NULL, 'Không', 0, '2026-05-28 00:00:00', '2026-05-26 21:31:49'),
(40, NULL, 'BN00040', NULL, 'Trần Thị Thuỷ', '1969-01-22', 'F', '0993456789', NULL, '123, Xã Nghi Công Bắc, Huyện Nghi Lộc, Tỉnh Nghệ An', NULL, NULL, 'Không', 0, '2026-05-28 00:00:00', '2026-05-26 21:32:19'),
(41, NULL, 'BN00041', NULL, 'Phan Hoài Nam', '1992-06-05', 'M', '0324567890', 'nam.ph@gmail.com', '12 Lê Thánh Tôn, Quy Nhơn', 'GD4790123456041', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(42, NULL, 'BN00042', NULL, 'Vũ Ngọc Lan', '1996-09-09', 'F', '0335678901', 'lan.vn@gmail.com', '99 Xuân Diệu, Quy Nhơn', 'HT4790123456042', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(43, NULL, 'BN00043', NULL, 'Hoàng Văn Phong', '1979-05-14', 'M', '0346789012', 'phong.hv@gmail.com', '21 Tây Sơn, Quy Nhơn', 'DN4790123456043', 'O+', 'Paracetamol', 0, '2026-05-28 00:00:00', NULL),
(44, NULL, 'BN00044', NULL, 'Nguyễn Kim Chi', '2003-11-12', 'F', '0357890123', 'chi.nk@gmail.com', '60 An Dương Vương, Quy Nhơn', 'CH4790123456044', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(45, NULL, 'BN00045', NULL, 'Đặng Thành Long', '1987-02-27', 'M', '0368901234', 'long.dt@gmail.com', '140 Hùng Vương, Quy Nhơn', 'TE4790123456045', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(46, NULL, 'BN00046', NULL, 'Bùi Diệu Linh', '1994-08-16', 'F', '0379012345', 'linh.bd@gmail.com', '88 Ngô Mây, Quy Nhơn', 'GD4790123456046', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(47, NULL, 'BN00047', NULL, 'Trần Cao Cường', '1974-12-03', 'M', '0380123456', 'cuong.tc@gmail.com', '12 Tháp Đôi, Quy Nhơn', 'HT4790123456047', 'B-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(48, NULL, 'BN00048', NULL, 'Lê Phương Thảo', '1999-07-24', 'F', '0391234567', 'thao.lp@gmail.com', '54 Trần Hưng Đạo, Quy Nhơn', 'DN4790123456048', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(49, NULL, 'BN00049', NULL, 'Phạm Ngọc Hải', '1985-10-31', 'M', '0702345678', 'hai.pn@gmail.com', '11 Nguyễn Huệ, Quy Nhơn', 'CH4790123456049', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(50, NULL, 'BN00050', NULL, 'Nguyễn Thị Hiền', '1966-04-04', 'F', '0763456789', 'hien.nt@gmail.com', '202 Lê Lợi, Quy Nhơn', 'TE4790123456050', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(51, NULL, 'BN00051', NULL, 'Võ Hoàng Quân', '1990-09-12', 'M', '0774567890', 'quan.vh@gmail.com', '45 Hoàng Quốc Việt, Quy Nhơn', 'GD4790123456051', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(52, NULL, 'BN00052', NULL, 'Đỗ Như Quỳnh', '1995-05-26', 'F', '0785678901', 'quynh.dn@gmail.com', '89 Bạch Đằng, Quy Nhơn', 'HT4790123456052', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(53, NULL, 'BN00053', NULL, 'Nguyễn Tấn Đạt', '1981-12-19', 'M', '0796789012', 'dat.nt@gmail.com', '14 Nguyễn Lữ, Quy Nhơn', 'DN4790123456053', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(54, NULL, 'BN00054', NULL, 'Trần Thanh Trúc', '1998-03-14', 'F', '0817890123', 'truc.tt@gmail.com', '73 Lê Duẩn, Quy Nhơn', 'CH4790123456054', 'AB-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(55, NULL, 'BN00055', NULL, 'Lê Đình Trọng', '1977-10-08', 'M', '0828901234', 'trong.ld@gmail.com', '112 Nguyễn Thị Định, Quy Nhơn', 'TE4790123456055', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(56, NULL, 'BN00056', NULL, 'Phạm Mai Anh', '2001-07-21', 'F', '0839012345', 'anh.pm@gmail.com', '36 Đô Đốc Bảo, Quy Nhơn', 'GD4790123456056', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(57, NULL, 'BN00057', NULL, 'Hoàng Xuân Vinh', '1988-01-30', 'M', '0840123456', 'vinh.hx@gmail.com', '95 Biên Cương, Quy Nhơn', 'HT4790123456057', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(58, NULL, 'BN00058', NULL, 'Nguyễn Thuỷ Tiên', '1993-11-05', 'F', '0852345678', 'tien.nt@gmail.com', '152 Chương Dương, Quy Nhơn', 'DN4790123456058', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(59, NULL, 'BN00059', NULL, 'Bùi Quang Huy', '1984-05-17', 'M', '0863456789', 'huy.bq@gmail.com', '18 Tố Hữu, Quy Nhơn', 'CH4790123456059', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(60, NULL, 'BN00060', NULL, 'Vũ Khánh Linh', '1997-08-29', 'F', '0884567890', 'linh.vk@gmail.com', '61 Phạm Hùng, Quy Nhơn', 'TE4790123456060', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(61, NULL, 'BN00061', NULL, 'Trần Hữu Phước', '1972-02-14', 'M', '0895678901', 'phuoc.th@gmail.com', '80 Nhơn Lý, Quy Nhơn', 'GD4790123456061', 'A-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(62, NULL, 'BN00062', NULL, 'Lê Thị Nga', '1965-06-22', 'F', '0906789012', 'nga.lt@gmail.com', '25 Nhơn Hải, Quy Nhơn', 'HT4790123456062', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(63, NULL, 'BN00063', NULL, 'Phạm Tiến Dũng', '2004-10-09', 'M', '0917890123', 'dung.pt@gmail.com', '104 Trần Quang Diệu, Quy Nhơn', 'DN4790123456063', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(64, NULL, 'BN00064', NULL, 'Nguyễn Thanh Hà', '1991-04-27', 'F', '0928901234', 'ha.nt@gmail.com', '38 Phú Tài, Quy Nhơn', 'CH4790123456064', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(65, NULL, 'BN00065', NULL, 'Hoàng Minh Nhật', '1983-07-11', 'M', '0939012345', 'nhat.hm@gmail.com', '57 Bùi Thị Xuân, Quy Nhơn', 'TE4790123456065', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(66, NULL, 'BN00066', NULL, 'Trịnh Thị Yến', '1978-09-03', 'F', '0940123456', 'yen.tt@gmail.com', '83 Phước Mỹ, Quy Nhơn', 'GD4790123456066', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(67, NULL, 'BN00067', NULL, 'Đặng Văn Việt', '1995-12-25', 'M', '0961234567', 'viet.dv@gmail.com', '12 Đống Đa, Quy Nhơn', 'HT4790123456067', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(68, NULL, 'BN00068', NULL, 'Bùi Thị Tú', '2002-03-18', 'F', '0972345678', 'tu.bt@gmail.com', '175 Hàm Nghi, Quy Nhơn', 'DN4790123456068', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(69, NULL, 'BN00069', NULL, 'Nguyễn Văn Thịnh', '1980-01-01', 'M', '0983456789', 'thinh.nv@gmail.com', '66 Hoa Lư, Quy Nhơn', 'CH4790123456069', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(70, NULL, 'BN00070', NULL, 'Lê Mỹ Hạnh', '1971-08-14', 'F', '0994567890', 'hanh.lm@gmail.com', '99 Quy Nhơn', 'TE4790123456070', 'O-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(71, NULL, 'BN00071', NULL, 'Trần Anh Quân', '1993-10-30', 'M', '0325678901', 'quan.ta@gmail.com', '210 Nguyễn Thái Học, Quy Nhơn', 'GD4790123456071', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(72, NULL, 'BN00072', NULL, 'Phạm Thu Trang', '1996-05-15', 'F', '0336789012', 'trang.pt@gmail.com', '45 Lê Hồng Phong, Quy Nhơn', 'HT4790123456072', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(73, NULL, 'BN00073', NULL, 'Nguyễn Tuấn Tú', '1985-07-20', 'M', '0347890123', 'tu.nt@gmail.com', '78 Phan Bội Châu, Quy Nhơn', 'DN4790123456073', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(74, NULL, 'BN00074', NULL, 'Hoàng Hồng Đào', '1967-12-12', 'F', '0358901234', 'dao.hh@gmail.com', '122 Tăng Bạt Hổ, Quy Nhơn', 'CH4790123456074', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(75, NULL, 'BN00075', NULL, 'Vũ Đức Mạnh', '1992-04-08', 'M', '0369012345', 'manh.vd@gmail.com', '34 Lê Thánh Tôn, Quy Nhơn', 'TE4790123456075', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(76, NULL, 'BN00076', NULL, 'Đỗ Ngọc Trinh', '1994-02-22', 'F', '0370123456', 'trinh.dn@gmail.com', '115 Xuân Diệu, Quy Nhơn', 'GD4790123456076', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(77, NULL, 'BN00077', NULL, 'Nguyễn Thành Nam', '1976-11-14', 'M', '0381234567', 'nam.nt@gmail.com', '43 Tây Sơn, Quy Nhơn', 'HT4790123456077', 'B-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(78, NULL, 'BN00078', NULL, 'Trần Thảo Nguyên', '2000-09-09', 'F', '0392345678', 'nguyen.tt@gmail.com', '90 An Dương Vương, Quy Nhơn', 'DN4790123456078', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(79, NULL, 'BN00079', NULL, 'Lê Hữu Đạt', '1989-06-18', 'M', '0703456789', 'dat.lh@gmail.com', '180 Hùng Vương, Quy Nhơn', 'CH4790123456079', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(80, NULL, 'BN00080', NULL, 'Phạm Như Ý', '1963-03-24', 'F', '0764567890', 'y.pn@gmail.com', '112 Ngô Mây, Quy Nhơn', 'TE4790123456080', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(81, NULL, 'BN00081', NULL, 'Hoàng Quốc Khánh', '1991-01-31', 'M', '0775678901', 'khanh.hq@gmail.com', '54 Tháp Đôi, Quy Nhơn', 'GD4790123456081', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(82, NULL, 'BN00082', NULL, 'Bùi Phương Anh', '1995-08-16', 'F', '0786789012', 'anh.bp@gmail.com', '98 Trần Hưng Đạo, Quy Nhơn', 'HT4790123456082', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(83, NULL, 'BN00083', NULL, 'Nguyễn Trọng Nghĩa', '1982-10-05', 'M', '0797890123', 'nghia.nt@gmail.com', '33 Nguyễn Huệ, Quy Nhơn', 'DN4790123456083', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(84, NULL, 'BN00084', NULL, 'Trần Diễm My', '1999-05-19', 'F', '0818901234', 'my.td@gmail.com', '255 Lê Lợi, Quy Nhơn', 'CH4790123456084', 'AB-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(85, NULL, 'BN00085', NULL, 'Lê Văn Thắng', '1974-07-27', 'M', '0829012345', 'thang.lv@gmail.com', '82 Hoàng Quốc Việt, Quy Nhơn', 'TE4790123456085', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(86, NULL, 'BN00086', NULL, 'Phạm Kim Ngân', '2003-04-12', 'F', '0830123456', 'ngan.pk@gmail.com', '119 Bạch Đằng, Quy Nhơn', 'GD4790123456086', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(87, NULL, 'BN00087', NULL, 'Nguyễn Duy Hải', '1988-12-03', 'M', '0841234567', 'hai.nd@gmail.com', '36 Nguyễn Lữ, Quy Nhơn', 'HT4790123456087', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(88, NULL, 'BN00088', NULL, 'Trần Bảo Ngọc', '1993-02-25', 'F', '0853456789', 'ngoc.tb@gmail.com', '94 Lê Duẩn, Quy Nhơn', 'DN4790123456088', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(89, NULL, 'BN00089', NULL, 'Vũ Hoàng Việt', '1986-09-14', 'M', '0864567890', 'viet.vh@gmail.com', '140 Nguyễn Thị Định, Quy Nhơn', 'CH4790123456089', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(90, NULL, 'BN00090', NULL, 'Đặng Minh Thư', '1998-06-08', 'F', '0885678901', 'thu.dm@gmail.com', '58 Đô Đốc Bảo, Quy Nhơn', 'TE4790123456090', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(91, NULL, 'BN00091', NULL, 'Bùi Văn Hùng', '1970-11-20', 'M', '0896789012', 'hung.bv@gmail.com', '115 Biên Cương, Quy Nhơn', 'GD4790123456091', 'A-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(92, NULL, 'BN00092', NULL, 'Nguyễn Thị Oanh', '1968-03-17', 'F', '0907890123', 'oanh.nt@gmail.com', '182 Chương Dương, Quy Nhơn', 'HT4790123456092', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(93, NULL, 'BN00093', NULL, 'Lê Anh Tuấn', '2005-05-05', 'M', '0918901234', 'tuan.la@gmail.com', '40 Tố Hữu, Quy Nhơn', 'DN4790123456093', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(94, NULL, 'BN00094', NULL, 'Trần Khánh Ly', '1991-01-22', 'F', '0929012345', 'ly.tk@gmail.com', '83 Phạm Hùng, Quy Nhơn', 'CH4790123456094', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(95, NULL, 'BN00095', '1111111', 'Phạm Xuân Trường', '1981-08-30', 'M', '0930123456', 'truong.px@gmail.com', '92 Nhơn Lý, Quy Nhơn', 'TE4790123456095', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(96, NULL, 'BN00096', NULL, 'Nguyễn Thu Trang', '1979-04-14', 'F', '0941234567', 'trang.nt@gmail.com', '44 Nhơn Hải, Quy Nhơn', 'GD4790123456096', 'A+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(97, NULL, 'BN00097', NULL, 'Hoàng Đức Toàn', '1994-07-19', 'M', '0962345678', 'toan.hd@gmail.com', '124 Trần Quang Diệu, Quy Nhơn', 'HT4790123456097', 'B+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(98, NULL, 'BN00098', NULL, 'Đỗ Thanh Huyền', '2002-12-03', 'F', '0973456789', 'huyen.dt@gmail.com', '56 Phú Tài, Quy Nhơn', 'DN4790123456098', 'O+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(99, NULL, 'BN00099', NULL, 'Nguyễn Quang Khải', '1985-02-16', 'M', '0984567890', 'khai.nq@gmail.com', '82 Bùi Thị Xuân, Quy Nhơn', 'CH4790123456099', 'AB+', 'Không', 0, '2026-05-28 00:00:00', NULL),
(100, NULL, 'BN00100', NULL, 'Trần Thúy Vy', '1996-10-27', 'F', '0995678901', 'vy.tt@gmail.com', '99 Phước Mỹ, Quy Nhơn', 'TE4790123456100', 'O-', 'Không', 0, '2026-05-28 00:00:00', NULL),
(118, 133, 'BN202605261680', NULL, 'Phan Trần Hoàng', NULL, NULL, '000000000', NULL, NULL, NULL, NULL, NULL, 0, '2026-05-28 00:00:00', NULL),
(119, 134, 'BN202605269109', NULL, '123', '2026-06-04', 'M', '1231231111', NULL, '111, Phường Hoài Nhơn Nam, Tỉnh Gia Lai', NULL, NULL, 'Không', 0, '2026-05-28 00:00:00', '2026-06-05 08:50:19'),
(120, NULL, 'BN202605269110', NULL, 'Lê Thanh Bình', '2026-04-29', 'F', '0982345678', NULL, NULL, NULL, NULL, NULL, 0, '2026-05-28 00:00:00', NULL),
(121, 135, 'BN202605269111', '0542060099922', 'Hoàng BN', '2026-05-13', 'M', '1111111111', '11hoangphandiy@gmail.com', 'Xã Xuân Thọ, Tỉnh Đắk Lắk', '', '', '', 0, '2026-05-28 00:00:00', '2026-06-29 08:56:09'),
(122, NULL, 'BN202605284318', NULL, 'Hoang test', '2026-05-06', 'M', '0901234561', NULL, 'thôn chánh nam, Phường Quy Nhơn Nam, Tỉnh Gia Lai', NULL, NULL, 'Không', 0, '2026-05-28 23:30:23', '2026-05-30 01:34:51'),
(123, NULL, 'BN00123', NULL, 'aaaaa', '2026-05-06', 'F', '7777777895', NULL, '111, Xã Đức Hợp, Tỉnh Hưng Yên', '1111', NULL, 'Không', 1, '2026-05-30 01:31:11', NULL),
(124, 123, 'BN00124', NULL, '111', '2026-05-06', 'M', '111', NULL, '111, Xã Bắc Đông Quan, Tỉnh Hưng Yên', '111', NULL, 'Không', 1, '2026-05-30 01:31:41', NULL),
(125, 137, 'BN202606057077', '054206007778', 'Hoàng DEV', '2006-08-16', 'M', '9999999999', NULL, 'thôn chánh nam, Xã Xuân Thọ, Tỉnh Đắk Lắk', 'BHYT123456789', NULL, 'Không', 0, '2026-06-05 09:00:27', '2026-06-06 15:00:33'),
(126, NULL, 'BN00126', NULL, 'bệnh nhân', '2026-05-20', 'M', '0123456456', NULL, 'abc, Phường Nhị Quý, Tỉnh Đồng Tháp', 'BHYT12345671111', NULL, 'Không', 1, '2026-06-05 11:16:52', NULL),
(127, NULL, 'BN00127', NULL, 'aaaaa', '2026-06-03', 'M', '000000888', NULL, 'thôn chánh nam, Xã Cẩm Xuyên, Tỉnh Hà Tĩnh', 'BHYT12341111111', NULL, 'Không', 1, '2026-06-05 11:45:24', NULL),
(128, NULL, 'BN00128', '123123123123', 'Hoàng DEV1', '2026-04-30', 'M', '9999999999', NULL, 'abc, Xã Bắc Thụy Anh, Tỉnh Hưng Yên', 'BHYT12345678911', NULL, 'Không', 1, '2026-06-05 14:01:04', NULL),
(129, NULL, 'BN00129', '1231231239876', 'hoàng abcxyz', '2026-06-02', 'M', '9999999988', NULL, 'abc, Phường Hoài Nhơn Bắc, Tỉnh Gia Lai', 'BHYT12345678944', 'A-', 'Không', 1, '2026-06-05 14:55:20', NULL),
(130, NULL, 'BN00130', '01234567891111', 'dev', '2026-06-02', 'M', '09601234561', NULL, 'ads, Phường Hoài Nhơn Tây, Tỉnh Gia Lai', '11111111111', NULL, 'Không', 1, '2026-06-06 23:16:37', NULL),
(131, NULL, 'BN00131', '0123456789123', 'Lê Thanh Bình 123', '2026-06-04', 'M', '9999999999123', NULL, '123, Xã Bắc Thụy Anh, Tỉnh Hưng Yên', '123', 'A+', '123', 1, '2026-06-07 00:23:42', NULL),
(132, NULL, 'BN00132', '1213123424', 'open ai', '2026-05-21', 'F', '3425567555', NULL, 'asdfgh, Xã Bắc Thái Ninh, Tỉnh Hưng Yên', '342453453636', 'A+', 'Không', 1, '2026-06-07 23:52:43', NULL),
(133, 138, 'BN202606216551', NULL, 'Hoàng 12 ad', NULL, NULL, '000111889', NULL, NULL, NULL, NULL, NULL, 0, '2026-06-21 11:53:43', NULL),
(134, 139, 'BN202606213611', NULL, 'Hoàng 12 ad', NULL, NULL, '000111880', NULL, NULL, NULL, NULL, NULL, 0, '2026-06-21 12:06:54', NULL),
(135, 140, 'BN202606219815', NULL, 'Hoàng 12 ad', NULL, NULL, '00011188955', NULL, NULL, NULL, NULL, NULL, 0, '2026-06-21 12:12:02', NULL),
(136, 149, 'BN202606215143', NULL, 'qtrhfh', NULL, NULL, '000111881078', NULL, NULL, NULL, NULL, NULL, 0, '2026-06-21 12:47:23', NULL),
(137, 150, 'BN202606213038', NULL, '32gffg', NULL, NULL, '000111889444', NULL, NULL, NULL, NULL, NULL, 0, '2026-06-21 12:48:52', NULL),
(138, 151, 'BN202606217974', NULL, 'Hoàng 12 ad', NULL, NULL, '00011188911', 'hoang4751190012@st.qnu.edu.vn', NULL, NULL, NULL, NULL, 0, '2026-06-21 13:15:32', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhinhhethong`
--

CREATE TABLE `cauhinhhethong` (
  `KhoacCauHinh` varchar(100) NOT NULL,
  `GiaTri` text DEFAULT NULL,
  `NguoiCapNhat` int(11) DEFAULT NULL,
  `ThoiGianCapNhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cauhinhhethong`
--

INSERT INTO `cauhinhhethong` (`KhoacCauHinh`, `GiaTri`, `NguoiCapNhat`, `ThoiGianCapNhat`) VALUES
('bao_tri', '0', 101, '2026-06-29 10:30:23'),
('ctk', 'PHAN TRAN HOANG', 101, '2026-06-29 10:30:23'),
('dia_chi', 'Quy Nhơn, Bình Định', 101, '2026-06-29 10:30:23'),
('email', 'phongkhamhuongson@gmail.com', 101, '2026-06-29 10:30:23'),
('gio_mo_cua', '{\"Monday\":{\"start\":\"08:00\",\"end\":\"17:00\",\"open\":true},\"Tuesday\":{\"start\":\"08:00\",\"end\":\"17:00\",\"open\":true},\"Wednesday\":{\"start\":\"08:00\",\"end\":\"20:00\",\"open\":true},\"Thursday\":{\"start\":\"08:00\",\"end\":\"20:00\",\"open\":true},\"Friday\":{\"start\":\"08:00\",\"end\":\"20:00\",\"open\":true},\"Saturday\":{\"start\":\"08:00\",\"end\":\"17:00\",\"open\":true},\"Sunday\":{\"start\":\"08:00\",\"end\":\"17:00\",\"open\":true}}', 101, '2026-06-29 10:30:23'),
('logo_url', 'public/assets/img/icon.png', 101, '2026-06-29 10:30:23'),
('ngan_hang', 'MbBank', 101, '2026-06-29 10:30:23'),
('so_dien_thoai', '18009999', 101, '2026-06-29 10:30:23'),
('stk', '00709916082006', 101, '2026-06-29 10:30:23'),
('ten_phong_kham', 'Phòng Khám Hương Sơn', 101, '2026-06-29 10:30:23'),
('tien_to_benh_nhan', 'BN', 101, '2026-06-29 10:30:23'),
('tien_to_hoa_don', 'HD', 101, '2026-06-29 10:30:23'),
('tien_to_phieu_kham', 'PK', 101, '2026-06-29 10:30:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chidinhcls`
--

CREATE TABLE `chidinhcls` (
  `MaChiDinh` int(11) NOT NULL,
  `MaPhieuKham` int(11) NOT NULL,
  `MaLoaiCLS` smallint(6) NOT NULL,
  `MoTaChiDinh` text DEFAULT NULL,
  `NgayChiDinh` datetime NOT NULL DEFAULT current_timestamp(),
  `trangthai` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chidinhcls`
--

INSERT INTO `chidinhcls` (`MaChiDinh`, `MaPhieuKham`, `MaLoaiCLS`, `MoTaChiDinh`, `NgayChiDinh`, `trangthai`) VALUES
(7001, 1, 5, 'Ghi điện tâm đồ để đánh giá nhịp tim và thiếu máu cơ tim', '2026-04-25 16:00:00', 0),
(7002, 1, 4, 'Đánh giá chức năng tim, phân suất tống máu', '2026-04-25 16:05:00', 0),
(7003, 1, 1, 'Đánh giá tổn thương cơ tim', '2026-04-25 16:10:00', 0),
(7004, 2, 4, 'Siêu âm đánh giá hình thái và cân nặng thai nhi', '2026-04-18 10:30:00', 0),
(7005, 3, 1, 'Đếm tiểu cầu, bạch cầu để theo dõi sốt xuất huyết', '2026-05-01 11:00:00', 0),
(7006, 3, 2, 'Kiểm tra chức năng thận', '2026-05-01 11:05:00', 0),
(7007, 4, 1, 'Đánh giá chức năng thận và đường huyết', '2026-05-09 09:00:00', 0),
(7008, 4, 1, 'Cholesterol toàn phần, HDL, LDL, Triglyceride', '2026-05-09 09:05:00', 0),
(7009, 5, 4, 'Khảo sát dị tật bẩm sinh, đo các chỉ số thai', '2026-05-13 10:05:00', 0),
(7010, 6, 5, 'Theo dõi nhịp tim định kỳ', '2026-05-16 15:05:00', 0),
(7011, 6, 1, 'Đánh giá suy tim', '2026-05-16 15:10:00', 0),
(7012, 7, 5, 'Phát hiện loạn nhịp tim', '2026-05-22 07:42:00', 0),
(7013, 7, 1, 'Kiểm tra rối loạn điện giải gây loạn nhịp', '2026-05-22 07:43:00', 0),
(7014, 8, 1, 'Phát hiện thiếu máu', '2026-05-22 08:06:00', 0),
(7015, 8, 1, 'Đánh giá nguyên nhân thiếu máu', '2026-05-22 08:07:00', 0),
(7016, 9, 4, 'Khảo sát tuần hoàn mạch máu, phát hiện hội chứng ống cổ tay', '2026-05-22 08:36:00', 0),
(7017, 100, 1, '', '2026-05-26 16:55:41', 0),
(7019, 169, 7, 'Nội soi dạ dày, đại tràng,...', '2026-06-07 20:17:54', 0),
(7020, 169, 1, 'Chụp X-quang các vùng 123', '2026-06-07 20:17:54', 0),
(7021, 174, 5, 'Chụp/Xét nghiệm theo quy chuẩn.12', '2026-06-07 22:10:16', 0),
(7022, 174, 6, 'Chụp/Xét nghiệm theo quy chuẩn.56', '2026-06-07 22:10:16', 0),
(7023, 196, 5, 'Chụp/Xét nghiệm theo quy chuẩn.', '2026-06-08 12:05:55', 2),
(7024, 196, 4, 'Chụp/Xét nghiệm theo quy chuẩn.', '2026-06-08 12:05:55', 2),
(7025, 199, 1, 'Chụp/Xét nghiệm theo quy chuẩn.', '2026-06-08 12:06:36', 2),
(7026, 199, 4, 'Chụp/Xét nghiệm theo quy chuẩn.', '2026-06-08 12:06:36', 0),
(7027, 206, 5, 'Ghi điện tâm đồ', '2026-06-19 03:56:49', 1),
(7028, 206, 7, 'Nội soi dạ dày, đại tràng,...', '2026-06-19 03:56:49', 2),
(7029, 209, 5, 'Ghi điện tâm đồ', '2026-06-20 00:19:16', 2),
(7030, 209, 3, 'Chụp X-quang các vùng', '2026-06-20 00:19:16', 2),
(7031, 209, 1, 'Công thức máu, sinh hóa,...', '2026-06-20 00:19:16', 2),
(7032, 210, 5, 'Ghi điện tâm đồ', '2026-06-20 00:37:24', 2),
(7033, 210, 3, 'Chụp X-quang các vùng', '2026-06-20 00:37:24', 2),
(7034, 210, 2, 'Tổng phân tích nước tiểu', '2026-06-20 00:37:24', 2),
(7035, 209, 5, 'Ghi điện tâm đồ', '2026-06-20 09:52:23', 1),
(7036, 209, 6, 'Cộng hưởng từ', '2026-06-20 09:52:23', 1),
(7037, 209, 7, 'Nội soi dạ dày, đại tràng,...', '2026-06-20 09:52:23', 1),
(7038, 211, 5, 'Ghi điện tâm đồ', '2026-06-20 09:58:48', 2),
(7039, 211, 1, 'Công thức máu, sinh hóa,...', '2026-06-20 09:58:48', 2),
(7040, 220, 5, 'Ghi điện tâm đồ', '2026-06-20 11:02:01', 2),
(7041, 220, 6, 'Cộng hưởng từ', '2026-06-20 11:02:01', 2),
(7042, 220, 3, 'Chụp X-quang các vùng', '2026-06-20 11:02:01', 2),
(7043, 213, 3, 'Chụp X-quang các vùng', '2026-06-20 23:51:22', 2),
(7044, 224, 3, 'Chụp X-quang các vùng', '2026-06-21 00:03:24', 2),
(7045, 225, 5, 'Ghi điện tâm đồ', '2026-06-21 00:28:38', 1),
(7046, 228, 1, 'Công thức máu, sinh hóa,...', '2026-06-21 02:44:16', 2),
(7047, 228, 3, 'Chụp X-quang các vùng', '2026-06-21 02:44:16', 2),
(7048, 228, 4, 'Siêu âm ổ bụng, tim, thai,...', '2026-06-21 02:44:16', 2),
(7049, 230, 3, 'Chụp X-quang các vùng', '2026-06-21 03:40:11', 2),
(7050, 230, 1, 'Công thức máu, sinh hóa,...', '2026-06-21 03:40:11', 2),
(7051, 231, 6, 'Cộng hưởng từ', '2026-06-21 15:52:29', 2),
(7052, 229, 4, 'Siêu âm ổ bụng, tim, thai,...', '2026-06-21 15:55:09', 0),
(7053, 229, 3, 'Chụp X-quang các vùng', '2026-06-21 15:55:09', 0),
(7054, 234, 6, 'Cộng hưởng từ', '2026-06-29 07:28:54', 2),
(7055, 235, 5, 'Ghi điện tâm đồ', '2026-06-29 08:07:09', 2),
(7056, 236, 5, 'Ghi điện tâm đồ', '2026-06-29 08:09:38', 1),
(7057, 236, 3, 'Chụp X-quang các vùng', '2026-06-29 08:09:38', 1),
(7058, 237, 1, 'Công thức máu, sinh hóa,...', '2026-06-29 11:19:41', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonthuoc`
--

CREATE TABLE `chitietdonthuoc` (
  `MaChiTiet` int(11) NOT NULL,
  `MaDonThuoc` int(11) NOT NULL,
  `MaThuoc` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(15,2) NOT NULL,
  `CachDung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonthuoc`
--

INSERT INTO `chitietdonthuoc` (`MaChiTiet`, `MaDonThuoc`, `MaThuoc`, `SoLuong`, `DonGia`, `CachDung`) VALUES
(10001, 9001, 3008, 30, 60000.00, '1 viên/ngày trước ăn sáng 30 phút, dùng trong 30 ngày'),
(10002, 9001, 3010, 30, 45000.00, '1 viên x 3 lần/ngày trước bữa ăn 15 phút'),
(10003, 9001, 3001, 20, 20000.00, '1 viên khi đau, tối đa 3 viên/ngày, không quá 5 ngày'),
(10004, 9002, 3004, 21, 50000.00, '1 viên x 3 lần/ngày, uống đủ 7 ngày'),
(10005, 9002, 3002, 15, 35000.00, '1 viên x 3 lần/ngày sau ăn, uống 5 ngày khi đau'),
(10006, 9003, 3035, 1, 52000.00, 'Uống 5-10ml nếu bé sốt sau tiêm (khi nhiệt độ > 38.5°C)'),
(10007, 9004, 3022, 10, 52000.00, '1 viên/ngày buổi tối, uống 10 ngày'),
(10008, 9004, 3025, 1, 52000.00, 'Bôi mỏng vùng da tổn thương 2 lần/ngày, dùng 10 ngày'),
(10009, 9005, 3006, 6, 135000.00, 'Uống 2 viên ngày đầu, sau đó 1 viên/ngày x 4 ngày'),
(10010, 9005, 3001, 15, 20000.00, '1-2 viên khi sốt/đau, cách 6 giờ, không quá 3g/ngày'),
(10011, 9005, 3014, 5, 68000.00, '1 viên sủi pha nước uống/ngày'),
(10012, 9006, 3028, 1, 128000.00, 'Nhỏ 1-2 giọt mỗi mắt khi khô mắt, có thể dùng 3-4 lần/ngày'),
(10013, 9007, 3019, 30, 98000.00, '1 viên/ngày vào buổi sáng cùng giờ'),
(10014, 9007, 3018, 30, 112000.00, '1 viên/ngày buổi sáng'),
(10015, 9007, 3017, 30, 120000.00, '1 viên/ngày vào buổi tối'),
(10016, 9007, 3003, 30, 28000.00, '1 viên/ngày sau bữa ăn (chống đông nhẹ)'),
(10017, 9008, 3001, 20, 20000.00, '1-2 viên khi đau đầu, tối đa 3g/ngày'),
(10018, 9008, 3016, 30, 90000.00, '1 viên/ngày sau ăn'),
(10019, 9009, 3037, 10, 60000.00, '2 viên lúc đầu, sau đó 1 viên sau mỗi lần đi ngoài lỏng, tối đa 5 viên/ngày'),
(10020, 9009, 3010, 15, 45000.00, '1 viên x 3 lần/ngày trước ăn'),
(10021, 9010, 3022, 10, 52000.00, '1 viên/ngày buổi tối, dùng 10 ngày'),
(10022, 9011, 3004, 21, 50000.00, '1 viên x 3 lần/ngày, uống đủ 7 ngày'),
(10023, 9011, 3001, 15, 20000.00, '1-2 viên khi sốt/đau họng, tối đa 3g/ngày'),
(10024, 9012, 3029, 1, 142000.00, 'Nhỏ 1-2 giọt x 4-6 lần/ngày mỗi mắt, dùng 7 ngày'),
(10025, 9013, 3042, 30, 120000.00, '1 viên/ngày buổi sáng'),
(10026, 9013, 3019, 30, 98000.00, '1 viên/ngày cùng giờ'),
(10027, 9013, 3041, 30, 112000.00, '1 viên/ngày buổi tối (kiểm soát cholesterol)'),
(10028, 9014, 3002, 10, 35000.00, '1 viên ngay khi đau xuất hiện, có thể lặp lại sau 6 giờ'),
(10029, 9014, 3022, 10, 52000.00, '1 viên/ngày buổi tối (giảm nhạy cảm ánh sáng, tiếng ồn)'),
(10030, 9015, 3008, 30, 60000.00, '1 viên/ngày buổi sáng trước ăn (duy trì liều)'),
(10031, 9016, 3025, 1, 52000.00, 'Bôi mỏng vùng tổn thương 1 lần/ngày buổi tối, dùng tiếp 10 ngày'),
(10032, 9017, 3007, 14, 85000.00, '1 viên x 2 lần/ngày trong 7 ngày'),
(10033, 9017, 3013, 21, 52000.00, '1 viên x 3 lần/ngày sau ăn'),
(10034, 9017, 3001, 10, 20000.00, '1-2 viên khi sốt, tối đa 3g/ngày'),
(10035, 9018, 3019, 30, 98000.00, '1 viên/ngày cùng giờ'),
(10036, 9018, 3049, 60, 150000.00, '1 viên x 2 lần/ngày trong bữa ăn (bảo vệ cơ tim)'),
(10037, 9019, 3050, 60, 135000.00, '1 viên x 3 lần/ngày (cải thiện tuần hoàn não)'),
(10038, 9020, 3008, 30, 60000.00, '1 viên/ngày sáng (duy trì liều thấp)'),
(10039, 9021, 3015, 30, 75000.00, '1 viên/ngày sau ăn (bổ sung canxi hồi phục)'),
(10040, 9022, 3050, 1, 135000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10041, 9022, 3049, 1, 150000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10042, 9022, 3047, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10043, 9023, 3049, 1, 150000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10044, 9024, 3046, 2, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10045, 9024, 3043, 3, 68000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10046, 9024, 3037, 3, 60000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10047, 9024, 3040, 3, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10048, 9024, 3042, 2, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10049, 9025, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10050, 9025, 3051, 1, 20000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10051, 9025, 3041, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10052, 9026, 3052, 2, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10053, 9026, 3051, 3, 20000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10054, 9026, 3044, 2, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10055, 9026, 3041, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10056, 9026, 3033, 1, 38000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10057, 9026, 3036, 1, 270000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10058, 9026, 3035, 3, 52000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10059, 9026, 3037, 1, 60000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10060, 9026, 3040, 3, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10061, 9027, 3051, 1, 20000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10062, 9027, 3044, 1, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10063, 9027, 3042, 1, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10064, 9027, 3040, 1, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10065, 9027, 3028, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10066, 9027, 3027, 1, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10067, 9027, 3029, 1, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10068, 9027, 3032, 3, 75000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10069, 9027, 3031, 3, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10070, 9027, 3030, 3, 38000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10071, 9027, 3013, 1, 52000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10072, 9027, 3015, 1, 75000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10073, 9027, 3018, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10074, 9027, 3017, 1, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10075, 9027, 3020, 1, 60000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10076, 9027, 3019, 1, 98000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10077, 9028, 3041, 4, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10078, 9028, 3042, 4, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10079, 9028, 3040, 4, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10080, 9028, 3027, 1, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10081, 9028, 3030, 4, 38000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10082, 9028, 3032, 3, 75000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10083, 9028, 3028, 2, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10084, 9029, 3051, 1, 20000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10085, 9029, 3041, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10086, 9029, 3042, 1, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10087, 9029, 3039, 1, 90000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10088, 9029, 3040, 1, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10089, 9029, 3034, 2, 42000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10090, 9029, 3033, 2, 38000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10091, 9029, 3036, 3, 270000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10092, 9029, 3035, 3, 52000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10093, 9029, 3038, 5, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10094, 9029, 3037, 4, 60000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10095, 9030, 3051, 1, 20000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10096, 9030, 3044, 1, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10097, 9030, 3041, 2, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10098, 9030, 3039, 3, 90000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10099, 9030, 3042, 1, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10100, 9031, 3051, 1, 20000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10101, 9031, 3044, 4, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10102, 9031, 3041, 3, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10103, 9031, 3042, 4, 120000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10104, 9031, 3035, 3, 52000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10105, 9031, 3033, 4, 38000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10106, 9032, 3046, 1, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10107, 9032, 3045, 3, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10108, 9032, 3048, 5, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10109, 9032, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10110, 9032, 3044, 4, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10111, 9033, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10112, 9033, 3047, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10113, 9033, 3048, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10114, 9033, 3045, 2, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10115, 9033, 3046, 2, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10116, 9033, 3044, 2, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10117, 9033, 3038, 2, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10118, 9033, 3037, 2, 60000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10119, 9033, 3040, 2, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10120, 9033, 3041, 4, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10121, 9033, 3031, 2, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10122, 9033, 3032, 2, 75000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10123, 9033, 3034, 2, 42000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10124, 9033, 3033, 2, 38000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10125, 9034, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10126, 9034, 3047, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10127, 9034, 3048, 2, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10128, 9034, 3045, 2, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10129, 9034, 3046, 2, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10130, 9034, 3044, 2, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10131, 9035, 3047, 3, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10132, 9035, 3048, 2, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10133, 9035, 3045, 3, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10134, 9035, 3046, 2, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10135, 9035, 3044, 2, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10136, 9035, 3038, 3, 142000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10137, 9035, 3037, 3, 60000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10138, 9035, 3040, 2, 45000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10139, 9036, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10140, 9036, 3047, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10141, 9036, 3048, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10142, 9036, 3045, 2, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10143, 9036, 3046, 1, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10144, 9037, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10145, 9037, 3047, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10146, 9037, 3048, 1, 112000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10147, 9037, 3045, 1, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10148, 9038, 3047, 1, 128000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10149, 9038, 3052, 1, 100000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10150, 9038, 3045, 1, 82000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'),
(10151, 9038, 3046, 3, 180000.00, 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieunhap`
--

CREATE TABLE `chitietphieunhap` (
  `MaChiTiet` int(11) NOT NULL,
  `MaPhieuNhap` int(11) NOT NULL,
  `MaThuoc` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(15,2) NOT NULL,
  `ThanhTien` decimal(15,2) NOT NULL,
  `SoLo` varchar(50) DEFAULT NULL,
  `HanSuDung` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietphieunhap`
--

INSERT INTO `chitietphieunhap` (`MaChiTiet`, `MaPhieuNhap`, `MaThuoc`, `SoLuong`, `DonGia`, `ThanhTien`, `SoLo`, `HanSuDung`) VALUES
(12001, 11001, 3001, 10000, 15000.00, 150000000.00, 'LOT2501A', '2027-12-31'),
(12002, 11001, 3004, 5000, 35000.00, 175000000.00, 'LOT2501B', '2027-09-30'),
(12003, 11001, 3008, 3000, 40000.00, 120000000.00, 'LOT2501C', '2027-10-31'),
(12004, 11002, 3002, 5000, 25000.00, 125000000.00, 'LOT2502A', '2027-06-30'),
(12005, 11002, 3022, 5000, 35000.00, 175000000.00, 'LOT2502B', '2027-11-30'),
(12006, 11003, 3019, 2000, 65000.00, 130000000.00, 'LOT2504A', '2027-09-30'),
(12007, 11003, 3018, 1500, 75000.00, 112500000.00, 'LOT2504B', '2028-05-31'),
(12008, 11004, 3017, 2000, 80000.00, 160000000.00, 'LOT2506A', '2027-10-31'),
(12009, 11004, 3042, 2000, 80000.00, 160000000.00, 'LOT2506B', '2028-06-30'),
(12010, 11005, 3001, 8000, 15000.00, 120000000.00, 'LOT2601A', '2028-12-31'),
(12011, 11005, 3008, 4000, 40000.00, 160000000.00, 'LOT2601B', '2028-10-31'),
(12012, 11006, 3013, 3000, 35000.00, 105000000.00, 'LOT2603A', '2027-11-30'),
(12013, 11006, 3050, 2000, 90000.00, 180000000.00, 'LOT2603B', '2028-02-29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietphieuxuat`
--

CREATE TABLE `chitietphieuxuat` (
  `MaChiTiet` int(11) NOT NULL,
  `MaPhieuXuat` int(11) NOT NULL,
  `MaThuoc` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(15,2) NOT NULL,
  `ThanhTien` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietphieuxuat`
--

INSERT INTO `chitietphieuxuat` (`MaChiTiet`, `MaPhieuXuat`, `MaThuoc`, `SoLuong`, `DonGia`, `ThanhTien`) VALUES
(14001, 13001, 3008, 1, 60000.00, 60000.00),
(14002, 13001, 3010, 1, 45000.00, 45000.00),
(14003, 13002, 3004, 1, 50000.00, 50000.00),
(14004, 13002, 3002, 1, 35000.00, 35000.00),
(14005, 13003, 3035, 1, 52000.00, 52000.00),
(14006, 13004, 3022, 1, 52000.00, 52000.00),
(14007, 13004, 3025, 1, 52000.00, 52000.00),
(14008, 13005, 3006, 1, 135000.00, 135000.00),
(14009, 13005, 3001, 1, 20000.00, 20000.00),
(14010, 13005, 3014, 1, 68000.00, 68000.00),
(14011, 13006, 3028, 1, 128000.00, 128000.00),
(14012, 13007, 3019, 1, 98000.00, 98000.00),
(14013, 13007, 3018, 1, 112000.00, 112000.00),
(14014, 13007, 3017, 1, 120000.00, 120000.00),
(14015, 13007, 3003, 1, 28000.00, 28000.00),
(14016, 13008, 3001, 1, 20000.00, 20000.00),
(14017, 13008, 3016, 1, 90000.00, 90000.00),
(14018, 13009, 3037, 1, 60000.00, 60000.00),
(14019, 13009, 3010, 1, 45000.00, 45000.00),
(14020, 13010, 3004, 1, 50000.00, 50000.00),
(14021, 13010, 3001, 1, 20000.00, 20000.00),
(14022, 13011, 3029, 1, 142000.00, 142000.00),
(14023, 13012, 3042, 1, 120000.00, 120000.00),
(14024, 13012, 3019, 1, 98000.00, 98000.00),
(14025, 13012, 3041, 1, 112000.00, 112000.00),
(14026, 13013, 3002, 1, 35000.00, 35000.00),
(14027, 13013, 3022, 1, 52000.00, 52000.00),
(14028, 13014, 3008, 1, 60000.00, 60000.00),
(14029, 13033, 3008, 30, 60000.00, 1800000.00),
(14030, 13033, 3010, 30, 45000.00, 1350000.00),
(14031, 13033, 3001, 20, 20000.00, 400000.00),
(14032, 13034, 3008, 30, 60000.00, 1800000.00),
(14033, 13035, 3004, 21, 50000.00, 1050000.00),
(14034, 13035, 3002, 15, 35000.00, 525000.00),
(14035, 13036, 3035, 1, 52000.00, 52000.00),
(14036, 13045, 3052, 1, 100000.00, 100000.00),
(14037, 13045, 3051, 1, 20000.00, 20000.00),
(14038, 13045, 3041, 1, 112000.00, 112000.00),
(14039, 13046, 3051, 1, 20000.00, 20000.00),
(14040, 13046, 3044, 1, 142000.00, 142000.00),
(14041, 13046, 3042, 1, 120000.00, 120000.00),
(14042, 13046, 3040, 1, 45000.00, 45000.00),
(14043, 13046, 3028, 1, 128000.00, 128000.00),
(14044, 13046, 3027, 1, 82000.00, 82000.00),
(14045, 13046, 3029, 1, 142000.00, 142000.00),
(14046, 13046, 3032, 3, 75000.00, 225000.00),
(14047, 13046, 3031, 3, 180000.00, 540000.00),
(14048, 13046, 3030, 3, 38000.00, 114000.00),
(14049, 13046, 3013, 1, 52000.00, 52000.00),
(14050, 13046, 3015, 1, 75000.00, 75000.00),
(14051, 13046, 3018, 1, 112000.00, 112000.00),
(14052, 13046, 3017, 1, 120000.00, 120000.00),
(14053, 13046, 3020, 1, 60000.00, 60000.00),
(14054, 13046, 3019, 1, 98000.00, 98000.00),
(14055, 13047, 3041, 4, 112000.00, 448000.00),
(14056, 13047, 3042, 4, 120000.00, 480000.00),
(14057, 13047, 3040, 4, 45000.00, 180000.00),
(14058, 13047, 3027, 1, 82000.00, 82000.00),
(14059, 13047, 3030, 4, 38000.00, 152000.00),
(14060, 13047, 3032, 3, 75000.00, 225000.00),
(14061, 13047, 3028, 2, 128000.00, 256000.00),
(14062, 13048, 3051, 1, 20000.00, 20000.00),
(14063, 13048, 3041, 1, 112000.00, 112000.00),
(14064, 13048, 3042, 1, 120000.00, 120000.00),
(14065, 13048, 3039, 1, 90000.00, 90000.00),
(14066, 13048, 3040, 1, 45000.00, 45000.00),
(14067, 13048, 3034, 2, 42000.00, 84000.00),
(14068, 13048, 3033, 2, 38000.00, 76000.00),
(14069, 13048, 3036, 3, 270000.00, 810000.00),
(14070, 13048, 3035, 3, 52000.00, 156000.00),
(14071, 13048, 3038, 5, 142000.00, 710000.00),
(14072, 13048, 3037, 4, 60000.00, 240000.00),
(14073, 13049, 3051, 1, 20000.00, 20000.00),
(14074, 13049, 3044, 1, 142000.00, 142000.00),
(14075, 13049, 3041, 2, 112000.00, 224000.00),
(14076, 13049, 3039, 3, 90000.00, 270000.00),
(14077, 13049, 3042, 1, 120000.00, 120000.00),
(14078, 13050, 3051, 1, 20000.00, 20000.00),
(14079, 13050, 3044, 4, 142000.00, 568000.00),
(14080, 13050, 3041, 3, 112000.00, 336000.00),
(14081, 13050, 3042, 4, 120000.00, 480000.00),
(14082, 13050, 3035, 3, 52000.00, 156000.00),
(14083, 13050, 3033, 4, 38000.00, 152000.00),
(14084, 13051, 3046, 1, 180000.00, 180000.00),
(14085, 13051, 3045, 3, 82000.00, 246000.00),
(14086, 13051, 3048, 5, 112000.00, 560000.00),
(14087, 13051, 3052, 1, 100000.00, 100000.00),
(14088, 13051, 3044, 4, 142000.00, 568000.00),
(14089, 13052, 3052, 1, 100000.00, 100000.00),
(14090, 13052, 3047, 1, 128000.00, 128000.00),
(14091, 13052, 3048, 1, 112000.00, 112000.00),
(14092, 13052, 3045, 2, 82000.00, 164000.00),
(14093, 13052, 3046, 2, 180000.00, 360000.00),
(14094, 13052, 3044, 2, 142000.00, 284000.00),
(14095, 13052, 3038, 2, 142000.00, 284000.00),
(14096, 13052, 3037, 2, 60000.00, 120000.00),
(14097, 13052, 3040, 2, 45000.00, 90000.00),
(14098, 13052, 3041, 4, 112000.00, 448000.00),
(14099, 13052, 3031, 2, 180000.00, 360000.00),
(14100, 13052, 3032, 2, 75000.00, 150000.00),
(14101, 13052, 3034, 2, 42000.00, 84000.00),
(14102, 13052, 3033, 2, 38000.00, 76000.00),
(14103, 13053, 3047, 3, 128000.00, 384000.00),
(14104, 13053, 3048, 2, 112000.00, 224000.00),
(14105, 13053, 3045, 3, 82000.00, 246000.00),
(14106, 13053, 3046, 2, 180000.00, 360000.00),
(14107, 13053, 3044, 2, 142000.00, 284000.00),
(14108, 13053, 3038, 3, 142000.00, 426000.00),
(14109, 13053, 3037, 3, 60000.00, 180000.00),
(14110, 13053, 3040, 2, 45000.00, 90000.00),
(14111, 13054, 3052, 1, 100000.00, 100000.00),
(14112, 13054, 3047, 1, 128000.00, 128000.00),
(14113, 13054, 3048, 1, 112000.00, 112000.00),
(14114, 13054, 3045, 2, 82000.00, 164000.00),
(14115, 13054, 3046, 1, 180000.00, 180000.00),
(14116, 13055, 3052, 1, 100000.00, 100000.00),
(14117, 13055, 3047, 1, 128000.00, 128000.00),
(14118, 13055, 3048, 1, 112000.00, 112000.00),
(14119, 13055, 3045, 1, 82000.00, 82000.00),
(14120, 13056, 3047, 1, 128000.00, 128000.00),
(14121, 13056, 3052, 1, 100000.00, 100000.00),
(14122, 13056, 3045, 1, 82000.00, 82000.00),
(14123, 13056, 3046, 3, 180000.00, 540000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyenkhoa`
--

CREATE TABLE `chuyenkhoa` (
  `MaChuyenKhoa` smallint(6) NOT NULL,
  `TenChuyenKhoa` varchar(100) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyenkhoa`
--

INSERT INTO `chuyenkhoa` (`MaChuyenKhoa`, `TenChuyenKhoa`, `MoTa`) VALUES
(1, 'Nội tổng quát', NULL),
(2, 'Ngoại tổng quát', NULL),
(3, 'Nhi khoa', NULL),
(4, 'Sản - Phụ khoa', NULL),
(5, 'Da liễu', NULL),
(6, 'Tai Mũi Họng', NULL),
(7, 'Mắt', NULL),
(8, 'Răng Hàm Mặt', NULL),
(9, 'Tim mạch', NULL),
(10, 'Thần kinh', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgiadichvu`
--

CREATE TABLE `danhgiadichvu` (
  `MaDanhGia` int(11) NOT NULL,
  `MaBenhNhan` int(11) NOT NULL,
  `MaPhieuKham` int(11) NOT NULL,
  `MaBacSi` int(11) DEFAULT NULL,
  `DiemSao` tinyint(4) NOT NULL,
  `NhanXet` text DEFAULT NULL,
  `DaDuyet` tinyint(1) NOT NULL DEFAULT 0,
  `NgayGui` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Đang đổ dữ liệu cho bảng `danhgiadichvu`
--

INSERT INTO `danhgiadichvu` (`MaDanhGia`, `MaBenhNhan`, `MaPhieuKham`, `MaBacSi`, `DiemSao`, `NhanXet`, `DaDuyet`, `NgayGui`) VALUES
(20001, 1, 1, 1003, 5, 'Bác sĩ An rất tận tâm, giải thích rõ ràng từng bước điều trị. Phòng khám sạch sẽ, lễ tân nhiệt tình.', 1, '2026-04-15 20:00:00'),
(20002, 2, 2, 1004, 5, 'BS. Trần Thị Bình theo dõi vết mổ rất kỹ. Yên tâm với dịch vụ ở đây.', 1, '2026-04-16 21:00:00'),
(20003, 5, 5, 1007, 4, 'Bác sĩ khám nhanh, thuốc hiệu quả. Chỉ hơi chờ lâu ở quầy lễ tân.', 1, '2026-04-20 18:30:00'),
(20004, 6, 6, 1008, 5, 'Bác sĩ Phong giải thích nguyên nhân bệnh rất dễ hiểu. Cảm ơn bác sĩ và đội ngũ phòng khám!', 1, '2026-04-23 10:00:00'),
(20005, 9, 9, 1011, 5, 'Bác sĩ Hải rất chuyên nghiệp, kiên nhẫn giải thích kết quả điện tim. Tin tưởng tuyệt đối.', 1, '2026-04-26 09:00:00'),
(20006, 10, 10, 1012, 4, 'Khám nhanh, kê đơn hợp lý. Phòng khám có thể cải thiện thêm thời gian chờ.', 1, '2026-04-27 11:00:00'),
(20007, 4, 4, 1006, 5, 'BS. Phạm Minh Tuấn Dũng rất chú tâm với thai phụ, giải thích siêu âm rõ ràng. Rất hài lòng!', 1, '2026-04-19 14:00:00'),
(20008, 7, 7, 1009, 5, 'Bác sĩ khám mắt rất kỹ, kê kính chuẩn. Sẽ giới thiệu bạn bè đến khám.', 1, '2026-04-24 17:00:00'),
(20009, 11, 11, 1003, 4, 'Điều trị tiêu hóa hiệu quả. Bác sĩ An cho lời khuyên ăn uống rất thực tế.', 1, '2026-05-01 10:00:00'),
(20010, 12, 12, 1004, 5, 'BS. Trần Thị Bình rất tận tâm với bệnh nhân. Tôi cảm thấy được chăm sóc rất tốt.', 1, '2026-05-02 15:00:00'),
(20011, 19, 19, 1011, 4, 'Điều trị huyết áp tốt, bác sĩ theo dõi kỹ. Mong phòng khám có thêm bãi đỗ xe.', 1, '2026-05-11 08:00:00'),
(20012, 9, 29, 1011, 5, 'Tái khám lần 2 vẫn rất hài lòng. BS. Hải nhớ bệnh sử của tôi, theo dõi rất liên tục.', 1, '2026-05-18 09:30:00'),
(20013, 1, 21, 1003, 5, 'Điều trị dạ dày tiến triển tốt, bác sĩ điều chỉnh phác đồ phù hợp. Phòng khám luôn đúng hẹn.', 1, '2026-05-12 14:00:00'),
(20014, 20, 20, 1012, 3, 'Khám ổn nhưng chờ khá lâu. Thuốc dùng được nhưng mong được tư vấn thêm về phòng ngừa.', 0, '2026-05-11 16:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donthuoc`
--

CREATE TABLE `donthuoc` (
  `MaDonThuoc` int(11) NOT NULL,
  `MaPhieuKham` int(11) NOT NULL,
  `NgayKeToa` datetime NOT NULL DEFAULT current_timestamp(),
  `LoiDan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donthuoc`
--

INSERT INTO `donthuoc` (`MaDonThuoc`, `MaPhieuKham`, `NgayKeToa`, `LoiDan`) VALUES
(9001, 1, '2026-04-15 09:10:00', 'Uống thuốc đúng giờ. Tránh đồ cay, nóng, chua. Không uống rượu bia. Tái khám nếu đau tăng'),
(9002, 2, '2026-04-16 09:25:00', 'Tiếp tục vệ sinh vết mổ hằng ngày. Không mang vật nặng. Tái khám ngay nếu sưng đỏ'),
(9003, 3, '2026-04-17 14:48:00', 'Theo dõi trẻ sau tiêm 30 phút tại phòng khám. Chườm lạnh nếu sưng chỗ tiêm'),
(9004, 4, '2026-04-19 15:20:00', 'Bôi thuốc đều đặn. Tránh gãi. Mặc quần áo thoáng mát. Tránh tiếp xúc chất gây dị ứng'),
(9005, 5, '2026-04-22 08:25:00', 'Uống đủ 5-7 ngày kháng sinh. Súc miệng nước muối ấm. Nghỉ ngơi đủ giấc'),
(9006, 6, '2026-04-23 14:25:00', 'Đeo kính theo đơn. Hạn chế nhìn màn hình liên tục. Nghỉ mắt 20 phút sau mỗi 2 giờ làm việc'),
(9007, 7, '2026-04-25 17:45:00', 'Uống thuốc đúng giờ mỗi ngày. Không bỏ liều. Hạn chế muối < 5g/ngày. Tập thể dục nhẹ'),
(9008, 8, '2026-04-26 08:55:00', 'Nghỉ ngơi đủ giấc 7-8 giờ/đêm. Tập yoga/thiền 30 phút/ngày. Hạn chế caffeine'),
(9009, 11, '2026-04-29 09:25:00', 'Uống nhiều nước bù điện giải. Ăn cháo trắng, bánh mì. Tránh sữa trong 3 ngày'),
(9010, 15, '2026-05-05 08:20:00', 'Rửa mặt 2 lần/ngày bằng sữa rửa mặt dịu nhẹ. Không nặn mụn. Thoa kem chống nắng SPF 30+'),
(9011, 16, '2026-05-06 14:55:00', 'Uống đủ liệu trình kháng sinh 7 ngày. Súc họng nước muối 3 lần/ngày. Uống nhiều nước ấm'),
(9012, 17, '2026-05-07 09:15:00', 'Nhỏ thuốc đều đặn 4 lần/ngày. Không dụi mắt. Rửa tay trước khi nhỏ thuốc. Dùng khăn riêng'),
(9013, 19, '2026-05-09 08:55:00', 'Uống thuốc đúng giờ sáng. Đo huyết áp tại nhà mỗi ngày. Giảm muối. Tái khám sau 4 tuần'),
(9014, 20, '2026-05-10 15:25:00', 'Uống thuốc ngay khi có dấu hiệu đau. Nằm nghỉ phòng tối yên tĩnh. Tránh rượu bia, ánh sáng mạnh'),
(9015, 21, '2026-05-10 08:55:00', 'Tiếp tục duy trì thuốc dạ dày. Ăn đúng bữa, không bỏ bữa. Không ăn khuya'),
(9016, 25, '2026-05-14 15:15:00', 'Tiếp tục bôi kem. Dùng kem dưỡng ẩm không mùi. Tránh xà phòng mạnh'),
(9017, 26, '2026-05-15 08:55:00', 'Uống đủ liệu trình kháng sinh 7 ngày. Uống nhiều nước. Không hút thuốc. Nghỉ ngơi đủ giấc'),
(9018, 29, '2026-05-16 15:30:00', 'Tiếp tục thuốc huyết áp và tim mạch. Theo dõi huyết áp tại nhà. Tập thể dục nhẹ 30 phút/ngày'),
(9019, 30, '2026-05-17 08:25:00', 'Uống thuốc theo đơn. Ngủ đủ giấc. Tránh ánh sáng màn hình 1 giờ trước khi ngủ'),
(9020, 41, '2026-05-22 07:05:00', 'Giảm liều Omeprazole xuống 10mg. Ăn đúng giờ. Tái khám sau 3 tháng'),
(9021, 42, '2026-05-22 07:10:00', 'Không cần dùng thêm thuốc. Theo dõi tại nhà. Tái khám nếu có bất thường'),
(9022, 170, '2026-06-07 21:31:13', ''),
(9023, 173, '2026-06-07 21:36:34', ''),
(9024, 172, '2026-06-07 21:40:51', 'hốc ít thôi'),
(9025, 204, '2026-06-19 01:41:58', ''),
(9026, 205, '2026-06-19 02:35:13', ''),
(9027, 207, '2026-06-19 03:45:34', '123456789'),
(9028, 208, '2026-06-19 03:54:14', ''),
(9029, 210, '2026-06-20 08:45:12', '1234'),
(9030, 211, '2026-06-20 10:45:41', ''),
(9031, 220, '2026-06-20 11:03:40', 'angcbdhtfbd'),
(9032, 224, '2026-06-21 01:08:42', 'dặn test 1234 fjig'),
(9033, 228, '2026-06-21 02:46:16', ''),
(9034, 229, '2026-06-21 03:12:55', 'giv gfig7i'),
(9035, 230, '2026-06-21 03:42:58', ''),
(9036, 231, '2026-06-21 15:56:34', ''),
(9037, 234, '2026-06-29 07:30:10', ''),
(9038, 235, '2026-06-29 08:17:34', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donvitinh`
--

CREATE TABLE `donvitinh` (
  `MaDonVi` smallint(6) NOT NULL,
  `TenDonVi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donvitinh`
--

INSERT INTO `donvitinh` (`MaDonVi`, `TenDonVi`) VALUES
(2, 'Chai'),
(4, 'Gói'),
(5, 'Hộp'),
(8, 'Lần'),
(6, 'mg'),
(7, 'ml'),
(3, 'Ống'),
(9, 'Tuýp'),
(1, 'Viên');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `filehosobenhnhan`
--

CREATE TABLE `filehosobenhnhan` (
  `MaFile` int(11) NOT NULL,
  `MaBenhNhan` int(11) NOT NULL,
  `MaPhieuKham` int(11) DEFAULT NULL,
  `TenFile` varchar(200) NOT NULL,
  `LoaiFile` varchar(50) DEFAULT NULL,
  `DuongDan` varchar(500) NOT NULL,
  `KichThuoc` bigint(20) DEFAULT NULL,
  `NgayTai` datetime NOT NULL DEFAULT current_timestamp(),
  `NguoiTai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

CREATE TABLE `hoadon` (
  `MaHoaDon` int(11) NOT NULL,
  `SoHoaDon` varchar(20) NOT NULL,
  `MaPhieuKham` int(11) NOT NULL,
  `TongTienCLS` decimal(15,2) NOT NULL DEFAULT 0.00,
  `TongTienThuoc` decimal(15,2) NOT NULL DEFAULT 0.00,
  `TongCong` decimal(15,2) NOT NULL,
  `GiamGia` decimal(15,2) NOT NULL DEFAULT 0.00,
  `TongThanhToan` decimal(15,2) NOT NULL,
  `TrangThai` varchar(50) NOT NULL DEFAULT 'CHO_THANH_TOAN',
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayThanhToan` datetime DEFAULT NULL,
  `PhuongThuc` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadon`
--

INSERT INTO `hoadon` (`MaHoaDon`, `SoHoaDon`, `MaPhieuKham`, `TongTienCLS`, `TongTienThuoc`, `TongCong`, `GiamGia`, `TongThanhToan`, `TrangThai`, `NgayTao`, `NgayThanhToan`, `PhuongThuc`) VALUES
(15001, 'HD00001', 1, 0.00, 125000.00, 275000.00, 0.00, 275000.00, '1', '2026-04-15 09:15:00', '2026-04-15 09:30:00', NULL),
(15002, 'HD00002', 2, 0.00, 85000.00, 235000.00, 0.00, 235000.00, '1', '2026-04-16 09:30:00', '2026-04-16 09:45:00', NULL),
(15003, 'HD00003', 3, 0.00, 52000.00, 152000.00, 0.00, 152000.00, '1', '2026-04-17 14:50:00', '2026-04-17 15:05:00', NULL),
(15004, 'HD00004', 4, 400000.00, 0.00, 600000.00, 0.00, 600000.00, '1', '2026-04-18 10:30:00', '2026-04-18 10:50:00', NULL),
(15005, 'HD00005', 5, 0.00, 104000.00, 254000.00, 0.00, 254000.00, '1', '2026-04-19 15:25:00', '2026-04-19 15:40:00', NULL),
(15006, 'HD00006', 6, 0.00, 223000.00, 373000.00, 0.00, 373000.00, '1', '2026-04-22 08:30:00', '2026-04-22 08:50:00', NULL),
(15007, 'HD00007', 7, 0.00, 128000.00, 278000.00, 0.00, 278000.00, '1', '2026-04-23 14:30:00', '2026-04-23 14:50:00', NULL),
(15008, 'HD00008', 8, 0.00, 0.00, 200000.00, 0.00, 200000.00, '1', '2026-04-24 10:00:00', '2026-04-24 10:15:00', NULL),
(15009, 'HD00009', 9, 780000.00, 358000.00, 1288000.00, 0.00, 1288000.00, '1', '2026-04-25 17:45:00', '2026-04-25 18:00:00', NULL),
(15010, 'HD00010', 10, 0.00, 110000.00, 260000.00, 0.00, 260000.00, '1', '2026-04-26 09:00:00', '2026-04-26 09:20:00', NULL),
(15011, 'HD00011', 11, 0.00, 105000.00, 255000.00, 0.00, 255000.00, '1', '2026-04-29 09:30:00', '2026-04-29 09:50:00', NULL),
(15012, 'HD00012', 12, 0.00, 0.00, 150000.00, 0.00, 150000.00, '1', '2026-04-30 14:20:00', '2026-04-30 14:35:00', NULL),
(15013, 'HD00013', 13, 200000.00, 0.00, 300000.00, 0.00, 300000.00, '1', '2026-05-01 11:00:00', '2026-05-01 11:20:00', NULL),
(15014, 'HD00014', 14, 0.00, 0.00, 200000.00, 0.00, 200000.00, '1', '2026-05-02 15:25:00', '2026-05-02 15:45:00', NULL),
(15015, 'HD00015', 15, 0.00, 0.00, 150000.00, 0.00, 150000.00, '1', '2026-05-05 08:25:00', '2026-05-05 08:40:00', NULL),
(15016, 'HD00016', 16, 0.00, 70000.00, 220000.00, 0.00, 220000.00, '1', '2026-05-06 15:00:00', '2026-05-06 15:15:00', NULL),
(15017, 'HD00017', 17, 0.00, 142000.00, 292000.00, 0.00, 292000.00, '1', '2026-05-07 09:20:00', '2026-05-07 09:35:00', NULL),
(15018, 'HD00018', 18, 0.00, 0.00, 200000.00, 0.00, 200000.00, '1', '2026-05-08 14:30:00', '2026-05-08 14:45:00', NULL),
(15019, 'HD00019', 19, 400000.00, 330000.00, 880000.00, 0.00, 880000.00, '1', '2026-05-09 09:00:00', '2026-05-09 09:30:00', NULL),
(15020, 'HD00020', 20, 0.00, 87000.00, 237000.00, 0.00, 237000.00, '1', '2026-05-10 15:30:00', '2026-05-10 15:50:00', NULL),
(15021, 'HD00021', 21, 0.00, 60000.00, 210000.00, 0.00, 210000.00, '1', '2026-05-10 09:00:00', '2026-05-10 09:20:00', NULL),
(15022, 'HD00022', 22, 0.00, 0.00, 150000.00, 0.00, 150000.00, '1', '2026-05-11 09:20:00', '2026-05-11 09:35:00', NULL),
(15023, 'HD00023', 23, 0.00, 0.00, 100000.00, 0.00, 100000.00, '1', '2026-05-12 14:50:00', '2026-05-12 15:05:00', NULL),
(15024, 'HD00024', 24, 400000.00, 0.00, 600000.00, 0.00, 600000.00, '1', '2026-05-13 10:40:00', '2026-05-13 11:00:00', NULL),
(15025, 'HD00025', 25, 0.00, 52000.00, 202000.00, 0.00, 202000.00, '1', '2026-05-14 15:20:00', '2026-05-14 15:40:00', NULL),
(15026, 'HD00026', 26, 0.00, 210000.00, 360000.00, 0.00, 360000.00, '1', '2026-05-15 09:00:00', '2026-05-15 09:20:00', NULL),
(15027, 'HD00027', 27, 0.00, 0.00, 150000.00, 0.00, 150000.00, '1', '2026-05-15 14:25:00', '2026-05-15 14:40:00', NULL),
(15028, 'HD00028', 28, 0.00, 0.00, 200000.00, 0.00, 200000.00, '1', '2026-05-16 09:40:00', '2026-05-16 09:55:00', NULL),
(15029, 'HD00029', 29, 470000.00, 248000.00, 868000.00, 0.00, 868000.00, '1', '2026-05-16 15:35:00', '2026-05-16 15:55:00', NULL),
(15030, 'HD00030', 30, 0.00, 135000.00, 285000.00, 0.00, 285000.00, '1', '2026-05-17 08:30:00', '2026-05-17 08:50:00', NULL),
(15031, 'HD00031', 41, 0.00, 60000.00, 210000.00, 0.00, 210000.00, '1', '2026-05-22 07:10:00', '2026-05-22 07:20:00', NULL),
(15032, 'HD00032', 42, 0.00, 75000.00, 225000.00, 0.00, 225000.00, '1', '2026-05-22 07:15:00', '2026-05-22 07:25:00', NULL),
(15033, 'HD00033', 38, 350000.00, 0.00, 500000.00, 0.00, 500000.00, '1', '2026-05-22 07:44:00', NULL, NULL),
(15034, 'HD00034', 39, 400000.00, 0.00, 550000.00, 0.00, 550000.00, '1', '2026-05-22 08:08:00', NULL, NULL),
(15035, 'HD00035', 40, 350000.00, 0.00, 500000.00, 0.00, 500000.00, '1', '2026-05-22 08:37:00', NULL, NULL),
(15040, 'HD202600036', 224, 572000.00, 1654000.00, 2226000.00, 0.00, 2226000.00, '1', '2026-06-20 21:34:07', '2026-06-20 21:34:07', NULL),
(15042, 'HD202600037', 228, 1582000.00, 2760000.00, 4342000.00, 0.00, 4342000.00, '1', '2026-06-20 22:14:19', '2026-06-20 22:14:19', NULL),
(15043, 'HD202600038', 230, 1352000.00, 2194000.00, 3546000.00, 0.00, 3546000.00, '1', '2026-06-20 22:47:31', '2026-06-20 22:47:31', NULL),
(15044, 'HD202600039', 234, 478000.00, 422000.00, 900000.00, 0.00, 900000.00, '1', '2026-06-29 02:31:10', '2026-06-29 02:31:10', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ketquacls`
--

CREATE TABLE `ketquacls` (
  `MaKetQua` int(11) NOT NULL,
  `MaChiDinh` int(11) NOT NULL,
  `KetQuaText` text DEFAULT NULL,
  `KetLuan` text DEFAULT NULL,
  `FileKetQua` varchar(500) DEFAULT NULL,
  `MaNVThucHien` int(11) DEFAULT NULL,
  `NgayThucHien` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ketquacls`
--

INSERT INTO `ketquacls` (`MaKetQua`, `MaChiDinh`, `KetQuaText`, `KetLuan`, `FileKetQua`, `MaNVThucHien`, `NgayThucHien`) VALUES
(8001, 7001, 'Nhịp xoang đều, tần số 82 lần/phút. ST chênh nhẹ V4-V6. PR: 0.16s, QRS: 0.08s', 'Thay đổi tái cực nhẹ vùng thành bên, cần theo dõi thêm bằng siêu âm tim', 'ecg_6009_001.pdf', 1020, '2026-04-25 16:45:00'),
(8002, 7002, 'Buồng tim kích thước bình thường. EF = 55%. Van tim không hở. Vách liên thất dày nhẹ (12mm)', 'Chức năng tâm thu bảo tồn, phì đại vách liên thất mức độ nhẹ liên quan tăng huyết áp', 'echo_6009_001.pdf', 1021, '2026-04-25 17:00:00'),
(8003, 7003, 'Troponin I: 0.02 ng/mL (ngưỡng bình thường < 0.04)', 'Troponin trong giới hạn bình thường, không có bằng chứng nhồi máu cơ tim cấp', NULL, 1020, '2026-04-25 17:30:00'),
(8004, 7004, 'BPD: 48mm. FL: 35mm. AC: 150mm. EFW: 450g. AFI: 12cm. Nhau bám mặt trước', 'Thai 20 tuần 3 ngày, phát triển phù hợp tuổi thai. Không phát hiện dị tật hình thái. Nhau bình thường', 'us_6004_001.jpg', 1021, '2026-04-18 11:00:00'),
(8005, 7005, 'WBC: 3.2 G/L (giảm), Neu: 45%, PLT: 95 G/L (giảm), Hb: 132 g/L, HCT: 39%', 'Giảm bạch cầu và tiểu cầu – phù hợp sốt xuất huyết Dengue. Cần theo dõi tiểu cầu mỗi ngày', NULL, 1020, '2026-05-01 12:00:00'),
(8006, 7006, 'Màu vàng trong, pH 6.5, tỷ trọng 1.015, protein âm, glucose âm, hồng cầu: 0-1/vi trường', 'Tổng phân tích nước tiểu bình thường', NULL, 1021, '2026-05-01 12:30:00'),
(8007, 7007, 'Glucose: 5.8 mmol/L, Urea: 6.2 mmol/L, Creatinin: 85 µmol/L', 'Chức năng thận bình thường. Đường huyết bình thường', NULL, 1020, '2026-05-09 10:00:00'),
(8008, 7008, 'Cholesterol TP: 6.1 mmol/L (tăng nhẹ). HDL: 1.2. LDL: 4.0 (tăng). TG: 2.0', 'Rối loạn lipid máu – LDL tăng, cần điều trị statin và thay đổi chế độ ăn', NULL, 1020, '2026-05-09 10:15:00'),
(8009, 7009, 'BPD: 48mm. FL: 36mm. AC: 152mm. EFW: 455g. AFI: 13cm. Tim thai 4 buồng bình thường', 'Thai 20 tuần 2 ngày, không dị tật hình thái, chỉ số phát triển bình thường', 'us_6024_001.jpg', 1021, '2026-05-13 11:00:00'),
(8010, 7010, 'Nhịp xoang đều 80 lần/phút. Không loạn nhịp. PR 0.15s. QRS 0.08s', 'Điện tim bình thường, nhịp xoang đều', 'ecg_6029_001.pdf', 1020, '2026-05-16 15:45:00'),
(8011, 7011, 'NT-proBNP: 185 pg/mL (bình thường < 125 pg/mL, tăng nhẹ)', 'NT-proBNP tăng nhẹ, có thể do tăng huyết áp lâu năm. Chưa đủ tiêu chuẩn suy tim', NULL, 1021, '2026-05-16 16:00:00'),
(8012, 7025, 'Glucose: 5.2 mmol/L\nCholesterol: 4.8 mmol/L\nAST (GOT): 25 U/L\nALT (GPT): 30 U/L', '123', NULL, 1020, '2026-06-08 17:01:17'),
(8013, 7023, '123', '123', NULL, 1020, '2026-06-19 04:02:36'),
(8014, 7024, '', '11111', 'cls_7024_1781853988_1bad740742679dd578f2bfd107bf9530.png', 1020, '2026-06-19 14:26:28'),
(8017, 7029, '1', '1', NULL, 1020, '2026-06-20 00:20:13'),
(8018, 7030, '', '1', 'cls_7030_1781889620_5502463febfda6bca118e42b2c3a56f3.jpeg', 1020, '2026-06-20 00:20:20'),
(8019, 7031, 'Glucose: 5.2 mmol/L\nCholesterol: 4.8 mmol/L\nAST (GOT): 25 U/L\nALT (GPT): 30 U/L', '1', NULL, 1020, '2026-06-20 00:21:13'),
(8020, 7032, '1', '1', NULL, 1020, '2026-06-20 00:38:11'),
(8021, 7033, '', '1', 'cls_7033_1781890698_4aa9c89a3262452d5b47387960d57cff.jpeg', 1020, '2026-06-20 00:38:18'),
(8022, 7034, 'pH: 6.5\nTỷ trọng: 1.0\nProtein: 1 mg/dL\nGlucose: 1 mg/dL\nHồng cầu: 1 /vi trường', '1', NULL, 1020, '2026-06-20 00:38:34'),
(8023, 7038, '1', '1', NULL, 1001, '2026-06-20 10:44:49'),
(8024, 7039, 'Glucose: 5 mmol/L\nCholesterol: 5 mmol/L\nAST (GOT): 5 U/L\nALT (GPT): 5 U/L', '1', NULL, 1001, '2026-06-20 10:44:58'),
(8025, 7040, '1', '1', NULL, 1001, '2026-06-20 11:02:18'),
(8026, 7041, '', '1', 'cls_7041_1781928145_740d46ab3590f6f7329d8eadf86dec35.jpeg', 1001, '2026-06-20 11:02:25'),
(8027, 7042, '', '1', 'cls_7042_1781928158_cae5340396d0f4da3d333da0a65fd0ae.png', 1001, '2026-06-20 11:02:38'),
(8028, 7043, '', '123', 'cls_7043_1781974345_d213b1b59769412e90d8874148d72a15.jpeg', 1001, '2026-06-20 23:52:25'),
(8029, 7044, '', 'hylo uene56e', 'cls_7044_1781975053_24c5683f75272dc8ab4459c9f032da42.jpeg', 1001, '2026-06-21 00:04:13'),
(8030, 7028, '', 'dvbvdffgf', 'cls_7028_1781978046_b3cdf004b392cc28fb4b49251f76eb14.jpeg', 1001, '2026-06-21 00:54:06'),
(8031, 7048, '', 'hong sao', 'cls_7048_1781984688_9c30a59d64b641564d19d91c64ac05ed.jpeg', 1001, '2026-06-21 02:44:48'),
(8032, 7047, '', 'có sao', 'cls_7047_1781984703_7307a5c19c7cee537f6f8f9908e17ee8.png', 1001, '2026-06-21 02:45:03'),
(8033, 7046, 'Glucose: 5..2 mmol/L\nCholesterol: 5 mmol/L\nAST (GOT): 5 U/L\nALT (GPT): 30 U/L', 'e rty 56', NULL, 1001, '2026-06-21 02:45:19'),
(8034, 7049, '', '23332nb  eggf  g ry erqr wrh h', 'cls_7049_1781988042_78b96b791a6c0a9e500b5357855bc583.jpeg', 1001, '2026-06-21 03:40:42'),
(8035, 7050, 'Glucose: 5..2 mmol/L\nCholesterol: 5 mmol/L\nAST (GOT): 5 U/L\nALT (GPT): 30 U/L', '134', NULL, 1001, '2026-06-21 03:42:27'),
(8036, 7051, '', '123', 'cls_7051_1782031969_4d450232b637d84bd276a3deac5e5d09.jpeg', 1001, '2026-06-21 15:52:49'),
(8037, 7054, '', 'ngu dũng ngu', 'cls_7054_1782692977_435817339717af1d2ef5dbe7d70578f5.jpg', 1001, '2026-06-29 07:29:37'),
(8038, 7055, '1', '1', NULL, 1001, '2026-06-29 08:07:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichhen`
--

CREATE TABLE `lichhen` (
  `MaLichHen` int(11) NOT NULL,
  `MaBenhNhan` int(11) NOT NULL,
  `NgayHen` date NOT NULL,
  `GioHen` time NOT NULL,
  `MaTrangThai` smallint(6) NOT NULL,
  `GhiChu` text DEFAULT NULL,
  `NguoiTao` int(11) NOT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichhen`
--

INSERT INTO `lichhen` (`MaLichHen`, `MaBenhNhan`, `NgayHen`, `GioHen`, `MaTrangThai`, `GhiChu`, `NguoiTao`, `NgayTao`, `NgayCapNhat`) VALUES
(1, 125, '2026-06-19', '08:00:00', 2, NULL, 137, '2026-06-05 22:59:00', '2026-06-05 23:11:38'),
(2, 125, '2026-06-13', '08:00:00', 2, NULL, 137, '2026-06-06 02:33:13', '2026-06-06 03:40:53'),
(5, 125, '2026-06-07', '08:00:00', 2, NULL, 137, '2026-06-06 15:00:10', '2026-06-06 15:00:33'),
(6, 125, '2026-06-06', '00:00:00', 1, 'đau họng nhiều ngày', 137, '2026-06-06 16:14:18', NULL),
(7, 125, '2026-06-06', '00:00:00', 1, 'asdfghjkl;\'', 137, '2026-06-06 16:28:20', NULL),
(8, 125, '2026-06-06', '00:00:00', 1, 'q111111111111111111', 137, '2026-06-06 16:42:20', NULL),
(9, 125, '2026-06-06', '00:00:00', 1, '11111111111111111111111', 137, '2026-06-06 16:48:05', NULL),
(10, 125, '2026-06-06', '00:00:00', 1, 'bệnh nói nhiều', 137, '2026-06-06 16:50:25', NULL),
(11, 121, '2026-06-06', '00:00:00', 1, 'qqqqqqqqqqqqqqqqqqqq', 135, '2026-06-06 23:04:53', NULL),
(12, 121, '2026-06-06', '00:00:00', 1, 'sssssssssssssssssss', 135, '2026-06-06 23:43:07', NULL),
(13, 121, '2026-06-06', '00:00:00', 1, '1asdfghjkjhgf', 135, '2026-06-07 00:01:32', NULL),
(14, 121, '2026-06-06', '00:00:00', 1, 'aaaaaaaaaaaaaaa', 135, '2026-06-07 00:03:46', NULL),
(15, 121, '2026-06-06', '00:00:00', 1, 'đau họng nhiều ngày', 135, '2026-06-07 00:36:12', NULL),
(16, 121, '2026-06-06', '00:00:00', 1, 'aaaaaaaaaaaaaaaaa', 135, '2026-06-07 00:54:45', NULL),
(17, 121, '2026-06-06', '00:00:00', 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 135, '2026-06-07 01:04:38', NULL),
(18, 121, '2026-06-06', '00:00:00', 1, 'aaaaaaaaaaaaaaaaaaaaaaq', 135, '2026-06-07 01:25:31', NULL),
(19, 121, '2026-06-08', '00:00:00', 1, '12345678912345678', 135, '2026-06-08 15:53:07', NULL),
(20, 121, '2026-06-20', '00:00:00', 1, 'tgvfđsegbhdhdhfhss', 135, '2026-06-20 10:59:19', NULL),
(21, 121, '2026-06-20', '00:00:00', 1, 'đau họng qorh d f', 135, '2026-06-21 02:36:43', NULL),
(22, 121, '2026-06-20', '00:00:00', 1, '56nbrt rtyu', 135, '2026-06-21 03:10:16', NULL),
(23, 121, '2026-06-28', '00:00:00', 1, 'ffffffffffffffffffff', 135, '2026-06-29 02:13:13', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichlamviec`
--

CREATE TABLE `lichlamviec` (
  `MaLich` int(11) NOT NULL,
  `MaBacSi` int(11) NOT NULL,
  `NgayLam` date NOT NULL,
  `Ca` varchar(20) NOT NULL CHECK (`Ca` in ('SANG','CHIEU','TOI')),
  `SoBenhNhanToiDa` smallint(6) NOT NULL DEFAULT 20,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichlamviec`
--

INSERT INTO `lichlamviec` (`MaLich`, `MaBacSi`, `NgayLam`, `Ca`, `SoBenhNhanToiDa`, `GhiChu`) VALUES
(4001, 1003, '2026-05-01', 'SANG', 20, NULL),
(4002, 1003, '2026-05-01', 'CHIEU', 15, NULL),
(4003, 1004, '2026-05-01', 'SANG', 20, NULL),
(4004, 1005, '2026-05-01', 'CHIEU', 15, NULL),
(4005, 1006, '2026-05-02', 'SANG', 18, NULL),
(4006, 1007, '2026-05-02', 'CHIEU', 15, NULL),
(4007, 1008, '2026-05-03', 'SANG', 20, NULL),
(4008, 1009, '2026-05-03', 'CHIEU', 15, NULL),
(4009, 1010, '2026-05-04', 'SANG', 18, NULL),
(4010, 1011, '2026-05-04', 'CHIEU', 15, NULL),
(4011, 1012, '2026-05-05', 'SANG', 20, NULL),
(4012, 1003, '2026-05-05', 'CHIEU', 15, NULL),
(4013, 1004, '2026-05-06', 'SANG', 20, NULL),
(4014, 1005, '2026-05-06', 'CHIEU', 15, NULL),
(4015, 1006, '2026-05-07', 'SANG', 18, NULL),
(4016, 1007, '2026-05-07', 'CHIEU', 15, NULL),
(4017, 1008, '2026-05-08', 'SANG', 20, NULL),
(4018, 1009, '2026-05-08', 'CHIEU', 15, NULL),
(4019, 1010, '2026-05-09', 'SANG', 18, NULL),
(4020, 1011, '2026-05-09', 'CHIEU', 15, NULL),
(4021, 1012, '2026-05-10', 'SANG', 20, NULL),
(4022, 1003, '2026-05-10', 'CHIEU', 15, NULL),
(4023, 1004, '2026-05-11', 'SANG', 20, NULL),
(4024, 1005, '2026-05-11', 'CHIEU', 15, NULL),
(4025, 1006, '2026-05-12', 'SANG', 18, NULL),
(4026, 1007, '2026-05-12', 'CHIEU', 15, NULL),
(4027, 1008, '2026-05-13', 'SANG', 20, NULL),
(4028, 1009, '2026-05-13', 'CHIEU', 15, NULL),
(4029, 1010, '2026-05-14', 'SANG', 18, NULL),
(4030, 1011, '2026-05-14', 'CHIEU', 15, NULL),
(4031, 1012, '2026-05-15', 'SANG', 20, NULL),
(4032, 1003, '2026-05-15', 'CHIEU', 15, NULL),
(4033, 1004, '2026-05-16', 'SANG', 20, NULL),
(4034, 1005, '2026-05-16', 'CHIEU', 15, NULL),
(4035, 1006, '2026-05-17', 'SANG', 18, NULL),
(4036, 1007, '2026-05-17', 'CHIEU', 15, NULL),
(4037, 1008, '2026-05-18', 'SANG', 20, NULL),
(4038, 1009, '2026-05-18', 'CHIEU', 15, NULL),
(4039, 1010, '2026-05-19', 'SANG', 18, NULL),
(4040, 1011, '2026-05-19', 'CHIEU', 15, NULL),
(4041, 1012, '2026-05-20', 'SANG', 20, NULL),
(4042, 1003, '2026-05-20', 'CHIEU', 15, NULL),
(4043, 1004, '2026-05-21', 'SANG', 20, NULL),
(4044, 1005, '2026-05-21', 'CHIEU', 15, NULL),
(4045, 1006, '2026-05-22', 'SANG', 18, NULL),
(4046, 1007, '2026-05-22', 'CHIEU', 15, NULL),
(4047, 1008, '2026-05-23', 'SANG', 20, NULL),
(4048, 1009, '2026-05-23', 'CHIEU', 15, NULL),
(4049, 1010, '2026-05-24', 'SANG', 18, NULL),
(4050, 1011, '2026-05-24', 'CHIEU', 15, NULL),
(4051, 1012, '2026-06-01', 'SANG', 20, NULL),
(4052, 1003, '2026-06-01', 'CHIEU', 15, NULL),
(4053, 1004, '2026-06-02', 'SANG', 20, NULL),
(4054, 1005, '2026-06-02', 'CHIEU', 15, NULL),
(4055, 1006, '2026-06-03', 'SANG', 18, NULL),
(4056, 1007, '2026-06-03', 'CHIEU', 15, NULL),
(4057, 1008, '2026-06-04', 'SANG', 20, NULL),
(4058, 1009, '2026-06-04', 'CHIEU', 15, NULL),
(4059, 1010, '2026-06-05', 'SANG', 18, NULL),
(4060, 1011, '2026-06-05', 'CHIEU', 15, NULL),
(4061, 1012, '2026-06-06', 'SANG', 20, NULL),
(4062, 1003, '2026-06-06', 'CHIEU', 15, NULL),
(4063, 1004, '2026-06-07', 'SANG', 20, NULL),
(4064, 1005, '2026-06-07', 'CHIEU', 15, NULL),
(4065, 1006, '2026-06-08', 'SANG', 18, NULL),
(4066, 1007, '2026-06-08', 'CHIEU', 15, NULL),
(4067, 1008, '2026-06-09', 'SANG', 20, NULL),
(4068, 1009, '2026-06-09', 'CHIEU', 15, NULL),
(4069, 1010, '2026-06-10', 'SANG', 18, NULL),
(4070, 1011, '2026-06-10', 'CHIEU', 15, NULL),
(4071, 1012, '2026-06-11', 'SANG', 20, NULL),
(4072, 1003, '2026-06-11', 'CHIEU', 15, NULL),
(4073, 1004, '2026-06-12', 'SANG', 20, NULL),
(4074, 1005, '2026-06-12', 'CHIEU', 15, NULL),
(4075, 1006, '2026-06-13', 'SANG', 18, NULL),
(4076, 1007, '2026-06-13', 'CHIEU', 15, NULL),
(4077, 1008, '2026-06-14', 'SANG', 20, NULL),
(4078, 1009, '2026-06-14', 'CHIEU', 15, NULL),
(4079, 1010, '2026-06-15', 'SANG', 18, NULL),
(4080, 1011, '2026-06-15', 'CHIEU', 15, NULL),
(4081, 1012, '2026-06-16', 'SANG', 20, NULL),
(4082, 1003, '2026-06-16', 'CHIEU', 15, NULL),
(4083, 1004, '2026-06-17', 'SANG', 20, NULL),
(4084, 1005, '2026-06-17', 'CHIEU', 15, NULL),
(4085, 1006, '2026-06-18', 'SANG', 18, NULL),
(4086, 1007, '2026-06-18', 'CHIEU', 15, NULL),
(4087, 1008, '2026-06-19', 'SANG', 20, NULL),
(4088, 1009, '2026-06-19', 'CHIEU', 15, NULL),
(4089, 1010, '2026-06-20', 'SANG', 18, NULL),
(4090, 1011, '2026-06-20', 'CHIEU', 15, NULL),
(4091, 1012, '2026-06-21', 'SANG', 20, NULL),
(4092, 1003, '2026-06-21', 'CHIEU', 15, NULL),
(4093, 1004, '2026-06-22', 'SANG', 20, NULL),
(4094, 1005, '2026-06-22', 'CHIEU', 15, NULL),
(4095, 1006, '2026-06-23', 'SANG', 18, NULL),
(4096, 1007, '2026-06-23', 'CHIEU', 15, NULL),
(4097, 1008, '2026-06-24', 'SANG', 20, NULL),
(4098, 1009, '2026-06-24', 'CHIEU', 15, NULL),
(4099, 1010, '2026-06-25', 'SANG', 18, NULL),
(4100, 1011, '2026-06-25', 'CHIEU', 15, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaiclsn`
--

CREATE TABLE `loaiclsn` (
  `MaLoaiCLS` smallint(6) NOT NULL,
  `TenLoaiCLS` varchar(100) NOT NULL,
  `DonGia` decimal(15,2) DEFAULT 0.00,
  `MoTa` varchar(255) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loaiclsn`
--

INSERT INTO `loaiclsn` (`MaLoaiCLS`, `TenLoaiCLS`, `DonGia`, `MoTa`, `TrangThai`) VALUES
(1, 'Xét nghiệm máu', 780000.00, 'Công thức máu, sinh hóa,...', 1),
(2, 'Xét nghiệm nước tiểu', 535000.00, 'Tổng phân tích nước tiểu', 1),
(3, 'X-quang', 572000.00, 'Chụp X-quang các vùng', 1),
(4, 'Siêu âm', 230000.00, 'Siêu âm ổ bụng, tim, thai,...', 1),
(5, 'Điện tim (ECG)', 645000.00, 'Ghi điện tâm đồ', 1),
(6, 'MRI', 478000.00, 'Cộng hưởng từ', 1),
(7, 'Nội soi', 786000.00, 'Nội soi dạ dày, đại tràng,...', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNhanVien` int(11) NOT NULL,
  `MaTaiKhoan` int(11) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `AnhThe` varchar(255) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` char(1) DEFAULT NULL CHECK (`GioiTinh` in ('M','F','O')),
  `CCCD` varchar(20) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DiaChi` varchar(500) DEFAULT NULL,
  `MaChuyenKhoa` smallint(6) DEFAULT NULL,
  `BangCap` varchar(150) DEFAULT NULL,
  `SoChungChi` varchar(50) DEFAULT NULL,
  `NgayVaoLam` date DEFAULT NULL,
  `DangHoatDong` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`MaNhanVien`, `MaTaiKhoan`, `HoTen`, `AnhThe`, `NgaySinh`, `GioiTinh`, `CCCD`, `SoDienThoai`, `Email`, `DiaChi`, `MaChuyenKhoa`, `BangCap`, `SoChungChi`, `NgayVaoLam`, `DangHoatDong`) VALUES
(1001, 101, 'Nguyễn Văn Admin', 'avatar_6a118f00c8615.jpg', '1975-03-15', 'M', '079075001234', '0905111111', 'admin@phongkham.vn', '123 Lê Lợi, Quy Nhơn', NULL, 'Thạc sĩ Quản trị Y tế', NULL, '2025-01-01', 1),
(1002, 102, 'Trần Thị Quản Lý', 'avatar_6a118f00c8615.jpg', '1980-07-20', 'F', '079080002345', '0905222222', 'quanly@phongkham.vn', '456 Trần Hưng Đạo, Quy Nhơn', NULL, 'Cử nhân Quản trị Kinh doanh', NULL, '2025-01-01', 1),
(1003, 103, 'BS. Nguyễn Văn An', 'avatar_6a118f00c8615.jpg', '1985-05-10', 'M', '079085003456', '0905333333', 'bs.nguyenvana@phongkham.vn', '12 Nguyễn Huệ, Quy Nhơn', 1, 'Bác sĩ đa khoa', 'BS001234', '2025-01-05', 1),
(1004, 104, 'BS. Trần Thị Bình', 'avatar_6a118f00c8615.jpg', '1987-08-15', 'F', '079087004567', '0905444444', 'bs.tranthib@phongkham.vn', '34 Lê Hồng Phong, Quy Nhơn', 2, 'Bác sĩ Ngoại khoa', 'BS002345', '2025-01-05', 1),
(1005, 105, 'BS. Lê Hoàng Công', 'avatar_6a118f00c8615.jpg', '1983-11-20', 'M', '079083005678', '0905555555', 'bs.lehoangc@phongkham.vn', '56 Ngô Mây, Quy Nhơn', 3, 'Bác sĩ Nhi khoa', 'BS003456', '2025-01-10', 1),
(1006, 106, 'Phạm Minh Tuấn Dũng', 'avatar_6a118f00c8615.jpg', '1986-02-25', 'M', '079086006789', '0905666666', 'bs.phamminhtd@phongkham.vn', '78 An Dương Vương, Quy Nhơn', 4, 'Bác sĩ Sản Phụ khoa', 'BS004567', '2025-01-10', 1),
(1007, 107, 'BS. Hoàng Thu Hà', 'avatar_6a118f00c8615.jpg', '1988-09-30', 'F', '079088007890', '0905777777', 'bs.hoangthue@phongkham.vn', '90 Xuân Diệu, Quy Nhơn', 5, 'Bác sĩ Da liễu', 'BS005678', '2025-01-15', 1),
(1008, 108, 'BS. Vũ Tiến Phong', 'avatar_6a118f00c8615.jpg', '1984-12-05', 'M', '079084008901', '0905888888', 'bs.vutienf@phongkham.vn', '102 Hoàng Văn Thụ, Quy Nhơn', 6, 'Bác sĩ Tai Mũi Họng', 'BS006789', '2025-02-01', 1),
(1009, 109, 'Đặng Thùy Giang', 'avatar_6a118f00c8615.jpg', '1989-04-12', 'F', '079089009012', '0905999999', 'bs.dangthuyg@phongkham.vn', '134 Trần Hưng Đạo, Quy Nhơn', NULL, '', 'BS007890', '2025-02-01', 1),
(1010, 110, 'BS. Bùi Quốc Hưng', 'avatar_6a118f00c8615.jpg', '1982-06-18', 'M', '079082010123', '0906111111', 'bs.buiquoch@phongkham.vn', '156 Nguyễn Thái Học, Quy Nhơn', 8, 'Bác sĩ Răng Hàm Mặt', 'BS008901', '2025-02-15', 1),
(1011, 111, 'Phan Lê Hải', 'avatar_6a118f00c8615.jpg', '1987-10-22', 'M', '079087011234', '0906222222', 'bs.phanlei@phongkham.vn', '178 Đống Đa, Quy Nhơn', 9, 'Bác sĩ Tim mạch', 'BS009012', '2025-03-01', 1),
(1012, 112, 'Đỗ Hoàng Khánh', 'avatar_6a118f00c8615.jpg', '1986-01-28', 'M', '079086012345', '0906333333', 'bs.dohoangk@phongkham.vn', '200 Tây Sơn, Quy Nhơn', 10, 'Bác sĩ Thần kinh', 'BS010123', '2025-03-01', 1),
(1013, 113, 'Nguyễn Thị Lan', 'avatar_6a118f00c8615.jpg', '1995-03-10', 'F', '079095013456', '0906444444', 'letan01@phongkham.vn', '15 Lý Thường Kiệt, Quy Nhơn', NULL, 'Trung cấp Y tế', NULL, '2025-01-05', 1),
(1014, 114, 'Trần Văn Minh', 'avatar_6a118f00c8615.jpg', '1996-07-15', 'M', '079096014567', '0906555555', 'letan02@phongkham.vn', '27 Hùng Vương, Quy Nhơn', NULL, 'Trung cấp Y tế', NULL, '2025-02-01', 1),
(1015, 115, 'Lê Thị Nga', 'avatar_6a118f00c8615.jpg', '1994-11-20', 'F', '079094015678', '0906666666', 'letan03@phongkham.vn', '39 Hai Bà Trưng, Quy Nhơn', NULL, 'Cao đẳng Y tế', NULL, '2025-03-01', 1),
(1016, 116, 'Phạm Văn Ơi', 'avatar_6a118f00c8615.jpg', '1997-05-25', 'M', '079097016789', '0906777777', 'letan04@phongkham.vn', '51 Trưng Nữ Vương, Quy Nhơn', NULL, 'Trung cấp Y tế', NULL, '2025-04-01', 1),
(1017, 117, 'DS. Vũ Thị Phương', 'avatar_6a118f00c8615.jpg', '1990-02-14', 'F', '079090017890', '0906888888', 'duocsi01@phongkham.vn', '63 Phan Bội Châu, Quy Nhơn', NULL, 'Dược sĩ Đại học', 'DS001234', '2025-01-10', 1),
(1018, 118, 'DS. Đặng Văn Quang', 'avatar_6a118f00c8615.jpg', '1992-06-20', 'M', '079092018901', '0906999999', 'duocsi02@phongkham.vn', '75 Nguyễn Văn Cừ, Quy Nhơn', NULL, 'Dược sĩ Đại học', 'DS002345', '2025-02-01', 1),
(1019, 119, 'Bùi Thị Rạng', 'avatar_6a156dbce2863.jpeg', '1991-10-08', 'F', '079091019012', '0907111111', 'duocsi03@phongkham.vn', '87 Lê Duẩn, Quy Nhơn', NULL, 'Dược sĩ Cao đẳng', 'DS003456', '2025-03-15', 1),
(1020, 120, 'KTV. Phan Văn Sáng', 'avatar_6a118f00c8615.jpg', '1993-04-12', 'M', '079093020123', '0907222222', 'ktv01@phongkham.vn', '99 Quang Trung, Quy Nhơn', NULL, 'Cao đẳng Kỹ thuật Y học', 'KTV001234', '2025-01-15', 1),
(1021, 121, 'KTV. Đỗ Thị Tâm', 'avatar_6a118f00c8615.jpg', '1994-08-18', 'F', '079094021234', '0907333333', 'ktv02@phongkham.vn', '111 Điện Biên Phủ, Quy Nhơn', NULL, 'Cao đẳng Kỹ thuật Y học', 'KTV002345', '2025-02-10', 1),
(1022, 122, 'Nguyễn Văn Uy', 'avatar_6a118f00c8615.jpg', '1995-12-24', 'M', '079095022345', '0907444444', 'ktv03@phongkham.vn', '123 Lê Thánh Tông, Quy Nhơn', NULL, 'Trung cấp Y tế', 'KTV003456', '2025-03-20', 1),
(1023, 123, 'Quốc Việt 1', 'avatar_6a20fbda0035d.png', '2023-09-19', 'M', '07777771234', '0987654321', 'receptionist@clinic.com', 'Quy Nhơn', NULL, 'Thạc Sĩ', NULL, '2026-05-23', 1),
(1026, 136, '12345678', 'avatar_6a20fb70ef00a.jpg', '2026-06-30', 'F', '0777777777777', '1234567890', 'rec23456eptionist@clinic.com', '1234567', NULL, 'Dược sĩ Cao đẳng', NULL, '2026-06-04', 1),
(1027, 152, '123', 'avatar_6a40837c35572.png', '2022-10-11', 'F', '07777771234123', '0987654123', '12rrr3@gmail.com', 'Quy Nhơn', NULL, 'Thạc Sĩ', NULL, '2026-06-28', 1),
(1028, 153, 'Hoàng DEV', 'avatar_6a41d81ddfd45.jpg', '2026-06-02', 'M', '1', '1', '11123@gmail.com', '123 Lê Thánh Tông, Quy Nhơn', NULL, '1', NULL, '2026-06-29', 1),
(1029, 154, 'Hà Phước Dũng', 'avatar_6a41b9e29803d.jpg', '2026-06-15', 'F', '07777771234rr', '0987654321', 'tranhoang160111805@gmail.com', '87 Lê Duẩn, Quy Nhơn', NULL, 'Dược sĩ Cao đẳng', NULL, '2026-06-29', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhatkyhoatdong`
--

CREATE TABLE `nhatkyhoatdong` (
  `MaNhatKy` bigint(20) NOT NULL,
  `MaTaiKhoan` int(11) NOT NULL,
  `HanhDong` varchar(100) NOT NULL,
  `TenBang` varchar(100) DEFAULT NULL,
  `MaBanGhi` int(11) DEFAULT NULL,
  `GiaTriCu` text DEFAULT NULL,
  `GiaTriMoi` text DEFAULT NULL,
  `DiaChiIP` varchar(45) DEFAULT NULL,
  `ThoiGian` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhatkyhoatdong`
--

INSERT INTO `nhatkyhoatdong` (`MaNhatKy`, `MaTaiKhoan`, `HanhDong`, `TenBang`, `MaBanGhi`, `GiaTriCu`, `GiaTriMoi`, `DiaChiIP`, `ThoiGian`) VALUES
(21001, 1, 'DANG_NHAP', NULL, NULL, NULL, NULL, '192.168.1.1', '2026-05-22 08:30:00'),
(21002, 2, 'DANG_NHAP', NULL, NULL, NULL, NULL, '192.168.1.2', '2026-05-22 07:00:00'),
(21003, 3, 'DANG_NHAP', NULL, NULL, NULL, NULL, '192.168.1.10', '2026-05-22 07:15:00'),
(21004, 4, 'DANG_NHAP', NULL, NULL, NULL, NULL, '192.168.1.20', '2026-05-22 07:45:00'),
(21005, 4, 'TAO_PHIEU_KHAM', 'PHIEUKHAM', 6031, NULL, '{\"MaPhieuKhamCode\":\"PK00031\",\"MaBenhNhan\":2011}', '192.168.1.20', '2026-05-22 07:46:00'),
(21006, 4, 'TAO_PHIEU_KHAM', 'PHIEUKHAM', 6032, NULL, '{\"MaPhieuKhamCode\":\"PK00032\",\"MaBenhNhan\":2012}', '192.168.1.20', '2026-05-22 07:50:00'),
(21007, 4, 'TAO_PHIEU_KHAM', 'PHIEUKHAM', 6033, NULL, '{\"MaPhieuKhamCode\":\"PK00033\",\"MaBenhNhan\":2013}', '192.168.1.20', '2026-05-22 08:31:00'),
(21008, 6, 'CAP_NHAT_PHIEU_KHAM', 'PHIEUKHAM', 6034, '{\"MaTrangThai\":1}', '{\"MaTrangThai\":3,\"ThongSoSinhTon\":\"{...}\"}', '192.168.1.11', '2026-05-22 08:00:00'),
(21009, 7, 'CAP_NHAT_PHIEU_KHAM', 'PHIEUKHAM', 6035, '{\"MaTrangThai\":1}', '{\"MaTrangThai\":3,\"ThongSoSinhTon\":\"{...}\"}', '192.168.1.12', '2026-05-22 08:15:00'),
(21010, 11, 'KE_DON_THUOC', 'DONTHUOC', 9007, NULL, '{\"MaPhieuKham\":6009,\"SoThuoc\":4}', '192.168.1.16', '2026-04-25 17:45:00'),
(21011, 3, 'KE_DON_THUOC', 'DONTHUOC', 9001, NULL, '{\"MaPhieuKham\":6001,\"SoThuoc\":3}', '192.168.1.10', '2026-04-15 09:10:00'),
(21012, 17, 'XUAT_THUOC', 'PHIEUXUAT', 13001, NULL, '{\"SoPhieuXuat\":\"PX00001\",\"MaBenhNhan\":2001,\"TongTien\":125000}', '192.168.1.30', '2026-04-15 09:30:00'),
(21013, 17, 'NHAP_THUOC', 'PHIEUNHAP', 11001, NULL, '{\"SoPhieuNhap\":\"PN00001\",\"NhaCungCap\":\"Cty Phương Nam\",\"TongTien\":45000000}', '192.168.1.30', '2025-01-10 08:00:00'),
(21014, 13, 'THANH_TOAN', 'HOADON', 15001, '{\"TrangThai\":\"CHO_THANH_TOAN\"}', '{\"TrangThai\":\"DA_THANH_TOAN\",\"NgayThanhToan\":\"2026-04-15 09:30:00\"}', '192.168.1.20', '2026-04-15 09:30:00'),
(21015, 2, 'DUYET_DANH_GIA', 'DANHGIADICHVU', 20001, '{\"DaDuyet\":0}', '{\"DaDuyet\":1}', '192.168.1.2', '2026-04-16 09:00:00'),
(21016, 1, 'CAP_NHAT_CAU_HINH', 'CAUHINHHETHONG', NULL, '{\"ten_phong_kham\":\"Phòng Khám Đa Khoa\"}', '{\"ten_phong_kham\":\"Phòng Khám Đa Khoa Tư Nhân An Tâm\"}', '192.168.1.1', '2026-01-15 10:00:00'),
(21017, 6, 'CHIDINHCLS', 'CHIDINHCLS', 7004, NULL, '{\"MaPhieuKham\":6004,\"TenDichVu\":\"Siêu âm hình thái thai nhi\"}', '192.168.1.11', '2026-04-18 10:30:00'),
(21018, 13, 'HUY_PHIEU_KHAM', 'PHIEUKHAM', 6043, '{\"MaTrangThai\":1}', '{\"MaTrangThai\":6,\"GhiChu\":\"Bệnh nhân không đến\"}', '192.168.1.20', '2026-05-22 09:35:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieukham`
--

CREATE TABLE `phieukham` (
  `MaPhieuKham` int(11) NOT NULL,
  `STT` int(11) DEFAULT NULL,
  `MaPhieuKhamCode` varchar(20) NOT NULL,
  `MaBenhNhan` int(11) NOT NULL,
  `MaBacSi` int(11) DEFAULT NULL,
  `MaChuyenKhoa` smallint(6) DEFAULT NULL,
  `NgayKham` date NOT NULL,
  `GioKham` time NOT NULL,
  `GioTiepNhan` time DEFAULT NULL,
  `LyDoKham` text DEFAULT NULL,
  `TrieuChung` text DEFAULT NULL,
  `TienSuBenh` text DEFAULT NULL,
  `ThongSoSinhTon` text DEFAULT NULL,
  `ChanDoanSoBo` text DEFAULT NULL,
  `ChanDoan` text DEFAULT NULL,
  `LoiDanBS` text DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `MaTrangThai` smallint(6) NOT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT NULL,
  `MaLichHen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieukham`
--

INSERT INTO `phieukham` (`MaPhieuKham`, `STT`, `MaPhieuKhamCode`, `MaBenhNhan`, `MaBacSi`, `MaChuyenKhoa`, `NgayKham`, `GioKham`, `GioTiepNhan`, `LyDoKham`, `TrieuChung`, `TienSuBenh`, `ThongSoSinhTon`, `ChanDoanSoBo`, `ChanDoan`, `LoiDanBS`, `GhiChu`, `MaTrangThai`, `NgayTao`, `NgayCapNhat`, `MaLichHen`) VALUES
(1, NULL, 'PK00001', 121, 1004, 2, '2026-05-20', '08:00:00', '01:30:47', 'Khám định kỳ', 'Không có triệu chứng', 'Không', '{\"nhiet_do\":78,\"huyet_ap\":78,\"nhip_tim\":78,\"spo2\":78,\"chieu_cao\":78,\"can_nang\":78,\"bmi\":128.2}', NULL, 'Bình thường', NULL, '78', 2, '2026-06-18 00:00:00', '2026-06-05 08:41:25', NULL),
(2, NULL, 'PK00002', 2, 1002, 2, '2026-05-20', '08:15:00', '01:30:47', 'Đau bụng cấp', 'Đau quặn vùng hạ sườn phải', 'Đau dạ dày', '{\"nhiet_do\":9,\"huyet_ap\":9,\"nhip_tim\":9,\"spo2\":9,\"chieu_cao\":9,\"can_nang\":9,\"bmi\":1111.1}', NULL, 'Nghi viêm ruột thừa', NULL, '9', 6, '2026-06-18 00:00:00', '2026-05-28 14:33:47', NULL),
(3, NULL, 'PK00003', 3, 1003, 3, '2026-05-20', '08:30:00', '01:30:47', 'Ho kéo dài', 'Ho khan, rát họng về đêm', 'Hút thuốc lá', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"115/75\", \"nhip_tim\": 70}', NULL, 'Viêm họng cấp', NULL, '', 6, '2026-06-18 00:00:00', '2026-05-28 14:35:27', NULL),
(4, NULL, 'PK00004', 4, 1004, 4, '2026-05-20', '08:45:00', '01:30:47', 'Phát ban da', 'Mẩn đỏ ngứa toàn thân', 'Dị ứng hải sản', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"120/80\", \"nhip_tim\": 78}', NULL, 'Mề đay dị ứng', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(5, NULL, 'PK00005', 5, 1005, 5, '2026-05-20', '09:00:00', '01:30:47', 'Đau đầu âm ỉ', 'Chóng mặt khi đứng dậy', 'Cao huyết áp', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"150/90\", \"nhip_tim\": 85}', NULL, 'Hội chứng tiền đình', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(6, NULL, 'PK00006', 6, 1001, 2, '2026-05-20', '09:15:00', '01:30:47', 'Đau mỏi vai gáy', 'Tê bì dọc cánh tay phải', 'Thoái hóa đốt sống cổ', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"120/80\", \"nhip_tim\": 72}', NULL, 'Căng cơ vùng cổ', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(7, NULL, 'PK00007', 7, 1002, 3, '2026-05-20', '09:30:00', '01:30:47', 'Khó thở', 'Khó thở khi vận động mạnh', 'Hen phế quản', '{\"nhiet_do\": 36.9, \"huyet_ap\": \"125/80\", \"nhip_tim\": 88}', NULL, 'Hen phế quản đợt cấp', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(8, NULL, 'PK00008', 8, 1003, 4, '2026-05-20', '09:45:00', '01:30:47', 'Kiểm tra mắt', 'Nhìn mờ, mỏi mắt khi xem đt', 'Cận thị 2 độ', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"110/70\", \"nhip_tim\": 74}', NULL, 'Tăng độ cận', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(9, NULL, 'PK00009', 9, 1004, 5, '2026-05-20', '10:00:00', '01:30:47', 'Rối loạn tiêu hóa', 'Tiêu chảy 3 lần từ sáng', 'Không', '{\"nhiet_do\": 37.5, \"huyet_ap\": \"115/75\", \"nhip_tim\": 80}', NULL, 'Nhiễm trùng đường ruột', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(10, NULL, 'PK00010', 10, 1005, 1, '2026-05-20', '10:15:00', '01:30:47', 'Đau ngực', 'Đau nhói ngực trái', 'Không', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"135/85\", \"nhip_tim\": 90}', NULL, 'Thiếu máu cơ tim cục bộ', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(11, NULL, 'PK00011', 11, 1001, 3, '2026-05-20', '10:30:00', '01:30:47', 'Khám sức khỏe', 'Không', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"120/80\", \"nhip_tim\": 76}', NULL, 'Sức khỏe tốt', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(12, NULL, 'PK00012', 12, 1002, 4, '2026-05-20', '10:45:00', '01:30:47', 'Sốt cao', 'Sốt liên tục 2 ngày, đau người', 'Không', '{\"nhiet_do\": 39.2, \"huyet_ap\": \"110/65\", \"nhip_tim\": 95}', NULL, 'Nghi sốt xuất huyết', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(13, NULL, 'PK00013', 13, 1003, 5, '2026-05-20', '11:00:00', '01:30:47', 'Đau răng', 'Sưng nướu hàm dưới', 'Sâu răng', '{\"nhiet_do\": 37.0, \"huyet_ap\": \"120/80\", \"nhip_tim\": 78}', NULL, 'Viêm quanh cuống răng', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(14, NULL, 'PK00014', 14, 1004, 1, '2026-05-20', '14:00:00', '01:30:47', 'Út tai', 'Ù tai trái kèm chóng mặt', 'Không', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"125/75\", \"nhip_tim\": 72}', NULL, 'Viêm tai giữa mạn tính', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(15, NULL, 'PK00015', 15, 1005, 2, '2026-05-20', '14:15:00', '01:30:47', 'Đau khớp gối', 'Sưng đau khớp gối phải', 'Gút', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"130/80\", \"nhip_tim\": 80}', NULL, 'Viêm khớp dạng thấp', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(16, NULL, 'PK00016', 16, 1001, 4, '2026-05-20', '14:30:00', '01:30:47', 'Mất ngủ', 'Khó vào giấc ngủ, tỉnh nửa đêm', 'Căng thẳng việc làm', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"118/78\", \"nhip_tim\": 68}', NULL, 'Rối loạn giấc ngủ', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(17, NULL, 'PK00017', 17, 1002, 5, '2026-05-20', '14:45:00', '01:30:47', 'Tiểu buốt', 'Tiểu gắt, nước tiểu đục', 'Không', '{\"nhiet_do\": 37.3, \"huyet_ap\": \"120/80\", \"nhip_tim\": 82}', NULL, 'Viêm đường tiết niệu', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(18, NULL, 'PK00018', 18, 1003, 1, '2026-05-20', '15:00:00', '01:30:47', 'Kiểm tra đường huyết', 'Khát nước nhiều, sút cân', 'Đái tháo đường tuýp 2', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"125/80\", \"nhip_tim\": 74}', NULL, 'Đường huyết chưa kiểm soát', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(19, NULL, 'PK00019', 19, 1004, 2, '2026-05-20', '15:15:00', '01:30:47', 'Đau lưng', 'Đau vùng thắt lưng dữ dội', 'Thoát vị đĩa đệm', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"135/85\", \"nhip_tim\": 76}', NULL, 'Thần kinh tọa chèn ép', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(20, NULL, 'PK00020', 20, 1005, 3, '2026-05-20', '15:30:00', '01:30:47', 'Nổi hạch cổ', 'Hạch góc hàm trái sưng đau', 'Viêm họng mạn', '{\"nhiet_do\": 37.6, \"huyet_ap\": \"120/80\", \"nhip_tim\": 84}', NULL, 'Viêm hạch phản ứng', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(21, NULL, 'PK00021', 121, 1008, 5, '2026-05-21', '08:00:00', '01:30:47', 'Khám ho', 'Ho có đờm đặc màu vàng', 'Viêm phế quản', '{\"nhiet_do\": 37.8, \"huyet_ap\": \"120/80\", \"nhip_tim\": 82}', NULL, 'Viêm phế quản cấp', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(22, NULL, 'PK00022', 22, 1002, 1, '2026-05-21', '08:15:00', '01:30:47', 'Chóng mặt', 'Cảm giác quay cuồng khi xoay đầu', 'Rối loạn tiền đình', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"110/70\", \"nhip_tim\": 72}', NULL, 'Thiếu máu não cục bộ', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(23, NULL, 'PK00023', 23, 1003, 2, '2026-05-21', '08:30:00', '01:30:47', 'Đau thượng vị', 'Nóng rát sau xương ức, ợ chua', 'Trào ngược dạ dày', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"122/78\", \"nhip_tim\": 75}', NULL, 'Trào ngược dạ dày thực quản', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(24, NULL, 'PK00024', 24, 1004, 3, '2026-05-21', '08:45:00', '01:30:47', 'Mệt mỏi kéo dài', 'Ăn ngủ kém, da xanh xao', 'Không', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"100/60\", \"nhip_tim\": 68}', NULL, 'Suy nhược cơ thể / Thiếu máu', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(25, NULL, 'PK00025', 25, 1005, 4, '2026-05-21', '09:00:00', '01:30:47', 'Ngứa ngáy', 'Da bong tróc, ngứa vùng khuỷu tay', 'Viêm da cơ địa', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"118/76\", \"nhip_tim\": 74}', NULL, 'Chàm thể tạng cấp', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(26, NULL, 'PK00026', 26, 1001, 1, '2026-05-21', '09:15:00', '01:30:47', 'Khám định kỳ', 'Ổn định', 'Huyết áp tâm thu cao', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"130/80\", \"nhip_tim\": 76}', NULL, 'Tăng huyết áp vô căn', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(27, NULL, 'PK00027', 27, 1002, 2, '2026-05-21', '09:30:00', '01:30:47', 'Đau thần kinh liên sườn', 'Đau dọc mạn sườn trái', 'Không', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"120/80\", \"nhip_tim\": 70}', NULL, 'Đau dây thần kinh liên sườn', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(28, NULL, 'PK00028', 28, 1003, 3, '2026-05-21', '09:45:00', '01:30:47', 'Nhiệt miệng nhiều', 'Vết loét ở lưỡi đau rát', 'Không', '{\"nhiet_do\": 37.1, \"huyet_ap\": \"115/75\", \"nhip_tim\": 78}', NULL, 'Loét niêm mạc miệng', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(29, NULL, 'PK00029', 29, 1004, 4, '2026-05-21', '10:00:00', '01:30:47', 'Đau tai', 'Tai phải chảy dịch ù tai', 'Viêm tai', '{\"nhiet_do\": 37.4, \"huyet_ap\": \"120/80\", \"nhip_tim\": 82}', NULL, 'Viêm tai giữa mủ', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(30, NULL, 'PK00030', 30, 1005, 5, '2026-05-21', '10:15:00', '01:30:47', 'Khám khớp tay', 'Đau các khớp ngón tay buổi sáng', 'Thoái hóa khớp', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"125/80\", \"nhip_tim\": 74}', NULL, 'Thoái hóa đa khớp sinh lý', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(31, NULL, 'PK00031', 31, 1001, 4, '2026-05-21', '10:30:00', '01:30:47', 'Sổ mũi nghẹt mũi', 'Chảy nước mũi trong, hắt hơi liên tục', 'Viêm xoang', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"118/78\", \"nhip_tim\": 72}', NULL, 'Viêm mũi dị ứng thời tiết', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(32, NULL, 'PK00032', 32, 1002, 5, '2026-05-21', '10:45:00', '01:30:47', 'Run tay chân', 'Tay run khi cầm nắm', 'Lão hóa', '{\"nhiet_do\": 36.3, \"huyet_ap\": \"140/85\", \"nhip_tim\": 80}', NULL, 'Theo dõi hội chứng Parkinson', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(33, NULL, 'PK00033', 33, 1003, 1, '2026-05-21', '11:00:00', '01:30:47', 'Kiểm tra béo phì', 'Tăng cân nhanh chóng không rõ lý do', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"130/82\", \"nhip_tim\": 78}', NULL, 'Thừa cân độ 1', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(34, NULL, 'PK00034', 34, 1004, 2, '2026-05-21', '14:00:00', '01:30:47', 'Nôn mửa nhiều', 'Ăn gì nôn nấy kèm sốt nhẹ', 'Ngộ độc thức ăn', '{\"nhiet_do\": 37.8, \"huyet_ap\": \"110/70\", \"nhip_tim\": 85}', NULL, 'Viêm dạ dày ruột cấp tính', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(35, NULL, 'PK00035', 35, 1005, 3, '2026-05-21', '14:15:00', '01:30:47', 'Huyết áp dao động', 'Lúc cao lúc thấp bất thường', 'Suy nhược', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"145/95\", \"nhip_tim\": 84}', NULL, 'Tăng huyết áp cơn bùng phát', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(36, NULL, 'PK00036', 36, 1001, 3, '2026-05-21', '14:30:00', '01:30:47', 'Đau gót chân', 'Đau buốt gót chân khi bước xuống giường', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"120/80\", \"nhip_tim\": 72}', NULL, 'Viêm gân gót / Gai gót chân', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(37, NULL, 'PK00037', 37, 1002, 4, '2026-05-21', '14:45:00', '01:30:47', 'Khám dị ứng', 'Ngứa sưng môi sau ăn mật ong', 'Dị ứng', '{\"nhiet_do\": 36.9, \"huyet_ap\": \"115/75\", \"nhip_tim\": 80}', NULL, 'Phù mạch dị ứng nhẹ', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(38, NULL, 'PK00038', 38, 1003, 5, '2026-05-21', '15:00:00', '01:30:47', 'Đau nửa đầu', 'Đau nhói giật nhói nửa đầu bên phải', 'Migraine', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"120/80\", \"nhip_tim\": 74}', NULL, 'Đau nửa đầu Migraine', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(39, NULL, 'PK00039', 39, 1004, 1, '2026-05-21', '15:15:00', '01:30:47', 'Khám ho ra máu', 'Ho khạc đờm lẫn vài tia máu tươi', 'Lao phổi cũ', '{\"nhiet_do\": 37.2, \"huyet_ap\": \"118/76\", \"nhip_tim\": 78}', NULL, 'Tổn thương phế quản lành tính', NULL, '', 2, '2026-06-18 00:00:00', '2026-05-26 21:31:49', NULL),
(40, NULL, 'PK00040', 40, 1005, 2, '2026-05-21', '15:30:00', '01:30:47', 'Khô mắt nghiêm trọng', 'Cộm rát sưng đỏ 2 mắt', 'Lão thị', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"130/80\", \"nhip_tim\": 72}', NULL, 'Hội chứng khô mắt nặng', NULL, '', 2, '2026-06-18 00:00:00', '2026-05-26 21:32:19', NULL),
(41, NULL, 'PK00041', 41, 1001, 2, '2026-05-22', '08:00:00', '01:30:47', 'Đau hạ sườn', 'Đau tức âm ỉ vùng sườn phải', 'Gan nhiễm mỡ', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"120/80\", \"nhip_tim\": 76}', NULL, 'Rối loạn chức năng gan', NULL, '', 6, '2026-06-18 00:00:00', '2026-05-28 13:40:06', NULL),
(42, NULL, 'PK00042', 42, 1002, 3, '2026-05-22', '08:15:00', '01:30:47', 'Kinh nguyệt không đều', 'Trễ kinh kèm đau bụng dưới', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"110/70\", \"nhip_tim\": 75}', NULL, 'Rối loạn nội tiết tố', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(43, NULL, 'PK00043', 43, 1003, 4, '2026-05-22', '08:30:00', '01:30:47', 'Dị ứng thuốc', 'Nổi ban đỏ sau uống thuốc sốt', 'Dị ứng thuốc', '{\"nhiet_do\": 37.0, \"huyet_ap\": \"122/78\", \"nhip_tim\": 80}', NULL, 'Dị ứng thuốc Paracetamol', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(44, NULL, 'PK00044', 44, 1004, 5, '2026-05-22', '08:45:00', '01:30:47', 'Đau mỏi thắt lưng', 'Đau ê ẩm vùng thắt lưng do ngồi nhiều', 'Không', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"115/75\", \"nhip_tim\": 70}', NULL, 'Đau cơ thắt lưng mạn tính', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(45, NULL, 'PK00045', 45, 1005, 1, '2026-05-22', '09:00:00', '01:30:47', 'Đái rắt ban đêm', 'Tiểu đêm 4-5 lần bất thường', 'Phì đại tuyến tiền liệt', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"130/82\", \"nhip_tim\": 74}', NULL, 'U xơ tuyến tiền liệt lành tính', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(46, NULL, 'PK00046', 46, 1001, 3, '2026-05-22', '09:15:00', '01:30:47', 'Khám rụng tóc', 'Tóc rụng thành mảng lớn', 'Stress', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"112/72\", \"nhip_tim\": 72}', NULL, 'Rụng tóc thể mảng do căng thẳng', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(47, NULL, 'PK00047', 47, 1002, 4, '2026-05-22', '09:30:00', '01:30:47', 'Tê bì bàn chân', 'Mất cảm giác nhẹ các ngón chân', 'Tiểu đường', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"140/88\", \"nhip_tim\": 78}', NULL, 'Biến chứng thần kinh ngoại vi', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(48, NULL, 'PK00048', 48, 1003, 5, '2026-05-22', '09:45:00', '01:30:47', 'Khám khàn tiếng', 'Khàn tiếng kéo dài, hụt hơi khi nói', 'Ca sĩ tự do', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"120/80\", \"nhip_tim\": 75}', NULL, 'Hạt xơ dây thanh quản', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(49, NULL, 'PK00049', 49, 1004, 1, '2026-05-22', '10:00:00', '01:30:47', 'Trướng bụng', 'Đầy hơi khó tiêu, bụng phình to', 'Ăn đồ nhiều mỡ', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"124/76\", \"nhip_tim\": 76}', NULL, 'Rối loạn men tiêu hóa mạn', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(50, NULL, 'PK00050', 50, 1005, 2, '2026-05-22', '10:15:00', '01:30:47', 'Khám run cơ', 'Co giật cơ bắp chân khi ngủ', 'Thiếu canxi', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"135/80\", \"nhip_tim\": 74}', NULL, 'Hạ canxi máu nhẹ sinh lý', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(51, NULL, 'PK00051', 51, 1001, 3, '2026-05-22', '10:30:00', '01:30:47', 'Đau xoang đầu', 'Đau nhức vùng trán rát mũi', 'Viêm xoang', '{\"nhiet_do\": 37.2, \"huyet_ap\": \"120/80\", \"nhip_tim\": 78}', NULL, 'Viêm xoang trán cấp tính', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(52, NULL, 'PK00052', 52, 1002, 4, '2026-05-22', '10:45:00', '01:30:47', 'Khám mắt đỏ', 'Gỉ mắt nhiều tụ máu kết mạc', 'Không', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"115/75\", \"nhip_tim\": 72}', NULL, 'Viêm kết mạc cấp (Đau mắt đỏ)', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(53, NULL, 'PK00053', 53, 1003, 5, '2026-05-22', '11:00:00', '01:30:47', 'Đau thắt cơ ngực', 'Đau tức khi hít thở sâu', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"128/82\", \"nhip_tim\": 75}', NULL, 'Viêm cơ liên sườn lành tính', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(54, NULL, 'PK00054', 54, 1004, 1, '2026-05-22', '14:00:00', '01:30:47', 'Nổi mụn nước', 'Mụn nước mọc vệt dài quanh eo phải', 'Không', '{\"nhiet_do\": 37.4, \"huyet_ap\": \"120/80\", \"nhip_tim\": 82}', NULL, 'Bệnh Zona thần kinh', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(55, NULL, 'PK00055', 55, 1005, 2, '2026-05-22', '14:15:00', '01:30:47', 'Khám mỡ máu', 'Đau đầu mệt mỏi, chân tay nặng nề', 'Rối loạn lipid', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"142/90\", \"nhip_tim\": 80}', NULL, 'Mỡ máu cao kèm gan nhiễm mỡ', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(56, NULL, 'PK00056', 56, 1001, 4, '2026-05-22', '14:30:00', '01:30:47', 'Đau nhói gót chân', 'Đau nhói buốt sưng nề nhẹ gót', 'Chấn thương thể thao', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"110/70\", \"nhip_tim\": 70}', NULL, 'Bong gân mắt cá chân nhẹ', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(57, NULL, 'PK00057', 57, 1002, 5, '2026-05-22', '14:45:00', '01:30:47', 'Khám đại tràng', 'Đi ngoài phân lỏng nát, đau bụng', 'Viêm đại tràng', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"118/78\", \"nhip_tim\": 74}', NULL, 'Hội chứng ruột kích thích', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(58, NULL, 'PK00058', 58, 1003, 1, '2026-05-22', '15:00:00', '01:30:47', 'Sụt cân bất thường', 'Sụt 4kg trong 1 tháng không rõ cớ', 'Không', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"112/70\", \"nhip_tim\": 76}', NULL, 'Nghi cường giáp / Cần xét nghiệm', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(59, NULL, 'PK00059', 59, 1004, 3, '2026-05-22', '15:15:00', '01:30:47', 'Đau khớp vai', 'Không giơ tay cao được, đau buốt', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"122/80\", \"nhip_tim\": 72}', NULL, 'Viêm quanh khớp vai cấp', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(60, NULL, 'PK00060', 60, 1005, 4, '2026-05-22', '15:30:00', '01:30:47', 'Ngứa tai trong', 'Ngứa ngáy tai chảy dịch vàng nhạt', 'Hay ngoáy tai ngoài tiệm', '{\"nhiet_do\": 36.9, \"huyet_ap\": \"120/75\", \"nhip_tim\": 75}', NULL, 'Nấm ống tai ngoài', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(61, NULL, 'PK00061', 61, 1001, 5, '2026-05-22', '15:45:00', '01:30:47', 'Huyết áp cao', 'Đau đầu giật nhói sau gáy', 'Tăng huyết áp', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"160/100\", \"nhip_tim\": 88}', NULL, 'Cơn tăng huyết áp vô căn cấp', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(62, NULL, 'PK00062', 62, 1002, 1, '2026-05-22', '16:00:00', '01:30:47', 'Khám ho đờm', 'Ho sâu từ ngực đờm đặc đục', 'Nhiễm lạnh', '{\"nhiet_do\": 38.2, \"huyet_ap\": \"130/80\", \"nhip_tim\": 84}', NULL, 'Viêm phổi thùy nhẹ', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(63, NULL, 'PK00063', 63, 1003, 2, '2026-05-22', '16:15:00', '01:30:47', 'Đau bụng quanh rốn', 'Đau âm ỉ đầy bụng khó chịu', 'Ăn đồ sống', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"110/68\", \"nhip_tim\": 72}', NULL, 'Rối loạn tiêu hóa cấp', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(64, NULL, 'PK00064', 64, 1004, 3, '2026-05-22', '16:30:00', '01:30:47', 'Khám đau cổ họng', 'Nuốt vướng nuốt đau sốt nhẹ', 'Không', '{\"nhiet_do\": 37.6, \"huyet_ap\": \"120/80\", \"nhip_tim\": 80}', NULL, 'Viêm Amidan cấp tính mủ', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(65, NULL, 'PK00065', 65, 1005, 4, '2026-05-22', '16:45:00', '01:30:47', 'Mẩn đỏ ngứa da', 'Da nổi mẩn đỏ ngứa ngáy nhiều', 'Thay đổi bột giặt mới', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"118/76\", \"nhip_tim\": 74}', NULL, 'Viêm da tiếp xúc dị ứng', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(66, NULL, 'PK00066', 66, 1001, 1, '2026-05-22', '17:00:00', '01:30:47', 'Đau tức thượng vị', 'Đau lúc đói ợ hơi nhiều', 'Viêm loét dạ dày', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"125/82\", \"nhip_tim\": 73}', NULL, 'Viêm dạ dày cấp đợt tái phát', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(67, NULL, 'PK00067', 67, 1002, 2, '2026-05-22', '17:15:00', '01:30:47', 'Đau buốt thắt lưng', 'Đau lan xuống mông đùi phải', 'Thoái hóa cột sống', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"130/80\", \"nhip_tim\": 76}', NULL, 'Thần kinh tọa tổn thương cơ học', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(68, NULL, 'PK00068', 68, 1003, 3, '2026-05-22', '17:30:00', '01:30:47', 'Kiểm tra mắt', 'Mắt nhức mờ chảy nước mắt trong', 'Làm việc máy tính 10 tiếng/ngày', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"114/74\", \"nhip_tim\": 71}', NULL, 'Hội chứng thị giác màn hình máy tính', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(69, NULL, 'PK00069', 69, 1004, 4, '2026-05-22', '17:45:00', '01:30:47', 'Khám tai ù', 'Tai trái kêu tiếng vo ve liên tục', 'Đi bơi bị nước vào tai', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"120/80\", \"nhip_tim\": 74}', NULL, 'Viêm tai ngoài khu trú nhẹ', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(70, NULL, 'PK00070', 70, 1005, 5, '2026-05-22', '18:00:00', '01:30:47', 'Đau nhức các khớp cổ tay', 'Khớp sưng nóng đỏ nhẹ đau nhiều', 'Thấp khớp cũ', '{\"nhiet_do\": 37.0, \"huyet_ap\": \"135/85\", \"nhip_tim\": 82}', NULL, 'Đợt cấp viêm đa khớp dạng thấp', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(71, NULL, 'PK00071', 71, 1001, 3, '2026-05-22', '18:15:00', '01:30:47', 'Đau đầu dữ dội', 'Đau giật giật nhói nửa đầu kèm buồn nôn', 'Hay thức đêm', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"120/80\", \"nhip_tim\": 78}', NULL, 'Cơn đau đầu căng thẳng vận mạch', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(72, NULL, 'PK00072', 72, 1002, 4, '2026-05-22', '18:30:00', '01:30:47', 'Tiểu tiện buốt rắt', 'Tiểu buốt cuối bãi, đi nhiều lần', 'Không', '{\"nhiet_do\": 37.4, \"huyet_ap\": \"118/74\", \"nhip_tim\": 80}', NULL, 'Nhiễm trùng đường tiểu dưới cấp', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(73, NULL, 'PK00073', 73, 1003, 5, '2026-05-22', '18:45:00', '01:30:47', 'Khám ho gió', 'Ho khan kéo dài cả tháng nay', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"120/80\", \"nhip_tim\": 72}', NULL, 'Viêm họng hạt dị ứng mạn', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(74, NULL, 'PK00074', 74, 1004, 1, '2026-05-22', '19:00:00', '01:30:47', 'Đau khớp gối', 'Đau nhức đầu gối khi lên xuống cầu thang', 'Lão hóa khớp', '{\"nhiet_do\": 36.3, \"huyet_ap\": \"140/85\", \"nhip_tim\": 76}', NULL, 'Thoái hóa khớp gối nguyên phát', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(75, NULL, 'PK00075', 75, 1005, 2, '2026-05-22', '19:15:00', '01:30:47', 'Khám kiểm tra đại tràng', 'Bụng chướng, mót rặn phân không đều', 'Không', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"124/78\", \"nhip_tim\": 74}', NULL, 'Viêm đại tràng co thắt chức năng', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(76, NULL, 'PK00076', 76, 1001, 1, '2026-05-22', '19:30:00', '01:30:47', 'Khám chóng mặt', 'Chóng mặt tư thế kịch phát lành tính', 'Rối loạn tiền đình', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"112/70\", \"nhip_tim\": 70}', NULL, 'Thiểu năng tuần hoàn não hệ nền', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(77, NULL, 'PK00077', 77, 1002, 2, '2026-05-22', '19:45:00', '01:30:47', 'Đau sườn ngực', 'Đau nhói vùng ngực sườn phải', 'Không', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"132/84\", \"nhip_tim\": 75}', NULL, 'Đau cơ liên sườn cấp do vận động sai', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(78, NULL, 'PK00078', 78, 1003, 3, '2026-05-22', '20:00:00', '01:30:47', 'Dị ứng mẩn đỏ toàn thân', 'Ngứa ngáy phát ban sau ăn thịt bò', 'Dị ứng bò', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"120/80\", \"nhip_tim\": 78}', NULL, 'Mề đay cấp tính do dị ứng đạm', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(79, NULL, 'PK00079', 79, 1004, 4, '2026-05-22', '20:15:00', '01:30:47', 'Đau mỏi cổ gáy', 'Cổ cứng đơ khó quay trái phải', 'Ngủ sai tư thế gối quá cao', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"122/78\", \"nhip_tim\": 73}', NULL, 'Hội chứng cổ vai gáy cấp', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(80, NULL, 'PK00080', 80, 1005, 5, '2026-05-22', '20:30:00', '01:30:47', 'Mất ngủ kinh niên', 'Mất ngủ kéo dài 3 tháng, sút cân', 'Suy nhược', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"130/80\", \"nhip_tim\": 76}', NULL, 'Suy nhược thần kinh / Rối loạn giấc ngủ', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(81, NULL, 'PK00081', 81, 1001, 1, '2026-05-22', '20:45:00', '01:30:47', 'Đau thượng vị', 'Đau đói nóng rát vùng trên rốn', 'Dạ dày mạn', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"120/80\", \"nhip_tim\": 74}', NULL, 'Viêm dạ dày tá tràng mạn tính', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(82, NULL, 'PK00082', 82, 1002, 2, '2026-05-22', '21:00:00', '01:30:47', 'Sốt lạnh run', 'Sốt cao rét run từng cơn', 'Không', '{\"nhiet_do\": 38.9, \"huyet_ap\": \"115/70\", \"nhip_tim\": 90}', NULL, 'Nhiễm siêu vi đường hô hấp', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(83, NULL, 'PK00083', 83, 1003, 3, '2026-05-22', '21:15:00', '01:30:47', 'Đau tai ngoài', 'Vành tai sưng nề đỏ đau buốt', 'Ngoáy tai rách da', '{\"nhiet_do\": 37.1, \"huyet_ap\": \"120/80\", \"nhip_tim\": 78}', NULL, 'Viêm sụn vành tai nhẹ chấn thương', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(84, NULL, 'PK00084', 84, 1004, 4, '2026-05-22', '21:30:00', '01:30:47', 'Khô rát họng', 'Họng đau rát không nuốt được bọt', 'Uống nước đá nhiều', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"118/76\", \"nhip_tim\": 75}', NULL, 'Viêm họng cấp tính đơn thuần', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(85, NULL, 'PK00085', 85, 1005, 5, '2026-05-22', '21:45:00', '01:30:47', 'Kiểm tra đường huyết', 'Định kỳ lấy máu mao mạch kiểm tra', 'Tiểu đường', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"125/80\", \"nhip_tim\": 72}', NULL, 'Đái tháo đường kiểm soát tốt', NULL, '', 1, '2026-06-18 00:00:00', NULL, NULL),
(86, NULL, 'PK00086', 86, 1001, 2, '2026-05-22', '22:00:00', '01:30:47', 'Khám ngứa ngáy da', 'Nổi sẩn ngứa cục bộ hai cẳng chân', 'Côn trùng cắn', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"110/70\", \"nhip_tim\": 70}', NULL, 'Sẩn ngứa dị ứng do côn trùng', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(87, NULL, 'PK00087', 87, 1002, 3, '2026-05-22', '22:15:00', '01:30:47', 'Tê đầu ngón tay', 'Tê bì ba ngón tay cái, trỏ, giữa tay phải', 'Nhân viên văn phòng', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"118/75\", \"nhip_tim\": 72}', NULL, 'Hội chứng ống cổ tay bên phải', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(88, NULL, 'PK00088', 88, 1003, 4, '2026-05-22', '22:30:00', '01:30:47', 'Trào ngược dạ dày', 'Ợ nóng lên họng rát ngực buổi đêm', 'Trào ngược mạn', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"120/80\", \"nhip_tim\": 74}', NULL, 'Trào ngược thực quản độ A', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(89, NULL, 'PK00089', 89, 1004, 5, '2026-05-22', '22:45:00', '01:30:47', 'Khám mỏi thắt lưng', 'Đau thắt lưng âm ỉ vùng chậu', 'Lái xe đường dài', '{\"nhiet_do\": 36.4, \"huyet_ap\": \"130/82\", \"nhip_tim\": 76}', NULL, 'Thoái hóa cột sống thắt lưng L4-L5', NULL, '', 5, '2026-06-18 00:00:00', NULL, NULL),
(90, NULL, 'PK00090', 90, 1005, 1, '2026-05-22', '23:00:00', '01:30:47', 'Khám rắt tiểu', 'Tiểu gắt nước tiểu vàng đậm đặc', 'Uống ít nước', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"122/80\", \"nhip_tim\": 78}', NULL, 'Theo dõi viêm bàng quang nhẹ', NULL, '', 6, '2026-06-18 00:00:00', NULL, NULL),
(91, NULL, 'PK00091', 91, 1001, 1, '2026-05-22', '23:15:00', '01:30:47', 'Khám hen suyễn', 'Khó thở cò cử tăng khi trời lạnh', 'Hen phế quản', '{\"nhiet_do\": 36.6, \"huyet_ap\": \"125/80\", \"nhip_tim\": 82}', NULL, 'Hen phế quản mạn tính kiểm soát', NULL, '', 7, '2026-06-18 00:00:00', NULL, NULL),
(92, NULL, 'PK00092', 92, 1002, 2, '2026-05-22', '23:30:00', '01:30:47', 'Đau khớp háng', 'Đau khớp háng khi đi lại vận động', 'Thoái hóa', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"138/85\", \"nhip_tim\": 74}', NULL, 'Thoái hóa khớp háng nhẹ giai đoạn 1', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(93, NULL, 'PK00093', 93, 1003, 3, '2026-05-22', '23:45:00', '01:30:47', 'Khám mề đay', 'Mẩn đỏ ngứa ngáy toàn thân lan rộng', 'Ăn nhộng tằm', '{\"nhiet_do\": 37.1, \"huyet_ap\": \"116/74\", \"nhip_tim\": 80}', NULL, 'Mề đay dị ứng thức ăn cấp tính', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(94, NULL, 'PK00094', 94, 1004, 4, '2026-05-22', '23:59:00', '01:30:47', 'Khám nhức đầu', 'Đau ê ẩm toàn bộ vùng đầu trán', 'Thiếu ngủ thức khuya', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"120/80\", \"nhip_tim\": 72}', NULL, 'Đau đầu do căng thẳng thần kinh', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(95, NULL, 'PK00095', 95, 1005, 5, '2026-05-22', '00:01:00', '01:30:47', 'Kiểm tra gan', 'Mệt mỏi chán ăn da hơi vàng nhẹ', 'Bia rượu nhiều', '{\"nhiet_do\": 36.8, \"huyet_ap\": \"130/80\", \"nhip_tim\": 76}', NULL, 'Tổn thương gan do cồn / Men gan cao', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(96, NULL, 'PK00096', 96, 1001, 3, '2026-05-22', '00:15:00', '01:30:47', 'Ho khan rát họng', 'Ho khạc liên tục rát buốt họng', 'Nhiễm lạnh', '{\"nhiet_do\": 37.3, \"huyet_ap\": \"118/76\", \"nhip_tim\": 78}', NULL, 'Viêm họng cấp tính đợt mùa đông', NULL, '', 8, '2026-06-18 00:00:00', NULL, NULL),
(97, NULL, 'PK00097', 97, 1002, 4, '2026-05-22', '00:30:00', '01:30:47', 'Đau quặn bụng dưới', 'Đau âm ỉ hố chậu trái phân lỏng', 'Ăn đồ lạnh', '{\"nhiet_do\":1,\"huyet_ap\":1,\"nhip_tim\":1,\"spo2\":1,\"chieu_cao\":1,\"can_nang\":1,\"bmi\":10000}', NULL, 'Viêm đại tràng cấp tính nhiễm khuẩn', NULL, '1', 3, '2026-06-18 00:00:00', NULL, NULL),
(98, NULL, 'PK00098', 98, 1003, 5, '2026-05-22', '00:45:00', '01:30:47', 'Nhức mắt chảy nước mắt', 'Mắt đỏ nhức mỏi liên tục rát', 'Bụi đường vào mắt', '{\"nhiet_do\": 36.5, \"huyet_ap\": \"112/72\", \"nhip_tim\": 71}', NULL, 'Viêm giác mạc nông do dị vật nhẹ', NULL, '', 2, '2026-06-18 00:00:00', NULL, NULL),
(99, NULL, 'PK00099', 99, 1004, 1, '2026-05-22', '01:00:00', '01:30:47', 'Khám tai chảy mủ', 'Tai phải đau nhức chảy dịch mủ hôi', 'Viêm tai cũ', '{\"nhiet_do\": 37.9, \"huyet_ap\": \"124/80\", \"nhip_tim\": 85}', NULL, 'Viêm tai giữa mạn tính đợt hồi viêm', NULL, '', 3, '2026-06-18 00:00:00', NULL, NULL),
(100, NULL, 'PK00100', 100, 1005, 2, '2026-05-22', '01:15:00', '01:30:47', 'Đau đa khớp xương', 'Các khớp ngón, cổ tay, đầu gối đau nhức', 'Thay đổi thời tiết', '{\"nhiet_do\": 36.7, \"huyet_ap\": \"132/84\", \"nhip_tim\": 77}', NULL, 'Đau đau khớp sinh lý người già', NULL, '', 4, '2026-06-18 00:00:00', NULL, NULL),
(115, NULL, '', 122, 1004, 2, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-05-30 01:34:51', NULL),
(122, NULL, 'PK202606050350196387', 119, 1010, 8, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', NULL, NULL),
(123, NULL, 'PK202606050439197120', 125, 1010, 8, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-05 09:40:24', NULL),
(124, NULL, 'PK202606050555267606', 125, 1007, 5, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', NULL, 5056),
(125, NULL, 'PK202606050610432602', 125, 1008, 6, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', NULL, 5057),
(126, NULL, 'PK20260605005', 125, 1004, 2, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', NULL, 5058),
(128, NULL, 'PK20260605006', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', NULL, NULL),
(130, NULL, 'PK20260605007', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-05 00:00:00', NULL),
(131, NULL, 'PK20260605008', 125, 1010, 8, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-05 23:11:38', 1),
(132, NULL, 'PK20260605009', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-05 00:00:00', NULL),
(133, NULL, 'PK20260605010', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-05 00:00:00', NULL),
(134, NULL, 'PK20260605011', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-05 00:00:00', NULL),
(135, NULL, 'PK20260605012', 128, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"nhip_tim\":75,\"spo2\":98,\"chieu_cao\":170,\"can_nang\":65,\"bmi\":22.5}', NULL, NULL, NULL, 'ks', 3, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(136, NULL, 'PK20260605013', 54, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(137, NULL, 'PK20260605014', 124, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ho', 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(138, 15, 'PK20260605015', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(139, 16, 'PK20260605016', 128, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"nhip_tim\":75,\"spo2\":98,\"chieu_cao\":170,\"can_nang\":65,\"bmi\":null}', NULL, NULL, NULL, 'ks', 3, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(140, 17, 'PK20260605017', 123, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(141, 18, 'PK20260605018', 126, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(142, 19, 'PK20260605019', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(143, 20, 'PK20260605020', 122, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(144, NULL, 'PK20260605021', 125, 1007, 5, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 03:40:53', 2),
(148, 1, 'PK20260606001', 125, 1005, 3, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-06 15:00:33', 5),
(149, 2, 'PK20260606002', 99, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(150, 3, 'PK20260606003', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'đau họng nhiều ngày', 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(151, 4, 'PK20260606004', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'asdfghjkl;\'', 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(152, 5, 'PK20260606005', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(153, 6, 'PK20260606006', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'q111111111111111111', 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(154, 7, 'PK20260606007', 124, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(155, 8, 'PK20260606008', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(156, 9, 'PK20260606009', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(157, 10, 'PK20260606010', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(158, 11, 'PK20260606011', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11111111111111111111111', 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(159, 12, 'PK20260606012', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'tôi bị ngu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(160, 13, 'PK20260606013', 125, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'bệnh nói nhiều', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(161, 14, 'PK20260606014', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(162, 15, 'PK20260606015', 5, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'ho', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(163, 16, 'PK20260606016', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(164, 17, 'PK20260606017', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'qqqqqqqqqqqqqqqqqqqq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(165, 18, 'PK20260606018', 123, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(166, 19, 'PK20260606019', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'sssssssssssssssssss', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(167, 20, 'PK20260606020', 130, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-06 00:00:00', NULL),
(168, 21, 'PK20260606021', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', '1asdfghjkjhgf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 00:25:50', NULL),
(169, 1, 'PK20260607001', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'aaaaaaaaaaaaaaa', 'Vành tai sưng nề đỏ đau buốt', 'Ngủ sai tư thế gối quá cao', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', 'aaa1', '11111', '11111aa', 'hohohahahihi', 4, '2026-06-18 00:00:00', '2026-06-07 20:17:54', NULL),
(170, 2, 'PK20260607002', 121, NULL, NULL, '0000-00-00', '00:45:32', '01:30:47', 'đau họng nhiều ngày', 'a', 'a', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '', 'a', 'a', NULL, 5, '2026-06-18 00:00:00', '2026-06-07 21:31:13', NULL),
(171, 3, 'PK20260607003', 130, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, 'a', 'a', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', NULL, 'a', 'a', NULL, 5, '2026-06-18 00:00:00', '2026-06-07 16:05:55', NULL),
(172, 4, 'PK20260607004', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'aaaaaaaaaaaaaaaaa', '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', 'a', 'ngu', '', '', 5, '2026-06-18 00:00:00', '2026-06-07 21:40:51', NULL),
(173, 5, 'PK20260607005', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '', 'aaaaaaa1234567890', '', '', 5, '2026-06-18 00:00:00', '2026-06-07 21:36:34', NULL),
(174, 6, 'PK20260607006', 99, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '1', '', NULL, 4, '2026-06-18 00:00:00', '2026-06-07 22:10:16', NULL),
(175, 7, 'PK20260607007', 86, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":18}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-07 15:33:44', NULL),
(176, 8, 'PK20260607008', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', 'aaaaaaaaaaaaaaaaaaaaaaq', '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', 'a', 'a', '', NULL, 3, '2026-06-18 00:00:00', '2026-06-07 22:58:34', NULL),
(177, 9, 'PK20260607009', 2, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":18}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-07 15:23:53', NULL),
(178, 10, 'PK20260607010', 95, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":40,\"huyet_ap\":1,\"mach\":40,\"nhip_tho\":15}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-07 23:32:59', NULL),
(179, 11, 'PK20260607011', 93, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":18}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-07 23:48:23', NULL),
(180, 12, 'PK20260607012', 45, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":18}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-07 23:33:19', NULL),
(181, 13, 'PK20260607013', 44, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:01:23', NULL),
(182, 14, 'PK20260607014', 49, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:02:26', NULL),
(183, 15, 'PK20260607015', 27, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:02:30', NULL),
(184, 16, 'PK20260607016', 129, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:34:23', NULL),
(185, 17, 'PK20260607017', 92, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:34:29', NULL),
(186, 18, 'PK20260607018', 94, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:34:50', NULL),
(187, 19, 'PK20260607019', 66, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:35:06', NULL),
(188, 20, 'PK20260607020', 46, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:35:09', NULL),
(189, 21, 'PK20260607021', 43, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:36:10', NULL),
(190, 22, 'PK20260607022', 29, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:36:23', NULL),
(191, 23, 'PK20260607023', 27, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:44:16', NULL),
(192, 24, 'PK20260607024', 27, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:44:49', NULL),
(193, 25, 'PK20260607025', 31, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:46:27', NULL),
(194, 26, 'PK20260607026', 29, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-07 23:46:41', NULL),
(195, 27, 'PK20260607027', 132, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":15}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-07 23:53:44', NULL),
(196, 1, 'PK20260608001', 130, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', 'acvdfe', '', '', NULL, 6, '2026-06-18 00:00:00', '2026-06-19 14:26:28', NULL),
(197, 2, 'PK20260608002', 64, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', 'a', '', '', NULL, 3, '2026-06-18 00:00:00', '2026-06-08 15:39:46', NULL),
(198, 3, 'PK20260608003', 60, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":18}', NULL, NULL, NULL, NULL, 3, '2026-06-18 00:00:00', '2026-06-08 00:05:34', NULL),
(199, 4, 'PK20260608004', 14, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', 'xn', '', '', NULL, 5, '2026-06-18 00:00:00', '2026-06-08 16:33:27', NULL),
(200, 5, 'PK20260608005', 17, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-08 09:28:06', NULL),
(201, 6, 'PK20260608006', 132, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-08 15:37:52', NULL),
(202, 7, 'PK20260608007', 77, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-08 15:38:30', NULL),
(203, 8, 'PK20260608008', 121, NULL, NULL, '0000-00-00', '00:00:00', '01:30:47', '12345678912345678', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-18 00:00:00', '2026-06-19 01:26:18', NULL),
(204, 1, 'PK20260619001', 132, NULL, NULL, '0000-00-00', '00:00:00', '01:38:54', NULL, '123', '123', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '123', '123', '', NULL, 5, '2026-06-19 01:38:54', '2026-06-19 01:41:58', NULL),
(205, 2, 'PK20260619002', 128, NULL, NULL, '0000-00-00', '00:00:00', '02:03:00', NULL, '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '1', '', NULL, 5, '2026-06-19 02:03:00', '2026-06-19 02:35:13', NULL),
(206, 3, 'PK20260619003', 131, NULL, NULL, '0000-00-00', '00:00:00', '02:47:09', NULL, '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '1', '', NULL, 5, '2026-06-19 02:47:09', '2026-06-19 04:00:44', NULL),
(207, 4, 'PK20260619004', 125, NULL, NULL, '0000-00-00', '00:00:00', '03:43:48', NULL, '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '1', '1', NULL, 8, '2026-06-19 03:43:48', '2026-06-19 03:45:34', NULL),
(208, 5, 'PK20260619005', 95, NULL, NULL, '0000-00-00', '00:00:00', '03:53:12', NULL, '2', '2', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '2', '2', '', NULL, 8, '2026-06-19 03:53:13', '2026-06-19 03:54:14', NULL),
(209, 1, 'PK20260620001', 95, NULL, NULL, '0000-00-00', '00:00:00', '00:18:20', NULL, '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '112bmbdgnh', '1', NULL, 5, '2026-06-20 00:18:20', '2026-06-20 09:52:28', NULL),
(210, 2, 'PK20260620002', 98, NULL, NULL, '0000-00-00', '00:00:00', '00:18:22', NULL, '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '123', '123oiuytrdsxccvbnm,k,m  cx', '', NULL, 8, '2026-06-20 00:18:22', '2026-06-20 08:45:12', NULL),
(211, 3, 'PK20260620003', 128, NULL, NULL, '0000-00-00', '00:00:00', '09:58:07', NULL, '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '1', '', NULL, 8, '2026-06-20 09:58:07', '2026-06-20 10:45:41', NULL),
(212, 4, 'PK20260620004', 132, NULL, NULL, '0000-00-00', '00:00:00', '10:24:12', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"mach\":80,\"nhip_tho\":18}', NULL, NULL, NULL, NULL, 3, '2026-06-20 10:24:12', '2026-06-20 21:11:39', NULL),
(213, 5, 'PK20260620005', 125, 1001, NULL, '2026-06-20', '23:51:11', '10:24:14', NULL, '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"chieu_cao\":null,\"can_nang\":null}', '1bfdfdqewtrnbnbdf av hgf', '', '', NULL, 6, '2026-06-20 10:24:14', '2026-06-20 23:52:25', NULL),
(214, 6, 'PK20260620006', 125, NULL, NULL, '0000-00-00', '00:00:00', '10:24:15', NULL, NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', NULL, NULL, NULL, NULL, 3, '2026-06-20 10:24:16', '2026-06-20 21:21:55', NULL),
(215, 7, 'PK20260620007', 1, 1001, NULL, '2026-06-20', '23:45:40', '10:24:18', '14nf', 'u srg ví  g i', 'u aleh  hgv uorvhu fhra', '{\"nhiet_do\":null,\"huyet_ap\":null,\"chieu_cao\":null,\"can_nang\":null}', 'sdsc', '; sip hjs9 8he  dhfs h', '', 'bevge', 3, '2026-06-20 10:24:18', '2026-06-20 23:45:40', NULL),
(216, 8, 'PK20260620008', 2, NULL, NULL, '2026-06-20', '22:33:55', '10:24:20', 'ho nhiều ngày 123fgh', 'asdfds', 'nft6666', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":90}', NULL, NULL, NULL, 'jgfdsbvbcvcvdfgh 123dft', 3, '2026-06-20 10:24:20', '2026-06-20 22:33:55', NULL),
(217, 9, 'PK20260620009', 132, NULL, NULL, '0000-00-00', '00:00:00', '10:24:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-20 10:24:48', '2026-06-20 10:24:48', NULL),
(218, 10, 'PK20260620010', 128, NULL, NULL, '0000-00-00', '00:00:00', '10:24:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-20 10:24:50', '2026-06-20 10:24:50', NULL),
(219, 11, 'PK20260620011', 129, NULL, NULL, '0000-00-00', '00:00:00', '10:25:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-20 10:25:40', '2026-06-20 10:25:40', NULL),
(220, 12, 'PK20260620012', 121, 1008, 5, '0000-00-00', '00:00:00', '11:01:07', 'tgvfđsegbhdhdhfhss', '1', '1', '{\"nhiet_do\":null,\"huyet_ap\":null,\"mach\":null,\"nhip_tho\":null}', '1', '1478', '1', NULL, 8, '2026-06-20 10:59:19', '2026-06-20 11:03:40', NULL),
(221, 13, 'PK20260620013', 132, NULL, NULL, '0000-00-00', '00:00:00', '21:09:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-20 21:09:41', '2026-06-20 21:09:41', NULL),
(222, 14, 'PK20260620014', 127, NULL, NULL, '0000-00-00', '00:00:00', '21:10:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-20 21:10:06', '2026-06-20 21:10:06', NULL);
INSERT INTO `phieukham` (`MaPhieuKham`, `STT`, `MaPhieuKhamCode`, `MaBenhNhan`, `MaBacSi`, `MaChuyenKhoa`, `NgayKham`, `GioKham`, `GioTiepNhan`, `LyDoKham`, `TrieuChung`, `TienSuBenh`, `ThongSoSinhTon`, `ChanDoanSoBo`, `ChanDoan`, `LoiDanBS`, `GhiChu`, `MaTrangThai`, `NgayTao`, `NgayCapNhat`, `MaLichHen`) VALUES
(223, 15, 'PK20260620015', 127, NULL, NULL, '0000-00-00', '00:00:00', '21:10:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-20 21:10:13', '2026-06-20 21:10:13', NULL),
(224, 1, 'PK20260621001', 132, 1001, NULL, '2026-06-21', '01:06:30', '00:01:58', '', '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"chieu_cao\":null,\"can_nang\":null}', 'hththttvvfff', '1234', '', '', 9, '2026-06-21 00:01:58', '2026-06-21 01:08:42', NULL),
(225, 2, 'PK20260621002', 125, 1001, NULL, '2026-06-21', '00:28:23', '00:02:00', '', '', '', '{\"nhiet_do\":null,\"huyet_ap\":null,\"chieu_cao\":null,\"can_nang\":null}', 'èwe', '', '', '', 5, '2026-06-21 00:02:00', '2026-06-21 00:38:42', NULL),
(226, 3, 'PK20260621003', 127, NULL, NULL, '0000-00-00', '00:00:00', '00:02:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-21 00:02:01', '2026-06-21 00:02:01', NULL),
(227, 4, 'PK20260621004', 129, 1001, NULL, '2026-06-21', '02:42:20', '00:02:04', '123', NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', NULL, NULL, NULL, 'dfrtyujmnbvcderth gư5yhyg', 3, '2026-06-21 00:02:04', '2026-06-21 02:42:20', NULL),
(228, 5, 'PK20260621005', 121, 1001, NULL, '2026-06-21', '03:35:31', '02:41:29', '1', 'lâm sàn', 'bệnh lý', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', 'hon onsbf o hoh h0u37  4y94', '1234567', '', '', 9, '2026-06-21 02:36:43', '2026-06-21 03:35:31', NULL),
(229, 6, 'PK20260621006', 121, 1001, NULL, '2026-06-21', '15:54:55', '03:10:36', '', 'vs s wrg', 'ưt g t3tgg 34terg3 4t43 3y fVF', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":186,\"can_nang\":80}', 'a', 'FR TB  E RDFG G DFV egg', '', '', 4, '2026-06-21 03:10:16', '2026-06-21 15:55:09', NULL),
(230, 7, 'PK20260621007', 121, 1010, 2, '2026-06-21', '03:42:50', '03:38:55', '', '2344', '2324355fbc', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":156,\"can_nang\":45}', 'xxc e ưc ư ưt g', '56565', '', '', 9, '2026-06-21 03:38:55', '2026-06-21 03:42:58', NULL),
(231, 8, 'PK20260621008', 126, 1001, NULL, '2026-06-21', '15:56:26', '10:46:19', '', '', '', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', 'a', 'a', '', '', 8, '2026-06-21 10:46:19', '2026-06-21 15:56:34', NULL),
(232, 9, 'PK20260621009', 1, NULL, NULL, '0000-00-00', '00:00:00', '10:46:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-21 10:46:22', '2026-06-21 10:46:22', NULL),
(233, 10, 'PK20260621010', 122, NULL, NULL, '0000-00-00', '00:00:00', '10:46:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-21 10:46:29', '2026-06-21 10:46:29', NULL),
(234, 1, 'PK20260629001', 121, 1001, NULL, '2026-06-29', '07:30:04', '07:27:58', 'ho cảm', 'học ngu', 'ngu', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', '123', 'ngu', '', '', 9, '2026-06-29 02:13:13', '2026-06-29 07:30:10', NULL),
(235, 2, 'PK20260629002', 138, 1001, NULL, '2026-06-29', '08:17:29', '07:31:47', 'buồn ngủ', '', '', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":67}', '1', '222', '', '', 8, '2026-06-29 07:31:47', '2026-06-29 08:17:34', NULL),
(236, 3, 'PK20260629003', 134, 1001, NULL, '2026-06-29', '08:09:24', '07:31:50', '1', '', '', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', '1', '', '', '', 5, '2026-06-29 07:31:51', '2026-06-29 08:09:53', NULL),
(237, 4, 'PK20260629004', 132, 1001, NULL, '2026-06-29', '11:19:34', '07:32:40', '', '123', '123', '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', '11', '222', '', '', 4, '2026-06-29 07:32:40', '2026-06-29 11:19:41', NULL),
(238, 5, 'PK20260629005', 138, 1001, NULL, '2026-06-29', '11:18:11', '07:38:08', '', NULL, NULL, '{\"nhiet_do\":36,\"huyet_ap\":\"120\\/80\",\"chieu_cao\":170,\"can_nang\":60}', NULL, NULL, NULL, '', 3, '2026-06-29 07:38:08', '2026-06-29 11:18:11', NULL),
(239, 6, 'PK20260629006', 135, NULL, NULL, '0000-00-00', '00:00:00', '07:40:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-29 07:40:15', '2026-06-29 07:40:15', NULL),
(240, 7, 'PK20260629007', 10, NULL, NULL, '0000-00-00', '00:00:00', '08:25:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-29 08:25:44', '2026-06-29 08:25:44', NULL),
(241, 8, 'PK20260629008', 2, NULL, NULL, '0000-00-00', '00:00:00', '08:25:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-29 08:25:50', '2026-06-29 08:25:50', NULL),
(242, 9, 'PK20260629009', 131, NULL, NULL, '0000-00-00', '00:00:00', '08:25:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-29 08:25:54', '2026-06-29 08:25:54', NULL),
(243, 10, 'PK20260629010', 1, NULL, NULL, '0000-00-00', '00:00:00', '08:25:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-29 08:25:58', '2026-06-29 08:25:58', NULL),
(244, 11, 'PK20260629011', 22, NULL, NULL, '0000-00-00', '00:00:00', '11:16:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2026-06-29 11:16:55', '2026-06-29 11:16:55', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieunhap`
--

CREATE TABLE `phieunhap` (
  `MaPhieuNhap` int(11) NOT NULL,
  `SoPhieuNhap` varchar(20) NOT NULL,
  `NhaCungCap` varchar(200) NOT NULL,
  `NgayNhap` date NOT NULL,
  `NguoiNhap` int(11) NOT NULL,
  `TongTien` decimal(15,2) NOT NULL,
  `GhiChu` text DEFAULT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieunhap`
--

INSERT INTO `phieunhap` (`MaPhieuNhap`, `SoPhieuNhap`, `NhaCungCap`, `NgayNhap`, `NguoiNhap`, `TongTien`, `GhiChu`, `NgayTao`) VALUES
(11001, 'PN00001', 'Công ty TNHH Dược Phẩm Phương Nam', '2025-01-10', 1017, 45000000.00, 'Nhập đầu kỳ quý 1/2025', '2025-01-10 08:00:00'),
(11002, 'PN00002', 'Công ty CP Dược Hậu Giang', '2025-02-05', 1017, 32000000.00, 'Nhập bổ sung tháng 2', '2025-02-05 08:00:00'),
(11003, 'PN00003', 'Công ty TNHH Dược Phẩm Phương Nam', '2025-04-01', 1018, 28000000.00, 'Nhập quý 2/2025', '2025-04-01 09:00:00'),
(11004, 'PN00004', 'Công ty CP Dược OPC', '2025-06-15', 1017, 51000000.00, 'Nhập lô lớn tháng 6', '2025-06-15 08:30:00'),
(11005, 'PN00005', 'Công ty TNHH Dược Phẩm Phương Nam', '2026-01-10', 1018, 60000000.00, 'Nhập đầu năm 2026', '2026-01-10 08:00:00'),
(11006, 'PN00006', 'Công ty CP Dược Hậu Giang', '2026-03-20', 1017, 38000000.00, 'Nhập bổ sung quý 1/2026', '2026-03-20 08:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieuxuat`
--

CREATE TABLE `phieuxuat` (
  `MaPhieuXuat` int(11) NOT NULL,
  `SoPhieuXuat` varchar(20) NOT NULL,
  `MaBenhNhan` int(11) NOT NULL,
  `NgayXuat` date NOT NULL,
  `NguoiXuat` int(11) NOT NULL,
  `TongTien` decimal(15,2) NOT NULL,
  `GhiChu` text DEFAULT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieuxuat`
--

INSERT INTO `phieuxuat` (`MaPhieuXuat`, `SoPhieuXuat`, `MaBenhNhan`, `NgayXuat`, `NguoiXuat`, `TongTien`, `GhiChu`, `NgayTao`) VALUES
(13001, 'PX00001', 1, '2026-04-15', 1017, 125000.00, 'Xuất theo đơn PK00001', '2026-04-15 09:30:00'),
(13002, 'PX00002', 2, '2026-04-16', 1017, 85000.00, 'Xuất theo đơn PK00002', '2026-04-16 09:45:00'),
(13003, 'PX00003', 3, '2026-04-17', 1018, 52000.00, 'Xuất theo đơn PK00003', '2026-04-17 15:05:00'),
(13004, 'PX00004', 5, '2026-04-19', 1017, 104000.00, 'Xuất theo đơn PK00005', '2026-04-19 15:30:00'),
(13005, 'PX00005', 6, '2026-04-22', 1018, 223000.00, 'Xuất theo đơn PK00006', '2026-04-22 08:45:00'),
(13006, 'PX00006', 7, '2026-04-23', 1017, 128000.00, 'Xuất theo đơn PK00007', '2026-04-23 14:45:00'),
(13007, 'PX00007', 9, '2026-04-25', 1017, 358000.00, 'Xuất theo đơn PK00009 (thuốc tim mạch)', '2026-04-25 18:00:00'),
(13008, 'PX00008', 10, '2026-04-26', 1018, 110000.00, 'Xuất theo đơn PK00010', '2026-04-26 09:15:00'),
(13009, 'PX00009', 11, '2026-04-29', 1017, 105000.00, 'Xuất theo đơn PK00011', '2026-04-29 09:45:00'),
(13010, 'PX00010', 16, '2026-05-06', 1018, 70000.00, 'Xuất theo đơn PK00016', '2026-05-06 15:10:00'),
(13011, 'PX00011', 17, '2026-05-07', 1017, 142000.00, 'Xuất theo đơn PK00017', '2026-05-07 09:30:00'),
(13012, 'PX00012', 19, '2026-05-09', 1018, 330000.00, 'Xuất theo đơn PK00019 (thuốc huyết áp)', '2026-05-09 09:30:00'),
(13013, 'PX00013', 20, '2026-05-10', 1017, 87000.00, 'Xuất theo đơn PK00020', '2026-05-10 15:45:00'),
(13014, 'PX00014', 21, '2026-05-22', 1017, 60000.00, 'Xuất theo đơn PK00041 (duy trì)', '2026-05-22 07:15:00'),
(13033, 'PX-20260528-F976', 1, '2026-05-28', 1017, 3550000.00, NULL, '2026-05-28 13:38:48'),
(13034, 'PX-20260528-C88C', 41, '2026-05-28', 1017, 1800000.00, NULL, '2026-05-28 13:40:06'),
(13035, 'PX-20260528-1030', 2, '2026-05-28', 1017, 1575000.00, NULL, '2026-05-28 14:33:47'),
(13036, 'PX-20260528-5412', 3, '2026-05-28', 1017, 52000.00, NULL, '2026-05-28 14:35:27'),
(13045, 'PX-20260618-1986', 132, '2026-06-19', 1017, 232000.00, NULL, '2026-06-19 03:27:41'),
(13046, 'PX-20260618-6723', 125, '2026-06-19', 1017, 2075000.00, NULL, '2026-06-19 03:51:42'),
(13047, 'PX-20260618-3699', 95, '2026-06-19', 1017, 1823000.00, NULL, '2026-06-19 03:54:51'),
(13048, 'PX-20260620-6158', 98, '2026-06-20', 1001, 2463000.00, NULL, '2026-06-20 08:45:58'),
(13049, 'PX-20260620-3541', 128, '2026-06-20', 1001, 776000.00, NULL, '2026-06-20 10:46:06'),
(13050, 'PX-20260620-8790', 121, '2026-06-20', 1001, 1712000.00, NULL, '2026-06-20 11:03:52'),
(13051, 'PX-20260620-8954', 132, '2026-06-21', 1001, 1654000.00, NULL, '2026-06-21 01:19:58'),
(13052, 'PX-20260620-7791', 121, '2026-06-21', 1001, 2760000.00, NULL, '2026-06-21 03:14:08'),
(13053, 'PX-20260620-9576', 121, '2026-06-21', 1001, 2194000.00, NULL, '2026-06-21 03:47:09'),
(13054, 'PX-20260621-3484', 126, '2026-06-21', 1001, 684000.00, NULL, '2026-06-21 15:57:21'),
(13055, 'PX-20260629-6182', 121, '2026-06-29', 1001, 422000.00, NULL, '2026-06-29 07:30:20'),
(13056, 'PX-20260629-4394', 138, '2026-06-29', 1001, 850000.00, NULL, '2026-06-29 08:17:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phuongthuctt`
--

CREATE TABLE `phuongthuctt` (
  `MaPhuongThuc` smallint(6) NOT NULL,
  `TenPhuongThuc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phuongthuctt`
--

INSERT INTO `phuongthuctt` (`MaPhuongThuc`, `TenPhuongThuc`) VALUES
(2, 'CHUYEN_KHOAN'),
(3, 'QR_CODE'),
(1, 'TIEN_MAT');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTaiKhoan` int(11) NOT NULL,
  `TenDangNhap` varchar(100) NOT NULL,
  `MatKhauHash` varchar(255) NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `DangHoatDong` tinyint(1) NOT NULL DEFAULT 1,
  `SoLanSaiMK` tinyint(4) NOT NULL DEFAULT 0,
  `KhoaDen` datetime DEFAULT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `LanDangNhapCuoi` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`MaTaiKhoan`, `TenDangNhap`, `MatKhauHash`, `SoDienThoai`, `DangHoatDong`, `SoLanSaiMK`, `KhoaDen`, `NgayTao`, `LanDangNhapCuoi`, `is_verified`, `otp_code`, `otp_expires_at`) VALUES
(1, 'patient001', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0901234561', 1, 0, NULL, '2025-03-20 08:00:00', '2026-05-29 13:49:17', 1, NULL, NULL),
(2, 'patient002', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0912345672', 1, 0, NULL, '2025-03-20 08:05:00', '2026-05-22 08:21:00', 1, NULL, NULL),
(3, 'patient003', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0923456783', 1, 0, NULL, '2025-03-20 08:10:00', '2026-05-22 08:22:00', 1, NULL, NULL),
(4, 'patient004', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0934567894', 1, 0, NULL, '2025-03-20 08:15:00', '2026-05-22 08:23:00', 1, NULL, NULL),
(5, 'patient005', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0945678905', 1, 0, NULL, '2025-03-20 08:20:00', '2026-05-22 08:24:00', 1, NULL, NULL),
(6, 'patient006', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0956789016', 1, 0, NULL, '2025-03-20 08:25:00', '2026-05-22 08:25:00', 1, NULL, NULL),
(7, 'patient007', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0967890127', 1, 0, NULL, '2025-03-20 08:30:00', '2026-05-22 08:26:00', 1, NULL, NULL),
(8, 'patient008', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0978901238', 1, 0, NULL, '2025-03-20 08:35:00', '2026-05-22 08:27:00', 1, NULL, NULL),
(9, 'patient009', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0989012349', 1, 0, NULL, '2025-03-20 08:40:00', '2026-05-22 08:28:00', 1, NULL, NULL),
(10, 'patient010', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0990123450', 1, 0, NULL, '2025-03-20 08:45:00', '2026-05-22 08:29:00', 1, NULL, NULL),
(11, 'patient011', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0321234561', 1, 0, NULL, '2025-03-21 09:00:00', '2026-05-22 08:30:00', 1, NULL, NULL),
(12, 'patient012', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0332345672', 1, 0, NULL, '2025-03-21 09:05:00', '2026-05-22 08:31:00', 1, NULL, NULL),
(13, 'patient013', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0343456783', 1, 0, NULL, '2025-03-21 09:10:00', '2026-05-22 08:32:00', 1, NULL, NULL),
(14, 'patient014', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0354567894', 1, 0, NULL, '2025-03-21 09:15:00', '2026-05-22 08:33:00', 1, NULL, NULL),
(15, 'patient015', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0365678905', 1, 0, NULL, '2025-03-21 09:20:00', '2026-05-22 08:34:00', 1, NULL, NULL),
(16, 'patient016', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0376789016', 1, 0, NULL, '2025-03-21 09:25:00', '2026-05-22 08:35:00', 1, NULL, NULL),
(17, 'patient017', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0387890127', 1, 0, NULL, '2025-03-21 09:30:00', '2026-05-22 08:36:00', 1, NULL, NULL),
(18, 'patient018', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0398901238', 1, 0, NULL, '2025-03-21 09:35:00', '2026-05-22 08:37:00', 1, NULL, NULL),
(19, 'patient019', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0701112223', 1, 0, NULL, '2025-03-22 10:00:00', '2026-05-22 08:38:00', 1, NULL, NULL),
(20, 'patient020', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0762223334', 1, 0, NULL, '2025-03-22 10:05:00', '2026-05-22 08:39:00', 1, NULL, NULL),
(21, 'patient021', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0773334445', 1, 0, NULL, '2025-03-22 10:10:00', '2026-05-22 08:40:00', 1, NULL, NULL),
(22, 'patient022', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0784445556', 1, 0, NULL, '2025-03-22 10:15:00', '2026-05-22 08:41:00', 1, NULL, NULL),
(23, 'patient023', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0795556667', 1, 0, NULL, '2025-03-22 10:20:00', '2026-05-22 08:42:00', 1, NULL, NULL),
(24, 'patient024', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0816667778', 1, 0, NULL, '2025-03-22 10:25:00', '2026-05-22 08:43:00', 1, NULL, NULL),
(25, 'patient025', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0827778889', 1, 0, NULL, '2025-03-22 10:30:00', '2026-05-22 08:44:00', 1, NULL, NULL),
(26, 'patient026', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0838889990', 1, 0, NULL, '2025-03-22 10:35:00', '2026-05-22 08:45:00', 1, NULL, NULL),
(27, 'patient027', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0849990001', 1, 0, NULL, '2025-03-22 10:40:00', '2026-05-22 08:46:00', 1, NULL, NULL),
(28, 'patient028', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0851234567', 1, 0, NULL, '2025-03-22 10:45:00', '2026-05-22 08:47:00', 1, NULL, NULL),
(29, 'patient029', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0862345678', 1, 0, NULL, '2025-03-23 08:00:00', '2026-05-22 08:48:00', 1, NULL, NULL),
(30, 'patient030', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0883456789', 1, 0, NULL, '2025-03-23 08:05:00', '2026-05-22 08:49:00', 1, NULL, NULL),
(31, 'patient031', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0894567890', 1, 0, NULL, '2025-03-23 08:10:00', '2026-05-22 08:50:00', 1, NULL, NULL),
(32, 'patient032', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905678901', 1, 0, NULL, '2025-03-23 08:15:00', '2026-05-22 08:51:00', 1, NULL, NULL),
(33, 'patient033', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0916789012', 1, 0, NULL, '2025-03-23 08:20:00', '2026-05-22 08:52:00', 1, NULL, NULL),
(34, 'patient034', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0927890123', 1, 0, NULL, '2025-03-23 08:25:00', '2026-05-22 08:53:00', 1, NULL, NULL),
(35, 'patient035', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0938901234', 1, 0, NULL, '2025-03-23 08:30:00', '2026-05-22 08:54:00', 1, NULL, NULL),
(36, 'patient036', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0949012345', 1, 0, NULL, '2025-03-23 08:35:00', '2026-05-22 08:55:00', 1, NULL, NULL),
(37, 'patient037', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0960123456', 1, 0, NULL, '2025-03-23 08:40:00', '2026-05-22 08:56:00', 1, NULL, NULL),
(38, 'patient038', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0971234567', 1, 0, NULL, '2025-03-23 08:45:00', '2026-05-22 08:57:00', 1, NULL, NULL),
(39, 'patient039', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0982345678', 1, 0, NULL, '2025-03-24 09:00:00', '2026-05-22 08:58:00', 1, NULL, NULL),
(40, 'patient040', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0993456789', 1, 0, NULL, '2025-03-24 09:05:00', '2026-05-22 08:59:00', 1, NULL, NULL),
(41, 'patient041', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0324567890', 1, 0, NULL, '2025-03-24 09:10:00', '2026-05-22 09:00:00', 1, NULL, NULL),
(42, 'patient042', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0335678901', 1, 0, NULL, '2025-03-24 09:15:00', '2026-05-22 09:01:00', 1, NULL, NULL),
(43, 'patient043', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0346789012', 1, 0, NULL, '2025-03-24 09:20:00', '2026-05-22 09:02:00', 1, NULL, NULL),
(44, 'patient044', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0357890123', 1, 0, NULL, '2025-03-24 09:25:00', '2026-05-22 09:03:00', 1, NULL, NULL),
(45, 'patient045', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0368901234', 1, 0, NULL, '2025-03-24 09:30:00', '2026-05-22 09:04:00', 1, NULL, NULL),
(46, 'patient046', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0379012345', 1, 0, NULL, '2025-03-24 09:35:00', '2026-05-22 09:05:00', 1, NULL, NULL),
(47, 'patient047', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0380123456', 1, 0, NULL, '2025-03-24 09:40:00', '2026-05-22 09:06:00', 1, NULL, NULL),
(48, 'patient048', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0391234567', 1, 0, NULL, '2025-03-24 09:45:00', '2026-05-22 09:07:00', 1, NULL, NULL),
(49, 'patient049', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0702345678', 1, 0, NULL, '2025-03-25 08:00:00', '2026-05-22 09:08:00', 1, NULL, NULL),
(50, 'patient050', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0763456789', 1, 0, NULL, '2025-03-25 08:05:00', '2026-05-22 09:09:00', 1, NULL, NULL),
(51, 'patient051', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0774567890', 1, 0, NULL, '2025-03-25 08:10:00', '2026-05-22 09:10:00', 1, NULL, NULL),
(52, 'patient052', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0785678901', 1, 0, NULL, '2025-03-25 08:15:00', '2026-05-22 09:11:00', 1, NULL, NULL),
(53, 'patient053', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0796789012', 1, 0, NULL, '2025-03-25 08:20:00', '2026-05-22 09:12:00', 1, NULL, NULL),
(54, 'patient054', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0817890123', 1, 0, NULL, '2025-03-25 08:25:00', '2026-05-22 09:13:00', 1, NULL, NULL),
(55, 'patient055', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0828901234', 1, 0, NULL, '2025-03-25 08:30:00', '2026-05-22 09:14:00', 1, NULL, NULL),
(56, 'patient056', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0839012345', 1, 0, NULL, '2025-03-25 08:35:00', '2026-05-22 09:15:00', 1, NULL, NULL),
(57, 'patient057', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0840123456', 1, 0, NULL, '2025-03-25 08:40:00', '2026-05-22 09:16:00', 1, NULL, NULL),
(58, 'patient058', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0852345678', 1, 0, NULL, '2025-03-25 08:45:00', '2026-05-22 09:17:00', 1, NULL, NULL),
(59, 'patient059', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0863456789', 1, 0, NULL, '2025-03-26 09:00:00', '2026-05-22 09:18:00', 1, NULL, NULL),
(60, 'patient060', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0884567890', 1, 0, NULL, '2025-03-26 09:05:00', '2026-05-22 09:19:00', 1, NULL, NULL),
(61, 'patient061', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0895678901', 1, 0, NULL, '2025-03-26 09:10:00', '2026-05-22 09:20:00', 1, NULL, NULL),
(62, 'patient062', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906789012', 1, 0, NULL, '2025-03-26 09:15:00', '2026-05-22 09:21:00', 1, NULL, NULL),
(63, 'patient063', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0917890123', 1, 0, NULL, '2025-03-26 09:20:00', '2026-05-22 09:22:00', 1, NULL, NULL),
(64, 'patient064', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0928901234', 1, 0, NULL, '2025-03-26 09:25:00', '2026-05-22 09:23:00', 1, NULL, NULL),
(65, 'patient065', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0939012345', 1, 0, NULL, '2025-03-26 09:30:00', '2026-05-22 09:24:00', 1, NULL, NULL),
(66, 'patient066', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0940123456', 1, 0, NULL, '2025-03-26 09:35:00', '2026-05-22 09:25:00', 1, NULL, NULL),
(67, 'patient067', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0961234567', 1, 0, NULL, '2025-03-26 09:40:00', '2026-05-22 09:26:00', 1, NULL, NULL),
(68, 'patient068', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0972345678', 1, 0, NULL, '2025-03-26 09:45:00', '2026-05-22 09:27:00', 1, NULL, NULL),
(69, 'patient069', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0983456789', 1, 0, NULL, '2025-03-27 08:00:00', '2026-05-22 09:28:00', 1, NULL, NULL),
(70, 'patient070', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0994567890', 1, 0, NULL, '2025-03-27 08:05:00', '2026-05-22 09:29:00', 1, NULL, NULL),
(71, 'patient071', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0325678901', 1, 0, NULL, '2025-03-27 08:10:00', '2026-05-22 09:30:00', 1, NULL, NULL),
(72, 'patient072', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0336789012', 1, 0, NULL, '2025-03-27 08:15:00', '2026-05-22 09:31:00', 1, NULL, NULL),
(73, 'patient073', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0347890123', 1, 0, NULL, '2025-03-27 08:20:00', '2026-05-22 09:32:00', 1, NULL, NULL),
(74, 'patient074', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0358901234', 1, 0, NULL, '2025-03-27 08:25:00', '2026-05-22 09:33:00', 1, NULL, NULL),
(75, 'patient075', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0369012345', 1, 0, NULL, '2025-03-27 08:30:00', '2026-05-22 09:34:00', 1, NULL, NULL),
(76, 'patient076', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0370123456', 1, 0, NULL, '2025-03-27 08:35:00', '2026-05-22 09:35:00', 1, NULL, NULL),
(77, 'patient077', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0381234567', 1, 0, NULL, '2025-03-27 08:40:00', '2026-05-22 09:36:00', 1, NULL, NULL),
(78, 'patient078', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0392345678', 1, 0, NULL, '2025-03-27 08:45:00', '2026-05-22 09:37:00', 1, NULL, NULL),
(79, 'patient079', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0703456789', 1, 0, NULL, '2025-03-28 09:00:00', '2026-05-22 09:38:00', 1, NULL, NULL),
(80, 'patient080', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0764567890', 1, 0, NULL, '2025-03-28 09:05:00', '2026-05-22 09:39:00', 1, NULL, NULL),
(81, 'patient081', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0775678901', 1, 0, NULL, '2025-03-28 09:10:00', '2026-05-22 09:40:00', 1, NULL, NULL),
(82, 'patient082', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0786789012', 1, 0, NULL, '2025-03-28 09:15:00', '2026-05-22 09:41:00', 1, NULL, NULL),
(83, 'patient083', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0797890123', 1, 0, NULL, '2025-03-28 09:20:00', '2026-05-22 09:42:00', 1, NULL, NULL),
(84, 'patient084', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0818901234', 1, 0, NULL, '2025-03-28 09:25:00', '2026-05-22 09:43:00', 1, NULL, NULL),
(85, 'patient085', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0829012345', 1, 0, NULL, '2025-03-28 09:30:00', '2026-05-22 09:44:00', 1, NULL, NULL),
(86, 'patient086', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0830123456', 1, 0, NULL, '2025-03-28 09:35:00', '2026-05-22 09:45:00', 1, NULL, NULL),
(87, 'patient087', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0841234567', 1, 0, NULL, '2025-03-28 09:40:00', '2026-05-22 09:46:00', 1, NULL, NULL),
(88, 'patient088', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0853456789', 1, 0, NULL, '2025-03-28 09:45:00', '2026-05-22 09:47:00', 1, NULL, NULL),
(89, 'patient089', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0864567890', 1, 0, NULL, '2025-03-29 08:00:00', '2026-05-22 09:48:00', 1, NULL, NULL),
(90, 'patient090', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0885678901', 1, 0, NULL, '2025-03-29 08:05:00', '2026-05-22 09:49:00', 1, NULL, NULL),
(91, 'patient091', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0896789012', 1, 0, NULL, '2025-03-29 08:10:00', '2026-05-22 09:50:00', 1, NULL, NULL),
(92, 'patient092', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0907890123', 1, 0, NULL, '2025-03-29 08:15:00', '2026-05-22 09:51:00', 1, NULL, NULL),
(93, 'patient093', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0918901234', 1, 0, NULL, '2025-03-29 08:20:00', '2026-05-22 09:52:00', 1, NULL, NULL),
(94, 'patient094', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0929012345', 1, 0, NULL, '2025-03-29 08:25:00', '2026-05-22 09:53:00', 1, NULL, NULL),
(95, 'patient095', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0930123456', 1, 0, NULL, '2025-03-29 08:30:00', '2026-05-22 09:54:00', 1, NULL, NULL),
(96, 'patient096', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0941234567', 1, 0, NULL, '2025-03-29 08:35:00', '2026-05-22 09:55:00', 1, NULL, NULL),
(97, 'patient097', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0962345678', 1, 0, NULL, '2025-03-29 08:40:00', '2026-05-22 09:56:00', 1, NULL, NULL),
(98, 'patient098', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0973456789', 1, 0, NULL, '2025-03-29 08:45:00', '2026-05-22 09:57:00', 1, NULL, NULL),
(99, 'patient099', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0984567890', 1, 0, NULL, '2025-03-30 09:00:00', '2026-05-22 09:58:00', 1, NULL, NULL),
(100, 'patient100', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0995678901', 1, 0, NULL, '2025-03-30 09:05:00', '2026-05-22 09:59:00', 1, NULL, NULL),
(101, 'hoangdev.ADMIN', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905111111', 1, 0, NULL, '2025-01-01 08:00:00', '2026-06-29 11:58:31', 1, NULL, NULL),
(102, 'quanly01', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905222222', 1, 0, NULL, '2025-01-01 08:00:00', '2026-05-22 07:00:00', 1, NULL, NULL),
(103, 'bs.nguyenvana', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905333333', 1, 0, NULL, '2025-01-05 08:00:00', '2026-05-22 07:15:00', 1, NULL, NULL),
(104, 'hoangdev.BS', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905444444', 1, 0, NULL, '2025-01-05 08:00:00', '2026-06-21 15:00:58', 1, NULL, NULL),
(105, 'bs.lehoangc', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905555555', 1, 0, NULL, '2025-01-10 08:00:00', '2026-06-07 13:10:48', 1, NULL, NULL),
(106, 'bs.phamminhtd', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905666666', 1, 0, NULL, '2025-01-10 08:00:00', '2026-05-22 07:25:00', 1, NULL, NULL),
(107, 'bs.hoangthue', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905777777', 1, 0, NULL, '2025-01-15 08:00:00', '2026-06-01 23:23:03', 1, NULL, NULL),
(108, 'bs.vutienf', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905888888', 1, 0, NULL, '2025-02-01 08:00:00', '2026-05-22 07:05:00', 1, NULL, NULL),
(109, 'hoangdev.DD', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0905999999', 1, 0, NULL, '2025-02-01 08:00:00', '2026-06-29 11:18:45', 1, NULL, NULL),
(110, 'bs.buiquoch', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906111111', 1, 0, NULL, '2025-02-15 08:00:00', '2026-05-22 07:30:00', 1, NULL, NULL),
(111, 'bs.phanlei', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906222222', 1, 0, NULL, '2025-03-01 08:00:00', '2026-05-22 06:50:00', 1, NULL, NULL),
(112, 'bs.dohoangk', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906333333', 1, 0, NULL, '2025-03-01 08:00:00', '2026-05-22 07:00:00', 1, NULL, NULL),
(113, 'hoangdev.LT', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906444444', 1, 0, NULL, '2025-01-05 08:00:00', '2026-06-21 14:59:41', 1, NULL, NULL),
(114, 'letan02', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906555555', 1, 0, NULL, '2025-02-01 08:00:00', '2026-05-22 07:40:00', 1, NULL, NULL),
(115, 'letan03', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906666666', 1, 0, NULL, '2025-03-01 08:00:00', '2026-05-22 07:50:00', 1, NULL, NULL),
(116, 'letan04', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906777777', 1, 0, NULL, '2025-04-01 08:00:00', '2026-06-04 10:53:02', 1, NULL, NULL),
(117, 'hoangdev.DS', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906888888', 1, 0, NULL, '2025-01-10 08:00:00', '2026-06-19 01:44:59', 1, NULL, NULL),
(118, 'duocsi02', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0906999999', 1, 0, NULL, '2025-02-01 08:00:00', '2026-06-03 16:06:45', 1, NULL, NULL),
(119, 'duocsi03', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0907111111', 1, 0, NULL, '2025-03-15 08:00:00', '2026-05-22 08:05:00', 1, NULL, NULL),
(120, 'hoangdev.KTV', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0907222222', 1, 0, NULL, '2025-01-15 08:00:00', '2026-06-29 08:08:55', 1, NULL, NULL),
(121, 'kythuatvien02', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0907333333', 1, 0, NULL, '2025-02-10 08:00:00', '2026-05-22 08:15:00', 1, NULL, NULL),
(122, 'kythuatvien03', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0907444444', 1, 0, NULL, '2025-03-20 08:00:00', '2026-05-22 08:20:00', 1, NULL, NULL),
(123, 'hoangphandiy', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', NULL, 1, 0, NULL, '2026-05-23 18:26:56', '2026-05-26 10:46:36', 1, NULL, NULL),
(126, '0987654444', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0987654444', 1, 0, NULL, '2026-05-26 08:11:04', '2026-05-26 08:11:35', 1, NULL, NULL),
(127, '7878787878', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '7878787878', 1, 0, NULL, '2026-05-26 08:15:44', NULL, 1, NULL, NULL),
(128, '0127575333', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0127575333', 1, 0, NULL, '2026-05-26 08:17:02', NULL, 1, NULL, NULL),
(129, '0127575755', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '0127575755', 1, 0, NULL, '2026-05-26 08:18:18', NULL, 1, NULL, NULL),
(133, '000000000', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '000000000', 1, 0, NULL, '2026-05-26 09:40:24', NULL, 1, NULL, NULL),
(134, 'bn1234', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '1231231111', 1, 0, NULL, '2026-05-26 09:53:25', '2026-06-05 08:43:17', 1, NULL, NULL),
(135, 'hoangdev.BN', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '1111111111', 1, 0, NULL, '2026-05-28 23:30:23', '2026-06-29 10:41:28', 1, NULL, NULL),
(136, '123', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', NULL, 1, 0, NULL, '2026-06-04 11:08:58', '2026-06-04 16:55:05', 1, NULL, NULL),
(137, 'hoang123', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '9999999999', 1, 0, NULL, '2026-06-05 09:00:27', '2026-06-06 15:39:41', 1, NULL, NULL),
(138, 'admin.test', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '000111889', 1, 0, NULL, '2026-06-21 11:53:43', NULL, 1, NULL, NULL),
(139, 'eeeeeee', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '000111880', 1, 0, NULL, '2026-06-21 12:06:54', NULL, 1, NULL, NULL),
(140, 'bn123545', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '00011188955', 1, 0, NULL, '2026-06-21 12:12:02', '2026-06-21 12:23:02', 1, NULL, NULL),
(149, 'tet123', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '000111881078', 1, 0, NULL, '2026-06-21 12:46:52', NULL, 1, NULL, NULL),
(150, '32gffg', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '000111889444', 1, 0, NULL, '2026-06-21 12:48:19', NULL, 0, '636656', '2026-06-21 08:06:31'),
(151, 'acvcv', '$2y$10$Y58c3znoeJXm5nbSK4D5HuD0YhCxKXaJL4cQFeZNKgYRaoU3M7nTe', '00011188911', 1, 0, NULL, '2026-06-21 13:14:39', '2026-06-21 14:47:03', 1, NULL, NULL),
(152, '123123123', '$2y$10$dF7MiY3up2t10i/PVmHy4epWQOzPqOjlyVM5jdlbk/eV9UiK2.2Tq', NULL, 0, 0, NULL, '2026-06-28 09:14:20', '2026-06-28 09:16:51', 0, NULL, NULL),
(153, 'hoang', '$2y$10$RWpU07RGzwbi.HYfkvKyQ.7FlgIEElTbGO3xhvACyRWfXNr4oL48i', NULL, 1, 0, NULL, '2026-06-29 02:21:53', NULL, 0, NULL, NULL),
(154, 'hoangphandiy11111', '$2y$10$Z4utdMJGpSbswpvXjRCaHedHWuoxIo4FnH962M6l6Hom9nzYfnCCS', NULL, 1, 0, NULL, '2026-06-29 02:25:28', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan_vaitro`
--

CREATE TABLE `taikhoan_vaitro` (
  `MaTaiKhoan` int(11) NOT NULL,
  `MaVaiTro` smallint(6) NOT NULL,
  `NgayPhanQuyen` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan_vaitro`
--

INSERT INTO `taikhoan_vaitro` (`MaTaiKhoan`, `MaVaiTro`, `NgayPhanQuyen`) VALUES
(1, 2, '2025-03-20 08:00:00'),
(2, 7, '2025-03-20 08:05:00'),
(3, 7, '2025-03-20 08:10:00'),
(4, 7, '2025-03-20 08:15:00'),
(5, 7, '2025-03-20 08:20:00'),
(6, 7, '2025-03-20 08:25:00'),
(7, 7, '2025-03-20 08:30:00'),
(8, 7, '2025-03-20 08:35:00'),
(9, 7, '2025-03-20 08:40:00'),
(10, 7, '2025-03-20 08:45:00'),
(11, 7, '2025-03-21 09:00:00'),
(12, 7, '2025-03-21 09:05:00'),
(13, 7, '2025-03-21 09:10:00'),
(14, 7, '2025-03-21 09:15:00'),
(15, 7, '2025-03-21 09:20:00'),
(16, 7, '2025-03-21 09:25:00'),
(17, 7, '2025-03-21 09:30:00'),
(18, 7, '2025-03-21 09:35:00'),
(19, 7, '2025-03-22 10:00:00'),
(20, 7, '2025-03-22 10:05:00'),
(21, 7, '2025-03-22 10:10:00'),
(22, 7, '2025-03-22 10:15:00'),
(23, 7, '2025-03-22 10:20:00'),
(24, 7, '2025-03-22 10:25:00'),
(25, 7, '2025-03-22 10:30:00'),
(26, 7, '2025-03-22 10:35:00'),
(27, 7, '2025-03-22 10:40:00'),
(28, 7, '2025-03-22 10:45:00'),
(29, 7, '2025-03-23 08:00:00'),
(30, 7, '2025-03-23 08:05:00'),
(31, 7, '2025-03-23 08:10:00'),
(32, 7, '2025-03-23 08:15:00'),
(33, 7, '2025-03-23 08:20:00'),
(34, 7, '2025-03-23 08:25:00'),
(35, 7, '2025-03-23 08:30:00'),
(36, 7, '2025-03-23 08:35:00'),
(37, 7, '2025-03-23 08:40:00'),
(38, 7, '2025-03-23 08:45:00'),
(39, 7, '2025-03-24 09:00:00'),
(40, 7, '2025-03-24 09:05:00'),
(41, 7, '2025-03-24 09:10:00'),
(42, 7, '2025-03-24 09:15:00'),
(43, 7, '2025-03-24 09:20:00'),
(44, 7, '2025-03-24 09:25:00'),
(45, 7, '2025-03-24 09:30:00'),
(46, 7, '2025-03-24 09:35:00'),
(47, 7, '2025-03-24 09:40:00'),
(48, 7, '2025-03-24 09:45:00'),
(49, 7, '2025-03-25 08:00:00'),
(50, 7, '2025-03-25 08:05:00'),
(51, 7, '2025-03-25 08:10:00'),
(52, 7, '2025-03-25 08:15:00'),
(53, 7, '2025-03-25 08:20:00'),
(54, 7, '2025-03-25 08:25:00'),
(55, 7, '2025-03-25 08:30:00'),
(56, 7, '2025-03-25 08:35:00'),
(57, 7, '2025-03-25 08:40:00'),
(58, 7, '2025-03-25 08:45:00'),
(59, 7, '2025-03-26 09:00:00'),
(60, 7, '2025-03-26 09:05:00'),
(61, 7, '2025-03-26 09:10:00'),
(62, 7, '2025-03-26 09:15:00'),
(63, 7, '2025-03-26 09:20:00'),
(64, 7, '2025-03-26 09:25:00'),
(65, 7, '2025-03-26 09:30:00'),
(66, 7, '2025-03-26 09:35:00'),
(67, 7, '2025-03-26 09:40:00'),
(68, 7, '2025-03-26 09:45:00'),
(69, 7, '2025-03-27 08:00:00'),
(70, 7, '2025-03-27 08:05:00'),
(71, 7, '2025-03-27 08:10:00'),
(72, 7, '2025-03-27 08:15:00'),
(73, 7, '2025-03-27 08:20:00'),
(74, 7, '2025-03-27 08:25:00'),
(75, 7, '2025-03-27 08:30:00'),
(76, 7, '2025-03-27 08:35:00'),
(77, 7, '2025-03-27 08:40:00'),
(78, 7, '2025-03-27 08:45:00'),
(79, 7, '2025-03-28 09:00:00'),
(80, 7, '2025-03-28 09:05:00'),
(81, 7, '2025-03-28 09:10:00'),
(82, 7, '2025-03-28 09:15:00'),
(83, 7, '2025-03-28 09:20:00'),
(84, 7, '2025-03-28 09:25:00'),
(85, 7, '2025-03-28 09:30:00'),
(86, 7, '2025-03-28 09:35:00'),
(87, 7, '2025-03-28 09:40:00'),
(88, 7, '2025-03-28 09:45:00'),
(89, 7, '2025-03-29 08:00:00'),
(90, 7, '2025-03-29 08:05:00'),
(91, 7, '2025-03-29 08:10:00'),
(92, 7, '2025-03-29 08:15:00'),
(93, 7, '2025-03-29 08:20:00'),
(94, 7, '2025-03-29 08:25:00'),
(95, 7, '2025-03-29 08:30:00'),
(96, 7, '2025-03-29 08:35:00'),
(97, 7, '2025-03-29 08:40:00'),
(98, 7, '2025-03-29 08:45:00'),
(99, 7, '2025-03-30 09:00:00'),
(100, 7, '2025-03-30 09:05:00'),
(101, 6, '2025-01-01 08:00:00'),
(102, 6, '2025-01-01 08:00:00'),
(103, 3, '2025-01-05 08:00:00'),
(104, 3, '2025-01-05 08:00:00'),
(105, 3, '2025-01-10 08:00:00'),
(106, 3, '2025-01-10 08:00:00'),
(107, 3, '2025-01-15 08:00:00'),
(108, 3, '2025-02-01 08:00:00'),
(109, 2, '2025-02-01 08:00:00'),
(110, 3, '2025-02-15 08:00:00'),
(111, 3, '2025-03-01 08:00:00'),
(112, 3, '2025-03-01 08:00:00'),
(113, 1, '2025-01-05 08:00:00'),
(114, 1, '2025-02-01 08:00:00'),
(115, 1, '2025-03-01 08:00:00'),
(116, 1, '2025-04-01 08:00:00'),
(117, 5, '2025-01-10 08:00:00'),
(118, 5, '2025-02-01 08:00:00'),
(119, 5, '2025-03-15 08:00:00'),
(120, 4, '2025-01-15 08:00:00'),
(121, 4, '2025-02-10 08:00:00'),
(122, 4, '2025-03-20 08:00:00'),
(123, 3, '2026-05-23 18:26:57'),
(126, 7, '2026-05-26 08:11:04'),
(127, 7, '2026-05-26 08:15:44'),
(128, 7, '2026-05-26 08:17:02'),
(129, 7, '2026-05-26 08:18:18'),
(136, 1, '2026-06-04 11:08:58'),
(152, 1, '2026-06-28 09:14:20'),
(153, 3, '2026-06-29 02:21:53'),
(154, 2, '2026-06-29 02:25:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `MaThanhToan` int(11) NOT NULL,
  `MaHoaDon` int(11) NOT NULL,
  `MaPhuongThuc` smallint(6) NOT NULL,
  `SoTien` decimal(15,2) NOT NULL,
  `NguoiThu` int(11) NOT NULL,
  `NgayThanhToan` datetime NOT NULL DEFAULT current_timestamp(),
  `GhiChu` text DEFAULT NULL,
  `PhuongThuc` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhtoan`
--

INSERT INTO `thanhtoan` (`MaThanhToan`, `MaHoaDon`, `MaPhuongThuc`, `SoTien`, `NguoiThu`, `NgayThanhToan`, `GhiChu`, `PhuongThuc`) VALUES
(16001, 15001, 1, 275000.00, 1013, '2026-04-15 09:30:00', NULL, NULL),
(16002, 15002, 1, 235000.00, 1013, '2026-04-16 09:45:00', NULL, NULL),
(16003, 15003, 1, 152000.00, 1014, '2026-04-17 15:05:00', NULL, NULL),
(16004, 15004, 3, 600000.00, 1013, '2026-04-18 10:50:00', 'Thanh toán QR Code', NULL),
(16005, 15005, 1, 254000.00, 1013, '2026-04-19 15:40:00', NULL, NULL),
(16006, 15006, 2, 373000.00, 1014, '2026-04-22 08:50:00', 'Chuyển khoản MB Bank', NULL),
(16007, 15007, 1, 278000.00, 1013, '2026-04-23 14:50:00', NULL, NULL),
(16008, 15008, 1, 200000.00, 1015, '2026-04-24 10:15:00', NULL, NULL),
(16009, 15009, 3, 1288000.00, 1013, '2026-04-25 18:00:00', 'Thanh toán QR Code VietQR', NULL),
(16010, 15010, 1, 260000.00, 1014, '2026-04-26 09:20:00', NULL, NULL),
(16011, 15011, 1, 255000.00, 1013, '2026-04-29 09:50:00', NULL, NULL),
(16012, 15012, 1, 150000.00, 1015, '2026-04-30 14:35:00', NULL, NULL),
(16013, 15013, 3, 300000.00, 1013, '2026-05-01 11:20:00', 'Thanh toán QR Code', NULL),
(16014, 15014, 1, 200000.00, 1014, '2026-05-02 15:45:00', NULL, NULL),
(16015, 15015, 1, 150000.00, 1013, '2026-05-05 08:40:00', NULL, NULL),
(16016, 15016, 2, 220000.00, 1015, '2026-05-06 15:15:00', 'Chuyển khoản Vietcombank', NULL),
(16017, 15017, 1, 292000.00, 1013, '2026-05-07 09:35:00', NULL, NULL),
(16018, 15018, 1, 200000.00, 1014, '2026-05-08 14:45:00', NULL, NULL),
(16019, 15019, 3, 880000.00, 1013, '2026-05-09 09:30:00', 'Thanh toán QR Code', NULL),
(16020, 15020, 1, 237000.00, 1015, '2026-05-10 15:50:00', NULL, NULL),
(16021, 15021, 1, 210000.00, 1013, '2026-05-10 09:20:00', NULL, NULL),
(16022, 15022, 1, 150000.00, 1014, '2026-05-11 09:35:00', NULL, NULL),
(16023, 15023, 1, 100000.00, 1013, '2026-05-12 15:05:00', NULL, NULL),
(16024, 15024, 3, 600000.00, 1015, '2026-05-13 11:00:00', 'Thanh toán QR Code VietQR', NULL),
(16025, 15025, 1, 202000.00, 1013, '2026-05-14 15:40:00', NULL, NULL),
(16026, 15026, 2, 360000.00, 1014, '2026-05-15 09:20:00', 'Chuyển khoản BIDV', NULL),
(16027, 15027, 1, 150000.00, 1013, '2026-05-15 14:40:00', NULL, NULL),
(16028, 15028, 1, 200000.00, 1015, '2026-05-16 09:55:00', NULL, NULL),
(16029, 15029, 3, 868000.00, 1013, '2026-05-16 15:55:00', 'Thanh toán QR Code', NULL),
(16030, 15030, 1, 285000.00, 1014, '2026-05-17 08:50:00', NULL, NULL),
(16031, 15031, 1, 210000.00, 1013, '2026-05-22 07:20:00', NULL, NULL),
(16032, 15032, 3, 225000.00, 1013, '2026-05-22 07:25:00', 'Thanh toán QR Code', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongbao`
--

CREATE TABLE `thongbao` (
  `MaThongBao` int(11) NOT NULL,
  `MaNguoiNhan` int(11) NOT NULL,
  `TieuDe` varchar(200) NOT NULL,
  `NoiDung` text NOT NULL,
  `DaDoc` tinyint(1) NOT NULL DEFAULT 0,
  `NgayGui` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thongbao`
--

INSERT INTO `thongbao` (`MaThongBao`, `MaNguoiNhan`, `TieuDe`, `NoiDung`, `DaDoc`, `NgayGui`) VALUES
(18001, 23, 'Xác nhận lịch khám thành công', 'Lịch khám của bạn vào ngày 22/05/2026 lúc 06:30 với BS. Nguyễn Văn An đã được xác nhận. Vui lòng đến trước 15 phút.', 1, '2026-05-21 18:00:00'),
(18002, 24, 'Nhắc nhở tái khám', 'Vết mổ của bạn đã lành hoàn toàn. Chúc mừng! Hãy liên hệ phòng khám nếu có bất thường.', 1, '2026-05-22 07:30:00'),
(18003, 31, 'Kết quả xét nghiệm sẵn sàng', 'Kết quả xét nghiệm tim mạch ngày 16/05 của bạn đã có. Vui lòng đến phòng khám nhận kết quả hoặc liên hệ BS. Phan Lê Hải.', 0, '2026-05-17 09:00:00'),
(18004, 41, 'Nhắc nhở uống thuốc', 'Hôm nay là ngày thứ 13 trong liệu trình điều trị huyết áp. Vui lòng nhớ uống thuốc đúng giờ và đo huyết áp buổi sáng.', 0, '2026-05-22 07:00:00'),
(18005, 36, 'Lịch khám thai tuần 28', 'Nhắc nhở: Bạn đang ở tuần thai 28. Đây là giai đoạn quan trọng cần khám định kỳ. Vui lòng liên hệ đặt lịch với BS. Phạm Minh Tuấn Dũng.', 0, '2026-05-20 09:00:00'),
(18006, 3, 'Hệ thống: 3 bệnh nhân đang chờ khám', 'Danh sách bệnh nhân chờ khám hôm nay: BN00021 (08:00), BN00011 (09:00), BN00021 (tái khám). Phòng khám số 1.', 1, '2026-05-22 07:30:00'),
(18007, 17, 'Cảnh báo tồn kho: Thuốc nhỏ mắt Tears Naturale', 'Số lượng tồn kho thuốc nhỏ mắt Tears Naturale (THU028) còn 500 chai, đang tiệm cận mức tối thiểu (50). Đề nghị lập phiếu nhập bổ sung.', 0, '2026-05-22 06:00:00'),
(18008, 2, 'Báo cáo doanh thu tháng 5/2026', 'Doanh thu tháng 5/2026 (đến ngày 22/05): 8.250.000đ. Chi tiết xem trong báo cáo tháng.', 0, '2026-05-22 08:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuoc`
--

CREATE TABLE `thuoc` (
  `MaThuoc` int(11) NOT NULL,
  `MaThuocCode` varchar(20) NOT NULL,
  `TenThuoc` varchar(150) NOT NULL,
  `TenHoatChat` varchar(200) DEFAULT NULL,
  `HamLuong` varchar(100) DEFAULT NULL,
  `MaDonVi` smallint(6) NOT NULL,
  `DangBaoChe` varchar(100) DEFAULT NULL,
  `QuyCach` varchar(100) DEFAULT NULL,
  `NhaSanXuat` varchar(200) DEFAULT NULL,
  `NuocSanXuat` varchar(100) DEFAULT NULL,
  `SoDangKy` varchar(50) DEFAULT NULL,
  `HanSuDung` date DEFAULT NULL,
  `SoLuongTon` int(11) NOT NULL DEFAULT 0,
  `TonToiThieu` int(11) NOT NULL DEFAULT 10,
  `GiaNhap` decimal(15,2) NOT NULL,
  `GiaBan` decimal(15,2) NOT NULL,
  `HuongDanSuDung` text DEFAULT NULL,
  `DangHoatDong` tinyint(1) NOT NULL DEFAULT 1,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayCapNhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuoc`
--

INSERT INTO `thuoc` (`MaThuoc`, `MaThuocCode`, `TenThuoc`, `TenHoatChat`, `HamLuong`, `MaDonVi`, `DangBaoChe`, `QuyCach`, `NhaSanXuat`, `NuocSanXuat`, `SoDangKy`, `HanSuDung`, `SoLuongTon`, `TonToiThieu`, `GiaNhap`, `GiaBan`, `HuongDanSuDung`, `DangHoatDong`, `NgayTao`, `NgayCapNhat`) VALUES
(3001, 'THU001', 'Paracetamol 500mg', 'Paracetamol', '500mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Pymepharco', 'Việt Nam', 'VD-12345-15', '2025-12-31', 30, 500, 15000.00, 20000.00, 'Uống 1-2 viên mỗi lần, 3-4 lần/ngày', 1, '2025-01-05 08:00:00', '2026-05-28 13:38:48'),
(3002, 'THU002', 'Ibuprofen 400mg', 'Ibuprofen', '400mg', 1, 'Viên nén bao phim', 'Hộp 10 vỉ x 10 viên', 'Hasan-Dermapharm', 'Đức', 'VD-23456-16', '2024-06-11', 2985, 300, 25000.00, 35000.00, 'Uống 1 viên mỗi lần, 3 lần/ngày sau ăn', 1, '2025-01-05 08:00:00', '2026-05-28 14:33:47'),
(3003, 'THU003', 'Aspirin 100mg', 'Acid acetylsalicylic', '100mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Bayer', 'Đức', 'VD-34567-17', '2028-03-31', 4000, 400, 20000.00, 28000.00, 'Uống 1 viên/ngày sau bữa ăn', 1, '2025-01-10 08:00:00', NULL),
(3004, 'THU004', 'Amoxicillin 500mg', 'Amoxicillin', '500mg', 5, 'Viên nang', 'Hộp 10 vỉ x 10 viên', 'Imexpharm', 'Việt Nam', 'VD-45678-18', '2027-09-30', 5979, 600, 35000.00, 50000.00, 'Uống 1 viên x 3 lần/ngày, uống đủ liệu trình', 1, '2025-01-10 08:00:00', '2026-05-28 14:33:47'),
(3005, 'THU005', 'Cefixime 200mg', 'Cefixime', '200mg', 5, 'Viên nang', 'Hộp 1 vỉ x 10 viên', 'Abbott', 'Mỹ', 'VD-56789-19', '2027-11-30', 2000, 200, 80000.00, 120000.00, 'Uống 1 viên x 2 lần/ngày', 1, '2025-01-15 08:00:00', NULL),
(3006, 'THU006', 'Azithromycin 250mg', 'Azithromycin', '250mg', 1, 'Viên nén bao phim', 'Hộp 1 vỉ x 6 viên', 'Teva', 'Israel', 'VD-67890-20', '2027-08-31', 1500, 150, 90000.00, 135000.00, 'Uống 2 viên ngày đầu, sau đó 1 viên/ngày', 1, '2025-01-15 08:00:00', NULL),
(3007, 'THU007', 'Ciprofloxacin 500mg', 'Ciprofloxacin', '500mg', 1, 'Viên nén bao phim', 'Hộp 10 vỉ x 10 viên', 'Sanofi', 'Pháp', 'VD-78901-21', '2028-01-31', 2500, 250, 60000.00, 85000.00, 'Uống 1 viên x 2 lần/ngày', 1, '2025-01-20 08:00:00', NULL),
(3008, 'THU008', 'Omeprazole 20mg', 'Omeprazole', '20mg', 5, 'Viên nang', 'Hộp 3 vỉ x 10 viên', 'Traphaco', 'Việt Nam', 'VD-89012-22', '2027-10-31', 4440, 450, 40000.00, 60000.00, 'Uống 1 viên/ngày trước bữa ăn sáng', 1, '2025-01-20 08:00:00', '2026-05-28 13:40:06'),
(3009, 'THU009', 'Esomeprazole 40mg', 'Esomeprazole', '40mg', 1, 'Viên nang', 'Hộp 3 vỉ x 10 viên', 'AstraZeneca', 'Thụy Điển', 'VD-90123-23', '2028-02-28', 3000, 300, 70000.00, 105000.00, 'Uống 1 viên/ngày trước bữa ăn', 1, '2025-02-01 08:00:00', NULL),
(3010, 'THU010', 'Domperidone 10mg', 'Domperidone', '10mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Janssen', 'Bỉ', 'VD-01234-24', '2027-07-31', 3470, 350, 30000.00, 45000.00, 'Uống 1 viên x 3 lần/ngày trước bữa ăn', 1, '2025-02-01 08:00:00', '2026-05-28 13:38:48'),
(3011, 'THU011', 'Bromhexine 8mg', 'Bromhexine HCl', '8mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'DHG Pharma', 'Việt Nam', 'VD-12345-25', '2027-12-31', 4000, 400, 25000.00, 38000.00, 'Uống 1-2 viên x 3 lần/ngày', 1, '2025-02-05 08:00:00', NULL),
(3012, 'THU012', 'Siro ho Prospan', 'Chiết xuất lá thường xuân', '7mg/5ml', 2, 'Siro', 'Chai 100ml', 'Engelhard', 'Đức', 'VD-23456-26', '2028-04-30', 1000, 100, 85000.00, 125000.00, 'Uống 5ml x 3 lần/ngày', 1, '2025-02-05 08:00:00', NULL),
(3013, 'THU013', 'Ambroxol 30mg', 'Ambroxol HCl', '30mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Boehringer', 'Đức', 'VD-34567-27', '2027-11-30', 3499, 350, 35000.00, 52000.00, 'Uống 1 viên x 3 lần/ngày sau ăn', 1, '2025-02-10 08:00:00', '2026-06-19 03:51:42'),
(3014, 'THU014', 'Vitamin C 500mg', 'Acid ascorbic', '500mg', 1, 'Viên sủi', 'Hộp 2 tuýp x 10 viên', 'USP', 'Việt Nam', 'VD-45678-28', '2028-06-30', 5000, 500, 45000.00, 68000.00, 'Hòa 1 viên vào nước, uống 1-2 lần/ngày', 1, '2025-02-10 08:00:00', NULL),
(3015, 'THU015', 'Calcium + D3', 'Calcium carbonate + Vitamin D3', '500mg + 200IU', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Pharbaco', 'Việt Nam', 'VD-56789-29', '2028-03-31', 3999, 400, 50000.00, 75000.00, 'Uống 1-2 viên/ngày sau bữa ăn', 1, '2025-02-15 08:00:00', '2026-06-19 03:51:42'),
(3016, 'THU016', 'Vitamin B Complex', 'Các vitamin nhóm B', 'Đa dạng', 1, 'Viên nang', 'Hộp 10 vỉ x 10 viên', 'Mega We Care', 'Thái Lan', 'VD-67890-30', '2028-08-31', 3000, 300, 60000.00, 90000.00, 'Uống 1 viên/ngày sau bữa ăn', 0, '2025-02-15 08:00:00', '2026-05-27 09:48:43'),
(3017, 'THU017', 'Atorvastatin 20mg', 'Atorvastatin', '20mg', 1, 'Viên nén bao phim', 'Hộp 3 vỉ x 10 viên', 'Pfizer', 'Mỹ', 'VD-78901-31', '2027-10-31', 1999, 200, 80000.00, 120000.00, 'Uống 1 viên/ngày vào buổi tối', 1, '2025-02-20 08:00:00', '2026-06-19 03:51:42'),
(3018, 'THU018', 'Bisoprolol 5mg', 'Bisoprolol fumarate', '5mg', 1, 'Viên nén', 'Hộp 3 vỉ x 10 viên', 'Merck', 'Đức', 'VD-89012-32', '2028-05-31', 1799, 180, 75000.00, 112000.00, 'Uống 1 viên/ngày vào buổi sáng', 1, '2025-02-20 08:00:00', '2026-06-19 03:51:42'),
(3019, 'THU019', 'Amlodipine 5mg', 'Amlodipine besilate', '5mg', 1, 'Viên nén', 'Hộp 3 vỉ x 10 viên', 'Novartis', 'Thụy Sĩ', 'VD-90123-33', '2027-09-30', 2499, 250, 65000.00, 98000.00, 'Uống 1 viên/ngày cùng giờ', 1, '2025-03-01 08:00:00', '2026-06-19 03:51:42'),
(3020, 'THU020', 'Metformin 500mg', 'Metformin HCl', '500mg', 1, 'Viên nén bao phim', 'Hộp 10 vỉ x 10 viên', 'Berlin Pharma', 'Việt Nam', 'VD-01234-34', '2027-12-31', 2999, 300, 40000.00, 60000.00, 'Uống 1-2 viên x 2-3 lần/ngày sau ăn', 1, '2025-03-01 08:00:00', '2026-06-19 03:51:42'),
(3021, 'THU021', 'Glimepiride 2mg', 'Glimepiride', '2mg', 1, 'Viên nén', 'Hộp 3 vỉ x 10 viên', 'Sanofi', 'Pháp', 'VD-12345-35', '2028-01-31', 1500, 150, 70000.00, 105000.00, 'Uống 1 viên/ngày trước bữa ăn sáng', 1, '2025-03-05 08:00:00', NULL),
(3022, 'THU022', 'Cetirizine 10mg', 'Cetirizine HCl', '10mg', 1, 'Viên nén bao phim', 'Hộp 10 vỉ x 10 viên', 'UCB', 'Bỉ', 'VD-23456-36', '2027-11-30', 4500, 450, 35000.00, 52000.00, 'Uống 1 viên/ngày vào buổi tối', 1, '2025-03-05 08:00:00', NULL),
(3023, 'THU023', 'Loratadine 10mg', 'Loratadine', '10mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Schering-Plough', 'Mỹ', 'VD-34567-37', '2028-02-29', 4000, 400, 40000.00, 60000.00, 'Uống 1 viên/ngày', 1, '2025-03-10 08:00:00', NULL),
(3024, 'THU024', 'Prednisolone 5mg', 'Prednisolone', '5mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Aventis', 'Pháp', 'VD-45678-38', '2027-08-31', 2000, 200, 50000.00, 75000.00, 'Uống theo chỉ định bác sĩ, giảm dần liều', 1, '2025-03-10 08:00:00', NULL),
(3025, 'THU025', 'Kem Betamethasone', 'Betamethasone', '0.1%', 9, 'Kem bôi', 'Tuýp 10g', 'GlaxoSmithKline', 'Anh', 'VD-56789-39', '2027-10-31', 1000, 100, 35000.00, 52000.00, 'Bôi mỏng lên vùng da bị tổn thương 2 lần/ngày', 1, '2025-03-15 08:00:00', NULL),
(3026, 'THU026', 'Gel Diclofenac', 'Diclofenac sodium', '1%', 9, 'Gel bôi', 'Tuýp 30g', 'Novartis', 'Thụy Sĩ', 'VD-67890-40', '2028-04-30', 800, 80, 65000.00, 98000.00, 'Bôi vào vùng đau, mát-xa nhẹ 3-4 lần/ngày', 1, '2025-03-15 08:00:00', NULL),
(3027, 'THU027', 'Kem Acyclovir 5%', 'Acyclovir', '5%', 9, 'Kem bôi', 'Tuýp 5g', 'Abbott', 'Mỹ', 'VD-78901-41', '2027-12-31', 598, 60, 55000.00, 82000.00, 'Bôi lên vết loét 5 lần/ngày', 1, '2025-03-20 08:00:00', '2026-06-19 03:54:51'),
(3028, 'THU028', 'Thuốc nhỏ mắt Tears Naturale', 'Dextran 70 + Hypromellose', '0.1% + 0.3%', 2, 'Dung dịch nhỏ mắt', 'Chai 15ml', 'Alcon', 'Mỹ', 'VD-89012-42', '2027-06-30', 497, 50, 85000.00, 128000.00, 'Nhỏ 1-2 giọt vào mắt khi cần', 1, '2025-03-20 08:00:00', '2026-06-19 03:54:51'),
(3029, 'THU029', 'Thuốc nhỏ mắt Tobramycin', 'Tobramycin', '0.3%', 2, 'Dung dịch nhỏ mắt', 'Chai 5ml', 'Novartis', 'Thụy Sĩ', 'VD-90123-43', '2027-09-30', 399, 40, 95000.00, 142000.00, 'Nhỏ 1-2 giọt x 4-6 lần/ngày', 1, '2025-03-25 08:00:00', '2026-06-19 03:51:42'),
(3030, 'THU030', 'Ceftriaxone 1g (tiêm)', 'Ceftriaxone sodium', '1g', 3, 'Bột pha tiêm', 'Hộp 1 lọ', 'Vidipha', 'Việt Nam', 'VD-01234-44', '2027-11-30', 1993, 200, 25000.00, 38000.00, 'Tiêm bắp hoặc tiêm tĩnh mạch theo chỉ định', 1, '2025-03-25 08:00:00', '2026-06-19 03:54:51'),
(3031, 'THU031', 'Dexamethasone 4mg/ml (tiêm)', 'Dexamethasone sodium phosphate', '4mg/ml', 3, 'Dung dịch tiêm', 'Hộp 25 ống x 2ml', 'Roussel', 'Pháp', 'VD-12345-45', '2028-03-31', 1495, 150, 120000.00, 180000.00, 'Tiêm bắp hoặc tiêm tĩnh mạch theo chỉ định', 1, '2025-04-01 08:00:00', '2026-06-21 03:14:08'),
(3032, 'THU032', 'Vitamin B1 100mg (tiêm)', 'Thiamine HCl', '100mg/2ml', 3, 'Dung dịch tiêm', 'Hộp 50 ống x 2ml', 'Unipharma', 'Việt Nam', 'VD-23456-46', '2027-10-31', 992, 100, 50000.00, 75000.00, 'Tiêm bắp 1-2 ống/ngày', 1, '2025-04-01 08:00:00', '2026-06-21 03:14:08'),
(3033, 'THU033', 'Natri Clorid 0.9%', 'Natri Clorid', '0.9%', 2, 'Dung dịch truyền', 'Chai 500ml', 'Fresenius Kabi', 'Đức', 'VD-34567-47', '2027-12-31', 2992, 300, 25000.00, 38000.00, 'Truyền tĩnh mạch theo chỉ định', 1, '2025-04-05 08:00:00', '2026-06-21 03:14:08'),
(3034, 'THU034', 'Glucose 5%', 'Glucose monohydrate', '5%', 2, 'Dung dịch truyền', 'Chai 500ml', 'B.Braun', 'Đức', 'VD-45678-48', '2028-01-31', 2496, 250, 28000.00, 42000.00, 'Truyền tĩnh mạch theo chỉ định', 1, '2025-04-05 08:00:00', '2026-06-21 03:14:08'),
(3035, 'THU035', 'Siro Paracetamol 250mg/5ml', 'Paracetamol', '250mg/5ml', 2, 'Siro', 'Chai 60ml', 'Pymepharco', 'Việt Nam', 'VD-56789-49', '2027-09-30', 1993, 200, 35000.00, 52000.00, 'Trẻ em uống 5-10ml x 3-4 lần/ngày', 1, '2025-04-10 08:00:00', '2026-06-20 11:03:52'),
(3036, 'THU036', 'Siro Zinnat 125mg/5ml', 'Cefuroxime axetil', '125mg/5ml', 2, 'Siro bột pha hỗn dịch', 'Chai 50ml', 'GlaxoSmithKline', 'Anh', 'VD-67890-50', '2027-07-31', 797, 80, 180000.00, 270000.00, 'Lắc đều, uống 5ml x 2 lần/ngày sau ăn', 1, '2025-04-10 08:00:00', '2026-06-20 08:45:58'),
(3037, 'THU037', 'Loperamide 2mg', 'Loperamide HCl', '2mg', 1, 'Viên nang', 'Hộp 10 vỉ x 10 viên', 'Johnson & Johnson', 'Mỹ', 'VD-78901-51', '2027-11-30', 1491, 150, 40000.00, 60000.00, 'Uống 2 viên ban đầu, sau đó 1 viên sau mỗi lần đi ngoài', 1, '2025-04-15 08:00:00', '2026-06-21 03:47:09'),
(3038, 'THU038', 'Lactuloza siro', 'Lactuloza', '667mg/ml', 2, 'Siro', 'Chai 200ml', 'Abbott', 'Mỹ', 'VD-89012-52', '2028-02-28', 990, 100, 95000.00, 142000.00, 'Uống 15-30ml/ngày', 1, '2025-04-15 08:00:00', '2026-06-21 03:47:09'),
(3039, 'THU039', 'Diazepam 5mg', 'Diazepam', '5mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'Roche', 'Thụy Sĩ', 'VD-90123-53', '2027-08-31', 496, 50, 60000.00, 90000.00, 'Uống 1 viên x 2-3 lần/ngày theo chỉ định', 1, '2025-04-20 08:00:00', '2026-06-20 10:46:06'),
(3040, 'THU040', 'Folic Acid 5mg', 'Acid folic', '5mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'DHG Pharma', 'Việt Nam', 'VD-01234-54', '2028-05-31', 2990, 300, 30000.00, 45000.00, 'Uống 1 viên/ngày', 1, '2025-04-20 08:00:00', '2026-06-21 03:47:09'),
(3041, 'THU041', 'Simvastatin 20mg', 'Simvastatin', '20mg', 1, 'Viên nén bao phim', 'Hộp 3 vỉ x 10 viên', 'Merck', 'Mỹ', 'VD-12345-55', '2027-10-31', 1185, 120, 75000.00, 112000.00, 'Uống 1 viên/ngày vào buổi tối', 1, '2025-04-25 08:00:00', '2026-06-21 03:14:08'),
(3042, 'THU042', 'Losartan 50mg', 'Losartan potassium', '50mg', 1, 'Viên nén bao phim', 'Hộp 3 vỉ x 10 viên', 'Merck', 'Đức', 'VD-23456-56', '2028-06-30', 1489, 150, 80000.00, 120000.00, 'Uống 1 viên/ngày', 1, '2025-04-25 08:00:00', '2026-06-20 11:03:52'),
(3043, 'THU043', 'Ranitidine 150mg', 'Ranitidine HCl', '150mg', 1, 'Viên nén bao phim', 'Hộp 10 vỉ x 10 viên', 'GlaxoSmithKline', 'Anh', 'VD-34567-57', '2027-09-30', 2000, 200, 45000.00, 68000.00, 'Uống 1 viên x 2 lần/ngày', 0, '2025-05-01 08:00:00', '2026-06-19 01:00:24'),
(3044, 'THU044', 'Clopidogrel 75mg', 'Clopidogrel bisulfate', '75mg', 1, 'Viên nén bao phim', 'Hộp 3 vỉ x 10 viên', 'Sanofi', 'Pháp', 'VD-45678-58', '2028-03-31', 986, 100, 95000.00, 142000.00, 'Uống 1 viên/ngày', 1, '2025-05-01 08:00:00', '2026-06-21 03:47:09'),
(3045, 'THU045', 'Salbutamol 2mg', 'Salbutamol sulfate', '2mg', 1, 'Viên nén', 'Hộp 10 vỉ x 10 viên', 'GlaxoSmithKline', 'Anh', 'VD-56789-59', '2027-11-30', 1788, 180, 55000.00, 82000.00, 'Uống 1 viên x 3-4 lần/ngày', 1, '2025-05-05 08:00:00', '2026-06-29 08:17:39'),
(3046, 'THU046', 'Ống xịt Ventolin', 'Salbutamol', '100mcg/liều', 2, 'Dung dịch xịt hít', 'Hộp 1 ống 200 liều', 'GlaxoSmithKline', 'Anh', 'VD-67890-60', '2028-01-31', 491, 50, 120000.00, 180000.00, 'Hít 1-2 nhát khi cần, cách nhau 4-6 giờ', 1, '2025-05-05 08:00:00', '2026-06-29 08:17:39'),
(3047, 'THU047', 'Montelukast 10mg', 'Montelukast sodium', '10mg', 1, 'Viên nén bao phim', 'Hộp 3 vỉ x 10 viên', 'Merck', 'Mỹ', 'VD-78901-61', '2027-12-31', 1193, 120, 85000.00, 128000.00, '', 0, '2025-05-10 08:00:00', '2026-06-29 11:11:47'),
(3048, 'THU048', 'Fluconazole 150mg', 'Fluconazole', '150mg', 5, 'Viên nang', 'Hộp 1 viên', 'Pfizer', 'Mỹ', 'VD-89012-62', '2027-08-31', 790, 80, 75000.00, 112000.00, 'Uống 1 viên liều đơn', 1, '2025-05-10 08:00:00', '2026-06-29 07:30:20'),
(3049, 'THU049', 'Trimetazidine 35mg', 'Trimetazidine diHCl', '35mg', 1, 'Viên nén giải phóng chậm', 'Hộp 6 vỉ x 10 viên', '', '', '', '2028-04-30', 1000, 100, 0.00, 150000.00, '', 0, '2025-05-15 08:00:00', '2026-06-19 01:00:43'),
(3050, 'THU050', 'Ginkgo Biloba 40mg', 'Chiết xuất Bạch quả', '40mg', 1, 'Viên nén bao phim', 'Hộp 6 vỉ x 10 viên', 'Ipsen', 'Pháp', 'VD-01234-64', '2028-02-29', 1500, 150, 90000.00, 135000.00, 'Uống 1 viên x 3 lần/ngày', 0, '2025-05-15 08:00:00', '2026-06-19 00:56:48'),
(3051, '1234', 'Trimetazidine 15mg', 'Trimetazidine', '35mg', 1, 'Viên nén giải phóng chậm', 'Hộp 6 vỉ x 10 viên', '', '', '', '2026-07-18', 995, 10, 0.00, 20000.00, '', 1, '2026-06-19 00:44:27', '2026-06-29 11:11:56'),
(3052, 'T123qw', 'Trimetazidine AXS', 'Trimetazidine FD', '350mg', 2, 'Viên nén giải phóng chậm', 'Hộp 6 vỉ x 10 viên', '1234222', '1234', '123422', '2026-06-27', 994, 10, 50000.00, 100000.00, '', 1, '2026-06-19 01:02:03', '2026-06-29 11:11:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tinnhan`
--

CREATE TABLE `tinnhan` (
  `MaTinNhan` int(11) NOT NULL,
  `MaNguoiGui` int(11) NOT NULL,
  `MaNguoiNhan` int(11) NOT NULL,
  `NoiDung` text NOT NULL,
  `DaDoc` tinyint(1) NOT NULL DEFAULT 0,
  `ThoiGianGui` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tinnhan`
--

INSERT INTO `tinnhan` (`MaTinNhan`, `MaNguoiGui`, `MaNguoiNhan`, `NoiDung`, `DaDoc`, `ThoiGianGui`) VALUES
(19001, 23, 3, 'Bác sĩ ơi, tôi uống thuốc Omeprazole được 2 tuần rồi nhưng vẫn còn đầy bụng sau ăn. Có bình thường không ạ?', 1, '2026-05-18 10:30:00'),
(19002, 3, 23, 'Chào bạn! Triệu chứng đầy bụng nhẹ trong 2-4 tuần đầu điều trị là bình thường. Bạn hãy tiếp tục uống thuốc đúng giờ và ăn chậm, nhai kỹ. Nếu sau 1 tuần vẫn không cải thiện, hãy đến tái khám nhé.', 1, '2026-05-18 12:00:00'),
(19003, 23, 3, 'Dạ, cảm ơn bác sĩ! Tôi sẽ làm theo ạ.', 1, '2026-05-18 12:30:00'),
(19004, 31, 11, 'Bác sĩ ơi, tôi cảm thấy hay hoa mắt lúc đứng dậy nhanh sau khi uống thuốc huyết áp. Có phải tác dụng phụ không ạ?', 1, '2026-05-19 14:00:00'),
(19005, 11, 31, 'Đây là tình trạng hạ huyết áp tư thế, khá phổ biến khi dùng thuốc hạ áp. Bạn hãy đứng dậy từ từ, không đột ngột. Nếu triệu chứng nặng hơn (ngất, tức ngực) hãy đến cấp cứu ngay. Tôi sẽ điều chỉnh liều thuốc khi bạn tái khám.', 1, '2026-05-19 15:30:00'),
(19006, 42, 12, 'Bác sĩ, tôi bị đau đầu dữ dội hơn hôm nay, buồn nôn nhiều. Tôi đã uống thuốc nhưng không bớt. Tôi phải làm gì ạ?', 0, '2026-05-22 09:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trangthailichchhen`
--

CREATE TABLE `trangthailichchhen` (
  `MaTrangThai` smallint(6) NOT NULL,
  `TenTrangThai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trangthailichchhen`
--

INSERT INTO `trangthailichchhen` (`MaTrangThai`, `TenTrangThai`) VALUES
(1, 'CHO_XAC_NHAN'),
(4, 'DA_HUY'),
(2, 'DA_XAC_NHAN'),
(3, 'DOI_LICH'),
(5, 'HOAN_THANH');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trangthaiphieukham`
--

CREATE TABLE `trangthaiphieukham` (
  `MaTrangThai` smallint(6) NOT NULL,
  `TenTrangThai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trangthaiphieukham`
--

INSERT INTO `trangthaiphieukham` (`MaTrangThai`, `TenTrangThai`) VALUES
(1, 'Cấp cứu'),
(7, 'Chờ cấp thuốc'),
(3, 'Chờ khám bệnh'),
(2, 'Chờ sơ khám'),
(8, 'Chờ thanh toán'),
(4, 'Chờ xét nghiệm'),
(6, 'Đã xét nghiệm'),
(5, 'Đang xét nghiệm'),
(9, 'Hoàn thành');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vaitro`
--

CREATE TABLE `vaitro` (
  `MaVaiTro` smallint(6) NOT NULL,
  `TenVaiTro` varchar(50) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vaitro`
--

INSERT INTO `vaitro` (`MaVaiTro`, `TenVaiTro`, `MoTa`) VALUES
(1, 'LE_TAN', 'Nhân viên lễ tân'),
(2, 'DIEU_DUONG', 'Điều dưỡng'),
(3, 'BAC_SI', 'Bác sĩ'),
(4, 'KY_THUAT_VIEN', 'Chuyên viên xét nghiệm'),
(5, 'DUOC_SI', 'Dược sĩ'),
(6, 'QUAN_LY', 'Quản lý phòng khám'),
(7, 'BENH_NHAN', 'Bệnh nhân');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `view_chitietdonthuoc`
-- (See below for the actual view)
--
CREATE TABLE `view_chitietdonthuoc` (
`MaDonThuoc` int(11)
,`MaPhieuKham` int(11)
,`MaPhieuKhamCode` varchar(20)
,`TenBenhNhan` varchar(100)
,`MaBN` varchar(20)
,`NgayKeToa` datetime
,`LoiDanChung` text
,`MaThuoc` int(11)
,`TenThuoc` varchar(150)
,`SoLuong` int(11)
,`DonGia` decimal(15,2)
,`ThanhTien` decimal(25,2)
,`CachDung` text
);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `v_doanhthu`
-- (See below for the actual view)
--
CREATE TABLE `v_doanhthu` (
`MaHoaDon` int(11)
,`SoHoaDon` varchar(20)
,`NgayKham` date
,`MaBenhNhan` varchar(20)
,`TenBenhNhan` varchar(100)
,`TenBacSi` varchar(100)
,`TenChuyenKhoa` varchar(100)
,`TongThanhToan` decimal(15,2)
,`NgayThanhToan` datetime
,`PhuongThucTT` varchar(50)
);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `v_hoadon_chitiet`
-- (See below for the actual view)
--
CREATE TABLE `v_hoadon_chitiet` (
`MaPhieuKham` int(11)
,`MaPhieuKhamCode` varchar(20)
,`MaBenhNhan` int(11)
,`LoaiMuc` varchar(5)
,`MaMuc` int(11)
,`TenMuc` varchar(251)
,`SoLuong` int(11)
,`DonGia` decimal(15,2)
,`NgayTaoMuc` datetime
);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `v_lichhen_hieuluc`
-- (See below for the actual view)
--
CREATE TABLE `v_lichhen_hieuluc` (
);

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `v_tonkhothuoc`
-- (See below for the actual view)
--
CREATE TABLE `v_tonkhothuoc` (
`MaThuoc` int(11)
,`MaThuocCode` varchar(20)
,`TenThuoc` varchar(150)
,`TenHoatChat` varchar(200)
,`HamLuong` varchar(100)
,`TenDonVi` varchar(50)
,`SoLuongTon` int(11)
,`TonToiThieu` int(11)
,`CanhBaoHetHang` int(1)
,`HanSuDung` date
,`DaHetHan` int(1)
,`GiaBan` decimal(15,2)
);

-- --------------------------------------------------------

--
-- Cấu trúc cho view `view_chitietdonthuoc`
--
DROP TABLE IF EXISTS `view_chitietdonthuoc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_chitietdonthuoc`  AS SELECT `dt`.`MaDonThuoc` AS `MaDonThuoc`, `pk`.`MaPhieuKham` AS `MaPhieuKham`, `pk`.`MaPhieuKhamCode` AS `MaPhieuKhamCode`, `bn`.`HoTen` AS `TenBenhNhan`, `bn`.`MaBN` AS `MaBN`, `dt`.`NgayKeToa` AS `NgayKeToa`, `dt`.`LoiDan` AS `LoiDanChung`, `ct`.`MaThuoc` AS `MaThuoc`, `t`.`TenThuoc` AS `TenThuoc`, `ct`.`SoLuong` AS `SoLuong`, `ct`.`DonGia` AS `DonGia`, `ct`.`SoLuong`* `ct`.`DonGia` AS `ThanhTien`, `ct`.`CachDung` AS `CachDung` FROM ((((`donthuoc` `dt` join `phieukham` `pk` on(`dt`.`MaPhieuKham` = `pk`.`MaPhieuKham`)) join `benhnhan` `bn` on(`pk`.`MaBenhNhan` = `bn`.`MaBenhNhan`)) join `chitietdonthuoc` `ct` on(`dt`.`MaDonThuoc` = `ct`.`MaDonThuoc`)) join `thuoc` `t` on(`ct`.`MaThuoc` = `t`.`MaThuoc`)) ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `v_doanhthu`
--
DROP TABLE IF EXISTS `v_doanhthu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_doanhthu`  AS SELECT `hd`.`MaHoaDon` AS `MaHoaDon`, `hd`.`SoHoaDon` AS `SoHoaDon`, `pk`.`NgayKham` AS `NgayKham`, `bn`.`MaBN` AS `MaBenhNhan`, `bn`.`HoTen` AS `TenBenhNhan`, `nv`.`HoTen` AS `TenBacSi`, `ck`.`TenChuyenKhoa` AS `TenChuyenKhoa`, `hd`.`TongThanhToan` AS `TongThanhToan`, `hd`.`NgayThanhToan` AS `NgayThanhToan`, `pt`.`TenPhuongThuc` AS `PhuongThucTT` FROM ((((((`hoadon` `hd` join `phieukham` `pk` on(`pk`.`MaPhieuKham` = `hd`.`MaPhieuKham`)) join `benhnhan` `bn` on(`bn`.`MaBenhNhan` = `pk`.`MaBenhNhan`)) join `nhanvien` `nv` on(`nv`.`MaNhanVien` = `pk`.`MaBacSi`)) join `chuyenkhoa` `ck` on(`ck`.`MaChuyenKhoa` = `pk`.`MaChuyenKhoa`)) join `thanhtoan` `tt` on(`tt`.`MaHoaDon` = `hd`.`MaHoaDon`)) join `phuongthuctt` `pt` on(`pt`.`MaPhuongThuc` = `tt`.`MaPhuongThuc`)) WHERE `hd`.`TrangThai` = 'DA_THANH_TOAN' ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `v_hoadon_chitiet`
--
DROP TABLE IF EXISTS `v_hoadon_chitiet`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_hoadon_chitiet`  AS SELECT `pk`.`MaPhieuKham` AS `MaPhieuKham`, `pk`.`MaPhieuKhamCode` AS `MaPhieuKhamCode`, `pk`.`MaBenhNhan` AS `MaBenhNhan`, 'CLS' AS `LoaiMuc`, `lcls`.`MaLoaiCLS` AS `MaMuc`, `lcls`.`TenLoaiCLS` AS `TenMuc`, 1 AS `SoLuong`, `lcls`.`DonGia` AS `DonGia`, `cd`.`NgayChiDinh` AS `NgayTaoMuc` FROM ((`phieukham` `pk` join `chidinhcls` `cd` on(`pk`.`MaPhieuKham` = `cd`.`MaPhieuKham`)) join `loaiclsn` `lcls` on(`cd`.`MaLoaiCLS` = `lcls`.`MaLoaiCLS`))union all select `pk`.`MaPhieuKham` AS `MaPhieuKham`,`pk`.`MaPhieuKhamCode` AS `MaPhieuKhamCode`,`pk`.`MaBenhNhan` AS `MaBenhNhan`,'Thuoc' AS `LoaiMuc`,`t`.`MaThuoc` AS `MaMuc`,concat(`t`.`TenThuoc`,' ',coalesce(`t`.`HamLuong`,'')) AS `TenMuc`,`ctdt`.`SoLuong` AS `SoLuong`,`ctdt`.`DonGia` AS `DonGia`,`dt`.`NgayKeToa` AS `NgayTaoMuc` from (((`phieukham` `pk` join `donthuoc` `dt` on(`pk`.`MaPhieuKham` = `dt`.`MaPhieuKham`)) join `chitietdonthuoc` `ctdt` on(`dt`.`MaDonThuoc` = `ctdt`.`MaDonThuoc`)) join `thuoc` `t` on(`ctdt`.`MaThuoc` = `t`.`MaThuoc`))  ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `v_lichhen_hieuluc`
--
DROP TABLE IF EXISTS `v_lichhen_hieuluc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_lichhen_hieuluc`  AS SELECT `lh`.`MaLichHen` AS `MaLichHen`, `bn`.`MaBN` AS `MaBenhNhan`, `bn`.`HoTen` AS `TenBenhNhan`, `bn`.`SoDienThoai` AS `SoDienThoai`, `nv`.`HoTen` AS `TenBacSi`, `ck`.`TenChuyenKhoa` AS `TenChuyenKhoa`, `lh`.`NgayHen` AS `NgayHen`, `lh`.`GioHen` AS `GioHen`, `tt`.`TenTrangThai` AS `TrangThai` FROM ((((`lichhen` `lh` join `benhnhan` `bn` on(`bn`.`MaBenhNhan` = `lh`.`MaBenhNhan`)) join `nhanvien` `nv` on(`nv`.`MaNhanVien` = `lh`.`MaBacSi`)) join `chuyenkhoa` `ck` on(`ck`.`MaChuyenKhoa` = `lh`.`MaChuyenKhoa`)) join `trangthailichchhen` `tt` on(`tt`.`MaTrangThai` = `lh`.`MaTrangThai`)) WHERE `tt`.`TenTrangThai` in ('CHO_XAC_NHAN','DA_XAC_NHAN') ;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `v_tonkhothuoc`
--
DROP TABLE IF EXISTS `v_tonkhothuoc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tonkhothuoc`  AS SELECT `t`.`MaThuoc` AS `MaThuoc`, `t`.`MaThuocCode` AS `MaThuocCode`, `t`.`TenThuoc` AS `TenThuoc`, `t`.`TenHoatChat` AS `TenHoatChat`, `t`.`HamLuong` AS `HamLuong`, `dv`.`TenDonVi` AS `TenDonVi`, `t`.`SoLuongTon` AS `SoLuongTon`, `t`.`TonToiThieu` AS `TonToiThieu`, CASE WHEN `t`.`SoLuongTon` <= `t`.`TonToiThieu` THEN 1 ELSE 0 END AS `CanhBaoHetHang`, `t`.`HanSuDung` AS `HanSuDung`, CASE WHEN `t`.`HanSuDung` < curdate() THEN 1 ELSE 0 END AS `DaHetHan`, `t`.`GiaBan` AS `GiaBan` FROM (`thuoc` `t` join `donvitinh` `dv` on(`dv`.`MaDonVi` = `t`.`MaDonVi`)) WHERE `t`.`DangHoatDong` = 1 ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `benhan`
--
ALTER TABLE `benhan`
  ADD PRIMARY KEY (`MaBenhAn`),
  ADD UNIQUE KEY `UQ_BenhAn_MaCode` (`MaBenhAnCode`),
  ADD KEY `FK_BenhAn_BenhNhan` (`MaBenhNhan`);

--
-- Chỉ mục cho bảng `benhan_phieukham`
--
ALTER TABLE `benhan_phieukham`
  ADD PRIMARY KEY (`MaBenhAn`,`MaPhieuKham`),
  ADD KEY `FK_BAPK_PhieuKham` (`MaPhieuKham`);

--
-- Chỉ mục cho bảng `benhnhan`
--
ALTER TABLE `benhnhan`
  ADD PRIMARY KEY (`MaBenhNhan`),
  ADD UNIQUE KEY `UQ_BenhNhan_MaBN` (`MaBN`),
  ADD UNIQUE KEY `UQ_BenhNhan_SoBHYT` (`SoBHYT`),
  ADD UNIQUE KEY `UQ_BenhNhan_TaiKhoan` (`MaTaiKhoan`),
  ADD UNIQUE KEY `UC_CCCD` (`CCCD`),
  ADD KEY `IDX_BenhNhan_SDT` (`SoDienThoai`),
  ADD KEY `IDX_BenhNhan_BHYT` (`SoBHYT`),
  ADD KEY `IDX_BenhNhan_HoTen` (`HoTen`);

--
-- Chỉ mục cho bảng `cauhinhhethong`
--
ALTER TABLE `cauhinhhethong`
  ADD PRIMARY KEY (`KhoacCauHinh`),
  ADD KEY `FK_CauHinh_NguoiCapNhat` (`NguoiCapNhat`);

--
-- Chỉ mục cho bảng `chidinhcls`
--
ALTER TABLE `chidinhcls`
  ADD PRIMARY KEY (`MaChiDinh`),
  ADD KEY `FK_ChiDinhCLS_LoaiCLS` (`MaLoaiCLS`),
  ADD KEY `IDX_ChiDinhCLS_PhieuKham` (`MaPhieuKham`);

--
-- Chỉ mục cho bảng `chitietdonthuoc`
--
ALTER TABLE `chitietdonthuoc`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD KEY `FK_CTDT_DonThuoc` (`MaDonThuoc`),
  ADD KEY `FK_CTDT_Thuoc` (`MaThuoc`);

--
-- Chỉ mục cho bảng `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD KEY `FK_CTPN_PhieuNhap` (`MaPhieuNhap`),
  ADD KEY `FK_CTPN_Thuoc` (`MaThuoc`);

--
-- Chỉ mục cho bảng `chitietphieuxuat`
--
ALTER TABLE `chitietphieuxuat`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD KEY `FK_CTPX_PhieuXuat` (`MaPhieuXuat`),
  ADD KEY `FK_CTPX_Thuoc` (`MaThuoc`);

--
-- Chỉ mục cho bảng `chuyenkhoa`
--
ALTER TABLE `chuyenkhoa`
  ADD PRIMARY KEY (`MaChuyenKhoa`),
  ADD UNIQUE KEY `UQ_ChuyenKhoa_Ten` (`TenChuyenKhoa`);

--
-- Chỉ mục cho bảng `danhgiadichvu`
--
ALTER TABLE `danhgiadichvu`
  ADD PRIMARY KEY (`MaDanhGia`),
  ADD UNIQUE KEY `UQ_DanhGia_BN_Phieu` (`MaBenhNhan`,`MaPhieuKham`),
  ADD KEY `FK_DanhGia_Phieu` (`MaPhieuKham`),
  ADD KEY `FK_DanhGia_BacSi` (`MaBacSi`);

--
-- Chỉ mục cho bảng `donthuoc`
--
ALTER TABLE `donthuoc`
  ADD PRIMARY KEY (`MaDonThuoc`),
  ADD UNIQUE KEY `UQ_DonThuoc_PhieuKham` (`MaPhieuKham`),
  ADD KEY `IDX_DonThuoc_PhieuKham` (`MaPhieuKham`);

--
-- Chỉ mục cho bảng `donvitinh`
--
ALTER TABLE `donvitinh`
  ADD PRIMARY KEY (`MaDonVi`),
  ADD UNIQUE KEY `UQ_DonViTinh_Ten` (`TenDonVi`);

--
-- Chỉ mục cho bảng `filehosobenhnhan`
--
ALTER TABLE `filehosobenhnhan`
  ADD PRIMARY KEY (`MaFile`),
  ADD KEY `FK_File_BenhNhan` (`MaBenhNhan`),
  ADD KEY `FK_File_PhieuKham` (`MaPhieuKham`),
  ADD KEY `FK_File_NguoiTai` (`NguoiTai`);

--
-- Chỉ mục cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`MaHoaDon`),
  ADD UNIQUE KEY `UQ_HoaDon_So` (`SoHoaDon`),
  ADD UNIQUE KEY `UQ_HoaDon_PhieuKham` (`MaPhieuKham`),
  ADD KEY `IDX_HoaDon_TrangThai` (`TrangThai`),
  ADD KEY `IDX_HoaDon_NgayThanhToan` (`NgayThanhToan`);

--
-- Chỉ mục cho bảng `ketquacls`
--
ALTER TABLE `ketquacls`
  ADD PRIMARY KEY (`MaKetQua`),
  ADD UNIQUE KEY `UQ_KetQuaCLS_ChiDinh` (`MaChiDinh`),
  ADD KEY `FK_KetQuaCLS_NVThucHien` (`MaNVThucHien`);

--
-- Chỉ mục cho bảng `lichhen`
--
ALTER TABLE `lichhen`
  ADD PRIMARY KEY (`MaLichHen`),
  ADD KEY `FK_LichHen_BenhNhan` (`MaBenhNhan`),
  ADD KEY `FK_LichHen_TrangThai` (`MaTrangThai`),
  ADD KEY `FK_LichHen_NguoiTao` (`NguoiTao`),
  ADD KEY `IDX_LichHen_NgayHen` (`NgayHen`);

--
-- Chỉ mục cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD PRIMARY KEY (`MaLich`),
  ADD UNIQUE KEY `UQ_LichLamViec` (`MaBacSi`,`NgayLam`,`Ca`);

--
-- Chỉ mục cho bảng `loaiclsn`
--
ALTER TABLE `loaiclsn`
  ADD PRIMARY KEY (`MaLoaiCLS`),
  ADD UNIQUE KEY `UQ_LoaiCLS_Ten` (`TenLoaiCLS`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNhanVien`),
  ADD UNIQUE KEY `UQ_NhanVien_TaiKhoan` (`MaTaiKhoan`),
  ADD UNIQUE KEY `UQ_NhanVien_CCCD` (`CCCD`),
  ADD KEY `FK_NhanVien_ChuyenKhoa` (`MaChuyenKhoa`);

--
-- Chỉ mục cho bảng `nhatkyhoatdong`
--
ALTER TABLE `nhatkyhoatdong`
  ADD PRIMARY KEY (`MaNhatKy`),
  ADD KEY `IDX_NhatKy_TaiKhoan` (`MaTaiKhoan`),
  ADD KEY `IDX_NhatKy_ThoiGian` (`ThoiGian`);

--
-- Chỉ mục cho bảng `phieukham`
--
ALTER TABLE `phieukham`
  ADD PRIMARY KEY (`MaPhieuKham`),
  ADD UNIQUE KEY `UQ_PhieuKham_MaCode` (`MaPhieuKhamCode`),
  ADD KEY `FK_PhieuKham_ChuyenKhoa` (`MaChuyenKhoa`),
  ADD KEY `IDX_PhieuKham_BenhNhan` (`MaBenhNhan`),
  ADD KEY `IDX_PhieuKham_BacSi` (`MaBacSi`),
  ADD KEY `IDX_PhieuKham_NgayKham` (`NgayKham`),
  ADD KEY `IDX_PhieuKham_TrangThai` (`MaTrangThai`),
  ADD KEY `idx_phieukham_malichhen` (`MaLichHen`);

--
-- Chỉ mục cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`MaPhieuNhap`),
  ADD UNIQUE KEY `UQ_PhieuNhap_So` (`SoPhieuNhap`),
  ADD KEY `FK_PhieuNhap_NguoiNhap` (`NguoiNhap`);

--
-- Chỉ mục cho bảng `phieuxuat`
--
ALTER TABLE `phieuxuat`
  ADD PRIMARY KEY (`MaPhieuXuat`),
  ADD UNIQUE KEY `UQ_PhieuXuat_So` (`SoPhieuXuat`),
  ADD KEY `FK_PhieuXuat_BenhNhan` (`MaBenhNhan`),
  ADD KEY `FK_PhieuXuat_NguoiXuat` (`NguoiXuat`);

--
-- Chỉ mục cho bảng `phuongthuctt`
--
ALTER TABLE `phuongthuctt`
  ADD PRIMARY KEY (`MaPhuongThuc`),
  ADD UNIQUE KEY `UQ_PhuongThucTT_Ten` (`TenPhuongThuc`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTaiKhoan`),
  ADD UNIQUE KEY `UQ_TaiKhoan_TenDangNhap` (`TenDangNhap`),
  ADD UNIQUE KEY `UQ_TaiKhoan_SDT` (`SoDienThoai`);

--
-- Chỉ mục cho bảng `taikhoan_vaitro`
--
ALTER TABLE `taikhoan_vaitro`
  ADD PRIMARY KEY (`MaTaiKhoan`,`MaVaiTro`),
  ADD KEY `FK_TKVT_VaiTro` (`MaVaiTro`);

--
-- Chỉ mục cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`MaThanhToan`),
  ADD KEY `FK_ThanhToan_PhuongThuc` (`MaPhuongThuc`),
  ADD KEY `FK_ThanhToan_NguoiThu` (`NguoiThu`),
  ADD KEY `IDX_ThanhToan_HoaDon` (`MaHoaDon`);

--
-- Chỉ mục cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`MaThongBao`),
  ADD KEY `FK_ThongBao_NguoiNhan` (`MaNguoiNhan`);

--
-- Chỉ mục cho bảng `thuoc`
--
ALTER TABLE `thuoc`
  ADD PRIMARY KEY (`MaThuoc`),
  ADD UNIQUE KEY `UQ_Thuoc_MaCode` (`MaThuocCode`),
  ADD KEY `FK_Thuoc_DonVi` (`MaDonVi`),
  ADD KEY `IDX_Thuoc_TenThuoc` (`TenThuoc`),
  ADD KEY `IDX_Thuoc_HanSuDung` (`HanSuDung`);

--
-- Chỉ mục cho bảng `tinnhan`
--
ALTER TABLE `tinnhan`
  ADD PRIMARY KEY (`MaTinNhan`),
  ADD KEY `FK_TinNhan_NguoiGui` (`MaNguoiGui`),
  ADD KEY `FK_TinNhan_NguoiNhan` (`MaNguoiNhan`);

--
-- Chỉ mục cho bảng `trangthailichchhen`
--
ALTER TABLE `trangthailichchhen`
  ADD PRIMARY KEY (`MaTrangThai`),
  ADD UNIQUE KEY `UQ_TrangThaiLH_Ten` (`TenTrangThai`);

--
-- Chỉ mục cho bảng `trangthaiphieukham`
--
ALTER TABLE `trangthaiphieukham`
  ADD PRIMARY KEY (`MaTrangThai`),
  ADD UNIQUE KEY `UQ_TrangThaiPK_Ten` (`TenTrangThai`);

--
-- Chỉ mục cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  ADD PRIMARY KEY (`MaVaiTro`),
  ADD UNIQUE KEY `UQ_VaiTro_Ten` (`TenVaiTro`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `benhan`
--
ALTER TABLE `benhan`
  MODIFY `MaBenhAn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17007;

--
-- AUTO_INCREMENT cho bảng `benhnhan`
--
ALTER TABLE `benhnhan`
  MODIFY `MaBenhNhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT cho bảng `chidinhcls`
--
ALTER TABLE `chidinhcls`
  MODIFY `MaChiDinh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7059;

--
-- AUTO_INCREMENT cho bảng `chitietdonthuoc`
--
ALTER TABLE `chitietdonthuoc`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10152;

--
-- AUTO_INCREMENT cho bảng `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12014;

--
-- AUTO_INCREMENT cho bảng `chitietphieuxuat`
--
ALTER TABLE `chitietphieuxuat`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14124;

--
-- AUTO_INCREMENT cho bảng `chuyenkhoa`
--
ALTER TABLE `chuyenkhoa`
  MODIFY `MaChuyenKhoa` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `danhgiadichvu`
--
ALTER TABLE `danhgiadichvu`
  MODIFY `MaDanhGia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `donthuoc`
--
ALTER TABLE `donthuoc`
  MODIFY `MaDonThuoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9039;

--
-- AUTO_INCREMENT cho bảng `donvitinh`
--
ALTER TABLE `donvitinh`
  MODIFY `MaDonVi` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `filehosobenhnhan`
--
ALTER TABLE `filehosobenhnhan`
  MODIFY `MaFile` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `MaHoaDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15045;

--
-- AUTO_INCREMENT cho bảng `ketquacls`
--
ALTER TABLE `ketquacls`
  MODIFY `MaKetQua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8039;

--
-- AUTO_INCREMENT cho bảng `lichhen`
--
ALTER TABLE `lichhen`
  MODIFY `MaLichHen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  MODIFY `MaLich` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4101;

--
-- AUTO_INCREMENT cho bảng `loaiclsn`
--
ALTER TABLE `loaiclsn`
  MODIFY `MaLoaiCLS` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNhanVien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1030;

--
-- AUTO_INCREMENT cho bảng `nhatkyhoatdong`
--
ALTER TABLE `nhatkyhoatdong`
  MODIFY `MaNhatKy` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21019;

--
-- AUTO_INCREMENT cho bảng `phieukham`
--
ALTER TABLE `phieukham`
  MODIFY `MaPhieuKham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  MODIFY `MaPhieuNhap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11007;

--
-- AUTO_INCREMENT cho bảng `phieuxuat`
--
ALTER TABLE `phieuxuat`
  MODIFY `MaPhieuXuat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13057;

--
-- AUTO_INCREMENT cho bảng `phuongthuctt`
--
ALTER TABLE `phuongthuctt`
  MODIFY `MaPhuongThuc` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `MaTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `MaThanhToan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16033;

--
-- AUTO_INCREMENT cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `MaThongBao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18009;

--
-- AUTO_INCREMENT cho bảng `thuoc`
--
ALTER TABLE `thuoc`
  MODIFY `MaThuoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3053;

--
-- AUTO_INCREMENT cho bảng `tinnhan`
--
ALTER TABLE `tinnhan`
  MODIFY `MaTinNhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19007;

--
-- AUTO_INCREMENT cho bảng `trangthailichchhen`
--
ALTER TABLE `trangthailichchhen`
  MODIFY `MaTrangThai` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `trangthaiphieukham`
--
ALTER TABLE `trangthaiphieukham`
  MODIFY `MaTrangThai` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  MODIFY `MaVaiTro` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `benhan`
--
ALTER TABLE `benhan`
  ADD CONSTRAINT `FK_BenhAn_BenhNhan` FOREIGN KEY (`MaBenhNhan`) REFERENCES `benhnhan` (`MaBenhNhan`);

--
-- Các ràng buộc cho bảng `benhan_phieukham`
--
ALTER TABLE `benhan_phieukham`
  ADD CONSTRAINT `FK_BAPK_BenhAn` FOREIGN KEY (`MaBenhAn`) REFERENCES `benhan` (`MaBenhAn`),
  ADD CONSTRAINT `FK_BAPK_PhieuKham` FOREIGN KEY (`MaPhieuKham`) REFERENCES `phieukham` (`MaPhieuKham`);

--
-- Các ràng buộc cho bảng `benhnhan`
--
ALTER TABLE `benhnhan`
  ADD CONSTRAINT `FK_BenhNhan_TaiKhoan` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `cauhinhhethong`
--
ALTER TABLE `cauhinhhethong`
  ADD CONSTRAINT `FK_CauHinh_NguoiCapNhat` FOREIGN KEY (`NguoiCapNhat`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `chidinhcls`
--
ALTER TABLE `chidinhcls`
  ADD CONSTRAINT `FK_ChiDinhCLS_LoaiCLS` FOREIGN KEY (`MaLoaiCLS`) REFERENCES `loaiclsn` (`MaLoaiCLS`),
  ADD CONSTRAINT `FK_ChiDinhCLS_PhieuKham` FOREIGN KEY (`MaPhieuKham`) REFERENCES `phieukham` (`MaPhieuKham`);

--
-- Các ràng buộc cho bảng `chitietdonthuoc`
--
ALTER TABLE `chitietdonthuoc`
  ADD CONSTRAINT `FK_CTDT_DonThuoc` FOREIGN KEY (`MaDonThuoc`) REFERENCES `donthuoc` (`MaDonThuoc`),
  ADD CONSTRAINT `FK_CTDT_Thuoc` FOREIGN KEY (`MaThuoc`) REFERENCES `thuoc` (`MaThuoc`);

--
-- Các ràng buộc cho bảng `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD CONSTRAINT `FK_CTPN_PhieuNhap` FOREIGN KEY (`MaPhieuNhap`) REFERENCES `phieunhap` (`MaPhieuNhap`),
  ADD CONSTRAINT `FK_CTPN_Thuoc` FOREIGN KEY (`MaThuoc`) REFERENCES `thuoc` (`MaThuoc`);

--
-- Các ràng buộc cho bảng `chitietphieuxuat`
--
ALTER TABLE `chitietphieuxuat`
  ADD CONSTRAINT `FK_CTPX_PhieuXuat` FOREIGN KEY (`MaPhieuXuat`) REFERENCES `phieuxuat` (`MaPhieuXuat`),
  ADD CONSTRAINT `FK_CTPX_Thuoc` FOREIGN KEY (`MaThuoc`) REFERENCES `thuoc` (`MaThuoc`);

--
-- Các ràng buộc cho bảng `danhgiadichvu`
--
ALTER TABLE `danhgiadichvu`
  ADD CONSTRAINT `FK_DanhGia_BacSi` FOREIGN KEY (`MaBacSi`) REFERENCES `nhanvien` (`MaNhanVien`),
  ADD CONSTRAINT `FK_DanhGia_BenhNhan` FOREIGN KEY (`MaBenhNhan`) REFERENCES `benhnhan` (`MaBenhNhan`),
  ADD CONSTRAINT `FK_DanhGia_Phieu` FOREIGN KEY (`MaPhieuKham`) REFERENCES `phieukham` (`MaPhieuKham`);

--
-- Các ràng buộc cho bảng `donthuoc`
--
ALTER TABLE `donthuoc`
  ADD CONSTRAINT `FK_DonThuoc_PhieuKham` FOREIGN KEY (`MaPhieuKham`) REFERENCES `phieukham` (`MaPhieuKham`);

--
-- Các ràng buộc cho bảng `filehosobenhnhan`
--
ALTER TABLE `filehosobenhnhan`
  ADD CONSTRAINT `FK_File_BenhNhan` FOREIGN KEY (`MaBenhNhan`) REFERENCES `benhnhan` (`MaBenhNhan`),
  ADD CONSTRAINT `FK_File_NguoiTai` FOREIGN KEY (`NguoiTai`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `FK_File_PhieuKham` FOREIGN KEY (`MaPhieuKham`) REFERENCES `phieukham` (`MaPhieuKham`);

--
-- Các ràng buộc cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `FK_HoaDon_PhieuKham` FOREIGN KEY (`MaPhieuKham`) REFERENCES `phieukham` (`MaPhieuKham`);

--
-- Các ràng buộc cho bảng `ketquacls`
--
ALTER TABLE `ketquacls`
  ADD CONSTRAINT `FK_KetQuaCLS_ChiDinh` FOREIGN KEY (`MaChiDinh`) REFERENCES `chidinhcls` (`MaChiDinh`),
  ADD CONSTRAINT `FK_KetQuaCLS_NVThucHien` FOREIGN KEY (`MaNVThucHien`) REFERENCES `nhanvien` (`MaNhanVien`);

--
-- Các ràng buộc cho bảng `lichhen`
--
ALTER TABLE `lichhen`
  ADD CONSTRAINT `FK_LichHen_BenhNhan` FOREIGN KEY (`MaBenhNhan`) REFERENCES `benhnhan` (`MaBenhNhan`),
  ADD CONSTRAINT `FK_LichHen_NguoiTao` FOREIGN KEY (`NguoiTao`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `FK_LichHen_TrangThai` FOREIGN KEY (`MaTrangThai`) REFERENCES `trangthailichchhen` (`MaTrangThai`);

--
-- Các ràng buộc cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD CONSTRAINT `FK_LichLamViec_BacSi` FOREIGN KEY (`MaBacSi`) REFERENCES `nhanvien` (`MaNhanVien`);

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `FK_NhanVien_ChuyenKhoa` FOREIGN KEY (`MaChuyenKhoa`) REFERENCES `chuyenkhoa` (`MaChuyenKhoa`),
  ADD CONSTRAINT `FK_NhanVien_TaiKhoan` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `nhatkyhoatdong`
--
ALTER TABLE `nhatkyhoatdong`
  ADD CONSTRAINT `FK_NhatKy_TaiKhoan` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `phieukham`
--
ALTER TABLE `phieukham`
  ADD CONSTRAINT `FK_PhieuKham_BacSi` FOREIGN KEY (`MaBacSi`) REFERENCES `nhanvien` (`MaNhanVien`),
  ADD CONSTRAINT `FK_PhieuKham_BenhNhan` FOREIGN KEY (`MaBenhNhan`) REFERENCES `benhnhan` (`MaBenhNhan`),
  ADD CONSTRAINT `FK_PhieuKham_TrangThai` FOREIGN KEY (`MaTrangThai`) REFERENCES `trangthaiphieukham` (`MaTrangThai`);

--
-- Các ràng buộc cho bảng `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `FK_PhieuNhap_NguoiNhap` FOREIGN KEY (`NguoiNhap`) REFERENCES `nhanvien` (`MaNhanVien`);

--
-- Các ràng buộc cho bảng `phieuxuat`
--
ALTER TABLE `phieuxuat`
  ADD CONSTRAINT `FK_PhieuXuat_BenhNhan` FOREIGN KEY (`MaBenhNhan`) REFERENCES `benhnhan` (`MaBenhNhan`),
  ADD CONSTRAINT `FK_PhieuXuat_NguoiXuat` FOREIGN KEY (`NguoiXuat`) REFERENCES `nhanvien` (`MaNhanVien`);

--
-- Các ràng buộc cho bảng `taikhoan_vaitro`
--
ALTER TABLE `taikhoan_vaitro`
  ADD CONSTRAINT `FK_TKVT_TaiKhoan` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `FK_TKVT_VaiTro` FOREIGN KEY (`MaVaiTro`) REFERENCES `vaitro` (`MaVaiTro`);

--
-- Các ràng buộc cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `FK_ThanhToan_HoaDon` FOREIGN KEY (`MaHoaDon`) REFERENCES `hoadon` (`MaHoaDon`),
  ADD CONSTRAINT `FK_ThanhToan_NguoiThu` FOREIGN KEY (`NguoiThu`) REFERENCES `nhanvien` (`MaNhanVien`),
  ADD CONSTRAINT `FK_ThanhToan_PhuongThuc` FOREIGN KEY (`MaPhuongThuc`) REFERENCES `phuongthuctt` (`MaPhuongThuc`);

--
-- Các ràng buộc cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  ADD CONSTRAINT `FK_ThongBao_NguoiNhan` FOREIGN KEY (`MaNguoiNhan`) REFERENCES `taikhoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `thuoc`
--
ALTER TABLE `thuoc`
  ADD CONSTRAINT `FK_Thuoc_DonVi` FOREIGN KEY (`MaDonVi`) REFERENCES `donvitinh` (`MaDonVi`);

--
-- Các ràng buộc cho bảng `tinnhan`
--
ALTER TABLE `tinnhan`
  ADD CONSTRAINT `FK_TinNhan_NguoiGui` FOREIGN KEY (`MaNguoiGui`) REFERENCES `taikhoan` (`MaTaiKhoan`),
  ADD CONSTRAINT `FK_TinNhan_NguoiNhan` FOREIGN KEY (`MaNguoiNhan`) REFERENCES `taikhoan` (`MaTaiKhoan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
