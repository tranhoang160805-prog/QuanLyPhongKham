<div class="main-container">
    <div class="container-center">
        <section class="relative bg-white pt-20 pb-32 overflow-hidden">
            <!-- Background Decor -->
            <div class="absolute inset-y-0 right-0 w-1/2 bg-slate-50 rounded-l-[100px] z-0 hidden lg:block"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-primary-50 text-primary-600 text-sm font-semibold mb-6">#1 Hệ thống y tế chuẩn quốc tế</span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight mb-6">
                            Chăm sóc sức khỏe <span class="text-primary-600">tận tâm</span> cho gia đình bạn
                        </h1>
                        <p class="text-lg text-slate-600 mb-10 leading-relaxed">
                            Đội ngũ Y Bác sĩ chuyên môn cao, trang thiết bị hiện đại cùng dịch vụ y tế chuẩn quốc tế. Chúng tôi luôn sẵn sàng đồng hành cùng sức khỏe của bạn.
                        </p>

                        <!-- Search Box -->
                        <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-100 flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                                <input type="text" placeholder="Tìm bác sĩ, chuyên khoa, dịch vụ..." class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all bg-slate-50">
                            </div>
                            <button class="h-12 px-8 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-colors whitespace-nowrap shadow-sm">
                                Tìm kiếm
                            </button>
                        </div>

                        <div class="mt-8 flex items-center gap-6 text-sm text-slate-500 font-medium">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                                <span>Khám đúng hẹn</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                                <span>Đội ngũ chuyên gia</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative hidden md:block">
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-100 to-transparent rounded-[40px] transform rotate-3"></div>
                        <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Clinic Interior" class="relative z-10 rounded-[40px] shadow-2xl object-cover h-[600px] w-full">

                        <!-- Floating Card -->
                        <div class="absolute -bottom-6 -left-10 bg-white p-6 rounded-2xl shadow-xl z-20 border border-slate-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-14 h-14 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl">mood</span>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-slate-800">10k+</div>
                                <div class="text-sm font-medium text-slate-500">Bệnh nhân hài lòng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- Chuyên khoa nổi bật -->
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Chuyên khoa nổi bật</h2>
                    <p class="text-slate-600 text-lg">Cung cấp các dịch vụ khám chữa bệnh đa dạng với đội ngũ bác sĩ đầu ngành</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php 
                    $specialties = [
                        ['icon' => 'monitor_heart', 'title' => 'Nội tổng quát', 'desc' => 'Khám và điều trị các bệnh lý nội khoa cơ bản.', 'color' => 'bg-blue-100 text-blue-600'],
                        ['icon' => 'pediatrics', 'title' => 'Nhi khoa', 'desc' => 'Chăm sóc sức khỏe toàn diện cho trẻ sơ sinh và trẻ nhỏ.', 'color' => 'bg-pink-100 text-pink-600'],
                        ['icon' => 'dentistry', 'title' => 'Nha khoa', 'desc' => 'Thẩm mỹ nha khoa, chỉnh nha và điều trị các bệnh về răng miệng.', 'color' => 'bg-teal-100 text-teal-600'],
                        ['icon' => 'visibility', 'title' => 'Mắt', 'desc' => 'Khám và điều trị các bệnh lý về mắt, đo thị lực.', 'color' => 'bg-purple-100 text-purple-600'],
                        ['icon' => '', 'title' => 'Tiêu hóa', 'desc' => 'Nội soi, chẩn đoán và điều trị bệnh lý dạ dày, đại tràng.', 'color' => 'bg-orange-100 text-orange-600'],
                        ['icon' => 'psychiatry', 'title' => 'Thần kinh', 'desc' => 'Khám và điều trị các bệnh lý về não, tủy sống, dây thần kinh.', 'color' => 'bg-indigo-100 text-indigo-600'],
                        ['icon' => 'pregnant_woman', 'title' => 'Sản phụ khoa', 'desc' => 'Chăm sóc thai kỳ, khám phụ khoa và tư vấn kế hoạch hóa gia đình.', 'color' => 'bg-rose-100 text-rose-600'],
                        ['icon' => 'stethoscope', 'title' => 'Tai mũi họng', 'desc' => 'Điều trị viêm xoang, viêm họng, các bệnh lý thính giác.', 'color' => 'bg-emerald-100 text-emerald-600'],
                    ];
                    foreach ($specialties as $spec):
                    ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-primary-200 transition-all group cursor-pointer">
                        <div class="w-14 h-14 rounded-xl <?php echo $spec['color']; ?> flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl"><?php echo $spec['icon']; ?></span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2"><?php echo $spec['title']; ?></h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2"><?php echo $spec['desc']; ?></p>
                        <a href="#" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">
                            Tìm hiểu thêm <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                    
                <div class="text-center mt-12">
                    <a href="index.php?page=services" class="inline-flex items-center justify-center px-6 py-3 border-2 border-primary-600 text-primary-600 font-bold rounded-xl hover:bg-primary-50 transition-colors">
                        Xem tất cả chuyên khoa
                    </a>
                </div>
            </div>
        </section>

