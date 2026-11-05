import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../l10n/app_localizations.dart';
import '../providers/question_provider.dart';
import '../widgets/error_retry_view.dart';
import '../widgets/loading_view.dart';

class QuestionsScreen extends StatelessWidget {
  const QuestionsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final prov = context.watch<QuestionProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.questions),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => context.push('/questions/create'),
        backgroundColor: Colors.amber.shade700,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.edit_note_rounded),
        label: Text(l10n.askQuestion),
      ),
      body: prov.isLoading && prov.questions.isEmpty
          ? const LoadingView()
          : prov.error != null && prov.questions.isEmpty
              ? ErrorRetryView(
                  message: prov.error,
                  onRetry: () => prov.loadInitialData(),
                )
              : prov.questions.isEmpty
                  ? Center(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(Icons.question_answer_outlined,
                              size: 56, color: Colors.grey.shade400),
                          const SizedBox(height: 12),
                          Text(
                            l10n.noData,
                            style: TextStyle(color: Colors.grey.shade600),
                          ),
                          const SizedBox(height: 16),
                          ElevatedButton(
                            onPressed: () => context.push('/questions/create'),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.amber.shade700,
                              foregroundColor: Colors.white,
                            ),
                            child: Text(l10n.askQuestion),
                          ),
                        ],
                      ),
                    )
                  : RefreshIndicator(
                      color: Colors.amber.shade700,
                      onRefresh: () => prov.loadInitialData(),
                      child: ListView.builder(
                        padding: const EdgeInsets.fromLTRB(16, 12, 16, 80),
                        itemCount: prov.questions.length,
                        itemBuilder: (context, index) {
                          final item = prov.questions[index];
                          return Card(
                            margin: const EdgeInsets.only(bottom: 12),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(14),
                              side: BorderSide(color: Colors.grey.shade200),
                            ),
                            elevation: 1,
                            child: Padding(
                              padding: const EdgeInsets.all(16.0),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    children: [
                                      if (item.topic != null)
                                        Container(
                                          padding: const EdgeInsets.symmetric(
                                              horizontal: 8, vertical: 3),
                                          decoration: BoxDecoration(
                                            color: Colors.amber.shade50,
                                            borderRadius:
                                                BorderRadius.circular(6),
                                            border: Border.all(
                                                color: Colors.amber.shade200),
                                          ),
                                          child: Text(
                                            item.topic!.name,
                                            style: TextStyle(
                                              color: Colors.amber.shade900,
                                              fontSize: 11,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ),
                                      const Spacer(),
                                      if (item.createdAt != null)
                                        Text(
                                          DateFormat('dd MMM yyyy')
                                              .format(item.createdAt!),
                                          style: TextStyle(
                                            fontSize: 11,
                                            color: Colors.grey.shade500,
                                          ),
                                        ),
                                    ],
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    item.subject,
                                    style: const TextStyle(
                                      fontSize: 15,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  const SizedBox(height: 6),
                                  Text(
                                    item.body,
                                    style: TextStyle(
                                      fontSize: 13,
                                      color: Colors.grey.shade800,
                                      height: 1.35,
                                    ),
                                  ),
                                  const SizedBox(height: 6),
                                  Text(
                                    '— ${item.name}',
                                    style: TextStyle(
                                      fontSize: 12,
                                      fontStyle: FontStyle.italic,
                                      color: Colors.grey.shade500,
                                    ),
                                  ),
                                  if (item.answer != null) ...[
                                    const SizedBox(height: 12),
                                    Container(
                                      padding: const EdgeInsets.all(12),
                                      decoration: BoxDecoration(
                                        color: Colors.green.shade50,
                                        borderRadius: BorderRadius.circular(10),
                                        border: Border(
                                          left: BorderSide(
                                            color: Colors.green.shade700,
                                            width: 4,
                                          ),
                                        ),
                                      ),
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Row(
                                            children: [
                                              Icon(Icons.verified,
                                                  size: 16,
                                                  color: Colors.green.shade800),
                                              const SizedBox(width: 6),
                                              Text(
                                                l10n.officialAnswer,
                                                style: TextStyle(
                                                  fontSize: 12,
                                                  fontWeight: FontWeight.bold,
                                                  color: Colors.green.shade900,
                                                ),
                                              ),
                                              if (item.answer?.officerName !=
                                                  null) ...[
                                                const Spacer(),
                                                Text(
                                                  '${l10n.answeredBy}: ${item.answer!.officerName}',
                                                  style: TextStyle(
                                                    fontSize: 11,
                                                    color:
                                                        Colors.green.shade800,
                                                  ),
                                                ),
                                              ],
                                            ],
                                          ),
                                          const SizedBox(height: 6),
                                          HtmlWidget(
                                            item.answer!.body,
                                            textStyle: TextStyle(
                                              fontSize: 13,
                                              color: Colors.grey.shade900,
                                              height: 1.4,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ],
                              ),
                            ),
                          );
                        },
                      ),
                    ),
    );
  }
}

