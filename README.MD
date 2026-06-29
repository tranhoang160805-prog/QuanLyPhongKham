# README HE THONG QUAN LY PHONG KHAM HUONG SON

Tai lieu nay duoc tong hop tu ma nguon hien tai cua du an `QuanLyPhongKham`, bao gom file schema `database/quanlyphongkham.sql`, cac file cau hinh, giao dien trong `src/views`, API trong `src/api`, controller trong `src/controllers` va tai nguyen frontend trong `public/assets`.

## 1. Tong quan he thong

He thong la ung dung web quan ly phong kham, phat trien bang PHP thuan, MySQL/MariaDB va giao dien HTML/CSS/JavaScript. Ung dung ho tro nhieu vai tro su dung trong quy trinh van hanh phong kham:

- Quan ly/Admin: quan tri nhan vien, tai khoan, danh muc, cau hinh, bao cao.
- Benh nhan: dang ky tai khoan, dang nhap, cap nhat ho so, dat lich, xem lich su kham, don thuoc, hoa don, ket qua xet nghiem.
- Le tan: tiep don benh nhan, lap phieu kham, dieu phoi lich hen, lap hoa don.
- Dieu duong: so kham, cap nhat thong so sinh ton, tien su benh, trieu chung va phan phong kham.
- Bac si: kham benh, chan doan, chi dinh can lam sang, xem ket qua can lam sang, ke don thuoc.
- Ky thuat vien: tiep nhan chi dinh CLS, thuc hien xet nghiem/can lam sang, nhap ket qua va upload file ket qua.
- Duoc si: quan ly kho thuoc, nhap/sua/khoa thuoc, cap phat thuoc va tru ton kho.

Kien truc hien tai la PHP thuan co mot file vao chinh `index.php`. Route duoc dinh nghia bang mang `$allowed_pages` trong `config/routing.php`. Logic nghiep vu nam chu yeu o `src/api/*.php` va mot phan nam truc tiep trong cac file view. Mot so controller hien tai chi la placeholder, rieng `NhanVienController.php` va `CauHinhController.php` co xu ly ghi du lieu.

## 2. Yeu cau chuc nang

| Ma  | Ten chuc nang                 | Mo ta ngan                                                                                                                              | Nguoi thuc hien                          |
| --- | ----------------------------- | --------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| F01 | Dang ky tai khoan benh nhan   | Benh nhan tao tai khoan, he thong kiem tra trung ten dang nhap/SDT/email, tao ban ghi `taikhoan`, `benhnhan` va phan vai tro benh nhan. | Benh nhan                                |
| F02 | Xac thuc OTP                  | Gui/xu ly OTP, xac thuc tai khoan va kich hoat nguoi dung.                                                                              | Benh nhan, he thong                      |
| F03 | Dang nhap                     | Kiem tra tai khoan, mat khau hash, trang thai hoat dong, vai tro; tao session dang nhap.                                                | Tat ca nguoi dung                        |
| F04 | Quen/doi mat khau             | Cap nhat mat khau moi, reset so lan sai, ho tro doi mat khau trong trang ho so.                                                         | Benh nhan, quan ly                       |
| F05 | Phan quyen theo vai tro       | Xac dinh menu va trang lam viec theo vai tro `admin`, `benh-nhan`, `le-tan`, `dieu-duong`, `bac-si`, `ky-thuat-vien`, `duoc-si`.        | He thong, quan ly                        |
| F06 | Chuyen goc nhin vai tro admin | Admin co the chuyen workspace sang cac vai tro nhan vien de xem/manh hinh lam viec.                                                     | Admin                                    |
| F07 | Quan ly ho so benh nhan       | Xem, tim kiem, cap nhat thong tin hanh chinh, BHYT, nhom mau, di ung, dia chi, so dien thoai.                                           | Benh nhan, le tan                        |
| F08 | Tiep nhan benh nhan           | Tao moi hoac cap nhat benh nhan khi den kham, tao/cap nhat phieu kham tu lich hen.                                                      | Le tan                                   |
| F09 | Dat lich kham online          | Benh nhan chon ngay gio, gui yeu cau lich hen va co the tao phieu kham dat truoc.                                                       | Benh nhan                                |
| F10 | Quan ly lich hen tai quay     | Le tan xem danh sach lich hen/phieu dat truoc, tim kiem, tiep nhan va chuyen vao quy trinh kham.                                        | Le tan                                   |
| F11 | Tao phieu kham                | Tao `phieukham` voi ma phieu, STT, benh nhan, bac si, chuyen khoa, ngay/gio kham, ly do kham.                                           | Le tan, benh nhan                        |
| F12 | Danh sach phieu kham hom nay  | Lay danh sach phieu kham, loc theo trang thai, tim kiem benh nhan, phan trang.                                                          | Le tan, dieu duong, bac si, KTV, duoc si |
| F13 | So kham                       | Cap nhat thong so sinh ton, trieu chung, tien su benh, chan doan so bo, ghi chu, bac si/chuyen khoa.                                    | Dieu duong                               |
| F14 | Kham benh                     | Bac si xem hang doi, cap nhat chan doan, loi dan, trang thai phieu kham.                                                                | Bac si                                   |
| F15 | Chi dinh CLS                  | Bac si chon dich vu can lam sang, tao cac dong `chidinhcls`, chuyen phieu sang trang thai cho xet nghiem.                               | Bac si                                   |
| F16 | Thuc hien CLS                 | KTV tiep nhan chi dinh, doi trang thai chi dinh sang dang thuc hien, chuyen phieu sang dang xet nghiem.                                 | Ky thuat vien                            |
| F17 | Nhap ket qua CLS              | KTV nhap ket qua dang text, ket luan, cac chi so mau/nuoc tieu, upload anh/file ket qua, cap nhat `ketquacls`.                          | Ky thuat vien                            |
| F18 | Xem ket qua CLS               | Bac si/benh nhan xem danh sach va chi tiet ket qua can lam sang.                                                                        | Bac si, benh nhan                        |
| F19 | Ke don thuoc                  | Bac si tim thuoc, chon so luong, don gia, cach dung, loi dan; tao `donthuoc` va `chitietdonthuoc`.                                      | Bac si                                   |
| F20 | Xem don thuoc                 | Benh nhan/duoc si xem don thuoc va chi tiet thuoc theo phieu kham.                                                                      | Benh nhan, duoc si                       |
| F21 | Cap phat thuoc                | Duoc si cap phat theo don, tao `phieuxuat`, `chitietphieuxuat`, tru `thuoc.SoLuongTon`, chuyen trang thai phieu.                        | Duoc si                                  |
| F22 | Quan ly kho thuoc             | Them/sua/khoa thuoc, loc theo trang thai, tim kiem, canh bao ton kho/han su dung.                                                       | Duoc si                                  |
| F23 | Nhap kho thuoc                | Giao dien nhap kho, gan nha cung cap, ngay nhap, nguoi nhap, chi tiet thuoc.                                                            | Duoc si                                  |
| F24 | Lap hoa don                   | Lay chi tiet dich vu CLS va thuoc cua phieu kham, tinh tong tien/giam gia/tong thanh toan, tao hoa don.                                 | Le tan                                   |
| F25 | Thanh toan hoa don            | Cap nhat trang thai hoa don, ngay thanh toan, tao ban ghi `thanhtoan` theo phuong thuc thanh toan.                                      | Le tan                                   |
| F26 | Xem hoa don benh nhan         | Benh nhan xem danh sach hoa don va chi tiet cac khoan CLS/thuoc.                                                                        | Benh nhan                                |
| F27 | Quan ly nhan vien             | Them/sua nhan vien, upload anh the, tao tai khoan, gan vai tro, gan chuyen khoa, khoa/mo tai khoan.                                     | Quan ly/Admin                            |
| F28 | Quan ly cau hinh he thong     | Cap nhat ten phong kham, hotline, email, dia chi, gio mo cua; luu vao `cauhinhhethong`.                                                 | Quan ly/Admin                            |
| F29 | Quan ly danh muc              | Giao dien quan ly danh muc he thong: chuyen khoa, dich vu CLS, don vi tinh, vai tro/trang thai.                                         | Quan ly/Admin                            |
| F30 | Bao cao thong ke              | Giao dien bao cao doanh thu, thong ke; schema co view `v_doanhthu`.                                                                     | Quan ly/Admin                            |
| F31 | Trang thong tin cong khai     | Trang chu, dich vu, doi ngu bac si, header/footer.                                                                                      | Khach truy cap                           |
| F32 | Bao tri va loi truy cap       | Che do bao tri, trang 403, 503.                                                                                                         | He thong, admin                          |

