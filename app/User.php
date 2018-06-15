<?php

namespace App;

use App\Models\Checkout;
use ClassesWithParents\D;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    protected $table = "users";
    public $timestamps = false;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getBy($field, $value)
    {
        $result = User::where($field, "=", $value)->first();

        return count($result) == 0 ? 0 : $result;
    }


    public static function get($data)
    {
        $email = $data['email'];
        $pass = $data['password'];

        $result = User::where("email", "=", $email)
            ->where("password", "=", $pass)
            ->get()->first();
        if (count($result) == 0) {
            $result = "";
        }

        return $result;
    }

    public static function loginFB($data)
    {
        $email = $data['email'];
        $facebookID = $data['id'];
        $name = $data['firstName'];
        $surname = $data['lastName'];

        $user = User::where("email", "=", $email)->first();
        if (count($user) == 0) {
            \DB::insert("insert into users (name,surname,facebook_id,email) values (?,?,?,?)", [
                $name, $surname, $facebookID, $email]);
        } else if (count($user) > 0 && !$user->facebook_id) {
            \DB::update("update users set facebook_id=? where email=?", [$facebookID, $email]);
        }
        $result = User::where("email", "=", $email)->first();
        return $result;
    }

    public static function add($data)
    {
        $email = $data['email'];
        $name = $data['name'];
        $surname = isset($data['surname']) ? $data['surname'] : "";
        $password = $data['password'];

        $result = "";
        $usersWithEmail = User::where("email", "=", $email)->get();
        if (count($usersWithEmail) == 0) {
            \DB::insert("insert into users (email,name,surname,password,ip,status,approved_mail,reg_date,
browser) values(?,?,?,?,?,?,?,?,?)", [$email, $name, $surname, $password, Checkout::getRealIpAddr(), 1, 1, date("Y-m-d"),
                $_SERVER['HTTP_USER_AGENT']]);
            $id = \DB::table("users")->orderBy("id","desc")->first();
            $result = User::where("id", "=", $id->id)->first();
        }

        return $result;
    }

    public function update(array $data = [], array $options = [])
    {
        return User::where("id", $options['id'])->update($data);
    }

    static function forgotPassWord($email, $name, $password)
    {
        $req = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
    <InfoMailClient>
    <UpdateContacts>
        <User>
            <Username>chipiisrael217</Username>
            <Token>652984760b078</Token>
        </User>
        <Contacts handleEvents="true">
            <Contact fname="$name" email="$email" var1="$password" addToGroup="890761"/>
        </Contacts>
    </UpdateContacts>
</InfoMailClient>
XML;

        $url = "http://infomail.inforu.co.il/api.php?xml=";
        $post_data = array(
            "xml" => $req,
        );

        $stream_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($post_data),
            ),
        );

        $context = stream_context_create($stream_options);
        $response = file_get_contents($url, null, $context);
        return $response;

    }


}
