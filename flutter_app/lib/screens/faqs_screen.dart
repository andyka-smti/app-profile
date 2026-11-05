import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:provider/provider.dart';
import '../l10n/app_localizations.dart';
import '../providers/portal_provider.dart';
import '../widgets/error_retry_view.dart';
import '../widgets/loading_view.dart';

class FaqsScreen extends StatefulWidget {
  const FaqsScreen({super.key});

  @override
  State<FaqsScreen> createState() => _FaqsScreenState();
}

class _FaqsScreenState extends State<FaqsScreen> {
  final TextEditingController _searchCtrl = TextEditingController();
  String _filter = '';

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<PortalProvider>().loadFaqs();
    });
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final portal = context.watch<PortalProvider>();

    final filteredFaqs = portal.faqs.where((faq) {
      if (_filter.isEmpty) return true;
      final q = _filter.toLowerCase();
      return faq.question.toLowerCase().contains(q) ||
          faq.answer.toLowerCase().contains(q);
    }).toList();

    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.faqs),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
            child: TextField(
              controller: _searchCtrl,
              decoration: InputDecoration(
                hintText: l10n.search,
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchCtrl.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchCtrl.clear();
                          setState(() => _filter = '');
                        },
                      )
                    : null,
                filled: true,
                fillColor: Colors.grey.shade100,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
              ),
              onChanged: (val) => setState(() => _filter = val),
            ),
          ),
          Expanded(
            child: portal.isLoadingFaqs && portal.faqs.isEmpty
                ? const LoadingView()
                : portal.faqsError != null && portal.faqs.isEmpty
                    ? ErrorRetryView(
                        message: portal.faqsError,
                        onRetry: () => portal.loadFaqs(),
                      )
                    : filteredFaqs.isEmpty
                        ? Center(
                            child: Text(
                              l10n.noData,
                              style: TextStyle(color: Colors.grey.shade500),
                            ),
                          )
                        : RefreshIndicator(
                            color: Colors.amber.shade700,
                            onRefresh: () => portal.loadFaqs(),
                            child: ListView.builder(
                              padding: const EdgeInsets.symmetric(horizontal: 16),
                              itemCount: filteredFaqs.length,
                              itemBuilder: (context, index) {
                                final faq = filteredFaqs[index];
                                return Card(
                                  margin: const EdgeInsets.only(bottom: 10),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(12),
                                    side: BorderSide(color: Colors.grey.shade200),
                                  ),
                                  elevation: 0,
                                  color: Colors.white,
                                  child: ExpansionTile(
                                    leading: CircleAvatar(
                                      radius: 16,
                                      backgroundColor: Colors.amber.shade100,
                                      child: Text(
                                        'Q',
                                        style: TextStyle(
                                          fontWeight: FontWeight.bold,
                                          color: Colors.amber.shade900,
                                        ),
                                      ),
                                    ),
                                    title: Text(
                                      faq.question,
                                      style: const TextStyle(
                                        fontSize: 14,
                                        fontWeight: FontWeight.w600,
                                      ),
                                    ),
                                    childrenPadding:
                                        const EdgeInsets.fromLTRB(16, 0, 16, 16),
                                    children: [
                                      const Divider(height: 1),
                                      const SizedBox(height: 10),
                                      HtmlWidget(
                                        faq.answer,
                                        textStyle: TextStyle(
                                          color: Colors.grey.shade800,
                                          fontSize: 13,
                                          height: 1.5,
                                        ),
                                      ),
                                    ],
                                  ),
                                );
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }
}