## 3. Co so du lieu va bang du lieu chinh

File co so du lieu chinh: `database/quanlyphongkham.sql`.

Thong tin dump:

- Database: `quanlyphongkham`.
- He quan tri: MariaDB/MySQL.
- Charset/collation: `utf8mb4`, `utf8mb4_unicode_ci`.
- Co du lieu mau cho benh nhan, nhan vien, thuoc, phieu kham, don thuoc, hoa don, CLS...

### 3.1 Danh sach bang va truong quan trong

| Bang                 | Muc dich                                       | Truong quan trong                                                                                                                                                                                                                                                                        |
| -------------------- | ---------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `taikhoan`           | Luu tai khoan dang nhap.                       | `MaTaiKhoan`, `TenDangNhap`, `MatKhauHash`, `SoDienThoai`, `DangHoatDong`, `SoLanSaiMK`, `KhoaDen`, `NgayTao`, `LanDangNhapCuoi`, `is_verified`, `otp_code`, `otp_expires_at`                                                                                                            |
| `vaitro`             | Danh muc vai tro nguoi dung.                   | `MaVaiTro`, `TenVaiTro`, `MoTa`                                                                                                                                                                                                                                                          |
| `taikhoan_vaitro`    | Gan tai khoan voi vai tro.                     | `MaTaiKhoan`, `MaVaiTro`, `NgayPhanQuyen`                                                                                                                                                                                                                                                |
| `benhnhan`           | Ho so hanh chinh va y te co ban cua benh nhan. | `MaBenhNhan`, `MaTaiKhoan`, `MaBN`, `CCCD`, `HoTen`, `NgaySinh`, `GioiTinh`, `SoDienThoai`, `Email`, `DiaChi`, `SoBHYT`, `NhomMau`, `DiUng`, `TrangThai`, `NgayTao`, `NgayCapNhat`                                                                                                       |
| `nhanvien`           | Ho so nhan vien phong kham.                    | `MaNhanVien`, `MaTaiKhoan`, `HoTen`, `AnhThe`, `NgaySinh`, `GioiTinh`, `CCCD`, `SoDienThoai`, `Email`, `DiaChi`, `MaChuyenKhoa`, `BangCap`, `SoChungChi`, `NgayVaoLam`, `DangHoatDong`                                                                                                   |
| `chuyenkhoa`         | Danh muc chuyen khoa.                          | `MaChuyenKhoa`, `TenChuyenKhoa`, `MoTa`                                                                                                                                                                                                                                                  |
| `lichlamviec`        | Lich lam viec cua bac si.                      | `MaLich`, `MaBacSi`, `NgayLam`, `Ca`, `SoBenhNhanToiDa`, `GhiChu`                                                                                                                                                                                                                        |
| `lichhen`            | Lich hen kham cua benh nhan.                   | `MaLichHen`, `MaBenhNhan`, `NgayHen`, `GioHen`, `MaTrangThai`, `GhiChu`, `NguoiTao`, `NgayTao`, `NgayCapNhat`                                                                                                                                                                            |
| `trangthailichchhen` | Danh muc trang thai lich hen.                  | `MaTrangThai`, `TenTrangThai`                                                                                                                                                                                                                                                            |
| `phieukham`          | Phieu kham, trung tam cua quy trinh kham.      | `MaPhieuKham`, `STT`, `MaPhieuKhamCode`, `MaBenhNhan`, `MaBacSi`, `MaChuyenKhoa`, `NgayKham`, `GioKham`, `GioTiepNhan`, `LyDoKham`, `TrieuChung`, `TienSuBenh`, `ThongSoSinhTon`, `ChanDoanSoBo`, `ChanDoan`, `LoiDanBS`, `GhiChu`, `MaTrangThai`, `MaLichHen`, `NgayTao`, `NgayCapNhat` |
| `trangthaiphieukham` | Danh muc trang thai phieu kham.                | `MaTrangThai`, `TenTrangThai`                                                                                                                                                                                                                                                            |
| `benhan`             | Ho so/benh an tong hop cua benh nhan.          | `MaBenhAn`, `MaBenhAnCode`, `MaBenhNhan`, `NgayMo`, `NgayDong`, `ChanDoanNoiTru`, `TinhTrang`, `GhiChu`                                                                                                                                                                                  |
| `benhan_phieukham`   | Bang lien ket benh an voi nhieu phieu kham.    | `MaBenhAn`, `MaPhieuKham`                                                                                                                                                                                                                                                                |
| `loaiclsn`           | Danh muc dich vu can lam sang/xet nghiem.      | `MaLoaiCLS`, `TenLoaiCLS`, `DonGia`, `MoTa`, `TrangThai`                                                                                                                                                                                                                                 |
| `chidinhcls`         | Chi dinh CLS cua bac si theo phieu kham.       | `MaChiDinh`, `MaPhieuKham`, `MaLoaiCLS`, `MoTaChiDinh`, `NgayChiDinh`, `trangthai`                                                                                                                                                                                                       |
| `ketquacls`          | Ket qua thuc hien CLS.                         | `MaKetQua`, `MaChiDinh`, `KetQuaText`, `KetLuan`, `FileKetQua`, `MaNVThucHien`, `NgayThucHien`                                                                                                                                                                                           |
| `thuoc`              | Danh muc va ton kho thuoc.                     | `MaThuoc`, `MaThuocCode`, `TenThuoc`, `TenHoatChat`, `HamLuong`, `MaDonVi`, `DangBaoChe`, `QuyCach`, `NhaSanXuat`, `NuocSanXuat`, `SoDangKy`, `HanSuDung`, `SoLuongTon`, `TonToiThieu`, `GiaNhap`, `GiaBan`, `HuongDanSuDung`, `DangHoatDong`, `NgayTao`, `NgayCapNhat`                  |
| `donvitinh`          | Don vi tinh thuoc.                             | `MaDonVi`, `TenDonVi`                                                                                                                                                                                                                                                                    |
| `donthuoc`           | Don thuoc theo phieu kham.                     | `MaDonThuoc`, `MaPhieuKham`, `NgayKeToa`, `LoiDan`                                                                                                                                                                                                                                       |
| `chitietdonthuoc`    | Cac dong thuoc trong don.                      | `MaChiTiet`, `MaDonThuoc`, `MaThuoc`, `SoLuong`, `DonGia`, `CachDung`                                                                                                                                                                                                                    |
| `phieunhap`          | Phieu nhap kho thuoc.                          | `MaPhieuNhap`, `SoPhieuNhap`, `NhaCungCap`, `NgayNhap`, `NguoiNhap`, `TongTien`, `GhiChu`, `NgayTao`                                                                                                                                                                                     |
| `chitietphieunhap`   | Chi tiet thuoc nhap kho.                       | `MaChiTiet`, `MaPhieuNhap`, `MaThuoc`, `SoLuong`, `DonGia`, `ThanhTien`, `SoLo`, `HanSuDung`                                                                                                                                                                                             |
| `phieuxuat`          | Phieu xuat/cap phat thuoc.                     | `MaPhieuXuat`, `SoPhieuXuat`, `MaBenhNhan`, `NgayXuat`, `NguoiXuat`, `TongTien`, `GhiChu`, `NgayTao`                                                                                                                                                                                     |
| `chitietphieuxuat`   | Chi tiet thuoc da xuat.                        | `MaChiTiet`, `MaPhieuXuat`, `MaThuoc`, `SoLuong`, `DonGia`, `ThanhTien`                                                                                                                                                                                                                  |
| `hoadon`             | Hoa don vien phi theo phieu kham.              | `MaHoaDon`, `SoHoaDon`, `MaPhieuKham`, `TongTienCLS`, `TongTienThuoc`, `TongCong`, `GiamGia`, `TongThanhToan`, `TrangThai`, `NgayTao`, `NgayThanhToan`                                                                                                                                   |
| `thanhtoan`          | Giao dich thanh toan hoa don.                  | `MaThanhToan`, `MaHoaDon`, `MaPhuongThuc`, `SoTien`, `NguoiThu`, `NgayThanhToan`, `GhiChu`                                                                                                                                                                                               |
| `phuongthuctt`       | Danh muc phuong thuc thanh toan.               | `MaPhuongThuc`, `TenPhuongThuc`                                                                                                                                                                                                                                                          |
| `filehosobenhnhan`   | File dinh kem ho so benh nhan.                 | `MaFile`, `MaBenhNhan`, `MaPhieuKham`, `TenFile`, `LoaiFile`, `DuongDan`, `KichThuoc`, `NgayTai`, `NguoiTai`                                                                                                                                                                             |
| `danhgiadichvu`      | Danh gia dich vu kham.                         | `MaDanhGia`, `MaBenhNhan`, `MaPhieuKham`, `MaBacSi`, `DiemSao`, `NhanXet`, `DaDuyet`, `NgayGui`                                                                                                                                                                                          |
| `cauhinhhethong`     | Cau hinh phong kham.                           | `KhoacCauHinh`, `GiaTri`, `NguoiCapNhat`, `ThoiGianCapNhat`                                                                                                                                                                                                                              |
| `nhatkyhoatdong`     | Nhat ky thao tac he thong.                     | `MaNhatKy`, `MaTaiKhoan`, `HanhDong`, `TenBang`, `MaBanGhi`, `GiaTriCu`, `GiaTriMoi`, `DiaChiIP`, `ThoiGian`                                                                                                                                                                             |
| `thongbao`           | Thong bao gui den tai khoan.                   | `MaThongBao`, `MaNguoiNhan`, `TieuDe`, `NoiDung`, `DaDoc`, `NgayGui`                                                                                                                                                                                                                     |
| `tinnhan`            | Tin nhan giua cac tai khoan.                   | `MaTinNhan`, `MaNguoiGui`, `MaNguoiNhan`, `NoiDung`, `DaDoc`, `ThoiGianGui`                                                                                                                                                                                                              |