<!-- Bác sĩ hàng đầu -->
        <section class="py-20 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Bác sĩ hàng đầu</h2>
                        <p class="text-slate-600 text-lg">Đội ngũ chuyên gia giàu kinh nghiệm, luôn tận tâm vì người bệnh</p>
                    </div>
                    <div class="hidden md:flex gap-2">
                        <button class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </button>
                        <button class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-colors">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </div>
                            
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php 
                    $doctors = [
                        ['name' => 'PGS. TS. Nguyễn Văn A', 'spec' => 'Nội tổng quát', 'exp' => '25 năm kinh nghiệm', 'img' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                        ['name' => 'BS. CKII. Trần Thị B', 'spec' => 'Sản phụ khoa', 'exp' => '18 năm kinh nghiệm', 'img' => 'https://images.unsplash.com/photo-1594824436998-d147bb4324f9?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                        ['name' => 'ThS. BS. Lê Hoàng C', 'spec' => 'Tim mạch', 'exp' => '15 năm kinh nghiệm', 'img' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                        ['name' => 'BS. CKI. Phạm Minh D', 'spec' => 'Nhi khoa', 'exp' => '10 năm kinh nghiệm', 'img' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                    ];
                    foreach ($doctors as $doc):
                    ?>
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                        <div class="h-64 overflow-hidden relative">
                            <img src="<?php echo $doc['img']; ?>" alt="<?php echo $doc['name']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-slate-700 shadow-sm flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-amber-500">star</span> 4.9
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-sm font-semibold text-primary-600 mb-1"><?php echo $doc['spec']; ?></p>
                            <h3 class="text-lg font-bold text-slate-800 mb-2 truncate"><?php echo $doc['name']; ?></h3>
                            <div class="flex items-center gap-2 text-sm text-slate-500 mb-5">
                                <span class="material-symbols-outlined text-base">work_history</span>
                                <span><?php echo $doc['exp']; ?></span>
                            </div>
                            <button class="w-full py-2.5 border border-primary-200 text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition-colors">
                                Đặt lịch khám
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                    
                <div class="text-center mt-12 md:hidden">
                    <a href="index.php?page=doctors" class="inline-flex items-center justify-center px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">
                        Xem tất cả bác sĩ
                    </a>
                </div>
            </div>
        </section>
                    
        <!-- Tại sao chọn Hương Sơn -->
        <section class="py-20 bg-primary-900 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Tại sao chọn Hương Sơn?</h2>
                        <p class="text-primary-100 text-lg mb-10 leading-relaxed">
                            Với phương châm "Bệnh nhân là trung tâm", chúng tôi không ngừng cải tiến chất lượng dịch vụ để mang đến trải nghiệm chăm sóc sức khỏe hoàn hảo nhất.
                        </p>
                        
                        <div class="space-y-6">
                            <div class="flex gap-4 items-start">
                                <div class="w-12 h-12 rounded-full bg-primary-800 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-primary-300 text-2xl">medical_services</span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-2">Đội ngũ chuyên gia</h4>
                                    <p class="text-primary-200">Quy tụ các bác sĩ giỏi, chuyên gia y tế hàng đầu với nhiều năm tu nghiệp trong và ngoài nước.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="w-12 h-12 rounded-full bg-primary-800 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-primary-300 text-2xl">biotech</span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-2">Trang thiết bị hiện đại</h4>
                                    <p class="text-primary-200">Hệ thống máy móc tân tiến được nhập khẩu trực tiếp từ các quốc gia có nền y học phát triển.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start">
                                <div class="w-12 h-12 rounded-full bg-primary-800 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-primary-300 text-2xl">support_agent</span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-2">Dịch vụ chuyên nghiệp</h4>
                                    <p class="text-primary-200">Quy trình khám chữa bệnh nhanh chóng, khoa học, giảm thiểu thời gian chờ đợi của khách hàng.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Doctor Consultation" class="rounded-2xl shadow-2xl">
                        <!-- Stat badge -->
                        <div class="absolute -bottom-8 -left-8 bg-white p-6 rounded-2xl shadow-xl border border-slate-100 flex gap-6 items-center">
                            <div>
                                <div class="text-4xl font-bold text-primary-600 mb-1">15k+</div>
                                <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Khám mỗi năm</div>
                            </div>
                            <div class="h-12 w-px bg-slate-200"></div>
                            <div>
                                <div class="text-4xl font-bold text-primary-600 mb-1">99%</div>
                                <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Hài lòng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
                    
        <!-- Tin tức & Tư vấn -->
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Tin tức & Tư vấn sức khỏe</h2>
                        <p class="text-slate-600 text-lg">Cập nhật thông tin y tế mới nhất và những lời khuyên hữu ích từ chuyên gia</p>
                    </div>
                    <a href="#" class="hidden md:inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700">
                        Xem tất cả tin tức <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </a>
                </div>
                    
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php 
                    $news = [
                        ['title' => '5 thói quen giúp bạn bảo vệ hệ tim mạch khỏe mạnh', 'date' => '25 Tháng 5, 2026', 'cat' => 'Tư vấn sức khỏe', 'img' => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                        ['title' => 'Dấu hiệu nhận biết sớm suy thận mạn tính và cách phòng tránh', 'date' => '22 Tháng 5, 2026', 'cat' => 'Bệnh học', 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5e4a055d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                        ['title' => 'Tầm quan trọng của việc khám sức khỏe tổng quát định kỳ', 'date' => '18 Tháng 5, 2026', 'cat' => 'Kiến thức y tế', 'img' => 'https://images.unsplash.com/photo-1576091160550-2173ff9e5eb2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                    ];
                    foreach ($news as $n):
                    ?>
                    <article class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-lg transition-all group cursor-pointer flex flex-col">
                        <div class="h-48 overflow-hidden">
                            <img src="<?php echo $n['img']; ?>" alt="News" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 mb-3">
                                <span class="text-primary-600 bg-primary-50 px-2 py-1 rounded-md uppercase tracking-wider"><?php echo $n['cat']; ?></span>
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_month</span> <?php echo $n['date']; ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-primary-600 transition-colors line-clamp-2"><?php echo $n['title']; ?></h3>
                            <p class="text-slate-600 text-sm mb-4 line-clamp-3">Tìm hiểu những thông tin hữu ích giúp bạn và gia đình nâng cao sức khỏe mỗi ngày, được tư vấn trực tiếp từ các chuyên gia y tế...</p>
                            <div class="mt-auto">
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600">Đọc tiếp <span class="material-symbols-outlined text-sm">arrow_forward</span></span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</div>