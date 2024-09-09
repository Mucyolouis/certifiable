<?php

namespace App\Models;

use App\Mail\TransferApproved;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\TransferRequestApproved;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['christian_id', 'from_church_id', 'to_church_id', 'approved_by', 'approval_status'];

    public function christian()
    {
        return $this->belongsTo(User::class);
    }

    public function fromChurch()
    {
        return $this->belongsTo(Church::class, 'from_church_id');
    }

    public function toChurch()
    {
        return $this->belongsTo(Church::class, 'to_church_id');
    }

    public function pastor()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // public function approve()
    // {
    //     $this->update([
    //         'approval_status' => 'Approved',
    //         'approved_by' => auth()->id(),
    //     ]);

    //     $this->christian->update([
    //         'church_id' => $this->to_church_id,
    //     ]);
        
    //     // Create and send the notification
    //     $this->christian->notify(new TransferRequestApproved($this));

    //     // Send email to the user
    //     Mail::to($this->christian->email)->send(new TransferApproved($this));

    //     return $this;
    // }
}
