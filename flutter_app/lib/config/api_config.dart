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

  /// Resolves an image URL so it works seamlessly across web, desktop, emulator, and physical devices with CORS support.
  static String? resolveImageUrl(String? rawUrl) {
    if (rawUrl == null || rawUrl.trim().isEmpty) return null;
    var trimmed = rawUrl.trim();

    final baseUri = Uri.parse(baseUrl);
    final origin = baseUri.hasPort
        ? '${baseUri.scheme}://${baseUri.host}:${baseUri.port}'
        : '${baseUri.scheme}://${baseUri.host}';

    // Route storage files through /api/v1/storage/ so it is served by Laravel with CORS headers
    if (trimmed.contains('/storage/') && !trimmed.contains('/api/v1/storage/')) {
      trimmed = trimmed.replaceFirst('/storage/', '/api/v1/storage/');
    }

    if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) {
      final parsed = Uri.parse(trimmed);
      // If the backend returns localhost/127.0.0.1 but the app connects via Android emulator or custom host
      if ((parsed.host == 'localhost' || parsed.host == '127.0.0.1') &&
          (baseUri.host != 'localhost' && baseUri.host != '127.0.0.1')) {
        return parsed
            .replace(
              scheme: baseUri.scheme,
              host: baseUri.host,
              port: baseUri.hasPort ? baseUri.port : parsed.port,
            )
            .toString();
      }
      return trimmed;
    }

    if (trimmed.startsWith('/api/v1/storage/')) {
      return '$origin$trimmed';
    }
    if (trimmed.startsWith('api/v1/storage/')) {
      return '$origin/$trimmed';
    }

    final cleanPath = trimmed.startsWith('/') ? trimmed : '/$trimmed';
    return '$origin/api/v1/storage$cleanPath';
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

