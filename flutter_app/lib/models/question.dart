import 'question_topic.dart';

class QuestionAnswerModel {
  final int id;
  final String body;
  final String? officerName;
  final DateTime? answeredAt;

  QuestionAnswerModel({
    required this.id,
    required this.body,
    this.officerName,
    this.answeredAt,
  });

  factory QuestionAnswerModel.fromJson(Map<String, dynamic> json) {
    return QuestionAnswerModel(
      id: json['id'] as int? ?? 0,
      body: json['body'] as String? ?? '',
      officerName: json['officer_name'] as String?,
      answeredAt: json['answered_at'] != null
          ? DateTime.tryParse(json['answered_at'] as String)
          : null,
    );
  }
}

class QuestionModel {
  final int id;
  final String name;
  final String subject;
  final String body;
  final String status;
  final QuestionTopicModel? topic;
  final QuestionAnswerModel? answer;
  final DateTime? createdAt;

  QuestionModel({
    required this.id,
    required this.name,
    required this.subject,
    required this.body,
    this.status = 'pending',
    this.topic,
    this.answer,
    this.createdAt,
  });

  factory QuestionModel.fromJson(Map<String, dynamic> json) {
    return QuestionModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      subject: json['subject'] as String? ?? '',
      body: json['body'] as String? ?? '',
      status: json['status'] as String? ?? 'pending',
      topic: json['topic'] != null
          ? QuestionTopicModel.fromJson(json['topic'] as Map<String, dynamic>)
          : null,
      answer: json['answer'] != null
          ? QuestionAnswerModel.fromJson(json['answer'] as Map<String, dynamic>)
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'] as String)
          : null,
    );
  }
}

