<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_tasks';
    protected $primaryKey = 'task_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    protected $dates = ['due_date'];
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to',
        'manager_id'
    ];
    protected $guarded = [
        'status',
        'created_date',
        'updated_date'
    ];
    protected $casts = [
        'due_date'   =>  "datetime:Y-m-d H:i",
        'created_date' => "datetime:Y-m-d H:i",
        'updated_date' => "datetime:Y-m-d H:i"
    ];

    protected $perPage = 10;

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeByPriority(Builder $query, $priority)
    {
        if ($priority)
            return $query->where('priority', '=', $priority);
        else
            return $query;
    }

    public function scopeByStatus(Builder $query, $status)
    {
        if ($status) {
            $s = '';
            switch ($this->status) {
                case 'pinding':
                    $s = 1;
                    break;
                case 'appointed':
                    $s = 2;
                    break;
                case 'started':
                    $s = 3;
                    break;
                case 'ended':
                    $s = 4;
                    break;
                case 'falied':
                    $s = 5;
                    break;
            }
            return $query->where('status', '=', $s);
        } else {
            return $query;
        }
    }
    public function scopeByDueDate(Builder $query)
    {
        return $query->orderBy('due_date');
    }

    public function scopeManagerTasks(Builder $query, $manager_id)
    {
        return $query->where('manager_id', '=', $manager_id);
    }
    public function scopeEmployeeTasks(Builder $query, $employee_id)
    {
        return $query->where('assigned_to', '=', $employee_id);
    }
}
