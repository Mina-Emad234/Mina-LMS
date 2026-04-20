<?php

namespace App\Models;

use App\Observers\ProgressObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['enrolement_id', 'lesson_id'])]
#[ObservedBy([ProgressObserver::class])]
class Progress extends Model
{
    use HasFactory, SoftDeletes;

    public function enrolement()
    {
        return $this->belongsTo(Enrolement::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
