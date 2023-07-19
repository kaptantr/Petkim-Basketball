<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Oyuncu;
use App\Models\Sezon;
use App\Models\WebmasterSection;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class SezonController extends Controller
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
        $Sezons = Sezon::orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));

        //Count of All Contacts
        $AllSezonsCount = Sezon::count();

        $search_word = "";
        $tableName = "sezon";

        if(!empty($group_id) && $group_id > 0) {
            \Session::put('ContactToEdit', Sezon::find($group_id));
        }
        else {
            \Session::forget('ContactToEdit');
        }
        return view("dashboard.contacts.list", compact(
            "Sezons",
            "group_id",
            "GeneralWebmasterSections",
            "AllSezonsCount",
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
            //find Sezons
            $Sezons = Sezon::where('adi', 'like', '%' . $request->q . '%')->orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));

            //Count of All Sezons
            $AllSezonsCount = $Sezons->count();
        } else {
            //List of all Sezons
            $Sezons = Sezon::orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));
            $AllSezonsCount = Sezon::count();
        }

        $search_word = $request->q;
        $tableName = "sezon";

        $group_id = null;
        \Session::forget('ContactToEdit');

        return view("dashboard.contacts.list", compact(
            "GeneralWebmasterSections",
            "Sezons",
            "group_id",
            "tableName",
            "AllSezonsCount",
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

        $Sezon = new Sezon;
        $Sezon->adi = trim($request->adi);
        $Sezon->save();

        $id = $Sezon->id;

        if($id > 0) {
            return redirect('admin/sezons/' . $id)->with('ContactToEdit', $Sezon)->with('doneMessage2', __('backend.saveDone'));
        }

        return redirect('admin/sezons/');
    }


    public function edit($id)
    {
        $ContactToEdit = Sezon::find($id);
        if (!empty($ContactToEdit)) {
            return redirect()->action('Dashboard\SezonController@index', $ContactToEdit->id);
        } else {
            return redirect()->action('Dashboard\SezonController@index');
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

        $model = new Sezon;
        $Sezon = $model->find($id);

        if (!empty($Sezon)) {

            $Sezon->adi = trim($request->adi ?? null);
            $Sezon->save();

            return redirect('admin/sezons/'.$id)->with('ContactToEdit', $Sezon)->with('doneMessage2', __('backend.saveDone'));
        }
        else {
            return redirect('admin/sezons/');
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

        $Sezon = Sezon::find($id);

        if (!empty($Sezon)) { $Sezon->delete(); }

        return redirect()->action('Dashboard\SezonController@index');

    }

}
