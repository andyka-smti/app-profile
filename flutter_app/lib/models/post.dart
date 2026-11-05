import 'category.dart';

class AuthorModel {
  final int? id;
  final String? name;
  final String? avatar;

  AuthorModel({this.id, this.name, this.avatar});

  factory AuthorModel.fromJson(Map<String, dynamic>? json) {
    if (json == null) return AuthorModel();
    return AuthorModel(
      id: json['id'] as int?,
      name: json['name'] as String?,
      avatar: json['avatar'] as String?,
    );
  }
}

class PostModel {
  final int id;
  final String title;
  final String slug;
  final String? summary;
  final String content;
  final String? image;
  final String status;
  final DateTime? publishedAt;
  final int viewCount;
  final bool isFeatured;
  final CategoryModel? category;
  final AuthorModel? author;
  final DateTime? createdAt;

  PostModel({
    required this.id,
    required this.title,
    required this.slug,
    this.summary,
    required this.content,
    this.image,
    this.status = 'published',
    this.publishedAt,
    this.viewCount = 0,
    this.isFeatured = false,
    this.category,
    this.author,
    this.createdAt,
  });

  factory PostModel.fromJson(Map<String, dynamic> json) {
    return PostModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      summary: json['summary'] as String?,
      content: json['content'] as String? ?? '',
      image: json['image'] as String?,
      status: json['status'] as String? ?? 'published',
      publishedAt: json['published_at'] != null
          ? DateTime.tryParse(json['published_at'] as String)
          : null,
      viewCount: json['view_count'] as int? ?? 0,
      isFeatured: json['is_featured'] as bool? ?? false,
      category: json['category'] != null
          ? CategoryModel.fromJson(json['category'] as Map<String, dynamic>)
          : null,
      author: json['author'] != null
          ? AuthorModel.fromJson(json['author'] as Map<String, dynamic>)
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'] as String)
          : null,
    );
  }
}

