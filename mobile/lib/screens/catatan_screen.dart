import 'package:flutter/material.dart';


class CatatanScreen extends StatefulWidget {
  final Map<String, dynamic> agendaData;
  const CatatanScreen({super.key, required this.agendaData});

  @override
  State<CatatanScreen> createState() => _CatatanScreenState();
}

class _CatatanScreenState extends State<CatatanScreen> {
  late String _currentSelectedMateri;
  late List<String> _subMateriList;
  late List<Map<String, dynamic>> _catatanList;
  bool _isPesertaExpanded = false;

  late List<String> _pesertaList;
  late String _penyelenggara;

  @override
  void initState() {
    super.initState();
    
    // Parse sub materi
    final rawSubMateri = widget.agendaData['sub_materi'];
    if (rawSubMateri is List) {
      _subMateriList = List<String>.from(rawSubMateri);
    } else {
      _subMateriList = [];
    }
    if (_subMateriList.isEmpty) {
      _subMateriList = ['Pengantar UI/UX', 'Normalisasi', 'ER Diagram', 'Pengantar Basis Data'];
    }
    
    _currentSelectedMateri = _subMateriList[0];
    
    // Penyelenggara and Peserta
    _penyelenggara = widget.agendaData['partisipan'] ?? 'Azka Zafran';
    _pesertaList = [
      _penyelenggara,
      'Ahmad Pasha',
      'Teguh Ryan',
      'Ficha',
      'Nabila',
    ];

    // Inisialisasi daftar catatan dengan data awal yang merepresentasikan web
    _catatanList = [
      {
        'id': '1',
        'author': 'Teguh Ryan',
        'time': '10.00',
        'views': 99,
        'title': 'Pengantar UI/UX',
        'content': widget.agendaData['isi_catatan'] ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis ad ex minus porro, minima harum in enim ducimus corrupti quas tenetur vel doloribus autem, aliquam fugit commodi sed saepe nemo!',
        'materi': 'Pengantar UI/UX',
      },
      {
        'id': '2',
        'author': 'Azka Zafran',
        'time': '21.52',
        'views': 100,
        'title': 'Pengantar Basis Data',
        'content': 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
        'materi': 'Pengantar Basis Data',
      },
      {
        'id': '3',
        'author': 'Azka Zafran',
        'time': '21:52',
        'views': 100,
        'title': 'Normalisasi',
        'content': 'Normalisasi data adalah proses mengatur data dalam database untuk menghindari redundansi data dan masalah anomali.',
        'materi': 'Normalisasi',
      },
      {
        'id': '4',
        'author': 'Azka Zafran',
        'time': '21:52',
        'views': 100,
        'title': 'ER Diagram',
        'content': 'Entity Relationship Diagram digunakan untuk memetakan hubungan antar tabel/entitas dalam basis data.',
        'materi': 'ER Diagram',
      },
    ];

    // Menyelaraskan catatan agar sesuai dengan sub materi yang ada di agenda aktif
    for (var i = 0; i < _catatanList.length; i++) {
      if (i < _subMateriList.length) {
        _catatanList[i]['materi'] = _subMateriList[i];
        _catatanList[i]['title'] = _subMateriList[i];
      } else {
        _catatanList[i]['materi'] = _subMateriList[0];
        _catatanList[i]['title'] = _subMateriList[0];
      }
    }
  }

  void _addCatatan(String title, String content) {
    setState(() {
      _catatanList.insert(0, {
        'id': DateTime.now().millisecondsSinceEpoch.toString(),
        'author': 'Teguh Ryan',
        'time': 'Sekarang',
        'views': 0,
        'title': title,
        'content': content,
        'materi': _currentSelectedMateri,
      });
    });
  }

  void _editCatatan(String id, String title, String content) {
    setState(() {
      final index = _catatanList.indexWhere((note) => note['id'] == id);
      if (index != -1) {
        _catatanList[index]['title'] = title;
        _catatanList[index]['content'] = content;
      }
    });
  }

  void _deleteCatatan(String id) {
    setState(() {
      _catatanList.removeWhere((note) => note['id'] == id);
    });
  }

