<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Kedeka\InertiaContact\Model as Contact;
use Kedeka\InertiaSetting\Model as Setting;
use Kedeka\Whatsapp\Facades\WhatsApp;
use Kedeka\Whatsapp\Rules\OnWhatsApp;
use Kedeka\WhatsappOtp\Rules\Valid;

class SendMessageController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'phone' => ['required', new OnWhatsApp],
            'message' => ['required'],
            'otp' => ['required', new Valid($request->phone, $request->timestamp)],
        ]);
        $validator->validate();
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $input = $validator->validated();
        $input['phone'] = $phone;

        $contact = Contact::create([
            'name' => $input['name'],
            'phone' => $input['phone'],
            'message' => $input['message'],
        ]);

        $template = <<<'HTML'
        #name telah mengiri pesan pada #url

        ```#message```

        Nomor WA : #phone
        HTML;

        $message['text'] = WhatsApp::template($template, [
            'url' => url('/'),
            'name' => $input['name'],
            'phone' => $input['phone'],
            'message' => $input['message'],
        ]);

        /* template button gak jalan */
        // $country_code = substr($request->get('phone'), 0, 2 - strlen($request->get('phone')));
        // $phone_number = substr($request->get('phone'), 2);
        // if ($country_code == '08') {
        //     $country_code = '+628';
        // }
        // $message['templateButtons'][] = [
        //     'label' => sprintf('Hubungi %s', $input['name']),
        //     'type' => 'call',
        //     'phone' => sprintf('%s%s', $country_code, $phone_number),
        // ];
        // for send contact
        // $contact = substr(sprintf('%s%s', $country_code, $phone_number), 1); // contact whatsapp number, must start with country code eg. 62, static
        // $name = $input['name']; // required

        // on enable setting
        $settings = Arr::undot(Setting::pluck('value', 'key'));
        if (! isset($settings['contact']['phone'])) {
            $operator = config('whatsapp.receipts');
        } else {
            $operator = array_unique(explode(',', str_replace(' ', '', $settings['contact']['phone'])));
        }

        foreach ($operator as $index => $number) {
            SendMessage::dispatch(trim($number), $message)->delay((now()->addSeconds(rand(5, 8))));
        }

        return redirect()
            ->back()
            ->banner('Pesan anda telah dikirim');
    }
}
