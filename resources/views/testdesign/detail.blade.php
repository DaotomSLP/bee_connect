@extends('testdesign.layout')

@section('body')
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-8">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ URL::asset('/img/bg2.jpg') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('/img/city.jpg') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('/img/cover.jpg') }}" class="d-block w-100" alt="...">
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
            <div class="col-lg-4">
                <div class="card bg-dark">
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
                                <p class="text-white mb-0">Baths</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">2</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="text-white mb-0">Floor</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="text-white mb-0">1</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
