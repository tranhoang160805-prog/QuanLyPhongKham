-- ================================================================
-- HỆ THỐNG QUẢN LÝ PHÒNG KHÁM TƯ NHÂN
-- Chuẩn hóa 3NF | MySQL/MariaDB | Đặt tên tiếng Việt không dấu
-- ================================================================

-- Xóa database nếu đã tồn tại
DROP DATABASE IF EXISTS QuanLyPhongKham;

-- Tạo database với charset UTF-8
CREATE DATABASE QuanLyPhongKham
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE QuanLyPhongKham;

-- ================================================================
-- PHẦN 1: DANH MỤC DÙNG CHUNG
-- ================================================================

-- Vai trò người dùng trong hệ thống
CREATE TABLE VAITRO (
    MaVaiTro        SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenVaiTro       VARCHAR(50)     NOT NULL,
    -- 'LE_TAN','DIEU_DUONG','BAC_SI','KY_THUAT_VIEN','DUOC_SI','QUAN_LY','IT_ADMIN','BENH_NHAN'
    MoTa            VARCHAR(255),
    CONSTRAINT UQ_VaiTro_Ten UNIQUE (TenVaiTro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chuyên khoa (Nội, Ngoại, Da liễu, Nhi, ...)
CREATE TABLE CHUYENKHOA (
    MaChuyenKhoa    SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenChuyenKhoa   VARCHAR(100)    NOT NULL,
    MoTa            VARCHAR(255),
    CONSTRAINT UQ_ChuyenKhoa_Ten UNIQUE (TenChuyenKhoa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Đơn vị tính (viên, mg, ml, chai, gói, ...)
CREATE TABLE DONVITINH (
    MaDonVi         SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenDonVi        VARCHAR(50)     NOT NULL,
    CONSTRAINT UQ_DonViTinh_Ten UNIQUE (TenDonVi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Loại dịch vụ cận lâm sàng (Xét nghiệm, X-quang, Siêu âm, ...)
CREATE TABLE LOAICLSN (
    MaLoaiCLS       SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenLoaiCLS      VARCHAR(100)    NOT NULL,   -- CLS = Cận Lâm Sàng
    MoTa            VARCHAR(255),
    CONSTRAINT UQ_LoaiCLS_Ten UNIQUE (TenLoaiCLS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Phương thức thanh toán
CREATE TABLE PHUONGTHUCTT (
    MaPhuongThuc    SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenPhuongThuc   VARCHAR(50)     NOT NULL,   -- 'TIEN_MAT','CHUYEN_KHOAN','QR'
    CONSTRAINT UQ_PhuongThucTT_Ten UNIQUE (TenPhuongThuc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trạng thái phiếu khám
CREATE TABLE TRANGTHAIPHIEUKHAM (
    MaTrangThai     SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenTrangThai    VARCHAR(50)     NOT NULL,
    -- 'CHO_KHAM','DA_SO_KHAM','DANG_KHAM','CHO_CLS','HOAN_THANH','DA_HUY'
    CONSTRAINT UQ_TrangThaiPK_Ten UNIQUE (TenTrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trạng thái lịch hẹn
CREATE TABLE TRANGTHAILICHCHHEN (
    MaTrangThai     SMALLINT        PRIMARY KEY AUTO_INCREMENT,
    TenTrangThai    VARCHAR(50)     NOT NULL,
    -- 'CHO_XAC_NHAN','DA_XAC_NHAN','DOI_LICH','DA_HUY','HOAN_THANH'
    CONSTRAINT UQ_TrangThaiLH_Ten UNIQUE (TenTrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 2: TÀI KHOẢN & NHÂN SỰ
-- ================================================================

-- Tài khoản đăng nhập hệ thống (nội bộ + bệnh nhân online)
CREATE TABLE TAIKHOAN (
    MaTaiKhoan      INT             PRIMARY KEY AUTO_INCREMENT,
    TenDangNhap     VARCHAR(100)    NOT NULL,
    MatKhauHash     VARCHAR(255)    NOT NULL,
    SoDienThoai     VARCHAR(15),
    DangHoatDong    BOOLEAN         NOT NULL DEFAULT 1,
    SoLanSaiMK      TINYINT         NOT NULL DEFAULT 0,     -- Số lần nhập sai mật khẩu
    KhoaDen         DATETIME,                               -- NULL = không bị khóa
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    LanDangNhapCuoi DATETIME,
    CONSTRAINT UQ_TaiKhoan_TenDangNhap  UNIQUE (TenDangNhap),
    CONSTRAINT UQ_TaiKhoan_SDT          UNIQUE (SoDienThoai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Phân quyền: 1 tài khoản có thể có nhiều vai trò
CREATE TABLE TAIKHOAN_VAITRO (
    MaTaiKhoan      INT             NOT NULL,
    MaVaiTro        SMALLINT        NOT NULL,
    NgayPhanQuyen   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT PK_TaiKhoan_VaiTro PRIMARY KEY (MaTaiKhoan, MaVaiTro),
    CONSTRAINT FK_TKVT_TaiKhoan   FOREIGN KEY (MaTaiKhoan) REFERENCES TAIKHOAN(MaTaiKhoan),
    CONSTRAINT FK_TKVT_VaiTro     FOREIGN KEY (MaVaiTro)   REFERENCES VAITRO(MaVaiTro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nhân viên phòng khám (Bác sĩ, Lễ tân, Điều dưỡng, Dược sĩ, IT, Quản lý)
CREATE TABLE NHANVIEN (
    MaNhanVien      INT             PRIMARY KEY AUTO_INCREMENT,
    MaTaiKhoan      INT             NOT NULL,
    HoTen           VARCHAR(100)    NOT NULL,
    NgaySinh        DATE,
    GioiTinh        CHAR(1)         CHECK (GioiTinh IN ('M','F','O')),  -- M/F/O(ther)
    CCCD            VARCHAR(20),
    SoDienThoai     VARCHAR(15),
    Email           VARCHAR(100),
    DiaChi          VARCHAR(500),
    MaChuyenKhoa    SMALLINT,                   -- Chỉ điền cho bác sĩ
    BangCap         VARCHAR(150),               -- Bằng cấp / học vị
    SoChungChi      VARCHAR(50),                -- Số chứng chỉ hành nghề
    NgayVaoLam      DATE,
    DangHoatDong    BOOLEAN         NOT NULL DEFAULT 1,
    CONSTRAINT UQ_NhanVien_TaiKhoan   UNIQUE (MaTaiKhoan),
    CONSTRAINT UQ_NhanVien_CCCD       UNIQUE (CCCD),
    CONSTRAINT FK_NhanVien_TaiKhoan   FOREIGN KEY (MaTaiKhoan)    REFERENCES TAIKHOAN(MaTaiKhoan),
    CONSTRAINT FK_NhanVien_ChuyenKhoa FOREIGN KEY (MaChuyenKhoa)  REFERENCES CHUYENKHOA(MaChuyenKhoa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nhật ký hoạt động (audit log) — theo yêu cầu chủ phòng khám
CREATE TABLE NHATKYHOATDONG (
    MaNhatKy        BIGINT          PRIMARY KEY AUTO_INCREMENT,
    MaTaiKhoan      INT             NOT NULL,
    HanhDong        VARCHAR(100)    NOT NULL,   -- 'DANG_NHAP','SUA_BENH_NHAN','XOA_THUOC',...
    TenBang         VARCHAR(100),               -- Tên bảng bị tác động
    MaBanGhi        INT,                        -- ID bản ghi bị tác động
    GiaTriCu        TEXT,                       -- JSON giá trị cũ
    GiaTriMoi       TEXT,                       -- JSON giá trị mới
    DiaChiIP        VARCHAR(45),
    ThoiGian        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_NhatKy_TaiKhoan FOREIGN KEY (MaTaiKhoan) REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 3: BỆNH NHÂN
-- ================================================================

CREATE TABLE BENHNHAN (
    MaBenhNhan      INT             PRIMARY KEY AUTO_INCREMENT,
    MaTaiKhoan      INT,                        -- NULL nếu lễ tân tạo trực tiếp (khám vãng lai)
    MaBN            VARCHAR(20)     NOT NULL,   -- Mã tự sinh: BN00001, BN00002,...
    HoTen           VARCHAR(100)    NOT NULL,
    NgaySinh        DATE,
    GioiTinh        CHAR(1)         CHECK (GioiTinh IN ('M','F','O')),
    SoDienThoai     VARCHAR(15),
    Email           VARCHAR(100),
    DiaChi          VARCHAR(500),
    SoBHYT          VARCHAR(30),                -- Số bảo hiểm y tế
    NhomMau         VARCHAR(5),                 -- A, B, AB, O (+/-)
    DiUng           TEXT,                       -- Dị ứng thuốc / thức ăn
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat     DATETIME,
    CONSTRAINT UQ_BenhNhan_MaBN     UNIQUE (MaBN),
    CONSTRAINT UQ_BenhNhan_SoBHYT   UNIQUE (SoBHYT),
    CONSTRAINT UQ_BenhNhan_TaiKhoan UNIQUE (MaTaiKhoan),
    CONSTRAINT FK_BenhNhan_TaiKhoan FOREIGN KEY (MaTaiKhoan) REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 4: LỊCH HẸN & PHIẾU KHÁM
-- ================================================================

-- Lịch hẹn (bệnh nhân đặt online hoặc lễ tân tạo)
CREATE TABLE LICHHEN (
    MaLichHen       INT             PRIMARY KEY AUTO_INCREMENT,
    MaBenhNhan      INT             NOT NULL,
    MaBacSi         INT             NOT NULL,   -- MaNhanVien của bác sĩ
    MaChuyenKhoa    SMALLINT        NOT NULL,
    NgayHen         DATE            NOT NULL,
    GioHen          TIME            NOT NULL,
    MaTrangThai     SMALLINT        NOT NULL,
    GhiChu          TEXT,
    NguoiTao        INT             NOT NULL,   -- MaTaiKhoan người tạo lịch
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat     DATETIME,
    CONSTRAINT FK_LichHen_BenhNhan   FOREIGN KEY (MaBenhNhan)    REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_LichHen_BacSi      FOREIGN KEY (MaBacSi)       REFERENCES NHANVIEN(MaNhanVien),
    CONSTRAINT FK_LichHen_ChuyenKhoa FOREIGN KEY (MaChuyenKhoa) REFERENCES CHUYENKHOA(MaChuyenKhoa),
    CONSTRAINT FK_LichHen_TrangThai  FOREIGN KEY (MaTrangThai)   REFERENCES TRANGTHAILICHCHHEN(MaTrangThai),
    CONSTRAINT FK_LichHen_NguoiTao   FOREIGN KEY (NguoiTao)      REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Phiếu khám bệnh
CREATE TABLE PHIEUKHAM (
    MaPhieuKham     INT             PRIMARY KEY AUTO_INCREMENT,
    MaPhieuKhamCode VARCHAR(20)     NOT NULL,   -- PK00001, PK00002,...
    MaBenhNhan      INT             NOT NULL,
    MaBacSi         INT             NOT NULL,
    MaChuyenKhoa    SMALLINT        NOT NULL,
    NgayKham        DATE            NOT NULL,
    GioKham         TIME            NOT NULL,
    LyDoKham        TEXT,
    TrieuChung      TEXT,
    TienSuBenh      TEXT,
    ThongSoSinhTon  TEXT,           -- JSON: nhiệt độ, huyết áp, nhịp tim,...
    ChanDoan        TEXT,
    GhiChu          TEXT,
    MaTrangThai     SMALLINT        NOT NULL,
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat     DATETIME,
    CONSTRAINT UQ_PhieuKham_MaCode    UNIQUE (MaPhieuKhamCode),
    CONSTRAINT FK_PhieuKham_BenhNhan  FOREIGN KEY (MaBenhNhan)    REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_PhieuKham_BacSi     FOREIGN KEY (MaBacSi)       REFERENCES NHANVIEN(MaNhanVien),
    CONSTRAINT FK_PhieuKham_ChuyenKhoa FOREIGN KEY (MaChuyenKhoa) REFERENCES CHUYENKHOA(MaChuyenKhoa),
    CONSTRAINT FK_PhieuKham_TrangThai FOREIGN KEY (MaTrangThai)   REFERENCES TRANGTHAIPHIEUKHAM(MaTrangThai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 5: CHỈ ĐỊNH CẬN LÂM SÀNG
-- ================================================================

-- Chỉ định dịch vụ cận lâm sàng (xét nghiệm, X-quang,...)
CREATE TABLE CHIDINHCLS (
    MaChiDinh       INT             PRIMARY KEY AUTO_INCREMENT,
    MaPhieuKham     INT             NOT NULL,
    MaLoaiCLS       SMALLINT        NOT NULL,
    TenDichVu       VARCHAR(150)    NOT NULL,
    MoTaChiDinh     TEXT,
    DonGia          DECIMAL(15,2)   NOT NULL,
    SoLuong         SMALLINT        NOT NULL DEFAULT 1,
    ThanhTien       DECIMAL(15,2)   NOT NULL,
    TrangThai       VARCHAR(50)     NOT NULL DEFAULT 'CHO_THUC_HIEN',
    -- 'CHO_THUC_HIEN','DANG_THUC_HIEN','HOAN_THANH','DA_HUY'
    NgayChiDinh     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_ChiDinhCLS_PhieuKham FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_ChiDinhCLS_LoaiCLS   FOREIGN KEY (MaLoaiCLS)   REFERENCES LOAICLSN(MaLoaiCLS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kết quả cận lâm sàng
CREATE TABLE KETQUACLS (
    MaKetQua        INT             PRIMARY KEY AUTO_INCREMENT,
    MaChiDinh       INT             NOT NULL,
    KetQuaText      TEXT,
    KetLuan         TEXT,
    FileKetQua      VARCHAR(500),   -- Đường dẫn file ảnh / PDF
    MaNVThucHien    INT,            -- MaNhanVien kỹ thuật viên
    NgayThucHien    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT UQ_KetQuaCLS_ChiDinh UNIQUE (MaChiDinh),
    CONSTRAINT FK_KetQuaCLS_ChiDinh FOREIGN KEY (MaChiDinh)     REFERENCES CHIDINHCLS(MaChiDinh),
    CONSTRAINT FK_KetQuaCLS_NVThucHien FOREIGN KEY (MaNVThucHien) REFERENCES NHANVIEN(MaNhanVien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 6: THUỐC & KHO
-- ================================================================

-- Kho thuốc
CREATE TABLE THUOC (
    MaThuoc         INT             PRIMARY KEY AUTO_INCREMENT,
    MaThuocCode     VARCHAR(20)     NOT NULL,   -- T00001, T00002,...
    TenThuoc        VARCHAR(150)    NOT NULL,
    TenHoatChat     VARCHAR(200),
    HamLuong        VARCHAR(100),               -- 500mg, 10ml,...
    MaDonVi         SMALLINT        NOT NULL,
    DangBaoChe      VARCHAR(100),               -- Viên nén, viên nang,...
    QuyCach         VARCHAR(100),               -- Hộp 100 viên,...
    NhaSanXuat      VARCHAR(200),
    NuocSanXuat     VARCHAR(100),
    SoDangKy        VARCHAR(50),
    HanSuDung       DATE,
    SoLuongTon      INT             NOT NULL DEFAULT 0,
    TonToiThieu     INT             NOT NULL DEFAULT 10,
    GiaNhap         DECIMAL(15,2)   NOT NULL,
    GiaBan          DECIMAL(15,2)   NOT NULL,
    HuongDanSuDung  TEXT,
    DangHoatDong    BOOLEAN         NOT NULL DEFAULT 1,
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat     DATETIME,
    CONSTRAINT UQ_Thuoc_MaCode UNIQUE (MaThuocCode),
    CONSTRAINT FK_Thuoc_DonVi  FOREIGN KEY (MaDonVi) REFERENCES DONVITINH(MaDonVi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nhập kho thuốc
CREATE TABLE PHIEUNHAP (
    MaPhieuNhap     INT             PRIMARY KEY AUTO_INCREMENT,
    SoPhieuNhap     VARCHAR(20)     NOT NULL,   -- PN00001, PN00002,...
    NhaCungCap      VARCHAR(200)    NOT NULL,
    NgayNhap        DATE            NOT NULL,
    NguoiNhap       INT             NOT NULL,   -- MaNhanVien
    TongTien        DECIMAL(15,2)   NOT NULL,
    GhiChu          TEXT,
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT UQ_PhieuNhap_So UNIQUE (SoPhieuNhap),
    CONSTRAINT FK_PhieuNhap_NguoiNhap FOREIGN KEY (NguoiNhap) REFERENCES NHANVIEN(MaNhanVien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chi tiết phiếu nhập
CREATE TABLE CHITIETPHIEUNHAP (
    MaChiTiet       INT             PRIMARY KEY AUTO_INCREMENT,
    MaPhieuNhap     INT             NOT NULL,
    MaThuoc         INT             NOT NULL,
    SoLuong         INT             NOT NULL,
    DonGia          DECIMAL(15,2)   NOT NULL,
    ThanhTien       DECIMAL(15,2)   NOT NULL,
    SoLo            VARCHAR(50),
    HanSuDung       DATE,
    CONSTRAINT FK_CTPN_PhieuNhap FOREIGN KEY (MaPhieuNhap) REFERENCES PHIEUNHAP(MaPhieuNhap),
    CONSTRAINT FK_CTPN_Thuoc     FOREIGN KEY (MaThuoc)     REFERENCES THUOC(MaThuoc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Xuất kho thuốc (bán cho bệnh nhân)
CREATE TABLE PHIEUXUAT (
    MaPhieuXuat     INT             PRIMARY KEY AUTO_INCREMENT,
    SoPhieuXuat     VARCHAR(20)     NOT NULL,   -- PX00001, PX00002,...
    MaBenhNhan      INT             NOT NULL,
    NgayXuat        DATE            NOT NULL,
    NguoiXuat       INT             NOT NULL,   -- MaNhanVien dược sĩ
    TongTien        DECIMAL(15,2)   NOT NULL,
    GhiChu          TEXT,
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT UQ_PhieuXuat_So UNIQUE (SoPhieuXuat),
    CONSTRAINT FK_PhieuXuat_BenhNhan  FOREIGN KEY (MaBenhNhan) REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_PhieuXuat_NguoiXuat FOREIGN KEY (NguoiXuat)  REFERENCES NHANVIEN(MaNhanVien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chi tiết phiếu xuất
CREATE TABLE CHITIETPHIEUXUAT (
    MaChiTiet       INT             PRIMARY KEY AUTO_INCREMENT,
    MaPhieuXuat     INT             NOT NULL,
    MaThuoc         INT             NOT NULL,
    SoLuong         INT             NOT NULL,
    DonGia          DECIMAL(15,2)   NOT NULL,
    ThanhTien       DECIMAL(15,2)   NOT NULL,
    CONSTRAINT FK_CTPX_PhieuXuat FOREIGN KEY (MaPhieuXuat) REFERENCES PHIEUXUAT(MaPhieuXuat),
    CONSTRAINT FK_CTPX_Thuoc     FOREIGN KEY (MaThuoc)     REFERENCES THUOC(MaThuoc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 7: ĐỖN THUỐC
-- ================================================================

-- Đơn thuốc (kê toa)
CREATE TABLE DONTHUOC (
    MaDonThuoc      INT             PRIMARY KEY AUTO_INCREMENT,
    MaPhieuKham     INT             NOT NULL,
    NgayKeToa       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    LoiDan          TEXT,           -- Lời dặn chung của bác sĩ
    CONSTRAINT UQ_DonThuoc_PhieuKham UNIQUE (MaPhieuKham),
    CONSTRAINT FK_DonThuoc_PhieuKham FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chi tiết đơn thuốc
CREATE TABLE CHITIETDONTHUOC (
    MaChiTiet       INT             PRIMARY KEY AUTO_INCREMENT,
    MaDonThuoc      INT             NOT NULL,
    MaThuoc         INT             NOT NULL,
    SoLuong         INT             NOT NULL,
    DonGia          DECIMAL(15,2)   NOT NULL,
    ThanhTien       DECIMAL(15,2)   NOT NULL,
    CachDung        TEXT,           -- 1 viên x 3 lần/ngày sau ăn
    CONSTRAINT FK_CTDT_DonThuoc FOREIGN KEY (MaDonThuoc) REFERENCES DONTHUOC(MaDonThuoc),
    CONSTRAINT FK_CTDT_Thuoc    FOREIGN KEY (MaThuoc)    REFERENCES THUOC(MaThuoc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 8: HÓA ĐƠN & THANH TOÁN
-- ================================================================

-- Hóa đơn thanh toán
CREATE TABLE HOADON (
    MaHoaDon        INT             PRIMARY KEY AUTO_INCREMENT,
    SoHoaDon        VARCHAR(20)     NOT NULL,   -- HD00001, HD00002,...
    MaPhieuKham     INT             NOT NULL,
    TongTienKham    DECIMAL(15,2)   NOT NULL,
    TongTienCLS     DECIMAL(15,2)   NOT NULL DEFAULT 0,
    TongTienThuoc   DECIMAL(15,2)   NOT NULL DEFAULT 0,
    TongCong        DECIMAL(15,2)   NOT NULL,
    GiamGia         DECIMAL(15,2)   NOT NULL DEFAULT 0,
    TongThanhToan   DECIMAL(15,2)   NOT NULL,
    TrangThai       VARCHAR(50)     NOT NULL DEFAULT 'CHO_THANH_TOAN',
    -- 'CHO_THANH_TOAN','DA_THANH_TOAN','DA_HUY'
    NgayTao         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayThanhToan   DATETIME,
    CONSTRAINT UQ_HoaDon_So       UNIQUE (SoHoaDon),
    CONSTRAINT UQ_HoaDon_PhieuKham UNIQUE (MaPhieuKham),
    CONSTRAINT FK_HoaDon_PhieuKham FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chi tiết thanh toán (có thể thanh toán nhiều lần, nhiều phương thức)
CREATE TABLE THANHTOAN (
    MaThanhToan     INT             PRIMARY KEY AUTO_INCREMENT,
    MaHoaDon        INT             NOT NULL,
    MaPhuongThuc    SMALLINT        NOT NULL,
    SoTien          DECIMAL(15,2)   NOT NULL,
    NguoiThu        INT             NOT NULL,   -- MaNhanVien thu ngân
    NgayThanhToan   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    GhiChu          TEXT,
    CONSTRAINT FK_ThanhToan_HoaDon      FOREIGN KEY (MaHoaDon)      REFERENCES HOADON(MaHoaDon),
    CONSTRAINT FK_ThanhToan_PhuongThuc  FOREIGN KEY (MaPhuongThuc)  REFERENCES PHUONGTHUCTT(MaPhuongThuc),
    CONSTRAINT FK_ThanhToan_NguoiThu    FOREIGN KEY (NguoiThu)      REFERENCES NHANVIEN(MaNhanVien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 9: BỆNH ÁN & HỒ SƠ
-- ================================================================

-- Bệnh án điện tử (gộp nhiều phiếu khám theo dõi dài hạn)
CREATE TABLE BENHAN (
    MaBenhAn        INT             PRIMARY KEY AUTO_INCREMENT,
    MaBenhAnCode    VARCHAR(20)     NOT NULL,   -- BA00001, BA00002,...
    MaBenhNhan      INT             NOT NULL,
    NgayMo          DATE            NOT NULL,
    NgayDong        DATE,
    ChanDoanNoiTru  TEXT,
    TinhTrang       VARCHAR(50)     NOT NULL DEFAULT 'MO',
    -- 'MO','DANG_DIEU_TRI','RA_VIEN','CHUYEN_VIEN'
    GhiChu          TEXT,
    CONSTRAINT UQ_BenhAn_MaCode UNIQUE (MaBenhAnCode),
    CONSTRAINT FK_BenhAn_BenhNhan FOREIGN KEY (MaBenhNhan) REFERENCES BENHNHAN(MaBenhNhan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Liên kết phiếu khám vào bệnh án
CREATE TABLE BENHAN_PHIEUKHAM (
    MaBenhAn        INT             NOT NULL,
    MaPhieuKham     INT             NOT NULL,
    CONSTRAINT PK_BenhAn_PhieuKham PRIMARY KEY (MaBenhAn, MaPhieuKham),
    CONSTRAINT FK_BAPK_BenhAn    FOREIGN KEY (MaBenhAn)    REFERENCES BENHAN(MaBenhAn),
    CONSTRAINT FK_BAPK_PhieuKham FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- File đính kèm (kết quả xét nghiệm, hình ảnh, giấy tờ,...)
CREATE TABLE FILEHOSOBENHNHAN (
    MaFile          INT             PRIMARY KEY AUTO_INCREMENT,
    MaBenhNhan      INT             NOT NULL,
    MaPhieuKham     INT,
    TenFile         VARCHAR(200)    NOT NULL,
    LoaiFile        VARCHAR(50),    -- 'HINH_ANH','KET_QUA_XN','PDF_BAO_CAO',...
    DuongDan        VARCHAR(500)    NOT NULL,
    KichThuoc       BIGINT,         -- byte
    NgayTai         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NguoiTai        INT,            -- MaTaiKhoan
    CONSTRAINT FK_File_BenhNhan  FOREIGN KEY (MaBenhNhan)  REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_File_PhieuKham FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_File_NguoiTai  FOREIGN KEY (NguoiTai)    REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 10: THÔNG BÁO & TIN NHẮN
-- ================================================================

-- Thông báo hệ thống
CREATE TABLE THONGBAO (
    MaThongBao      INT             PRIMARY KEY AUTO_INCREMENT,
    MaNguoiNhan     INT             NOT NULL,   -- MaTaiKhoan
    TieuDe          VARCHAR(200)    NOT NULL,
    NoiDung         TEXT            NOT NULL,
    DaDoc           BOOLEAN         NOT NULL DEFAULT 0,
    NgayGui         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_ThongBao_NguoiNhan FOREIGN KEY (MaNguoiNhan) REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tin nhắn / chat tư vấn (bệnh nhân <-> bác sĩ)
CREATE TABLE TINNHAN (
    MaTinNhan       INT             PRIMARY KEY AUTO_INCREMENT,
    MaNguoiGui      INT             NOT NULL,   -- MaTaiKhoan
    MaNguoiNhan     INT             NOT NULL,   -- MaTaiKhoan
    NoiDung         TEXT            NOT NULL,
    DaDoc           BOOLEAN         NOT NULL DEFAULT 0,
    ThoiGianGui     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_TinNhan_NguoiGui  FOREIGN KEY (MaNguoiGui)  REFERENCES TAIKHOAN(MaTaiKhoan),
    CONSTRAINT FK_TinNhan_NguoiNhan FOREIGN KEY (MaNguoiNhan) REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 11: ĐÁNH GIÁ DỊCH VỤ
-- ================================================================

CREATE TABLE DANHGIADICHVU (
    MaDanhGia       INT             PRIMARY KEY AUTO_INCREMENT,
    MaBenhNhan      INT             NOT NULL,
    MaPhieuKham     INT             NOT NULL,
    MaBacSi         INT,                        -- NULL nếu đánh giá chung phòng khám
    DiemSao         TINYINT         NOT NULL,
    NhanXet         TEXT,
    DaDuyet         BOOLEAN         NOT NULL DEFAULT 0,  -- Quản lý duyệt trước khi hiển thị
    NgayGui         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT UQ_DanhGia_BN_Phieu  UNIQUE (MaBenhNhan, MaPhieuKham),
    CONSTRAINT CK_DanhGia_DiemSao   CHECK (DiemSao BETWEEN 1 AND 5),
    CONSTRAINT FK_DanhGia_BenhNhan  FOREIGN KEY (MaBenhNhan)  REFERENCES BENHNHAN(MaBenhNhan),
    CONSTRAINT FK_DanhGia_Phieu     FOREIGN KEY (MaPhieuKham) REFERENCES PHIEUKHAM(MaPhieuKham),
    CONSTRAINT FK_DanhGia_BacSi     FOREIGN KEY (MaBacSi)     REFERENCES NHANVIEN(MaNhanVien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PHẦN 12: CẤU HÌNH HỆ THỐNG
-- ================================================================

-- Thông tin cấu hình phòng khám (key-value)
CREATE TABLE CAUHINHHETHONG (
    KhoacCauHinh    VARCHAR(100)    PRIMARY KEY,
    GiaTri          TEXT,
    NguoiCapNhat    INT,
    ThoiGianCapNhat DATETIME,
    CONSTRAINT FK_CauHinh_NguoiCapNhat FOREIGN KEY (NguoiCapNhat) REFERENCES TAIKHOAN(MaTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lịch làm việc / ca trực bác sĩ
CREATE TABLE LICHLAMVIEC (
    MaLich          INT             PRIMARY KEY AUTO_INCREMENT,
    MaBacSi         INT             NOT NULL,
    NgayLam         DATE            NOT NULL,
    Ca              VARCHAR(20)     NOT NULL
                    CHECK (Ca IN ('SANG','CHIEU','TOI')),
    SoBenhNhanToiDa SMALLINT        NOT NULL DEFAULT 20,
    GhiChu          TEXT,
    CONSTRAINT UQ_LichLamViec UNIQUE (MaBacSi, NgayLam, Ca),
    CONSTRAINT FK_LichLamViec_BacSi FOREIGN KEY (MaBacSi) REFERENCES NHANVIEN(MaNhanVien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
JOIN BENHNHAN           bn  ON bn.MaBenhNhan    = pk.MaBenhNhan
JOIN NHANVIEN           nv  ON nv.MaNhanVien    = pk.MaBacSi
JOIN CHUYENKHOA         ck  ON ck.MaChuyenKhoa  = pk.MaChuyenKhoa
JOIN THANHTOAN          tt  ON tt.MaHoaDon      = hd.MaHoaDon
JOIN PHUONGTHUCTT       pt  ON pt.MaPhuongThuc  = tt.MaPhuongThuc
WHERE hd.TrangThai = 'DA_THANH_TOAN';

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
    CASE WHEN t.HanSuDung < CURDATE() THEN 1 ELSE 0 END AS DaHetHan,
    t.GiaBan
FROM THUOC t
JOIN DONVITINH dv ON dv.MaDonVi = t.MaDonVi
WHERE t.DangHoatDong = 1;

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
JOIN CHUYENKHOA         ck  ON ck.MaChuyenKhoa  = lh.MaChuyenKhoa
JOIN TRANGTHAILICHCHHEN tt  ON tt.MaTrangThai   = lh.MaTrangThai
WHERE tt.TenTrangThai IN ('CHO_XAC_NHAN', 'DA_XAC_NHAN');

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

-- ================================================================
-- DỮ LIỆU MẪU KHỞI TẠO
-- ================================================================

INSERT INTO VAITRO (TenVaiTro, MoTa) VALUES
('LE_TAN',         'Nhân viên lễ tân'),
('DIEU_DUONG',     'Điều dưỡng'),
('BAC_SI',         'Bác sĩ'),
('KY_THUAT_VIEN',  'Chuyên viên xét nghiệm'),
('DUOC_SI',        'Dược sĩ'),
('QUAN_LY',        'Quản lý phòng khám'),
('BENH_NHAN',      'Bệnh nhân');

INSERT INTO TRANGTHAIPHIEUKHAM (TenTrangThai) VALUES
('CHO_KHAM'), ('DA_SO_KHAM'), ('DANG_KHAM'),
('CHO_CLS'), ('HOAN_THANH'), ('DA_HUY');

INSERT INTO TRANGTHAILICHCHHEN (TenTrangThai) VALUES
('CHO_XAC_NHAN'), ('DA_XAC_NHAN'), ('DOI_LICH'),
('DA_HUY'), ('HOAN_THANH');

INSERT INTO PHUONGTHUCTT (TenPhuongThuc) VALUES
('TIEN_MAT'), ('CHUYEN_KHOAN'), ('QR_CODE');

INSERT INTO DONVITINH (TenDonVi) VALUES
('Viên'), ('Chai'), ('Ống'), ('Gói'), ('Hộp'),
('mg'), ('ml'), ('Lần'), ('Tuýp');

INSERT INTO LOAICLSN (TenLoaiCLS, MoTa) VALUES
('Xét nghiệm máu',         'Công thức máu, sinh hóa,...'),
('Xét nghiệm nước tiểu',   'Tổng phân tích nước tiểu'),
('X-quang',                'Chụp X-quang các vùng'),
('Siêu âm',                'Siêu âm ổ bụng, tim, thai,...'),
('Điện tim (ECG)',         'Ghi điện tâm đồ'),
('MRI',                    'Cộng hưởng từ'),
('Nội soi',                'Nội soi dạ dày, đại tràng,...');

INSERT INTO CHUYENKHOA (TenChuyenKhoa) VALUES
('Nội tổng quát'), ('Ngoại tổng quát'), ('Nhi khoa'),
('Sản - Phụ khoa'), ('Da liễu'), ('Tai Mũi Họng'),
('Mắt'), ('Răng Hàm Mặt'), ('Tim mạch'), ('Thần kinh');

INSERT INTO CAUHINHHETHONG (KhoacCauHinh, GiaTri) VALUES
('ten_phong_kham',     'Phòng Khám Đa Khoa'),
('dia_chi',            ''),
('so_dien_thoai',      ''),
('logo_url',           ''),
('tien_to_hoa_don',    'HD'),
('tien_to_phieu_kham', 'PK'),
('tien_to_benh_nhan',  'BN');