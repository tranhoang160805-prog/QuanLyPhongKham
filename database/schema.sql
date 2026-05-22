-- ================================================================
-- HỆ THỐNG QUẢN LÝ PHÒNG KHÁM TƯ NHÂN
-- Chuẩn hóa 3NF | SQL Server | Đặt tên tiếng Việt không dấu
-- ================================================================

USE master;
GO

IF EXISTS (SELECT name FROM sys.databases WHERE name = N'QuanLyPhongKham')
    DROP DATABASE QuanLyPhongKham;
GO

CREATE DATABASE QuanLyPhongKham
    COLLATE Vietnamese_CI_AS;
GO

USE QuanLyPhongKham;
GO

-- ================================================================
-- PHẦN 1: DANH MỤC DÙNG CHUNG
-- ================================================================

-- Vai trò người dùng trong hệ thống
CREATE TABLE VAITRO (
    MaVaiTro        SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenVaiTro       NVARCHAR(50)    NOT NULL,
    -- 'LE_TAN','DIEU_DUONG','BAC_SI','KY_THUAT_VIEN','DUOC_SI','QUAN_LY','IT_ADMIN','BENH_NHAN'
    MoTa            NVARCHAR(255),
    CONSTRAINT UQ_VaiTro_Ten UNIQUE (TenVaiTro)
);
GO

-- Chuyên khoa (Nội, Ngoại, Da liễu, Nhi, ...)
CREATE TABLE CHUYENKHOA (
    MaChuyenKhoa    SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenChuyenKhoa   NVARCHAR(100)   NOT NULL,
    MoTa            NVARCHAR(255),
    CONSTRAINT UQ_ChuyenKhoa_Ten UNIQUE (TenChuyenKhoa)
);
GO

-- Đơn vị tính (viên, mg, ml, chai, gói, ...)
CREATE TABLE DONVITINH (
    MaDonVi         SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenDonVi        NVARCHAR(50)    NOT NULL,
    CONSTRAINT UQ_DonViTinh_Ten UNIQUE (TenDonVi)
);
GO

-- Loại dịch vụ cận lâm sàng (Xét nghiệm, X-quang, Siêu âm, ...)
CREATE TABLE LOAICLSN (
    MaLoaiCLS       SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenLoaiCLS      NVARCHAR(100)   NOT NULL,   -- CLS = Cận Lâm Sàng
    MoTa            NVARCHAR(255),
    CONSTRAINT UQ_LoaiCLS_Ten UNIQUE (TenLoaiCLS)
);
GO

-- Phương thức thanh toán
CREATE TABLE PHUONGTHUCTT (
    MaPhuongThuc    SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenPhuongThuc   NVARCHAR(50)    NOT NULL,   -- 'TIEN_MAT','CHUYEN_KHOAN','QR'
    CONSTRAINT UQ_PhuongThucTT_Ten UNIQUE (TenPhuongThuc)
);
GO

-- Trạng thái phiếu khám
CREATE TABLE TRANGTHAIPHIEUKHAM (
    MaTrangThai     SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenTrangThai    NVARCHAR(50)    NOT NULL,
    -- 'CHO_KHAM','DA_SO_KHAM','DANG_KHAM','CHO_CLS','HOAN_THANH','DA_HUY'
    CONSTRAINT UQ_TrangThaiPK_Ten UNIQUE (TenTrangThai)
);
GO

-- Trạng thái lịch hẹn
CREATE TABLE TRANGTHAILICHCHHEN (
    MaTrangThai     SMALLINT        PRIMARY KEY IDENTITY(1,1),
    TenTrangThai    NVARCHAR(50)    NOT NULL,
    -- 'CHO_XAC_NHAN','DA_XAC_NHAN','DOI_LICH','DA_HUY','HOAN_THANH'
    CONSTRAINT UQ_TrangThaiLH_Ten UNIQUE (TenTrangThai)
);
GO

-- ================================================================
-- PHẦN 2: TÀI KHOẢN & NHÂN SỰ
-- ================================================================

-- Tài khoản đăng nhập hệ thống (nội bộ + bệnh nhân online)
CREATE TABLE TAIKHOAN (
    MaTaiKhoan      INT             PRIMARY KEY IDENTITY(1,1),
    TenDangNhap     NVARCHAR(100)   NOT NULL,
    MatKhauHash     NVARCHAR(255)   NOT NULL,
    SoDienThoai     NVARCHAR(15),
    DangHoatDong    BIT             NOT NULL DEFAULT 1,
    SoLanSaiMK      TINYINT         NOT NULL DEFAULT 0,     -- Số lần nhập sai mật khẩu
    KhoaDen         DATETIME2,                              -- NULL = không bị khóa
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    LanDangNhapCuoi DATETIME2,
    CONSTRAINT UQ_TaiKhoan_TenDangNhap  UNIQUE (TenDangNhap),
    CONSTRAINT UQ_TaiKhoan_SDT          UNIQUE (SoDienThoai)
);
GO

-- Phân quyền: 1 tài khoản có thể có nhiều vai trò
CREATE TABLE TAIKHOAN_VAITRO (
    MaTaiKhoan      INT             NOT NULL,
    MaVaiTro        SMALLINT        NOT NULL,
    NgayPhanQuyen   DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT PK_TaiKhoan_VaiTro PRIMARY KEY (MaTaiKhoan, MaVaiTro),
    CONSTRAINT FK_TKVT_TaiKhoan   FOREIGN KEY (MaTaiKhoan) REFERENCES TAIKHOAN(MaTaiKhoan),
    CONSTRAINT FK_TKVT_VaiTro     FOREIGN KEY (MaVaiTro)   REFERENCES VAITRO(MaVaiTro)
);
GO

