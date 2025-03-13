    @extends('front.layout.app')
    <style>
        #fullscreenSlider .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #fullscreenSlider {
            display: none;
        }

        #fullscreenSlider.active {
            display: block;
            /* Eğer overlay şeklinde görünmesini isterseniz ek stil verebilirsiniz */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
        }


    </style>
    @section('content')
        <div class="page-title aos-init aos-animate" data-aos="fade">
            <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-start">
                        <div class="col-lg-5">
                            <h1>Haberler</h1>
                            <p class="mb-0">
                                Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle karşılar ve yenilikçi, kaliteli hizmeti hedefler.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="breadcrumbs">
                <div class="container">
                    <ol>
                        <li><a href="{{ url('/') }}">Ana Sayfa</a></li>
                        <li class="current">Haberler</li>
                    </ol>
                </div>
            </nav>
        </div>

        <section class="container my-4">
            <div class="row">
                <!-- Haber Resimleri Slider ve Thumbnail Galeri -->
                <div class="col-md-6">
                    <!-- Ana Slider -->
                    <div class="swiper mySwiper d-flex align-items-center justify-content-center">
                        <div class="swiper-wrapper">
                            <!-- Kapak Resmi: Her zaman ilk slide -->
                            <div class="swiper-slide">
                                <img src="{{ asset('uploads/news/'.$news->image) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     alt="Kapak Resmi"
                                     class="img-fluid open-fullscreen"
                                     data-index="0"
                                     style="max-width: 100%; height: auto;">
                            </div>
                            <!-- Ek Haber Resimleri -->
                            @if(isset($news) && $news->images->isNotEmpty())
                                @foreach($news->images as $index => $img)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('uploads/news/'.$img->image) }}"
                                             onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                             alt="Ek Haber Resmi"
                                             class="img-fluid open-fullscreen"
                                             data-index="{{ $index + 1 }}"
                                             style="max-width: 100%; height: auto;">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <!-- Thumbnail Slider (Galeri Önizlemesi) -->
                    <div class="swiper mySwiper2 mt-3">
                        <div class="swiper-wrapper">
                            <!-- Kapak Resmi Thumbnail -->
                            <div class="swiper-slide">
                                <img src="{{ asset('uploads/news/'.$news->image) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     alt="Kapak Resmi"
                                     class="img-fluid open-fullscreen"
                                     data-index="0"
                                     style="max-width: 100%; height: auto;">
                            </div>
                            <!-- Ek Haber Resimleri Thumbnail -->
                            @if(isset($news) && $news->images->isNotEmpty())
                                @foreach($news->images as $index => $img)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('uploads/news/'.$img->image) }}"
                                             onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                             alt="Ek Haber Resmi"
                                             class="img-fluid open-fullscreen"
                                             data-index="{{ $index + 1 }}"
                                             style="max-width: 100%; height: auto;">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fullscreen Swiper Yapısı -->
                <div id="fullscreenSlider" class="fullscreen-container">
                    <span class="close-btn">&times;</span>
                    <div class="swiper myFullscreenSwiper">
                        <div class="swiper-wrapper">
                            <!-- Kapak Resmi Fullscreen -->
                            <div class="swiper-slide">
                                <img src="{{ asset('uploads/news/'.$news->image) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     class="img-fluid" alt="Kapak Resmi">
                            </div>
                            <!-- Ek Haber Resimleri Fullscreen -->
                            @if(isset($news) && $news->images->isNotEmpty())
                                @foreach($news->images as $img)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('uploads/news/'.$img->image) }}"
                                             onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                             class="img-fluid" alt="Ek Haber Resmi">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>

                <!-- Haber İçeriği -->
                <div class="col-md-6 order-2 order-md-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 style="color: var(--accent-color)">
                            <a href="{{ route('news.detail', $news->slug) }}" style="text-decoration: none; color: inherit;">
                                {{ $news->title }}
                            </a>
                        </h2>
                    </div>
                    <p class="fst-italic">
                        <a href="{{ route('news.detail', $news->slug) }}" style="text-decoration: none; color: inherit;">
                            {!! $news->content !!}
                        </a>
                    </p>
                </div>
            </div>
        </section>
    @endsection

    @section('script')
        <!-- Swiper CSS/JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                // Thumbnail Slider (galeri önizlemesi)
                var thumbSwiper = new Swiper('.mySwiper2', {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesVisibility: true,
                    watchSlidesProgress: true,
                });
                // Ana Slider, thumbnail ile senkronize
                var mainSwiper = new Swiper('.mySwiper', {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    thumbs: {
                        swiper: thumbSwiper,
                    },
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const fullscreenSlider = document.getElementById("fullscreenSlider");
                const closeBtn = document.querySelector(".close-btn");
                let fullscreenSwiperInstance = null;

                // Tüm "open-fullscreen" sınıflı resimler için tıklama olayı ekle (ana ve thumbnail slider'dan)
                document.querySelectorAll(".open-fullscreen").forEach((img) => {
                    img.addEventListener("click", function () {
                        const index = parseInt(this.getAttribute("data-index"));
                        fullscreenSlider.classList.add("active");

                        if (fullscreenSwiperInstance) fullscreenSwiperInstance.destroy(true, true);

                        fullscreenSwiperInstance = new Swiper(".myFullscreenSwiper", {
                            slidesPerView: 1,
                            spaceBetween: 10,
                            loop: false, // Doğru index için loop kapalı
                            effect: "fade",
                            fadeEffect: { crossFade: true },
                            autoplay: { delay: 4000, disableOnInteraction: false },
                            speed: 1000,
                            navigation: {
                                nextEl: ".swiper-button-next",
                                prevEl: ".swiper-button-prev",
                            },
                            pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                            },
                            initialSlide: index,
                        });
                    });
                });

                function closeFullscreen() {
                    fullscreenSlider.classList.remove("active");
                    if (fullscreenSwiperInstance) fullscreenSwiperInstance.destroy(true, true);
                }

                closeBtn.addEventListener("click", closeFullscreen);

                document.addEventListener("keydown", function (event) {
                    if (event.key === "Escape") {
                        closeFullscreen();
                    }
                });

                fullscreenSlider.addEventListener("click", function (event) {
                    if (event.target === fullscreenSlider) {
                        closeFullscreen();
                    }
                });
            });
        </script>
    @endsection
