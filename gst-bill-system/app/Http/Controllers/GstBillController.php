<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\GstBill;
use Illuminate\Support\Facades\DB;


class GstBillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    # Function to load bills
    public function index()
    {
        $bills = GstBill::where('is_deleted', 0)->with('party')->get();
        return view("gst-bill.index", compact('bills'));
    }

    # Function to load add bill
    public function addGstBill()
    {
        $data['parties'] = Party::where('party_type', 'client')->orderBy('full_name')->get();

        return view("gst-bill.add", $data);
    }

    # Function to create bill
    public function createGstBill(Request $request)
    {
        // Valildation for form
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_no' => 'required|string|max:255',
            'item_description' => 'required|max:250',
            'total_amount' => 'required|numeric',
            'cgst_rate' => 'nullable|min:0|max:100',
            'cgst_amount' => 'numeric|min:0',
            'sgst_rate' => 'nullable|min:0|max:100',
            'sgst_amount' => 'numeric|min:0',
            'igst_rate' => 'nullable|min:0|max:100',
            'igst_amount' => 'numeric|min:0',
            'tax_amount' => 'numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
        ]);

        $param = $request->all();

        // Remove token from post data before inserting
        unset($param['_token']);
        GstBill::create($param);

        // Redirect to manage bill
        return redirect()->route('manage-gst-bills')->withStatus("Bill created successfully");
    }

    # Function to load print bill
    public function print($id)
    {
        $data['bill'] = GstBill::where('id', $id)->with('party')->first();
        return view("gst-bill.print", $data);
    }
}
