import 'package:flutter/material.dart';
import '../models/category.dart';
import '../models/faq.dart';
import '../models/menu.dart';
import '../models/page_model.dart';
import '../models/post.dart';
import '../models/setting.dart';
import '../models/slide.dart';
import '../services/api_service.dart';

class PortalProvider extends ChangeNotifier {
  String _currentLocale = 'id';
  late ApiService _api;

  PortalProvider({String initialLocale = 'id'}) {
    _currentLocale = initialLocale;
    _api = ApiService(activeLocale: initialLocale);
    loadHomeData();
  }

  void updateLocale(String newLocale) {
    if (_currentLocale == newLocale) return;
    _currentLocale = newLocale;
    _api = ApiService(activeLocale: newLocale);
    loadHomeData();
    loadFaqs();
  }

  // State: Home Feed
  bool _isLoadingHome = false;
  String? _homeError;
  List<SlideModel> _slides = [];
  List<PostModel> _featuredPosts = [];
  List<PostModel> _latestPosts = [];
  List<CategoryModel> _categories = [];
  MenuModel? _headerMenu;
  Map<String, String> _settings = {};

  bool get isLoadingHome => _isLoadingHome;
  String? get homeError => _homeError;
  List<SlideModel> get slides => _slides;
  List<PostModel> get featuredPosts => _featuredPosts;
  List<PostModel> get latestPosts => _latestPosts;
  List<CategoryModel> get categories => _categories;
  MenuModel? get headerMenu => _headerMenu;
  Map<String, String> get settings => _settings;

  // State: All Posts Feed
  bool _isLoadingPosts = false;
  String? _postsError;
  List<PostModel> _allPosts = [];
  String? _selectedCategorySlug;
  String _searchQuery = '';

  bool get isLoadingPosts => _isLoadingPosts;
  String? get postsError => _postsError;
  List<PostModel> get allPosts => _allPosts;
  String? get selectedCategorySlug => _selectedCategorySlug;
  String get searchQuery => _searchQuery;

  // State: FAQs
  bool _isLoadingFaqs = false;
  String? _faqsError;
  List<FaqModel> _faqs = [];

  bool get isLoadingFaqs => _isLoadingFaqs;
  String? get faqsError => _faqsError;
  List<FaqModel> get faqs => _faqs;

  Future<void> loadHomeData() async {
    _isLoadingHome = true;
    _homeError = null;
    notifyListeners();

    try {
      final results = await Future.wait([
        _api.getSlides().catchError((_) => <SlideModel>[]),
        _api.getPosts(featured: true, perPage: 5).catchError((_) => <PostModel>[]),
        _api.getPosts(perPage: 6).catchError((_) => <PostModel>[]),
        _api.getCategories().catchError((_) => <CategoryModel>[]),
        _api.getMenu('header').catchError((_) => null),
        _api.getSettings().catchError((_) => <SettingModel>[]),
      ]);

      _slides = results[0] as List<SlideModel>;
      _featuredPosts = results[1] as List<PostModel>;
      _latestPosts = results[2] as List<PostModel>;
      _categories = results[3] as List<CategoryModel>;
      _headerMenu = results[4] as MenuModel?;

      final settingsList = results[5] as List<SettingModel>;
      _settings = {
        for (var s in settingsList) s.key: s.value?.toString() ?? '',
      };

      _homeError = null;
    } catch (e) {
      _homeError = e.toString();
    } finally {
      _isLoadingHome = false;
      notifyListeners();
    }
  }

  Future<void> loadPosts({String? categorySlug, String? search}) async {
    _isLoadingPosts = true;
    _postsError = null;
    _selectedCategorySlug = categorySlug;
    if (search != null) _searchQuery = search;
    notifyListeners();

    try {
      _allPosts = await _api.getPosts(
        category: _selectedCategorySlug,
        search: _searchQuery,
        perPage: 20,
      );
      _postsError = null;
    } catch (e) {
      _postsError = e.toString();
    } finally {
      _isLoadingPosts = false;
      notifyListeners();
    }
  }

  Future<void> loadFaqs({int? topicId}) async {
    _isLoadingFaqs = true;
    _faqsError = null;
    notifyListeners();

    try {
      _faqs = await _api.getFaqs(topicId: topicId);
      _faqsError = null;
    } catch (e) {
      _faqsError = e.toString();
    } finally {
      _isLoadingFaqs = false;
      notifyListeners();
    }
  }

  Future<PostModel?> getPostDetail(String slug) async {
    try {
      return await _api.getPost(slug);
    } catch (_) {
      return null;
    }
  }

  Future<PageModel?> getPageDetail(String slug) async {
    try {
      return await _api.getPage(slug);
    } catch (_) {
      return null;
    }
  }
}

