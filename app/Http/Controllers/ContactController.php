<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    //

    public function getContacts() {
        $allContacts = Contact::all();
        return response()->json($allContacts);
    }

    public function addContact(Request $request)
    {
        $contactID = $this->generateContactID();
        $contactName = $request->contact_name;
        $contactPhone = $request->contact_number;


        if(empty($contactName)) {
            return response()->json(['status'=>false, 'message'=>'Name_Required']);
        }
        if(empty($contactPhone)) {
            return response()->json(['status'=>false, 'message'=>'Phone_Required']);
        }

        try{
            $checkContactName = Contact::where('contact_name',$contactName)->first();
            $checkContactPhone = Contact::where('contact_number',$contactPhone)->first();
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'message'=>'Unkown_Error']);
        }

        if($checkContactName){
            return response()->json(['status'=>false, 'message'=>'Name_Exists']);
        }
        if($checkContactPhone){
            return response()->json(['status'=>false, 'message'=>'Phone_Exists']);
        }

        $contactModel = new Contact();
        $contactModel->contact_id = $contactID;
        $contactModel->contact_name = $contactName;
        $contactModel->contact_number = $contactPhone;
        if($contactModel->save()){
            return response()->json(['status'=>true, 'message'=>'Contact_saved']);
        }
        return response()->json(['status'=>false, 'message'=>'Unknown_Error']);

    }

    private function generateContactID() {
        $contactID = Str::random(20);
        $checkContactID = Contact::where('contact_id',$contactID)->first();
        if($checkContactID){
            $this->generateContactID();
            return;
        }
        return $contactID;
    }
}