Ghi chu: Schema co ten bang `trangthailichchhen`. Day co ve la loi chinh ta, nhung phai dung dung ten nay khi truy van theo database hien tai.

### 3.2 Cac view SQL

| View                   | Muc dich                                                                                           |
| ---------------------- | -------------------------------------------------------------------------------------------------- |
| `view_chitietdonthuoc` | Ghep don thuoc, phieu kham, benh nhan, chi tiet don va thuoc de lay chi tiet don thuoc day du.     |
| `v_doanhthu`           | Tong hop hoa don da thanh toan, ngay kham, benh nhan, bac si, chuyen khoa, phuong thuc thanh toan. |
| `v_hoadon_chitiet`     | Hop nhat cac muc tinh tien tu CLS va thuoc cua mot phieu kham.                                     |
| `v_lichhen_hieuluc`    | Lich hen con hieu luc, gom thong tin benh nhan, bac si, chuyen khoa, trang thai.                   |
| `v_tonkhothuoc`        | Ton kho thuoc dang hoat dong, canh bao het hang va het han.                                        |

### 3.3 Khoa chinh, unique va chi muc noi bat

- `taikhoan`: PK `MaTaiKhoan`, unique `TenDangNhap`, unique `SoDienThoai`.
- `benhnhan`: PK `MaBenhNhan`, unique `MaBN`, unique `SoBHYT`, unique `MaTaiKhoan`, unique `CCCD`; index theo `SoDienThoai`, `SoBHYT`, `HoTen`.
- `nhanvien`: PK `MaNhanVien`, unique `MaTaiKhoan`, unique `CCCD`.
- `phieukham`: PK `MaPhieuKham`, unique `MaPhieuKhamCode`, index `MaBenhNhan`, `MaBacSi`, `NgayKham`, `MaTrangThai`, `MaLichHen`.
- `benhan`: PK `MaBenhAn`, unique `MaBenhAnCode`.
- `benhan_phieukham`: PK ghep `MaBenhAn`, `MaPhieuKham`.
- `donthuoc`: PK `MaDonThuoc`, unique `MaPhieuKham`.
- `hoadon`: PK `MaHoaDon`, unique `SoHoaDon`, unique `MaPhieuKham`.
- `ketquacls`: PK `MaKetQua`, unique `MaChiDinh`.
- `lichlamviec`: unique `MaBacSi`, `NgayLam`, `Ca`.
- `thuoc`: PK `MaThuoc`, unique `MaThuocCode`, index `TenThuoc`, `HanSuDung`.

### 3.4 Khoa ngoai chinh

- `benhnhan.MaTaiKhoan` -> `taikhoan.MaTaiKhoan`.
- `nhanvien.MaTaiKhoan` -> `taikhoan.MaTaiKhoan`.
- `nhanvien.MaChuyenKhoa` -> `chuyenkhoa.MaChuyenKhoa`.
- `taikhoan_vaitro.MaTaiKhoan` -> `taikhoan.MaTaiKhoan`.
- `taikhoan_vaitro.MaVaiTro` -> `vaitro.MaVaiTro`.
- `lichhen.MaBenhNhan` -> `benhnhan.MaBenhNhan`.
- `lichhen.NguoiTao` -> `taikhoan.MaTaiKhoan`.
- `lichhen.MaTrangThai` -> `trangthailichchhen.MaTrangThai`.
- `phieukham.MaBenhNhan` -> `benhnhan.MaBenhNhan`.
- `phieukham.MaBacSi` -> `nhanvien.MaNhanVien`.
- `phieukham.MaTrangThai` -> `trangthaiphieukham.MaTrangThai`.
- `benhan.MaBenhNhan` -> `benhnhan.MaBenhNhan`.
- `benhan_phieukham.MaBenhAn` -> `benhan.MaBenhAn`.
- `benhan_phieukham.MaPhieuKham` -> `phieukham.MaPhieuKham`.
- `chidinhcls.MaPhieuKham` -> `phieukham.MaPhieuKham`.
- `chidinhcls.MaLoaiCLS` -> `loaiclsn.MaLoaiCLS`.
- `ketquacls.MaChiDinh` -> `chidinhcls.MaChiDinh`.
- `ketquacls.MaNVThucHien` -> `nhanvien.MaNhanVien`.
- `donthuoc.MaPhieuKham` -> `phieukham.MaPhieuKham`.
- `chitietdonthuoc.MaDonThuoc` -> `donthuoc.MaDonThuoc`.
- `chitietdonthuoc.MaThuoc` -> `thuoc.MaThuoc`.
- `thuoc.MaDonVi` -> `donvitinh.MaDonVi`.
- `phieunhap.NguoiNhap` -> `nhanvien.MaNhanVien`.
- `chitietphieunhap.MaPhieuNhap` -> `phieunhap.MaPhieuNhap`.
- `chitietphieunhap.MaThuoc` -> `thuoc.MaThuoc`.
- `phieuxuat.MaBenhNhan` -> `benhnhan.MaBenhNhan`.
- `phieuxuat.NguoiXuat` -> `nhanvien.MaNhanVien`.
- `chitietphieuxuat.MaPhieuXuat` -> `phieuxuat.MaPhieuXuat`.
- `chitietphieuxuat.MaThuoc` -> `thuoc.MaThuoc`.
- `hoadon.MaPhieuKham` -> `phieukham.MaPhieuKham`.
- `thanhtoan.MaHoaDon` -> `hoadon.MaHoaDon`.
- `thanhtoan.MaPhuongThuc` -> `phuongthuctt.MaPhuongThuc`.
- `thanhtoan.NguoiThu` -> `nhanvien.MaNhanVien`.
- `filehosobenhnhan.MaBenhNhan` -> `benhnhan.MaBenhNhan`.
- `filehosobenhnhan.MaPhieuKham` -> `phieukham.MaPhieuKham`.
- `filehosobenhnhan.NguoiTai` -> `taikhoan.MaTaiKhoan`.
- `danhgiadichvu.MaBenhNhan` -> `benhnhan.MaBenhNhan`.
- `danhgiadichvu.MaPhieuKham` -> `phieukham.MaPhieuKham`.
- `danhgiadichvu.MaBacSi` -> `nhanvien.MaNhanVien`.
- `cauhinhhethong.NguoiCapNhat` -> `taikhoan.MaTaiKhoan`.
- `nhatkyhoatdong.MaTaiKhoan` -> `taikhoan.MaTaiKhoan`.
- `thongbao.MaNguoiNhan` -> `taikhoan.MaTaiKhoan`.
- `tinnhan.MaNguoiGui`, `tinnhan.MaNguoiNhan` -> `taikhoan.MaTaiKhoan`.

