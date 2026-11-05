import 'question_topic.dart';

class FaqModel {
  final int id;
  final int? topicId;
  final QuestionTopicModel? topic;
  final String question;
  final String answer;
  final int sortOrder;

  FaqModel({
    required this.id,
    this.topicId,
    this.topic,
    required this.question,
    required this.answer,
    this.sortOrder = 0,
  });

  factory FaqModel.fromJson(Map<String, dynamic> json) {
    return FaqModel(
      id: json['id'] as int? ?? 0,
      topicId: json['topic_id'] as int?,
      topic: json['topic'] != null
          ? QuestionTopicModel.fromJson(json['topic'] as Map<String, dynamic>)
          : null,
      question: json['question'] as String? ?? '',
      answer: json['answer'] as String? ?? '',
      sortOrder: json['sort_order'] as int? ?? 0,
    );
  }
}

