<?php

namespace App\Http\Controllers;

use Akeeba\Engine\Core\Domain\Db;
use App\Http\API\AliexpressAPI;
use App\Http\Middleware\PreventAuth;
use App\Marketing;
use App\Models\Cart;
use App\User;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id)
    {
        return view('user.profile', []);
    }

    public function history($id)
    {

        $history = Cart::tidyHistory(Cart::getCurrentHistory());
//dd($history);

        $history = $this->isSendReport($history);

        $jsData = Util::includeJsWithData("jsData", $history);
        $last = null;
        if (Input::get("last")) {
            foreach ($history as $order) $last = $order;
            $last = $last->summaryId;
        };

        return view('user.history', [
            'summaryId' => $last,
            'status' => 'index',
            'history' => $history,
            'page' => "history",
            'jsData' => $jsData
        ]);

    }

    public function isSendReport($history)
    {
        for ($i = 0; $i < $history->count(); $i++) {
            for ($j = 0; $j < $history[$i]->products->count(); $j++) {
                $result = \DB::table("itemsRoport")->where([
                    ['userId', '=', $history[$i]->products[$j]->orderUserId],
                    ['orderId', '=', $history[$i]->products[$j]->orderSummaryId],
                    ['itemId', 'LIKE', $history[$i]->products[$j]->orderProductId],
                ])->first();
                if ($result) {
                    $history[$i]->products[$j]->isSendReport = true;
                } else {
                    $history[$i]->products[$j]->isSendReport = false;
                }
            }
        }

        return $history;
    }

    public function product($id, $site, $title)
    {
        $res = "";
        switch ($site) {
            case "ebay":
                $res = $this->ebayProduct($id, $title);
                break;
            case "aliexpress":
                $res = $this->aliProduct($id);
                break;
        }

        return $res;
    }

    public function ebayProduct($id, $title)
    {
        return redirect()->action(
            'EbayController@product', [
                'title' => $title,
                'id' => $id
            ]
        );
    }

    public function aliProduct($id)
    {
        $ali = new AliexpressAPI();

        // getting url from aliexpress api
        $product = json_decode($ali->getSingleProduct($id));
        $title = $product->result->productTitle;

        // calling normal aliexpress controller
//        return redirect("/aliexpress/product/$title/$id");
        return redirect()->action(
            'AliexpressController@product', [
                'title' => $title,
                'id' => $id
            ]
        );
    }

    public function blocked(Request $request)
    {
        return view('user.blocked', ['previous_page' => $request->server('HTTP_REFERER')]);
    }

    public function update($id)
    {
        $name = Input::get("first_name");
        $surname = Input::get("last_name");
        //$email = Input::get("email");
        $city = Input::get("city");
        $street = Input::get("street");
        $buildingNumber = Input::get("building_number");
        $apartmentNumber = Input::get("apartment_number");
        $telephone = Input::get("telephone");
        $postalCode = Input::get("postal_code");
        $numEnter = Input::get("num_enter");
//        dd(Input::get());
        \DB::update("update users set name=?,surname=?,city=?,street=?,building_number=?,apartment_number=?,num_enter=?,
telephone=?,postal_code=? where id=?", [$name, $surname, $city, $street, $buildingNumber, $apartmentNumber, $numEnter, $telephone, $postalCode,
            $id]);
        $user = User::where(["id" => $id])->get()->first();
        $_SESSION['user'] = $user;

        return redirect("/profile/$id")->withInput(['status' => \Lang::get('general.success_update')]);
    }

    public function reportItem(Request $request)
    {
        $picturePath = '';
        if (Input::hasFile('photo')) {
            $destinationPath = 'images/damagedProducts/' . Input::get("orderSummaryID") . '/';
            $extension = Input::file('photo')->getClientOriginalExtension();
            $fileName = Input::get("productID") . '.' . $extension;
            Input::file('photo')->move($destinationPath, $fileName);
            $picturePath = $destinationPath . $fileName;
            //dd($picturePath);
        }

        \DB::insert("insert into itemsRoport (userId,orderId,itemId,sender,message,date,time,status,reportReason,
itemIdInDataBase,picturePath) values (?,?,?,?,?,?,?,?,?,?,?)", [Input::get("userID"), Input::get("orderSummaryID"),
            Input::get("productID"), "User", Input::get("message"), date("Y-m-d"), date("H:i:s"), "0", Input::get("reason"),
            Input::get("orderID"), $picturePath
        ]);
        return redirect("/profile/" . $_SESSION['user']->id)->withInput(['status' => \Lang::get('general.success_update')]);
    }

    protected function reportConversation()
    {
        $reports = \DB::table("itemsRoport")->where("userId", "=", $_SESSION['user']->id)->orderBy('date', 'desc')->get();

        return view('user.reportConversation', [
            'reports' => $reports,
        ]);
    }

    protected function conversation()
    {
        $tickets = \DB::table("customerService")->where("userId", "=", $_SESSION['user']->id)->orderBy('date', 'desc')->get();

        return view('user.conversation', [
            'tickets' => $tickets,
        ]);
    }

    protected function addMessageToReportService()
    {
//        $result = \DB::select("show tables");
//        dd($result);
        \DB::insert("insert into conversation (reportId,userId,orderId,itemId,sender,message,date,time) values 
(?,?,?,?,?,?,?,?)", [Input::get("reportId"), Input::get("userId"), Input::get("orderId"), Input::get("itemId"), "User",
            Input::get("message"), date("Y-m-d"), date("H:i:s")
        ]);
        \DB::update("update itemsRoport set status=? where id=?", [0, Input::get("reportId")]);
        return redirect()->back();//withInput(['status' => \Lang::get('general.success_update')]);
    }

    protected function addMessageToCustomerService()
    {
        \DB::insert("insert into customerServiceConversation (ticketId,userId,sender,message,date,time) values(?,?,?,?,?,?)", [
            Input::get("ticketId"), Input::get("userId"), "User", Input::get("message"), date("Y-m-d"), date("H:i:s")]);
        \DB::update("update customerService set status=? where id=?", [0, Input::get("ticketId")]);
        //\DB::table("customerService")->where('id', Input::get("ticketId"))->update(['status' => 0]);
        return redirect()->back();//withInput(['status' => \Lang::get('general.success_update')]);
    }

    protected function reportChat($id)
    {
        if (!isset($_SESSION['user']->id)) {
            return redirect("/auth");
        }
        $conversationsForReport = \DB::table("conversation")->where("reportId", "=", $id)
            ->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        $report = \DB::table("itemsRoport")->where("id", "=", $id)->where("userId", "=", $_SESSION['user']->id)->get();

        return view('user.reportChat', [
            'conversationsForReport' => $conversationsForReport,
            'reportInfo' => $report,
        ]);
    }

    protected function ticketChat($id)
    {
        if (!isset($_SESSION['user']->id)) {
            return redirect("/auth");
        }
        $conversationsForTicket = \DB::table("customerServiceConversation")->where("ticketId", "=", $id)
            ->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        $ticket = \DB::table("customerService")->where("id", "=", $id)->where("userId", "=", $_SESSION['user']->id)->get();
        return view('user.ticketChat', [
            'conversationsForTicket' => $conversationsForTicket,
            'ticketInfo' => $ticket,
        ]);
    }

    public function forgotPasswordIndex()
    {
        return view('user.forgot_password', [
            'title' => \Lang::get('general.forgot_pass_title')
        ]);
    }

    public function forgotPassword()
    {
        $enteredEmail = Input::get('email');
        $captcha = Input::get('g-recaptcha-response');

        $errorMsg = $captcha == null
            ? \Lang::get('general.CAPTCHA_field_required')
            : \Lang::get('general.password_reset_email_not_found');
        $failRedirect = redirect()->back()->withInput([
            'not_found' => $errorMsg
        ]);

        // go back if hackers removed js validation
        if (!$enteredEmail) return $failRedirect;

        $user = User::getBy('email', $enteredEmail);

        // go back if email not found
        if (!$user) return $failRedirect;

        // do stuff and go back on success
        User::forgotPassWord($user['email'], $user['name'], $user['password']);
        $successRedirect = redirect()->back()->withInput(['found' => \Lang::get('general.password_reset_email_found')]);
        Marketing::sendMessageForgotPassword($user['email'], $user['name'], $user['password']);
        return $successRedirect;
    }
}