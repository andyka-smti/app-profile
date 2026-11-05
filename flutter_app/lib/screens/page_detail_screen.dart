import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:provider/provider.dart';
import '../models/page_model.dart';
import '../providers/portal_provider.dart';
import '../widgets/error_retry_view.dart';
import '../widgets/loading_view.dart';

class PageDetailScreen extends StatefulWidget {
  final String slug;

  const PageDetailScreen({super.key, required this.slug});

  @override
  State<PageDetailScreen> createState() => _PageDetailScreenState();
}

class _PageDetailScreenState extends State<PageDetailScreen> {
  PageModel? _page;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchPage();
  }

  Future<void> _fetchPage() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    final page = await context.read<PortalProvider>().getPageDetail(widget.slug);
    if (!mounted) return;

    if (page != null) {
      setState(() {
        _page = page;
        _isLoading = false;
      });
    } else {
      setState(() {
        _error = 'Page not found';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(),
        body: const LoadingView(),
      );
    }

    if (_error != null || _page == null) {
      return Scaffold(
        appBar: AppBar(),
        body: ErrorRetryView(
          message: _error,
          onRetry: _fetchPage,
        ),
      );
    }

    final page = _page!;

    return Scaffold(
      appBar: AppBar(
        title: Text(page.title),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (page.image != null && page.image!.isNotEmpty) ...[
              ClipRRect(
                borderRadius: BorderRadius.circular(14),
                child: Image.network(
                  page.image!,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) =>
                      const SizedBox.shrink(),
                ),
              ),
              const SizedBox(height: 16),
            ],
            Text(
              page.title,
              style: const TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
                height: 1.3,
              ),
            ),
            const SizedBox(height: 16),
            HtmlWidget(
              page.content,
              textStyle: const TextStyle(fontSize: 15, height: 1.6),
            ),
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }
}
