import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';

class AppLocalizations {
  final Locale locale;

  AppLocalizations(this.locale);

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations) ??
        AppLocalizations(const Locale('id'));
  }

  static const LocalizationsDelegate<AppLocalizations> delegate =
      _AppLocalizationsDelegate();

  static final Map<String, Map<String, String>> _localizedValues = {
    'id': {
      'app_title': 'Portal Profil Instansi',
      'home': 'Beranda',
      'posts': 'Berita & Artikel',
      'faqs': 'FAQ & Bantuan',
      'questions': 'Tanya Kami',
      'profile': 'Profil & Info',
      'settings': 'Pengaturan',
      'language': 'Bahasa',
      'indonesian': 'Bahasa Indonesia',
      'english': 'English',
      'select_language': 'Pilih Bahasa',
      'search': 'Cari...',
      'search_posts': 'Cari berita atau artikel...',
      'latest_news': 'Kabar Terkini',
      'featured_news': 'Berita Utama',
      'view_all': 'Lihat Semua',
      'categories': 'Kategori',
      'all_categories': 'Semua Kategori',
      'read_more': 'Baca Selengkapnya',
      'published_at': 'Diterbitkan',
      'views': 'dilihat',
      'author': 'Penulis',
      'share': 'Bagikan',
      'static_pages': 'Halaman Profil',
      'quick_menu': 'Menu Utama',
      'ask_question': 'Ajukan Pertanyaan',
      'ask_question_desc': 'Punya pertanyaan seputar instansi? Kirimkan kepada kami.',
      'full_name': 'Nama Lengkap',
      'email': 'Alamat Email',
      'phone': 'Nomor Telepon / WhatsApp',
      'topic': 'Topik Pertanyaan',
      'select_topic': 'Pilih Topik',
      'subject': 'Judul / Perihal',
      'question_body': 'Isi Pertanyaan',
      'submit_question': 'Kirim Pertanyaan',
      'submitting': 'Mengirimkan...',
      'question_success_title': 'Pertanyaan Terkirim!',
      'question_success_desc': 'Pertanyaan Anda telah kami terima dan akan segera ditinjau oleh petugas.',
      'error_loading': 'Gagal memuat data. Silakan periksa koneksi internet Anda.',
      'retry': 'Coba Lagi',
      'no_data': 'Belum ada data tersedia.',
      'no_posts': 'Tidak ada artikel yang ditemukan.',
      'answered': 'Terjawab',
      'pending': 'Menunggu Jawaban',
      'official_answer': 'Tanggapan Resmi',
      'answered_by': 'Dijawab oleh',
      'contact_info': 'Kontak & Informasi',
      'address': 'Alamat',
      'phone_number': 'Telepon',
      'email_address': 'Email',
      'social_media': 'Media Sosial',
      'api_base_url': 'URL Server API',
      'save': 'Simpan',
      'cancel': 'Batal',
      'field_required': 'Kolom ini wajib diisi',
      'invalid_email': 'Alamat email tidak valid',
    },
    'en': {
      'app_title': 'Institutional Profile Portal',
      'home': 'Home',
      'posts': 'News & Articles',
      'faqs': 'FAQ & Help',
      'questions': 'Public Inquiries',
      'profile': 'Profile & Info',
      'settings': 'Settings',
      'language': 'Language',
      'indonesian': 'Bahasa Indonesia',
      'english': 'English',
      'select_language': 'Select Language',
      'search': 'Search...',
      'search_posts': 'Search news or articles...',
      'latest_news': 'Latest News',
      'featured_news': 'Featured Highlights',
      'view_all': 'View All',
      'categories': 'Categories',
      'all_categories': 'All Categories',
      'read_more': 'Read More',
      'published_at': 'Published on',
      'views': 'views',
      'author': 'Author',
      'share': 'Share',
      'static_pages': 'Information Pages',
      'quick_menu': 'Quick Access',
      'ask_question': 'Submit an Inquiry',
      'ask_question_desc': 'Have a question about our institution? Send it to us.',
      'full_name': 'Full Name',
      'email': 'Email Address',
      'phone': 'Phone / WhatsApp Number',
      'topic': 'Inquiry Topic',
      'select_topic': 'Select a Topic',
      'subject': 'Subject / Topic Title',
      'question_body': 'Your Question / Message',
      'submit_question': 'Send Inquiry',
      'submitting': 'Sending...',
      'question_success_title': 'Inquiry Submitted!',
      'question_success_desc': 'Your question has been received and will be reviewed shortly by our staff.',
      'error_loading': 'Failed to load data. Please check your network connection.',
      'retry': 'Retry',
      'no_data': 'No data available at this time.',
      'no_posts': 'No articles found.',
      'answered': 'Answered',
      'pending': 'Pending Review',
      'official_answer': 'Official Response',
      'answered_by': 'Answered by',
      'contact_info': 'Contact & Information',
      'address': 'Address',
      'phone_number': 'Phone',
      'email_address': 'Email',
      'social_media': 'Social Media',
      'api_base_url': 'API Server URL',
      'save': 'Save',
      'cancel': 'Cancel',
      'field_required': 'This field is required',
      'invalid_email': 'Please enter a valid email address',
    },
  };

  String get(String key) {
    return _localizedValues[locale.languageCode]?[key] ??
        _localizedValues['id']?[key] ??
        key;
  }

  String get appTitle => get('app_title');
  String get home => get('home');
  String get posts => get('posts');
  String get faqs => get('faqs');
  String get questions => get('questions');
  String get profile => get('profile');
  String get settings => get('settings');
  String get language => get('language');
  String get indonesian => get('indonesian');
  String get english => get('english');
  String get selectLanguage => get('select_language');
  String get search => get('search');
  String get searchPosts => get('search_posts');
  String get latestNews => get('latest_news');
  String get featuredNews => get('featured_news');
  String get viewAll => get('view_all');
  String get categories => get('categories');
  String get allCategories => get('all_categories');
  String get readMore => get('read_more');
  String get publishedAt => get('published_at');
  String get views => get('views');
  String get author => get('author');
  String get share => get('share');
  String get staticPages => get('static_pages');
  String get quickMenu => get('quick_menu');
  String get askQuestion => get('ask_question');
  String get askQuestionDesc => get('ask_question_desc');
  String get fullName => get('full_name');
  String get email => get('email');
  String get phone => get('phone');
  String get topic => get('topic');
  String get selectTopic => get('select_topic');
  String get subject => get('subject');
  String get questionBody => get('question_body');
  String get submitQuestion => get('submit_question');
  String get submitting => get('submitting');
  String get questionSuccessTitle => get('question_success_title');
  String get questionSuccessDesc => get('question_success_desc');
  String get errorLoading => get('error_loading');
  String get retry => get('retry');
  String get noData => get('no_data');
  String get noPosts => get('no_posts');
  String get answered => get('answered');
  String get pending => get('pending');
  String get officialAnswer => get('official_answer');
  String get answeredBy => get('answered_by');
  String get contactInfo => get('contact_info');
  String get address => get('address');
  String get phoneNumber => get('phone_number');
  String get emailAddress => get('email_address');
  String get socialMedia => get('social_media');
  String get apiBaseUrl => get('api_base_url');
  String get save => get('save');
  String get cancel => get('cancel');
  String get fieldRequired => get('field_required');
  String get invalidEmail => get('invalid_email');
}

class _AppLocalizationsDelegate
    extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) => ['id', 'en'].contains(locale.languageCode);

  @override
  Future<AppLocalizations> load(Locale locale) {
    return SynchronousFuture<AppLocalizations>(AppLocalizations(locale));
  }

  @override
  bool shouldReload(_AppLocalizationsDelegate old) => false;
}

