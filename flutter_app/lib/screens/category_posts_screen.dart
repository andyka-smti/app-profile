import 'package:flutter/material.dart';
import 'posts_screen.dart';

class CategoryPostsScreen extends StatelessWidget {
  final String slug;

  const CategoryPostsScreen({super.key, required this.slug});

  @override
  Widget build(BuildContext context) {
    return PostsScreen(initialCategorySlug: slug);
  }
}

