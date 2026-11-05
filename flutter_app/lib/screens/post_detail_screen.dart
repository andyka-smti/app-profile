import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../config/api_config.dart';
import '../l10n/app_localizations.dart';
import '../models/post.dart';
import '../providers/portal_provider.dart';
import '../widgets/error_retry_view.dart';
import '../widgets/loading_view.dart';

class PostDetailScreen extends StatefulWidget {
  final String slug;

  const PostDetailScreen({super.key, required this.slug});

  @override
  State<PostDetailScreen> createState() => _PostDetailScreenState();
}

class _PostDetailScreenState extends State<PostDetailScreen> {
  PostModel? _post;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchDetail();
  }

  Future<void> _fetchDetail() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    final post = await context.read<PortalProvider>().getPostDetail(widget.slug);
    if (!mounted) return;

    if (post != null) {
      setState(() {
        _post = post;
        _isLoading = false;
      });
    } else {
      setState(() {
        _error = 'Article not found';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(),
        body: const LoadingView(),
      );
    }

    if (_error != null || _post == null) {
      return Scaffold(
        appBar: AppBar(),
        body: ErrorRetryView(
          message: _error,
          onRetry: _fetchDetail,
        ),
      );
    }

    final post = _post!;

    return Scaffold(
      appBar: AppBar(
        title: Text(
          post.category?.name ?? l10n.posts,
          style: const TextStyle(fontSize: 16),
        ),
      ),
      body: Scrollbar(
        thumbVisibility: true,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(
            parent: BouncingScrollPhysics(),
          ),
          padding: const EdgeInsets.all(16.0),
          child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Category & Date Header
            Row(
              children: [
                if (post.category != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.amber.shade100,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      post.category!.name,
                      style: TextStyle(
                        color: Colors.amber.shade900,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                const Spacer(),
                if (post.publishedAt != null)
                  Text(
                    DateFormat('EEEE, dd MMMM yyyy').format(post.publishedAt!),
                    style: TextStyle(color: Colors.grey.shade600, fontSize: 12),
                  ),
              ],
            ),
            const SizedBox(height: 12),

            // Title
            Text(
              post.title,
              style: const TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
                height: 1.3,
              ),
            ),
            const SizedBox(height: 10),

            // Author and views meta
            Row(
              children: [
                if (post.author?.name != null) ...[
                  CircleAvatar(
                    radius: 14,
                    backgroundColor: Colors.amber.shade200,
                    child: Text(
                      post.author!.name![0].toUpperCase(),
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: Colors.grey.shade800,
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Text(
                    post.author!.name!,
                    style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                  ),
                  const SizedBox(width: 14),
                ],
                Icon(Icons.visibility_outlined, size: 16, color: Colors.grey.shade600),
                const SizedBox(width: 4),
                Text(
                  '${post.viewCount} ${l10n.views}',
                  style: TextStyle(fontSize: 12, color: Colors.grey.shade600),
                ),
              ],
            ),
            const SizedBox(height: 16),

            // Featured Image
            Builder(
              builder: (context) {
                final imageUrl = ApiConfig.resolveImageUrl(post.image);
                if (imageUrl == null || imageUrl.isEmpty) {
                  return const SizedBox.shrink();
                }
                return Padding(
                  padding: const EdgeInsets.only(bottom: 16.0),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(14),
                    child: Image.network(
                      imageUrl,
                      width: double.infinity,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) =>
                          const SizedBox.shrink(),
                    ),
                  ),
                );
              },
            ),

            // Summary Quote (if available)
            if (post.summary != null && post.summary!.isNotEmpty) ...[
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: Colors.amber.shade50,
                  borderRadius: BorderRadius.circular(10),
                  border: Border(
                    left: BorderSide(color: Colors.amber.shade800, width: 4),
                  ),
                ),
                child: Text(
                  post.summary!,
                  style: TextStyle(
                    fontSize: 14,
                    fontStyle: FontStyle.italic,
                    color: Colors.grey.shade800,
                    height: 1.4,
                  ),
                ),
              ),
              const SizedBox(height: 16),
            ],

            // Full Article HTML Content
            HtmlWidget(
              post.content,
              textStyle: const TextStyle(fontSize: 15, height: 1.6),
            ),
            const SizedBox(height: 32),
          ],
        ),
      ),
    ),
  );
  }
}
