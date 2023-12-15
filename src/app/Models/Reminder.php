<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    use HasFactory;

    protected function eventAt(): Attribute
    {
        return new Attribute(
            get: fn () =>  Carbon::parse($this->attributes['event_at'])->format('d/m/Y H:m')
        );
    }

    protected function remindAt(): Attribute
    {
        return new Attribute(
            get: fn () =>  Carbon::parse($this->attributes['remind_at'])->format('d/m/Y H:m')
        );
    }
}
