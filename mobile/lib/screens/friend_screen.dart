// lib/screens/friend_screen.dart
import 'package:flutter/material.dart';

class FriendScreen extends StatefulWidget {
  const FriendScreen({super.key});

  @override
  State<FriendScreen> createState() => _FriendScreenState();
}

class _FriendScreenState extends State<FriendScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;

  // Data dummy list nama sesuai tampilan web asli kamu
  final List<String> _daftarTeman = ['Teguh Ryan', 'Raphaelle Gibson IV', 'Leland Yost'];
  final List<String> _permintaanTeman = ['Ahmad Dhani', 'Siti Nurhaliza'];
  final List<String> _tambahTeman = ['Budi Utomo', 'Clarissa Anggraini', 'Dedi Corbuzier'];

  @override
  void initState() {
    super.initState();
    // Mengatur 3 ruang tab internal
    _tabController = TabController(length: 3, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xffF8FAFC), // Background abu-abu terang murni sesuai web
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // 1. Judul Halaman Besar di Luar Kotak Putih
            const Text(
              'Teman',
              style: TextStyle(
                fontSize: 32,
                fontWeight: FontWeight.w500,
                color: Colors.black,
              ),
            ),
            const SizedBox(height: 16),

            // 2. Kotak Putih Utama (Card dengan Bayangan Halus)
            Expanded(
              child: Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.05),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    // --- REKAYASA NAVIGASI TAB RATAL 100% MEMENUHI LEBAR CARD ---
                    Container(
                      decoration: const BoxDecoration(
                        border: Border(
                          bottom: BorderSide(color: Color(0xffE5E7EB), width: 1), // Garis pembatas bawah (.border-b)
                        ),
                      ),
                      child: TabBar(
                        controller: _tabController,
                        isScrollable: false, // Menonaktifkan scroll agar membagi rata space layar
                        tabAlignment: TabAlignment.fill, // Memaksa tab merentang penuh 100% lebar card
                        
                        labelColor: const Color(0xff1E3A8A), // Warna teks saat aktif (Navy)
                        unselectedLabelColor: const Color(0xff9CA3AF), // Warna teks saat tidak aktif (Abu-abu)
                        
                        // Menyesuaikan gaya font agar aman dan pas di segala ukuran layar HP
                        labelStyle: const TextStyle(
                          fontWeight: FontWeight.bold, 
                          fontSize: 12,
                        ),
                        unselectedLabelStyle: const TextStyle(
                          fontWeight: FontWeight.normal, 
                          fontSize: 12,
                        ),
                        
                        indicatorColor: const Color(0xff1E3A8A), // Garis indikator bawah aktif
                        indicatorSize: TabBarIndicatorSize.tab, // Garis penuh sepanjang blok tab-nya
                        
                        // Menyederhanakan teks agar tidak overflow / terpotong di HP
                        tabs: const [
                          Tab(text: 'Daftar'),
                          Tab(text: 'Permintaan'),
                          Tab(text: 'Tambah'),
                        ],
                      ),
                    ),

                    // --- ISI KONTEN UNTUK MASING-MASING TAB ---
                    Expanded(
                      child: TabBarView(
                        controller: _tabController,
                        children: [
                          _buildTabContent(_daftarTeman),
                          _buildTabContent(_permintaanTeman),
                          _buildTabContent(_tambahTeman),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Helper Widget pembentuk list kotak abu-abu nama teman
  Widget _buildTabContent(List<String> names) {
    if (names.isEmpty) {
      return const Center(child: Text('Tidak ada data'));
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16.0),
      itemCount: names.length,
      itemBuilder: (context, index) {
        return Container(
          width: double.infinity,
          margin: const EdgeInsets.only(bottom: 12), // Jarak spasi antar kotak nama
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          decoration: BoxDecoration(
            color: const Color(0xffE5E7EB), // Kotak latar abu-abu halus murni (.bg-gray-200)
            borderRadius: BorderRadius.circular(8), // Sudut melengkung halus
          ),
          child: Text(
            names[index],
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w400,
              color: Color(0xff1F2937), // Warna teks nama gelap murni
            ),
          ),
        );
      },
    );
  }
}