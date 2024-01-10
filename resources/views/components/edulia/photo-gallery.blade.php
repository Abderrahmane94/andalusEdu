<div class="row mb-minus-24">
    @if ($photoGalleries->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
        <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                href="{{ URL::to('/photo-gallery') }}">@lang('edulia.photo_gallery')</a></p>
    @else
        @foreach ($photoGalleries as $photoGallery)
            <div class="col-lg-{{ $column }}">
                <a href='{{ route('frontend.gallery-details', $photoGallery->id) }}' class="gallery_item">
                    <div class="gallery_item_img"><img src="{{ asset($photoGallery->feature_image) }}" alt="">
                    </div>
                    <div class="gallery_item_inner">
                        <h4>{{ $photoGallery->name }}</h4>
                        <p>{{ $photoGallery->description }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    @endif
</div>
