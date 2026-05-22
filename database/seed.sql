 -- ================================================================
-- SEED DATA — HỆ THỐNG QUẢN LÝ PHÒNG KHÁM TƯ NHÂN
-- Dữ liệu mẫu đầy đủ để test toàn bộ chức năng
-- Thứ tự: đúng theo FK dependency
-- Mật khẩu mẫu tất cả tài khoản: Admin@123
-- (hash BCrypt $2b$12$... — thay bằng hash thật khi deploy)
-- ================================================================

USE QuanLyPhongKham;
GO

SET NOCOUNT ON;

-- ================================================================
-- PHẦN 1: CẤU HÌNH PHÒNG KHÁM
-- ================================================================

UPDATE CAUHINHHETHONG SET GiaTri = N'Phòng Khám Đa Khoa Gia Phúc'    WHERE KhoacCauHinh = N'ten_phong_kham';
UPDATE CAUHINHHETHONG SET GiaTri = N'123 Đường Nguyễn Huệ, Q.1, TP.HCM' WHERE KhoacCauHinh = N'dia_chi';
UPDATE CAUHINHHETHONG SET GiaTri = N'028.3822.1234'                    WHERE KhoacCauHinh = N'so_dien_thoai';
UPDATE CAUHINHHETHONG SET GiaTri = N'HD'                               WHERE KhoacCauHinh = N'tien_to_hoa_don';
UPDATE CAUHINHHETHONG SET GiaTri = N'PK'                               WHERE KhoacCauHinh = N'tien_to_phieu_kham';
UPDATE CAUHINHHETHONG SET GiaTri = N'BN'                               WHERE KhoacCauHinh = N'tien_to_benh_nhan';
GO

-- ================================================================
-- PHẦN 2: TÀI KHOẢN HỆ THỐNG
-- ================================================================
-- IDENTITY bắt đầu từ 1 theo thứ tự insert
-- MaTaiKhoan: 1=quan_ly, 2=le_tan1, 3=le_tan2, 4=dieu_duong1, 5=dieu_duong2
--             6=bac_si1, 7=bac_si2, 8=bac_si3, 9=bac_si4, 10=bac_si5
--             11=ky_thuat_vien, 12=duoc_si, 13=bn1..22=bn10

INSERT INTO TAIKHOAN (TenDangNhap, MatKhauHash, SoDienThoai, DangHoatDong, LanDangNhapCuoi) VALUES
-- Nội bộ
(N'quan.ly',        N'$2b$12$hashed_Admin@123', N'0901000001', 1, '2025-05-17 08:00:00'),
(N'le.tan1',        N'$2b$12$hashed_Admin@123', N'0901000002', 1, '2025-05-17 07:45:00'),
(N'le.tan2',        N'$2b$12$hashed_Admin@123', N'0901000003', 1, '2025-05-17 07:50:00'),
(N'dieu.duong1',    N'$2b$12$hashed_Admin@123', N'0901000004', 1, '2025-05-17 07:55:00'),
(N'dieu.duong2',    N'$2b$12$hashed_Admin@123', N'0901000005', 1, '2025-05-17 08:00:00'),
(N'bac.si.tuan',    N'$2b$12$hashed_Admin@123', N'0901000006', 1, '2025-05-17 08:00:00'),
(N'bac.si.linh',    N'$2b$12$hashed_Admin@123', N'0901000007', 1, '2025-05-17 08:05:00'),
(N'bac.si.hung',    N'$2b$12$hashed_Admin@123', N'0901000008', 1, '2025-05-17 08:10:00'),
(N'bac.si.mai',     N'$2b$12$hashed_Admin@123', N'0901000009', 1, '2025-05-17 07:30:00'),
(N'bac.si.nam',     N'$2b$12$hashed_Admin@123', N'0901000010', 1, '2025-05-17 08:00:00'),
(N'ky.thuat.vien1', N'$2b$12$hashed_Admin@123', N'0901000011', 1, '2025-05-17 08:00:00'),
(N'duoc.si1',       N'$2b$12$hashed_Admin@123', N'0901000012', 1, '2025-05-17 08:00:00'),
-- Bệnh nhân có tài khoản
(N'bn.lan',         N'$2b$12$hashed_Admin@123', N'0911100001', 1, '2025-05-15 09:00:00'),
(N'bn.minh',        N'$2b$12$hashed_Admin@123', N'0911100002', 1, '2025-05-14 14:00:00'),
(N'bn.hoa',         N'$2b$12$hashed_Admin@123', N'0911100003', 1, '2025-05-16 10:30:00'),
(N'bn.duc',         N'$2b$12$hashed_Admin@123', N'0911100004', 1, '2025-05-10 08:00:00'),
(N'bn.thu',         N'$2b$12$hashed_Admin@123', N'0911100005', 1, '2025-05-13 16:00:00'),
(N'bn.phong',       N'$2b$12$hashed_Admin@123', N'0911100006', 1, '2025-05-12 11:00:00'),
(N'bn.yen',         N'$2b$12$hashed_Admin@123', N'0911100007', 1, '2025-05-16 09:00:00'),
(N'bn.khanh',       N'$2b$12$hashed_Admin@123', N'0911100008', 1, '2025-05-11 14:00:00'),
(N'bn.ngoc',        N'$2b$12$hashed_Admin@123', N'0911100009', 1, '2025-05-08 08:30:00'),
(N'bn.bao',         N'$2b$12$hashed_Admin@123', N'0911100010', 1, '2025-05-07 15:00:00');
GO

-- ================================================================
-- PHẦN 3: PHÂN QUYỀN
-- ================================================================
-- Bật tính năng cho phép chèn thủ công giá trị vào cột IDENTITY
SET IDENTITY_INSERT VAITRO ON;

INSERT INTO VAITRO (MaVaiTro, TenVaiTro, MoTa) VALUES
(1, N'LE_TAN',        N'Nhân viên lễ tân tiếp đón'),
(2, N'DIEU_DUONG',    N'Điều dưỡng lâm sàng'),
(3, N'BAC_SI',        N'Bác sĩ khám và điều trị'),
(4, N'KY_THUAT_VIEN', N'Kỹ thuật viên xét nghiệm, chẩn đoán hình ảnh'),
(5, N'DUOC_SI',       N'Dược sĩ cấp phát, quản lý kho thuốc'),
(6, N'QUAN_LY',       N'Ban quản lý phòng khám'),
(7, N'BENH_NHAN',     N'Tài khoản dành cho bệnh nhân');

-- Tắt tính năng chèn thủ công để đưa cột IDENTITY về trạng thái tự động tăng bình thường
SET IDENTITY_INSERT VAITRO OFF;
GO

INSERT INTO TAIKHOAN_VAITRO (MaTaiKhoan, MaVaiTro) VALUES
(1,  6),  -- quan.ly     → QUAN_LY
(2,  1),  -- le.tan1     → LE_TAN
(3,  1),  -- le.tan2     → LE_TAN
(4,  2),  -- dieu.duong1 → DIEU_DUONG
(5,  2),  -- dieu.duong2 → DIEU_DUONG
(6,  3),  -- bac.si.tuan → BAC_SI
(7,  3),  -- bac.si.linh → BAC_SI
(8,  3),  -- bac.si.hung → BAC_SI
(9,  3),  -- bac.si.mai  → BAC_SI
(10, 3),  -- bac.si.nam  → BAC_SI
(11, 4),  -- ky.thuat.vien → KY_THUAT_VIEN
(12, 5),  -- duoc.si1    → DUOC_SI
(13, 7),(14, 7),(15, 7),(16, 7),(17, 7),
(18, 7),(19, 7),(20, 7),(21, 7),(22, 7); -- bệnh nhân → BENH_NHAN
GO

-- ================================================================
-- PHẦN 4: NHÂN VIÊN
-- ================================================================
-- MaChuyenKhoa: 1=Nội, 2=Ngoại, 3=Nhi, 4=Sản-Phụ, 5=Da liễu
--               6=TMH, 7=Mắt, 8=Răng, 9=Tim mạch, 10=Thần kinh