-- Nhân viên phòng khám (Bác sĩ, Lễ tân, Điều dưỡng, Dược sĩ, IT, Quản lý)
CREATE TABLE NHANVIEN (
    MaNhanVien      INT             PRIMARY KEY IDENTITY(1,1),
    MaTaiKhoan      INT             NOT NULL,
    HoTen           NVARCHAR(100)   NOT NULL,
    NgaySinh        DATE,
    GioiTinh        NCHAR(1)        CHECK (GioiTinh IN ('M','F','O')),  -- M/F/O(ther)
    CCCD            NVARCHAR(20),
    SoDienThoai     NVARCHAR(15),
    Email           NVARCHAR(100),
    DiaChi          NVARCHAR(500),
    MaChuyenKhoa    SMALLINT,                   -- Chỉ điền cho bác sĩ
    BangCap         NVARCHAR(150),              -- Bằng cấp / học vị
    SoChungChi      NVARCHAR(50),               -- Số chứng chỉ hành nghề
    NgayVaoLam      DATE,
    DangHoatDong    BIT             NOT NULL DEFAULT 1,
    CONSTRAINT UQ_NhanVien_TaiKhoan   UNIQUE (MaTaiKhoan),
    CONSTRAINT UQ_NhanVien_CCCD       UNIQUE (CCCD),
    CONSTRAINT FK_NhanVien_TaiKhoan   FOREIGN KEY (MaTaiKhoan)    REFERENCES TAIKHOAN(MaTaiKhoan),
    CONSTRAINT FK_NhanVien_ChuyenKhoa FOREIGN KEY (MaChuyenKhoa)  REFERENCES CHUYENKHOA(MaChuyenKhoa)
);
GO

