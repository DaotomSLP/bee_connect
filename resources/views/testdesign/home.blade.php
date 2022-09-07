@extends('testdesign.layout')

@section('body')
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-12">
                <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
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
                            <a href="/testdesign/detail"><img src="{{ URL::asset('/img/design/1.jpeg') }}" class="d-block w-100" alt="..."></a>
                        </div>
                        <div class="carousel-item">
                            <a href="/testdesign/detail"><img src="{{ URL::asset('/img/design/2.jpeg') }}" class="d-block w-100" alt="..."></a>
                        </div>
                        <div class="carousel-item">
                            <a href="/testdesign/detail"><img src="{{ URL::asset('/img/design/3.jpeg') }}" class="d-block w-100" alt="..."></a>
                        </div>
                    </div>
                    <button class="carousel-control-prev Text-secondary" type="button" data-bs-target="#carouselExampleIndicators"
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
    </div>
@endsection