INSERT INTO NHANVIEN (MaTaiKhoan, HoTen, NgaySinh, GioiTinh, CCCD, SoDienThoai, Email, DiaChi, MaChuyenKhoa, BangCap, SoChungChi, NgayVaoLam) VALUES
(1,  N'Nguyễn Văn Phúc',     '1975-03-10', 'M', '079075001001', N'0901000001', N'phuc.nv@giaPhuc.vn',  N'45 Lê Lợi, Q.1, TP.HCM',        NULL, N'Thạc sĩ Quản trị Y tế',   NULL,           '2015-01-01'),
(2,  N'Trần Thị Bình',       '1992-07-22', 'F', '079092001002', N'0901000002', N'binh.tt@giaPhuc.vn',  N'12 Hai Bà Trưng, Q.3, TP.HCM',   NULL, N'Cao đẳng Y',              NULL,           '2020-03-01'),
(3,  N'Lê Thị Hằng',         '1995-11-05', 'F', '079095001003', N'0901000003', N'hang.lt@giaPhuc.vn',  N'78 Đinh Tiên Hoàng, Q.BT, TP.HCM',NULL, N'Cao đẳng Y',              NULL,           '2022-06-15'),
(4,  N'Phạm Thị Cúc',        '1990-04-18', 'F', '079090001004', N'0901000004', N'cuc.pt@giaPhuc.vn',   N'56 Võ Văn Tần, Q.3, TP.HCM',     NULL, N'Cử nhân Điều dưỡng',      NULL,           '2018-09-01'),
(5,  N'Đỗ Thị Lan',          '1993-08-30', 'F', '079093001005', N'0901000005', N'lan.dt@giaPhuc.vn',   N'23 Nguyễn Đình Chiểu, Q.3, TP.HCM',NULL,N'Cử nhân Điều dưỡng',     NULL,           '2021-02-01'),
(6,  N'BS. Trần Minh Tuấn',  '1980-06-15', 'M', '079080001006', N'0901000006', N'tuan.tm@giaPhuc.vn',  N'88 Trần Hưng Đạo, Q.1, TP.HCM',  1,    N'Bác sĩ CKI Nội khoa',     N'012345/BYT',  '2016-04-01'),
(7,  N'BS. Nguyễn Thị Linh', '1985-09-20', 'F', '079085001007', N'0901000007', N'linh.nt@giaPhuc.vn',  N'34 Lý Tự Trọng, Q.1, TP.HCM',    2,    N'Bác sĩ CKI Ngoại khoa',   N'023456/BYT',  '2017-08-01'),
(8,  N'BS. Lê Quang Hùng',   '1978-12-03', 'M', '079078001008', N'0901000008', N'hung.lq@giaPhuc.vn',  N'67 Nguyễn Thị Minh Khai, Q.3, TP.HCM', 3, N'Bác sĩ CKI Nhi khoa',  N'034567/BYT',  '2015-06-01'),
(9,  N'BS. Hồ Thị Mai',      '1983-02-14', 'F', '079083001009', N'0901000009', N'mai.ht@giaPhuc.vn',   N'11 Đinh Tiên Hoàng, Q.BT, TP.HCM', 4,   N'Bác sĩ CKI Sản phụ khoa', N'045678/BYT', '2016-11-01'),
(10, N'BS. Vũ Đức Nam',      '1982-05-25', 'M', '079082001010', N'0901000010', N'nam.vd@giaPhuc.vn',   N'99 Lê Thánh Tôn, Q.1, TP.HCM',    5,    N'Bác sĩ CKI Da liễu',      N'056789/BYT',  '2018-03-01'),
(11, N'Đặng Hữu Khoa',       '1994-10-08', 'M', '079094001011', N'0901000011', N'khoa.dh@giaPhuc.vn',  N'45 Cách Mạng Tháng 8, Q.3, TP.HCM',NULL, N'Cử nhân Kỹ thuật Y tế',  NULL,           '2022-01-10'),
(12, N'Bùi Thị Hoa',         '1988-03-12', 'F', '079088001012', N'0901000012', N'hoa.bt@giaPhuc.vn',   N'78 Nguyễn Trãi, Q.5, TP.HCM',     NULL, N'Dược sĩ Đại học',         NULL,           '2019-07-01');
GO

-- ================================================================
-- PHẦN 5: BỆNH NHÂN (10 có tài khoản + 5 vãng lai)
-- ================================================================

INSERT INTO BENHNHAN (MaTaiKhoan, MaBN, HoTen, NgaySinh, GioiTinh, SoDienThoai, Email, DiaChi, SoBHYT, NhomMau, DiUng) VALUES
(13, N'BN00001', N'Nguyễn Thị Lan',   '1985-04-12', 'F', N'0911100001', N'lan@email.com',   N'12 Lê Văn Sỹ, Q.3, TP.HCM',        N'DN4010012345678', N'A+',  N'Penicillin'),
(14, N'BN00002', N'Trần Văn Minh',    '1990-08-25', 'M', N'0911100002', N'minh@email.com',  N'45 Nguyễn Thị Thập, Q.7, TP.HCM',  N'DN4020023456789', N'O+',  NULL),
(15, N'BN00003', N'Lê Thị Hoa',       '1978-01-30', 'F', N'0911100003', N'hoa@email.com',   N'78 Hoàng Diệu, Q.4, TP.HCM',        N'DN4030034567890', N'B+',  N'Aspirin'),
(16, N'BN00004', N'Phạm Văn Đức',     '1965-11-07', 'M', N'0911100004', N'duc@email.com',   N'23 Bà Huyện Thanh Quan, Q.3, TP.HCM',N'DN4040045678901',N'AB+', NULL),
(17, N'BN00005', N'Đỗ Thị Thu',       '1995-06-18', 'F', N'0911100005', N'thu@email.com',   N'56 Đinh Bộ Lĩnh, Q.BT, TP.HCM',    N'DN4050056789012', N'O-',  NULL),
(18, N'BN00006', N'Ngô Văn Phong',    '1970-09-03', 'M', N'0911100006', N'phong@email.com', N'34 Tô Hiến Thành, Q.10, TP.HCM',   N'DN4060067890123', N'A-',  N'Sulfa'),
(19, N'BN00007', N'Vũ Thị Yến',       '1988-12-22', 'F', N'0911100007', N'yen@email.com',   N'67 Ngô Gia Tự, Q.10, TP.HCM',       N'DN4070078901234', N'B-',  NULL),
(20, N'BN00008', N'Hoàng Văn Khánh',  '1982-03-15', 'M', N'0911100008', N'khanh@email.com', N'11 Thành Thái, Q.10, TP.HCM',       N'DN4080089012345', N'O+',  NULL),
(21, N'BN00009', N'Bùi Thị Ngọc',     '1999-07-09', 'F', N'0911100009', N'ngoc@email.com',  N'89 Cao Thắng, Q.3, TP.HCM',          N'DN4090090123456', N'A+',  N'Codeine'),
(22, N'BN00010', N'Dương Văn Bảo',    '1975-02-28', 'M', N'0911100010', N'bao@email.com',   N'44 Nguyễn Đình Chiểu, Q.3, TP.HCM', N'DN4100001234567', N'AB-', NULL),
-- Bệnh nhân vãng lai (không có tài khoản)
(NULL, N'BN00011', N'Trương Thị Kiều',  '1960-05-14', 'F', N'0922200001', NULL, N'Bình Dương',       NULL,               N'O+',  NULL),
(NULL, N'BN00012', N'Lâm Quốc Toàn',   '1993-10-20', 'M', N'0922200002', NULL, N'Long An',          NULL,               N'B+',  NULL),
(NULL, N'BN00013', N'Mai Thị Phượng',  '1987-08-11', 'F', N'0922200003', NULL, N'Đồng Nai',         NULL,               N'A+',  N'Ibuprofen'),
(NULL, N'BN00014', N'Phan Văn Tài',    '2005-03-25', 'M', N'0922200004', NULL, N'Tây Ninh',         NULL,               N'O+',  NULL),
(NULL, N'BN00015', N'Đinh Thị Bé',     '1955-12-01', 'F', N'0922200005', NULL, N'TP.HCM',           NULL,               N'AB+', NULL);
GO

-- ================================================================
-- PHẦN 6: DỊCH VỤ KHÁM
-- ================================================================
-- MaChuyenKhoa: 1=Nội, 2=Ngoại, 3=Nhi, 4=Sản-Phụ, 5=Da liễu

INSERT INTO DICHVUKHAM (MaChuyenKhoa, TenDichVu, DonGia, MoTa) VALUES
(1,   N'Khám nội tổng quát',       150000, N'Khám tổng quát chuyên khoa nội'),
(2,   N'Khám ngoại tổng quát',     150000, N'Khám tổng quát chuyên khoa ngoại'),
(3,   N'Khám nhi',                 120000, N'Khám bệnh cho trẻ em'),
(4,   N'Khám sản phụ khoa',        180000, N'Khám phụ khoa và thai sản'),
(5,   N'Khám da liễu',             150000, N'Khám bệnh da liễu'),
(1,   N'Khám tái khám nội',         80000, N'Tái khám nội khoa'),
(3,   N'Khám tái khám nhi',          80000, N'Tái khám nhi khoa'),
(4,   N'Siêu âm thai',             200000, N'Siêu âm theo dõi thai nhi'),
(1,   N'Tư vấn dinh dưỡng',        100000, N'Tư vấn chế độ ăn uống'),
(NULL, N'Tiêm phòng',               80000, N'Tiêm vaccine các loại');
GO

-- ================================================================
-- PHẦN 7: DANH MỤC CLS
-- ================================================================
-- MaLoaiCLS: 1=XN máu, 2=XN nước tiểu, 3=X-quang, 4=Siêu âm, 5=ECG, 6=MRI, 7=Nội soi
-- MaDonVi: 1=Viên, 2=Chai, 3=Ống, 4=Gói, 5=Hộp, 6=mg, 7=ml, 8=Lần, 9=Tuýp

INSERT INTO DICHVUCLS (MaLoaiCLS, TenDichVu, MaDonVi, DonGia, MoTa) VALUES
-- Xét nghiệm máu (loại 1)
(1, N'Công thức máu toàn phần (CBC)',    8, 120000, N'Đếm số lượng và phân loại tế bào máu'),
(1, N'Đường huyết lúc đói (FPG)',        8,  60000, N'Đo glucose máu nhịn ăn'),
(1, N'HbA1c',                            8, 180000, N'Đánh giá kiểm soát đường huyết 3 tháng'),
(1, N'Bộ mỡ máu (Lipid profile)',        8, 200000, N'Cholesterol, Triglyceride, HDL, LDL'),
(1, N'AST / ALT (men gan)',              8, 100000, N'Chức năng gan'),
(1, N'Creatinine / Ure (chức năng thận)',8, 100000, N'Đánh giá chức năng thận'),
(1, N'TSH (tuyến giáp)',                 8, 250000, N'Hormone kích thích tuyến giáp'),
(1, N'Tổng phân tích nước tiểu',         8,  60000, N'Phân tích nước tiểu toàn diện'),
-- X-quang (loại 3)
(3, N'X-quang ngực thẳng',               8, 120000, N'Chụp phổi thẳng'),
(3, N'X-quang cột sống thắt lưng',       8, 150000, N'Chụp cột sống thắt lưng'),
(3, N'X-quang bàn tay / bàn chân',       8, 100000, N'Chụp xương tứ chi'),
-- Siêu âm (loại 4)
(4, N'Siêu âm ổ bụng tổng quát',         8, 200000, N'Siêu âm gan, mật, tụy, thận, lách'),
(4, N'Siêu âm tim (Echo)',               8, 350000, N'Đánh giá chức năng tim'),
(4, N'Siêu âm tuyến giáp',              8, 180000, N'Kiểm tra nhân giáp'),
(4, N'Siêu âm thai 2D',                 8, 200000, N'Theo dõi thai nhi'),
(4, N'Siêu âm vú',                      8, 200000, N'Tầm soát u vú'),
-- ECG (loại 5)
(5, N'Điện tim 12 chuyển đạo (ECG)',     8, 100000, N'Đo hoạt động điện tim');
GO