## 4. Mo hinh thuc the lien ket va mo hinh quan he theo tung dich vu

### 4.1 Dich vu xac thuc, tai khoan va phan quyen

Mo hinh thuc the lien ket:

```text
TAIKHOAN 1--n TAIKHOAN_VAITRO n--1 VAITRO
TAIKHOAN 1--0..1 BENHNHAN
TAIKHOAN 1--0..1 NHANVIEN
NHANVIEN n--1 CHUYENKHOA
TAIKHOAN 1--n THONGBAO
TAIKHOAN 1--n NHATKYHOATDONG
TAIKHOAN 1--n TINNHAN (nguoi gui/nguoi nhan)
```

Mo hinh quan he:

- `taikhoan(MaTaiKhoan PK, TenDangNhap UNIQUE, MatKhauHash, SoDienThoai UNIQUE, DangHoatDong, is_verified, otp_code, otp_expires_at)`
- `vaitro(MaVaiTro PK, TenVaiTro UNIQUE, MoTa)`
- `taikhoan_vaitro(MaTaiKhoan PK/FK, MaVaiTro PK/FK, NgayPhanQuyen)`
- `benhnhan(MaBenhNhan PK, MaTaiKhoan FK UNIQUE, MaBN UNIQUE, HoTen, SoDienThoai, Email, ...)`
- `nhanvien(MaNhanVien PK, MaTaiKhoan FK UNIQUE, MaChuyenKhoa FK, HoTen, DangHoatDong, ...)`

Dich vu/chuc nang lien quan:

- Dang nhap: `src/views/auth/login.php`.
- Dang ky: `src/views/auth/dang-ky.php`.
- Quen mat khau: `src/views/auth/quen-mat-khau.php`.
- Verify OTP: `src/views/auth/verify-otp.php`, `src/views/auth/otp-handler.php`, `src/views/auth/otp-modal.php`.
- Reset mat khau: `src/api/resetMatKhau.php`.
- Lay/cap nhat trang thai tai khoan: `src/api/getTrangThaiTaiKhoan.php`.

### 4.2 Dich vu tiep nhan, lich hen va phieu kham

Mo hinh thuc the lien ket:

```text
BENHNHAN 1--n LICHHEN n--1 TRANGTHAILICHCHHEN
BENHNHAN 1--n PHIEUKHAM n--1 TRANGTHAIPHIEUKHAM
NHANVIEN(Bac si) 1--n PHIEUKHAM
CHUYENKHOA 1--n PHIEUKHAM
LICHHEN 0..1--0..n PHIEUKHAM (qua MaLichHen trong phieukham)
```

Mo hinh quan he:

- `lichhen(MaLichHen PK, MaBenhNhan FK, NgayHen, GioHen, MaTrangThai FK, GhiChu, NguoiTao FK)`
- `phieukham(MaPhieuKham PK, MaPhieuKhamCode UNIQUE, STT, MaBenhNhan FK, MaBacSi FK, MaChuyenKhoa, NgayKham, GioKham, GioTiepNhan, LyDoKham, MaTrangThai FK, MaLichHen)`
- `trangthailichchhen(MaTrangThai PK, TenTrangThai)`
- `trangthaiphieukham(MaTrangThai PK, TenTrangThai)`

Dich vu/chuc nang lien quan:

- Dat lich: `src/views/benh-nhan/dat-lich.php`, `src/api/lichHen.php`.
- Tiep nhan/le tan: `src/views/le-tan/lich-hen.php`, `src/api/saveBenhNhanTiepNhan.php`, `src/api/createPhieuKham.php`.
- Danh sach phieu kham: `src/views/le-tan/ds-benh-nhan.php`, `src/api/getDSPhieuKham.php`.
- Lay danh sach benh nhan: `src/api/getBenhNhan.php`, `src/api/getDanhSachBN.php`.
- Lay bac si theo khoa: `src/api/getBacSiByKhoa.php`.

### 4.3 Dich vu so kham va kham benh

Mo hinh thuc the lien ket:

```text
BENHNHAN 1--n PHIEUKHAM
NHANVIEN(Bac si) 1--n PHIEUKHAM
CHUYENKHOA 1--n PHIEUKHAM
PHIEUKHAM n--1 TRANGTHAIPHIEUKHAM
```

Mo hinh quan he:

- `phieukham` luu thong tin so kham va kham benh: `TrieuChung`, `TienSuBenh`, `ThongSoSinhTon`, `ChanDoanSoBo`, `ChanDoan`, `LoiDanBS`, `GhiChu`, `MaTrangThai`.

Dich vu/chuc nang lien quan:

- So kham dieu duong: `src/views/dieu-duong/so-kham.php`, `src/api/saveSoKham.php`.
- Lich su so kham: `src/views/dieu-duong/ls-so-kham.php`.
- Kham benh bac si: `src/views/bac-si/kham-benh.php`.
- Xem ket qua CLS va cap nhat chan doan sau CLS: `src/views/bac-si/xem-kq-cls.php`.

### 4.4 Dich vu can lam sang/xet nghiem

Mo hinh thuc the lien ket:

```text
PHIEUKHAM 1--n CHIDINHCLS n--1 LOAICLSN
CHIDINHCLS 1--0..1 KETQUACLS
NHANVIEN(KTV) 1--n KETQUACLS
```

Mo hinh quan he:

- `loaiclsn(MaLoaiCLS PK, TenLoaiCLS UNIQUE, DonGia, MoTa, TrangThai)`
- `chidinhcls(MaChiDinh PK, MaPhieuKham FK, MaLoaiCLS FK, MoTaChiDinh, NgayChiDinh, trangthai)`
- `ketquacls(MaKetQua PK, MaChiDinh FK UNIQUE, KetQuaText, KetLuan, FileKetQua, MaNVThucHien FK, NgayThucHien)`

Dich vu/chuc nang lien quan:

- Bac si chi dinh CLS: `src/views/bac-si/chi-dinh-cls.php`, `src/api/chiDinhCLS.php`.
- KTV nhan chi dinh: `src/views/ky-thuat-vien/xet-nghiem.php`.
- KTV nhap ket qua: `src/views/ky-thuat-vien/kq-xet-nghiem.php`, `src/api/getKetQuaXetNghiem.php`.
- Bac si/benh nhan xem ket qua: `src/views/bac-si/xem-kq-cls.php`, `src/views/benh-nhan/ls-xet-nghiem.php`, `src/api/getDSKetQuaCLS.php`, `src/api/getChiTietXN.php`.
- File upload ket qua duoc luu trong `uploads/ket-qua-cls`.

### 4.5 Dich vu don thuoc va kho duoc

Mo hinh thuc the lien ket:

```text
PHIEUKHAM 1--0..1 DONTHUOC 1--n CHITIETDONTHUOC n--1 THUOC n--1 DONVITINH
NHANVIEN(Duoc si) 1--n PHIEUXUAT 1--n CHITIETPHIEUXUAT n--1 THUOC
NHANVIEN(Duoc si) 1--n PHIEUNHAP 1--n CHITIETPHIEUNHAP n--1 THUOC
BENHNHAN 1--n PHIEUXUAT
```

Mo hinh quan he:

- `donthuoc(MaDonThuoc PK, MaPhieuKham FK UNIQUE, NgayKeToa, LoiDan)`
- `chitietdonthuoc(MaChiTiet PK, MaDonThuoc FK, MaThuoc FK, SoLuong, DonGia, CachDung)`
- `thuoc(MaThuoc PK, MaThuocCode UNIQUE, TenThuoc, MaDonVi FK, SoLuongTon, TonToiThieu, GiaNhap, GiaBan, HanSuDung, DangHoatDong)`
- `phieunhap(MaPhieuNhap PK, SoPhieuNhap UNIQUE, NhaCungCap, NgayNhap, NguoiNhap FK, TongTien)`
- `chitietphieunhap(MaChiTiet PK, MaPhieuNhap FK, MaThuoc FK, SoLuong, DonGia, ThanhTien, SoLo, HanSuDung)`
- `phieuxuat(MaPhieuXuat PK, SoPhieuXuat UNIQUE, MaBenhNhan FK, NgayXuat, NguoiXuat FK, TongTien)`
- `chitietphieuxuat(MaChiTiet PK, MaPhieuXuat FK, MaThuoc FK, SoLuong, DonGia, ThanhTien)`

