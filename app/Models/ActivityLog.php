<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'model_type', 'description'];

    public static function log($action, $modelType, $description)
    {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'description' => $description,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