-- ================================================================
-- PHẦN 8: THUỐC
-- ================================================================
-- MaDonVi: 1=Viên, 2=Chai, 3=Ống, 4=Gói, 5=Hộp

INSERT INTO THUOC (MaThuocCode, TenThuoc, TenHoatChat, HangSanXuat, MaDonVi, DangBaoChe, HamLuong, GiaNhap, GiaBan, SoLuongTon, TonToiThieu, HanSuDung) VALUES
(N'T001', N'Paracetamol Stella 500mg',   N'Paracetamol',         N'Stella',       1, N'Viên nén',  N'500mg',    800,   1200,  500, 100, '2026-12-31'),
(N'T002', N'Amoxicillin 500mg',          N'Amoxicillin',         N'Domesco',      1, N'Viên nang', N'500mg',   1200,   1800,  300,  80, '2026-08-31'),
(N'T003', N'Omeprazole 20mg',            N'Omeprazole',          N'Stada',        1, N'Viên nang', N'20mg',    1500,   2200,  400,  80, '2026-10-31'),
(N'T004', N'Metformin 500mg',            N'Metformin HCl',       N'Traphaco',     1, N'Viên nén',  N'500mg',    600,    900,  600, 100, '2026-11-30'),
(N'T005', N'Amlodipine 5mg',             N'Amlodipine',          N'Imexpharm',    1, N'Viên nén',  N'5mg',      700,   1100,  350,  80, '2026-09-30'),
(N'T006', N'Atorvastatin 10mg',          N'Atorvastatin',        N'Stella',       1, N'Viên nén',  N'10mg',    2500,   3800,  250,  60, '2026-12-31'),
(N'T007', N'Cetirizine 10mg',            N'Cetirizine HCl',      N'DHG',          1, N'Viên nén',  N'10mg',     500,    800,  400,  80, '2027-01-31'),
(N'T008', N'Domperidone 10mg',           N'Domperidone',         N'Domesco',      1, N'Viên nén',  N'10mg',     800,   1200,  350,  80, '2026-07-31'),
(N'T009', N'Vitamin C 1000mg',           N'Acid Ascorbic',       N'Hana',         1, N'Viên sủi',  N'1000mg',   600,    900, 1000, 150, '2026-12-31'),
(N'T010', N'Diclofenac 50mg',            N'Diclofenac Sodium',   N'Roussel',      1, N'Viên nén',  N'50mg',     900,   1400,  300,  60, '2026-06-30'),
(N'T011', N'Ambroxol 30mg',              N'Ambroxol HCl',        N'Stada',        1, N'Viên nén',  N'30mg',     700,   1100,  400,  80, '2026-09-30'),
(N'T012', N'Salbutamol 2.5mg/2.5ml',     N'Salbutamol',          N'GSK',          3, N'Dung dịch', N'2.5mg',   3500,   5000,  100,  30, '2026-05-31'),
(N'T013', N'Clopidogrel 75mg',           N'Clopidogrel',         N'Sanofi',       1, N'Viên nén',  N'75mg',    4500,   6800,  200,  50, '2026-11-30'),
(N'T014', N'Metronidazole 250mg',        N'Metronidazole',       N'Mekophar',     1, N'Viên nén',  N'250mg',    600,    900,  350,  80, '2026-08-31'),
(N'T015', N'Hydrocortisone cream 1%',    N'Hydrocortisone',      N'Pymepharco',   9, N'Kem bôi',   N'1%',      8000,  12000,   80,  20, '2026-10-31'),
(N'T016', N'Multivitamin tổng hợp',      N'Multivitamin',        N'DHG',          5, N'Hộp 30v',   NULL,       15000,  22000,  150,  30, '2027-03-31'),
(N'T017', N'Siro ho Bổ phế',            N'Thảo dược',           N'Traphaco',     2, N'Chai 125ml',NULL,       18000,  25000,  120,  30, '2026-12-31'),
(N'T018', N'ORS bù điện giải',          N'Oral Rehydration Salts',N'Mekophar',   4, N'Gói',       NULL,        2000,   3500,  500, 100, '2027-06-30'),
(N'T019', N'Ibuprofen 400mg',            N'Ibuprofen',           N'Imexpharm',   1, N'Viên nén',  N'400mg',   1100,   1800,  300,  60, '2026-09-30'),
(N'T020', N'Azithromycin 500mg',         N'Azithromycin',        N'Pymepharco',  1, N'Viên nén',  N'500mg',   4000,   6000,  150,  40, '2026-07-31');
GO

-- ================================================================
-- PHẦN 9: NHẬP KHO
-- ================================================================
-- MaDuocSi = MaNhanVien của dược sĩ = 12

INSERT INTO PHIEUNHAPKHO (MaPhieu, NhaCungCap, MaDuocSi, NgayNhap, TongTienNhap, GhiChu) VALUES
(N'NK20250401', N'Công ty Dược Phẩm Nam Hà',     12, '2025-04-01', 25000000, N'Nhập định kỳ tháng 4'),
(N'NK20250415', N'Công ty TNHH Dược Sài Gòn',    12, '2025-04-15', 18000000, N'Bổ sung thuốc tim mạch'),
(N'NK20250501', N'Công ty Cổ phần Dược DHG',     12, '2025-05-01', 32000000, N'Nhập định kỳ tháng 5'),
(N'NK20250510', N'Công ty Dược Phẩm TW3',        12, '2025-05-10', 15000000, N'Bổ sung thuốc kháng sinh');
GO

INSERT INTO CHITIETNHAPKHO (MaPhieuNhap, MaThuoc, SoLuongNhap, DonGiaNhap, HanSuDung, SoLoThuoc) VALUES
-- Phiếu NK001
(1,  1, 500,  800, '2026-12-31', N'L2025041'),
(1,  2, 300, 1200, '2026-08-31', N'L2025042'),
(1,  3, 400, 1500, '2026-10-31', N'L2025043'),
(1,  7, 400,  500, '2027-01-31', N'L2025044'),
(1,  9,1000,  600, '2026-12-31', N'L2025045'),
-- Phiếu NK002
(2,  5, 350,  700, '2026-09-30', N'L2025046'),
(2,  6, 250, 2500, '2026-12-31', N'L2025047'),
(2, 13, 200, 4500, '2026-11-30', N'L2025048'),
-- Phiếu NK003
(3,  4, 600,  600, '2026-11-30', N'L2025051'),
(3,  8, 350,  800, '2026-07-31', N'L2025052'),
(3, 11, 400,  700, '2026-09-30', N'L2025053'),
(3, 16, 150,15000, '2027-03-31', N'L2025054'),
(3, 17, 120,18000, '2026-12-31', N'L2025055'),
(3, 18, 500, 2000, '2027-06-30', N'L2025056'),
-- Phiếu NK004
(4, 14, 350,  600, '2026-08-31', N'L2025057'),
(4, 20, 150, 4000, '2026-07-31', N'L2025058'),
(4, 19, 300, 1100, '2026-09-30', N'L2025059');
GO

-- ================================================================
-- PHẦN 10: LỊCH LÀM VIỆC BÁC SĨ (2 tuần)
-- ================================================================
-- MaBacSi: 6=BS.Tuấn(Nội), 7=BS.Linh(Ngoại), 8=BS.Hùng(Nhi)
--          9=BS.Mai(Sản), 10=BS.Nam(Da liễu)

