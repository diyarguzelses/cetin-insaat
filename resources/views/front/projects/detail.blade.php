@extends('front.layout.app')

@section('content')

    <!-- Thumbnail çerçeve ve boyut ayarı için stil -->
    <style>
        .mySwiper2 .swiper-slide {
            width: 100px;       /* Sabit genişlik */
            height: 100px;      /* Sabit yükseklik */
            border: 2px solid #ddd; /* İnce bir çerçeve */
            overflow: hidden;
            margin: 5px;
        }
        .mySwiper2 .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;  /* Resmin oranını koruyarak sığdırır */
            cursor: pointer;
        }
        /* Fullscreen slider için basit stil (ortalanmış) */
        .fullscreen-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
            display: none;
            align-items: center;   /* Dikey ortalama */
            justify-content: center; /* Yatay ortalama */
        }
        #fullscreenSlider .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .fullscreen-container.active {
            display: flex;
        }
        .fullscreen-container .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
            z-index: 10000;
        }
    </style>

    <div class="page-title aos-init aos-animate" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-start">
                    <div class="col-lg-6">
                        @if($status == 0)
                            <h1>TAMAMLANAN PROJELERİMİZ</h1>
                        @else
                            <h1>DEVAM EDEN PROJELERİMİZ</h1>
                        @endif
                        <p class="mb-0">
                            Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle
                            karşılar ve yenilikçi, kaliteli hizmeti hedefler.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('homePage.index') }}">Ana Sayfa</a></li>
                    @if($status == 0)
                        <li><a href="{{ route('completedProjects.index') }}" class="current">Tamamlanan Projeler</a></li>
                    @else
                        <li><a href="{{ route('continuingProjects.index') }}" class="current">Devam Eden Projeler</a></li>
                    @endif
                </ol>
            </div>
        </nav>
    </div>

    <section class="container">
        <div class="row">
            <!-- Galeri Slider -->
            <div class="col-6">
                <!-- Ana Slider -->
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($projects->images as $img)
                            <div class="swiper-slide">
                                <!-- data-index ekleniyor -->
                                <img src="{{ asset('/'.$img->image_path) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     class="img-fluid open-fullscreen" data-index="{{ $loop->index }}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <!-- Navigasyon butonları -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <!-- Thumbnail Slider -->
                <div class="swiper mySwiper2 mt-3">
                    <div class="swiper-wrapper">
                        @foreach($projects->images as $img)
                            <div class="swiper-slide">
                                <!-- data-index ekleniyor -->
                                <img src="{{ asset('/'.$img->image_path) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     class="img-fluid" data-index="{{ $loop->index }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Fullscreen Slider -->
            <div id="fullscreenSlider" class="fullscreen-container">
                <span class="close-btn">&times;</span>
                <div class="swiper myFullscreenSwiper">
                    <div class="swiper-wrapper">
                        @foreach($projects->images as $img)
                            <div class="swiper-slide">
                                <img src="{{ asset('/'.$img->image_path) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     class="img-fluid" alt="">
                            </div>
                        @endforeach
                    </div>
                    <!-- Fullscreen slider navigasyon -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <!-- Proje Bilgi Alanı -->
            <div class="col-lg-6 order-2 order-lg-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 style="color: var(--accent-color)">{{ $projects->name }}</h2>
                    <div class="date">
                        <span class="day">{{ $projects->created_at->format('d') }}</span>
                        <span class="month">{{ $projects->created_at->locale('tr')->isoFormat('MMM') }}</span>
                    </div>
                </div>
                <p class="fst-italic">
                    <span>{!! $projects->description !!}</span>
                </p>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fullscreenSlider = document.getElementById("fullscreenSlider");
            const closeBtn = document.querySelector(".close-btn");
            let fullscreenSwiperInstance = null;

            // Thumbnail slider (loop kapalı)
            const galleryThumbs = new Swiper('.mySwiper2', {
                spaceBetween: 10,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });

            // Ana slider (loop açık)
            const galleryTop = new Swiper('.mySwiper', {
                spaceBetween: 10,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: galleryThumbs,
                },
            });

            // Fullscreen slider açma fonksiyonu (loop kapalı)
            function openFullscreen(index) {
                fullscreenSlider.classList.add("active");
                if (fullscreenSwiperInstance) fullscreenSwiperInstance.destroy(true, true);
                fullscreenSwiperInstance = new Swiper(".myFullscreenSwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: false, // loop kapalı, böylece indexler doğru eşleşir
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
            }

            // Hem ana slider hem de thumbnail resimlerindeki tıklama olayını data-index üzerinden alıyoruz
            document.querySelectorAll(".open-fullscreen, .mySwiper2 .swiper-slide img").forEach((img) => {
                img.addEventListener("click", function () {
                    let index = parseInt(this.getAttribute("data-index"));
                    openFullscreen(index);
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
