<?php

namespace App\Http\Controllers;

use App;
use App\Imports\oyuncularImport;
use App\Mail\NotificationEmail;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Istatistik;
use App\Models\Lig;
use App\Models\Menajer;
use App\Models\Oyuncu;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Sezon;
use App\Models\Pozisyon;
use App\Models\Takim;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\TopicField;
use App\Models\User;
use App\Models\Webmail;
use App\Models\WebmasterSection;
use App\Models\WebmasterSetting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;
use Mail;
use Redirect;
use App\Helpers\Helper;
use SoulDoit\DataTable\SSP;
use App\Models\MacSonuc;
use App\Models\OyuncuDetay;
use App\Models\Kapsam;

class HomeController extends Controller
{
    public function __construct()
    {
        // check if script not installed yet.
        try {
            $WebmasterSettings = WebmasterSetting::find(1);
        } catch (\Exception $e) {
            // check for installation
            if (!File::exists('core/storage/installed')) {
                Redirect::to('/install')->send();
            }
        }

        // check if website is closed
        $this->close_check();
    }

    public function SEO($seo_url_slug = 0)
    {
        return $this->SEOByLang("", $seo_url_slug);
    }

    public function SEOByLang($lang = "", $seo_url_slug = 0)
    {
        if ($lang != "") {
            // Set Language
            App::setLocale($lang);
            \Session::put('locale', $lang);
        }
        $seo_url_slug = Str::slug($seo_url_slug, '-');

        switch ($seo_url_slug) {
            case "home" :
                return $this->HomePage();
                break;
            case "about" :
                $id = 1;
                $section = 1;
                return $this->topic($section, $id);
                break;
            case "privacy" :
                $id = 3;
                $section = 1;
                return $this->topic($section, $id);
                break;
            case "terms" :
                $id = 4;
                $section = 1;
                return $this->topic($section, $id);
                break;
        }
        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);
        $Current_Slug = "seo_url_slug_" . @Helper::currentLanguage()->code;
        $Default_Slug = "seo_url_slug_" . env('DEFAULT_LANGUAGE');
        $Current_Title = "title_" . @Helper::currentLanguage()->code;
        $Default_Title = "title_" . env('DEFAULT_LANGUAGE');