INSERT INTO LICHLAMVIEC (MaBacSi, NgayLam, Ca, SoBenhNhanToiDa) VALUES
-- Tuần 1
(6,  '2025-05-12', N'SANG',  20), (6,  '2025-05-12', N'CHIEU', 15),
(7,  '2025-05-12', N'SANG',  20),
(8,  '2025-05-12', N'SANG',  25), (8,  '2025-05-12', N'CHIEU', 20),
(9,  '2025-05-12', N'SANG',  15),
(10, '2025-05-12', N'CHIEU', 20),
(6,  '2025-05-13', N'SANG',  20),
(7,  '2025-05-13', N'SANG',  20), (7,  '2025-05-13', N'CHIEU', 15),
(8,  '2025-05-13', N'SANG',  25),
(9,  '2025-05-13', N'CHIEU', 15),
(10, '2025-05-13', N'SANG',  20),
(6,  '2025-05-14', N'SANG',  20), (6,  '2025-05-14', N'CHIEU', 15),
(7,  '2025-05-14', N'SANG',  20),
(8,  '2025-05-14', N'CHIEU', 20),
(9,  '2025-05-14', N'SANG',  15),
(10, '2025-05-14', N'SANG',  20),
(6,  '2025-05-15', N'SANG',  20),
(7,  '2025-05-15', N'CHIEU', 15),
(8,  '2025-05-15', N'SANG',  25), (8,  '2025-05-15', N'CHIEU', 20),
(9,  '2025-05-15', N'SANG',  15),
(10, '2025-05-15', N'SANG',  20), (10, '2025-05-15', N'CHIEU', 15),
(6,  '2025-05-16', N'SANG',  20), (6,  '2025-05-16', N'CHIEU', 15),
(7,  '2025-05-16', N'SANG',  20),
(8,  '2025-05-16', N'SANG',  25),
(9,  '2025-05-16', N'SANG',  15), (9,  '2025-05-16', N'CHIEU', 10),
(10, '2025-05-16', N'CHIEU', 20),
-- Tuần 2 (hiện tại)
(6,  '2025-05-19', N'SANG',  20), (6,  '2025-05-19', N'CHIEU', 15),
(7,  '2025-05-19', N'SANG',  20),
(8,  '2025-05-19', N'SANG',  25),
(9,  '2025-05-19', N'SANG',  15),
(10, '2025-05-19', N'SANG',  20), (10, '2025-05-19', N'CHIEU', 15),
(6,  '2025-05-20', N'SANG',  20),
(7,  '2025-05-20', N'SANG',  20), (7,  '2025-05-20', N'CHIEU', 15),
(8,  '2025-05-20', N'CHIEU', 20),
(9,  '2025-05-20', N'SANG',  15),
(10, '2025-05-20', N'SANG',  20);
GO

-- ================================================================
-- PHẦN 11: LỊCH HẸN
-- ================================================================
-- MaBenhNhan: 1..15 | MaBacSi(NhanVien): 6..10
-- MaTrangThaiLH: 1=CHO_XAC_NHAN, 2=DA_XAC_NHAN, 3=DOI_LICH, 4=DA_HUY, 5=HOAN_THANH
-- MaChuyenKhoa: 1=Nội, 2=Ngoại, 3=Nhi, 4=Sản-Phụ, 5=Da liễu
-- NguoiTao: 2=le.tan1, 3=le.tan2 (MaTaiKhoan)

INSERT INTO LICHHEN (MaBenhNhan, MaBacSi, MaChuyenKhoa, NgayHen, GioHen, MaTrangThai, GhiChu, NguoiTao, NgayTao) VALUES
(1,  6, 1, '2025-05-12', '08:00', 5, N'Tái khám đường huyết',       2, '2025-05-09 09:00:00'),
(2,  7, 2, '2025-05-12', '09:00', 5, N'Khám đau bụng',              2, '2025-05-09 10:00:00'),
(3,  9, 4, '2025-05-13', '08:30', 5, N'Khám thai tuần 28',          2, '2025-05-10 08:00:00'),
(4,  6, 1, '2025-05-13', '10:00', 5, N'Kiểm tra huyết áp',          3, '2025-05-10 09:00:00'),
(5,  8, 3, '2025-05-14', '08:00', 5, N'Trẻ sốt 3 ngày',             2, '2025-05-11 10:00:00'),
(6,  6, 1, '2025-05-14', '09:30', 5, N'Khám tiểu đường',            2, '2025-05-11 11:00:00'),
(7, 10, 5, '2025-05-15', '08:00', 5, N'Dị ứng da',                  3, '2025-05-12 08:00:00'),
(8,  6, 1, '2025-05-15', '10:00', 5, N'Tái khám tim mạch',          2, '2025-05-12 09:00:00'),
(9,  9, 4, '2025-05-16', '08:30', 5, N'Khám phụ khoa',              2, '2025-05-13 08:00:00'),
(10, 7, 2, '2025-05-16', '09:00', 5, N'Đau lưng mãn tính',          2, '2025-05-13 09:00:00'),
-- Lịch hẹn hôm nay và tương lai
(1,  6, 1, '2025-05-17', '08:00', 2, N'Kiểm tra định kỳ',           2, '2025-05-14 08:00:00'),
(2,  8, 3, '2025-05-17', '09:00', 2, N'Khám trẻ em',                3, '2025-05-14 09:00:00'),
(3,  9, 4, '2025-05-19', '08:30', 1, N'Khám thai định kỳ',          13,'2025-05-15 10:00:00'),
(5,  6, 1, '2025-05-19', '10:00', 1, N'Khám mệt mỏi',               15,'2025-05-15 11:00:00'),
(11, 7, 2, '2025-05-20', '08:00', 1, N'Đau vai gáy',                 2, '2025-05-16 08:00:00'),
(12, 6, 1, '2025-05-20', '09:30', 2, N'Khám sức khỏe tổng quát',   2, '2025-05-16 09:00:00'),
-- Lịch đã hủy
(4,  6, 1, '2025-05-10', '08:00', 4, N'Bận việc đột xuất',          16,'2025-05-08 09:00:00'),
(6, 10, 5, '2025-05-11', '09:00', 4, N'Bệnh nhân không đến',        2, '2025-05-09 10:00:00');
GO

-- ================================================================
-- PHẦN 12: PHIẾU KHÁM
-- ================================================================
-- MaTrangThaiPK: 1=CHO_KHAM, 2=DA_SO_KHAM, 3=DANG_KHAM, 4=CHO_CLS, 5=HOAN_THANH, 6=DA_HUY
-- NguoiTao (MaTaiKhoan): 2=le.tan1

INSERT INTO PHIEUKHAM (MaPhieu, MaBenhNhan, MaLichHen, MaBacSi, MaChuyenKhoa, MaTrangThai, SoThuTu, NgayKham, NguoiTao, NgayTao, NgayHoanThanh) VALUES
-- Phiếu hoàn thành (tuần trước)
(N'PK20250512001', 1,  1, 6,  1, 5,  1, '2025-05-12', 2, '2025-05-12 07:30:00', '2025-05-12 10:15:00'),
(N'PK20250512002', 2,  2, 7,  2, 5,  2, '2025-05-12', 2, '2025-05-12 07:35:00', '2025-05-12 11:00:00'),
(N'PK20250512003', 11,NULL, 6, 1, 5,  3, '2025-05-12', 2, '2025-05-12 07:40:00', '2025-05-12 10:45:00'),
(N'PK20250513001', 3,  3, 9,  4, 5,  1, '2025-05-13', 2, '2025-05-13 07:30:00', '2025-05-13 09:30:00'),
(N'PK20250513002', 4,  4, 6,  1, 5,  2, '2025-05-13', 2, '2025-05-13 07:35:00', '2025-05-13 11:00:00'),
(N'PK20250514001', 5,  5, 8,  3, 5,  1, '2025-05-14', 2, '2025-05-14 07:30:00', '2025-05-14 09:00:00'),
(N'PK20250514002', 6,  6, 6,  1, 5,  2, '2025-05-14', 2, '2025-05-14 07:35:00', '2025-05-14 11:30:00'),
(N'PK20250514003', 12,NULL, 7, 2, 5,  3, '2025-05-14', 2, '2025-05-14 07:40:00', '2025-05-14 10:00:00'),
(N'PK20250515001', 7,  7,10,  5, 5,  1, '2025-05-15', 2, '2025-05-15 07:30:00', '2025-05-15 09:45:00'),
(N'PK20250515002', 8,  8, 6,  1, 5,  2, '2025-05-15', 2, '2025-05-15 07:35:00', '2025-05-15 11:00:00'),
(N'PK20250516001', 9,  9, 9,  4, 5,  1, '2025-05-16', 2, '2025-05-16 07:30:00', '2025-05-16 10:00:00'),
(N'PK20250516002', 10,10, 7,  2, 5,  2, '2025-05-16', 2, '2025-05-16 07:35:00', '2025-05-16 11:30:00'),
(N'PK20250516003', 13,NULL, 8, 3, 5,  3, '2025-05-16', 3, '2025-05-16 07:40:00', '2025-05-16 09:30:00'),
-- Phiếu hôm nay (đang trong ngày)
(N'PK20250517001', 1, 11, 6,  1, 5,  1, '2025-05-17', 2, '2025-05-17 07:30:00', '2025-05-17 10:00:00'),
(N'PK20250517002', 2, 12, 8,  3, 4,  2, '2025-05-17', 2, '2025-05-17 07:35:00', NULL),
(N'PK20250517003', 14,NULL, 6, 1, 3,  3, '2025-05-17', 2, '2025-05-17 07:40:00', NULL),
(N'PK20250517004', 15,NULL,10, 5, 2,  4, '2025-05-17', 2, '2025-05-17 07:45:00', NULL),
(N'PK20250517005', 5, NULL, 8, 3, 1,  5, '2025-05-17', 2, '2025-05-17 08:00:00', NULL);
GO

-- ================================================================
-- PHẦN 13: SƠ KHÁM (Điều dưỡng nhập)
-- ================================================================
-- MaDieuDuong = MaNhanVien: 4=Điều dưỡng Cúc, 5=Điều dưỡng Lan

