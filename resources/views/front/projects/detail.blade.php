@extends('front.layout.app')

@section('content')

    <div class="page-title aos-init aos-animate" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-start ">
                    <div class="col-lg-6">
                        @if($status ==0)
                            <h1>TAMAMLANAN PROJELERİMİZ</h1>
                        @else
                            <h1>DEVAM EDEN PROJELERİMİZ</h1>
                        @endif
                        <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle
                            karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{route('homePage.index')}}">Ana Sayfa</a></li>
                    @if($status ==0)
                        <li><a href="{{route('completedProjects.index')}}" class="current">Tamamlanan Projeler</a></li>
                    @else
                        <li><a href="{{route('continuingProjects.index')}}" class="current">Devam Eden Projeler</a></li>
                    @endif
                </ol>
            </div>
        </nav>
    </div>

    <section class="container">
        <div class="row">
            <!-- Ana Swiper -->
            <div class="col-6">
                <div class="swiper mySwiper d-flex align-items-center justify-content-start">
                    <div class="swiper-wrapper">
                        @foreach($projects->images as $img)
                            <div class="swiper-slide">
                                <img src="{{ asset('/'.$img->image_path) }}"
                                     onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                     class="img-fluid open-fullscreen" data-index="{{ $loop->index }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Fullscreen Swiper -->
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
                    <!-- Ok butonları ve pagination noktaları -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>



            <div class="col-lg-6 order-2 order-lg-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 style="color: var(--accent-color)">{{ $projects->name }}</h2>
                    <div class="date">
                        <span class="day">{{ $projects->created_at->format('d') }}</span>
                        <span class="month">{{ $projects->created_at->locale('tr')->isoFormat('MMM') }}</span>
                    </div>
                </div>
                <p class="fst-italic">
                    <span>{!! $projects->description !!} </span>
                </p>
            </div>
        </div>
    </section>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let fullscreenSlider = document.getElementById("fullscreenSlider");
            let closeBtn = document.querySelector(".close-btn");
            let openFullscreenBtns = document.querySelectorAll(".open-fullscreen");
            let swiperInstance = null;

            let mainSwiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                }
            });

            openFullscreenBtns.forEach((img, index) => {
                img.addEventListener("click", function () {
                    fullscreenSlider.classList.add("active");

                    if (swiperInstance) swiperInstance.destroy(true, true);

                    swiperInstance = new Swiper(".myFullscreenSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        effect: "fade",
                        fadeEffect: {
                            crossFade: true
                        },
                        autoplay: {
                            delay: 4000,
                            disableOnInteraction: false,
                        },
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
                if (swiperInstance) swiperInstance.destroy(true, true);
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

