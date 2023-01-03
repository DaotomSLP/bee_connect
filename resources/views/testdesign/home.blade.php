@extends('testdesign.layout')

@section('body')
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-3 d-lg-block d-none">
                <div class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light card-shadow"
                    id="ftco-category-navbar" style="display: block">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-cate-nav"
                        aria-controls="ftco-cate-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars text-white"></span>
                    </button>
                    <div class="d-inline" id="ftco-cate-nav">
                        <ul class="navbar-nav" id="category-nav">
                            @for ($i = 0; $i < 5; $i++)
                                <li class="nav-item dropdown dropdown-category">
                                    <a class="nav-link text-wrap text-break" href="#" id="dropdown04"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        House-{{ $i + 1 }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-1 dropdown-category-menu rounded-0"
                                        aria-labelledby="dropdown04">
                                        <div class="bg-dark ml-1" id="ftco-nav-1">
                                            <ul class="navbar-nav" id="category-nav">
                                                <li class="nav-item dropdown dropdown-category-2">
                                                    <a class="nav-link text-wrap text-break" href="#" id="dropdown041"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">home</a>
                                                    <div class="dropdown-menu dropdown-menu-2 dropdown-category-menu rounded-0"
                                                        aria-labelledby="dropdown04">
                                                        <div class="bg-dark ml-1" id="ftco-nav-2">
                                                            <ul class="navbar-nav" id="category-nav">
                                                                <li class="nav-item dropdown dropdown-category-2">
                                                                    <a class="nav-link" href="#">Cate3</a>
                                                                </li>
                                                                <li class="nav-item dropdown dropdown-category-2">
                                                                    <a class="nav-link" href="#">Cate3</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="nav-item dropdown dropdown-category-2">
                                                    <a class="nav-link text-wrap text-break" href="#" id="dropdown041"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">home</a>
                                                    <div class="dropdown-menu dropdown-menu-2 dropdown-category-menu rounded-0"
                                                        aria-labelledby="dropdown04">
                                                        <div class="bg-dark ml-1" id="ftco-nav-2">
                                                            <ul class="navbar-nav" id="category-nav">
                                                                <li class="nav-item dropdown dropdown-category-2">
                                                                    <a class="nav-link" href="#">Cate3</a>
                                                                </li>
                                                                <li class="nav-item dropdown dropdown-category-2">
                                                                    <a class="nav-link" href="#">Cate3</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <a href="/testdesign/detail"><img src="{{ URL::asset('/img/design/1.jpeg') }}"
                                    class="d-block w-100" alt="..."></a>
                        </div>
                        <div class="carousel-item">
                            <a href="/testdesign/detail"><img src="{{ URL::asset('/img/design/2.jpeg') }}"
                                    class="d-block w-100" alt="..."></a>
                        </div>
                        <div class="carousel-item">
                            <a href="/testdesign/detail"><img src="{{ URL::asset('/img/design/3.jpeg') }}"
                                    class="d-block w-100" alt="..."></a>
                        </div>
                    </div>
                    <button class="carousel-control-prev Text-secondary" type="button"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
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
    </div>
@endsection
