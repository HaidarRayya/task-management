<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        if ($this->manager_id != Auth::user()->user_id) {
            $data['managerName'] = User::find($this->manager_id)->name;
        }
        if ($this->assigned_to != Auth::user()->user_id) {
            $employee = User::find($this->assigned_to);
            if ($employee != null) {
                $data['employeeName'] = $employee->name;
            }
        }

        $status = '';
        switch ($this->status) {
            case 1:
                $status = 'pinding';
                break;
            case 2:
                $status = 'appointed';
                break;
            case 3:
                $status = 'started';
                break;
            case 4:
                $status = 'ended';
                break;
            case 5:
                $status = 'falied';
                break;
        }

        return [
            'taskId' => $this->task_id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'status' => $status,
            ...$data,
        ];
    }
}
