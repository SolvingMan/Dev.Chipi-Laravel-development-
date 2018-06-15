<?php

namespace App\Http\Controllers;

use App\Marketing;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ServiceController extends Controller
{
    public function newsletter(Request $request)
    {

    }

    public function sendNewsletter()
    {
        $site = 'https://www.chipi.co.il';
        $products = \DB::table('newslatter')->orderBy('date', 'asc')->orderBy('order', 'asc')->get();
        $date = '';
        foreach ($products as $product) {
            $product->price = Util::replacePrices($product->price);
            $date = $product->date;
        }

        $nameOfMail = \DB::table('newslatterBasic')->select('nameOfEmail')->where('date', $date)->first();
        $emails = \DB::table('mailingList')->select('email')->where('subscribe', 0)->get();

        foreach ($emails as $email) {
            Mail::send("emails.newsletter", ['products' => $products, 'site' => $site, 'email' => $email->email, 'date' => $date, 'nameOfMail' => $nameOfMail->nameOfEmail], function ($message) use ($email) {
                $message->to($email->email, 'Test')->subject('Testing!');
            });
        }
    }

    public function unsubscribeNewsletter($email)
    {
        $dateUnsubscribe = \Carbon\Carbon::now()->toDateString();
        $unsubscribe = \DB::table('mailingList')->where('email', $email)->update(['subscribe' => 1, 'dateUnsubscribe' => $dateUnsubscribe]);
        if ($unsubscribe) {
            return view('main.unsubscribe');
        } else {
            return view('main.alreadyunsubscribe');
        }
    }

    public function seeEmail($email, $date)
    {
        $utm_source = $_GET['utm_source'];
        $site = 'https://www.chipi.co.il';

        $products = \DB::table('newslatter')->where('date', $date)->orderBy('date', 'asc')->orderBy('order', 'asc')->get();

        foreach ($products as $product) {
            $product->price = Util::replacePrices($product->price);

        }
        return view('emails.newsletterOnWebsite', [
            'products' => $products,
            'email' => $email,
            'site' => $site,
            'date' => $date,
            'nameOfMail' => $utm_source
        ]);
    }

    public function userCartEmail()
    {
        return view('emails.userCartEmail');
    }

    public function welcome()
    {
        return view("emails.welcome", ['name' => "Anya Zdarova"]);
    }
}
