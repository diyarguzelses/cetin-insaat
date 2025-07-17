@php use Illuminate\Support\Str; @endphp
@extends('front.layout.app')

@section('content')

    <section id="hero" class="hero section dark-background">

        {{-- Video elementi. Otomatik ve sessiz başlar. Ses kontrolü için JavaScript ile yönetilir. --}}
        <video id="heroVideo" autoplay loop playsinline class="video-background">
            <source src="{{ asset('videos/deneme2.mp4') }}" type="video/mp4">
            Tarayıcınız video etiketini desteklemiyor.
        </video>

        {{-- Başlık ve içeriğin video üzerine sağ üstte konumlandırılması --}}
{{--        <div class="video-hero-content-top-right">--}}
{{--            <h2 data-aos="fade-up" data-aos-delay="100" class="aos-init aos-animate">Geçmişten Günümüze<br>Yükselen Başarılar</h2>--}}
{{--            <div class="d-flex mt-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">--}}
{{--                --}}{{-- Buraya ek bir buton veya link ekleyebilirsiniz --}}
{{--            </div>--}}
{{--        </div>--}}

        {{-- Ses kontrol düğmesinin video üzerinde sağ üstte konumlandırılması --}}
        <div class="volume-control-container-top-right">
            <button id="volumeButton" class="volume-button">
                <i class="fas fa-volume-up"></i>
            </button>
        </div>

    </section>
    <div id="trainers-index" class="trainers-index">

        <div class="container member_container">

            <div class="row member_row">

                @foreach($firstThreeSectors as $sector)
                    <div class="col-lg-3 col-md-6 d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <div class="member">
                            <img src="{{asset('uploads/sectors/'.$sector->image)}}" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';" class="img-fluid" alt="">
                            <div class="member-content">
                                <h4>{{$sector->name}}</h4>
                                <hr>
                                <p>
                                    {!! Str::limit($sector->text, 100) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <section id="about" class="about section about_bg">

        <div class="container">

            <div class="row gy-4">
                <div class="hr_baslik">
                    <h2 style="color: var(--accent-color)">Rakamlarla Çetin İnşaat</h2>
                    <hr class="hr">
                </div>

                <div class="col-lg-6 order-1 order-lg-1 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    <div class="block">
                        <h1 class="text-center" style="color: white;padding-top: 50px;">Birlikte Üretiyoruz</h1>
                    </div>
                </div>

                <div class="col-lg-6 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up"
                     data-aos-delay="200">
                    <h3 style="color: var(--accent-color)">1990 <br> yılından bu yana büyüyen güç</h3>
                    <ul class="istatistic ">
                        <ul class="row">
                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $projectCount }} <br> Projeler</h4>
                            </li>
                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $personnelCount }}+ <br> Çalışan Sayısı</h4>
                            </li>

                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $machineCount }}+ <br> Makine Sayısı</h4>
                            </li>
                            <li class="col-lg-6">
                                <i class="fa-solid fa-circle fa-sm"></i>
                                <h4>{{ $projeCategoryCount }}+ <br> Faaliyet Alanı</h4>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>

        </div>

    </section>

    <section id="about-us" class="section about-us">

        <div class="">
            <div class="container hr_baslik">
                <h2 style="color: var(--accent-color)">Haberler</h2>
                <hr class="hr">
            </div>
            <div class="row gy-4">
                <div class="col-lg-4 order-1 order-lg-1 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                    @if(!empty($lastnew->image) && file_exists(public_path('uploads/news/'.$lastnew->image)))
                        <img src="{{ asset('/uploads/news/'.$lastnew->image) }}" class="img-fluid" style="height: 300px!important;object-fit: cover" alt="">
                    @else
                        <img src="{{ asset('front/assets/img/default-img.png') }}" class="img-fluid" style="height: 300px!important;object-fit: cover" alt="">
                    @endif
                </div>

                <div class="col-lg-8 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    @if(isset($lastnew) && $lastnew->title)
                        <a href="{{ route('news.detail', $lastnew->slug) }}" style="color: black!important;">
                            <h3 style="color: var(--accent-color)">{{ $lastnew->title }}</h3>
                            <div class="fsitalict- w-75 bg-white2 mr-2">
                                {!! Str::limit(strip_tags($lastnew->content), 450) !!}
                                <a href="{{ route('news.detail', $lastnew->slug) }}" style="color: var(--accent-color)"> Devamını gör</a>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="news_title">
            <div class="container">
                <div id="testimonials" class="testimonials" style="padding-top: 100px">
                    <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                        <div class="swiper init-swiper swiper-initialized swiper-horizontal swiper-backface-hidden">
                            <script type="application/json" class="swiper-config">
                                {
                                  "loop": true,
                                  "speed": 600,
                                  "autoplay": {
                                    "delay": 5000
                                  },
                                  "slidesPerView": "auto",
                                  "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                  },
                                  "breakpoints": {
                                    "320": {
                                      "slidesPerView": 1,
                                      "spaceBetween": 40
                                    },
                                    "1200": {
                                      "slidesPerView": 2,
                                      "spaceBetween": 20
                                    }
                                  }
                                }
                            </script>

                            <div class="swiper-wrapper">
                                @foreach ($news as $index => $new)
                                    <div class="swiper-slide">
                                        <a href="{{ route('news.detail', $new->slug) }}">
                                            <div class="testimonial-wrap">
                                                <div class="testimonial-item">
                                                    <img src="{{ asset('/uploads/news/'.$new->image) }}"
                                                         onerror="this.onerror=null; this.src='{{ asset('front/assets/img/default-img.png') }}';"
                                                         class="testimonial-img" alt="Haber Resmi">
                                                    <h3>{{ Str::limit(($new->title), 70) }}</h3>
                                                    <p>
                                                        <i class="bi bi-quote quote-icon-left"></i>
                                                        <span>{{ Str::limit(strip_tags($new->content), 200) }}</span>
                                                        <i class="bi bi-quote quote-icon-right"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal">
                                @foreach ($news as $index => $new)
                                    <span class="swiper-pagination-bullet {{ $loop->first ? 'swiper-pagination-bullet-active' : '' }}" tabindex="0" role="button" aria-label="Go to slide {{ $loop->iteration }}"></span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="game-section">
        <div class="container hr_baslik">
            <h2 style="color: var(--accent-color)">Faaliyet Alanları</h2>
            <hr class="hr">
        </div>

        <div class="owl-carousel custom-carousel owl-theme">
            @foreach($nextFourSectors as $sector)
                <div class="item" style="background-image: url('/uploads/sectors/{{ $sector->image }}');cursor: auto" onerror="this.onerror=null; this.src='{{asset('front/assets/img/default-img.png')}}';">
                    <div class="item-desc">
                        <h4>{{ $sector->name }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="custom-swiper-section container">
        <div class="container pb-3">
            <h2 style="color: var(--accent-color)">Kurucularımız</h2>
            <hr class="hr">
        </div>
        <swiper-container class="customSwiper"
                          pagination="true"
                          pagination-clickable="true"
                          navigation="false"
                          slides-per-view="3"
                          space-between="20"
                          centered-slides="true"
                          loop="false">
            @foreach($boardMembers as $member)
                <swiper-slide>
                    <div class="image-container">
                        <img src="{{ asset('uploads/board_of_directors/' . $member->image) }}"
                             class="open-modal"
                             data-bs-toggle="modal"
                             data-bs-target="#cvModal"
                             data-name="{{ $member->name }}"
                             data-cv="{{ $member->biography }}"/>
                        <div class="hover-button">+</div>
                    </div>
                </swiper-slide>
            @endforeach
        </swiper-container>

        <div class="modal fade" id="cvModal" tabindex="-1" aria-labelledby="cvModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-end">
                <div class="modal-content" style="border: none">
                    <div class="modal-header mt-4" style="border: none">
                        <h5 class="modal-title" id="cvName">Kişi İsmi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="cvText">Burada kişinin CV bilgileri yer alacaktır.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(".custom-carousel").owlCarousel({
            autoWidth: true,
            loop: true
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let trainersSection = document.getElementById("trainers-index");

            function revealOnScroll() {
                let scrollPosition = window.scrollY;
                let triggerPoint = 100;

                if (scrollPosition > triggerPoint) {
                    trainersSection.classList.add("visible");
                    window.removeEventListener("scroll", revealOnScroll);
                }
            }

            window.addEventListener("scroll", revealOnScroll);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let cvModal = document.getElementById("cvModal");
            let modalTitle = document.getElementById("cvName");
            let modalBody = document.getElementById("cvText");

            cvModal.addEventListener("show.bs.modal", function (event) {
                let button = event.relatedTarget;

                if (button) {
                    let name = button.getAttribute("data-name") || "Bilinmeyen";
                    let cv = button.getAttribute("data-cv") || "Bilgi bulunamadı.";

                    modalTitle.textContent = name;
                    modalBody.innerHTML = cv.replace(/\n/g, "<br>");
                }
            });

            document.querySelectorAll(".hover-button").forEach(button => {
                button.addEventListener("click", function () {
                    let parent = this.closest(".image-container");
                    let img = parent.querySelector("img");

                    if (img) {
                        let name = img.getAttribute("data-name") || "Bilinmeyen";
                        let cv = img.getAttribute("data-cv") || "Bilgi bulunamadı.";

                        modalTitle.textContent = name;
                        modalBody.innerHTML = cv.replace(/\n/g, "<br>");

                        let modal = new bootstrap.Modal(cvModal);
                        modal.show();
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('heroVideo');
            const volumeButton = document.getElementById('volumeButton');
            const volumeIcon = volumeButton.querySelector('i');

            video.muted = true;
            volumeIcon.classList.remove('fa-volume-up');
            volumeIcon.classList.add('fa-volume-mute');

            document.addEventListener('click', function handleClick() {
                document.removeEventListener('click', handleClick);
                video.play().catch(error => {
                    console.log("Video zaten oynuyor veya bir hata oluştu:", error);
                });
            }, { once: true });

            volumeButton.addEventListener('click', function() {
                if (video.muted) {
                    video.muted = false;
                    volumeIcon.classList.remove('fa-volume-mute');
                    volumeIcon.classList.add('fa-volume-up');
                    video.play().catch(error => {
                        console.log("Ses açıldıktan sonra oynatma hatası:", error);
                    });
                } else {
                    video.muted = true;
                    volumeIcon.classList.remove('fa-volume-up');
                    volumeIcon.classList.add('fa-volume-mute');
                }
            });
        });
    </script>

    <style>
        #hero {
            position: relative;
            overflow: hidden; /* Bu, videonun kesilmemesi için önemli olabilir */
            height: 100vh; /* Ekran yüksekliği kadar yer kaplasın */
        }

        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%; /* Video da tam alanı kaplasın */
            object-fit: cover; /* En boy oranını koruyarak alanı doldurur, bu kesilme sorununu çözer */
            z-index: 1; /* Video en altta olsun */
        }

        .video-hero-content-top-right {
            position: absolute;
            top: 20px; /* Üstten boşluk */
            right: 70px; /* Ses düğmesinin soluna yerleştirmek için ayarlandı */
            z-index: 10;
            color: white;
            text-align: right;
            max-width: 400px;
        }

        .video-hero-content-top-right h2 {
            font-size: 2em;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .video-hero-content-top-right .d-flex {
            margin-top: 20px;
            justify-content: flex-end;
        }

        .volume-control-container-top-right {
            position: absolute;
            top: 20px; /* Başlıkla aynı hizaya */
            right: 20px; /* En sağa */
            z-index: 1001;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            padding: 5px;
            display: flex;
            align-items: center;
        }

        .volume-button {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
        }

        .volume-button:hover {
            color: #007bff;
        }

        .volume-button i {
            font-size: 1.2em;
        }

        /* Responsive ayarlar */
        @media (max-width: 991.98px) {
            .video-hero-content-top-right {
                top: 10px;
                right: 60px; /* Ses düğmesinin soluna göre ayar */
                max-width: 300px;
            }
            .video-hero-content-top-right h2 {
                font-size: 1.8em;
            }
            .volume-control-container-top-right {
                top: 10px;
                right: 10px;
            }
            .volume-button {
                font-size: 20px;
                width: 25px;
                height: 25px;
            }
            .volume-button i {
                font-size: 1.1em;
            }
        }

        @media (max-width: 767.98px) {
            .video-hero-content-top-right {
                top: 5px;
                right: 55px; /* Ses düğmesinin soluna göre ayar */
                max-width: 250px;
            }
            .video-hero-content-top-right h2 {
                font-size: 1.5em;
            }
            .volume-control-container-top-right {
                top: 5px;
                right: 5px;
            }
            .volume-button {
                font-size: 18px;
                width: 20px;
                height: 20px;
            }
            .volume-button i {
                font-size: 1em;
            }
        }
        /* hero bölümündeki dark‑background overlay’ini kaldır */
        #hero.dark-background::before,
        #hero.dark-background::after {
            display: none !important;
            content: none !important;
            background: transparent !important;
        }

        /* Veya direkt arka plan rengini sıfırla */
        #hero.dark-background {
            background: transparent !important;
        }

    </style>

@endsection
