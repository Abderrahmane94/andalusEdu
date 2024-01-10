<!-- blog area start -->
<section class="section_padding blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-12">
                <div class="blog_card">
                    <div class="row">
                        <div class="col-lg-8 col-md-7">
                            <x-blog-list
                                :count="pagesetting('blog_count')"
                                :sorting="pagesetting('blog_data_sorting')" 
                                :btntext="pagesetting('blog_read_more_text')" 
                                >
                            </x-blog-list>
                        </div>
                        <div class="col-lg-4 col-md-5">
                            @if (pagesetting('blog_search') == 1)
                                <div class="blog_widget">
                                    <div class="blog_widget_search">
                                        <label for="#" class='blog_widget_search_icon'><i class="far fa-search"></i></label>
                                        <input type="text" class="input-control-input" id="blogallcontentsearch" placeholder='{{pagesetting('blog_search_placeholder')}}'>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- blog area end -->
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $("#blogallcontentsearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".searchBlogContent").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
@endpushonce