Dich vu/chuc nang lien quan:

- Bac si ke don: `src/views/bac-si/cap-thuoc.php`, `src/api/saveDonThuoc.php`.
- Benh nhan xem don: `src/views/benh-nhan/don-thuoc.php`, `src/api/getChiTietDonThuoc.php`.
- Duoc si cap phat: `src/views/duoc-si/cap-phat.php`, `src/api/saveCapThuoc.php`, `src/api/capThuoc.php`.
- Quan ly kho: `src/views/duoc-si/kho-thuoc.php`, `src/api/getDSThuoc.php`, `src/api/saveThuoc.php`.
- Nhap kho: `src/views/duoc-si/nhap-kho.php`.

### 4.6 Dich vu hoa don va thanh toan

Mo hinh thuc the lien ket:

```text
PHIEUKHAM 1--0..1 HOADON 1--n THANHTOAN n--1 PHUONGTHUCTT
NHANVIEN(Le tan) 1--n THANHTOAN
PHIEUKHAM 1--n CHIDINHCLS
PHIEUKHAM 1--0..1 DONTHUOC 1--n CHITIETDONTHUOC
```

Mo hinh quan he:

- `hoadon(MaHoaDon PK, SoHoaDon UNIQUE, MaPhieuKham FK UNIQUE, TongTienCLS, TongTienThuoc, TongCong, GiamGia, TongThanhToan, TrangThai, NgayTao, NgayThanhToan)`
- `thanhtoan(MaThanhToan PK, MaHoaDon FK, MaPhuongThuc FK, SoTien, NguoiThu FK, NgayThanhToan, GhiChu)`
- `phuongthuctt(MaPhuongThuc PK, TenPhuongThuc UNIQUE)`
- `v_hoadon_chitiet` lay cac dong tinh tien tu CLS va thuoc.
- `v_doanhthu` tong hop doanh thu da thanh toan.

Dich vu/chuc nang lien quan:

- Lap hoa don: `src/views/le-tan/thanh-toan-hd.php`, `src/api/saveHD.php`, `src/api/actionHoaDon.php`.
- Lay du lieu hoa don: `src/api/getHoaDon.php`, `src/api/getHoaDonData.php`, `src/api/getChiTietHoaDon.php`.
- Benh nhan xem hoa don: `src/views/benh-nhan/hoa-don-bn.php`, `src/api/getHoaDonBN.php`.

### 4.7 Dich vu benh an dien tu va file ho so

Mo hinh thuc the lien ket:

```text
BENHNHAN 1--n BENHAN n--n PHIEUKHAM
BENHNHAN 1--n FILEHOSOBENHNHAN
PHIEUKHAM 1--n FILEHOSOBENHNHAN
TAIKHOAN 1--n FILEHOSOBENHNHAN
```

Mo hinh quan he:

- `benhan(MaBenhAn PK, MaBenhAnCode UNIQUE, MaBenhNhan FK, NgayMo, NgayDong, ChanDoanNoiTru, TinhTrang, GhiChu)`
- `benhan_phieukham(MaBenhAn PK/FK, MaPhieuKham PK/FK)`
- `filehosobenhnhan(MaFile PK, MaBenhNhan FK, MaPhieuKham FK, TenFile, LoaiFile, DuongDan, KichThuoc, NgayTai, NguoiTai FK)`

Dich vu/chuc nang lien quan:

- Benh an dien tu: `src/views/benh-nhan/benh-an-dt.php`.
- Lich su kham/ho so benh an: `src/views/benh-nhan/lich-su-kham.php`, `src/api/getHoSoBenhAn.php`.

### 4.8 Dich vu quan tri he thong

Mo hinh thuc the lien ket:

```text
TAIKHOAN 1--n CAUHINHHETHONG (nguoi cap nhat)
TAIKHOAN 1--n NHATKYHOATDONG
CHUYENKHOA, LOAICLSN, DONVITINH, VAITRO, TRANGTHAI* la cac danh muc phuc vu toan he thong
```

Mo hinh quan he:

- `cauhinhhethong(KhoacCauHinh PK, GiaTri, NguoiCapNhat FK, ThoiGianCapNhat)`
- `nhatkyhoatdong(MaNhatKy PK, MaTaiKhoan FK, HanhDong, TenBang, MaBanGhi, GiaTriCu, GiaTriMoi, DiaChiIP, ThoiGian)`
- Danh muc: `chuyenkhoa`, `loaiclsn`, `donvitinh`, `vaitro`, `trangthaiphieukham`, `trangthailichchhen`, `phuongthuctt`.

Dich vu/chuc nang lien quan:

- Quan ly nhan vien: `src/views/quan-ly/nhan-vien.php`, `src/controllers/NhanVienController.php`.
- Cai dat he thong: `src/views/quan-ly/cai-dat-he-thong.php`, `src/controllers/CauHinhController.php`.
- Danh muc: `src/views/quan-ly/danh-muc.php`.
- Bao cao: `src/views/quan-ly/bao-cao.php`.

## 5. Trang giao dien

### 5.1 Trang cong khai va xac thuc

| Trang          | File                               | Muc dich                                     | Thanh phan chinh                                        |
| -------------- | ---------------------------------- | -------------------------------------------- | ------------------------------------------------------- |
| Trang chu      | `src/views/auth/trang-chu.php`     | Gioi thieu phong kham va dieu huong dich vu. | Header, noi dung gioi thieu, CTA, footer                |
| Doi ngu bac si | `src/views/auth/doi-ngu.php`       | Hien thi danh sach/doi ngu bac si.           | Goi API nhan vien, card bac si, thong tin chuyen khoa   |
| Dich vu        | `src/views/auth/dich-vu.php`       | Gioi thieu dich vu va bang gia.              | Danh sach dich vu, mo ta, gia                           |
| Dang nhap      | `src/views/auth/login.php`         | Dang nhap he thong.                          | Form dang nhap, validate, session, thong bao loi        |
| Dang ky        | `src/views/auth/dang-ky.php`       | Dang ky tai khoan benh nhan.                 | Form thong tin, kiem tra trung, OTP/mail, tao tai khoan |
| Quen mat khau  | `src/views/auth/quen-mat-khau.php` | Doi mat khau khi quen mat khau.              | Form xac minh, update password hash                     |
| Verify OTP     | `src/views/auth/verify-otp.php`    | Xac minh ma OTP.                             | Form OTP, cap nhat `is_verified`                        |
| Logout         | `src/views/auth/logout.php`        | Dang xuat.                                   | Huy session/chuyen trang                                |
| Thong bao      | `src/views/auth/thong-bao.php`     | Hien thi alert/thong bao.                    | UI thong bao                                            |

### 5.2 Trang benh nhan

| Trang              | File                                    | Muc dich                                        | Thanh phan chinh                                                                       |
| ------------------ | --------------------------------------- | ----------------------------------------------- | -------------------------------------------------------------------------------------- |
| Ho so ca nhan      | `src/views/benh-nhan/profile.php`       | Xem/cap nhat thong tin ca nhan va doi mat khau. | Form ho so, tinh/thanh, quan/huyen/xa qua API dia gioi, nut sua/luu, form doi mat khau |
| Dat lich           | `src/views/benh-nhan/dat-lich.php`      | Dat lich kham online.                           | Lich chon ngay/gio, form ly do, danh sach phieu kham cua toi                           |
| Lich su kham       | `src/views/benh-nhan/lich-su-kham.php`  | Xem lich su kham va ho so benh an.              | Timeline/card phieu kham, goi `getHoSoBenhAn.php`                                      |
| Don thuoc          | `src/views/benh-nhan/don-thuoc.php`     | Xem danh sach va chi tiet don thuoc.            | Bang don thuoc, modal chi tiet thuoc                                                   |
| Hoa don            | `src/views/benh-nhan/hoa-don-bn.php`    | Xem hoa don cua benh nhan.                      | Danh sach hoa don, modal chi tiet, tong tien/trang thai                                |
| Ket qua xet nghiem | `src/views/benh-nhan/ls-xet-nghiem.php` | Xem lich su va chi tiet ket qua CLS.            | Danh sach chi dinh, modal ket qua, file ket qua                                        |
| Benh an dien tu    | `src/views/benh-nhan/benh-an-dt.php`    | Xem benh an dien tu.                            | Thong tin benh an/phieu kham lien quan                                                 |

