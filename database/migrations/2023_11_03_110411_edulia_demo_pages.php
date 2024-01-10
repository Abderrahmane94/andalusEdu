
<?php

use App\SmGeneralSettings;
use App\Models\FrontResult;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{

    function replace_array_recursive(string $needle, string $replace, array &$haystack)
    {
        array_walk_recursive(
            $haystack,
            function (&$item, $key, $data) {
                $item = str_replace($data['needle'], $data['replace'], $item);
                return $item;
            },
            ['needle' => $needle, 'replace' => $replace]
        );
    }

    public function up(): void
    {
        $filesInFolder = File::files(resource_path('/views/themes/edulia/demo/'));
        foreach ($filesInFolder as $path) {
            $file = pathinfo($path);
            if (file_exists($file['dirname'] . '/' . $file['basename'])) {
                $file_content =  file_get_contents(($file['dirname'] . '/' . $file['basename']));
                $file_data = json_decode($file_content, true);
                $this->replace_array_recursive("[App_url]", (url('/')), $file_data);
                if ($file_data) {
                    $check_exist  = DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->where('school_id', 1)->where('slug', $file_data['slug'])->first();
                    if (!$check_exist) {

                        DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->insert(
                            [
                                'name' => $file_data['name'],
                                'title' => $file_data['title'],
                                'description' => $file_data['description'],
                                'slug' => $file_data['slug'],
                                'settings' => json_encode($file_data['settings']),
                                'home_page' => $file_data['home_page'],
                                'status' => 'published',
                                'school_id' => 1
                            ]
                        );
                    }
                }
            }
        }


        $builderPages = DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->whereIn('slug', ['home', 'aboutus-page', 'course', 'blog', 'gallery', 'result'])->get();
        foreach ($builderPages as $builderPage) {
            $data = new SmHeaderMenuManager();
            $data->type = 'sPages';
            $data->element_id = $builderPage->id;
            $data->title = $builderPage->name;
            $data->link = '/' . $builderPage->slug;
            $data->theme = 'edulia';
            $data->school_id = $builderPage->school_id;
            if ($builderPage->slug == 'home') {
                $data->position = 1;
            } elseif ($builderPage->slug == 'aboutus-page') {
                $data->position = 2;
            } elseif ($builderPage->slug == 'course') {
                $data->position = 3;
            } elseif ($builderPage->slug == 'gallery') {
                $data->position = 5;
            } elseif ($builderPage->slug == 'result') {
                $data->position = 6;
            }
            $data->save();
        }


        $othersData = new SmHeaderMenuManager();
        $othersData->type = 'customLink';
        $othersData->element_id = NULL;
        $othersData->title = 'Others';
        $othersData->link = NULL;
        $othersData->position = 7;
        $othersData->theme =  'edulia';
        $othersData->school_id = 1;
        $othersData->save();

        $blogData = new SmHeaderMenuManager();
        $blogData->type = 'customLink';
        $blogData->element_id = NULL;
        $blogData->title = 'Blog';
        $blogData->link = url('/blog-list');
        $blogData->position = 4;
        $blogData->theme =  'edulia';
        $blogData->school_id = 1;
        $blogData->save();

        $builderPagesDatas = DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->whereIn('slug', ['academic-calendars', 'class-routines', 'events', 'exam-routine', 'facilities', 'noticeboard', 'student-lists', 'individual-result', 'tuition-fees'])->get();
        foreach ($builderPagesDatas as $key => $otherPage) {
            $storeData = new SmHeaderMenuManager();
            $storeData->type = 'sPages';
            $storeData->position = $key + 1;
            $storeData->element_id = $otherPage->id;
            $storeData->title = $otherPage->name;
            $storeData->parent_id = $othersData->id;
            $storeData->link = '/' . $otherPage->slug;
            $storeData->theme = 'edulia';
            $storeData->school_id = $otherPage->school_id;
            $storeData->save();
        }

        
        Artisan::call('storage:link');

        DB::table('sm_notice_boards')->insert([
            [
                'notice_title' => 'This is a sample notice 1',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 2',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 3',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 4',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 5',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
        ]);

        $frontResultDatas = [
            'Science' => 'public/uploads/front_result/sci.jpg', 
            'Arts' => 'public/uploads/front_result/art.jpg', 
            'Commerce' => 'public/uploads/front_result/comm.png'
        ];

        foreach($frontResultDatas as $key => $frontResultData){
            $data = new FrontResult();
            $data->title = $key;
            $data->publish_date = date('Y-m-d');
            $data->result_file = $frontResultData;
            $data->school_id = 1;
            $data->save();
        }

        Artisan::call('optimize:clear');
    }

    public function down(): void
    {
        //
    }
};
