import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../l10n/app_localizations.dart';
import '../l10n/locale_provider.dart';
import '../providers/portal_provider.dart';
import 'language_switch_dialog.dart';

class AppDrawer extends StatelessWidget {
  const AppDrawer({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final portal = context.watch<PortalProvider>();
    final localeProv = context.watch<LocaleProvider>();

    final siteName = portal.settings['site_name'] ?? l10n.appTitle;
    final siteDescription = portal.settings['site_description'] ?? '';

    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [
                  Colors.amber.shade700,
                  Colors.amber.shade900,
                ],
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                const CircleAvatar(
                  radius: 28,
                  backgroundColor: Colors.white,
                  child: Icon(Icons.account_balance, color: Colors.amber, size: 30),
                ),
                const SizedBox(height: 10),
                Text(
                  siteName,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                if (siteDescription.isNotEmpty)
                  Text(
                    siteDescription,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: TextStyle(
                      color: Colors.white.withValues(alpha: 0.85),
                      fontSize: 12,
                    ),
                  ),
              ],
            ),
          ),
          ListTile(
            leading: const Icon(Icons.home_outlined),
            title: Text(l10n.home),
            onTap: () {
              Navigator.pop(context);
              context.go('/');
            },
          ),
          ListTile(
            leading: const Icon(Icons.article_outlined),
            title: Text(l10n.posts),
            onTap: () {
              Navigator.pop(context);
              context.go('/posts');
            },
          ),
          ListTile(
            leading: const Icon(Icons.help_outline),
            title: Text(l10n.faqs),
            onTap: () {
              Navigator.pop(context);
              context.go('/faqs');
            },
          ),
          ListTile(
            leading: const Icon(Icons.question_answer_outlined),
            title: Text(l10n.questions),
            onTap: () {
              Navigator.pop(context);
              context.go('/questions');
            },
          ),
          const Divider(),
          // Information Pages section
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            child: Text(
              l10n.staticPages,
              style: TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: Colors.grey.shade600,
              ),
            ),
          ),
          ListTile(
            dense: true,
            leading: const Icon(Icons.info_outline, size: 20),
            title: const Text('Tentang Kami / About Us'),
            onTap: () {
              Navigator.pop(context);
              context.push('/pages/tentang-kami');
            },
          ),
          ListTile(
            dense: true,
            leading: const Icon(Icons.flag_outlined, size: 20),
            title: const Text('Visi & Misi / Vision & Mission'),
            onTap: () {
              Navigator.pop(context);
              context.push('/pages/visi-dan-misi');
            },
          ),
          ListTile(
            dense: true,
            leading: const Icon(Icons.account_tree_outlined, size: 20),
            title: const Text('Struktur Organisasi'),
            onTap: () {
              Navigator.pop(context);
              context.push('/pages/struktur-organisasi');
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.language),
            title: Text(l10n.language),
            trailing: Chip(
              label: Text(
                localeProv.isIndonesian ? '🇮🇩 ID' : '🇬🇧 EN',
                style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold),
              ),
              backgroundColor: Colors.amber.shade100,
            ),
            onTap: () {
              Navigator.pop(context);
              LanguageSwitchDialog.show(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.settings_outlined),
            title: Text(l10n.settings),
            onTap: () {
              Navigator.pop(context);
              context.push('/settings');
            },
          ),
        ],
      ),
    );
  }
}
