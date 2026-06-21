<?php
// 1. GỌI API LẤY DỮ LIỆU NHÂN VIÊN TỪ SERVER
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$apiUrl = $protocol . $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) . 'src/api/getNhanVienList.php';

$apiResponse = @file_get_contents($apiUrl);
$staffList = [];

if ($apiResponse) {
    $result = json_decode($apiResponse, true);
    if (isset($result['success']) && $result['success'] === true) {
        $staffList = $result['data'] ?? [];
    }
}

// 2. LẤY GIÁ TRỊ FILTER TỪ URL
$selectedRole = isset($_GET['role_filter']) ? $_GET['role_filter'] : '';
$searchQuery = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';

// 3. TIẾN HÀNH LỌC DỮ LIỆU TRÊN MẢNG PHP
$filteredStaff = array_filter($staffList, function($member) use ($selectedRole, $searchQuery) {
    if (($member['status'] ?? '') !== 'active') return false;

    if (!empty($selectedRole) && ($member['role_name'] ?? '') !== $selectedRole) {
        return false;
    }

    if (!empty($searchQuery)) {
        $memberName = mb_strtolower($member['name'] ?? '', 'UTF-8');
        $searchLower = mb_strtolower($searchQuery, 'UTF-8');
        if (strpos($memberName, $searchLower) === false) {
            return false;
        }
    }
    return true;
});

// Chuyển mảng về dạng index liên tục để JavaScript xử lý đếm số lượng chính xác
$filteredStaff = array_values($filteredStaff);

// Định nghĩa danh sách vai trò cho thanh ngang
$roleTabs = [
    ''              => 'Tất cả',
    'BAC_SI'        => 'Bác sĩ chuyên khoa',
    'LE_TAN'        => 'Nhân viên Lễ tân',
    'DIEU_DUONG'    => 'Điều dưỡng viên',
    'DUOC_SI'       => 'Dược sĩ lâm sàng',
    'KY_THUAT_VIEN' => 'Kỹ thuật viên'
];
?>

<section class="bg-blue-600 pt-32 pb-20 relative overflow-hidden rounded-2xl mb-8">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Đội ngũ Chuyên gia Y tế</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto">
            Hội tụ những bác sĩ đầu ngành, kỹ thuật viên dày dặn kinh nghiệm, luôn tận tâm đặt sức khỏe bệnh nhân lên hàng đầu.
        </p>
    </div>
</section>

