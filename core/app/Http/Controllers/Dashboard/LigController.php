<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Oyuncu;
use App\Models\Lig;
use App\Models\WebmasterSection;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class LigController extends Controller
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
        $Ligs = Lig::orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));

        //Count of All Contacts
        $AllLigsCount = Lig::count();

        $search_word = "";
        $tableName = "lig";

        if(!empty($group_id) && $group_id > 0) {
            \Session::put('ContactToEdit', Lig::find($group_id));
        }
        else {
            \Session::forget('ContactToEdit');
        }
        return view("dashboard.contacts.list", compact(
            "Ligs",
            "group_id",
            "GeneralWebmasterSections",
            "AllLigsCount",
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
            //find Ligs
            $Ligs = Lig::where('adi', 'like', '%' . $request->q . '%')->orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));

            //Count of All Ligs
            $AllLigsCount = $Ligs->count();
        } else {
            //List of all Ligs
            $Ligs = Lig::orderby('adi', 'asc')->paginate(env('BACKEND_PAGINATION'));
            $AllLigsCount = Lig::count();
        }

        $search_word = $request->q;
        $tableName = "lig";

        $group_id = null;
        \Session::forget('ContactToEdit');

        return view("dashboard.contacts.list", compact(
            "GeneralWebmasterSections",
            "Ligs",
            "group_id",
            "tableName",
            "AllLigsCount",
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

        $Lig = new Lig;
        $Lig->adi = trim($request->adi);
        $Lig->save();

        $id = $Lig->id;

        if($id > 0) {
            return redirect('admin/ligs/' . $id)->with('ContactToEdit', $Lig)->with('doneMessage2', __('backend.saveDone'));
        }

        return redirect('admin/ligs/');
    }


    public function edit($id)
    {
        $ContactToEdit = Lig::find($id);
        if (!empty($ContactToEdit)) {
            return redirect()->action('Dashboard\LigController@index', $ContactToEdit->id);
        } else {
            return redirect()->action('Dashboard\LigController@index');
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

        $model = new Lig;
        $Lig = $model->find($id);

        if (!empty($Lig)) {

            $Lig->adi = trim($request->adi ?? null);
            $Lig->save();

            return redirect('admin/ligs/'.$id)->with('ContactToEdit', $Lig)->with('doneMessage2', __('backend.saveDone'));
        }
        else {
            return redirect('admin/ligs/');
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

        $Lig = Lig::find($id);

        if (!empty($Lig)) { $Lig->delete(); }

        return redirect()->action('Dashboard\LigController@index');

    }

}
