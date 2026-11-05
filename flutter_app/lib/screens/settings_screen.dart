import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/api_config.dart';
import '../l10n/app_localizations.dart';
import '../l10n/locale_provider.dart';
import '../providers/portal_provider.dart';
import '../widgets/language_switch_dialog.dart';

class SettingsScreen extends StatelessWidget {
  const SettingsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final localeProv = context.watch<LocaleProvider>();
    final portal = context.watch<PortalProvider>();

    final siteName = portal.settings['site_name'] ?? l10n.appTitle;
    final address = portal.settings['contact_address'] ?? 'Jl. Pendidikan No. 1';
    final phone = portal.settings['contact_phone'] ?? '(021) 1234567';
    final email = portal.settings['contact_email'] ?? 'info@sekolah.sch.id';

    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.settings),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16.0),
        children: [
          // Language Section
          Text(
            l10n.language,
            style: TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.bold,
              color: Colors.grey.shade600,
            ),
          ),
          const SizedBox(height: 8),
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: ListTile(
              leading: const Icon(Icons.language, color: Colors.amber),
              title: Text(l10n.language),
              subtitle: Text(
                localeProv.isIndonesian ? l10n.indonesian : l10n.english,
              ),
              trailing: Chip(
                label: Text(
                  localeProv.isIndonesian ? '🇮🇩 ID' : '🇬🇧 EN',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                backgroundColor: Colors.amber.shade100,
              ),
              onTap: () => LanguageSwitchDialog.show(context),
            ),
          ),
          const SizedBox(height: 20),

          // Contact & Agency Information
          Text(
            l10n.contactInfo,
            style: TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.bold,
              color: Colors.grey.shade600,
            ),
          ),
          const SizedBox(height: 8),
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    siteName,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Icon(Icons.location_on_outlined, size: 18, color: Colors.grey),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          address,
                          style: TextStyle(color: Colors.grey.shade800, fontSize: 13),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      const Icon(Icons.phone_outlined, size: 18, color: Colors.grey),
                      const SizedBox(width: 8),
                      Text(
                        phone,
                        style: TextStyle(color: Colors.grey.shade800, fontSize: 13),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      const Icon(Icons.email_outlined, size: 18, color: Colors.grey),
                      const SizedBox(width: 8),
                      Text(
                        email,
                        style: TextStyle(color: Colors.grey.shade800, fontSize: 13),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),

          // API Configuration
          Text(
            l10n.apiBaseUrl,
            style: TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.bold,
              color: Colors.grey.shade600,
            ),
          ),
          const SizedBox(height: 8),
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: ListTile(
              leading: const Icon(Icons.dns_outlined, color: Colors.amber),
              title: Text(
                ApiConfig.baseUrl,
                style: const TextStyle(fontSize: 13, fontFamily: 'monospace'),
              ),
              subtitle: const Text('Default auto-detect (10.0.2.2 / 127.0.0.1)'),
              trailing: const Icon(Icons.edit_outlined, size: 20),
              onTap: () => _showEditApiUrlDialog(context),
            ),
          ),
          const SizedBox(height: 24),

          // App version badge
          Center(
            child: Text(
              'Flutter Portal v1.0.0 • Laravel 13 Powered',
              style: TextStyle(color: Colors.grey.shade500, fontSize: 11),
            ),
          ),
        ],
      ),
    );
  }

  void _showEditApiUrlDialog(BuildContext context) {
    final controller = TextEditingController(text: ApiConfig.baseUrl);
    final l10n = AppLocalizations.of(context);

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Text(l10n.apiBaseUrl),
        content: TextField(
          controller: controller,
          decoration: const InputDecoration(
            hintText: 'http://10.0.2.2:8000/api/v1',
            border: OutlineInputBorder(),
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: Text(l10n.cancel),
          ),
          ElevatedButton(
            onPressed: () {
              ApiConfig.customBaseUrl = controller.text;
              context.read<PortalProvider>().loadHomeData();
              Navigator.pop(ctx);
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.amber.shade700,
              foregroundColor: Colors.white,
            ),
            child: Text(l10n.save),
          ),
        ],
      ),
    );
  }
}

