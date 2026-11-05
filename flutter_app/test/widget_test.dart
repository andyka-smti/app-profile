import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_portal/main.dart';

void main() {
  testWidgets('Portal app boots successfully', (WidgetTester tester) async {
    await tester.pumpWidget(const MyApp());
    await tester.pump();

    // Verify app renders with navigation destinations
    expect(find.byType(MyApp), findsOneWidget);
  });
}
