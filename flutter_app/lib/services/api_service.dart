import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import '../models/category.dart';
import '../models/faq.dart';
import '../models/menu.dart';
import '../models/page_model.dart';
import '../models/post.dart';
import '../models/question.dart';
import '../models/question_topic.dart';
import '../models/setting.dart';
import '../models/slide.dart';

class ApiService {
  final String activeLocale;

  ApiService({this.activeLocale = 'id'});

  Map<String, String> get _headers => {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Accept-Language': activeLocale,
        'X-Locale': activeLocale,
      };

  Uri _buildUri(String path, [Map<String, dynamic>? queryParameters]) {
    final query = <String, dynamic>{
      'locale': activeLocale,
      ...?queryParameters,
    };
    final urlString = '${ApiConfig.baseUrl}$path';
    final parsedUri = Uri.parse(urlString);

    return parsedUri.replace(
      queryParameters: {
        ...parsedUri.queryParameters,
        ...query.map((k, v) => MapEntry(k, v?.toString() ?? '')),
      },
    );
  }

  // --- Slides ---
  Future<List<SlideModel>> getSlides() async {
    final response = await http
        .get(_buildUri(ApiConfig.slides), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => SlideModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch slides: ${response.statusCode}');
  }

  // --- Menus ---
  Future<MenuModel?> getMenu(String location) async {
    final response = await http
        .get(_buildUri('${ApiConfig.menus}/$location'), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as Map<String, dynamic>?;
      return data != null ? MenuModel.fromJson(data) : null;
    }
    return null;
  }

  // --- Categories ---
  Future<List<CategoryModel>> getCategories() async {
    final response = await http
        .get(_buildUri(ApiConfig.categories), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => CategoryModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch categories: ${response.statusCode}');
  }

  Future<CategoryModel> getCategory(String slug) async {
    final response = await http
        .get(_buildUri('${ApiConfig.categories}/$slug'), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as Map<String, dynamic>;
      return CategoryModel.fromJson(data);
    }
    throw Exception('Category not found');
  }

  // --- Posts ---
  Future<List<PostModel>> getPosts({
    String? category,
    bool? featured,
    String? search,
    int perPage = 10,
    int page = 1,
  }) async {
    final query = <String, dynamic>{
      'per_page': perPage,
      'page': page,
    };
    if (category != null && category.isNotEmpty) {
      query['category'] = category;
    }
    if (featured == true) {
      query['featured'] = '1';
    }
    if (search != null && search.trim().isNotEmpty) {
      query['search'] = search.trim();
    }

    final response = await http
        .get(_buildUri(ApiConfig.posts, query), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => PostModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch posts: ${response.statusCode}');
  }

  Future<PostModel> getPost(String slug) async {
    final response = await http
        .get(_buildUri('${ApiConfig.posts}/$slug'), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as Map<String, dynamic>;
      return PostModel.fromJson(data);
    }
    throw Exception('Post not found');
  }

  // --- Static Pages ---
  Future<PageModel> getPage(String slug) async {
    final response = await http
        .get(_buildUri('${ApiConfig.pages}/$slug'), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as Map<String, dynamic>;
      return PageModel.fromJson(data);
    }
    throw Exception('Page not found');
  }

  // --- Settings ---
  Future<List<SettingModel>> getSettings() async {
    final response = await http
        .get(_buildUri(ApiConfig.settings), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => SettingModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch settings');
  }

  // --- Layanan Masyarakat: Topik Pertanyaan & FAQ ---
  Future<List<QuestionTopicModel>> getQuestionTopics() async {
    final response = await http
        .get(_buildUri(ApiConfig.questionTopics), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => QuestionTopicModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch question topics');
  }

  Future<List<FaqModel>> getFaqs({int? topicId}) async {
    final query = <String, dynamic>{};
    if (topicId != null) query['topic_id'] = topicId;

    final response = await http
        .get(_buildUri(ApiConfig.faqs, query), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => FaqModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch FAQs');
  }

  // --- Layanan Masyarakat: Pertanyaan Publik ---
  Future<List<QuestionModel>> getQuestions() async {
    final response = await http
        .get(_buildUri(ApiConfig.questions), headers: _headers)
        .timeout(const Duration(seconds: 15));

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body) as Map<String, dynamic>;
      final data = json['data'] as List<dynamic>? ?? [];
      return data.map((item) => QuestionModel.fromJson(item as Map<String, dynamic>)).toList();
    }
    throw Exception('Failed to fetch questions');
  }

  Future<bool> submitQuestion({
    required String name,
    required String email,
    String? phone,
    required String subject,
    required String body,
    int? topicId,
  }) async {
    final payload = <String, dynamic>{
      'name': name,
      'email': email,
      'subject': subject,
      'body': body,
    };
    if (phone != null && phone.isNotEmpty) payload['phone'] = phone;
    if (topicId != null) payload['topic_id'] = topicId;

    final response = await http
        .post(
          _buildUri(ApiConfig.questions),
          headers: _headers,
          body: jsonEncode(payload),
        )
        .timeout(const Duration(seconds: 15));

    return response.statusCode == 201 || response.statusCode == 200;
  }
}

