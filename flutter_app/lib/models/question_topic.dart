class QuestionTopicModel {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? icon;
  final int sortOrder;
  final int? questionsCount;
  final int? faqsCount;

  QuestionTopicModel({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.icon,
    this.sortOrder = 0,
    this.questionsCount,
    this.faqsCount,
  });

  factory QuestionTopicModel.fromJson(Map<String, dynamic> json) {
    return QuestionTopicModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      description: json['description'] as String?,
      icon: json['icon'] as String?,
      sortOrder: json['sort_order'] as int? ?? 0,
      questionsCount: json['questions_count'] as int?,
      faqsCount: json['faqs_count'] as int?,
    );
  }
}