  void _confirmDeleteCatatan(String id) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          title: const Text(
            'Hapus Catatan',
            style: TextStyle(fontWeight: FontWeight.bold, color: Color(0xff1E3A8A)),
          ),
          content: const Text('Apakah Anda yakin ingin menghapus catatan ini?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Batal', style: TextStyle(color: Colors.grey)),
            ),
            ElevatedButton(
              onPressed: () {
                _deleteCatatan(id);
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Catatan berhasil dihapus')),
                );
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xffDC2626),
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
              ),
              child: const Text('Hapus'),
            ),
          ],
        );
      },
    );
  }

  void _showFormCatatan({Map<String, dynamic>? existingCatatan}) {
    final titleController = TextEditingController(
      text: existingCatatan != null ? existingCatatan['title'] : _currentSelectedMateri,
    );
    final contentController = TextEditingController(
      text: existingCatatan != null ? existingCatatan['content'] : '',
    );
    final bool isEdit = existingCatatan != null;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
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
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      isEdit ? 'Form Edit Catatan' : 'Form Tambah Catatan',
                      style: const TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Color(0xff1E3A8A),
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.black45),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                const Text(
                  'Judul Catatan / Topik',
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: Color(0xff374151),
                  ),
                ),
                const SizedBox(height: 6),
                TextFormField(
                  controller: titleController,
                  decoration: InputDecoration(
                    hintText: 'Masukkan judul catatan...',
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
                    contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                      borderSide: const BorderSide(color: Color(0xff1E3A8A), width: 1.5),
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                const Text(
                  'Isi Catatan',
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: Color(0xff374151),
                  ),
                ),
                const SizedBox(height: 6),
                TextFormField(
                  controller: contentController,
                  maxLines: 6,
                  decoration: InputDecoration(
                    hintText: 'Silakan mengisi isi catatan...',
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
                    contentPadding: const EdgeInsets.all(12),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                      borderSide: const BorderSide(color: Color(0xff1E3A8A), width: 1.5),
                    ),
                  ),
                ),
                const SizedBox(height: 24),
                SizedBox(
                  width: double.infinity,
                  height: 48,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      if (titleController.text.trim().isEmpty || contentController.text.trim().isEmpty) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Judul dan isi catatan tidak boleh kosong!')),
                        );
                        return;
                      }
                      if (isEdit) {
                        _editCatatan(existingCatatan['id'], titleController.text, contentController.text);
                      } else {
                        _addCatatan(titleController.text, contentController.text);
                      }
                      Navigator.pop(context);
                    },
                    icon: const Icon(Icons.send_rounded, color: Colors.white, size: 18),
                    label: Text(
                      isEdit ? 'Simpan Perubahan' : 'Simpan',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xff1E3A8A),
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildAgendaDetailCard() {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xffE5E7EB), width: 1),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.02),
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 10),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Row(
                  children: [
                    Icon(Icons.info_outline, color: Color(0xff1E3A8A), size: 20),
                    SizedBox(width: 8),
                    Text(
                      'Informasi Agenda',
                      style: TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                        color: Color(0xff1E3A8A),
                      ),
                    ),
                  ],
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: const Color(0xffDCFCE7),
                    borderRadius: BorderRadius.circular(50),
                  ),
                  child: const Text(
                    'Aktif',
                    style: TextStyle(
                      color: Color(0xff15803D),
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
          ),
          const Divider(height: 1, color: Color(0xffE5E7EB)),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            child: Row(
              children: [
                const SizedBox(
                  width: 110,
                  child: Text(
                    'Penyelenggara:',
                    style: TextStyle(color: Color(0xff4B5563), fontSize: 14),
                  ),
                ),
                Expanded(
                  child: Text(
                    _penyelenggara,
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      color: Color(0xff1F2937),
                      fontSize: 14,
                    ),
                  ),
                ),
              ],
            ),
          ),
          const Divider(height: 1, color: Color(0xffE5E7EB)),
          InkWell(
            onTap: () {
              setState(() {
                _isPesertaExpanded = !_isPesertaExpanded;
              });
            },
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Peserta (${_pesertaList.length})',
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      color: Color(0xff4B5563),
                    ),
                  ),
                  Icon(
                    _isPesertaExpanded ? Icons.keyboard_arrow_up : Icons.keyboard_arrow_down,
                    color: Colors.grey,
                    size: 20,
                  ),
                ],
              ),
            ),
          ),
          if (_isPesertaExpanded)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              child: Wrap(
                spacing: 8,
                runSpacing: 8,
                children: _pesertaList.map((peserta) {
                  final bool isCurrentUser = peserta == 'Teguh Ryan';
                  return Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: isCurrentUser ? const Color(0xffDBEAFE) : const Color(0xffF3F4F6),
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(
                        color: isCurrentUser ? const Color(0xff3B82F6) : const Color(0xffE5E7EB),
                        width: 1,
                      ),
                    ),
                    child: Text(
                      peserta,
                      style: TextStyle(
                        fontSize: 13,
                        color: isCurrentUser ? const Color(0xff1E40AF) : const Color(0xff374151),
                        fontWeight: isCurrentUser ? FontWeight.bold : FontWeight.normal,
                      ),
                    ),
                  );
                }).toList(),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildMateriChips() {
    return SizedBox(
      height: 40,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        itemCount: _subMateriList.length,
        itemBuilder: (context, index) {
          final materi = _subMateriList[index];
          final bool isSelected = materi == _currentSelectedMateri;
          return Padding(
            padding: const EdgeInsets.only(right: 8.0),
            child: ChoiceChip(
              label: Text(
                materi,
                style: TextStyle(
                  color: isSelected ? Colors.white : const Color(0xff1F2937),
                  fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                ),
              ),
              selected: isSelected,
              selectedColor: const Color(0xff1E3A8A),
              backgroundColor: const Color(0xffE5E7EB),
              onSelected: (selected) {
                if (selected) {
                  setState(() {
                    _currentSelectedMateri = materi;
                  });
                }
              },
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(20),
                side: BorderSide(
                  color: isSelected ? const Color(0xff1E3A8A) : Colors.transparent,
                ),
              ),
              showCheckmark: false,
            ),
          );
        },
      ),
    );
  }

  Widget _buildCatatanPostCard(Map<String, dynamic> note) {
    final bool isMyPost = note['author'] == 'Teguh Ryan';
    
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border(
          bottom: BorderSide(color: const Color(0xffE5E7EB), width: 3),
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.02),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      note['author'],
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        color: Color(0xff1F2937),
                        fontSize: 14,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      '• ${note['time']} · ${note['views']} views',
                      style: const TextStyle(
                        color: Colors.grey,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
              if (isMyPost)
                Row(
                  children: [
                    IconButton(
                      icon: const Icon(Icons.edit_square, color: Colors.black54, size: 20),
                      onPressed: () => _showFormCatatan(existingCatatan: note),
                      tooltip: 'Edit',
                      constraints: const BoxConstraints(),
                      padding: const EdgeInsets.all(8),
                    ),
                    IconButton(
                      icon: const Icon(Icons.delete_forever, color: Colors.redAccent, size: 20),
                      onPressed: () => _confirmDeleteCatatan(note['id']),
                      tooltip: 'Delete',
                      constraints: const BoxConstraints(),
                      padding: const EdgeInsets.all(8),
                    ),
                  ],
                ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            note['title'],
            style: const TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: Color(0xff1F2937),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            note['content'],
            style: const TextStyle(
              fontSize: 14,
              color: Color(0xff4B5563),
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEmptyState() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xffE5E7EB), width: 1),
      ),
      child: Column(
        children: [
          const Icon(
            Icons.note_alt_outlined,
            size: 64,
            color: Colors.black26,
          ),
          const SizedBox(height: 16),
          Text(
            'Belum ada catatan untuk topik "$_currentSelectedMateri"',
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w500,
              color: Color(0xff4B5563),
            ),
          ),
          const SizedBox(height: 8),
          const Text(
            'Klik tombol "Post Catatan" di bawah untuk memposting catatan pertama Anda.',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 13,
              color: Colors.grey,
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final filteredCatatan = _catatanList.where((note) => note['materi'] == _currentSelectedMateri).toList();

    return Scaffold(
      backgroundColor: const Color(0xffF8FAFC),
      appBar: AppBar(
        title: Text(
          widget.agendaData['judul'] ?? 'Catatan',
          style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.white, fontSize: 18),
        ),
        backgroundColor: const Color(0xff1E3A8A),
        foregroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildAgendaDetailCard(),
              const SizedBox(height: 20),
              const Text(
                'Materi / Topik Catatan',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Color(0xff1F2937),
                ),
              ),
              const SizedBox(height: 10),
              _buildMateriChips(),
              const SizedBox(height: 20),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Catatan: $_currentSelectedMateri',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Color(0xff1E3A8A),
                    ),
                  ),
                  Text(
                    '${filteredCatatan.length} Postingan',
                    style: const TextStyle(
                      fontSize: 13,
                      color: Colors.grey,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              if (filteredCatatan.isEmpty)
                _buildEmptyState()
              else
                ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: filteredCatatan.length,
                  itemBuilder: (context, index) {
                    final note = filteredCatatan[index];
                    return _buildCatatanPostCard(note);
                  },
                ),
              const SizedBox(height: 80),
            ],
          ),
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showFormCatatan(),
        backgroundColor: const Color(0xff1E3A8A),
        foregroundColor: Colors.white,
        elevation: 4,
        icon: const Icon(Icons.edit_note, size: 24),
        label: const Text('Post Catatan', style: TextStyle(fontWeight: FontWeight.bold)),
      ),
    );
  }
}