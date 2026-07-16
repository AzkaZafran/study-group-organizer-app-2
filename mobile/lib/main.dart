// lib/main.dart
import 'package:flutter/material.dart';
import 'screens/login_screen.dart'; // Mengarah ke login_screen pertama kali

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Kolaborasi Agenda App',
      debugShowCheckedModeBanner: false, // Menghilangkan teks debug merah di pojok kanan atas
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xff1E3A8A)),
        useMaterial3: true,
      ),
      home: const LoginScreen(), // Set halaman utama ke LoginScreen
    );
  }
}