-- Nhật ký hoạt động (audit log) — theo yêu cầu chủ phòng khám
CREATE TABLE NHATKYHOATDONG (
    MaNhatKy        BIGINT          PRIMARY KEY IDENTITY(1,1),
    MaTaiKhoan      INT             NOT NULL,
    HanhDong        NVARCHAR(100)   NOT NULL,   -- 'DANG_NHAP','SUA_BENH_NHAN','XOA_THUOC',...
    TenBang         NVARCHAR(100),              -- Tên bảng bị tác động
    MaBanGhi        INT,                        -- ID bản ghi bị tác động
    GiaTriCu        NVARCHAR(MAX),              -- JSON giá trị cũ
    GiaTriMoi       NVARCHAR(MAX),              -- JSON giá trị mới
    DiaChiIP        NVARCHAR(45),
    ThoiGian        DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_NhatKy_TaiKhoan FOREIGN KEY (MaTaiKhoan) REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- ================================================================
-- PHẦN 3: BỆNH NHÂN
-- ================================================================

CREATE TABLE BENHNHAN (
    MaBenhNhan      INT             PRIMARY KEY IDENTITY(1,1),
    MaTaiKhoan      INT,                        -- NULL nếu lễ tân tạo trực tiếp (khám vãng lai)
    MaBN            NVARCHAR(20)    NOT NULL,   -- Mã tự sinh: BN00001, BN00002,...
    HoTen           NVARCHAR(100)   NOT NULL,
    NgaySinh        DATE,
    GioiTinh        NCHAR(1)        CHECK (GioiTinh IN ('M','F','O')),
    SoDienThoai     NVARCHAR(15),
    Email           NVARCHAR(100),
    DiaChi          NVARCHAR(500),
    SoBHYT          NVARCHAR(30),               -- Số bảo hiểm y tế
    NhomMau         NVARCHAR(5),               -- A, B, AB, O (+/-)
    DiUng           NVARCHAR(MAX),             -- Dị ứng thuốc / thức ăn
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    NgayCapNhat     DATETIME2,
    CONSTRAINT UQ_BenhNhan_MaBN     UNIQUE (MaBN),
    CONSTRAINT UQ_BenhNhan_SoBHYT   UNIQUE (SoBHYT),
    CONSTRAINT UQ_BenhNhan_TaiKhoan UNIQUE (MaTaiKhoan),
    CONSTRAINT FK_BenhNhan_TaiKhoan FOREIGN KEY (MaTaiKhoan) REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- ================================================================
-- PHẦN 4: LỊCH HẸN & PHIẾU KHÁM
-- ================================================================

-- Lịch hẹn (bệnh nhân đặt online hoặc lễ tân tạo)
CREATE TABLE LICHHEN (
    MaLichHen       INT             PRIMARY KEY IDENTITY(1,1),
    MaBenhNhan      INT             NOT NULL,
    MaBacSi         INT             NOT NULL,   -- MaNhanVien của bác sĩ
    MaChuyenKhoa    SMALLINT        NOT NULL,
    NgayHen         DATE            NOT NULL,
    GioHen          TIME            NOT NULL,
    MaTrangThai     SMALLINT        NOT NULL,
    GhiChu          NVARCHAR(MAX),
    NguoiTao        INT             NOT NULL,   -- MaTaiKhoan người tạo lịch
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    NgayHuy         DATETIME2,
    LyDoHuy         NVARCHAR(500),
    CONSTRAINT FK_LichHen_BenhNhan    FOREIGN KEY (MaBenhNhan)   REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_LichHen_BacSi       FOREIGN KEY (MaBacSi)      REFERENCES NHANVIEN(MaNhanVien),
    CONSTRAINT FK_LichHen_ChuyenKhoa  FOREIGN KEY (MaChuyenKhoa) REFERENCES CHUYENKHOA(MaChuyenKhoa),
    CONSTRAINT FK_LichHen_TrangThai   FOREIGN KEY (MaTrangThai)  REFERENCES TRANGTHAILICHCHHEN(MaTrangThai),
    CONSTRAINT FK_LichHen_NguoiTao    FOREIGN KEY (NguoiTao)     REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- Phiếu khám — bản ghi trung tâm của toàn bộ một lượt khám
CREATE TABLE PHIEUKHAM (
    MaPhieuKham     INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieu         NVARCHAR(20)    NOT NULL,   -- Mã in: PK00001,...
    MaBenhNhan      INT             NOT NULL,
    MaLichHen       INT,                        -- NULL nếu khám vãng lai
    MaBacSi         INT             NOT NULL,
    MaChuyenKhoa    SMALLINT        NOT NULL,
    MaTrangThai     SMALLINT        NOT NULL,
    SoThuTu         SMALLINT,                   -- Số thứ tự hàng chờ
    NgayKham        DATE            NOT NULL,
    NguoiTao        INT             NOT NULL,   -- Lễ tân tạo phiếu
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    NgayHoanThanh   DATETIME2,
    CONSTRAINT UQ_PhieuKham_MaPhieu   UNIQUE (MaPhieu),
    CONSTRAINT UQ_PhieuKham_LichHen   UNIQUE (MaLichHen),
    CONSTRAINT FK_PhieuKham_BenhNhan  FOREIGN KEY (MaBenhNhan)   REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_PhieuKham_LichHen   FOREIGN KEY (MaLichHen)    REFERENCES LICHHEN(MaLichHen),
    CONSTRAINT FK_PhieuKham_BacSi     FOREIGN KEY (MaBacSi)      REFERENCES NHANVIEN(MaNhanVien),
    CONSTRAINT FK_PhieuKham_CK        FOREIGN KEY (MaChuyenKhoa) REFERENCES CHUYENKHOA(MaChuyenKhoa),
    CONSTRAINT FK_PhieuKham_TrangThai FOREIGN KEY (MaTrangThai)  REFERENCES TRANGTHAIPHIEUKHAM(MaTrangThai),
    CONSTRAINT FK_PhieuKham_NguoiTao  FOREIGN KEY (NguoiTao)     REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- Kết quả sơ khám ban đầu (Điều dưỡng nhập)
CREATE TABLE SOKHAM (
    MaSoKham        INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieuKham     INT             NOT NULL,
    MaDieuDuong     INT             NOT NULL,   -- MaNhanVien của điều dưỡng
    NhietDo         DECIMAL(4,1),              -- °C
    HuyetAp         NVARCHAR(20),              -- VD: "120/80"
    NhipTim         SMALLINT,                  -- bpm
    CanNang         DECIMAL(5,2),              -- kg
    ChieuCao        DECIMAL(5,1),              -- cm
    GhiChu          NVARCHAR(MAX),
    ThoiGianNhap    DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT UQ_SoKham_PhieuKham  UNIQUE (MaPhieuKham),
    CONSTRAINT FK_SoKham_PhieuKham  FOREIGN KEY (MaPhieuKham)  REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_SoKham_DieuDuong  FOREIGN KEY (MaDieuDuong)  REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- ================================================================
-- PHẦN 5: BỆNH ÁN ĐIỆN TỬ (EMR)
-- ================================================================

CREATE TABLE BENHANKHAM (
    MaBenhAn        INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieuKham     INT             NOT NULL,
    LyDoKham        NVARCHAR(MAX),             -- Triệu chứng / lý do đến khám
    TienSuBenh      NVARCHAR(MAX),             -- Tiền sử bệnh lý cá nhân & gia đình
    ChanDoanSoBo    NVARCHAR(MAX),             -- Chẩn đoán sơ bộ
    ChanDoanXacDinh NVARCHAR(MAX),             -- Chẩn đoán xác định (sau CLS)
    MaICD10         NVARCHAR(10),              -- Mã bệnh theo ICD-10
    PacDoTieuTri    NVARCHAR(MAX),             -- Phác đồ điều trị
    LoiDanBacSi     NVARCHAR(MAX),             -- Lời dặn / hướng dẫn sau khám
    NgayTaiKham     DATE,                      -- Ngày hẹn tái khám
    NguoiGhiNhan    INT             NOT NULL,  -- MaNhanVien bác sĩ
    NgayCapNhat     DATETIME2,
    CONSTRAINT UQ_BenhAn_PhieuKham  UNIQUE (MaPhieuKham),
    CONSTRAINT FK_BenhAn_PhieuKham  FOREIGN KEY (MaPhieuKham)   REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_BenhAn_BacSi      FOREIGN KEY (NguoiGhiNhan)  REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- ================================================================
-- PHẦN 6: CẬN LÂM SÀNG (XÉT NGHIỆM / KỸ THUẬT)
-- ================================================================

-- Danh mục dịch vụ cận lâm sàng
CREATE TABLE DICHVUCLS (
    MaDichVuCLS     INT             PRIMARY KEY IDENTITY(1,1),
    MaLoaiCLS       SMALLINT        NOT NULL,
    TenDichVu       NVARCHAR(150)   NOT NULL,
    MaDonVi         SMALLINT,
    DonGia          DECIMAL(15,2)   NOT NULL DEFAULT 0,
    MoTa            NVARCHAR(255),
    DangHoatDong    BIT             NOT NULL DEFAULT 1,
    CONSTRAINT UQ_DichVuCLS_Ten   UNIQUE (TenDichVu),
    CONSTRAINT FK_DVCLS_LoaiCLS   FOREIGN KEY (MaLoaiCLS) REFERENCES LOAICLSN(MaLoaiCLS),
    CONSTRAINT FK_DVCLS_DonVi     FOREIGN KEY (MaDonVi)   REFERENCES DONVITINH(MaDonVi)
);
GO

-- Chỉ định cận lâm sàng (bác sĩ chỉ định)
CREATE TABLE CHIDINHCLS (
    MaChiDinh       INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieuKham     INT             NOT NULL,
    MaDichVuCLS     INT             NOT NULL,
    MaBacSiChiDinh  INT             NOT NULL,
    GhiChu          NVARCHAR(MAX),
    TrangThai       NVARCHAR(20)    NOT NULL DEFAULT 'DA_CHI_DINH'
                    CHECK (TrangThai IN ('DA_CHI_DINH','DANG_THUC_HIEN','HOAN_THANH','DA_HUY')),
    ThoiGianChiDinh DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_ChiDinhCLS_PhieuKham  FOREIGN KEY (MaPhieuKham)    REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_ChiDinhCLS_DichVu     FOREIGN KEY (MaDichVuCLS)    REFERENCES DICHVUCLS(MaDichVuCLS),
    CONSTRAINT FK_ChiDinhCLS_BacSi      FOREIGN KEY (MaBacSiChiDinh) REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- Kết quả cận lâm sàng
CREATE TABLE KETQUACLS (
    MaKetQua        INT             PRIMARY KEY IDENTITY(1,1),
    MaChiDinh       INT             NOT NULL,
    MaKyThuatVien   INT             NOT NULL,
    KetQuaChuoi     NVARCHAR(MAX),             -- Kết quả định tính (mô tả)
    KetQuaSo        DECIMAL(15,4),             -- Kết quả định lượng
    MaDonViKQ       SMALLINT,                  -- Đơn vị của kết quả (mg/dL, ...)
    KhoangThamChieu NVARCHAR(100),             -- Khoảng bình thường
    KetLuan         NVARCHAR(MAX),             -- Kết luận / nhận định
    ThoiGianThucHien DATETIME2      NOT NULL DEFAULT GETDATE(),
    CONSTRAINT UQ_KetQuaCLS_ChiDinh UNIQUE (MaChiDinh),
    CONSTRAINT FK_KetQuaCLS_ChiDinh FOREIGN KEY (MaChiDinh)      REFERENCES CHIDINHCLS(MaChiDinh),
    CONSTRAINT FK_KetQuaCLS_KTV     FOREIGN KEY (MaKyThuatVien)  REFERENCES NHANVIEN(MaNhanVien),
    CONSTRAINT FK_KetQuaCLS_DonVi   FOREIGN KEY (MaDonViKQ)      REFERENCES DONVITINH(MaDonVi)
);
GO

-- File đính kèm kết quả (ảnh X-quang, siêu âm, MRI, ...)
CREATE TABLE FILEKQCLS (
    MaFile          INT             PRIMARY KEY IDENTITY(1,1),
    MaKetQua        INT             NOT NULL,
    TenFile         NVARCHAR(255)   NOT NULL,
    DuongDan        NVARCHAR(500)   NOT NULL,
    LoaiFile        NVARCHAR(50),              -- 'HINH_ANH','PDF','DICOM'
    KichThuoc       INT,                       -- bytes
    ThoiGianTai     DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_FileCLS_KetQua FOREIGN KEY (MaKetQua) REFERENCES KETQUACLS(MaKetQua)
);
GO

-- ================================================================
-- PHẦN 7: ĐƠN THUỐC
-- ================================================================

-- Danh mục thuốc
CREATE TABLE THUOC (
    MaThuoc         INT             PRIMARY KEY IDENTITY(1,1),
    MaThuocCode     NVARCHAR(30)    NOT NULL,   -- Mã nội bộ
    TenThuoc        NVARCHAR(200)   NOT NULL,
    TenHoatChat     NVARCHAR(200),              -- Tên hoạt chất / generic
    HangSanXuat     NVARCHAR(150),
    MaDonVi         SMALLINT        NOT NULL,   -- Đơn vị: viên, chai, ống,...
    DangBaoChe      NVARCHAR(100),              -- Viên nén, siro, tiêm,...
    HamLuong        NVARCHAR(100),              -- 500mg, 250ml,...
    GiaNhap         DECIMAL(15,2)   NOT NULL DEFAULT 0,
    GiaBan          DECIMAL(15,2)   NOT NULL DEFAULT 0,
    SoLuongTon      INT             NOT NULL DEFAULT 0,
    TonToiThieu     INT             NOT NULL DEFAULT 0,  -- Định mức cảnh báo hết hàng
    HanSuDung       DATE,
    HuongDanBaoQuan NVARCHAR(500),
    DangHoatDong    BIT             NOT NULL DEFAULT 1,
    CONSTRAINT UQ_Thuoc_MaCode  UNIQUE (MaThuocCode),
    CONSTRAINT FK_Thuoc_DonVi   FOREIGN KEY (MaDonVi) REFERENCES DONVITINH(MaDonVi)
);
GO

-- Đơn thuốc (phần đầu)
CREATE TABLE DONTHUOC (
    MaDonThuoc      INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieuKham     INT             NOT NULL,
    MaBacSi         INT             NOT NULL,
    ChanDoanTrenDon NVARCHAR(MAX),
    LuuYDacBiet     NVARCHAR(MAX),
    TrangThai       NVARCHAR(20)    NOT NULL DEFAULT 'NHAP'
                    CHECK (TrangThai IN ('NHAP','DA_KY','DA_CAP_PHAT','DA_HUY')),
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    NgayKy          DATETIME2,
    CONSTRAINT UQ_DonThuoc_PhieuKham UNIQUE (MaPhieuKham),
    CONSTRAINT FK_DonThuoc_PhieuKham FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_DonThuoc_BacSi     FOREIGN KEY (MaBacSi)     REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- Chi tiết đơn thuốc (từng dòng thuốc)
CREATE TABLE CHITIETDONTHUOC (
    MaChiTiet       INT             PRIMARY KEY IDENTITY(1,1),
    MaDonThuoc      INT             NOT NULL,
    MaThuoc         INT             NOT NULL,
    SoLuong         INT             NOT NULL,
    HuongDanSuDung  NVARCHAR(MAX)   NOT NULL,  -- Liều dùng / cách dùng
    SoNgaySuDung    SMALLINT,
    DonGiaTaiThoiDiem DECIMAL(15,2) NOT NULL,  -- Giá lúc kê đơn (lưu lịch sử)
    CONSTRAINT FK_CTDonThuoc_DonThuoc FOREIGN KEY (MaDonThuoc) REFERENCES DONTHUOC(MaDonThuoc),
    CONSTRAINT FK_CTDonThuoc_Thuoc    FOREIGN KEY (MaThuoc)    REFERENCES THUOC(MaThuoc)
);
GO

-- ================================================================
-- PHẦN 8: KHO DƯỢC
-- ================================================================

-- Phiếu nhập kho thuốc
CREATE TABLE PHIEUNHAPKHO (
    MaPhieuNhap     INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieu         NVARCHAR(30)    NOT NULL,
    NhaCungCap      NVARCHAR(150),
    MaDuocSi        INT             NOT NULL,
    NgayNhap        DATE            NOT NULL,
    TongTienNhap    DECIMAL(15,2)   NOT NULL DEFAULT 0,
    GhiChu          NVARCHAR(MAX),
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT UQ_PhieuNhapKho_MaPhieu UNIQUE (MaPhieu),
    CONSTRAINT FK_PhieuNhapKho_DuocSi  FOREIGN KEY (MaDuocSi) REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- Chi tiết phiếu nhập kho
CREATE TABLE CHITIETNHAPKHO (
    MaChiTiet       INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieuNhap     INT             NOT NULL,
    MaThuoc         INT             NOT NULL,
    SoLuongNhap     INT             NOT NULL,
    DonGiaNhap      DECIMAL(15,2)   NOT NULL,
    HanSuDung       DATE,
    SoLoThuoc       NVARCHAR(50),              -- Số lô sản xuất
    CONSTRAINT FK_CTNhapKho_PhieuNhap FOREIGN KEY (MaPhieuNhap) REFERENCES PHIEUNHAPKHO(MaPhieuNhap),
    CONSTRAINT FK_CTNhapKho_Thuoc     FOREIGN KEY (MaThuoc)     REFERENCES THUOC(MaThuoc)
);
GO

-- Phiếu xuất kho — cấp phát thuốc theo đơn
CREATE TABLE PHIEUCAPPHATTHUOC (
    MaPhieuCap      INT             PRIMARY KEY IDENTITY(1,1),
    MaDonThuoc      INT             NOT NULL,
    MaDuocSi        INT             NOT NULL,
    ThoiGianCap     DATETIME2       NOT NULL DEFAULT GETDATE(),
    GhiChu          NVARCHAR(MAX),
    CONSTRAINT UQ_PhieuCap_DonThuoc UNIQUE (MaDonThuoc),
    CONSTRAINT FK_PhieuCap_DonThuoc FOREIGN KEY (MaDonThuoc) REFERENCES DONTHUOC(MaDonThuoc),
    CONSTRAINT FK_PhieuCap_DuocSi   FOREIGN KEY (MaDuocSi)   REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- Phiếu hủy / trả thuốc hết hạn
CREATE TABLE PHIEUHUY (
    MaPhieuHuy      INT             PRIMARY KEY IDENTITY(1,1),
    MaThuoc         INT             NOT NULL,
    SoLuong         INT             NOT NULL,
    LyDoHuy         NVARCHAR(500),             -- 'HET_HAN','HU_HONG','THU_HOI'
    MaDuocSi        INT             NOT NULL,
    ThoiGianHuy     DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_PhieuHuy_Thuoc   FOREIGN KEY (MaThuoc)   REFERENCES THUOC(MaThuoc),
    CONSTRAINT FK_PhieuHuy_DuocSi  FOREIGN KEY (MaDuocSi)  REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- ================================================================
-- PHẦN 9: DỊCH VỤ KHÁM
-- ================================================================

-- Danh mục dịch vụ khám (Khám nội, Khám ngoại, Siêu âm thai, ...)
CREATE TABLE DICHVUKHAM (
    MaDichVu        INT             PRIMARY KEY IDENTITY(1,1),
    MaChuyenKhoa    SMALLINT,
    TenDichVu       NVARCHAR(150)   NOT NULL,
    DonGia          DECIMAL(15,2)   NOT NULL DEFAULT 0,
    MoTa            NVARCHAR(255),
    DangHoatDong    BIT             NOT NULL DEFAULT 1,
    CONSTRAINT UQ_DichVuKham_Ten  UNIQUE (TenDichVu),
    CONSTRAINT FK_DVKham_CK       FOREIGN KEY (MaChuyenKhoa) REFERENCES CHUYENKHOA(MaChuyenKhoa)
);
GO

-- Dịch vụ sử dụng trong phiếu khám
CREATE TABLE PHIEUKHAM_DICHVU (
    MaPhieuKhamDV   INT             PRIMARY KEY IDENTITY(1,1),
    MaPhieuKham     INT             NOT NULL,
    MaDichVu        INT             NOT NULL,
    SoLuong         SMALLINT        NOT NULL DEFAULT 1,
    DonGiaTaiThoiDiem DECIMAL(15,2) NOT NULL,
    CONSTRAINT FK_PKDV_PhieuKham  FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_PKDV_DichVu     FOREIGN KEY (MaDichVu)    REFERENCES DICHVUKHAM(MaDichVu)
);
GO

-- ================================================================
-- PHẦN 10: HÓA ĐƠN & THANH TOÁN
-- ================================================================

-- Hóa đơn
CREATE TABLE HOADON (
    MaHoaDon        INT             PRIMARY KEY IDENTITY(1,1),
    SoHoaDon        NVARCHAR(30)    NOT NULL,   -- HD00001,...
    MaPhieuKham     INT             NOT NULL,
    NguoiTao        INT             NOT NULL,   -- Lễ tân tạo hóa đơn
    TamTinh         DECIMAL(15,2)   NOT NULL DEFAULT 0,
    GiamGia         DECIMAL(15,2)   NOT NULL DEFAULT 0,
    TongThanhToan   DECIMAL(15,2)   NOT NULL DEFAULT 0,
    TrangThai       NVARCHAR(20)    NOT NULL DEFAULT 'CHO_THANH_TOAN'
                    CHECK (TrangThai IN ('CHO_THANH_TOAN','DA_THANH_TOAN','DA_HUY')),
    NgayTao         DATETIME2       NOT NULL DEFAULT GETDATE(),
    NgayThanhToan   DATETIME2,
    CONSTRAINT UQ_HoaDon_SoHD       UNIQUE (SoHoaDon),
    CONSTRAINT UQ_HoaDon_PhieuKham  UNIQUE (MaPhieuKham),
    CONSTRAINT FK_HoaDon_PhieuKham  FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_HoaDon_NguoiTao   FOREIGN KEY (NguoiTao)    REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- Chi tiết hóa đơn (liệt kê từng khoản thu)
CREATE TABLE CHITIETHOADON (
    MaChiTietHD     INT             PRIMARY KEY IDENTITY(1,1),
    MaHoaDon        INT             NOT NULL,
    LoaiKhoan       NVARCHAR(20)    NOT NULL
                    CHECK (LoaiKhoan IN ('DICH_VU','CAN_LAM_SANG','THUOC')),
    TenKhoan        NVARCHAR(200)   NOT NULL,   -- Lưu tên tại thời điểm xuất HĐ
    SoLuong         INT             NOT NULL,
    DonGia          DECIMAL(15,2)   NOT NULL,
    ThanhTien       DECIMAL(15,2)   NOT NULL,
    MaBanGhiGoc     INT,            -- MaPhieuKhamDV / MaChiDinh / MaChiTietDonThuoc
    CONSTRAINT FK_CTHoaDon_HoaDon FOREIGN KEY (MaHoaDon) REFERENCES HOADON(MaHoaDon)
);
GO

-- Giao dịch thanh toán (1 hóa đơn hỗ trợ nhiều phương thức)
CREATE TABLE THANHTOAN (
    MaThanhToan     INT             PRIMARY KEY IDENTITY(1,1),
    MaHoaDon        INT             NOT NULL,
    MaPhuongThuc    SMALLINT        NOT NULL,
    SoTienThanhToan DECIMAL(15,2)   NOT NULL,
    MaGiaoDich      NVARCHAR(100),              -- Mã giao dịch ngân hàng / QR
    NguoiThuTien    INT             NOT NULL,   -- MaTaiKhoan lễ tân
    ThoiGianTT      DATETIME2       NOT NULL DEFAULT GETDATE(),
    GhiChu          NVARCHAR(MAX),
    CONSTRAINT FK_ThanhToan_HoaDon      FOREIGN KEY (MaHoaDon)      REFERENCES HOADON(MaHoaDon),
    CONSTRAINT FK_ThanhToan_PhuongThuc  FOREIGN KEY (MaPhuongThuc)  REFERENCES PHUONGTHUCTT(MaPhuongThuc),
    CONSTRAINT FK_ThanhToan_LeTan       FOREIGN KEY (NguoiThuTien)  REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- ================================================================
-- PHẦN 11: ĐÁNH GIÁ DỊCH VỤ
-- ================================================================

CREATE TABLE DANHGIADICHVU (
    MaDanhGia       INT             PRIMARY KEY IDENTITY(1,1),
    MaBenhNhan      INT             NOT NULL,
    MaPhieuKham     INT             NOT NULL,
    MaBacSi         INT,                        -- NULL nếu đánh giá chung phòng khám
    DiemSao         TINYINT         NOT NULL,
    NhanXet         NVARCHAR(MAX),
    DaDuyet         BIT             NOT NULL DEFAULT 0,  -- Quản lý duyệt trước khi hiển thị
    NgayGui         DATETIME2       NOT NULL DEFAULT GETDATE(),
    CONSTRAINT UQ_DanhGia_BN_Phieu  UNIQUE (MaBenhNhan, MaPhieuKham),
    CONSTRAINT CK_DanhGia_DiemSao   CHECK (DiemSao BETWEEN 1 AND 5),
    CONSTRAINT FK_DanhGia_BenhNhan  FOREIGN KEY (MaBenhNhan)  REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_DanhGia_Phieu     FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_DanhGia_BacSi     FOREIGN KEY (MaBacSi)     REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- ================================================================
-- PHẦN 12: CẤU HÌNH HỆ THỐNG
-- ================================================================

-- Thông tin cấu hình phòng khám (key-value)
CREATE TABLE CAUHINHHETHONG (
    KhoacCauHinh    NVARCHAR(100)   PRIMARY KEY,
    GiaTri          NVARCHAR(MAX),
    NguoiCapNhat    INT,
    ThoiGianCapNhat DATETIME2,
    CONSTRAINT FK_CauHinh_NguoiCapNhat FOREIGN KEY (NguoiCapNhat) REFERENCES TAIKHOAN(MaTaiKhoan)
);
GO

-- Lịch làm việc / ca trực bác sĩ
CREATE TABLE LICHLAMVIEC (
    MaLich          INT             PRIMARY KEY IDENTITY(1,1),
    MaBacSi         INT             NOT NULL,
    NgayLam         DATE            NOT NULL,
    Ca              NVARCHAR(20)    NOT NULL
                    CHECK (Ca IN ('SANG','CHIEU','TOI')),
    SoBenhNhanToiDa SMALLINT        NOT NULL DEFAULT 20,
    GhiChu          NVARCHAR(MAX),
    CONSTRAINT UQ_LichLamViec UNIQUE (MaBacSi, NgayLam, Ca),
    CONSTRAINT FK_LichLamViec_BacSi FOREIGN KEY (MaBacSi) REFERENCES NHANVIEN(MaNhanVien)
);
GO

-- ================================================================
-- PHẦN 13: VIEW HỖ TRỢ BÁO CÁO
-- ================================================================

-- View tổng hợp doanh thu các hóa đơn đã thanh toán
CREATE VIEW V_DOANHTHU AS
SELECT
    hd.MaHoaDon,
    hd.SoHoaDon,
    pk.NgayKham,
    bn.MaBN                 AS MaBenhNhan,
    bn.HoTen                AS TenBenhNhan,
    nv.HoTen                AS TenBacSi,
    ck.TenChuyenKhoa,
    hd.TongThanhToan,
    hd.NgayThanhToan,
    pt.TenPhuongThuc        AS PhuongThucTT
FROM HOADON hd
JOIN PHIEUKHAM          pk  ON pk.MaPhieuKham   = hd.MaPhieuKham
JOIN BENHNHAN           bn  ON bn.MaBenhNhan     = pk.MaBenhNhan
JOIN NHANVIEN           nv  ON nv.MaNhanVien     = pk.MaBacSi
JOIN CHUYENKHOA         ck  ON ck.MaChuyenKhoa  = pk.MaChuyenKhoa
JOIN THANHTOAN          tt  ON tt.MaHoaDon       = hd.MaHoaDon
JOIN PHUONGTHUCTT       pt  ON pt.MaPhuongThuc   = tt.MaPhuongThuc
WHERE hd.TrangThai = N'DA_THANH_TOAN';
GO

-- View tồn kho thuốc (cảnh báo hết hàng & hết hạn)
CREATE VIEW V_TONKHOTHUOC AS
SELECT
    t.MaThuoc,
    t.MaThuocCode,
    t.TenThuoc,
    t.TenHoatChat,
    t.HamLuong,
    dv.TenDonVi,
    t.SoLuongTon,
    t.TonToiThieu,
    CASE WHEN t.SoLuongTon <= t.TonToiThieu THEN 1 ELSE 0 END  AS CanhBaoHetHang,
    t.HanSuDung,
    CASE WHEN t.HanSuDung < CAST(GETDATE() AS DATE) THEN 1 ELSE 0 END AS DaHetHan,
    t.GiaBan
FROM THUOC t
JOIN DONVITINH dv ON dv.MaDonVi = t.MaDonVi
WHERE t.DangHoatDong = 1;
GO

-- View lịch hẹn đang chờ / đã xác nhận
CREATE VIEW V_LICHHEN_HIEULUC AS
SELECT
    lh.MaLichHen,
    bn.MaBN                 AS MaBenhNhan,
    bn.HoTen                AS TenBenhNhan,
    bn.SoDienThoai,
    nv.HoTen                AS TenBacSi,
    ck.TenChuyenKhoa,
    lh.NgayHen,
    lh.GioHen,
    tt.TenTrangThai         AS TrangThai
FROM LICHHEN lh
JOIN BENHNHAN           bn  ON bn.MaBenhNhan    = lh.MaBenhNhan
JOIN NHANVIEN           nv  ON nv.MaNhanVien    = lh.MaBacSi
JOIN CHUYENKHOA         ck  ON ck.MaChuyenKhoa = lh.MaChuyenKhoa
JOIN TRANGTHAILICHCHHEN tt  ON tt.MaTrangThai   = lh.MaTrangThai
WHERE tt.TenTrangThai IN (N'CHO_XAC_NHAN', N'DA_XAC_NHAN');
GO

-- ================================================================
-- PHẦN 14: INDEX TỐI ƯU HIỆU NĂNG
-- ================================================================

CREATE INDEX IDX_BenhNhan_SDT          ON BENHNHAN(SoDienThoai);
CREATE INDEX IDX_BenhNhan_BHYT         ON BENHNHAN(SoBHYT);
CREATE INDEX IDX_BenhNhan_HoTen        ON BENHNHAN(HoTen);
CREATE INDEX IDX_PhieuKham_BenhNhan    ON PHIEUKHAM(MaBenhNhan);
CREATE INDEX IDX_PhieuKham_BacSi       ON PHIEUKHAM(MaBacSi);
CREATE INDEX IDX_PhieuKham_NgayKham    ON PHIEUKHAM(NgayKham);
CREATE INDEX IDX_PhieuKham_TrangThai   ON PHIEUKHAM(MaTrangThai);
CREATE INDEX IDX_LichHen_NgayHen       ON LICHHEN(NgayHen);
CREATE INDEX IDX_LichHen_BacSi         ON LICHHEN(MaBacSi);
CREATE INDEX IDX_ChiDinhCLS_PhieuKham  ON CHIDINHCLS(MaPhieuKham);
CREATE INDEX IDX_DonThuoc_PhieuKham    ON DONTHUOC(MaPhieuKham);
CREATE INDEX IDX_HoaDon_TrangThai      ON HOADON(TrangThai);
CREATE INDEX IDX_HoaDon_NgayThanhToan  ON HOADON(NgayThanhToan);
CREATE INDEX IDX_ThanhToan_HoaDon      ON THANHTOAN(MaHoaDon);
CREATE INDEX IDX_Thuoc_TenThuoc        ON THUOC(TenThuoc);
CREATE INDEX IDX_Thuoc_HanSuDung       ON THUOC(HanSuDung);
CREATE INDEX IDX_NhatKy_TaiKhoan       ON NHATKYHOATDONG(MaTaiKhoan);
CREATE INDEX IDX_NhatKy_ThoiGian       ON NHATKYHOATDONG(ThoiGian);
GO

-- ================================================================
-- DỮ LIỆU MẪU KHỞI TẠO
-- ================================================================

INSERT INTO VAITRO (TenVaiTro, MoTa) VALUES
(N'LE_TAN',         N'Nhân viên lễ tân'),
(N'DIEU_DUONG',     N'Điều dưỡng'),
(N'BAC_SI',         N'Bác sĩ'),
(N'KY_THUAT_VIEN',  N'Chuyên viên xét nghiệm'),
(N'DUOC_SI',        N'Dược sĩ'),
(N'QUAN_LY',        N'Quản lý phòng khám'),
(N'BENH_NHAN',      N'Bệnh nhân');

INSERT INTO TRANGTHAIPHIEUKHAM (TenTrangThai) VALUES
(N'CHO_KHAM'), (N'DA_SO_KHAM'), (N'DANG_KHAM'),
(N'CHO_CLS'), (N'HOAN_THANH'), (N'DA_HUY');

INSERT INTO TRANGTHAILICHCHHEN (TenTrangThai) VALUES
(N'CHO_XAC_NHAN'), (N'DA_XAC_NHAN'), (N'DOI_LICH'),
(N'DA_HUY'), (N'HOAN_THANH');

INSERT INTO PHUONGTHUCTT (TenPhuongThuc) VALUES
(N'TIEN_MAT'), (N'CHUYEN_KHOAN'), (N'QR_CODE');

INSERT INTO DONVITINH (TenDonVi) VALUES
(N'Viên'), (N'Chai'), (N'Ống'), (N'Gói'), (N'Hộp'),
(N'mg'), (N'ml'), (N'Lần'), (N'Tuýp');

INSERT INTO LOAICLSN (TenLoaiCLS, MoTa) VALUES
(N'Xét nghiệm máu',         N'Công thức máu, sinh hóa,...'),
(N'Xét nghiệm nước tiểu',   N'Tổng phân tích nước tiểu'),
(N'X-quang',                N'Chụp X-quang các vùng'),
(N'Siêu âm',                N'Siêu âm ổ bụng, tim, thai,...'),
(N'Điện tim (ECG)',          N'Ghi điện tâm đồ'),
(N'MRI',                    N'Cộng hưởng từ'),
(N'Nội soi',                N'Nội soi dạ dày, đại tràng,...');

INSERT INTO CHUYENKHOA (TenChuyenKhoa) VALUES
(N'Nội tổng quát'), (N'Ngoại tổng quát'), (N'Nhi khoa'),
(N'Sản - Phụ khoa'), (N'Da liễu'), (N'Tai Mũi Họng'),
(N'Mắt'), (N'Răng Hàm Mặt'), (N'Tim mạch'), (N'Thần kinh');

INSERT INTO CAUHINHHETHONG (KhoacCauHinh, GiaTri) VALUES
(N'ten_phong_kham',     N'Phòng Khám Đa Khoa'),
(N'dia_chi',            N''),
(N'so_dien_thoai',      N''),
(N'logo_url',           N''),
(N'tien_to_hoa_don',    N'HD'),
(N'tien_to_phieu_kham', N'PK'),
(N'tien_to_benh_nhan',  N'BN');
GO