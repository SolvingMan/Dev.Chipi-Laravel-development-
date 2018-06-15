<?php

namespace App\Http\Controllers;

use App\Marketing;
use App\Models\Cart;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Models\Checkout;

class AuthController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function register(Request $request)
    {
        $messages = [
//            "g-recaptcha-response.required" => Lang::get("general.CAPTCHA_field_required"),
            'email.unique' => '<a href="/user/forgot_password_index"> ' . Lang::get('general.email_exists') . '</a>',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255|email',
            'name' => 'required|max:50',
            'password' => 'required|min:6|max:20',
//            'check_rules' => 'required',
//            'g-recaptcha-response' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect('auth/?method=register')
                ->withErrors($validator)
                ->withInput();
        }

        $userExists = User::getBy("email", $request->get('email'));
        if ($userExists) {
            return redirect('auth?method=register')
                ->withInput([
                    "error" => '<a href="/user/forgot_password_index"> ' . Lang::get('general.email_exists') . '</a>',
                ]);
        }

        $userNewsletterExists = \DB::table('mailingList')->where('email', $request->get('email'))->get();
        if ($userNewsletterExists->isEmpty()) {
            \DB::table('mailingList')->insertGetId([
                "email" => $request->get('email'),
                'dateRegister' => date("Y-m-d"),
                'ip' => Checkout::getRealIpAddr(),
                'power' => 1,
            ]);
        }


        $user = User::add($request->except("_token"));
        $redirectPage = $this->loginRoutine($user);

        // make post notification
//        $notifyResult = Marketing::signInNotify($user['email'], $user['name']);

        $registerScript = true;
        // send email about new user
        Marketing::sendMail();

        $mail = new \PHPMailer();

        $mail->setFrom('info@chipi.co.il', "אתר צ'יפי");


        $mail->addAddress($user->email, "Chipi");

        $mail->AddReplyTo('info@chipi.co.il', "אתר צ'יפי");
        $mail->Subject = "ברוך הבא לאתר צ'יפי";
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Body = view("emails.welcome", ['name' => $user->name]);
        $mail->send();
//        \Mail::send("emails.welcome", ['name' => $request->get('name')], function ($message) use ($user) {
//            $message->to($user->email, $user->name)->subject("Welcome to Chipi");
//        });
        //dd($registerScript);
        return redirect($redirectPage)->withInput([
            'registerScript' => $registerScript,
            'message' => Lang::get('general.register_success')
        ]);
    }

    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('auth/?method=login')
                ->withErrors($validator)
                ->withInput();
        }

        // check get user from db
        $user = User::get($request->except("_token"));
        if ($user != "") {
            $redirectPage = $this->loginRoutine($user);
            if (isset($_SESSION['from_contactus'])) {
                unset($_SESSION['from_contactus']);
                $redirectPage = "/main/contactus";
            }
            return redirect($redirectPage)->withInput([
                'message' => Lang::get('general.login_success')
            ]);
        } else {
            return redirect('auth/?method=login')
                ->withInput([
                    "error" => Lang::get('general.no_user_found')
                ]);
        }
    }

    function loginFB(Request $request)
    {
        $user = User::loginFB($request);
        $_SESSION['user'] = $user;

        $redirectPage = $this->loginRoutine($user);
        if (isset($_SESSION['from_contactus'])) {
            unset($_SESSION['from_contactus']);
            $redirectPage = "/main/contactus";
        }
        return redirect($redirectPage)->withInput([
            'message' => Lang::get('general.login_success')
        ]);
    }

    function loginRoutine($user)
    {
        $_SESSION['user'] = $user;
        // store user id in cookie (to be able to relogin latta)
        setcookie('usr_id', $_SESSION['user']->id, time() + 3600 * 24 * 365, "/");

        // put products from session to database
        Cart::putProductsToDB($_SESSION['user']->id);
        $_SESSION['products'] = Cart::getProducts();
        $referer = isset($_SESSION['referer']) ? $_SESSION['referer'] : '/';
        // determine where to redirect user after successful login
        $redirectPage = (isset($_SESSION['products']) && count($_SESSION['products']) > 0) ? "/checkout" : $referer;

        return $redirectPage;
    }

    // function to redirect after fb login
    function redirect()
    {
        $redirectPage = isset($_SESSION['products']) ? "/cart" : "/profile/" . $_SESSION['user']->id;

        return redirect($redirectPage)->withInput([
            'message' => Lang::get('general.login_success')
        ]);
    }

    function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['products']);

        // remove user id cookie from browser
        setcookie('usr_id', " ", time() - 3600 * 24 * 365, "/");

//        return redirect()->back();
        return redirect("/");
    }

    function index(Request $request)
    {
        $_SESSION['referer'] = strpos($request->headers->get('referer'), 'chipi') !== 0 ?
            $request->headers->get('referer') : $request->server->get('SERVER_NAME');
        $rules = \DB::table('pages')->where('pageId', '=', 1)->select("pageText")->get()->first();

        return view('user.auth', [
            'rules' => $rules->pageText,
            'title' => Lang::get('general.auth_title')
        ]);
    }
}
