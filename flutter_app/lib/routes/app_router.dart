import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../l10n/app_localizations.dart';
import '../screens/ask_question_screen.dart';
import '../screens/category_posts_screen.dart';
import '../screens/faqs_screen.dart';
import '../screens/home_screen.dart';
import '../screens/page_detail_screen.dart';
import '../screens/post_detail_screen.dart';
import '../screens/posts_screen.dart';
import '../screens/questions_screen.dart';
import '../screens/settings_screen.dart';

final GlobalKey<NavigatorState> _rootNavigatorKey =
    GlobalKey<NavigatorState>(debugLabel: 'root');

class AppRouter {
  static final GoRouter router = GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: '/',
    routes: [
      StatefulShellRoute.indexedStack(
        builder: (context, state, navigationShell) {
          return ScaffoldWithNestedNavigation(
            navigationShell: navigationShell,
          );
        },
        branches: [
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/',
                builder: (context, state) => const HomeScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/posts',
                builder: (context, state) => const PostsScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/faqs',
                builder: (context, state) => const FaqsScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/questions',
                builder: (context, state) => const QuestionsScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/settings',
                builder: (context, state) => const SettingsScreen(),
              ),
            ],
          ),
        ],
      ),
      // Standalone Detail and Create Routes
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/posts/:slug',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return PostDetailScreen(slug: slug);
        },
      ),
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/pages/:slug',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return PageDetailScreen(slug: slug);
        },
      ),
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/categories/:slug',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return CategoryPostsScreen(slug: slug);
        },
      ),
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/questions/create',
        builder: (context, state) => const AskQuestionScreen(),
      ),
    ],
  );
}

class ScaffoldWithNestedNavigation extends StatelessWidget {
  final StatefulNavigationShell navigationShell;

  const ScaffoldWithNestedNavigation({
    super.key,
    required this.navigationShell,
  });

  void _onTap(BuildContext context, int index) {
    navigationShell.goBranch(
      index,
      initialLocation: index == navigationShell.currentIndex,
    );
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    return Scaffold(
      body: navigationShell,
      bottomNavigationBar: NavigationBar(
        selectedIndex: navigationShell.currentIndex,
        onDestinationSelected: (index) => _onTap(context, index),
        indicatorColor: Colors.amber.shade200,
        destinations: [
          NavigationDestination(
            icon: const Icon(Icons.home_outlined),
            selectedIcon: const Icon(Icons.home),
            label: l10n.home,
          ),
          NavigationDestination(
            icon: const Icon(Icons.article_outlined),
            selectedIcon: const Icon(Icons.article),
            label: l10n.posts,
          ),
          NavigationDestination(
            icon: const Icon(Icons.help_outline),
            selectedIcon: const Icon(Icons.help),
            label: l10n.faqs,
          ),
          NavigationDestination(
            icon: const Icon(Icons.question_answer_outlined),
            selectedIcon: const Icon(Icons.question_answer),
            label: l10n.questions,
          ),
          NavigationDestination(
            icon: const Icon(Icons.settings_outlined),
            selectedIcon: const Icon(Icons.settings),
            label: l10n.settings,
          ),
        ],
      ),
    );
  }
}

