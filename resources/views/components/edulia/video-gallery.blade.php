<div class="video-gallery">
    <div class="row">
        @if ($videoGalleries->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
            <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                    href="{{ URL::to('/video-gallery') }}">@lang('edulia.video_gallery')</a></p>
        @else
            @foreach ($videoGalleries as $videoGallery)
                @php
                    $variable = substr($videoGallery->video_link, 32, 11);
                @endphp
                <div class="col-lg-{{ $column }}">
                    <div class="single-video-item">
                        <a href='https://www.youtube.com/watch?v={{ $variable }}' class="gallery_item video">
                            <div class="gallery_item_img">
                                <img src="https://img.youtube.com/vi/{{ $variable }}/maxresdefault.jpg"
                                    alt="video thumbnail">
                            </div>
                            <div class="gallery_item_inner">
                                <h4>{{ $videoGallery->name }}</h4>
                                <p>{{ $videoGallery->description }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
