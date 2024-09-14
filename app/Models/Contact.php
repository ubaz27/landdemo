<?php

namespace App\Models;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Mail;

class Contact extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    

    
}
