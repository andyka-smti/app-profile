import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../l10n/app_localizations.dart';
import '../l10n/locale_provider.dart';

class LanguageSwitchDialog extends StatelessWidget {
  const LanguageSwitchDialog({super.key});

  static Future<void> show(BuildContext context) {
    return showDialog(
      context: context,
      builder: (ctx) => const LanguageSwitchDialog(),
    );
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final localeProv = context.watch<LocaleProvider>();
    final currentCode = localeProv.languageCode;

    return AlertDialog(
      title: Row(
        children: [
          const Icon(Icons.language, color: Colors.amber),
          const SizedBox(width: 8),
          Text(l10n.selectLanguage),
        ],
      ),
      content: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          ListTile(
            leading: const Text('🇮🇩', style: TextStyle(fontSize: 24)),
            title: Text(l10n.indonesian),
            trailing: currentCode == 'id'
                ? const Icon(Icons.check_circle, color: Colors.amber)
                : null,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
            selected: currentCode == 'id',
            selectedTileColor: Colors.amber.shade50,
            onTap: () {
              context.read<LocaleProvider>().setLocale(const Locale('id'));
              Navigator.of(context).pop();
            },
          ),
          const SizedBox(height: 6),
          ListTile(
            leading: const Text('🇬🇧', style: TextStyle(fontSize: 24)),
            title: Text(l10n.english),
            trailing: currentCode == 'en'
                ? const Icon(Icons.check_circle, color: Colors.amber)
                : null,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
            selected: currentCode == 'en',
            selectedTileColor: Colors.amber.shade50,
            onTap: () {
              context.read<LocaleProvider>().setLocale(const Locale('en'));
              Navigator.of(context).pop();
            },
          ),
        ],
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.of(context).pop(),
          child: Text(l10n.cancel),
        ),
      ],
    );
  }
}
