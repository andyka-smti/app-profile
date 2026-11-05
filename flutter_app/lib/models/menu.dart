class MenuItemModel {
  final int id;
  final String label;
  final String? url;
  final String type;
  final int sortOrder;
  final List<MenuItemModel> children;

  MenuItemModel({
    required this.id,
    required this.label,
    this.url,
    this.type = 'custom',
    this.sortOrder = 0,
    this.children = const [],
  });

  factory MenuItemModel.fromJson(Map<String, dynamic> json) {
    final rawChildren = json['children'] as List<dynamic>? ?? [];
    return MenuItemModel(
      id: json['id'] as int? ?? 0,
      label: json['label'] as String? ?? '',
      url: json['url'] as String?,
      type: json['type'] as String? ?? 'custom',
      sortOrder: json['sort_order'] as int? ?? 0,
      children: rawChildren
          .map((c) => MenuItemModel.fromJson(c as Map<String, dynamic>))
          .toList(),
    );
  }
}

class MenuModel {
  final int id;
  final String name;
  final String location;
  final List<MenuItemModel> items;

  MenuModel({
    required this.id,
    required this.name,
    required this.location,
    this.items = const [],
  });

  factory MenuModel.fromJson(Map<String, dynamic> json) {
    final rawItems = json['items'] as List<dynamic>? ?? [];
    return MenuModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      location: json['location'] as String? ?? '',
      items: rawItems
          .map((i) => MenuItemModel.fromJson(i as Map<String, dynamic>))
          .toList(),
    );
  }
}