<section class="py-6 bg-white border border-slate-100 rounded-2xl mb-8 shadow-sm sticky top-[72px] z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
        
        <form method="GET" action="index.php" class="flex gap-4">
            <input type="hidden" name="page" value="doctors">
            <input type="hidden" name="role_filter" value="<?= htmlspecialchars($selectedRole) ?>">
            
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" name="search_name" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Tìm kiếm theo tên bác sĩ, nhân viên..." class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all bg-slate-50 text-sm">
            </div>
            <button type="submit" class="h-12 px-8 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm whitespace-nowrap">
                Tìm kiếm
            </button>
        </form>

        <div class="border-t border-slate-100 pt-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">Lọc theo danh mục chức vụ:</p>
            <div class="flex flex-wrap md:flex-nowrap gap-2 overflow-x-auto pb-2 scrollbar-none snap-x">
                <?php foreach ($roleTabs as $key => $label): 
                    // Tạo link GET động giữ nguyên từ khóa tìm kiếm tên khi bấm chuyển tab role
                    $tabUrl = "index.php?page=doctors&role_filter=" . $key . (!empty($searchQuery) ? "&search_name=" . urlencode($searchQuery) : "");
                    $isActive = ($selectedRole === $key);
                    
                    $tabClass = $isActive 
                        ? "bg-blue-600 text-white shadow-sm border-blue-600" 
                        : "bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200/60";
                ?>
                    <a href="<?= $tabUrl ?>" class="px-4 py-2.5 border rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 snap-start <?= $tabClass ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<section class="py-4">
    <div class="max-w-7xl mx-auto">
        <?php if (empty($filteredStaff)): ?>
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200">
                <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">person_search</span>
                <p class="text-slate-500 font-medium">Không tìm thấy nhân sự phù hợp với bộ lọc.</p>
            </div>
        <?php else: ?>
            
            <div id="staff-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($filteredStaff as $index => $doc): 
                    // 2 hàng trên desktop (4 cột) = 8 nhân viên đầu tiên được hiện, còn lại mặc định thêm class ẩn
                    $isInitialHidden = $index >= 8;
                ?>
                <div class="staff-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col <?= $isInitialHidden ? 'hidden' : '' ?>" 
                     data-index="<?= $index ?>">
                    
                    <div class="h-64 overflow-hidden bg-slate-50 relative flex items-center justify-center border-b border-slate-50">
                        <?php if (!empty($doc['avatar'])): ?>
                            <img src="public/uploads/avatars/<?= htmlspecialchars($doc['avatar']) ?>" alt="<?= htmlspecialchars($doc['name']) ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-600 flex items-center justify-center font-bold text-5xl tracking-wide select-none group-hover:scale-105 transition-transform duration-500" style="<?= !empty($doc['avatar']) ? 'display:none;' : '' ?>">
                            <?= htmlspecialchars($doc['initials'] ?? 'U') ?>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2.5">
                            <?php
                            $badgeStyles = [
                                'BAC_SI' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'LE_TAN' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'DIEU_DUONG' => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                'DUOC_SI' => 'bg-purple-50 text-purple-700 border-purple-100'
                            ];
                            $currentBadgeStyle = $badgeStyles[$doc['role_name']] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                            ?>
                            <span class="px-2.5 py-0.5 border text-[11px] font-bold rounded-md uppercase tracking-wide <?= $currentBadgeStyle ?>">
                                <?= htmlspecialchars($doc['role']) ?>
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-800 mb-0.5 tracking-tight group-hover:text-blue-600 transition-colors">
                            <?= htmlspecialchars($doc['name']) ?>
                        </h3>
                        <p class="text-xs text-slate-400 font-mono mb-4">ID: [<?= htmlspecialchars($doc['staff_id']) ?>]</p>
                        
                        <div class="mt-auto pt-4 border-t border-slate-50 space-y-2 text-xs text-slate-600 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">school</span>
                                <span class="font-medium">Trình độ:</span>
                                <span class="text-slate-800"><?= htmlspecialchars($doc['qualification'] ?? 'Đang cập nhật') ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">verified_user</span>
                                <span class="font-medium">Số CCHN:</span>
                                <span class="text-slate-800 font-mono"><?= htmlspecialchars($doc['license_number'] ?? 'Không có / Miễn') ?></span>
                            </div>
                        </div>
                        
                        <a href="index.php?page=dat-lich&doctor_id=<?= $doc['staff_id'] ?>" class="w-full text-center py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-sm block">
                            Đặt lịch làm việc
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($filteredStaff) > 8): ?>
                <div class="mt-12 flex justify-center gap-4">
                    
                    <button id="btn-staff-load-more" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm">
                        <span>Xem thêm nhân viên</span>
                        <span class="material-symbols-outlined text-xl">keyboard_arrow_down</span>
                    </button>

                    <button id="btn-staff-collapse" class="hidden flex items-center gap-2 px-6 py-3 border border-slate-200 hover:border-red-500 text-slate-600 hover:text-red-600 bg-white font-bold text-sm rounded-xl transition-all shadow-sm">
                        <span>Thu gọn lại</span>
                        <span class="material-symbols-outlined text-xl">keyboard_arrow_up</span>
                    </button>

                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnLoadMore = document.getElementById("btn-staff-load-more");
    const btnCollapse = document.getElementById("btn-staff-collapse");
    
    if (!btnLoadMore) return; // Nếu số lượng nhân viên ít hơn 8, không chạy script

    const allCards = document.querySelectorAll(".staff-card");
    const totalStaff = allCards.length;
    
    // Mỗi lần click "Xem thêm", hệ thống sẽ mở thêm 2 hàng nữa (8 nhân viên tiếp theo)
    const itemsPerPage = 8; 
    let currentVisibleCount = 8;

    // 1. XỬ LÝ SỰ KIỆN KHÍ ẤN NÚT "XEM THÊM"
    btnLoadMore.addEventListener("click", function() {
        let nextTargetCount = currentVisibleCount + itemsPerPage;
        
        // Hiện các thẻ tiếp theo nằm trong phạm vi chỉ mục
        allCards.forEach((card, idx) => {
            if (idx >= currentVisibleCount && idx < nextTargetCount) {
                card.classList.remove("hidden");
            }
        });

        currentVisibleCount = Math.min(nextTargetCount, totalStaff);

        // Sau khi hiện, luôn hiển thị nút "Thu gọn lại" bên cạnh
        btnCollapse.classList.remove("hidden");

        // KIỂM TRA: Nếu đã hiện hết sạch nhân viên, tiến hành ẨN nút "Xem thêm" đi
        if (currentVisibleCount >= totalStaff) {
            btnLoadMore.classList.add("hidden");
        }
    });

    // 2. XỬ LÝ SỰ KIỆN KHI ẤN NÚT "THU GỌN LẠI"
    btnCollapse.addEventListener("click", function() {
        // Ẩn toàn bộ các thẻ từ vị trí thứ 8 trở đi
        allCards.forEach((card, idx) => {
            if (idx >= 8) {
                card.classList.add("hidden");
            }
        });

        // Đặt lại số lượng hiển thị về trạng thái 2 hàng ban đầu
        currentVisibleCount = 8;

        // Hiện lại nút "Xem thêm" và giấu chính nó (nút thu gọn) đi
        btnLoadMore.classList.remove("hidden");
        btnCollapse.classList.add("hidden");

        // Cuộn màn hình mượt mà về đầu danh sách grid để user tiện theo dõi
        const gridContainer = document.getElementById("staff-grid-container");
        if (gridContainer) {
            gridContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>