<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Article extends Model
{
    use HasFactory, Sortable;

    /**
     * To specify that id is not insertable or updatable
     */
    protected $guarded = ['id'];

    public $sortable = [
        'id',
        'title',
        'category_id',
        'created_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
