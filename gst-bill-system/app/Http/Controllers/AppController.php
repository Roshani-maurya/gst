<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use Illuminate\Support\Facades\DB;


class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('login');
    }

    public function about()
    {
        return view("about");
    }

    // Function to soft delete the data
    public function delete($table, $id)
    {
        $param = array('is_deleted' => 1);
        DB::table($table)->where('id', $id)->update($param);

        // Redirect back on the same page
        return redirect()->back()->withStatus("Record deleted successfully");
    }
}
