
@pushonce(config('pagebuilder.site_style_var'))
    <!-- Data Tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.jqueryui.min.css">
@endpushonce

<div class="container section_padding px-3 px-sm-0">
    <x-student-scholarship 
            :tableheading="pagesetting('student_scholarship_table_heading')"
            :phototitle="pagesetting('photo_title')"
            :nametitle="pagesetting('name_title')"
            :sessiontitle="pagesetting('session_title')"
            :scholarshiptitle="pagesetting('scholarship_title')"
            :studentphoto="pagesetting('student_scholarship_student_photo')"
            :name="pagesetting('student_scholarship_student_name')"
            :session="pagesetting('student_scholarship_student_session')"
            :scholarship="pagesetting('student_scholarship_student')"
    ></x-student-scholarship>
</div>

@pushonce(config('pagebuilder.site_script_var'))
        <!-- Data Tables -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.5.0/dataTables.responsive.min.js">
        </script>
@endpushonce