### 5.3 Trang le tan

| Trang                          | File                                 | Muc dich                                                       | Thanh phan chinh                                                                                           |
| ------------------------------ | ------------------------------------ | -------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| Danh sach benh nhan/phieu kham | `src/views/le-tan/ds-benh-nhan.php`  | Theo doi phieu kham trong ngay.                                | Bang phieu kham, loc trang thai, tim kiem, phan trang                                                      |
| Lich hen                       | `src/views/le-tan/lich-hen.php`      | Tiep nhan benh nhan, lap phieu kham, xu ly lich hen dat truoc. | Form benh nhan, form phieu kham, danh sach benh nhan, danh sach phieu dat truoc, chon bac si/khoa, dia chi |
| Thanh toan                     | `src/views/le-tan/thanh-toan-hd.php` | Lap hoa don va thanh toan vien phi.                            | Bang chi tiet phi, tong tien, giam gia, phuong thuc thanh toan, in/xac nhan                                |

### 5.4 Trang dieu duong

| Trang           | File                                  | Muc dich                                         | Thanh phan chinh                                                                  |
| --------------- | ------------------------------------- | ------------------------------------------------ | --------------------------------------------------------------------------------- |
| So kham         | `src/views/dieu-duong/so-kham.php`    | Cap nhat thong so sinh ton va thong tin so kham. | Danh sach phieu cho so kham, form sinh ton, trieu chung, tien su, chan doan so bo |
| Lich su so kham | `src/views/dieu-duong/ls-so-kham.php` | Xem lich su so kham.                             | Bang danh sach, tim kiem/phan trang                                               |

### 5.5 Trang bac si

| Trang           | File                                | Muc dich                                      | Thanh phan chinh                                              |
| --------------- | ----------------------------------- | --------------------------------------------- | ------------------------------------------------------------- |
| Kham benh       | `src/views/bac-si/kham-benh.php`    | Bac si xu ly phieu kham va chan doan.         | Danh sach phieu, form chan doan/loi dan, chuyen trang thai    |
| Chi dinh CLS    | `src/views/bac-si/chi-dinh-cls.php` | Tao chi dinh CLS cho phieu kham.              | Thong tin benh nhan, danh sach dich vu CLS, gio hang chi dinh |
| Ke don thuoc    | `src/views/bac-si/cap-thuoc.php`    | Lap don thuoc sau kham.                       | Tim thuoc, chon so luong/don gia/cach dung, luu don           |
| Xem ket qua CLS | `src/views/bac-si/xem-kq-cls.php`   | Xem ket qua xet nghiem va cap nhat chan doan. | Danh sach ket qua, modal chi tiet, form chan doan             |

### 5.6 Trang ky thuat vien

| Trang              | File                                        | Muc dich                               | Thanh phan chinh                                                          |
| ------------------ | ------------------------------------------- | -------------------------------------- | ------------------------------------------------------------------------- |
| Xet nghiem         | `src/views/ky-thuat-vien/xet-nghiem.php`    | Tiep nhan/nhan thuc hien chi dinh CLS. | Danh sach chi dinh cho thuc hien, nut nhan ca le/tat ca                   |
| Ket qua xet nghiem | `src/views/ky-thuat-vien/kq-xet-nghiem.php` | Nhap va cap nhat ket qua CLS.          | Form ket qua, chi so mau/nuoc tieu, upload file, danh sach dang thuc hien |

### 5.7 Trang duoc si

| Trang     | File                              | Muc dich                                | Thanh phan chinh                                                   |
| --------- | --------------------------------- | --------------------------------------- | ------------------------------------------------------------------ |
| Kho thuoc | `src/views/duoc-si/kho-thuoc.php` | Quan ly danh muc va ton kho thuoc.      | Bang thuoc, form them/sua, loc trang thai, tim kiem, khoa/mo thuoc |
| Cap phat  | `src/views/duoc-si/cap-phat.php`  | Cap phat thuoc theo don va tru ton kho. | Chi tiet don thuoc, nut cap phat, cap nhat phieu xuat              |
| Nhap kho  | `src/views/duoc-si/nhap-kho.php`  | Giao dien nhap kho thuoc.               | Form nha cung cap, ngay nhap, danh sach thuoc nhap                 |

### 5.8 Trang quan ly/admin

| Trang             | File                                     | Muc dich                                     | Thanh phan chinh                                                             |
| ----------------- | ---------------------------------------- | -------------------------------------------- | ---------------------------------------------------------------------------- |
| Dashboard         | `src/views/quan-ly/dashboard.php`        | Trang tong quan.                             | Hien tai file rong                                                           |
| Quan ly nhan vien | `src/views/quan-ly/nhan-vien.php`        | Quan ly nhan vien, tai khoan, vai tro.       | Bang nhan vien, thong ke, loc vai tro/trang thai, modal them/sua, upload anh |
| Bao cao           | `src/views/quan-ly/bao-cao.php`          | Bao cao/thong ke.                            | Bo loc thoi gian, khu vuc thong ke/bieu do                                   |
| Danh muc          | `src/views/quan-ly/danh-muc.php`         | Quan ly danh muc he thong.                   | Tabs/form danh muc, bang du lieu                                             |
| Cai dat he thong  | `src/views/quan-ly/cai-dat-he-thong.php` | Cau hinh thong tin phong kham va gio mo cua. | Form cau hinh, lich mo cua, lich su cap nhat                                 |

### 5.9 Layout va trang loi

| Trang/thanh phan | File                            | Muc dich                                                |
| ---------------- | ------------------------------- | ------------------------------------------------------- |
| Header           | `src/views/layouts/header.php`  | Thanh dieu huong cho trang cong khai/benh nhan.         |
| Sidebar          | `src/views/layouts/sidebar.php` | Menu workspace theo vai tro, ho tro AJAX load noi dung. |
| Footer           | `src/views/layouts/footer.php`  | Chan trang.                                             |
| Alert            | `src/views/layouts/alert.php`   | Thanh thong bao dung chung.                             |
| 403              | `src/views/errors/403.php`      | Trang cam truy cap.                                     |
| Bao tri/503      | `src/views/errors/bao-tri.php`  | Trang che do bao tri.                                   |

## 6. Cong nghe su dung

| Thanh phan          | Cong nghe su dung                   | Ghi chu                                                                       |
| ------------------- | ----------------------------------- | ----------------------------------------------------------------------------- |
| Backend             | PHP thuan                           | Entry point `index.php`, include view theo route.                             |
| Database            | MySQL/MariaDB                       | Ket noi bang PDO trong `config/database.php`.                                 |
| SQL schema          | phpMyAdmin dump                     | File `database/quanlyphongkham.sql`.                                          |
| Frontend            | HTML, CSS, JavaScript thuan         | Logic fetch API nam trong tung view va `public/assets/js`.                    |
| CSS utility         | Tailwind CSS CDN                    | Duoc nhung trong `index.php` bang `https://cdn.tailwindcss.com`.              |
| Icon                | Google Material Symbols             | Link trong `index.php`.                                                       |
| Gui email           | PHPMailer                           | Thu muc `phpmailer/`, dung trong dang ky/OTP.                                 |
| Upload file         | PHP `$_FILES`, `move_uploaded_file` | Anh nhan vien vao `public/assets/img`, ket qua CLS vao `uploads/ket-qua-cls`. |
| Bao mat mat khau    | `password_hash`, `password_verify`  | Dung trong dang ky/dang nhap/doi mat khau.                                    |
| Session             | PHP session                         | Luu `user_id`, `user_role`, `root_role`, `view_role`, thong tin profile.      |
| API noi bo          | PHP endpoint trong `src/api`        | Tra JSON cho giao dien qua `fetch`.                                           |
| Cau hinh he thong   | Bang `cauhinhhethong`               | Load qua `config/site.php`.                                                   |
| Routing             | Mang PHP trong `config/routing.php` | Mapping `page` -> file view, mapping menu theo vai tro.                       |
| Dia gioi hanh chinh | API ngoai `provinces.open-api.vn`   | Dung trong profile va le tan de chon tinh/phuong xa.                          |

