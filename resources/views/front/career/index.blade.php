@extends('front.layout.app')

@section('content')

    <div class="page-title page-title_2 aos-init aos-animate" data-aos="fade" >
        <div class="heading" >
            <div class="container">
                <div class="row d-flex justify-content-start ">
                    <div class="col-lg-5">
                        <h1>Kariyer Sayfası</h1>
                        <p class="mb-0">Çetin İnşaat, modern teknolojiyle müşteri beklentilerini kalite-fiyat dengesiyle karşılar ve yenilikçi, kaliteli hizmeti hedefler.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{route('homePage.index')}}">Ana Sayfa</a></li>
                    <li class="current">Kariyer</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="about-us" class="section about-us">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-5 order-1 order-lg-2 aos-init aos-animate default_img2" data-aos="fade-up" data-aos-delay="100">
                    <div class="image-container">
                        <img src="{{asset('uploads/career/'. $page->image)}}" class="img-fluid" alt="">
                    </div>
                </div>

                <div class="col-lg-6 order-2 order-lg-1 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <h3>Kariyer</h3>
                    <p class="fst-italic">

                        {!! $part1 !!}

                    </p>
                </div>

            </div>
            <div class="row gy-4 mt-3">

                <div class="col-lg-5 order-1 order-lg-1 aos-init aos-animate default_img" data-aos="fade-up" data-aos-delay="100" >
                    <img src="{{asset('uploads/career/'. $page->image)}}" class="img-fluid" alt="" style="margin-bottom: 30px">
                </div>

                <div class="col-lg-6 order-2 order-lg-2 content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <p class="fst-italic">
                        {!! $part2 !!}

                    </p>
                </div>

            </div>
        </div>

    </section>






@endsection

