<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'due_date',
        'tuition',
        'sdf',
        'hot_lunch',
        'enrollment',
        'type_of_payment',
        'status_payment',
        'percentage_discount',
        'upload_ticket',
    ];

    protected $dates = ['due_date'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Get the sections for the blog post.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}
