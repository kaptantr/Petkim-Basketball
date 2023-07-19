<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Oyuncu;
use App\Models\Takim;
use App\Models\WebmasterSection;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class TakimController extends Controller
{

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
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        //List of all Contacts
        $Takims = Takim::orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));

        //Count of All Contacts
        $AllTakimsCount = Takim::count();

        $search_word = "";
        $tableName = "takim";

        if(!empty($group_id) && $group_id > 0) {
            \Session::put('ContactToEdit', Takim::find($group_id));
        }
        else {
            \Session::forget('ContactToEdit');
        }
        return view("dashboard.contacts.list", compact(
            "Takims",
            "group_id",
            "GeneralWebmasterSections",
            "AllTakimsCount",
            "tableName",
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

        if ($request->q != "") {
            //find Takims
            $Takims = Takim::where('adi', 'like', '%' . $request->q . '%')->orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));

            //Count of All Takims
            $AllTakimsCount = $Takims->count();
        } else {
            //List of all Takims
            $Takims = Takim::orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));
            $AllTakimsCount = Takim::count();
        }

        $search_word = $request->q;
        $tableName = "takim";

        $group_id = null;
        \Session::forget('ContactToEdit');

        return view("dashboard.contacts.list", compact(
            "GeneralWebmasterSections",
            "Takims",
            "group_id",
            "tableName",
            "AllTakimsCount",
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

        $Takim = new Takim;
        $Takim->adi = trim($request->adi);
        $Takim->save();

        $id = $Takim->id;

        if($id > 0) {
            return redirect('admin/takims/' . $id)->with('ContactToEdit', $Takim)->with('doneMessage2', __('backend.saveDone'));
        }

        return redirect('admin/takims/');
    }


    public function edit($id)
    {
        $ContactToEdit = Takim::find($id);
        if (!empty($ContactToEdit)) {
            return redirect()->action('Dashboard\TakimController@index', $ContactToEdit->id);
        } else {
            return redirect()->action('Dashboard\TakimController@index');
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

        $model = new Takim;
        $Takim = $model->find($id);

        if (!empty($Takim)) {

            $Takim->adi = trim($request->adi ?? null);
            $Takim->save();

            return redirect('admin/takims/'.$id)->with('ContactToEdit', $Takim)->with('doneMessage2', __('backend.saveDone'));
        }
        else {
            return redirect('admin/takims/');
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

        $Takim = Takim::find($id);

        if (!empty($Takim)) { $Takim->delete(); }

        return redirect()->action('Dashboard\TakimController@index');

    }

}
