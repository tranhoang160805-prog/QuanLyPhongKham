<?php

// =========================================================================
// HÀM 1: TÍNH TOÁN DỮ LIỆU PHAN TRANG TRONG DATABASE (Gọi ở đầu file view)
// =========================================================================
if (!function_exists('getPaginationData')) {
    function getPaginationData($pdo, $table_source, $where_clause = "WHERE 1=1", $params = [], $current_page = 1, $limit = 10) {
        
        // 1. Tính tổng số bản ghi
        $sql_count = "SELECT COUNT(*) FROM $table_source $where_clause";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->execute($params);
        $total_records = (int)$stmt_count->fetchColumn();

        // 2. Tính tổng số trang
        $total_pages = $total_records > 0 ? ceil($total_records / $limit) : 1;

        // 3. Chuẩn hóa trang hiện tại để chống lỗi vượt quá hoặc nhỏ hơn 1
        if ($current_page < 1) $current_page = 1;
        if ($current_page > $total_pages) $current_page = $total_pages;

        // 4. Tính toán vị trí bắt đầu lấy dữ liệu (Offset)
        $offset = ((int)$current_page - 1) * (int)$limit;

        return [
            'total_records' => $total_records,
            'total_pages'   => $total_pages,
            'current_page'  => $current_page,
            'offset'        => $offset,
            'start_record'  => $total_records > 0 ? $offset + 1 : 0
        ];
    }
}

// =========================================================================
// HÀM 2: HIỂN THỊ THANH NÚT BẤM PHÂN TRANG HTML (Gọi ở cuối file view - dưới table)
// =========================================================================
if (!function_exists('renderPagination')) {
    // Đổi tên chuẩn thành renderPagination như đoạn code của bạn
    function renderPagination($current_page, $total_pages, $page_param_name = 'p') {
        if ($total_pages <= 1) return; // Nếu chỉ có 1 trang thì không cần hiện

        // Tự động lấy lại toàn bộ các tham số bộ lọc cũ trên URL (như status_filter, search...)
        $get_params = $_GET;
        
        echo '<ul class="pagination-list" style="display: flex; gap: 4px; list-style: none; margin: 0; padding: 0; align-items: center;">';

        // 1. Nút Lùi Trang (Mũi tên trái)
        if ($current_page > 1) {
            $get_params[$page_param_name] = $current_page - 1;
            $prev_url = "index.php?" . http_build_query($get_params);
            echo "<li><a href='{$prev_url}' style='display: block; padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #334155; text-decoration: none; font-size: 0.85rem; font-weight: 500;'>&laquo;</a></li>";
        }

        // 2. Hiển thị các số trang (Thuật toán hiển thị rút gọn có dấu ...)
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === 1 || $i === $total_pages || ($i >= $current_page - 1 && $i <= $current_page + 1)) {
                $get_params[$page_param_name] = $i;
                $page_url = "index.php?" . http_build_query($get_params);
                
                if ($i === $current_page) {
                    echo "<li class='active'><a href='{$page_url}' style='display: block; padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid #0284c7; background: #0284c7; color: #fff; text-decoration: none; font-size: 0.85rem; font-weight: 600;'>{$i}</a></li>";
                } else {
                    echo "<li><a href='{$page_url}' style='display: block; padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #334155; text-decoration: none; font-size: 0.85rem; font-weight: 500;'>{$i}</a></li>";
                }
            } 
            else if ($i === 2 || $i === $total_pages - 1) {
                echo "<li style='padding: 0 6px; color: #64748b; font-size: 0.85rem;'>...</li>";
            }
        }

        // 3. Nút Tiến Trang (Mũi tên phải)
        if ($current_page < $total_pages) {
            $get_params[$page_param_name] = $current_page + 1;
            $next_url = "index.php?" . http_build_query($get_params);
            echo "<li><a href='{$next_url}' style='display: block; padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #334155; text-decoration: none; font-size: 0.85rem; font-weight: 500;'>&raquo;</a></li>";
        }

        echo '</ul>';
    }
}