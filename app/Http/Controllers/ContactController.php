<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPostContact;
use App\Notifications\ContactNotification;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function sendMessage(RequestPostContact $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->validated();

            Notification::route('mail', config('error-mailer.email.recipient'))
                ->notify(new ContactNotification($data));

            return redirect()->back()->with('success', 'Message envoyé avec succès');
        } catch (\Exception $e) {
            report($e); // Log l'erreur

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'envoi du message')
                ->withInput();
        }
    }
}
