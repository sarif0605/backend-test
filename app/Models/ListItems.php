<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/ListItems.php

class ListItems extends Model
{
    use HasFactory, HasUuids;

    protected $table = "list_items";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'notes_id',
        'parent_id',
        'description',
        'is_completed',
    ];

    public function notes()
    {
        return $this->belongsTo(Notes::class, 'notes_id');
    }

    public function parent()
    {
        return $this->belongsTo(ListItems::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ListItems::class, 'parent_id')->with('children');
    }
}
