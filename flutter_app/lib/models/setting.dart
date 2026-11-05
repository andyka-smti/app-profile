class SettingModel {
  final int id;
  final String group;
  final String key;
  final dynamic value;
  final String type;

  SettingModel({
    required this.id,
    required this.group,
    required this.key,
    required this.value,
    this.type = 'text',
  });

  factory SettingModel.fromJson(Map<String, dynamic> json) {
    return SettingModel(
      id: json['id'] as int? ?? 0,
      group: json['group'] as String? ?? 'general',
      key: json['key'] as String? ?? '',
      value: json['value'],
      type: json['type'] as String? ?? 'text',
    );
  }
}

