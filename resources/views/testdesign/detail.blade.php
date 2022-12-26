@extends('testdesign.layout')

@section('body')
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-8">
                <div id="carouselInteriorPlanIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselInteriorPlanIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselInteriorPlanIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselInteriorPlanIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#carouselInteriorPlanIndicators" data-bs-slide-to="3"
                            aria-label="Slide 4"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ URL::asset('/img/design/detail1_1.jpeg') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('/img/design/detail1_2.jpeg') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('/img/design/detail1_3.jpeg') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('/img/design/detail1_4.jpeg') }}" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselInteriorPlanIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselInteriorPlanIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-lg-4 mt-5 mt-lg-1">
                <div class="card bg-dark card-shadow"card-shadow >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">WIDTH</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">24'0"</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">DEPTH</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">24'0"</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">LIVING AREA</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">1050 sq.ft</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">BEDROOM(S)</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">2</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">BATHS</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">2</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">FLOOR</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">1</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-12">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ URL::asset('/img/design/detail2_1.jpeg') }}" class="d-block w-100"
                                alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('/img/design/detail2_2.jpeg') }}" class="d-block w-100"
                                alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <p class="h5 font-weight-bolder Text-secondary">
                    1st LEVEL
                </p>
            </div>
            <div class="col-12">
                <div class="card bg-dark card-shadow"card-shadow >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white mb-0 h5 font-weight-bolder">ຫ້ອງ</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white mb-0 h5 font-weight-bolder">SIZES</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white mb-0 h5 font-weight-bolder">CEILING</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">Kitchen</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">11'8" x 10'4"</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">~ 8'0"</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">Dining room</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">11'8" x 10'4"</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">~ 8'0"</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">Foyer</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">11'8" x 10'4"</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">~ 8'0"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-12">
                <p class="h5 font-weight-bolder Text-secondary">
                    2nd LEVEL
                </p>
            </div>
            <div class="col-12">
                <div class="card bg-dark card-shadow"card-shadow >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white mb-0 h5 font-weight-bolder">ຫ້ອງ</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white mb-0 h5 font-weight-bolder">SIZES</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white mb-0 h5 font-weight-bolder">CEILING</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">Kitchen</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">11'8" x 10'4"</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">~ 8'0"</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">Dining room</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">11'8" x 10'4"</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">~ 8'0"</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">Foyer</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">11'8" x 10'4"</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="text-white font-weight-lighter mb-0 ">~ 8'0"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <p class="h4 font-weight-bolder text-uppercase headertext-symbol Text-secondary">
                    Description
                </p>
            </div>
            <div class="col-12">
                <div class="card bg-dark card-shadow"card-shadow >
                    <div class="card-body">
                        <p class="text-white">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam et fuga ullam, perspiciatis
                            omnis dicta, eos asperiores quis ipsum cumque itaque quod architecto doloremque, consequatur
                            deserunt qui mollitia! Ipsa, eaque?
                        </p>
                        <p class="text-white">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam et fuga ullam, perspiciatis
                            omnis dicta, eos asperiores quis ipsum cumque itaque quod architecto doloremque, consequatur
                            deserunt qui mollitia! Ipsa, eaque?
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-12">
                <p class="h4 font-weight-bolder text-uppercase headertext-symbol Text-secondary">
                    Recommended
                </p>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <a href="/testdesign/detail">
                    <div class="card bg-dark card-shadow plan-card">
                        <img class="card-img-top plan-card-image" src="{{ URL::asset('/img/design/1.jpeg') }}"
                            alt="Card image cap">
                        <div class="card-body plan-card-body bg-dark">
                            <p class="text-white font-weight-bolder h5">
                                Plan name.
                            </p>
                            <p class="text-white font-weight-lighter mb-0">
                                category name.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <a href="/testdesign/detail">
                    <div class="card bg-dark card-shadow plan-card">
                        <img class="card-img-top plan-card-image" src="{{ URL::asset('/img/design/1.jpeg') }}"
                            alt="Card image cap">
                        <div class="card-body plan-card-body bg-dark">
                            <p class="text-white font-weight-bolder h5">
                                Plan name.
                            </p>
                            <p class="text-white font-weight-lighter mb-0">
                                category name.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <a href="/testdesign/detail">
                    <div class="card bg-dark card-shadow plan-card">
                        <img class="card-img-top plan-card-image" src="{{ URL::asset('/img/design/1.jpeg') }}"
                            alt="Card image cap">
                        <div class="card-body plan-card-body bg-dark">
                            <p class="text-white font-weight-bolder h5">
                                Plan name.
                            </p>
                            <p class="text-white font-weight-lighter mb-0">
                                category name.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <a href="/testdesign/detail">
                    <div class="card bg-dark card-shadow plan-card">
                        <img class="card-img-top plan-card-image" src="{{ URL::asset('/img/design/1.jpeg') }}"
                            alt="Card image cap">
                        <div class="card-body plan-card-body bg-dark">
                            <p class="text-white font-weight-bolder h5">
                                Plan name.
                            </p>
                            <p class="text-white font-weight-lighter mb-0">
                                category name.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <br />
    </div>
@endsection
