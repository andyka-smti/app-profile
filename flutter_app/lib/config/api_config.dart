import 'dart:io' show Platform;
import 'package:flutter/foundation.dart';

class ApiConfig {
  static String? _customBaseUrl;

  static String get defaultBaseUrl {
    if (kIsWeb) {
      return 'http://127.0.0.1:8000/api/v1';
    }
    try {
      if (Platform.isAndroid) {
        return 'http://10.0.2.2:8000/api/v1';
      }
    } catch (_) {}
    return 'http://127.0.0.1:8000/api/v1';
  }

  static String get baseUrl => _customBaseUrl ?? defaultBaseUrl;

  static set customBaseUrl(String? url) {
    if (url != null && url.trim().isNotEmpty) {
      _customBaseUrl = url.trim().replaceAll(RegExp(r'/+$'), '');
    } else {
      _customBaseUrl = null;
    }
  }

  // Endpoints
  static const String posts = '/posts';
  static const String pages = '/pages';
  static const String categories = '/categories';
  static const String slides = '/slides';
  static const String menus = '/menus';
  static const String settings = '/settings';
  static const String questionTopics = '/question-topics';
  static const String faqs = '/faqs';
  static const String questions = '/questions';
}

