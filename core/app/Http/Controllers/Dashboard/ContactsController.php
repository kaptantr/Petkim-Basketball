<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menajer;
use App\Models\Oyuncu;
use App\Models\Pozisyon;
use App\Models\Takim;
use App\Models\WebmasterSection;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class ContactsController extends Controller
{

    private $uploadPath = "uploads/contacts/";

    // Define Default Variables

    public function __construct()
    {
        $this->middleware('auth');

        // Check Permissions
        if (!@Auth::user()->permissionsGroup->newsletter_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index($group_id = null)
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        $pozisyonlar = Pozisyon::orderby('adi', 'asc')->distinct()->get();
        $takimlar = Takim::orderby('adi', 'asc')->distinct()->get();
        $menajerler = Menajer::orderby('adi', 'asc')->distinct()->get();

        //List of all Contacts
        $Contacts = Oyuncu::orderby('adsoyad', 'asc')->paginate(env('BACKEND_PAGINATION'));

        //Count of All Contacts
        $AllContactsCount = Oyuncu::count();

        $search_word = "";
        $tableName = "contact";

        if(!empty($group_id) && $group_id > 0) {
            \Session::put('ContactToEdit', Oyuncu::find($group_id));
        }
        else {
            \Session::forget('ContactToEdit');
        }
        return view("dashboard.contacts.list", compact(
            "Contacts",
            "group_id",
            "GeneralWebmasterSections",
            "pozisyonlar",
            "takimlar",
            "menajerler",
            "tableName",
            "AllContactsCount",
            "search_word"));
    }

    /**
     * Search resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        $pozisyonlar = Pozisyon::orderby('adi', 'asc')->get();
        $takimlar = Takim::orderby('adi', 'asc')->get();
        $menajerler = Menajer::orderby('adi', 'asc')->get();

        if ($request->q != "") {
            if(date('m') <= 6) {
                //find Contacts
                $Contacts = Oyuncu::where('adsoyad', 'like', '%' . $request->q . '%')
                    ->orwhere('takim_' . date('y') . '_' . (date('y') + 1) . '_1', 'like', '%' . $request->q . '%')
                    ->orderby('adsoyad', 'asc')->paginate(env('BACKEND_PAGINATION'));
            } else {
                //find Contacts
                $Contacts = Oyuncu::where('adsoyad', 'like', '%' . $request->q . '%')
                    ->orwhere('takim_' . date('y') . '_' . (date('y') + 1) . '_2', 'like', '%' . $request->q . '%')
                    ->orderby('adsoyad', 'asc')->paginate(env('BACKEND_PAGINATION'));
            }

            //Count of All Contacts
            $AllContactsCount = $Contacts->count();
        } else {
            //List of all Contacts
            $Contacts = Oyuncu::orderby('adsoyad', 'asc')->paginate(env('BACKEND_PAGINATION'));
            $AllContactsCount = Oyuncu::count();
        }

        $search_word = $request->q;
        $tableName = "contact";

        $group_id = null;
        \Session::forget('ContactToEdit');

        return view("dashboard.contacts.list", compact(
            "GeneralWebmasterSections",
            "Contacts",
            "group_id",
            "pozisyonlar",
            "takimlar",
            "tableName",
            "menajerler",
            "AllContactsCount",
            "search_word"));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->edit_status) {
            return Redirect::to(route('NoPermission'))->send();
        }

        $model = new Oyuncu;
        $rules = $model->rules;
        $data = $request->except('table', '_method', '_token', 'submit');

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('doneMessage', $validator->errors()->first());
        }

        // Start of Upload Files
        $formFileName = "file";
        $fileFinalName_tr = "";
        if ($request->{$formFileName} != "") {
            $fileFinalName_tr = time() . rand(1111, 9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
            $path = $this->getUploadPath();
            $request->file($formFileName)->move($path, $fileFinalName_tr);
        }
        // End of Upload Files

        $Oyuncu = new Oyuncu;
        $Oyuncu->adsoyad = trim($request->adsoyad ?? null);
        $Oyuncu->ana_pozisyon = trim($request->ana_pozisyon ?? null);
        $Oyuncu->yan_pozisyon = trim($request->yan_pozisyon ?? null);
        $Oyuncu->alt_kimlik = trim($request->alt_kimlik ?? null);
        $Oyuncu->boy = trim($request->boy ?? null);
        $Oyuncu->dogum_tarihi = date("Y-m-d", strtotime($request->dogum_tarihi ?? null));
        $Oyuncu->menajer = trim($request->menajer ?? null);
        $Oyuncu->oyuncu_ozellikleri = trim($request->oyuncu_ozellikleri ?? null);

        for ($i=16; $i<=34; $i++) {
            $name1 = 'takim_'.$i.'_'.($i + 1).'_1';
            $name2 = 'takim_'.$i.'_'.($i + 1).'_2';

            $Oyuncu->{$name1} = trim($request->{$name1} ?? null);
            $Oyuncu->{$name2} = trim($request->{$name2} ?? null);
        }

        $Oyuncu->status = trim($request->status ?? 1);
        $Oyuncu->save();

        $id = $Oyuncu->id;

        if($id > 0) {
            return redirect('admin/contacts/' . $id)->with('ContactToEdit', $Oyuncu)->with('doneMessage2', __('backend.saveDone'));
        }

        return redirect('admin/contacts/');
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = Config::get('app.APP_URL') . $uploadPath;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ContactToEdit = Oyuncu::find($id);
        if (!empty($ContactToEdit)) {
            return redirect()->action('Dashboard\ContactsController@index', $ContactToEdit->id);
        } else {
            return redirect()->action('Dashboard\ContactsController@index');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->edit_status) {
            return Redirect::to(route('NoPermission'))->send();
        }

        $model = new Oyuncu;
        $rules = $model->rules;
        $data = $request->except('table', '_method', '_token', 'submit');

        if(!empty($data->dogum_tarihi)) {
            $data->dogum_tarihi = date('Y-m-d', strtotime($data->dogum_tarihi));
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('doneMessage', $validator->errors()->first());
        }

        $Oyuncu = $model->find($id);

        if (!empty($Oyuncu)) {

            // Start of Upload Files
            $formFileName = "file";
            $fileFinalName_tr = "";
            if ($request->{$formFileName} != "") {
                $fileFinalName_tr = time() . rand(1111, 9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->getUploadPath();
                $request->file($formFileName)->move($path, $fileFinalName_tr);
            }
            if ($fileFinalName_tr != "") {
                if ($Oyuncu->photo != "") {
                    File::delete($this->getUploadPath() . $Oyuncu->photo);
                }
                $Oyuncu->photo = $fileFinalName_tr;
            }
            // End of Upload Files

            $Oyuncu->adsoyad = trim($request->adsoyad ?? null);
            $Oyuncu->ana_pozisyon = trim($request->ana_pozisyon ?? null);
            $Oyuncu->yan_pozisyon = trim($request->yan_pozisyon ?? null);
            $Oyuncu->alt_kimlik = trim($request->alt_kimlik ?? null);
            $Oyuncu->boy = trim($request->boy ?? null);
            $Oyuncu->dogum_tarihi = date("Y-m-d", strtotime($request->dogum_tarihi ?? null));
            $Oyuncu->menajer = trim($request->menajer ?? null);
            $Oyuncu->oyuncu_ozellikleri = trim($request->oyuncu_ozellikleri ?? null);

            for ($i=16; $i<=34; $i++) {
                $name1 = 'takim_'.$i.'_'.($i + 1).'_1';
                $name2 = 'takim_'.$i.'_'.($i + 1).'_2';

                $Oyuncu->{$name1} = trim($request->{$name1} ?? null);
                $Oyuncu->{$name2} = trim($request->{$name2} ?? null);
            }

            $Oyuncu->status = trim($request->status ?? 1);
            $Oyuncu->save();

            return redirect('admin/contacts/'.$id)->with('ContactToEdit', $Oyuncu)->with('doneMessage2', __('backend.saveDone'));
        }
        else {
            return redirect('admin/contacts/');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->delete_status) {
            return Redirect::to(route('NoPermission'))->send();
        }

        $Oyuncu = Oyuncu::find($id);

        if (!empty($Oyuncu)) {
            // Delete a Oyuncu file
            if ($Oyuncu->photo != "") {
                File::delete($this->getUploadPath() . $Oyuncu->photo);
            }

            $Oyuncu->delete();
        }
        return redirect()->action('Dashboard\ContactsController@index');

    }


    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[]
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request)
    {
        //
        if ($request->ids != "") {
            if ($request->action == "activate") {
                Oyuncu::whereIn('id', $request->ids)
                    ->update(['status' => 1]);

            } elseif ($request->action == "block") {
                Oyuncu::whereIn('id', $request->ids)
                    ->update(['status' => 0]);

            } elseif ($request->action == "delete") {
                // Check Permissions
                if (!@Auth::user()->permissionsGroup->delete_status) {
                    return Redirect::to(route('NoPermission'))->send();
                }
                // Delete Contacts file
                $Contacts = Oyuncu::whereIn('id', $request->ids)->get();
                if(!empty($Contacts)) {
                    foreach ($Contacts as $Oyuncu) {
                        if ($Oyuncu->photo != "") {
                            File::delete($this->getUploadPath() . $Oyuncu->photo);
                        }
                    }
                }

                Oyuncu::whereIn('id', $request->ids)
                    ->delete();

            }
        }
        return redirect()->action('Dashboard\ContactsController@index')->with('doneMessage', __('backend.saveDone'));
    }


}
