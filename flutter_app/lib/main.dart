import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:provider/provider.dart';
import 'l10n/app_localizations.dart';
import 'l10n/locale_provider.dart';
import 'providers/portal_provider.dart';
import 'providers/question_provider.dart';
import 'routes/app_router.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(
          create: (_) => LocaleProvider(),
        ),
        ChangeNotifierProxyProvider<LocaleProvider, PortalProvider>(
          create: (ctx) => PortalProvider(
            initialLocale: ctx.read<LocaleProvider>().languageCode,
          ),
          update: (ctx, localeProv, portalProv) {
            portalProv ??= PortalProvider(initialLocale: localeProv.languageCode);
            portalProv.updateLocale(localeProv.languageCode);
            return portalProv;
          },
        ),
        ChangeNotifierProxyProvider<LocaleProvider, QuestionProvider>(
          create: (ctx) => QuestionProvider(
            initialLocale: ctx.read<LocaleProvider>().languageCode,
          ),
          update: (ctx, localeProv, questionProv) {
            questionProv ??= QuestionProvider(initialLocale: localeProv.languageCode);
            questionProv.updateLocale(localeProv.languageCode);
            return questionProv;
          },
        ),
      ],
      child: Consumer<LocaleProvider>(
        builder: (context, localeProv, _) {
          return MaterialApp.router(
            title: 'Portal Instansi',
            debugShowCheckedModeBanner: false,
            locale: localeProv.locale,
            supportedLocales: const [
              Locale('id', 'ID'),
              Locale('en', 'US'),
            ],
            localizationsDelegates: const [
              AppLocalizations.delegate,
              GlobalMaterialLocalizations.delegate,
              GlobalWidgetsLocalizations.delegate,
              GlobalCupertinoLocalizations.delegate,
            ],
            theme: ThemeData(
              useMaterial3: true,
              colorScheme: ColorScheme.fromSeed(
                seedColor: Colors.amber,
                primary: Colors.amber.shade800,
                secondary: Colors.amber.shade600,
                surface: Colors.white,
              ),
              appBarTheme: AppBarTheme(
                centerTitle: false,
                elevation: 0,
                backgroundColor: Colors.white,
                foregroundColor: Colors.grey.shade900,
                scrolledUnderElevation: 1,
              ),
              cardTheme: CardThemeData(
                elevation: 1,
                color: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(14),
                ),
              ),
              elevatedButtonTheme: ElevatedButtonThemeData(
                style: ElevatedButton.styleFrom(
                  elevation: 0,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(10),
                  ),
                ),
              ),
              inputDecorationTheme: InputDecorationTheme(
                filled: true,
                fillColor: Colors.grey.shade50,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10),
                  borderSide: BorderSide(color: Colors.grey.shade300),
                ),
                enabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10),
                  borderSide: BorderSide(color: Colors.grey.shade300),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10),
                  borderSide: BorderSide(color: Colors.amber.shade700, width: 1.5),
                ),
              ),
            ),
            routerConfig: AppRouter.router,
          );
        },
      ),
    );
  }
}
