import 'dart:async';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../models/slide.dart';

class SlideCarousel extends StatefulWidget {
  final List<SlideModel> slides;

  const SlideCarousel({super.key, required this.slides});

  @override
  State<SlideCarousel> createState() => _SlideCarouselState();
}

class _SlideCarouselState extends State<SlideCarousel> {
  final PageController _pageController = PageController();
  int _currentPage = 0;
  Timer? _timer;

  @override
  void initState() {
    super.initState();
    _startAutoPlay();
  }

  void _startAutoPlay() {
    if (widget.slides.length <= 1) return;
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 5), (_) {
      if (!mounted || widget.slides.isEmpty) return;
      final nextPage = (_currentPage + 1) % widget.slides.length;
      _pageController.animateToPage(
        nextPage,
        duration: const Duration(milliseconds: 600),
        curve: Curves.easeInOut,
      );
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (widget.slides.isEmpty) return const SizedBox.shrink();

    return Column(
      children: [
        SizedBox(
          height: 190,
          child: PageView.builder(
            controller: _pageController,
            onPageChanged: (index) {
              setState(() => _currentPage = index);
            },
            itemCount: widget.slides.length,
            itemBuilder: (context, index) {
              final slide = widget.slides[index];
              return Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 6.0),
                child: GestureDetector(
                  onTap: () {
                    if (slide.linkUrl != null && slide.linkUrl!.isNotEmpty) {
                      final url = slide.linkUrl!;
                      if (url.startsWith('/pages/')) {
                        final slug = url.replaceFirst('/pages/', '');
                        context.push('/pages/$slug');
                      } else if (url.startsWith('/posts')) {
                        context.push('/posts');
                      }
                    }
                  },
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(16),
                    child: Stack(
                      fit: StackFit.expand,
                      children: [
                        // Background image or gradient
                        if (slide.image != null && slide.image!.isNotEmpty)
                          Image.network(
                            slide.image!,
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) =>
                                _buildFallbackBanner(),
                          )
                        else
                          _buildFallbackBanner(),

                        // Gradient overlay
                        Container(
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              begin: Alignment.topCenter,
                              end: Alignment.bottomCenter,
                              colors: [
                                Colors.black.withValues(alpha: 0.1),
                                Colors.black.withValues(alpha: 0.85),
                              ],
                            ),
                          ),
                        ),

                        // Text content
                        Positioned(
                          left: 16,
                          right: 16,
                          bottom: 16,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Text(
                                slide.title,
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                  shadows: [
                                    Shadow(blurRadius: 4, color: Colors.black54),
                                  ],
                                ),
                              ),
                              if (slide.description != null &&
                                  slide.description!.isNotEmpty) ...[
                                const SizedBox(height: 4),
                                Text(
                                  slide.description!,
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  style: TextStyle(
                                    color: Colors.white.withValues(alpha: 0.9),
                                    fontSize: 12,
                                  ),
                                ),
                              ],
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),
        ),
        if (widget.slides.length > 1) ...[
          const SizedBox(height: 6),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(
              widget.slides.length,
              (index) => Container(
                width: _currentPage == index ? 20 : 6,
                height: 6,
                margin: const EdgeInsets.symmetric(horizontal: 3),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(3),
                  color: _currentPage == index
                      ? Colors.amber.shade700
                      : Colors.grey.shade300,
                ),
              ),
            ),
          ),
        ],
      ],
    );
  }

  Widget _buildFallbackBanner() {
    return Container(
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
      child: const Center(
        child: Icon(Icons.school, size: 64, color: Colors.white24),
      ),
    );
  }
}
