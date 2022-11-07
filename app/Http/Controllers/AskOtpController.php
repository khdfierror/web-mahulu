<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kedeka\Whatsapp\Rules\OnWhatsApp;

class AskOtpController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', new OnWhatsApp],
            'timestamp' => ['required'],
        ]);
        $validator->validate();

        $otp = (new \Kedeka\WhatsappOtp\Ask)->otp($request->phone, $request->timestamp);

        return redirect()->back()->with('otp', $otp);
    }
}
