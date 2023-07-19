<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Istatistik;
use App\Models\Oyuncu;
use App\Models\Takim;
use App\Models\WebmasterSection;
use Form;
use Helper;
use Illuminate\Http\Request;

class IstatistikController extends Controller
{
    private $uploadPath = "uploads/topics/";

    // Define Default Variables

    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function index($group_id = null)
    {
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        $WebmasterSection = WebmasterSection::find(8);

        $takimlar = Takim::orderby('adi', 'asc')->distinct()->get();

        //List of all Contacts
        $Istatistiks = Istatistik::orderby('adsoyad', 'asc')->paginate(env('BACKEND_PAGINATION'));

        //Count of All Contacts
        $AllIstatistiksCount = Istatistik::count();

        $search_word = "";

        if (!empty($group_id) && $group_id > 0) {
            \Session::put('ContactToEdit', Istatistik::find($group_id));
        } else {
            \Session::forget('ContactToEdit');
        }
        return view("dashboard.topics.edit", compact(
            "Istatistiks",
            "group_id",
            "GeneralWebmasterSections",
            "WebmasterSection",
            "takimlar",
            "AllIstatistikCount",
            "search_word"));
    }

    public function list(Request $request)
    {

        $title_var = "title_" . @Helper::currentLanguage()->code;
        $title_var2 = "title_" . env('DEFAULT_LANGUAGE');

        $limit = $request->input('length');
        $start = $request->input('start');
        $dir = $request->input('order.0.dir');

        \Cookie::queue("user_documents_page_order", 3, 31104000);

        //search inputs
        $folder_id = $request->input('folder_id');
        \Session()->put('current_admin_temp_folder_id', $folder_id);

        $webmasterId = $request->input('webmaster_id');
        $q = $request->input('find_q');

        $columns = array(
            0 => 'check',
            1 => 'id',
            2 => 'adsoyad',
            3 => 'rakip_takim',
            4 => 'tarih',
            5 => 'sure',
            6 => 'sayi',
            7 => 'AG',
            8 => 'SA',
            9 => 'S2',
            10 => 'S3',
            11 => 'SR',
            12 => 'HR',
            13 => 'TR',
            14 => 'AST',
            15 => 'TÇ',
            16 => 'TK',
            17 => 'BL',
            18 => 'FA',
            19 => 'VP',
            20 => 'sezon',
            21 => 'lig',
            22 => 'kapsam',
            23 => 'takimi',
            24 => 'options',
        );

        $order = $columns[$request->input('order.0.column')];
        if ($order == "") {
            $order = "id";
        }

        $Istatistikler = new Istatistik;

        if ($q != "") {
            $Istatistikler = Istatistik::where('adsoyad', 'like', '%' . $q . '%')
                ->orwhere('rakip_takim', 'like', '%' . $q . '%')
                ->orwhere('sezon', 'like', '%' . $q . '%')
                ->orwhere('lig', 'like', '%' . $q . '%')
                ->orwhere('kapsam', 'like', '%' . $q . '%')
                ->orwhere('takimi', 'like', '%' . $q . '%')
                ->offset($start)->limit($limit)
                ->orderBy($order, $dir)->orderBy("id", "desc")->get();
            $totalData = $Istatistikler->count();
            $totalFiltered = $totalData;

        } else {
            $Istatistikler = $Istatistikler->offset($start)->limit($limit)->orderBy($order, $dir)->orderBy("id", "desc")->get();
            $totalData = $Istatistikler->count();
            $totalFiltered = $totalData;
        }

        $data = array();
        if ($totalFiltered > 0) {
            $total = 0;
            foreach ($Istatistikler as $Istatistik) {
                if ($Istatistik->$title_var != "") {
                    $title = $Istatistik->$title_var;
                } else {
                    $title = $Istatistik->$title_var2;
                }

                $nestedData['check'] = "<div class='row_checker'><label class=\"ui-check m-a-0\">
                                                <input type=\"checkbox\" name=\"ids[]\" value=\"" . $Istatistik->id . "\"><i class=\"dark-white\"></i>
                                                " . Form::hidden('row_ids[]', $Istatistik->id, array('class' => 'form-control row_no')) . "
                                            </label>
                                        </div>";

                $edit_btn = "<a class=\"btn btn-sm info\" href=\"href=\"/admin/istatistik/" . $Istatistik->id . "\" data-toggle=\"tooltip\" data-original-title=\" " .
                    __('backend.viewDetails') . "\">
                                    <i class=\"material-icons\">&#xe8f4;</i>
                            </a>";

                $nestedData['adsoyad'] = "<div class='text-left'>" . $Istatistik->adsoyad . "</div>";
                $nestedData['rakip_takim'] = "<div class='text-left'>" . $Istatistik->rakip_takim . "</div>";
                $nestedData['tarih'] = "<div class='text-center'>" . date("d.m.Y", strtotime($Istatistik->tarih)) . "</div>";
                $nestedData['sure'] = "<div class='text-center'>" . date("H:i:s", strtotime($Istatistik->sure)) . "</div>";
                $nestedData['sayi'] = "<div class='text-center'>" . $Istatistik->sayi . "</div>";
                $nestedData['AG'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->AG) . "</div>";
                $nestedData['AG_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->AG_deger) . "</div>";
                $nestedData['SA'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->SA) . "</div>";
                $nestedData['SA_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->SA_deger) . "</div>";
                $nestedData['S2'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->S2) . "</div>";
                $nestedData['S2_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->S2_deger) . "</div>";
                $nestedData['S3'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->S3) . "</div>";
                $nestedData['S3_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->S3_deger) . "</div>";
                $nestedData['SR'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->SR) . "</div>";
                $nestedData['SR_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->SR_deger) . "</div>";
                $nestedData['HR'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->HR) . "</div>";
                $nestedData['HR_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->HR_deger) . "</div>";
                $nestedData['TR'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->TR) . "</div>";
                $nestedData['TR_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->TR_deger) . "</div>";
                $nestedData['AST'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->AST) . "</div>";
                $nestedData['AS_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->AS_deger) . "</div>";
                $nestedData['TÇ'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->TÇ) . "</div>";
                $nestedData['TÇ_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->TÇ_deger) . "</div>";
                $nestedData['TK'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->TK) . "</div>";
                $nestedData['TK_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->TK_deger) . "</div>";
                $nestedData['BL'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->BL) . "</div>";
                $nestedData['BL_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->BL_deger) . "</div>";
                $nestedData['FA'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->FA) . "</div>";
                $nestedData['FA_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->FA_deger) . "</div>";
                $nestedData['VP'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->VP) . "</div>";
                $nestedData['VP_deger'] = "<div class='text-center'>" . str_ireplace(' ', '', $Istatistik->VP_deger) . "</div>";
                $nestedData['sezon'] = "<div class='text-center'>" . $Istatistik->sezon . "</div>";
                $nestedData['lig'] = "<div class='text-center'>" . $Istatistik->lig . "</div>";
                $nestedData['kapsam'] = "<div class='text-center'>" . $Istatistik->kapsam . "</div>";
                $nestedData['takimi'] = "<div class='text-left'>" . $Istatistik->takimi . "</div>";

                $nestedData['options'] = "<div class='text-center'>" . $edit_btn . "</div>";

                $data[] = $nestedData;
            }
        }
        $statics = [];
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        exit(json_encode($json_data));

    }

}
