import '../config/api_config.dart';

class PageModel {
  final int id;
  final String title;
  final String slug;
  final String content;
  final String? image;
  final int sortOrder;
  final bool showInMenu;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  PageModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.content,
    this.image,
    this.sortOrder = 0,
    this.showInMenu = false,
    this.createdAt,
    this.updatedAt,
  });

  factory PageModel.fromJson(Map<String, dynamic> json) {
    return PageModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      content: json['content'] as String? ?? '',
      image: ApiConfig.resolveImageUrl(json['image'] as String?),
      sortOrder: json['sort_order'] as int? ?? 0,
      showInMenu: json['show_in_menu'] as bool? ?? false,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.tryParse(json['updated_at'] as String)
          : null,
    );
  }
}