INSERT INTO SOKHAM (MaPhieuKham, MaDieuDuong, NhietDo, HuyetAp, NhipTim, CanNang, ChieuCao, GhiChu) VALUES
(1,  4, 37.0, N'130/85', 76, 58.0,  160.0, NULL),
(2,  4, 37.2, N'120/78', 82, 72.5,  172.0, N'BN đau bụng phải'),
(3,  4, 36.8, N'115/75', 74, 55.0,  158.0, N'Có tiền sử tăng huyết áp'),
(4,  5, 36.9, N'125/82', 78, 80.0,  165.0, N'Khám thai lần 3'),
(5,  5, 37.5, N'118/76', 80, 68.0,  168.0, N'Hay bị đau đầu'),
(6,  4, 37.8, N'100/65', 95, 16.5,   105.0,N'Trẻ 4 tuổi, sốt 38.5 tối qua'),
(7,  4, 36.9, N'135/88', 72, 78.0,  170.0, N'BN đái tháo đường type 2'),
(8,  5, 37.1, N'118/74', 88, 62.5,  163.0, N'Đau lưng dưới 2 tuần'),
(9,  5, 37.0, N'122/80', 78, 65.0,  162.0, N'Nổi mề đay 5 ngày'),
(10, 4, 36.8, N'140/90', 68, 82.0,  175.0, N'Đang dùng Amlodipine'),
(11, 4, 37.3, N'118/76', 80, 57.5,  161.0, N'Ra huyết trắng bất thường'),
(12, 5, 37.0, N'125/82', 76, 88.0,  173.0, N'Đau lưng lan xuống chân phải'),
(13, 5, 37.2, N'110/70', 82, 22.0,  110.0, N'Trẻ 3 tuổi, tiêu chảy 2 ngày'),
(14, 4, 37.0, N'130/85', 76, 58.0,  160.0, NULL),
(15, 4, 37.1, N'112/72', 88, 18.0,  108.0, N'Trẻ 5 tuổi, ho 1 tuần'),
(16, 5, 36.9, N'128/84', 74, 70.0,  169.0, N'Đau đầu, chóng mặt'),
(17, 5, 37.0, N'122/78', 80, 63.0,  163.0, NULL);
GO

-- ================================================================
-- PHẦN 14: BỆNH ÁN
-- ================================================================
-- NguoiGhiNhan = MaNhanVien bác sĩ: 6=Tuấn, 7=Linh, 8=Hùng, 9=Mai, 10=Nam

INSERT INTO BENHANKHAM (MaPhieuKham, LyDoKham, TienSuBenh, ChanDoanSoBo, ChanDoanXacDinh, MaICD10, PacDoTieuTri, LoiDanBacSi, NgayTaiKham, NguoiGhiNhan, NgayCapNhat) VALUES
(1, N'Kiểm tra đường huyết định kỳ, mệt mỏi, tiểu nhiều',
   N'Đái tháo đường type 2 phát hiện 3 năm trước. Đang dùng Metformin 500mg.',
   N'Đái tháo đường type 2 chưa kiểm soát tốt',
   N'Đái tháo đường type 2, đường huyết tăng (HbA1c 8.2%)',
   N'E11.9', N'Tăng liều Metformin lên 1000mg sáng chiều. Bổ sung Vitamin C.',
   N'Ăn kiêng tinh bột, tập thể dục 30 phút/ngày. Tái khám sau 1 tháng.',
   '2025-06-12', 6, '2025-05-12 09:00:00'),

(2, N'Đau bụng hố chậu phải 2 ngày, sốt nhẹ',
   N'Không có tiền sử phẫu thuật bụng.',
   N'Viêm ruột thừa cấp',
   N'Viêm ruột thừa cấp, cần phẫu thuật',
   N'K35.9', N'Chuyển khoa ngoại phẫu thuật cắt ruột thừa nội soi.',
   N'Nhịn ăn, vào nhập viện ngay để phẫu thuật.',
   NULL, 7, '2025-05-12 10:00:00'),

(3, N'Đau đầu tái phát, chóng mặt khi đứng lên',
   N'Tăng huyết áp phát hiện 5 năm. Đang dùng Amlodipine 5mg.',
   N'Tăng huyết áp không kiểm soát',
   N'Tăng huyết áp độ 2, rối loạn tiền đình',
   N'I10', N'Tăng Amlodipine 10mg. Thêm Atorvastatin 10mg phòng ngừa biến chứng tim mạch.',
   N'Hạn chế muối < 5g/ngày. Tránh thay đổi tư thế đột ngột. Uống thuốc đều đặn.',
   '2025-06-12', 6, '2025-05-12 10:30:00'),

(4, N'Khám thai định kỳ lần 3, tuần 28',
   N'Mang thai lần đầu, không có bệnh lý nền.',
   N'Thai 28 tuần, phát triển bình thường',
   N'Thai nghén bình thường tuần 28',
   N'Z34.0', N'Bổ sung sắt, acid folic. Tiêm phòng uốn ván.',
   N'Uống viên sắt và acid folic đều đặn. Khám siêu âm tuần 32.',
   '2025-06-13', 9, '2025-05-13 09:00:00'),

(5, N'Đau đầu, chóng mặt, hay quên',
   N'Tăng huyết áp 10 năm. Đái tháo đường 5 năm. Đang dùng nhiều thuốc.',
   N'Tăng huyết áp mất kiểm soát, biến chứng thần kinh',
   N'Tăng huyết áp biến chứng, rối loạn tuần hoàn não',
   N'I67.9', N'Điều chỉnh phác đồ. Thêm Clopidogrel 75mg phòng đột quỵ.',
   N'Đo huyết áp tại nhà sáng chiều. Ghi chép nhật ký. Hạn chế rượu bia.',
   '2025-06-13', 6, '2025-05-13 10:30:00'),

(6, N'Trẻ sốt 38.5°C, ho khan, chảy nước mũi 3 ngày',
   N'Tiêm phòng đầy đủ. Không dị ứng thuốc.',
   N'Viêm hô hấp trên do virus',
   N'Viêm mũi họng cấp do virus',
   N'J06.9', N'Paracetamol 250mg khi sốt > 38.5. Ambroxol siro 5ml x 3 lần/ngày.',
   N'Cho trẻ uống nhiều nước. Tắm nước ấm khi sốt. Tái khám nếu sốt không giảm sau 3 ngày.',
   NULL, 8, '2025-05-14 08:30:00'),

(7, N'Mệt mỏi, tiểu nhiều, khát nước, sụt cân 3kg/tháng',
   N'Gia đình có người mắc đái tháo đường.',
   N'Đái tháo đường type 2 mới phát hiện',
   N'Đái tháo đường type 2',
   N'E11.9', N'Bắt đầu Metformin 500mg sau ăn sáng và tối. Theo dõi đường huyết.',
   N'Ăn kiêng tinh bột, đường. Tập thể dục ít nhất 30 phút/ngày. Không bỏ bữa.',
   '2025-06-14', 6, '2025-05-14 11:00:00'),

(8, N'Đau thắt lưng lan xuống chân phải 2 tuần, tê bì ngón chân',
   N'Làm việc văn phòng, ngồi nhiều.',
   N'Thoát vị đĩa đệm L4-L5',
   N'Thoát vị đĩa đệm cột sống thắt lưng',
   N'M51.1', N'Diclofenac 50mg x 2 lần/ngày sau ăn. X-quang cột sống, xem xét MRI.',
   N'Hạn chế mang vác nặng. Tập bài tập cơ lưng. Không ngồi quá 1 tiếng liên tục.',
   '2025-05-28', 7, '2025-05-14 09:30:00'),

(9, N'Nổi mề đay toàn thân 5 ngày, ngứa nhiều về đêm',
   N'Tiền sử viêm mũi dị ứng.',
   N'Mề đay mạn tính',
   N'Mề đay do dị ứng',
   N'L50.0', N'Cetirizine 10mg x 1 viên tối. Hydrocortisone cream bôi vùng ngứa.',
   N'Tránh thức ăn tanh, đồ biển. Không dùng xà phòng có hương liệu. Mặc đồ cotton.',
   '2025-05-29', 10, '2025-05-15 09:00:00'),

(10, N'Kiểm tra huyết áp, tim đập nhanh bất thường',
   N'Tăng huyết áp 8 năm. Đang dùng Amlodipine 5mg.',
   N'Tăng huyết áp không kiểm soát, rối loạn nhịp tim',
   N'Tăng huyết áp, nhịp tim nhanh',
   N'I10', N'Tăng Amlodipine 10mg. Làm ECG và siêu âm tim đánh giá.',
   N'Hạn chế cà phê, thuốc lá. Uống thuốc đúng giờ. Tái khám sau 2 tuần.',
   '2025-05-31', 6, '2025-05-15 10:30:00'),

(11, N'Ra huyết trắng bất thường, ngứa âm hộ 1 tuần',
   N'Không có tiền sử phụ khoa.',
   N'Viêm âm đạo do nấm',
   N'Viêm âm đạo do Candida',
   N'B37.3', N'Metronidazole gel âm đạo. Fluconazole 150mg uống 1 lần.',
   N'Vệ sinh vùng kín đúng cách. Mặc đồ lót cotton. Không thụt rửa âm đạo.',
   NULL, 9, '2025-05-16 09:30:00'),

(12, N'Đau lưng lan xuống chân trái, tê bì cẳng chân',
   N'Viêm cột sống 3 năm.',
   N'Thoát vị đĩa đệm L5-S1',
   N'Thoát vị đĩa đệm, chèn ép rễ thần kinh',
   N'M51.1', N'Diclofenac 50mg x 2 lần/ngày. Chỉ định MRI cột sống thắt lưng.',
   N'Nghỉ ngơi, không gắng sức. Vật lý trị liệu 2 tuần.',
   '2025-05-30', 7, '2025-05-16 11:00:00'),

(13, N'Trẻ tiêu chảy 2 ngày, nôn ói, mất nước nhẹ',
   N'Không có tiền sử đặc biệt.',
   N'Tiêu chảy cấp, mất nước nhẹ',
   N'Viêm dạ dày ruột cấp do virus',
   N'A09', N'ORS bù nước. Kẽm bổ sung. Chế độ ăn BRAT.',
   N'Cho trẻ uống ORS từng ngụm nhỏ. Ăn cháo, chuối, táo nghiền. Tái khám nếu nôn nhiều.',
   NULL, 8, '2025-05-16 09:00:00'),