        $WebmasterSection1 = WebmasterSection::where($Current_Slug, $seo_url_slug)->orwhere($Default_Slug, $seo_url_slug)->first();
        if (!empty($WebmasterSection1)) {
            // MAIN SITE SECTION
            $section = $WebmasterSection1->id;
            return $this->topics($section, 0);
        } else {
            $WebmasterSection2 = WebmasterSection::where($Current_Title, $seo_url_slug)->orwhere($Default_Title, $seo_url_slug)->first();
            if (empty($WebmasterSection2)) {
                $AllWebmasterSections = WebmasterSection::where('status', 1)->get();
                foreach ($AllWebmasterSections as $TWebmasterSection) {
                    if ($TWebmasterSection->$Current_Title != "") {
                        $TTitle = $TWebmasterSection->$Current_Title;
                    } else {
                        $TTitle = $TWebmasterSection->$Default_Title;
                    }
                    $TTitle_slug = Str::slug($TTitle, '-');
                    if ($TTitle_slug == $seo_url_slug) {
                        $WebmasterSection2 = $TWebmasterSection;
                        break;
                    }
                }
            }
            if (!empty($WebmasterSection2)) {
                // MAIN SITE SECTION
                $section = $WebmasterSection2->id;
                return $this->topics($section, 0);
            } else {
                $Section = Section::where('status', 1)->where($Current_Slug, $seo_url_slug)->orwhere($Default_Slug, $seo_url_slug)->first();
                if (empty($Section)) {
                    $AllSection = Section::where('status', 1)->get();
                    foreach ($AllSection as $TSection) {
                        if ($TSection->$Current_Title != "") {
                            $TTitle = $TSection->$Current_Title;
                        } else {
                            $TTitle = $TSection->$Default_Title;
                        }
                        $TTitle_slug = Str::slug($TTitle, '-');
                        if ($TTitle_slug == $seo_url_slug) {
                            $Section = $TSection;
                            break;
                        }
                    }
                }

                if (!empty($Section)) {
                    // SITE Category
                    $section = $Section->webmaster_id;
                    $cat = $Section->id;
                    return $this->topics($section, $cat);
                } else {
                    $Topic = Topic::where('status', 1)->where($Current_Slug, $seo_url_slug)->orwhere($Default_Slug, $seo_url_slug)->first();
                    if (empty($Topic)) {
                        $AllTopics = Topic::where('status', 1)->get();
                        foreach ($AllTopics as $TTopic) {
                            if ($TTopic->$Current_Title != "") {
                                $TTitle = $TTopic->$Current_Title;
                            } else {
                                $TTitle = $TTopic->$Default_Title;
                            }
                            $TTitle_slug = Str::slug($TTitle, '-');
                            if ($TTitle_slug == $seo_url_slug) {
                                $Topic = $TTopic;
                                break;
                            }
                        }
                    }
                    if (!empty($Topic)) {
                        // SITE Topic
                        $section_id = $Topic->webmaster_id;
                        $WebmasterSection = WebmasterSection::find($section_id);
                        $section = $WebmasterSection->id;
                        $id = $Topic->id;
                        return $this->topic($section, $id);
                    } else {
                        // Not found
                        return redirect()->route("HomePage");
                    }
                }
            }
        }

    }

    public function HomePage()
    {
        return $this->HomePageByLang("");
    }

    public function HomePageByLang($lang = "")
    {

        if ($lang != "") {
            // Set Language
            App::setLocale($lang);
            \Session::put('locale', $lang);
        }
        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        // General for all pages
        $WebsiteSettings = Setting::find(1);

        // Home topics
        $HomeTopics = Topic::where([['status', 1], ['webmaster_id', $WebmasterSettings->home_content1_section_id], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['status', 1], ['webmaster_id', $WebmasterSettings->home_content1_section_id], ['expire_date', null]])->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->limit(12)->get();
        // Home photos
        $HomePhotos = Topic::where([['status', 1], ['webmaster_id', $WebmasterSettings->home_content2_section_id], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['status', 1], ['webmaster_id', $WebmasterSettings->home_content2_section_id], ['expire_date', null]])->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->limit(6)->get();
        // Home Partners
        $HomePartners = Topic::where([['status', 1], ['webmaster_id', $WebmasterSettings->home_content3_section_id], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['status', 1], ['webmaster_id', $WebmasterSettings->home_content3_section_id], ['expire_date', null]])->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->get();

        // Get Latest News
        $LatestNews = $this->latest_topics($WebmasterSettings->latest_news_section_id);

        // Get Home page slider banners
        $SliderBanners = Banner::where('section_id', $WebmasterSettings->home_banners_section_id)->where('status', 1)->orderby('row_no', 'asc')->get();

        // Get Home page Test banners
        $TextBanners = Banner::where('section_id', $WebmasterSettings->home_text_banners_section_id)->where('status', 1)->orderby('row_no', 'asc')->get();

        $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
        $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;

        $PageTitle = ""; // will show default site Title
        $PageDescription = $WebsiteSettings->$site_desc_var;
        $PageKeywords = $WebsiteSettings->$site_keywords_var;

        $HomePage = [];
        if ($WebmasterSettings->default_currency_id > 0) {
            $HomePage = Topic::where("status", 1)->find($WebmasterSettings->default_currency_id);
        }


        $widgetDatas['eniyi3luk'] = DB::select(
            DB::raw("
                SELECT
                    SUM(S3_deger) AS toplam_S3_deger,
                    IF(LOCATE('/', S3)>=0, SUM(SUBSTRING_INDEX(S3, '/', 1)), SUM(S3)) AS basarili_3luksayi,
                    IF(LOCATE('/', S3)>=0, SUM(SUBSTRING_INDEX(S3, '/', -1)), SUM(S3)) AS toplam_3luksayi,
                    (IF(LOCATE('/', S3)>=0, SUM(SUBSTRING_INDEX(S3, '/', -1)), SUM(S3)) - IF(LOCATE('/', S3)>=0, SUM(SUBSTRING_INDEX(S3, '/', 1)), SUM(S3))) AS basarisiz_3luksayi,
                    adsoyad,
                    sezon,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(sure))) AS toplamsure,
                    SUM(sayi) AS toplam_sayi
                FROM
                    istatistikler
                GROUP BY adsoyad
                ORDER BY toplam_S3_deger DESC
                LIMIT 5
            ")
        );

        $widgetDatas['eniyi5oyuncu'] = DB::select(
            DB::raw("
                SELECT
                    SUM(sayi) AS toplam_sayi,
                    IF(LOCATE('/', S2)>=0, SUM(SUBSTRING_INDEX(S2, '/', -1)), SUM(S2)) AS toplam_2liksayi,
                    IF(LOCATE('/', S3)>=0, SUM(SUBSTRING_INDEX(S3, '/', -1)), SUM(S3)) AS toplam_3luksayi,
                    adsoyad,
                    sezon,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(sure))) AS toplamsure
                FROM
                    istatistikler
                GROUP BY adsoyad
                ORDER BY toplam_sayi DESC
                LIMIT 5
            ")
        );

        $widgetDatas['engencler'] = DB::select(
            DB::raw("
                SELECT * FROM (
                        SELECT
                            SUM(ista.sayi) AS toplam_sayi,
                            IF(LOCATE('/', ista.S2)>=0, SUM(SUBSTRING_INDEX(ista.S2, '/', -1)), SUM(ista.S2)) AS toplam_2liksayi,
                            IF(LOCATE('/', ista.S3)>=0, SUM(SUBSTRING_INDEX(ista.S3, '/', -1)), SUM(ista.S3)) AS toplam_3luksayi,
                            ista.adsoyad,
                            ista.sezon,
                            SEC_TO_TIME(SUM(TIME_TO_SEC(ista.sure))) AS toplamsure,
                            TIMESTAMPDIFF(YEAR, oyun.dogum_tarihi, NOW()) AS yas,
                            oyun.dogum_tarihi
                        FROM
                            istatistikler ista
                            INNER JOIN oyuncular oyun ON oyun.adsoyad=ista.adsoyad
                        GROUP BY ista.adsoyad
                        ORDER BY oyun.dogum_tarihi DESC
                        LIMIT 5
                ) temp
                ORDER BY toplam_sayi DESC
            ")
        );

        $widgetDatas['haftanineniyisi'] = DB::select(
            DB::raw("
                SELECT
                    SUM(sayi) AS toplam_sayi,
                    IF(LOCATE('/', S2)>=0, SUM(SUBSTRING_INDEX(S2, '/', -1)), SUM(S2)) AS toplam_2liksayi,
                    IF(LOCATE('/', S3)>=0, SUM(SUBSTRING_INDEX(S3, '/', -1)), SUM(S3)) AS toplam_3luksayi,
                    adsoyad,
                    sezon,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(sure))) AS toplamsure
                FROM
                    istatistikler
                GROUP BY adsoyad
                ORDER BY toplam_sayi DESC
                LIMIT 5
            ")
        );



        return view("frontEnd.home",
            compact(
                "WebsiteSettings",
                "WebmasterSettings",
                "SliderBanners",
                "TextBanners",
                "PageTitle",
                "PageDescription",
                "PageKeywords",
                "PageTitle",
                "PageDescription",
                "PageKeywords",
                "HomePage",
                "HomeTopics",
                "HomePhotos",
                "HomePartners",
                "LatestNews",
                "widgetDatas"
            )
        );

    }

    public function topic($section = 0, $id = 0)
    {
        // check url slug
        if (!is_numeric($id)) {
            return $this->SEOByLang($section, $id);
        }

        $lang_dirs = array_filter(glob(App::langPath() . '/*'), 'is_dir');
        // check if this like "/ar/blog"
        if (in_array(App::langPath() . "/$section", $lang_dirs)) {
            return $this->topicsByLang($section, $id, 0);
        } else {
            return $this->topicByLang("", $section, $id);
        }
    }

    public function topicsByLang($lang = "", $section = 0, $cat = 0)
    {
        if (!is_numeric($cat)) {
            return $this->topicsByLang($section, $cat, 0);
        }
        if ($lang != "") {
            // Set Language
            App::setLocale($lang);
            \Session::put('locale', $lang);
        }

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        // get Webmaster section settings by name
        $Current_Slug = "seo_url_slug_" . @Helper::currentLanguage()->code;
        $Default_Slug = "seo_url_slug_" . env('DEFAULT_LANGUAGE');
        $WebmasterSection = WebmasterSection::where($Current_Slug, $section)->orwhere($Default_Slug, $section)->first();
        if (empty($WebmasterSection)) {
            // get Webmaster section settings by ID
            $WebmasterSection = WebmasterSection::find($section);
        }
        if (!empty($WebmasterSection)) {

            // if private redirect back to home
            if ($WebmasterSection->type == 4) {
                return redirect()->route("HomePage");
            }

            // count topics by Category
            $category_and_topics_count = array();
            $AllSections = Section::where('webmaster_id', '=', $WebmasterSection->id)->where('status', 1)->orderby('row_no', 'asc')->get();
            if (count($AllSections) > 0) {
                foreach ($AllSections as $AllSection) {
                    $category_topics = array();
                    $TopicCategories = TopicCategory::where('section_id', $AllSection->id)->get();
                    foreach ($TopicCategories as $category) {
                        $category_topics[] = $category->topic_id;
                    }

                    $Topics = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->whereIn('id', $category_topics)->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->get();
                    $category_and_topics_count[$AllSection->id] = count($Topics);
                }
            }

            // Get current Category Section details
            $CurrentCategory = Section::find($cat);
            // Get a list of all Category ( for side bar )
            $Categories = Section::where('webmaster_id', '=', $WebmasterSection->id)->where('father_id', '=',
                '0')->where('status', 1)->orderby('webmaster_id', 'asc')->orderby('row_no', 'asc')->get();

            if (!empty($CurrentCategory)) {
                $category_topics = array();
                $TopicCategories = TopicCategory::where('section_id', $cat)->get();
                foreach ($TopicCategories as $category) {
                    $category_topics[] = $category->topic_id;
                }
                // update visits
                $CurrentCategory->visits = $CurrentCategory->visits + 1;
                $CurrentCategory->save();
                // Topics by Cat_ID

                $Topics = Topic::where(function ($q) use ($WebmasterSection) {
                    $q->where([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]]);
                })->whereIn('id', $category_topics)->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->paginate(env('FRONTEND_PAGINATION'));

                // Get Most Viewed Topics fot this Category
                $TopicsMostViewed = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->whereIn('id', $category_topics)->orderby('visits', 'desc')->limit(3)->get();
            } else {
                // Topics if NO Cat_ID
                $Topics = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status',
                    1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->paginate(env('FRONTEND_PAGINATION'));
                // Get Most Viewed
                $TopicsMostViewed = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status',
                    1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->orderby('visits', 'desc')->limit(3)->get();
            }

            // General for all pages
            $WebsiteSettings = Setting::find(1);

            $SideBanners = Banner::where('section_id', $WebmasterSettings->side_banners_section_id)->where('status',
                1)->orderby('row_no', 'asc')->get();


            // Get Latest News
            $LatestNews = $this->latest_topics($WebmasterSettings->latest_news_section_id);

            // Page Title, Description, Keywords
            if (!empty($CurrentCategory)) {
                $seo_title_var = "seo_title_" . @Helper::currentLanguage()->code;
                $seo_description_var = "seo_description_" . @Helper::currentLanguage()->code;
                $seo_keywords_var = "seo_keywords_" . @Helper::currentLanguage()->code;
                $tpc_title_var = "title_" . @Helper::currentLanguage()->code;
                $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
                $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;
                if ($CurrentCategory->$seo_title_var != "") {
                    $PageTitle = $CurrentCategory->$seo_title_var;
                } else {
                    $PageTitle = $CurrentCategory->$tpc_title_var;
                }
                if ($CurrentCategory->$seo_description_var != "") {
                    $PageDescription = $CurrentCategory->$seo_description_var;
                } else {
                    $PageDescription = $WebsiteSettings->$site_desc_var;
                }
                if ($CurrentCategory->$seo_keywords_var != "") {
                    $PageKeywords = $CurrentCategory->$seo_keywords_var;
                } else {
                    $PageKeywords = $WebsiteSettings->$site_keywords_var;
                }
            } else {
                $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
                $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;

                $title_var = "title_" . @Helper::currentLanguage()->code;
                $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
                if ($WebmasterSection->$title_var != "") {
                    $PageTitle = $WebmasterSection->$title_var;
                } else {
                    $PageTitle = $WebmasterSection->$title_var2;
                }

                $PageDescription = $WebsiteSettings->$site_desc_var;
                $PageKeywords = $WebsiteSettings->$site_keywords_var;

            }
            // .. end of .. Page Title, Description, Keywords

            // Send all to the view
            $view = "topics";
            if ($WebmasterSection->type == 5) {
                $view = "table";
            }
            $statics = [];
            foreach ($WebmasterSection->customFields as $customField) {
                if ($customField->in_statics && ($customField->type == 6 || $customField->type == 7)) {
                    $cf_details_var = "details_" . @Helper::currentLanguage()->code;
                    $cf_details_var2 = "details_en" . env('DEFAULT_LANGUAGE');
                    if ($customField->$cf_details_var != "") {
                        $cf_details = $customField->$cf_details_var;
                    } else {
                        $cf_details = $customField->$cf_details_var2;
                    }
                    $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                    $line_num = 1;
                    $statics_row = [];
                    foreach ($cf_details_lines as $cf_details_line) {
                        if ($customField->type == 6) {
                            $tids = TopicField::select("topic_id")->where("field_id", $customField->id)->where("field_value", $line_num);
                        } else {
                            $tids = TopicField::select("topic_id")->where("field_id", $customField->id)->where("field_value", 'like', '%' . $line_num . '%');
                        }
                        $Topics_count = Topic::where('webmaster_id', '=', $WebmasterSection->id)->whereIn('id', $tids)->count();
                        $statics_row[$line_num] = $Topics_count;
                        $line_num++;
                    }
                    $statics[$customField->id] = $statics_row;
                }
            }

            return view("frontEnd." . $view,
                compact("WebsiteSettings",
                    "WebmasterSettings",
                    "LatestNews",
                    "SideBanners",
                    "WebmasterSection",
                    "Categories",
                    "Topics",
                    "CurrentCategory",
                    "PageTitle",
                    "PageDescription",
                    "PageKeywords",
                    "TopicsMostViewed",
                    "category_and_topics_count",
                    "statics"));

        } else {

            return $this->SEOByLang($lang, $section);
        }

    }

    public function topicByLang($lang = "", $section = 0, $id = 0)
    {

        $not_container = false;

        if ($lang != "") {
            // Set Language
            App::setLocale($lang);
            \Session::put('locale', $lang);
        }

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        // check for pages called by name not id
        switch ($section) {
            case "about" :
                $id = 1;
                $section = 1;
                break;
            case "privacy" :
                $id = 3;
                $section = 1;
                break;
            case "terms" :
                $id = 4;
                $section = 1;
                break;
        }


        // get Webmaster section settings by name
        $Current_Slug = "seo_url_slug_" . @Helper::currentLanguage()->code;
        $Default_Slug = "seo_url_slug_" . env('DEFAULT_LANGUAGE');
        $WebmasterSection = WebmasterSection::where($Current_Slug, $section)->orwhere($Default_Slug, $section)->first();
        if (empty($WebmasterSection)) {
            // get Webmaster section settings by ID
            $WebmasterSection = WebmasterSection::find($section);
        }
        if (!empty($WebmasterSection)) {

            // if private redirect back to home
            if ($WebmasterSection->type == 4) {
                return redirect()->route("HomePage");
            }

            // count topics by Category
            $category_and_topics_count = array();
            $AllSections = Section::where('webmaster_id', '=', $WebmasterSection->id)->where('status', 1)->orderby('row_no', 'asc')->get();
            if (count($AllSections) > 0) {
                foreach ($AllSections as $AllSection) {
                    $category_topics = array();
                    $TopicCategories = TopicCategory::where('section_id', $AllSection->id)->get();
                    foreach ($TopicCategories as $category) {
                        $category_topics[] = $category->topic_id;
                    }

                    $Topics = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->whereIn('id', $category_topics)->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->get();
                    $category_and_topics_count[$AllSection->id] = count($Topics);
                }
            }

            $Topic = Topic::where('status', 1)->find($id);

            if (!empty($Topic) && ($Topic->expire_date == '' || ($Topic->expire_date != '' && $Topic->expire_date >= date("Y-m-d")))) {
                // update visits
                $Topic->visits = $Topic->visits + 1;
                $Topic->save();

                // Get current Category Section details
                $CurrentCategory = array();
                $TopicCategory = TopicCategory::where('topic_id', $Topic->id)->first();
                if (!empty($TopicCategory)) {
                    $CurrentCategory = Section::find($TopicCategory->section_id);
                }
                // Get a list of all Category ( for side bar )
                $Categories = Section::where('webmaster_id', '=', $WebmasterSection->id)->where('status',
                    1)->where('father_id', '=', '0')->orderby('webmaster_id', 'asc')->orderby('row_no', 'asc')->get();

                // Get Most Viewed
                $TopicsMostViewed = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->orderby('visits', 'desc')->limit(3)->get();

                // General for all pages
                $WebsiteSettings = Setting::find(1);

                $SideBanners = Banner::where('section_id', $WebmasterSettings->side_banners_section_id)->where('status',
                    1)->orderby('row_no', 'asc')->get();

                // Get Latest News
                $LatestNews = $this->latest_topics($WebmasterSettings->latest_news_section_id);

                // Page Title, Description, Keywords
                $seo_title_var = "seo_title_" . @Helper::currentLanguage()->code;
                $seo_description_var = "seo_description_" . @Helper::currentLanguage()->code;
                $seo_keywords_var = "seo_keywords_" . @Helper::currentLanguage()->code;
                $tpc_title_var = "title_" . @Helper::currentLanguage()->code;
                $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
                $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;
                if ($Topic->$seo_title_var != "") {
                    $PageTitle = $Topic->$seo_title_var;
                } else {
                    $PageTitle = $Topic->$tpc_title_var;
                }
                if ($Topic->$seo_description_var != "") {
                    $PageDescription = $Topic->$seo_description_var;
                } else {
                    $PageDescription = $WebsiteSettings->$site_desc_var;
                }
                if ($Topic->$seo_keywords_var != "") {
                    $PageKeywords = $Topic->$seo_keywords_var;
                } else {
                    $PageKeywords = $WebsiteSettings->$site_keywords_var;
                }

                // .. end of .. Page Title, Description, Keywords
                if ($Topic->title_en == 'not-container') {
                    $not_container = true;
                }

                return view("frontEnd.topic",
                    compact("WebsiteSettings",
                        "WebmasterSettings",
                        "LatestNews",
                        "Topic",
                        "SideBanners",
                        "WebmasterSection",
                        "Categories",
                        "CurrentCategory",
                        "PageTitle",
                        "PageDescription",
                        "PageKeywords",
                        "TopicsMostViewed",
                        "not_container",
                        "category_and_topics_count"));

            } else {
                return redirect()->action('HomeController@HomePage');
            }
        } else {
            return redirect()->action('HomeController@HomePage');
        }
    }

    public function topics($section = 0, $cat = 0)
    {
        $lang_dirs = array_filter(glob(App::langPath() . '/*'), 'is_dir');
        // check if this like "/ar/blog"
        if (in_array(App::langPath() . "/$section", $lang_dirs)) {
            return $this->topicsByLang($section, $cat, 0);
        } else {
            return $this->topicsByLang("", $section, $cat);
        }
    }

    public function userTopics($id)
    {
        return $this->userTopicsByLang("", $id);
    }

    public function userTopicsByLang($lang = "", $id)
    {

        if ($lang != "") {
            // Set Language
            App::setLocale($lang);
            \Session::put('locale', $lang);
        }

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        // get User Details
        $User = User::find($id);
        if (!empty($User)) {


            // count topics by Category
            $category_and_topics_count = array();
            $AllSections = Section::where('status', 1)->orderby('row_no', 'asc')->get();
            if (!empty($AllSections)) {
                foreach ($AllSections as $AllSection) {
                    $category_topics = array();
                    $TopicCategories = TopicCategory::where('section_id', $AllSection->id)->get();
                    foreach ($TopicCategories as $category) {
                        $category_topics[] = $category->topic_id;
                    }

                    $Topics = Topic::where([['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['status', 1], ['expire_date', null]])->whereIn('id', $category_topics)->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->get();
                    $category_and_topics_count[$AllSection->id] = count($Topics);
                }
            }

            // Get current Category Section details
            $CurrentCategory = "none";
            $WebmasterSection = "none";
            // Get a list of all Category ( for side bar )
            $Categories = Section::where('father_id', '=',
                '0')->where('status', 1)->orderby('webmaster_id', 'asc')->orderby('row_no', 'asc')->get();

            // Topics if NO Cat_ID
            $Topics = Topic::where([['created_by', $User->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['created_by', $User->id], ['status', 1], ['expire_date', null]])->orderby('row_no', 'asc')->paginate(env('FRONTEND_PAGINATION'));
            // Get Most Viewed
            $TopicsMostViewed = Topic::where([['created_by', $User->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['created_by', $User->id], ['status', 1], ['expire_date', null]])->orderby('visits', 'desc')->limit(3)->get();

            // General for all pages
            $WebsiteSettings = Setting::find(1);

            $SideBanners = Banner::where('section_id', $WebmasterSettings->side_banners_section_id)->where('status',
                1)->orderby('row_no', 'asc')->get();


            // Get Latest News
            $LatestNews = $this->latest_topics($WebmasterSettings->latest_news_section_id);

            // Page Title, Description, Keywords
            $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
            $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;

            $PageTitle = $User->name;
            $PageDescription = $WebsiteSettings->$site_desc_var;
            $PageKeywords = $WebsiteSettings->$site_keywords_var;

            // .. end of .. Page Title, Description, Keywords

            // Send all to the view
            return view("frontEnd.topics",
                compact("WebsiteSettings",
                    "WebmasterSettings",
                    "LatestNews",
                    "User",
                    "SideBanners",
                    "WebmasterSection",
                    "Categories",
                    "Topics",
                    "CurrentCategory",
                    "PageTitle",
                    "PageDescription",
                    "PageKeywords",
                    "TopicsMostViewed",
                    "category_and_topics_count"));

        } else {
            // If no section name/ID go back to home
            return redirect()->action('HomeController@HomePage');
        }

    }

    public function searchTopics(Request $request)
    {

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        $search_word = $request->search_word;

        if ($search_word != "") {

            // count topics by Category
            $category_and_topics_count = array();
            $AllSections = Section::where('status', 1)->orderby('row_no', 'asc')->get();
            if (!empty($AllSections)) {
                foreach ($AllSections as $AllSection) {
                    $category_topics = array();
                    $TopicCategories = TopicCategory::where('section_id', $AllSection->id)->get();
                    foreach ($TopicCategories as $category) {
                        $category_topics[] = $category->topic_id;
                    }

                    $Topics = Topic::where([['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orWhere([['status', 1], ['expire_date', null]])->whereIn('id', $category_topics)->orderby('row_no', env("FRONTEND_TOPICS_ORDER", "asc"))->get();
                    $category_and_topics_count[$AllSection->id] = count($Topics);
                }
            }

            // Get current Category Section details
            $CurrentCategory = "none";
            $WebmasterSection = "none";
            // Get a list of all Category ( for side bar )
            $Categories = Section::where('father_id', '=',
                '0')->where('status', 1)->orderby('webmaster_id', 'asc')->orderby('row_no', 'asc')->get();

            // Topics if NO Cat_ID
            $Topics = Topic::where('title_' . Helper::currentLanguage()->code, 'like', '%' . $search_word . '%')
                ->orwhere('seo_title_' . Helper::currentLanguage()->code, 'like', '%' . $search_word . '%')
                ->orwhere('details_' . Helper::currentLanguage()->code, 'like', '%' . $search_word . '%')
                ->orderby('id', 'desc')->paginate(env('FRONTEND_PAGINATION'));
            // Get Most Viewed
            $TopicsMostViewed = Topic::where([['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['status', 1], ['expire_date', null]])->orderby('visits', 'desc')->limit(3)->get();

            // General for all pages
            $WebsiteSettings = Setting::find(1);

            $SideBanners = Banner::where('section_id', $WebmasterSettings->side_banners_section_id)->where('status', 1)->orderby('row_no', 'asc')->get();


            // Get Latest News
            $LatestNews = $this->latest_topics($WebmasterSettings->latest_news_section_id);

            // Page Title, Description, Keywords
            $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
            $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;

            $PageTitle = $search_word;
            $PageDescription = $WebsiteSettings->$site_desc_var;
            $PageKeywords = $WebsiteSettings->$site_keywords_var;

            // .. end of .. Page Title, Description, Keywords

            // Send all to the view
            return view("frontEnd.topics", compact(
                "WebsiteSettings",
                "WebmasterSettings",
                "LatestNews",
                "search_word",
                "SideBanners",
                "WebmasterSection",
                "Categories",
                "Topics",
                "CurrentCategory",
                "PageTitle",
                "PageDescription",
                "PageKeywords",
                "TopicsMostViewed",
                "category_and_topics_count"));

        } else {
            // If no section name/ID go back to home
            return redirect()->action('HomeController@HomePage');
        }

    }

    public function excelContact(Request $request): string
    {
//        try
//        {
        $excel_file = $request->file('excel');
        if ($excel_file->store('imported') !== FALSE) {

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly(['ANA SAYFA']); //Sadece Ana Sayfayı yükle
            $spreadSheet = $reader->load($excel_file);
            $workSheet = $spreadSheet->getSheetByName('ANA SAYFA');
            if (empty($workSheet)) {
                return 'ANA SAYFA, isimli sayfa bulunamadı!';
            }

            $startRow = 2;
            $maxRow = 10000;

            //>------------Sütun indexlerini oluştur------------------<
            $columns = [
                'adsoyad' => null,
                'ana_pozisyon' => null,
                'yan_pozisyon' => null,
                'alt_kimlik' => null,
                'boy' => null,
                'dogum_tarihi' => null,
                'menajer' => null,
                'oyuncu_ozellikleri' => null,
                'photo' => null,
                'status' => null
            ];
            for ($i = 16; $i <= 34; $i++) {
                $columns['takim_' . $i . '_' . ($i + 1) . '_1'] = null;
                $columns['takim_' . $i . '_' . ($i + 1) . '_2'] = null;
            }
            for ($c = 1; $c < 1000; $c++) {
                $headCol = trim($workSheet->getCellByColumnAndRow($c, 1)->getValue());
                if (empty($headCol)) {
                    break;
                } //sütun boşsa atla

                if ($headCol == 'İSİM') {
                    $columns['adsoyad'] = $c;
                }
                if ($headCol == 'ANA POZİSYON') {
                    $columns['ana_pozisyon'] = $c;
                }
                if ($headCol == 'YAN POZİSYON') {
                    $columns['yan_pozisyon'] = $c;
                }
                if ($headCol == 'ALT KİMLİK') {
                    $columns['alt_kimlik'] = $c;
                }
                if ($headCol == 'BOY(CM)') {
                    $columns['boy'] = $c;
                }
                if ($headCol == 'DOĞUM TARİHİ') {
                    $columns['dogum_tarihi'] = str_ireplace(' ', '', $c);
                }

                for ($i = 16; $i <= 34; $i++) {
                    if ($headCol == 'TAKIM 20' . $i . '-' . ($i + 1)) {
                        $columns['takim_' . $i . '_' . ($i + 1) . '_1'] = $c;
                    }
                    $columns['takim_' . $i . '_' . ($i + 1) . '_2'] = '';
                }

                if ($headCol == 'MENAJER') {
                    $columns['menajer'] = $c;
                }

                if ($headCol == 'MENAJER E-MAİL') {
                    $columns['menajer_email'] = $c;
                }

                if ($headCol == 'MENAJER TELEFON') {
                    $columns['menajer_telefon'] = $c;
                }

                if ($headCol == 'OYUNCU ÖZELLİKLERİ') {
                    $columns['oyuncu_ozellikleri'] = $c;
                }
                $columns['photo'] = '';
                $columns['status'] = 1;
            }
            //>------------------------------<

            //>-------------satırları oku-----------------<
            for ($r = $startRow; $r < $maxRow; $r++) {
                $rowCol = trim($workSheet->getCellByColumnAndRow($columns['adsoyad'], $r)->getValue());
                if (empty($rowCol)) {
                    break;
                } //satır boşsa atla

                //>------------------------------<
                $oyuncu_array = [];
                $oyuncu_array = [
                    'adsoyad' => trim($workSheet->getCellByColumnAndRow($columns['adsoyad'], $r)->getValue() ?? ''),
                    'ana_pozisyon' => trim($workSheet->getCellByColumnAndRow($columns['ana_pozisyon'], $r)->getValue() ?? ''),
                    'yan_pozisyon' => trim($workSheet->getCellByColumnAndRow($columns['yan_pozisyon'], $r)->getValue() ?? ''),
                    'alt_kimlik' => trim($workSheet->getCellByColumnAndRow($columns['alt_kimlik'], $r)->getValue() ?? ''),
                    'boy' => trim($workSheet->getCellByColumnAndRow($columns['boy'], $r)->getValue() ?? ''),
                    'dogum_tarihi' => Helper::excelToPhpDate($workSheet->getCellByColumnAndRow($columns['dogum_tarihi'], $r)->getValue()),
                    'menajer' => trim($workSheet->getCellByColumnAndRow($columns['menajer'], $r)->getValue() ?? ''),
                    'menajer_email' => trim($workSheet->getCellByColumnAndRow($columns['menajer_email'], $r)->getValue() ?? ''),
                    'menajer_telefon' => trim($workSheet->getCellByColumnAndRow($columns['menajer_telefon'], $r)->getValue() ?? ''),
                    'oyuncu_ozellikleri' => trim($workSheet->getCellByColumnAndRow($columns['oyuncu_ozellikleri'], $r)->getValue() ?? ''),
                    'photo' => '',
                    'status' => 1,
                ];
                for ($i = 16; $i <= 34; $i++) {
                    $name1 = 'takim_' . $i . '_' . ($i + 1) . '_1';
                    $name2 = 'takim_' . $i . '_' . ($i + 1) . '_2';

                    $oyuncu_array[$name1] = !empty($workSheet->getCellByColumnAndRow($columns[$name1], $r)->getValue()) ?
                        (\App\Helpers\Helper::iceriyorMu($workSheet->getCellByColumnAndRow($columns[$name1], $r)->getValue(), '-') ?
                            trim(explode('-', $workSheet->getCellByColumnAndRow($columns[$name1], $r)->getValue())[0] ?? '') :
                            trim($workSheet->getCellByColumnAndRow($columns[$name1], $r)->getValue())) : '';
                    $oyuncu_array[$name2] = !empty($workSheet->getCellByColumnAndRow($columns[$name2], $r)->getValue()) ?
                        (Helper::iceriyorMu($workSheet->getCellByColumnAndRow($columns[$name2], $r)->getValue(), '-') ?
                            trim(explode('-', $workSheet->getCellByColumnAndRow($columns[$name2], $r)->getValue())[1] ?? '') : '') : '';

                    if (empty($oyuncu_array[$name2])) {
                        $oyuncu_array[$name2] = $oyuncu_array[$name1];
                    }
                }
                //>------------------------------<


                $oyuncu_array2 = $oyuncu_array;
                unset($oyuncu_array2['adsoyad']);
                unset($oyuncu_array2['photo']);

                try {
                    Oyuncu::updateOrInsert($oyuncu_array);
                } catch (\Exception $err) {
                    Oyuncu::where(
                        [
                            'adsoyad' => $oyuncu_array['adsoyad']
                        ]
                    )->update($oyuncu_array2);
                }

                //if (!empty($oyuncu_array['pozisyon'])) { Pozisyon::updateOrCreate([ 'adi' => $oyuncu_array['pozisyon'] ]); } //Pozisyon ekle

                //>----------------Takımlar ekle--------------<
                for ($i = 16; $i <= 34; $i++) {
                    $name1 = 'takim_' . $i . '_' . ($i + 1) . '_1';
                    $name2 = 'takim_' . $i . '_' . ($i + 1) . '_2';

                    if (!empty($oyuncu_array[$name1])) {
                        try { Takim::updateOrInsert(['adi' => trim($oyuncu_array[$name1])]); } catch (\Exception $err) { }
                    }
                    if (!empty($oyuncu_array[$name2])) {
                        try { Takim::updateOrInsert(['adi' => trim($oyuncu_array[$name2])]); } catch (\Exception $err) { }
                    }
                }
                //>------------------------------<

                if (!empty($oyuncu_array['menajer'])) {
                    try { Menajer::updateOrInsert(['adi' => trim($oyuncu_array['menajer'])]); } catch (\Exception $err) { }
                } //Menajer ekle

            }

            //>------------------------------<

            $this->excelContactStatistic($excel_file->getRealPath()); //Istatistikleri ekle
        }
//        }
//        catch (\Exception $err) {
//            return $err->getMessage();
//        }

        return 'true';
    }


    public function excelContactStatistic(string $excel_file)
    {
//        try
//        {
        if (File::exists($excel_file)) {

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadSheet = $reader->load($excel_file);

            for ($i = 0; $i < $spreadSheet->getSheetCount(); $i++) {
                $workSheet = $spreadSheet->getSheet($i);
                $adsoyad = $spreadSheet->getSheetNames()[$i];

                $startRow = 2;
                $maxRow = 10000;

                //>------------Sütun indexlerini oluştur------------------<
                $columns = [
                    'rakip_takim' => null,
                    'tarih' => null,
                    'sure' => null,
                    'sayi' => null,
                    'AG' => null,
                    'SA' => null,
                    'S2' => null,
                    'S3' => null,
                    'SR' => null,
                    'HR' => null,
                    'TR' => null,
                    'AST' => null,
                    'TÇ' => null,
                    'TK' => null,
                    'BL' => null,
                    'FA' => null,
                    'VP' => null,
                    'sezon' => null,
                    'lig' => null,
                    'kapsam' => null,
                    'takimi' => null,
                ];
                for ($c = 1; $c < 1000; $c++) {
                    $headCol = trim($workSheet->getCellByColumnAndRow($c, 1)->getValue());
                    if (empty($headCol)) {
                        break;
                    } //sütun boşsa atla

                    if ($headCol == 'Tarih') {
                        $columns['tarih'] = $c;
                    }
                    if ($headCol == 'Rakip') {
                        $columns['rakip_takim'] = $c;
                    }
                    if ($headCol == 'Süre') {
                        $columns['sure'] = $c;
                    }
                    if ($headCol == 'Sayı') {
                        $columns['sayi'] = $c;
                    }
                    if ($headCol == 'AG') {
                        $columns['AG'] = $c;
                    }
                    if ($headCol == 'SA') {
                        $columns['SA'] = str_ireplace(' ', '', $c);
                    }
                    if ($headCol == '2S') {
                        $columns['S2'] = str_ireplace(' ', '', $c);
                    }
                    if ($headCol == '3S') {
                        $columns['S3'] = str_ireplace(' ', '', $c);
                    }
                    if ($headCol == 'SR') {
                        $columns['SR'] = $c;
                    }
                    if ($headCol == 'HR') {
                        $columns['HR'] = $c;
                    }
                    if ($headCol == 'TR') {
                        $columns['TR'] = $c;
                    }
                    if ($headCol == 'AS') {
                        $columns['AST'] = $c;
                    }
                    if ($headCol == 'TÇ') {
                        $columns['TÇ'] = $c;
                    }
                    if ($headCol == 'TK') {
                        $columns['TK'] = $c;
                    }
                    if ($headCol == 'BL') {
                        $columns['BL'] = $c;
                    }
                    if ($headCol == 'FA') {
                        $columns['FA'] = $c;
                    }
                    if ($headCol == 'VP') {
                        $columns['VP'] = $c;
                    }
                    if ($headCol == 'SEZON') {
                        $columns['sezon'] = $c;
                    }
                    if ($headCol == 'LİG') {
                        $columns['lig'] = $c;
                    }
                    if ($headCol == 'KAPSAM') {
                        $columns['kapsam'] = $c;
                    }
                    if ($headCol == 'TAKIMI') {
                        $columns['takimi'] = $c;
                    }
                }
                //>------------------------------<

                //>-------------satırları oku-----------------<
                for ($r = $startRow; $r < $maxRow; $r++) {
                    $ilkSutun = $workSheet->getCellByColumnAndRow($columns['tarih'], $r)->getValue();
                    if (empty($ilkSutun)) { //satır boşsa atla
                        continue;
                    }


                    //>------------------------------<
                    if (strlen($ilkSutun) == 7 && Helper::iceriyorMu($ilkSutun, '-')) {

                        $ilksezon = strlen(explode('-', $ilkSutun)[0])==4 ? explode('-', $ilkSutun)[0] : '20'.explode('-', $ilkSutun)[0];
                        $sonsezon = strlen(explode('-', $ilkSutun)[1])==4 ? explode('-', $ilkSutun)[1] : '20'.explode('-', $ilkSutun)[1];
                        $sezon = $ilksezon . '-' . $sonsezon;

                        $istatistik_array = [
                            'adsoyad' => trim($adsoyad),
                            'sezon' => trim($sezon),
                            'sure' => Date::excelToDateTimeObject($workSheet->getCellByColumnAndRow($columns['sure'], $r)->getCalculatedValue())->format('H:i:s'),
                            'sayi' => $workSheet->getCellByColumnAndRow($columns['sayi'], $r)->getCalculatedValue() ?? '0',
                            'SA' => trim($workSheet->getCellByColumnAndRow($columns['SA'], $r)->getCalculatedValue() ?? '0%'),
                            'S2' => trim($workSheet->getCellByColumnAndRow($columns['S2'], $r)->getCalculatedValue() ?? '0%'),
                            'S3' => trim($workSheet->getCellByColumnAndRow($columns['S3'], $r)->getCalculatedValue() ?? '0%'),
                            'SR' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['SR'], $r)->getCalculatedValue() ?? '0')),
                            'HR' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['HR'], $r)->getCalculatedValue() ?? '0')),
                            'TR' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['TR'], $r)->getCalculatedValue() ?? '0')),
                            'AST' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['AST'], $r)->getCalculatedValue() ?? '0')),
                            'TÇ' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['TÇ'], $r)->getCalculatedValue() ?? '0')),
                            'TK' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['TK'], $r)->getCalculatedValue() ?? '0')),
                            'BL' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['BL'], $r)->getCalculatedValue() ?? '0')),
                            'FA' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['FA'], $r)->getCalculatedValue() ?? '0')),
                            'VP' => floatVal(trim($workSheet->getCellByColumnAndRow($columns['VP'], $r)->getCalculatedValue() ?? '0')),
                        ];

                        $istatistik_array2 = $istatistik_array;
                        unset($istatistik_array2['adsoyad']);
                        unset($istatistik_array2['sezon']);

                        try {
                            OyuncuDetay::updateOrInsert($istatistik_array);
                        } catch (\Exception $err) {
                            OyuncuDetay::where(
                                [
                                    'adsoyad' => trim($istatistik_array['adsoyad']),
                                    'sezon' => trim($istatistik_array['sezon'])
                                ]
                            )->update($istatistik_array2);
                        }

                        continue;
                    }


                    $istatistik_array = [
                        'adsoyad' => trim($adsoyad),
                        'rakip_takim' => trim($workSheet->getCellByColumnAndRow($columns['rakip_takim'], $r)->getValue() ?? ''),
                        'tarih' => Helper::excelToPhpDate($workSheet->getCellByColumnAndRow($columns['tarih'], $r)->getValue()),
                        'sure' => Date::excelToDateTimeObject($workSheet->getCellByColumnAndRow($columns['sure'], $r)->getValue())->format('H:i:s'),
                        'sayi' => $workSheet->getCellByColumnAndRow($columns['sayi'], $r)->getValue() ?? '',
                        'AG' => trim($workSheet->getCellByColumnAndRow($columns['AG'], $r)->getValue() ?? ''),
                        'AG_deger' => 0,
                        'SA' => trim($workSheet->getCellByColumnAndRow($columns['SA'], $r)->getValue() ?? ''),
                        'SA_deger' => 0,
                        'S2' => trim($workSheet->getCellByColumnAndRow($columns['S2'], $r)->getValue() ?? ''),
                        'S2_deger' => 0,
                        'S3' => trim($workSheet->getCellByColumnAndRow($columns['S3'], $r)->getValue() ?? ''),
                        'S3_deger' => 0,
                        'SR' => trim($workSheet->getCellByColumnAndRow($columns['SR'], $r)->getValue() ?? ''),
                        'SR_deger' => 0,
                        'HR' => trim($workSheet->getCellByColumnAndRow($columns['HR'], $r)->getValue() ?? ''),
                        'HR_deger' => 0,
                        'TR' => trim($workSheet->getCellByColumnAndRow($columns['TR'], $r)->getValue() ?? ''),
                        'TR_deger' => 0,
                        'AST' => trim($workSheet->getCellByColumnAndRow($columns['AST'], $r)->getValue() ?? ''),
                        'AS_deger' => 0,
                        'TÇ' => trim($workSheet->getCellByColumnAndRow($columns['TÇ'], $r)->getValue() ?? ''),
                        'TÇ_deger' => 0,
                        'TK' => trim($workSheet->getCellByColumnAndRow($columns['TK'], $r)->getValue() ?? ''),
                        'TK_deger' => 0,
                        'BL' => trim($workSheet->getCellByColumnAndRow($columns['BL'], $r)->getValue() ?? ''),
                        'BL_deger' => 0,
                        'FA' => trim($workSheet->getCellByColumnAndRow($columns['FA'], $r)->getValue() ?? ''),
                        'FA_deger' => 0,
                        'VP' => trim($workSheet->getCellByColumnAndRow($columns['VP'], $r)->getValue() ?? ''),
                        'VP_deger' => 0,
                        'sezon' => trim($workSheet->getCellByColumnAndRow($columns['sezon'], $r)->getValue() ?? ''),
                        'lig' => trim($workSheet->getCellByColumnAndRow($columns['lig'], $r)->getValue() ?? ''),
                        'kapsam' => trim($workSheet->getCellByColumnAndRow($columns['kapsam'], $r)->getValue() ?? ''),
                        'takimi' => trim($workSheet->getCellByColumnAndRow($columns['takimi'], $r)->getValue() ?? ''),
                    ];

                    $istatistik_array['AG_deger'] = Helper::iceriyorMu($istatistik_array['AG'], '/') ? intval(trim(explode('/', $istatistik_array['AG'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['AG'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['AG'])[1] ?? 1))) : 0;
                    $istatistik_array['SA_deger'] = Helper::iceriyorMu($istatistik_array['SA'], '/') ? intval(trim(explode('/', $istatistik_array['SA'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['SA'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['SA'])[1] ?? 1))) : 0;
                    $istatistik_array['S2_deger'] = Helper::iceriyorMu($istatistik_array['S2'], '/') ? intval(trim(explode('/', $istatistik_array['S2'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['S2'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['S2'])[1] ?? 1))) : 0;
                    $istatistik_array['S3_deger'] = Helper::iceriyorMu($istatistik_array['S3'], '/') ? intval(trim(explode('/', $istatistik_array['S3'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['S3'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['S3'])[1] ?? 1))) : 0;
                    $istatistik_array['SR_deger'] = Helper::iceriyorMu($istatistik_array['SR'], '/') ? intval(trim(explode('/', $istatistik_array['SR'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['SR'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['SR'])[1] ?? 1))) : 0;
                    $istatistik_array['HR_deger'] = Helper::iceriyorMu($istatistik_array['HR'], '/') ? intval(trim(explode('/', $istatistik_array['HR'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['HR'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['HR'])[1] ?? 1))) : 0;
                    $istatistik_array['TR_deger'] = Helper::iceriyorMu($istatistik_array['TR'], '/') ? intval(trim(explode('/', $istatistik_array['TR'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['TR'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['TR'])[1] ?? 1))) : 0;
                    $istatistik_array['AS_deger'] = Helper::iceriyorMu($istatistik_array['AST'], '/') ? intval(trim(explode('/', $istatistik_array['AST'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['AST'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['AST'])[1] ?? 1))) : 0;
                    $istatistik_array['TÇ_deger'] = Helper::iceriyorMu($istatistik_array['TÇ'], '/') ? intval(trim(explode('/', $istatistik_array['TÇ'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['TÇ'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['TÇ'])[1] ?? 1))) : 0;
                    $istatistik_array['TK_deger'] = Helper::iceriyorMu($istatistik_array['TK'], '/') ? intval(trim(explode('/', $istatistik_array['TK'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['TK'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['TK'])[1] ?? 1))) : 0;
                    $istatistik_array['BL_deger'] = Helper::iceriyorMu($istatistik_array['BL'], '/') ? intval(trim(explode('/', $istatistik_array['BL'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['BL'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['BL'])[1] ?? 1))) : 0;
                    $istatistik_array['FA_deger'] = Helper::iceriyorMu($istatistik_array['FA'], '/') ? intval(trim(explode('/', $istatistik_array['FA'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['FA'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['FA'])[1] ?? 1))) : 0;
                    $istatistik_array['VP_deger'] = Helper::iceriyorMu($istatistik_array['VP'], '/') ? intval(trim(explode('/', $istatistik_array['VP'])[0] ?? 0)) / (intval(trim(explode('/', $istatistik_array['VP'])[1] ?? 1)) == 0 ? 1 : intval(trim(explode('/', $istatistik_array['VP'])[1] ?? 1))) : 0;
                    //>------------------------------<


                    $istatistik_array2 = $istatistik_array;
                    unset($istatistik_array2['adsoyad']);
                    unset($istatistik_array2['rakip_takim']);
                    unset($istatistik_array2['tarih']);
                    unset($istatistik_array2['sezon']);
                    unset($istatistik_array2['lig']);
                    unset($istatistik_array2['takimi']);

                    try {
                        Istatistik::updateOrInsert($istatistik_array);
                    } catch (\Exception $err) {
                        Istatistik::where(
                            [
                                'adsoyad' => trim($istatistik_array['adsoyad']),
                                'rakip_takim' => trim($istatistik_array['rakip_takim']),
                                'tarih' => trim($istatistik_array['tarih']),
                                'sezon' => trim($istatistik_array['sezon']),
                                'lig' => trim($istatistik_array['lig']),
                                'takimi' => trim($istatistik_array['takimi'])
                            ]
                        )->update($istatistik_array2);
                    }


                    if (!empty($istatistik_array['rakip_takim'])) {
                        try { Takim::updateOrInsert(['adi' => trim($istatistik_array['rakip_takim'])]); } catch (\Exception $err) { }
                    } //Takım ekle

                    if (!empty($istatistik_array['takimi'])) {
                        try { Takim::updateOrInsert(['adi' => trim($istatistik_array['takimi'])]); } catch (\Exception $err) { }
                    } //Takım ekle

                    if (!empty($istatistik_array['sezon'])) {
                        try { Sezon::updateOrInsert(['adi' => trim($istatistik_array['sezon'])]); } catch (\Exception $err) { }
                    } //Sezon ekle

                    if (!empty($istatistik_array['lig'])) {
                        try { Lig::updateOrInsert(['adi' => trim($istatistik_array['lig'])]); } catch (\Exception $err) { }
                    } //Lig ekle

                    if (!empty($istatistik_array['kapsam'])) {
                        try { Kapsam::updateOrInsert(['adi' => trim($istatistik_array['kapsam'])]); } catch (\Exception $err) { }
                    } //Kapsam ekle
                }
                //>------------------------------<
            }

        }
//        }
//        catch (\Exception $err) {
//            return;
//        }
    }


    public function excelContactsClear()
    {
        if (@Auth::user()->permissions != 0) {
            return redirect('admin');
        }

        Oyuncu::truncate();
        Istatistik::truncate();
        Lig::truncate();
        Kapsam::truncate();
        Menajer::truncate();
        Sezon::truncate();
        Takim::truncate();
        OyuncuDetay::truncate();

        foreach (Storage::files('imported', true) as $file) {
            Storage::delete($file);
        }
        return redirect('admin/contacts');
    }


    public function ContactPage()
    {
        return $this->ContactPageByLang("");
    }

    public function ContactPageByLang($lang = "")
    {

        if ($lang != "") {
            // Set Language
            App::setLocale($lang);
            \Session::put('locale', $lang);
        }
        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        $id = $WebmasterSettings->contact_page_id;
        $Topic = Topic::where('status', 1)->find($id);


        if (!empty($Topic) && ($Topic->expire_date == '' || ($Topic->expire_date != '' && $Topic->expire_date >= date("Y-m-d")))) {

            // update visits
            $Topic->visits = $Topic->visits + 1;
            $Topic->save();

            // get Webmaster section settings by ID
            $WebmasterSection = WebmasterSection::find($Topic->webmaster_id);

            if (!empty($WebmasterSection)) {

                // Get current Category Section details
                $CurrentCategory = Section::find($Topic->section_id);
                // Get a list of all Category ( for side bar )
                $Categories = Section::where('webmaster_id', '=', $WebmasterSection->id)->where('father_id', '=',
                    '0')->where('status', 1)->orderby('webmaster_id', 'asc')->orderby('row_no', 'asc')->get();

                // Get Most Viewed
                $TopicsMostViewed = Topic::where([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['webmaster_id', '=', $WebmasterSection->id], ['status', 1], ['expire_date', null]])->orderby('visits', 'desc')->limit(3)->get();

                // General for all pages
                $WebsiteSettings = Setting::find(1);

                $SideBanners = Banner::where('section_id', $WebmasterSettings->side_banners_section_id)->where('status',
                    1)->orderby('row_no', 'asc')->get();

                // Get Latest News
                $LatestNews = $this->latest_topics($WebmasterSettings->latest_news_section_id);


                // Page Title, Description, Keywords
                $seo_title_var = "seo_title_" . @Helper::currentLanguage()->code;
                $seo_description_var = "seo_description_" . @Helper::currentLanguage()->code;
                $seo_keywords_var = "seo_keywords_" . @Helper::currentLanguage()->code;
                $tpc_title_var = "title_" . @Helper::currentLanguage()->code;
                $site_desc_var = "site_desc_" . @Helper::currentLanguage()->code;
                $site_keywords_var = "site_keywords_" . @Helper::currentLanguage()->code;
                if ($Topic->$seo_title_var != "") {
                    $PageTitle = $Topic->$seo_title_var;
                } else {
                    $PageTitle = $Topic->$tpc_title_var;
                }
                if ($Topic->$seo_description_var != "") {
                    $PageDescription = $Topic->$seo_description_var;
                } else {
                    $PageDescription = $WebsiteSettings->$site_desc_var;
                }
                if ($Topic->$seo_keywords_var != "") {
                    $PageKeywords = $Topic->$seo_keywords_var;
                } else {
                    $PageKeywords = $WebsiteSettings->$site_keywords_var;
                }
                // .. end of .. Page Title, Description, Keywords

                return view("frontEnd.contact",
                    compact("WebsiteSettings",
                        "WebmasterSettings",
                        "LatestNews",
                        "Topic",
                        "SideBanners",
                        "WebmasterSection",
                        "Categories",
                        "CurrentCategory",
                        "PageTitle",
                        "PageDescription",
                        "PageKeywords",
                        "TopicsMostViewed"));

            } else {
                return redirect()->action('HomeController@HomePage');
            }
        } else {
            return redirect()->action('HomeController@HomePage');
        }

    }

    public function ContactPageSubmit(Request $request)
    {

        $this->validate($request, [
            'contact_name' => 'required',
            'contact_email' => 'required|email',
            'contact_subject' => 'required',
            'contact_message' => 'required'
        ]);

        if (env('NOCAPTCHA_STATUS', false)) {
            $this->validate($request, [
                'g-recaptcha-response' => 'required|captcha'
            ]);
        }

        $site_title_var = "site_title_" . @Helper::currentLanguage()->code;
        $site_email = @Helper::GeneralSiteSettings("site_webmails");

        $Webmail = new Webmail;
        $Webmail->cat_id = 0;
        $Webmail->group_id = null;
        $Webmail->title = $request->contact_subject;
        $Webmail->details = $request->contact_message;
        $Webmail->date = date("Y-m-d H:i:s");
        $Webmail->from_email = $request->contact_email;
        $Webmail->from_name = $request->contact_name;
        $Webmail->from_phone = $request->contact_phone;
        $Webmail->to_email = $site_email;
        $Webmail->to_name = @Helper::GeneralSiteSettings($site_title_var);
        $Webmail->status = 0;
        $Webmail->flag = 0;
        $Webmail->save();

        // SEND Notification Email
        if (@Helper::GeneralSiteSettings('notify_messages_status')) {
            try {
                $recipient = explode(",", str_replace(" ", "", $site_email));
                $message_details = __('frontend.name') . ": " . $request->contact_name . "<hr>" . __('frontend.phone') . ": " . $request->contact_phone . "<hr>" . __('frontend.email') . ": " . $request->contact_email . "<hr>" . __('frontend.message') . ":<br>" . nl2br($request->contact_message);

                Mail::to($recipient)->send(new NotificationEmail(
                    [
                        "title" => $request->contact_subject,
                        "details" => $message_details,
                        "from_email" => $request->contact_email,
                        "from_name" => $request->contact_name
                    ]
                ));
            } catch (\Exception $e) {

            }
        }

        return "OK";
    }

    public function subscribeSubmit(Request $request)
    {


        $this->validate($request, [
            'subscribe_name' => 'required',
            'subscribe_email' => 'required|email'
        ]);

        // General Webmaster Settings
        $WebmasterSettings = WebmasterSetting::find(1);

        $Contacts = Contact::where('email', $request->subscribe_email)->get();
        if (count($Contacts) > 0) {
            return __('frontend.subscribeToOurNewsletterError');
        } else {
            $subscribe_names = explode(' ', $request->subscribe_name, 2);

            $Contact = new Contact;
            $Contact->group_id = $WebmasterSettings->newsletter_contacts_group;
            $Contact->first_name = @$subscribe_names[0];
            $Contact->last_name = @$subscribe_names[1];
            $Contact->email = $request->subscribe_email;
            $Contact->status = 1;
            $Contact->save();

            return "OK";
        }
    }

    public function commentSubmit(Request $request)
    {

        $this->validate($request, [
            'comment_name' => 'required',
            'comment_message' => 'required',
            'topic_id' => 'required',
            'comment_email' => 'required|email'
        ]);

        if (env('NOCAPTCHA_STATUS', false)) {
            $this->validate($request, [
                'g-recaptcha-response' => 'required|captcha'
            ]);
        }
        // Topic details
        $Topic = Topic::where('status', 1)->find($request->topic_id);
        if (!empty($Topic)) {
            $next_nor_no = Comment::where('topic_id', '=', $request->topic_id)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            $Comment = new Comment;
            $Comment->row_no = $next_nor_no;
            $Comment->name = $request->comment_name;
            $Comment->email = $request->comment_email;
            $Comment->comment = $request->comment_message;
            $Comment->topic_id = $request->topic_id;;
            $Comment->date = date("Y-m-d H:i:s");
            $Comment->status = (@Helper::GeneralWebmasterSettings('new_comments_status')) ? 1 : 0;
            $Comment->save();


            $site_email = @Helper::GeneralSiteSettings("site_webmails");

            $tpc_title_var = "title_" . @Helper::currentLanguage()->code;
            $tpc_title = $Topic->$tpc_title_var;

            // SEND Notification Email
            if (@Helper::GeneralSiteSettings('notify_comments_status')) {
                try {
                    $recipient = explode(",", str_replace(" ", "", $site_email));
                    $message_details = __('frontend.name') . ": " . $request->comment_name . "<hr>" . __('frontend.email') . ": " . $request->comment_email . "<hr>" . __('frontend.comment') . ":<br>" . nl2br($request->comment_message);

                    Mail::to($recipient)->send(new NotificationEmail(
                        [
                            "title" => "Comment: " . $tpc_title,
                            "details" => $message_details,
                            "from_email" => $request->comment_email,
                            "from_name" => $request->comment_name
                        ]
                    ));
                } catch (\Exception $e) {

                }
            }
        }

        return "OK";
    }

    public function orderSubmit(Request $request)
    {

        $this->validate($request, [
            'order_name' => 'required',
            'order_phone' => 'required',
            'topic_id' => 'required',
            'order_email' => 'required|email'
        ]);

        if (env('NOCAPTCHA_STATUS', false)) {
            $this->validate($request, [
                'g-recaptcha-response' => 'required|captcha'
            ]);
        }

        $site_title_var = "site_title_" . @Helper::currentLanguage()->code;
        $site_email = @Helper::GeneralSiteSettings("site_webmails");

        $Topic = Topic::where('status', 1)->find($request->topic_id);
        if (!empty($Topic)) {
            $tpc_title_var = "title_" . @Helper::currentLanguage()->code;
            $tpc_title = $Topic->$tpc_title_var;

            $Webmail = new Webmail;
            $Webmail->cat_id = 0;
            $Webmail->group_id = 2;
            $Webmail->contact_id = null;
            $Webmail->father_id = null;
            $Webmail->title = "ORDER: " . $Topic->$tpc_title_var;
            $Webmail->details = $request->order_message;
            $Webmail->date = date("Y-m-d H:i:s");
            $Webmail->from_email = $request->order_email;
            $Webmail->from_name = $request->order_name;
            $Webmail->from_phone = $request->order_phone;
            $Webmail->to_email = $site_email;
            $Webmail->to_name = @Helper::GeneralSiteSettings($site_title_var);
            $Webmail->status = 0;
            $Webmail->flag = 0;
            $Webmail->save();


            // SEND Notification Email
            if (@Helper::GeneralSiteSettings('notify_orders_status')) {
                try {
                    $recipient = explode(",", str_replace(" ", "", $site_email));
                    $message_details = __('frontend.name') . ": " . $request->order_name . "<hr>" . __('frontend.phone') . ": " . $request->order_phone . "<hr>" . __('frontend.email') . ": " . $request->order_email . "<hr>" . __('frontend.notes') . ":<br>" . nl2br($request->order_message);

                    Mail::to($recipient)->send(new NotificationEmail(
                        [
                            "title" => "Order: " . $tpc_title,
                            "details" => $message_details,
                            "from_email" => $request->order_email,
                            "from_name" => $request->order_name
                        ]
                    ));
                } catch (\Exception $e) {

                }
            }

        }

        return "OK";
    }

    public function latest_topics($section_id, $limit = 3)
    {
        return Topic::where([['status', 1], ['webmaster_id', $section_id], ['expire_date', '>=', date("Y-m-d")], ['expire_date', '<>', null]])->orwhere([['status', 1], ['webmaster_id', $section_id], ['expire_date', null]])->orderby('row_no', 'desc')->limit($limit)->get();
    }

    public function close_check()
    {
        // Check the website Status
        $WebsiteSettings = Setting::find(1);
        $site_status = $WebsiteSettings->site_status;
        $site_msg = $WebsiteSettings->close_msg;
        if (!@Auth::check()) {
            if ($site_status == 0) {
                // close the website
                $site_title = $WebsiteSettings->{'site_title_' . Helper::currentLanguage()->code};
                $site_desc = $WebsiteSettings->{'site_desc_' . Helper::currentLanguage()->code};
                $site_keywords = $WebsiteSettings->{'site_keywords_' . Helper::currentLanguage()->code};
                echo "<!DOCTYPE html>
                    <html lang=\"en\">
                    <head>
                    <meta charset=\"utf-8\">
                    <title>$site_title</title>
                    <meta name=\"description\" content=\"$site_desc\"/>
                    <meta name=\"keywords\" content=\"$site_keywords\"/>
                    <body>
                    <br>
                    <div style='text-align: center;'>
                    <p>$site_msg</p>
                    </div>
                    </body>
                    </html>
                ";
                exit();
            }
        }
    }


    public function oyuncu_istatistik_ajax(Request $request)
    {

        $table = 'istatistikler';
        $primaryKey = 'id';

        $columns = [
            ['db' => 'adsoyad', 'dt' => 0],
            ['db' => 'rakip_takim', 'dt' => 1],
            [
                'db' => 'tarih',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    return date('d.m.Y', strtotime($d));
                }
            ],
            ['db' => 'sure', 'dt' => 3],
            ['db' => 'sayi', 'dt' => 4],
            ['db' => 'AG', 'dt' => 5],
            ['db' => 'SA', 'dt' => 6],
            ['db' => 'S2', 'dt' => 7],
            ['db' => 'S3', 'dt' => 8],
            ['db' => 'SR', 'dt' => 9],
            ['db' => 'HR', 'dt' => 10],
            ['db' => 'TR', 'dt' => 11],
            ['db' => 'AST', 'dt' => 12],
            ['db' => 'TÇ', 'dt' => 13],
            ['db' => 'TK', 'dt' => 14],
            ['db' => 'BL', 'dt' => 15],
            ['db' => 'FA', 'dt' => 16],
            ['db' => 'VP', 'dt' => 17],
            ['db' => 'sezon', 'dt' => 18],
            ['db' => 'lig', 'dt' => 19],
            ['db' => 'kapsam', 'dt' => 20],
            ['db' => 'takimi', 'dt' => 21],
        ];

        $dt_obj = new SSP($table, $columns);

        if (empty($request['select_value1']) && empty($request['select_value2']) && empty($request['select_value3'])
            && empty($request['select_value4']) && empty($request['select_value5']) && empty($request['select_value6'])
            && empty($request['select_value7']) && empty($request['select_value8']) && empty($request['select_value9'])
            && empty($request['select_value10'])) {

            $dt_obj->where('id', '>', '0');

        } else {

            if (!empty($request['select_value1'])) { $dt_obj->where('adsoyad', 'like', '%'.$request['select_value1'].'%'); }
            if (!empty($request['select_value2'])) { $dt_obj->where('takimi', $request['select_value2']); }
            if (!empty($request['select_value3'])) { $dt_obj->where('rakip_takim', $request['select_value3']); }
            if (!empty($request['select_value4'])) {
                $request['select_value4'] = date("Y-m-d H:i:s", strtotime($request['select_value4'].' 00:00:00'));
                $dt_obj->where('tarih', '>=', $request['select_value4']);
            }
            if (!empty($request['select_value5'])) {
                $request['select_value5'] = date("Y-m-d H:i:s", strtotime($request['select_value5'].' 23:59:59'));
                $dt_obj->where('tarih', '<=', $request['select_value5']);
            }
            if (!empty($request['select_value6'])) {
                $dt_obj->where('sure', '>=', '00:' . $request['select_value6']);
            }
            if (!empty($request['select_value7'])) {
                $dt_obj->where('sure', '<=', '00:' . $request['select_value7']);
            }
            if (!empty($request['select_value8'])) { $dt_obj->where('sezon', $request['select_value8']); }
            if (!empty($request['select_value9'])) { $dt_obj->where('lig', $request['select_value9']); }
            if (!empty($request['select_value10'])) { $dt_obj->where('kapsam', $request['select_value10']); }
        }

        $dt_arr = $dt_obj->getDtArr();

        return response()->json($dt_arr);

    }


    public function oyuncular_ajax(Request $request)
    {

        $oyuncular = DB::select(
            DB::raw("
                SELECT DISTINCT
                    adsoyad,
                    (Select id From oyuncular Where adsoyad=oyuncu_detaylar.adsoyad Limit 1) AS id,
                    (Select photo From oyuncular Where adsoyad=oyuncu_detaylar.adsoyad Limit 1) AS photo,
                    (Select ana_pozisyon From oyuncular Where adsoyad=oyuncu_detaylar.adsoyad Limit 1) AS ana_pozisyon,
                    (Select yan_pozisyon From oyuncular Where adsoyad=oyuncu_detaylar.adsoyad Limit 1) AS yan_pozisyon
                FROM
                    oyuncu_detaylar
                ORDER BY
                    adsoyad ASC
            ")
        );

        return (count($oyuncular) ?? 0) == 0 ? 'false' : json_encode($oyuncular);
    }


    public function oyuncu_listeler_ajax(Request $request)
    {
        if($request->adsoyad != '') {
            $sezon = DB::select(
                DB::raw("
                    SELECT DISTINCT
                        sezon
                    FROM
                        oyuncu_detaylar
                    WHERE
                        adsoyad = '" . urldecode($request->adsoyad) . "'
                    ORDER BY 1 ASC
                ")
            );

            $takim = Istatistik::select("takimi")->distinct()->where('adsoyad', urldecode($request->adsoyad))->orderBy("takimi", "asc")->get();
            $oyuncu['sezonlar'] = $sezon;
            $oyuncu['takimlar'] = $takim;
        }

        return count($oyuncu) == 0 ? 'false' : json_encode($oyuncu);
    }


    public function sezonlar_ajax(Request $request)
    {
        $sezonlar = Sezon::get()->sortBy('id');

        return ($sezonlar->count() ?? 0) == 0 ? 'false' : json_encode($sezonlar);
    }

    public function takimlar_ajax(Request $request)
    {
        $takimlar = Takim::get()->sortBy('adi');

        return ($takimlar->count() ?? 0) == 0 ? 'false' : json_encode($takimlar);
    }


    public function pozisyonlar_ajax(Request $request)
    {
        $pozisyonlar = Pozisyon::get()->sortBy('adi');

        return ($pozisyonlar->count() ?? 0) == 0 ? 'false' : json_encode($pozisyonlar);
    }

    public function kapsamlar_ajax(Request $request)
    {
        $kapsamlar = Kapsam::get()->sortBy('adi');

        return ($kapsamlar->count() ?? 0) == 0 ? 'false' : json_encode($kapsamlar);
    }

    public function ligler_ajax(Request $request)
    {
        $ligler = Lig::get()->sortBy('adi');

        return ($ligler->count() ?? 0) == 0 ? 'false' : json_encode($ligler);
    }


    public function oyuncular_istatistik_ajax(Request $request)
    {
        $adsoyad = !empty($request->adsoyad) ? urldecode($request->adsoyad) : '';
        $ista_type = empty($request->ista_type) ? 0 : $request->ista_type;

        if ($adsoyad != '' && $ista_type == 1) {
            $istatistikler = Istatistik::where('adsoyad', $adsoyad)->get()->sortBy('adi');

            return $istatistikler->count() == 0 ? 'false' : json_encode($istatistikler);
        }
        else if($adsoyad != '' && $request->takim!='' && $request->sezon!='' && $request->donem!='') {

            $query = "
                SELECT
                    sezon,
                    sure AS ortalamasure,
                    (Select SEC_TO_TIME(SUM(TIME_TO_SEC(sure))) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamsure,

                    sayi AS ortalamasayi,
                    (Select SUM(sayi) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamsayi,

                    SA AS ortalamaSA,
                    S2 AS ortalamaS2,
                    S3 AS ortalamaS3,

                     FORMAT(SR, 2) AS ortalamaSR,
                    (Select SUM(SR) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamSR,

                     FORMAT(HR, 2) AS ortalamaHR,
                    (Select SUM(HR) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamHR,

                     FORMAT(TR, 2) AS ortalamaTR,
                    (Select SUM(TR) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamTR,

                     FORMAT(AST, 2) AS ortalamaAS,
                    (Select SUM(AST) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamAS,

                     FORMAT(TÇ, 2) AS ortalamaTÇ,
                    (Select SUM(TÇ) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamTÇ,

                     FORMAT(TK, 2) AS ortalamaTK,
                    (Select SUM(TK) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamTK,

                     FORMAT(BL, 2) AS ortalamaBL,
                    (Select SUM(BL) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamBL,

                     FORMAT(FA, 2) AS ortalamaFA,
                    (Select SUM(FA) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamFA,

                     FORMAT(IF(VP<0,0,VP), 2) AS ortalamaVP,
                    (Select SUM(VP) From istatistikler Where adsoyad='$adsoyad' And sezon=oyuncu_detaylar.sezon) AS toplamVP
                FROM oyuncu_detaylar
                WHERE adsoyad='$adsoyad'";


            if($request->value1 != '' && $request->value1 > -1) {
                $query .= " AND takimi='".urldecode($request->value1)."'";
            }

            if($request->value2 != '' && $request->value2 > -1) {
                $query .= " AND sezon='".urldecode($request->value2)."'";
            }
            elseif($request->value2 == -1) {
                $query .= " AND sezon Like '%-".date('Y')."'";
            }

            $query .= "
                LIMIT 1
            ";

            $istatistikler = DB::select(DB::raw($query));

            if(count($istatistikler) == 0) {
                $query = "
                    SELECT
                        '' AS sezon,
                        '00:00:00:' AS ortalamasure,
                        '00:00:00:' AS toplamsure,
                        '0' AS ortalamasayi,
                        '1' AS toplamsayi,
                        '0%' AS ortalamaSA,
                        '0%' AS ortalamaS2,
                        '0%' AS ortalamaS3,
                        '0' AS ortalamaSR,
                        '1' AS toplamSR,
                        '0' AS ortalamaHR,
                        '1' AS toplamHR,
                        '0' AS ortalamaTR,
                        '1' AS toplamTR,
                        '0' AS ortalamaAS,
                        '1' AS toplamAS,
                        '0' AS ortalamaTÇ,
                        '1' AS toplamTÇ,
                        '0' AS ortalamaTK,
                        '1' AS toplamTK,
                        '0' AS ortalamaBL,
                        '1' AS toplamBL,
                        '0' AS ortalamaFA,
                        '1' AS toplamFA,
                        '0' AS ortalamaVP,
                        '1' AS toplamVP
                    FROM oyuncu_detaylar
                    LIMIT 1
                ";
                return json_encode(DB::select(DB::raw($query)));

            }

            return  json_encode($istatistikler);

        }
        else if ($adsoyad != '' && $ista_type == 0) {

            $query = "
                SELECT
                    '' AS sezon,
                    '00:00:00:' AS ortalamasure,
                    '00:00:00:' AS toplamsure,
                    '0' AS ortalamasayi,
                    '1' AS toplamsayi,
                    '0%' AS ortalamaSA,
                    '0%' AS ortalamaS2,
                    '0%' AS ortalamaS3,
                    '0' AS ortalamaSR,
                    '1' AS toplamSR,
                    '0' AS ortalamaHR,
                    '1' AS toplamHR,
                    '0' AS ortalamaTR,
                    '1' AS toplamTR,
                    '0' AS ortalamaAS,
                    '1' AS toplamAS,
                    '0' AS ortalamaTÇ,
                    '1' AS toplamTÇ,
                    '0' AS ortalamaTK,
                    '1' AS toplamTK,
                    '0' AS ortalamaBL,
                    '1' AS toplamBL,
                    '0' AS ortalamaFA,
                    '1' AS toplamFA,
                    '0' AS ortalamaVP,
                    '1' AS toplamVP
                FROM oyuncu_detaylar
                LIMIT 1
            ";

            $istatistikler = DB::select(DB::raw($query));

            return count($istatistikler) == 0 ? 'false' : json_encode($istatistikler);

        }
    }


    public function donem_istatistik_ajax(Request $request)
    {

        $table = 'istatistikler';
        $primaryKey = 'id';

        $columns = [
            ['db' => 'adsoyad', 'dt' => 0],
            ['db' => 'rakip_takim', 'dt' => 1],
            [
                'db' => 'tarih',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    return date('d.m.Y', strtotime($d));
                }
            ],
            ['db' => 'sure', 'dt' => 3],
            ['db' => 'sayi', 'dt' => 4],
            ['db' => 'AG', 'dt' => 5],
            ['db' => 'SA', 'dt' => 6],
            ['db' => 'S2', 'dt' => 7],
            ['db' => 'S3', 'dt' => 8],
            ['db' => 'SR', 'dt' => 9],
            ['db' => 'HR', 'dt' => 10],
            ['db' => 'TR', 'dt' => 11],
            ['db' => 'AST', 'dt' => 12],
            ['db' => 'TÇ', 'dt' => 13],
            ['db' => 'TK', 'dt' => 14],
            ['db' => 'BL', 'dt' => 15],
            ['db' => 'FA', 'dt' => 16],
            ['db' => 'VP', 'dt' => 17],
            ['db' => 'sezon', 'dt' => 18],
            ['db' => 'lig', 'dt' => 19],
            ['db' => 'kapsam', 'dt' => 20],
            ['db' => 'takimi', 'dt' => 21],
        ];

        $dt_obj = new SSP($table, $columns);
        if (empty($request['select_value'])) {
            $dt_obj->where('id', '0');
        } else {
            $dt_obj->where('sezon', $request['select_value']);
        }
        $dt_arr = $dt_obj->getDtArr();

        return response()->json($dt_arr);

    }


    public function donem_istatistik_ajax_donemler()
    {

        $sezonlar = Sezon::get()->sortBy('adi')->pluck('adi')->toArray();

        return response()->json(is_array($sezonlar) && count($sezonlar) > 0 ? $sezonlar : 'false');

    }


    public function pozisyon_istatistik_ajax(Request $request)
    {

        $table = 'istatistikler';
        $primaryKey = 'id';

        $columns = [
            ['db' => 'adsoyad', 'dt' => 0],
            ['db' => 'pozisyon1.title', 'dt' => 1],
            ['db' => 'pozisyon2.title', 'dt' => 2],
            ['db' => 'rakip_takim', 'dt' => 3],
            [
                'db' => 'tarih',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return date('d.m.Y', strtotime($d));
                }
            ],
            ['db' => 'sure', 'dt' => 5],
            ['db' => 'sayi', 'dt' => 6],
            ['db' => 'AG', 'dt' => 7],
            ['db' => 'SA', 'dt' => 8],
            ['db' => 'S2', 'dt' => 9],
            ['db' => 'S3', 'dt' => 10],
            ['db' => 'SR', 'dt' => 11],
            ['db' => 'HR', 'dt' => 12],
            ['db' => 'TR', 'dt' => 13],
            ['db' => 'AST', 'dt' => 14],
            ['db' => 'TÇ', 'dt' => 15],
            ['db' => 'TK', 'dt' => 16],
            ['db' => 'BL', 'dt' => 17],
            ['db' => 'FA', 'dt' => 18],
            ['db' => 'VP', 'dt' => 19],
            ['db' => 'sezon', 'dt' => 20],
            ['db' => 'lig', 'dt' => 21],
            ['db' => 'kapsam', 'dt' => 22],
            ['db' => 'takimi', 'dt' => 23],
        ];

        $dt_obj = new SSP($table, $columns);
        $dt_obj->leftJoin('oyuncular', 'istatistikler.adsoyad', 'oyuncular.adsoyad');
        $dt_obj->leftJoin('pozisyonlar AS pozisyon1', 'oyuncular.ana_pozisyon', 'pozisyon1.adi');
        $dt_obj->leftJoin('pozisyonlar AS pozisyon2', 'oyuncular.yan_pozisyon', 'pozisyon2.adi');

        if (!empty($request['select_value1'])) {
            $dt_obj->where('pozisyon1.title', $request['select_value1']);
        }

        if (!empty($request['select_value2'])) {
            $dt_obj->where('pozisyon2.title', $request['select_value2']);
        }

        if (empty($request['select_value1']) && empty($request['select_value2'])) {
            $dt_obj->where('istatistikler.id', '0');
        }

        $dt_arr = $dt_obj->getDtArr();

        return response()->json($dt_arr);

    }


    public function pozisyon_istatistik_ajax_pozisyonlar()
    {

        $pozisyonlar = Pozisyon::selectRaw("*, CONCAT(adi, ' (', title, ')') AS pozisyon")->get()->sortBy('adi')->pluck('pozisyon', 'title')->toArray();

        return response()->json(is_array($pozisyonlar) && count($pozisyonlar) > 0 ? $pozisyonlar : 'false');

    }



    public function mac_analizleri_filtreler_ajax()
    {

        $filtreler = [];
        $filtreler['mac_nolar'] = MacSonuc::selectRaw("distinct Mac_No AS adi, Mac_No AS title")->whereRaw("Mac_No<>''")->groupBy('Mac_No')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['haftalar'] = MacSonuc::selectRaw("distinct Hafta AS adi, Hafta AS title")->whereRaw("Hafta<>''")->groupBy('Hafta')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['ligler'] = MacSonuc::selectRaw("distinct Lig AS adi, Lig AS title")->whereRaw("Lig<>''")->groupBy('Lig')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['sehirler'] = MacSonuc::selectRaw("distinct Sehir AS adi, Sehir AS title")->whereRaw("Sehir<>''")->groupBy('Sehir')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['a_takimlar'] = MacSonuc::selectRaw("distinct A_Takim AS adi, A_Takim AS title")->whereRaw("A_Takim<>''")->groupBy('A_Takim')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['b_takimlar'] = MacSonuc::selectRaw("distinct B_Takim AS adi, B_Takim AS title")->whereRaw("B_Takim<>''")->groupBy('B_Takim')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['gruplar'] = MacSonuc::selectRaw("distinct Grubu AS adi, Grubu AS title")->whereRaw("Grubu<>''")->groupBy('Grubu')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['salonlar'] = MacSonuc::selectRaw("distinct Salon AS adi, Salon AS title")->whereRaw("Salon<>''")->groupBy('Salon')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['hakemler'] = MacSonuc::selectRaw("distinct Bas_Hakem AS adi, Bas_Hakem AS title")->whereRaw("Bas_Hakem<>''")->groupBy('Bas_Hakem')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['yrd_hakemler'] = MacSonuc::selectRaw("distinct 1_Yrd_Hakem AS adi, 1_Yrd_Hakem AS title")->whereRaw("1_Yrd_Hakem<>''")->groupBy('1_Yrd_Hakem')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['tvler'] = MacSonuc::selectRaw("distinct TV AS adi, TV AS title")->whereRaw("TV<>''")->groupBy('TV')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();
        $filtreler['masa_gorevliler'] = MacSonuc::selectRaw("distinct 1_Masa_Gorevlisi AS adi, 1_Masa_Gorevlisi AS title")->whereRaw("1_Masa_Gorevlisi<>''")->groupBy('1_Masa_Gorevlisi')->get()->sortBy('adi')->pluck('adi', 'title')->toArray();

        return response()->json(is_array($filtreler) && count($filtreler) > 0 ? $filtreler : 'false');

    }


    public function tbl_mac_sonuclari_ajax(Request $request)
    {

        $table = 'mac_sonuclari';
        $primaryKey = 'id';

        $columns = [
            [
                'db' => 'Tarih',
                'dt' => 0,
                'formatter' => function ($d, $row) {
                    if($d == '0000-00-00') {
                        return 'İptal edildi';
                    }
                    else {
                        return date('d.m.Y', strtotime($d));
                    }
                }
            ],
            ['db' => 'Hafta', 'dt' => 1],
            ['db' => 'Lig', 'dt' => 2],
            ['db' => 'A_Takim', 'dt' => 3],
            ['db' => 'Sonuc', 'dt' => 4],
            ['db' => 'B_Takim', 'dt' => 5],
        ];


        $dt_obj = new SSP($table, $columns);
        $dt_obj->where('lig', 'TBL');
        $dt_arr = $dt_obj->getDtArr();

        return response()->json($dt_arr);

    }


    public function bsl_mac_sonuclari_ajax(Request $request)
    {

        $table = 'mac_sonuclari';
        $primaryKey = 'id';

        $columns = [
            [
                'db' => 'Tarih',
                'dt' => 0,
                'formatter' => function ($d, $row) {
                    if($d == '0000-00-00') {
                        return 'İptal edildi';
                    }
                    else {
                        return date('d.m.Y', strtotime($d));
                    }
                }
            ],
            ['db' => 'Hafta', 'dt' => 1],
            ['db' => 'Lig', 'dt' => 2],
            ['db' => 'A_Takim', 'dt' => 3],
            ['db' => 'Sonuc', 'dt' => 4],
            ['db' => 'B_Takim', 'dt' => 5],
        ];


        $dt_obj = new SSP($table, $columns);
        $dt_obj->where('lig', 'BSL');
        $dt_arr = $dt_obj->getDtArr();

        return response()->json($dt_arr);

    }


    public function mac_analizleri_ajax(Request $request)
    {

        $table = 'mac_sonuclari';
        $primaryKey = 'id';

        $columns = [
            [
                'db' => 'Tarih',
                'dt' => 0,
                'formatter' => function ($d, $row) {
                    if($d == '0000-00-00') {
                        return 'İptal edildi';
                    }
                    else {
                        return date('d.m.Y', strtotime($d));
                    }
                }
            ],
            ['db' => 'Saat', 'dt' => 1],
            ['db' => 'Mac_No', 'dt' => 2],
            ['db' => 'Hafta', 'dt' => 3],
            ['db' => 'Lig', 'dt' => 4],
            ['db' => 'A_Takim', 'dt' => 5],
            ['db' => 'Sonuc', 'dt' => 6],
            ['db' => 'B_Takim', 'dt' => 7],
			['db' => 'Grubu', 'dt' => 8],
            ['db' => 'Sehir', 'dt' => 9],
            ['db' => 'Salon', 'dt' => 10],
            ['db' => 'TV', 'dt' => 11],
            ['db' => 'Bas_Hakem', 'dt' => 12],
            ['db' => '1_Yrd_Hakem', 'dt' => 13],
            ['db' => '2_Yrd_Hakem', 'dt' => 14],
            ['db' => '1_Masa_Gorevlisi', 'dt' => 15],
            ['db' => '2_Masa_Gorevlisi', 'dt' => 16],
            ['db' => '3_Masa_Gorevlisi', 'dt' => 17],
            ['db' => '4_Masa_Gorevlisi', 'dt' => 18]
        ];


        $dt_obj = new SSP($table, $columns);

        if (empty($request['select_value1']) && empty($request['select_value2']) && empty($request['select_value3'])
            && empty($request['select_value4']) && empty($request['select_value5']) && empty($request['select_value6'])
            && empty($request['select_value7']) && empty($request['select_value8']) && empty($request['select_value9'])
            && empty($request['select_value10']) && empty($request['select_value11']) && empty($request['select_value12'])) {

            $dt_obj->where('Lig', 'TBL')->orWhere('Lig', 'BSL');

        } else {

            if (!empty($request['select_value1'])) { $dt_obj->where('Mac_No', $request['select_value1']); }
            if (!empty($request['select_value2'])) { $dt_obj->where('Hafta', $request['select_value2']); }
            if (!empty($request['select_value3'])) { $dt_obj->where('Lig', $request['select_value3']); }
            if (!empty($request['select_value4'])) { $dt_obj->where('Sehir', $request['select_value4']); }
            if (!empty($request['select_value5'])) { $dt_obj->where('A_Takim', $request['select_value5']); }
            if (!empty($request['select_value6'])) { $dt_obj->where('B_Takim', $request['select_value6']); }
            if (!empty($request['select_value7'])) { $dt_obj->where('Grubu', $request['select_value7']); }
            if (!empty($request['select_value8'])) { $dt_obj->where('Salon', $request['select_value8']); }
            if (!empty($request['select_value9'])) { $dt_obj->where('Bas_Hakem', $request['select_value9']); }
            if (!empty($request['select_value10'])) { $dt_obj->where('1_Yrd_Hakem', $request['select_value10']); }
            if (!empty($request['select_value11'])) { $dt_obj->where('TV', $request['select_value11']); }
            if (!empty($request['select_value12'])) { $dt_obj->where('1_Masa_Gorevlisi', $request['select_value12']); }
        }

        $dt_arr = $dt_obj->getDtArr();
        return response()->json($dt_arr);

    }


    public function oyuncu_profil_tablosu_ajax(Request $request)
    {

        $table = 'oyuncular';
        $primaryKey = 'id';

        $columns = [
            ['db' => 'id', 'dt' => 0],
            ['db' => 'adsoyad', 'dt' => 1],
            ['db' => 'ana_pozisyon', 'dt' => 2],
            ['db' => 'yan_pozisyon', 'dt' => 3],
            ['db' => 'boy', 'dt' => 4],
            ['db' => 'dogum_tarihi', 'formatter' => function ($d, $row) {
                return date('d.m.Y', strtotime($d));
            }, 'dt' => 5],
            ['db' => 'menajer', 'dt' => 6],
            ['db' => 'takim_16_17_1', 'formatter' => function ($d, $row) {
                return $row->takim_16_17_1 . ' - ' . $row->takim_16_17_1;
            }, 'dt' => 7],
            ['db' => 'takim_17_18_1', 'formatter' => function ($d, $row) {
                return $row->takim_17_18_1 . ' - ' . $row->takim_17_18_1;
            }, 'dt' => 8],
            ['db' => 'takim_18_19_1', 'formatter' => function ($d, $row) {
                return $row->takim_18_19_1 . ' - ' . $row->takim_18_19_1;
            }, 'dt' => 9],
            ['db' => 'takim_19_20_1', 'formatter' => function ($d, $row) {
                return $row->takim_19_20_1 . ' - ' . $row->takim_19_20_1;
            }, 'dt' => 10],
            ['db' => 'takim_20_21_1', 'formatter' => function ($d, $row) {
                return $row->takim_20_21_1 . ' - ' . $row->takim_20_21_1;
            }, 'dt' => 11],
            ['db' => 'takim_21_22_1', 'formatter' => function ($d, $row) {
                return $row->takim_21_22_1 . ' - ' . $row->takim_21_22_1;
            }, 'dt' => 12],
            ['db' => 'takim_22_23_1', 'formatter' => function ($d, $row) {
                return $row->takim_22_23_1 . ' - ' . $row->takim_22_23_1;
            }, 'dt' => 13],
            ['db' => 'takim_23_24_1', 'formatter' => function ($d, $row) {
                return $row->takim_23_24_1 . ' - ' . $row->takim_23_24_1;
            }, 'dt' => 14],
            ['db' => 'takim_24_25_1', 'formatter' => function ($d, $row) {
                return $row->takim_24_25_1 . ' - ' . $row->takim_24_25_1;
            }, 'dt' => 15],
            ['db' => 'takim_25_26_1', 'formatter' => function ($d, $row) {
                return $row->takim_25_26_1 . ' - ' . $row->takim_25_26_1;
            }, 'dt' => 16],
            ['db' => 'takim_26_27_1', 'formatter' => function ($d, $row) {
                return $row->takim_26_27_1 . ' - ' . $row->takim_26_27_1;
            }, 'dt' => 17],
            ['db' => 'takim_27_28_1', 'formatter' => function ($d, $row) {
                return $row->takim_27_28_1 . ' - ' . $row->takim_27_28_1;
            }, 'dt' => 18],
            ['db' => 'takim_28_29_1', 'formatter' => function ($d, $row) {
                return $row->takim_28_29_1 . ' - ' . $row->takim_28_29_1;
            }, 'dt' => 19],
            ['db' => 'takim_29_30_1', 'formatter' => function ($d, $row) {
                return $row->takim_29_30_1 . ' - ' . $row->takim_29_30_1;
            }, 'dt' => 20],
            ['db' => 'takim_30_31_1', 'formatter' => function ($d, $row) {
                return $row->takim_30_31_1 . ' - ' . $row->takim_30_31_1;
            }, 'dt' => 21],
            ['db' => 'takim_31_32_1', 'formatter' => function ($d, $row) {
                return $row->takim_31_32_1 . ' - ' . $row->takim_31_32_1;
            }, 'dt' => 22],
            ['db' => 'takim_32_33_1', 'formatter' => function ($d, $row) {
                return $row->takim_32_33_1 . ' - ' . $row->takim_32_33_1;
            }, 'dt' => 23],
            ['db' => 'takim_33_34_1', 'formatter' => function ($d, $row) {
                return $row->takim_33_34_1 . ' - ' . $row->takim_33_34_1;
            }, 'dt' => 24],
            ['db' => 'takim_34_35_1', 'formatter' => function ($d, $row) {
                return $row->takim_34_35_1 . ' - ' . $row->takim_34_35_1;
            }, 'dt' => 25],
        ];

        $dt_obj = new SSP($table, $columns);
        $dt_obj->where('status', '1');
        $dt_arr = $dt_obj->getDtArr();

        return response()->json($dt_arr);

    }


}
