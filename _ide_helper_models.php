<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Amazon
 *
 * @mixin \Eloquent
 */
	class Amazon extends \Eloquent {}
}

namespace App{
/**
 * App\Ebay
 *
 * @mixin \Eloquent
 */
	class Ebay extends \Eloquent {}
}

namespace App{
/**
 * App\Aliexpress
 *
 * @mixin \Eloquent
 * @property int $catId
 * @property int $catIdAliexpress
 * @property string $catName
 * @property int $catOrder
 * @property string $catTitle
 * @property string $catDesc
 * @property string $catIcon
 * @property string $catPic
 * @property string $catPicWidth
 * @property string $catPicHeight
 * @property int $catPower
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatIdAliexpress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatPic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatPicHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatPicWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatPower($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aliexpress whereCatTitle($value)
 */
	class Aliexpress extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