## 7. Cau truc ma nguon

```text
QuanLyPhongKham/
|-- index.php                         # Entry point chinh, route theo page, load layout/view
|-- index.html                        # File HTML rieng trong thu muc goc
|-- test.php                          # File test/thu nghiem
|-- README.MD                         # Tai lieu he thong
|-- config/
|   |-- database.php                  # Ket noi PDO den database quanlyphongkham
|   |-- routing.php                   # Danh sach page, title, menu theo vai tro
|   |-- site.php                      # Load cau hinh tu bang cauhinhhethong
|   |-- status.php                    # Hang so trang thai
|   |-- app.php                       # Placeholder/rong
|   |-- constants.php                 # Placeholder/rong
|-- database/
|   |-- quanlyphongkham.sql           # Schema va du lieu mau
|-- phpmailer/
|   |-- PHPMailer.php, SMTP.php, ...  # Thu vien gui mail
|-- public/
|   |-- assets/
|   |   |-- css/                      # CSS dung chung va CSS theo phan he
|   |   |-- js/                       # JS dung chung
|   |   |-- img/                      # Anh/logo/avatar
|-- src/
|   |-- api/                          # Endpoint AJAX/JSON
|   |-- controllers/                  # Controller xu ly POST cho nhan vien/cau hinh, nhieu file placeholder
|   |-- helpers/                      # Ham format, mailer, pdf
|   |-- views/
|   |   |-- auth/                     # Trang cong khai, dang nhap, dang ky, OTP
|   |   |-- benh-nhan/                # Phan he benh nhan
|   |   |-- le-tan/                   # Phan he le tan
|   |   |-- dieu-duong/               # Phan he dieu duong
|   |   |-- bac-si/                   # Phan he bac si
|   |   |-- ky-thuat-vien/            # Phan he ky thuat vien
|   |   |-- duoc-si/                  # Phan he duoc si
|   |   |-- quan-ly/                  # Phan he quan ly/admin
|   |   |-- layouts/                  # Header/sidebar/footer/alert
|   |   |-- errors/                   # Trang loi/bao tri
|-- uploads/
|   |-- ket-qua-cls/                  # File anh/ket qua can lam sang da upload
```

### 7.1 Cac file cau hinh quan trong

- `config/database.php`: tao bien `$pdo` ket noi `localhost`, database `quanlyphongkham`, user `root`, password rong, charset `utf8`.
- `config/routing.php`: khai bao `$allowed_pages`, `$page_titles`, `$menus_by_role`.
- `config/site.php`: lay tat ca cau hinh tu `CAUHINHHETHONG` thanh mang `$site`.
- `config/status.php`: dinh nghia `STATUS_CONFIG` cho phieu kham, hoa don, CLS, thong bao.

### 7.2 Cac API noi bo

| API                                | Chuc nang chinh                                                    |
| ---------------------------------- | ------------------------------------------------------------------ |
| `src/api/actionHoaDon.php`         | Tao hoa don, thanh toan, huy hoa don.                              |
| `src/api/capThuoc.php`             | Cap thuoc, tao phieu xuat va tru kho.                              |
| `src/api/chiDinhCLS.php`           | Luu chi dinh can lam sang.                                         |
| `src/api/createPhieuKham.php`      | Tao phieu kham moi.                                                |
| `src/api/getBacSiByKhoa.php`       | Lay danh sach bac si theo chuyen khoa.                             |
| `src/api/getBenhNhan.php`          | Lay danh sach/chi tiet benh nhan, tim kiem, loc trang thai.        |
| `src/api/getChiTietDonThuoc.php`   | Lay chi tiet don thuoc theo phieu kham.                            |
| `src/api/getChiTietHoaDon.php`     | Lay chi tiet hoa don theo phieu kham.                              |
| `src/api/getChiTietXN.php`         | Lay chi tiet mot chi dinh/ket qua xet nghiem.                      |
| `src/api/getDanhSachBN.php`        | Lay danh sach lich hen/benh nhan.                                  |
| `src/api/getDSKetQuaCLS.php`       | Lay danh sach ket qua CLS.                                         |
| `src/api/getDSPhieuKham.php`       | Lay danh sach phieu kham, thong ke, loc, tim kiem, phan trang.     |
| `src/api/getDSThuoc.php`           | Lay danh sach thuoc, loc ton kho/trang thai, tim kiem, phan trang. |
| `src/api/getHoaDon.php`            | Lay danh sach/chi tiet/du lieu tao hoa don.                        |
| `src/api/getHoaDonBN.php`          | Lay hoa don cho benh nhan.                                         |
| `src/api/getHoaDonData.php`        | Lay thong ke, danh sach, chi tiet hoa don.                         |
| `src/api/getHoSoBenhAn.php`        | Lay ho so/lich su kham cua benh nhan dang dang nhap.               |
| `src/api/getKetQuaXetNghiem.php`   | Luu va lay ket qua xet nghiem.                                     |
| `src/api/getNhanVien.php`          | Lay chi tiet nhan vien.                                            |
| `src/api/getNhanVienList.php`      | Lay danh sach nhan vien theo vai tro/trang thai/tim kiem.          |
| `src/api/getThongTinBenhNhan.php`  | Lay/cap nhat profile benh nhan, doi mat khau.                      |
| `src/api/getTrangThaiTaiKhoan.php` | Cap nhat trang thai hoat dong tai khoan.                           |
| `src/api/lichHen.php`              | Tao lich hen va lay phieu kham cua benh nhan.                      |
| `src/api/resetMatKhau.php`         | Reset mat khau tai khoan.                                          |
| `src/api/saveBenhNhanTiepNhan.php` | Them/cap nhat benh nhan va tiep nhan phieu kham.                   |
| `src/api/saveCapThuoc.php`         | Luu cap phat thuoc, tao phieu xuat, tru ton kho.                   |
| `src/api/saveDonThuoc.php`         | Luu don thuoc va chi tiet don.                                     |
| `src/api/saveHD.php`               | Luu hoa don va chuyen trang thai phieu kham.                       |
| `src/api/saveSoKham.php`           | Cap nhat thong tin so kham/kham benh trong phieu kham.             |
| `src/api/saveThuoc.php`            | Them/sua/khoa thuoc.                                               |

## 8. Chuc nang da cai dat theo phan he

### 8.1 Phan he benh nhan

- Dang ky tai khoan moi.
- Dang nhap/dang xuat.
- Xac thuc OTP.
- Quen mat khau/doi mat khau.
- Xem va cap nhat ho so ca nhan.
- Chon/cap nhat dia chi bang API dia gioi.
- Dat lich kham online.
- Xem phieu kham/lich su kham.
- Xem don thuoc va chi tiet thuoc.
- Xem ket qua xet nghiem/CLS.
- Xem hoa don va chi tiet hoa don.
- Xem benh an dien tu.

### 8.2 Phan he le tan

- Xem danh sach phieu kham trong ngay.
- Loc phieu kham theo trang thai.
- Tim kiem benh nhan/phieu kham.
- Quan ly lich hen dat truoc.
- Them moi benh nhan khi tiep nhan.
- Cap nhat thong tin benh nhan cu.
- Tao phieu kham moi.
- Gan bac si/chuyen khoa/ngay gio kham.
- Lap hoa don tu chi phi CLS va thuoc.
- Thanh toan hoa don theo phuong thuc thanh toan.

### 8.3 Phan he dieu duong

- Xem danh sach phieu cho so kham.
- Cap nhat thong so sinh ton.
- Cap nhat trieu chung, tien su benh, chan doan so bo.
- Chuyen trang thai phieu kham sau so kham.
- Xem lich su so kham.

### 8.4 Phan he bac si

- Xem danh sach phieu kham.
- Cap nhat chan doan va loi dan.
- Tao chi dinh CLS.
- Xem ket qua CLS sau khi KTV nhap.
- Ke don thuoc.
- Tim kiem thuoc khi ke don.
- Chuyen trang thai phieu kham theo tien trinh kham.

### 8.5 Phan he ky thuat vien

- Xem danh sach chi dinh CLS cho thuc hien.
- Nhan thuc hien mot chi dinh hoac nhieu chi dinh theo phieu.
- Nhap ket qua xet nghiem/can lam sang.
- Nhap ket luan.
- Upload file/anh ket qua.
- Cap nhat trang thai chi dinh va phieu kham sau khi hoan tat.

