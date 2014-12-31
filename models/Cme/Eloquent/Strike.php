<?php
namespace Cme\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Strike extends Model
{
    const PUT = 'put';

    const CALL = 'call';

    protected $fillable = [
        'bulletin_date',
        'code',
        'month',
        'type',
        'strike',
        'volume',
        'open_interest',
    ];
}
