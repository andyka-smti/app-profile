import 'package:flutter/material.dart';
import '../l10n/app_localizations.dart';

class ErrorRetryView extends StatelessWidget {
  final String? message;
  final VoidCallback onRetry;

  const ErrorRetryView({
    super.key,
    this.message,
    required this.onRetry,
  });

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.cloud_off_rounded, size: 56, color: Colors.amber.shade700),
            const SizedBox(height: 12),
            Text(
              message ?? l10n.errorLoading,
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey.shade800, fontSize: 14),
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: onRetry,
              icon: const Icon(Icons.refresh),
              label: Text(l10n.retry),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.amber.shade700,
                foregroundColor: Colors.white,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

