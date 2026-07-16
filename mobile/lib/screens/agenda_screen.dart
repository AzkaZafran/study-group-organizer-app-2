import 'package:flutter/material.dart';
import '../mock_data/mock_agenda.dart';
import 'catatan_screen.dart';

class AgendaScreen extends StatelessWidget {
  const AgendaScreen({super.key});

  void _showDetailDialog(BuildContext context, Map<String, dynamic> item) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (BuildContext context) {
        return Container(
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
                  Expanded(
                    child: Text(
                      'Detail: ${item['judul']}',
                      style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Color(0xff1F2937)),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.close, color: Colors.black54),
                    onPressed: () => Navigator.pop(context),
                    padding: EdgeInsets.zero,
                    constraints: const BoxConstraints(),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              const Divider(color: Colors.black12, thickness: 1),
              const SizedBox(height: 16),
              _buildPopupSingleRow('Penyelenggara', item['partisipan']),
              const SizedBox(height: 14),
              _buildPopupSingleRow('Tempat', item['tempat']),
              const SizedBox(height: 14),
              _buildPopupSingleRow('Waktu', '${item['waktu']}  ${item['jam']}'),
              const SizedBox(height: 14),
              _buildPopupSingleRow('Status', item['status']),
              const SizedBox(height: 16),
              const Text('Partisipan:', style: TextStyle(color: Color(0xff1F2937), fontSize: 16, fontWeight: FontWeight.w500)),
              const SizedBox(height: 10),
              Wrap(
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                    decoration: BoxDecoration(
                      border: Border.all(color: const Color(0xff1E3A8A), width: 1.5),
                      borderRadius: BorderRadius.circular(50),
                    ),
                    child: Text(item['partisipan'], style: const TextStyle(color: Color(0xff1F2937), fontSize: 14, fontWeight: FontWeight.bold)),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Divider(color: Colors.black12, thickness: 1),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton(
                      onPressed: () {
                        // 1. Tutup Bottom Sheet
                        Navigator.pop(context); 
                        
                        // 2. Navigasi ke CatatanScreen dengan menyertakan agendaData
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => CatatanScreen(agendaData: item), // Kirim 'item' ke sini
                          ),
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xff1F2937), 
                        foregroundColor: Colors.white, 
                        padding: const EdgeInsets.symmetric(vertical: 14)
                      ),
                      child: const Text('Ke Catatan'),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: ElevatedButton(
                      onPressed: () => Navigator.pop(context),
                      style: ElevatedButton.styleFrom(backgroundColor: const Color(0xff6C757D), foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(vertical: 14)),
                      child: const Text('Tutup'),
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final agendas = MockAgenda.data;
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Agenda', style: TextStyle(fontSize: 32, fontWeight: FontWeight.w500, color: Color(0xff1F2937))),
          const SizedBox(height: 16),
          ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: agendas.length,
            itemBuilder: (context, index) {
              final item = agendas[index];
              return Padding(
                padding: const EdgeInsets.only(bottom: 16.0),
                child: Card(
                  margin: EdgeInsets.zero,
                  elevation: 2,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  // Material memastikan InkWell rapi dengan borderRadius
                  child: Material(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                    child: InkWell(
                      borderRadius: BorderRadius.circular(8),
                      onTap: () => _showDetailDialog(context, item),
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(item['judul'], style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xff1E3A8A))),
                            const SizedBox(height: 12),
                            _buildDetailRow('Tempat', item['tempat']),
                            _buildDetailRow('Waktu', item['waktu']),
                            _buildDetailRow('Jam', item['jam']),
                            _buildDetailRow('Status', item['status']),
                            const SizedBox(height: 6),
                            _buildDetailRow('Partisipan', item['partisipan']),
                            const SizedBox(height: 12),
                            const Center(
                              child: Text('Klik untuk detail', style: TextStyle(fontSize: 13, color: Colors.black45)),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(width: 90, child: Text(label, style: const TextStyle(color: Color(0xff1F2937), fontSize: 15, fontWeight: FontWeight.w500))),
          const Text('  :  ', style: TextStyle(fontWeight: FontWeight.w500)),
          Expanded(child: Text(value, style: const TextStyle(color: Colors.black, fontWeight: FontWeight.bold, fontSize: 15))),
        ],
      ),
    );
  }

  Widget _buildPopupSingleRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text('$label: ', style: const TextStyle(color: Color(0xff1F2937), fontSize: 16, fontWeight: FontWeight.w400)),
        Expanded(child: Text(value, style: const TextStyle(color: Colors.black, fontSize: 16, fontWeight: FontWeight.bold))),
      ],
    );
  }
}