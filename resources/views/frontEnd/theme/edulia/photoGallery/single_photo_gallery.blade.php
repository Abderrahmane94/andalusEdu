@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
{{headerContent()}}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.gallery_details') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.gallery_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding gallery">
        <div class="container">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <div class="gallery_details">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section_title">
                                <h2>{{ $gallery_feature->name }}</h2>
                                <p>{{ $gallery_feature->description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" data-pswp>
                        <div class="col-md-12">
                            <a href='assets/img/gallery/large/1.png'
                                class="gallery_details_item gallery_details_img_preview"><img
                                    src="{{ asset($gallery_feature->feature_image) }}"
                                    alt=""></a>
                        </div>
                        @foreach ($galleries as $gallery)
                            <div class="col-md-6">
                                <a href='{{ asset($gallery->gallery_image) }}' class="gallery_details_item"><img
                                        src="{{ asset($gallery->gallery_image) }}" alt=""></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{footerContent()}}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $(document).ready(function() {
            $(document).on('click', '.newsReplyBtn', function(e) {
                e.preventDefault();
                var commentId = $(this).data('comment-id');
                $('.replyDiv_' + commentId).slideToggle();
                $('.normalComment').slideToggle();
                $('.replyDiv_' + commentId).find('.parentId').val(commentId);
            })
        })
    </script>
@endpushonce
