// lib/screens/main_layout.dart
import 'package:flutter/material.dart';
import 'agenda_screen.dart';
import 'friend_screen.dart'; // Memastikan file friend_screen lama yang digunakan

class MainLayout extends StatefulWidget {
  const MainLayout({super.key});

  @override
  State<MainLayout> createState() => _MainLayoutState();
}

class _MainLayoutState extends State<MainLayout> {
  // Index 0 = Agenda, Index 1 = Teman
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const AgendaScreen(),
    const FriendScreen(), // Menggunakan FriendScreen yang sudah diperbaiki
  ];

  // =========================================================================
  // FUNGSI POP-UP FORM TAMBAH AGENDA (DIAMBIL DARI TOMBOL TENGAH NAV BAR)
  // =========================================================================
  void _showTambahAgendaDialog() {
    final TextEditingController namaController = TextEditingController();
    final TextEditingController tempatController = TextEditingController();
    final TextEditingController tanggalController = TextEditingController();
    final TextEditingController jamController = TextEditingController();
    final TextEditingController partisipanController = TextEditingController();

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (BuildContext context) {
        return StatefulBuilder(
          builder: (BuildContext context, StateSetter setModalState) {
            return Padding(
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
              ),
              child: Container(
                decoration: const BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
                ),
                padding: const EdgeInsets.all(24.0),
                child: SingleChildScrollView(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text(
                            'Form Tambah Agenda',
                            style: TextStyle(
                              fontSize: 18, 
                              fontWeight: FontWeight.bold, 
                              color: Color(0xff1E3A8A), 
                            ),
                          ),
                          IconButton(
                            icon: const Icon(Icons.close, color: Colors.black45, size: 20),
                            onPressed: () => Navigator.pop(context),
                            padding: EdgeInsets.zero,
                            constraints: const BoxConstraints(),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      
                      const Text(
                        'Penyelenggara : teguh ryan',
                        style: TextStyle(
                          fontSize: 14, 
                          color: Color(0xff4B5563), 
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                      const SizedBox(height: 16),

                      _buildFieldLabel('Nama Agenda'),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: namaController,
                        decoration: _buildInputDecoration('name agenda'),
                      ),
                      const SizedBox(height: 14),
                      
                      _buildFieldLabel('Tempat'),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: tempatController,
                        decoration: _buildInputDecoration('name place'),
                      ),
                      const SizedBox(height: 14),

                      _buildFieldLabel('Waktu'),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: tanggalController,
                        readOnly: true,
                        decoration: _buildInputDecoration('dd/mm/yyyy').copyWith(
                          suffixIcon: const Icon(Icons.calendar_today_outlined, size: 18, color: Colors.black54),
                        ),
                        onTap: () async {
                          DateTime? pickedDate = await showDatePicker(
                            context: context,
                            initialDate: DateTime.now(),
                            firstDate: DateTime(2000),
                            lastDate: DateTime(2100),
                          );
                          if (pickedDate != null) {
                            setModalState(() {
                              tanggalController.text = "${pickedDate.day}/${pickedDate.month}/${pickedDate.year}";
                            });
                          }
                        },
                      ),
                      const SizedBox(height: 14),

                      _buildFieldLabel('Jam'),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: jamController,
                        readOnly: true,
                        decoration: _buildInputDecoration('--:--').copyWith(
                          suffixIcon: const Icon(Icons.access_time, size: 18, color: Colors.black54),
                        ),
                        onTap: () async {
                          TimeOfDay? pickedTime = await showTimePicker(
                            context: context,
                            initialTime: TimeOfDay.now(),
                          );
                          if (pickedTime != null) {
                            setModalState(() {
                              jamController.text = pickedTime.format(context);
                            });
                          }
                        },
                      ),
                      const SizedBox(height: 14),

                      _buildFieldLabel('Partisipan'),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: partisipanController,
                        decoration: _buildInputDecoration('+ Klik untuk tambah partisipan'),
                      ),
                      const SizedBox(height: 10),

                      SizedBox(
                        width: double.infinity, 
                        height: 40,
                        child: OutlinedButton(
                          onPressed: () {},
                          style: OutlinedButton.styleFrom(
                            side: const BorderSide(color: Color(0xffD1D5DB), width: 1), 
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
                          ),
                          child: const Text(
                            'Search', 
                            style: TextStyle(
                              color: Color(0xff6B7280), 
                              fontWeight: FontWeight.w400,
                              fontSize: 14,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 24),

                      Align(
                        alignment: Alignment.center,
                        child: SizedBox(
                          width: 140, 
                          height: 40,
                          child: ElevatedButton(
                            onPressed: () => Navigator.pop(context),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: const Color(0xff1D3557), 
                              foregroundColor: Colors.white,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
                              elevation: 0,
                            ),
                            child: const Text(
                              'Simpan',
                              style: TextStyle(fontWeight: FontWeight.w500, fontSize: 15),
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            );
          }
        );
      },
    );
  }

  Widget _buildFieldLabel(String text) {
    return Text(
      text,
      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w400, color: Color(0xff374151)),
    );
  }

  InputDecoration _buildInputDecoration(String hint) {
    return InputDecoration(
      hintText: hint,
      hintStyle: const TextStyle(color: Colors.black26, fontSize: 14), 
      contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      filled: true,
      fillColor: Colors.white,
      isDense: true, 
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(6),
        borderSide: const BorderSide(color: Color(0xffD1D5DB), width: 1),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(6),
        borderSide: const BorderSide(color: Color(0xff1E3A8A), width: 1.2),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xffF8FAFC), 
      appBar: AppBar(
        title: const Text(
          'teguh ryan',
          style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w500),
        ),
        backgroundColor: const Color(0xff1E3A8A),
        elevation: 0,
      ),
      // Merender halaman berdasarkan _currentIndex yang murni (0 = Agenda, 1 = Teman)
      body: _pages[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        // Menentukan ikon mana yang aktif menyala biru secara presisi
        currentIndex: _currentIndex == 1 ? 2 : _currentIndex, 
        type: BottomNavigationBarType.fixed,
        selectedItemColor: const Color(0xff0D6EFD),
        unselectedItemColor: Colors.grey,
        onTap: (index) {
          if (index == 1) {
            // Jika memencet tombol tengah (+ Tambah), buka pop-up tanpa mengubah halaman belakang
            _showTambahAgendaDialog(); 
          } else if (index == 2) {
            // Jika memencet indeks ke-2 (Ikon Teman), ubah halaman aktif ke halaman Teman
            setState(() {
              _currentIndex = 1;
            });
          } else {
            // Jika memencet indeks ke-0 (Ikon Agenda), pindah ke halaman Agenda
            setState(() {
              _currentIndex = 0;
            });
          }
        },
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.grid_view), label: 'Agenda'),
          BottomNavigationBarItem(icon: Icon(Icons.add_circle, size: 30, color: Color(0xff0D6EFD)), label: 'Tambah'),
          BottomNavigationBarItem(icon: Icon(Icons.people), label: 'Teman'),
        ],
      ),
    );
  }
}