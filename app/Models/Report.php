<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Report extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reports';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'code',
        'name',
        'address',
        'content',
        'date',
        'phone',
        'amount',
        'quantity',
        'seller',
        'note',
        'status',
        'user_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(BackpackUser::class);
    }

    public function afterCreated()
    {
        //get report count today
        $countToday = Report::where('user_id', $this->user_id)
            ->whereDate('created_at', Carbon::today())
            ->count();


        if ($countToday < 10) {
            $increaseStr = "00".$countToday;
        } elseif ($countToday < 100) {
            $increaseStr = "0".$countToday;
        } else {
            $increaseStr = "".$countToday;
        }

        $strDate = Carbon::today()->toDateString();

        $this->update([
            'code' => strtoupper($this->user->transport_code.Carbon::tomorrow()->format('dm').$increaseStr),
            'date' => $strDate,
            'seller' => $this->user->name
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