(14, N'Mệt mỏi kéo dài 2 tuần, chán ăn, sụt cân',
   N'Không có bệnh nền.',
   N'Hội chứng mệt mỏi, cần xét nghiệm tầm soát',
   N'Thiếu máu nhẹ, thiếu vitamin D',
   N'D64.9', N'Bổ sung sắt + Vitamin C. Multivitamin tổng hợp.',
   N'Ăn đầy đủ chất đạm và rau xanh. Ngủ đủ giấc. Tái khám sau 1 tháng.',
   '2025-06-17', 6, '2025-05-17 09:30:00');
GO

-- ================================================================
-- PHẦN 15: DỊCH VỤ SỬ DỤNG TRONG PHIẾU KHÁM
-- ================================================================
-- MaDichVu: 1=Khám nội, 2=Khám ngoại, 3=Khám nhi, 4=Khám sản, 5=Khám da liễu, 6=Tái khám nội...

INSERT INTO PHIEUKHAM_DICHVU (MaPhieuKham, MaDichVu, SoLuong, DonGiaTaiThoiDiem) VALUES
(1,  6, 1,  80000),  -- Tái khám nội
(2,  2, 1, 150000),  -- Khám ngoại
(3,  6, 1,  80000),  -- Tái khám nội
(4,  4, 1, 180000),  -- Khám sản, siêu âm thai
(4,  8, 1, 200000),  -- Siêu âm thai
(5,  1, 1, 150000),  -- Khám nội
(6,  1, 1, 150000),  -- Khám nội
(7,  2, 1, 150000),  -- Khám ngoại
(8,  5, 1, 150000),  -- Khám da liễu
(9,  1, 1, 150000),  -- Khám nội
(10, 4, 1, 180000),  -- Khám sản
(11, 2, 1, 150000),  -- Khám ngoại
(12, 3, 1, 120000),  -- Khám nhi
(13, 6, 1,  80000),  -- Tái khám nội
(14, 3, 1, 120000),  -- Khám nhi
(15, 1, 1, 150000),  -- Khám nội
(16, 5, 1, 150000),  -- Khám da liễu
(17, 7, 1,  80000);  -- Tái khám nhi
GO

-- ================================================================
-- PHẦN 16: CHỈ ĐỊNH CẬN LÂM SÀNG
-- ================================================================
-- MaDichVuCLS: 1=CBC, 2=Đường huyết, 3=HbA1c, 4=Lipid, 5=AST/ALT, 6=Creatinine
--              7=TSH, 8=TPTNT, 9=X-quang ngực, 10=X-quang cột sống, 11=X-quang tứ chi
--              12=Siêu âm ổ bụng, 13=Siêu âm tim, 14=Siêu âm giáp, 15=SA thai, 16=SA vú, 17=ECG
-- MaBacSiChiDinh = MaNhanVien

INSERT INTO CHIDINHCLS (MaPhieuKham, MaDichVuCLS, MaBacSiChiDinh, GhiChu, TrangThai) VALUES
-- Phiếu 1: BN Lan - ĐTĐ
(1,  2, 6, N'Đường huyết lúc đói',              N'HOAN_THANH'),
(1,  3, 6, N'HbA1c kiểm tra kiểm soát ĐH',     N'HOAN_THANH'),
(1,  4, 6, N'Kiểm tra mỡ máu',                  N'HOAN_THANH'),
-- Phiếu 3: BN Phúc - THA
(3,  1, 6, N'Công thức máu tổng quát',           N'HOAN_THANH'),
(3,  5, 6, N'Kiểm tra chức năng gan',            N'HOAN_THANH'),
(3,  6, 6, N'Kiểm tra chức năng thận',           N'HOAN_THANH'),
(3, 17, 6, N'Đánh giá nhịp tim',                 N'HOAN_THANH'),
-- Phiếu 5: BN Đức - THA + ĐTĐ
(5,  1, 6, NULL,                                 N'HOAN_THANH'),
(5,  2, 6, N'Đường huyết sau ăn 2h',            N'HOAN_THANH'),
(5,  6, 6, NULL,                                 N'HOAN_THANH'),
(5, 17, 6, N'Đánh giá tim mạch toàn diện',      N'HOAN_THANH'),
(5, 13, 6, N'Siêu âm tim đánh giá chức năng',   N'HOAN_THANH'),
-- Phiếu 7: BN Minh - đau lưng
(7,  9, 7, N'X-quang ngực CTC',                 N'HOAN_THANH'),
(7, 10, 7, N'X-quang cột sống thắt lưng thẳng nghiêng', N'HOAN_THANH'),
-- Phiếu 10: BN Khánh - THA nhịp nhanh
(10,17, 6, N'Ghi điện tim 12 chuyển đạo',       N'HOAN_THANH'),
(10,13, 6, N'Siêu âm tim',                      N'HOAN_THANH'),
(10, 1, 6, NULL,                                N'HOAN_THANH'),
-- Phiếu 12: BN Toàn - đau lưng lan xuống chân
(12,10, 7, N'X-quang cột sống LS thẳng nghiêng', N'HOAN_THANH'),
-- Phiếu 15: Hôm nay - đang chờ kết quả
(15, 1, 8, N'Công thức máu',                    N'DANG_THUC_HIEN'),
(15, 8, 8, N'Tổng phân tích nước tiểu',         N'DA_CHI_DINH'),
-- Phiếu 16: Hôm nay
(16, 1, 6, NULL,                                N'DA_CHI_DINH'),
(16, 2, 6, NULL,                                N'DA_CHI_DINH'),
(16,12, 6, N'Siêu âm ổ bụng tổng quát',        N'DA_CHI_DINH');
GO

-- ================================================================
-- PHẦN 17: KẾT QUẢ CẬN LÂM SÀNG
-- ================================================================
-- MaKyThuatVien = MaNhanVien: 11=Đặng Hữu Khoa
-- MaDonViKQ (đơn vị kết quả): 6=mg, 7=ml, 8=Lần

INSERT INTO KETQUACLS (MaChiDinh, MaKyThuatVien, KetQuaChuoi, KetQuaSo, MaDonViKQ, KhoangThamChieu, KetLuan) VALUES
-- Phiếu 1: BN Lan
(1,  11, NULL, 8.9,  6, N'3.9 - 6.1 mmol/L',  N'Đường huyết cao, vượt mức bình thường'),
(2,  11, NULL, 8.2,  8, N'< 7.0%',             N'HbA1c cao, kiểm soát ĐH chưa tốt'),
(3,  11, N'Cholesterol: 5.8 mmol/L; Triglyceride: 2.1 mmol/L; HDL: 1.1; LDL: 3.7',
         NULL, NULL, N'TC < 5.2; TG < 1.7; HDL > 1.0; LDL < 3.4', N'Rối loạn lipid máu nhẹ'),
-- Phiếu 3: BN Phúc
(4,  11, NULL, 5.2,  8, N'3.9 - 6.1 mmol/L',  N'Đường huyết bình thường'),
(5,  11, N'AST: 28 U/L; ALT: 35 U/L',
         NULL, NULL, N'AST < 40; ALT < 40',   N'Chức năng gan bình thường'),
(6,  11, N'Creatinine: 95 µmol/L; Ure: 6.8 mmol/L',
         NULL, NULL, N'Cre: 62-106; Ure: 2.5-6.4', N'Chức năng thận bình thường'),
(7,  11, N'Nhịp xoang đều, tần số 76 ck/ph. Không có dấu hiệu bất thường.',
         NULL, NULL, NULL,                    N'Điện tim bình thường'),
-- Phiếu 5: BN Đức
(8,  11, N'RBC: 4.2; WBC: 7.8; PLT: 210',
         NULL, NULL, NULL,                    N'Công thức máu trong giới hạn bình thường'),
(9,  11, NULL, 9.2,  6, N'< 7.8 mmol/L (sau ăn 2h)', N'Đường huyết sau ăn tăng cao'),
(10, 11, N'Creatinine: 108 µmol/L; Ure: 7.2 mmol/L',
         NULL, NULL, N'Cre: 62-106; Ure: 2.5-6.4', N'Creatinine tăng nhẹ, cần theo dõi'),
(11, 11, N'Nhịp xoang, tần số 88 ck/ph. Trục lệch trái nhẹ. Không có dấu hiệu thiếu máu cơ tim.',
         NULL, NULL, NULL,                    N'Điện tim chấp nhận được'),
(12, 11, N'EF: 62%. Kích thước buồng tim bình thường. Van tim không hở. Không có rối loạn vận động vùng.',
         NULL, NULL, N'EF > 55%',            N'Chức năng tim bình thường'),
-- Phiếu 7: BN Minh
(13, 11, N'Phổi 2 bên thông thoáng. Không thấy tổn thương. Tim bóng bình thường.',
         NULL, NULL, NULL,                   N'X-quang ngực bình thường'),
(14, 11, N'L4-L5: Giảm chiều cao đĩa đệm. Có gai xương bờ trên thân L4. Khoảng gian đốt hẹp.',
         NULL, NULL, NULL,                   N'Thoái hóa đĩa đệm L4-L5, gai xương'),
-- Phiếu 10: BN Khánh
(15, 11, N'Nhịp xoang nhanh, tần số 98 ck/ph. ST không thay đổi. PR và QRS bình thường.',
         NULL, NULL, NULL,                   N'Nhịp tim nhanh xoang'),
(16, 11, N'EF: 58%. Phì đại thất trái nhẹ. Van hai lá hở nhẹ độ 1.',
         NULL, NULL, N'EF > 55%',           N'Phì đại thất trái do THA, hở van hai lá nhẹ'),
