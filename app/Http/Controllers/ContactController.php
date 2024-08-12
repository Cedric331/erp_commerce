<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPostContact;
use App\Notifications\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * @param RequestPostContact $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(RequestPostContact $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        Notification::route('mail', env('ERROR_MAILER_RECIPIENT'))->notify(new ContactNotification($data));

        return redirect()->back();
    }
}
