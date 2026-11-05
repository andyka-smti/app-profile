import 'package:flutter/material.dart';
import '../models/question.dart';
import '../models/question_topic.dart';
import '../services/api_service.dart';

class QuestionProvider extends ChangeNotifier {
  String _currentLocale = 'id';
  late ApiService _api;

  QuestionProvider({String initialLocale = 'id'}) {
    _currentLocale = initialLocale;
    _api = ApiService(activeLocale: initialLocale);
    loadInitialData();
  }

  void updateLocale(String newLocale) {
    if (_currentLocale == newLocale) return;
    _currentLocale = newLocale;
    _api = ApiService(activeLocale: newLocale);
    loadInitialData();
  }

  bool _isLoading = false;
  String? _error;
  List<QuestionTopicModel> _topics = [];
  List<QuestionModel> _questions = [];

  bool get isLoading => _isLoading;
  String? get error => _error;
  List<QuestionTopicModel> get topics => _topics;
  List<QuestionModel> get questions => _questions;

  Future<void> loadInitialData() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final results = await Future.wait([
        _api.getQuestionTopics().catchError((_) => <QuestionTopicModel>[]),
        _api.getQuestions().catchError((_) => <QuestionModel>[]),
      ]);

      _topics = results[0] as List<QuestionTopicModel>;
      _questions = results[1] as List<QuestionModel>;
      _error = null;
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> submitQuestion({
    required String name,
    required String email,
    String? phone,
    required String subject,
    required String body,
    int? topicId,
  }) async {
    try {
      final success = await _api.submitQuestion(
        name: name,
        email: email,
        phone: phone,
        subject: subject,
        body: body,
        topicId: topicId,
      );
      if (success) {
        // Refresh question list
        loadInitialData();
      }
      return success;
    } catch (_) {
      return false;
    }
  }
}

