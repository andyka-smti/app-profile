import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../l10n/app_localizations.dart';
import '../l10n/locale_provider.dart';
import '../providers/portal_provider.dart';
import '../widgets/app_drawer.dart';
import '../widgets/error_retry_view.dart';
import '../widgets/language_switch_dialog.dart';
import '../widgets/loading_view.dart';
import '../widgets/post_card.dart';
import '../widgets/slide_carousel.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final portal = context.watch<PortalProvider>();
    final localeProv = context.watch<LocaleProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(
          portal.settings['site_name'] ?? l10n.appTitle,
          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 17),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
            tooltip: l10n.search,
            onPressed: () => context.push('/posts'),
          ),
          TextButton(
            onPressed: () => LanguageSwitchDialog.show(context),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  localeProv.isIndonesian ? '🇮🇩' : '🇬🇧',
                  style: const TextStyle(fontSize: 16),
                ),
                const SizedBox(width: 4),
                Text(
                  localeProv.languageCode.toUpperCase(),
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    color: Colors.amber.shade900,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
      drawer: const AppDrawer(),
      body: portal.isLoadingHome && portal.slides.isEmpty
          ? const LoadingView()
          : portal.homeError != null && portal.slides.isEmpty
              ? ErrorRetryView(
                  message: portal.homeError,
                  onRetry: () => portal.loadHomeData(),
                )
              : RefreshIndicator(
                  color: Colors.amber.shade700,
                  onRefresh: () => portal.loadHomeData(),
                  child: ListView(
                    padding: const EdgeInsets.only(bottom: 24),
                    children: [
                      // 1. Slides Carousel
                      if (portal.slides.isNotEmpty) ...[
                        const SizedBox(height: 8),
                        SlideCarousel(slides: portal.slides),
                        const SizedBox(height: 12),
                      ],

                      // 2. Quick Menu / Categories Bar
                      _buildQuickCategories(context, portal, l10n),

                      // 3. Featured Posts Carousel
                      if (portal.featuredPosts.isNotEmpty) ...[
                        Padding(
                          padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
                          child: Row(
                            children: [
                              Icon(Icons.star_rounded,
                                  color: Colors.amber.shade700, size: 22),
                              const SizedBox(width: 6),
                              Text(
                                l10n.featuredNews,
                                style: const TextStyle(
                                  fontSize: 17,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                        ),
                        SizedBox(
                          height: 240,
                          child: ListView.builder(
                            scrollDirection: Axis.horizontal,
                            padding: const EdgeInsets.symmetric(horizontal: 16),
                            itemCount: portal.featuredPosts.length,
                            itemBuilder: (context, index) {
                              return PostCard(
                                post: portal.featuredPosts[index],
                                isHorizontal: true,
                              );
                            },
                          ),
                        ),
                      ],

                      // 4. Latest Posts Section
                      Padding(
                        padding: const EdgeInsets.fromLTRB(16, 20, 16, 8),
                        child: Row(
                          children: [
                            Icon(Icons.newspaper_rounded,
                                color: Colors.amber.shade700, size: 20),
                            const SizedBox(width: 6),
                            Text(
                              l10n.latestNews,
                              style: const TextStyle(
                                fontSize: 17,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const Spacer(),
                            TextButton(
                              onPressed: () => context.push('/posts'),
                              child: Text(
                                l10n.viewAll,
                                style: TextStyle(
                                  color: Colors.amber.shade800,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      if (portal.latestPosts.isEmpty)
                        Padding(
                          padding: const EdgeInsets.all(24.0),
                          child: Center(
                            child: Text(
                              l10n.noPosts,
                              style: TextStyle(color: Colors.grey.shade500),
                            ),
                          ),
                        )
                      else
                        ...portal.latestPosts
                            .map((post) => PostCard(post: post)),

                      // 5. Ask Question Call to Action Card
                      _buildAskCard(context, l10n),
                    ],
                  ),
                ),
    );
  }

  Widget _buildQuickCategories(
      BuildContext context, PortalProvider portal, AppLocalizations l10n) {
    if (portal.categories.isEmpty) return const SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 4.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Text(
                l10n.categories,
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                  color: Colors.grey.shade600,
                ),
              ),
              const Spacer(),
              InkWell(
                onTap: () => context.push('/posts'),
                child: Text(
                  l10n.allCategories,
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.amber.shade800,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: portal.categories.map((cat) {
                return Padding(
                  padding: const EdgeInsets.only(right: 8.0),
                  child: ActionChip(
                    avatar: Icon(Icons.tag, size: 14, color: Colors.amber.shade800),
                    label: Text(
                      cat.name,
                      style: const TextStyle(fontSize: 12),
                    ),
                    backgroundColor: Colors.white,
                    side: BorderSide(color: Colors.amber.shade200),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(20),
                    ),
                    onPressed: () {
                      context.push('/categories/${cat.slug}');
                    },
                  ),
                );
              }).toList(),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAskCard(BuildContext context, AppLocalizations l10n) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [Colors.amber.shade700, Colors.amber.shade900],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.amber.shade900.withValues(alpha: 0.2),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  l10n.askQuestion,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  l10n.askQuestionDesc,
                  style: TextStyle(
                    color: Colors.white.withValues(alpha: 0.9),
                    fontSize: 12,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 12),
          ElevatedButton(
            onPressed: () => context.push('/questions/create'),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.white,
              foregroundColor: Colors.amber.shade900,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(20),
              ),
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
            ),
            child: Text(
              l10n.submitQuestion,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
            ),
          ),
        ],
      ),
    );
  }
}
