<section class="hero_area_slider  owl-carousel">
    @if ($homeSliders->isEmpty())
        <div class="hero_area" id='slider-1'
            style="background-image: url('public/uploads/home_slider/demo-slider.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="hero_area_inner">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @foreach ($homeSliders as $homeSlider)
            <div class="hero_area" id='slider-1'
                style="background-image: url('{{ asset($homeSlider->image) }}')">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="hero_area_inner">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</section>