(17, 11, N'RBC: 4.8; WBC: 9.2; HGB: 140; PLT: 235',
         NULL, NULL, NULL,                  N'Công thức máu bình thường'),
-- Phiếu 12: BN Toàn
(18, 11, N'L5-S1: Hẹp khoảng gian đốt. Thoái hóa đĩa đệm L4-L5, L5-S1.',
         NULL, NULL, NULL,                  N'Thoái hóa đĩa đệm nhiều tầng');
GO

-- File đính kèm kết quả X-quang
INSERT INTO FILEKQCLS (MaKetQua, TenFile, DuongDan, LoaiFile, KichThuoc) VALUES
(14, N'xquang_cot_song_BN002.jpg', N'/uploads/ket-qua-cls/2025/05/14/xquang_cot_song_BN002.jpg', N'HINH_ANH', 2456320),
(15, N'ecg_BN008_20250515.pdf',    N'/uploads/ket-qua-cls/2025/05/15/ecg_BN008_20250515.pdf',    N'PDF',      1024000),
(16, N'echo_tim_BN008.jpg',        N'/uploads/ket-qua-cls/2025/05/15/echo_tim_BN008.jpg',        N'HINH_ANH', 3145728),
(18, N'xquang_cot_song_BN010.jpg', N'/uploads/ket-qua-cls/2025/05/16/xquang_cot_song_BN010.jpg', N'HINH_ANH', 2654208);
GO

-- ================================================================
-- PHẦN 18: ĐƠN THUỐC
-- ================================================================

INSERT INTO DONTHUOC (MaPhieuKham, MaBacSi, ChanDoanTrenDon, LuuYDacBiet, TrangThai, NgayTao, NgayKy) VALUES
(1,  6, N'Đái tháo đường type 2, rối loạn lipid máu',   N'Uống thuốc sau ăn. Không bỏ bữa.', N'DA_CAP_PHAT', '2025-05-12 09:00:00', '2025-05-12 09:15:00'),
(3,  6, N'Tăng huyết áp độ 2, rối loạn tiền đình',      N'Uống đúng giờ, không bỏ liều.',    N'DA_CAP_PHAT', '2025-05-12 10:30:00', '2025-05-12 10:45:00'),
(5,  6, N'Tăng huyết áp biến chứng, ĐTĐ type 2',        N'Kiêng muối, đường. Theo dõi sát.',  N'DA_CAP_PHAT', '2025-05-13 10:30:00', '2025-05-13 10:45:00'),
(6,  8, N'Viêm mũi họng cấp do virus',                   N'Uống nhiều nước. Nghỉ ngơi.',       N'DA_CAP_PHAT', '2025-05-14 08:30:00', '2025-05-14 08:45:00'),
(7,  6, N'Đái tháo đường type 2 mới phát hiện',          N'Ăn kiêng tinh bột. Tập thể dục.',  N'DA_CAP_PHAT', '2025-05-14 11:00:00', '2025-05-14 11:15:00'),
(8,  7, N'Thoát vị đĩa đệm L4-L5',                      N'Không mang vác nặng.',               N'DA_CAP_PHAT', '2025-05-14 09:30:00', '2025-05-14 09:45:00'),
(9, 10, N'Mề đay do dị ứng',                             N'Tránh thức ăn tanh.',                N'DA_CAP_PHAT', '2025-05-15 09:00:00', '2025-05-15 09:15:00'),
(10, 6, N'Tăng huyết áp, nhịp tim nhanh',                N'Uống thuốc đúng giờ.',               N'DA_CAP_PHAT', '2025-05-15 10:30:00', '2025-05-15 10:45:00'),
(13, 8, N'Viêm dạ dày ruột cấp do virus',                N'Bù nước tích cực.',                  N'DA_CAP_PHAT', '2025-05-16 09:00:00', '2025-05-16 09:10:00'),
(14, 6, N'Thiếu máu nhẹ, thiếu Vitamin D',               N'Uống sắt cách xa bữa ăn 1 tiếng.', N'DA_KY',      '2025-05-17 09:30:00', '2025-05-17 09:45:00');
GO

INSERT INTO CHITIETDONTHUOC (MaDonThuoc, MaThuoc, SoLuong, HuongDanSuDung, SoNgaySuDung, DonGiaTaiThoiDiem) VALUES
-- Đơn 1: BN Lan - ĐTĐ + mỡ máu
(1,  4,  60, N'Uống 1 viên sau ăn sáng và tối',        30, 900),
(1,  6,  30, N'Uống 1 viên trước khi ngủ',              30, 3800),
(1,  9,  30, N'Uống 1 viên sủi buổi sáng',             30, 900),
-- Đơn 2: BN Phúc - THA
(2,  5,  30, N'Uống 1 viên 10mg vào buổi sáng',        30, 1100),
(2,  6,  30, N'Uống 1 viên trước khi ngủ',              30, 3800),
-- Đơn 3: BN Đức - THA + ĐTĐ
(3,  5,  30, N'Uống 1 viên sáng',                       30, 1100),
(3,  4,  60, N'Uống 1 viên sau ăn sáng và tối',        30, 900),
(3, 13,  30, N'Uống 1 viên sáng sau ăn',               30, 6800),
-- Đơn 4: Trẻ BN Thu - viêm hô hấp
(4,  1,  10, N'Uống 1/2 viên khi sốt > 38.5°C, cách nhau ít nhất 4-6 tiếng', 5, 1200),
(4, 11,  15, N'Uống 5ml x 3 lần/ngày sau ăn',          5, 1100),
-- Đơn 5: BN Phong - ĐTĐ mới
(5,  4,  60, N'Uống 1 viên sau ăn sáng và tối',        30, 900),
(5,  9,  30, N'Uống 1 viên sủi buổi sáng',             30, 900),
-- Đơn 6: BN Toàn - đau lưng
(6, 10,  20, N'Uống 1 viên sáng và chiều sau ăn',      10, 1400),
-- Đơn 7: BN Yến - mề đay
(7,  7,  30, N'Uống 1 viên trước khi ngủ',             30, 800),
(7, 15,   1, N'Bôi lớp mỏng lên vùng ngứa 2 lần/ngày', 14, 12000),
-- Đơn 8: BN Khánh - THA nhịp nhanh
(8,  5,  30, N'Uống 1 viên 10mg vào buổi sáng',        30, 1100),
(8,  6,  30, N'Uống 1 viên trước khi ngủ',              30, 3800),
-- Đơn 9: Trẻ - tiêu chảy
(9, 18,  14, N'Pha 1 gói với 200ml nước, uống từng ngụm nhỏ', 7, 3500),
(9,  1,   5, N'Uống khi sốt, liều theo cân nặng',       3, 1200),
-- Đơn 10: BN Bảo - thiếu máu (chưa cấp phát)
(10,16,  30, N'Uống 1 viên/ngày sau bữa ăn sáng',      30, 22000),
(10, 9,  30, N'Uống 1 viên sủi buổi sáng, giúp hấp thu sắt', 30, 900);
GO

-- ================================================================
-- PHẦN 19: CẤP PHÁT THUỐC
-- ================================================================
-- MaDuocSi = MaNhanVien: 12=Bùi Thị Hoa

INSERT INTO PHIEUCAPPHATTHUOC (MaDonThuoc, MaDuocSi, ThoiGianCap, GhiChu) VALUES
(1, 12, '2025-05-12 11:00:00', NULL),
(2, 12, '2025-05-12 11:30:00', NULL),
(3, 12, '2025-05-13 11:00:00', NULL),
(4, 12, '2025-05-14 09:30:00', NULL),
(5, 12, '2025-05-14 11:30:00', NULL),
(6, 12, '2025-05-14 10:30:00', NULL),
(7, 12, '2025-05-15 09:30:00', NULL),
(8, 12, '2025-05-15 11:00:00', NULL),
(9, 12, '2025-05-16 09:30:00', NULL);
GO

-- Phiếu hủy thuốc (1 trường hợp mẫu)
INSERT INTO PHIEUHUY (MaThuoc, SoLuong, LyDoHuy, MaDuocSi) VALUES
(12, 5, N'HET_HAN', 12);  -- Salbutamol hết hạn tháng 5
GO

-- ================================================================
-- PHẦN 20: HÓA ĐƠN & THANH TOÁN
-- ================================================================
-- NguoiTao = MaTaiKhoan: 2=le.tan1