### 8.6 Phan he duoc si

- Xem/tim kiem danh sach thuoc.
- Them thuoc moi.
- Sua thong tin thuoc.
- Khoa/mo trang thai thuoc.
- Loc thuoc theo trang thai/ton kho.
- Xem chi tiet don thuoc can cap.
- Cap phat thuoc.
- Tao phieu xuat va chi tiet phieu xuat.
- Tru ton kho sau cap phat.
- Giao dien nhap kho thuoc.

### 8.7 Phan he quan ly/admin

- Quan ly nhan vien.
- Them nhan vien kem tai khoan va vai tro.
- Sua nhan vien.
- Upload/cap nhat anh the nhan vien.
- Khoa/mo tai khoan nhan vien.
- Loc nhan vien theo vai tro/trang thai.
- Tim kiem nhan vien.
- Cai dat thong tin phong kham.
- Cai dat gio mo cua.
- Quan ly danh muc he thong.
- Xem giao dien bao cao/thong ke.
- Chuyen workspace sang cac vai tro nhan vien.

## 9. Luong nghiep vu tong quat

### 9.1 Luong dat lich va tiep nhan

```text
Benh nhan dang nhap
-> Dat lich kham
-> Tao lich hen/phieu kham dat truoc
-> Le tan xem danh sach dat truoc
-> Tiep nhan/cap nhat thong tin benh nhan
-> Tao hoac cap nhat phieu kham
-> Chuyen vao danh sach phieu kham hom nay
```

### 9.2 Luong kham benh co CLS

```text
Le tan tao phieu kham
-> Dieu duong so kham
-> Bac si kham va chi dinh CLS
-> KTV nhan chi dinh
-> KTV nhap ket qua CLS
-> Bac si xem ket qua va cap nhat chan doan
-> Bac si ke don
-> Duoc si cap phat thuoc
-> Le tan lap/thanh toan hoa don
```

### 9.3 Luong kham benh khong CLS

```text
Le tan tao phieu kham
-> Dieu duong so kham
-> Bac si kham va chan doan
-> Bac si ke don
-> Duoc si cap phat thuoc
-> Le tan lap/thanh toan hoa don
```

### 9.4 Luong hoa don

```text
Phieu kham co chi phi CLS va/hoac thuoc
-> Le tan mo trang thanh toan
-> He thong lay chi tiet tu CLS va don thuoc
-> Tinh TongTienCLS, TongTienThuoc, TongCong, GiamGia, TongThanhToan
-> Tao HOADON
-> Khi thu tien, tao THANHTOAN va cap nhat TrangThai hoa don
```

## 10. Trang thai va quy uoc nghiep vu

### 10.1 Trang thai phieu kham trong code

Theo `config/status.php`:

| Ma  | Trang thai      |
| --- | --------------- |
| 1   | Cap cuu         |
| 2   | Cho so kham     |
| 3   | Cho kham benh   |
| 4   | Cho xet nghiem  |
| 5   | Dang xet nghiem |
| 6   | Da xet nghiem   |
| 7   | Cho cap thuoc   |
| 8   | Da cap thuoc    |
| 9   | Da huy          |

Luu y: Mot so API dang dung trang thai phieu kham theo cac buoc nghiep vu:

- `chiDinhCLS.php`: chuyen sang `MaTrangThai = 4`.
- `xet-nghiem.php`: chuyen sang `MaTrangThai = 5`.
- `getKetQuaXetNghiem.php`: khi tat ca chi dinh hoan tat, chuyen sang `MaTrangThai = 6`.
- `saveDonThuoc.php`: sau ke don, chuyen sang `MaTrangThai = 7`.
- `saveCapThuoc.php`: sau cap phat, chuyen sang `MaTrangThai = 8`.
- `saveHD.php`: sau tao hoa don, chuyen sang `MaTrangThai = 9` trong code hien tai.

### 10.2 Trang thai CLS

Trong bang `chidinhcls`, cot `trangthai` duoc dung theo quy uoc:

- `0`: chua thuc hien.
- `1`: dang/da nhan thuc hien.
- `2`: da co ket qua.

### 10.3 Trang thai hoa don

Trong code API hoa don co cac gia tri chuoi:

- `CHO_THANH_TOAN`: cho thanh toan.
- `DA_THANH_TOAN`: da thanh toan.
- `DA_HUY`: da huy.

`config/status.php` cung co mapping hoa don dang so `0/1`, nen can dong bo neu tiep tuc phat trien.

## 11. Ghi chu hien trang ma nguon

- File `README.MD` ban dau rong, tai lieu nay la ban tong hop moi.
- Worktree hien tai co nhieu file dang `modified/deleted/untracked`; tai lieu chi phan anh ma nguon hien co trong workspace.
- Mot so file controller rong hoac chi co `<?php`, gom `AuthController.php`, `BaoCaoController.php`, `BenhNhanController.php`, `CLSController.php`, `DonThuocController.php`, `HoaDonController.php`, `KhamBenhController.php`, `KhoController.php`, `LichHenController.php`, `PhieuKhamController.php`.
- Logic nghiep vu dang phan tan trong view va API, chua tach thanh model/service ro rang.
- `config/routing.php` co mot so route tro den file khong ton tai hoac ten khac, vi du `dang-ky` tro `src/views/auth/dangky.php` trong khi file hien co la `dang-ky.php`; `hoa-don-lt` tro `src/views/le-tan/hoa-don.php` nhung file hien co la `thanh-toan-hd.php`; `ls-xet-nghiem` route KTV tro file khong thay trong danh sach hien tai.
- `src/views/quan-ly/dashboard.php` hien tai rong.
- `src/views/quan-ly/dich-vu.php` gan nhu rong.
- `src/views/quan-ly/cai-dat-he-thong.php` require `src/models/CauHinhHeThongModel.php`, nhung thu muc `src/models` khong co trong danh sach file hien tai.
- CSDL co view `v_lichhen_hieuluc` tham chieu `lh.MaBacSi` va `lh.MaChuyenKhoa`, trong khi cau truc bang `lichhen` hien thi trong dump khong co hai cot nay; can kiem tra khi import/chay view.
- Ten bang `trangthailichchhen` co ve sai chinh ta nhung dang duoc dung trong schema.
- Mot so comment/chuoi trong file PHP hien thi sai dau do encoding trong workspace, nhung logic van co the chay neu moi truong dang tuong thich.

## 12. Huong dan cai dat/chay nhanh

1. Dat thu muc du an trong `C:\xampp\htdocs\QuanLyPhongKham`.
2. Bat Apache va MySQL/MariaDB bang XAMPP.
3. Tao database `quanlyphongkham`.
4. Import file `database/quanlyphongkham.sql` bang phpMyAdmin hoac MySQL CLI.
5. Kiem tra ket noi trong `config/database.php`:
   - Host: `localhost`
   - Database: `quanlyphongkham`
   - User: `root`
   - Password: rong
6. Mo ung dung tai:

```text
http://localhost/QuanLyPhongKham/index.php
```

## 13. Tai nguyen tinh va thu muc upload

- CSS dung chung: `public/assets/css/style.css`, `style2.css`, `root.css`, `navbar.css`, `modal.css`.
- CSS theo phan he:
  - `public/assets/css/BacSi`
  - `public/assets/css/BenhNhan`
  - `public/assets/css/DieuDuong`
  - `public/assets/css/DuocSi`
  - `public/assets/css/KyThuatVien`
  - `public/assets/css/LeTan`
  - `public/assets/css/QuanLy`
  - `public/assets/css/LayOuts`
- JavaScript: `public/assets/js/ajax123.js`, `main123.js`, `utils.js`.
- Anh/logo/avatar: `public/assets/img`.
- File ket qua CLS: `uploads/ket-qua-cls`.

## 14. Tong ket

He thong Quan Ly Phong Kham Huong Son da co day du cac phan he cot loi cua mot phong kham nho/vua: tiep nhan, kham benh, can lam sang, don thuoc, kho duoc, hoa don, benh nhan va quan tri. Diem manh la schema du lieu kha day du va giao dien da bao phu nhieu vai tro. Diem can cai thien khi phat trien tiep la dong bo route/file, tach logic view/API thanh controller-service-model ro rang, dong bo trang thai nghiep vu, bo sung test va chuan hoa encoding tieng Viet.
