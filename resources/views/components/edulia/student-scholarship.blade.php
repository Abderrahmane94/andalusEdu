<div class="container section_padding px-3 px-sm-0">
    <div class="common_data_table">
        <h4 class="text-center mb-5">{{$tableheading}}</h4>
        <div class="scholar_student_table">
            <table class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        @if($studentphoto == 1)
                            <th>{{$phototitle}}</th>
                        @endif
                        @if($name == 1)
                            <th>{{$nametitle}}</th>
                        @endif
                        @if($session == 1)
                            <th>{{$sessiontitle}}</th>
                        @endif
                        @if($scholarship == 1)
                            <th>{{$scholarshiptitle}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if($studentphoto == 1)
                            <td>
                                <img src="assets/img/student/1.jpeg" class="student_image" alt="student image">
                            </td>
                        @endif
                        @if($name == 1)
                            <td>মো: হাবিবুর রাহমান</td>
                        @endif
                        @if($session == 1)
                            <td>২০০০-২০০৫</td>
                        @endif
                        @if($scholarship == 1)
                            <td>জুনিয়র বৃত্তি ২০০২</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>