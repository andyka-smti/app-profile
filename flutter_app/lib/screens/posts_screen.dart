import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../l10n/app_localizations.dart';
import '../providers/portal_provider.dart';
import '../widgets/error_retry_view.dart';
import '../widgets/loading_view.dart';
import '../widgets/post_card.dart';

class PostsScreen extends StatefulWidget {
  final String? initialCategorySlug;

  const PostsScreen({super.key, this.initialCategorySlug});

  @override
  State<PostsScreen> createState() => _PostsScreenState();
}

class _PostsScreenState extends State<PostsScreen> {
  final TextEditingController _searchController = TextEditingController();
  String? _selectedCategory;

  @override
  void initState() {
    super.initState();
    _selectedCategory = widget.initialCategorySlug;
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<PortalProvider>().loadPosts(
            categorySlug: _selectedCategory,
          );
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _onSearch(String query) {
    context.read<PortalProvider>().loadPosts(
          categorySlug: _selectedCategory,
          search: query,
        );
  }

  void _selectCategory(String? slug) {
    setState(() => _selectedCategory = slug);
    context.read<PortalProvider>().loadPosts(
          categorySlug: slug,
          search: _searchController.text,
        );
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final portal = context.watch<PortalProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.posts),
      ),
      body: Column(
        children: [
          // Search Input
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 8),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: l10n.searchPosts,
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          _onSearch('');
                        },
                      )
                    : null,
                filled: true,
                fillColor: Colors.grey.shade100,
                contentPadding: const EdgeInsets.symmetric(horizontal: 16),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
              ),
              onSubmitted: _onSearch,
            ),
          ),

          // Categories Filter Tabs
          if (portal.categories.isNotEmpty)
            SizedBox(
              height: 44,
              child: ListView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                children: [
                  Padding(
                    padding: const EdgeInsets.only(right: 8.0),
                    child: ChoiceChip(
                      label: Text(l10n.allCategories),
                      selected: _selectedCategory == null,
                      onSelected: (_) => _selectCategory(null),
                      selectedColor: Colors.amber.shade200,
                    ),
                  ),
                  ...portal.categories.map((cat) {
                    final isSelected = _selectedCategory == cat.slug;
                    return Padding(
                      padding: const EdgeInsets.only(right: 8.0),
                      child: ChoiceChip(
                        label: Text(cat.name),
                        selected: isSelected,
                        onSelected: (_) => _selectCategory(cat.slug),
                        selectedColor: Colors.amber.shade200,
                      ),
                    );
                  }),
                ],
              ),
            ),

          const SizedBox(height: 6),

          // Posts List
          Expanded(
            child: portal.isLoadingPosts && portal.allPosts.isEmpty
                ? const LoadingView()
                : portal.postsError != null && portal.allPosts.isEmpty
                    ? ErrorRetryView(
                        message: portal.postsError,
                        onRetry: () => portal.loadPosts(
                          categorySlug: _selectedCategory,
                          search: _searchController.text,
                        ),
                      )
                    : portal.allPosts.isEmpty
                        ? Center(
                            child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Icon(Icons.article_outlined,
                                    size: 56, color: Colors.grey.shade400),
                                const SizedBox(height: 12),
                                Text(
                                  l10n.noPosts,
                                  style: TextStyle(color: Colors.grey.shade600),
                                ),
                              ],
                            ),
                          )
                        : RefreshIndicator(
                            color: Colors.amber.shade700,
                            onRefresh: () => portal.loadPosts(
                              categorySlug: _selectedCategory,
                              search: _searchController.text,
                            ),
                            child: ListView.builder(
                              itemCount: portal.allPosts.length,
                              itemBuilder: (context, index) {
                                return PostCard(post: portal.allPosts[index]);
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }
}

