<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Party;
use App\Models\GstBill;
use Illuminate\Support\Facades\Validator;


class GstBillController extends Controller
{
	#To load
     
    public function index()
    {
        $bills = GstBill::with('party')->get();
        return view("gst-bill.index", compact('bills'));
    }
	
   #To Add
    public function addGstBill(){
   
       
        $data['parties'] = Party::where('party_type', 'client')->orderBy('full_name')->get();
        return view("gst-bill.add", $data);
    }
   
      # Function To create gst bill
    public function createGstBill(Request $request)
    {
        // Valildation
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

        // Redirect to manage bills
        return redirect()->route('manage-gst-bills')->withStatus("Bill created successfully");
    }
    
    
   #To Print
    public function print($id){
   
       
       $data['bill'] = Gstbill::where('id', $id)->with('party')->first();
       return view("gst-bill.print", $data);
    }
   
    
}