INSERT INTO HOADON (SoHoaDon, MaPhieuKham, NguoiTao, TamTinh, GiamGia, TongThanhToan, TrangThai, NgayTao, NgayThanhToan) VALUES
(N'HD20250512001', 1,  2,  390000,      0,  390000, N'DA_THANH_TOAN', '2025-05-12 11:10:00', '2025-05-12 11:15:00'),
(N'HD20250512002', 2,  2,  550000,      0,  550000, N'DA_THANH_TOAN', '2025-05-12 11:20:00', '2025-05-12 11:25:00'),
(N'HD20250512003', 3,  2,  200000,      0,  200000, N'DA_THANH_TOAN', '2025-05-12 11:00:00', '2025-05-12 11:05:00'),
(N'HD20250513001', 4,  2,  770000,      0,  770000, N'DA_THANH_TOAN', '2025-05-13 09:40:00', '2025-05-13 09:45:00'),
(N'HD20250513002', 5,  2, 1060000,      0, 1060000, N'DA_THANH_TOAN', '2025-05-13 11:10:00', '2025-05-13 11:20:00'),
(N'HD20250514001', 6,  2,  280000,      0,  280000, N'DA_THANH_TOAN', '2025-05-14 09:10:00', '2025-05-14 09:15:00'),
(N'HD20250514002', 7,  2,  390000,      0,  390000, N'DA_THANH_TOAN', '2025-05-14 11:20:00', '2025-05-14 11:30:00'),
(N'HD20250514003', 8,  2,  480000,      0,  480000, N'DA_THANH_TOAN', '2025-05-14 10:10:00', '2025-05-14 10:15:00'),
(N'HD20250515001', 9,  2,  312000,  12000,  300000, N'DA_THANH_TOAN', '2025-05-15 09:50:00', '2025-05-15 09:55:00'),
(N'HD20250515002',10,  2,  880000,      0,  880000, N'DA_THANH_TOAN', '2025-05-15 11:10:00', '2025-05-15 11:20:00'),
(N'HD20250516001',11,  2,  390000,      0,  390000, N'DA_THANH_TOAN', '2025-05-16 10:10:00', '2025-05-16 10:15:00'),
(N'HD20250516002',12,  3,  790000,      0,  790000, N'DA_THANH_TOAN', '2025-05-16 11:40:00', '2025-05-16 11:50:00'),
(N'HD20250516003',13,  3,  198000,      0,  198000, N'DA_THANH_TOAN', '2025-05-16 09:40:00', '2025-05-16 09:45:00'),
(N'HD20250517001',14,  2,  320000,      0,  320000, N'DA_THANH_TOAN', '2025-05-17 10:10:00', '2025-05-17 10:20:00'),
-- Hóa đơn chờ thanh toán (hôm nay)
(N'HD20250517002',15,  2,  356000,      0,  356000, N'CHO_THANH_TOAN','2025-05-17 10:00:00', NULL),
(N'HD20250517003',16,  2,  150000,      0,  150000, N'CHO_THANH_TOAN','2025-05-17 09:00:00', NULL);
GO

-- Chi tiết hóa đơn
INSERT INTO CHITIETHOADON (MaHoaDon, LoaiKhoan, TenKhoan, SoLuong, DonGia, ThanhTien, MaBanGhiGoc) VALUES
-- HD001: BN Lan
(1, N'DICH_VU',     N'Tái khám nội tổng quát',     1,  80000,   80000, 1),
(1, N'CAN_LAM_SANG',N'Đường huyết lúc đói (FPG)',   1,  60000,   60000, 1),
(1, N'CAN_LAM_SANG',N'HbA1c',                       1, 180000,  180000, 2),
(1, N'CAN_LAM_SANG',N'Bộ mỡ máu (Lipid profile)',   1, 200000,  200000, 3),
(1, N'THUOC',       N'Metformin 500mg x 60 viên',   60,   900,   54000, 1),
(1, N'THUOC',       N'Atorvastatin 10mg x 30 viên', 30,  3800,  114000, 2),
(1, N'THUOC',       N'Vitamin C 1000mg x 30 viên',  30,   900,   27000, 3),
-- HD002: BN Minh - ngoại khoa
(2, N'DICH_VU',     N'Khám ngoại tổng quát',        1, 150000,  150000, 2),
(2, N'CAN_LAM_SANG',N'X-quang ngực thẳng',          1, 120000,  120000, 13),
(2, N'CAN_LAM_SANG',N'X-quang cột sống thắt lưng',  1, 150000,  150000, 14),
(2, N'THUOC',       N'Diclofenac 50mg x 20 viên',   20,  1400,   28000, 6),
-- HD004: BN Hoa - sản khoa
(4, N'DICH_VU',     N'Khám sản phụ khoa',           1, 180000,  180000, 4),
(4, N'DICH_VU',     N'Siêu âm thai 2D',             1, 200000,  200000, 5),
(4, N'THUOC',       N'ORS bù điện giải x 14 gói',  14,   3500,   49000, 9),
-- HD014: BN Bảo - hôm nay đã TT
(14,N'DICH_VU',     N'Tái khám nội tổng quát',      1,  80000,   80000,13),
(14,N'THUOC',       N'Multivitamin x 30 hộp',       30, 22000,  660000,10),
-- HD015: chờ TT
(15,N'DICH_VU',     N'Khám nhi',                    1, 120000,  120000,14),
(15,N'CAN_LAM_SANG',N'Công thức máu toàn phần',     1, 120000,  120000,19),
(15,N'CAN_LAM_SANG',N'Tổng phân tích nước tiểu',    1,  60000,   60000,20),
(15,N'THUOC',       N'Paracetamol 500mg x 10 viên', 10,  1200,   12000,4),
(15,N'THUOC',       N'ORS bù nước x 14 gói',       14,  3500,   49000,9),
-- HD016: chờ TT
(16,N'DICH_VU',     N'Khám nội tổng quát',          1, 150000,  150000,15);
GO

-- Giao dịch thanh toán
-- MaPhuongThuc: 1=TIEN_MAT, 2=CHUYEN_KHOAN, 3=QR_CODE
-- NguoiThuTien (MaTaiKhoan): 2=le.tan1, 3=le.tan2

INSERT INTO THANHTOAN (MaHoaDon, MaPhuongThuc, SoTienThanhToan, MaGiaoDich, NguoiThuTien, ThoiGianTT) VALUES
(1,  1, 390000, NULL,              2, '2025-05-12 11:15:00'),
(2,  1, 550000, NULL,              2, '2025-05-12 11:25:00'),
(3,  1, 200000, NULL,              2, '2025-05-12 11:05:00'),
(4,  3, 770000, N'QR2025051300001',2, '2025-05-13 09:45:00'),
(5,  2,1060000, N'MB2025051300023',2, '2025-05-13 11:20:00'),
(6,  1, 280000, NULL,              2, '2025-05-14 09:15:00'),
(7,  1, 390000, NULL,              2, '2025-05-14 11:30:00'),
(8,  3, 480000, N'QR2025051400002',2, '2025-05-14 10:15:00'),
(9,  1, 300000, NULL,              3, '2025-05-15 09:55:00'),
(10, 2, 880000, N'VCB2025051500045',3,'2025-05-15 11:20:00'),
(11, 1, 390000, NULL,              2, '2025-05-16 10:15:00'),
(12, 3, 790000, N'QR2025051600003',3, '2025-05-16 11:50:00'),
(13, 1, 198000, NULL,              3, '2025-05-16 09:45:00'),
(14, 1, 320000, NULL,              2, '2025-05-17 10:20:00');
GO

-- ================================================================
-- PHẦN 21: ĐÁNH GIÁ DỊCH VỤ
-- ================================================================

INSERT INTO DANHGIADICHVU (MaBenhNhan, MaPhieuKham, MaBacSi, DiemSao, NhanXet, DaDuyet) VALUES
(1,  1, 6, 5, N'Bác sĩ Tuấn rất tận tình, giải thích rõ ràng. Phòng khám sạch sẽ, nhân viên thân thiện.', 1),
(2,  2, 7, 4, N'Bác sĩ Linh khám kỹ, chẩn đoán chính xác. Chờ hơi lâu nhưng dịch vụ tốt.', 1),
(3,  4, 9, 5, N'BS. Mai rất chu đáo, giải thích kỹ về quá trình mang thai. Rất hài lòng.', 1),
(5,  6, 8, 4, N'Bác sĩ khám kỹ cho con. Phòng khám nhi sạch sẽ, có đồ chơi cho trẻ.', 1),
(7,  9,10, 5, N'Bác sĩ Nam chẩn đoán đúng bệnh, điều trị hiệu quả. Bệnh đã giảm nhiều.', 0),
(8, 10, 6, 3, N'Khám được nhưng thời gian chờ đợi hơi lâu. Mong cải thiện thêm.', 1),
(9, 11, 9, 5, N'Rất hài lòng với dịch vụ. BS. Mai giải thích rõ ràng và quan tâm đến bệnh nhân.', 0),
(6,  7, 6, 4, N'Bác sĩ Tuấn chuyên nghiệp, phác đồ điều trị hợp lý. Thuốc uống thấy đỡ hơn nhiều.', 1);
GO

-- ================================================================
-- PHẦN 22: NHẬT KÝ HOẠT ĐỘNG (mẫu audit log)
-- ================================================================

INSERT INTO NHATKYHOATDONG (MaTaiKhoan, HanhDong, TenBang, MaBanGhi, GiaTriCu, GiaTriMoi, DiaChiIP, ThoiGian) VALUES
(1, N'DANG_NHAP',       NULL,        NULL, NULL, NULL,                                          N'192.168.1.1',  '2025-05-17 08:00:00'),
(2, N'DANG_NHAP',       NULL,        NULL, NULL, NULL,                                          N'192.168.1.2',  '2025-05-17 07:45:00'),
(2, N'TAO_PHIEU_KHAM',  N'PHIEUKHAM',18,   NULL, N'{"MaPhieu":"PK20250517005","MaBenhNhan":5}', N'192.168.1.2',  '2025-05-17 08:10:00'),
(3, N'DANG_NHAP',       NULL,        NULL, NULL, NULL,                                          N'192.168.1.3',  '2025-05-17 07:50:00'),
(6, N'SUA_BENH_AN',     N'BENHANKHAM',14, N'{"ChanDoanSoBo":"Mệt mỏi"}', N'{"ChanDoanSoBo":"Thiếu máu nhẹ"}', N'192.168.1.6', '2025-05-17 09:35:00'),
(12,N'CAP_PHAT_THUOC',  N'PHIEUCAPPHATTHUOC',9, NULL, N'{"MaDonThuoc":9,"SoLuong":"ORS x14"}',  N'192.168.1.12', '2025-05-16 09:30:00'),
(1, N'XEM_BAO_CAO',     N'V_DOANHTHU',NULL,NULL, NULL,                                         N'192.168.1.1',  '2025-05-17 08:30:00');
GO
