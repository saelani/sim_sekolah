<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'sys_users';
    public $timestamps = true;
    protected $fillable = [
        'student_id',
        'teacher_id',
        'enrollment_id',
        'name',
        'email',
        'role',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    public function student()
    {
        return $this->hasMany(Student::class, 'student_id');
    }
    // public function teacher()
    // {
    //     return $this->hasMany(Teacher::class, 'teacher_id');
    // }

    public function teacher()
    {
        // Ubah dari hasMany menjadi belongsTo jika kolomnya ada di tabel sys_users (teacher_id)
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teacherByUserId()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function resolveTeacher(): ?Teacher
    {
        return $this->teacher ?? $this->teacherByUserId;
    }


    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'enrollment_id');
    }
    public function contactMessage()
    {
        return $this->hasMany(ContactMessage::class);
    }
    public function download()
    {
        return $this->hasMany(Download::class);
    }
    public function galleryItem()
    {
        return $this->hasMany(GalleryItem::class);
    }
    public function post()
    {
        return $this->hasMany(Post::class);
    }
    public function schoolAchievement()
    {
        return $this->hasMany(SchoolAchievement::class);
    }
    public function schoolFacility()
    {
        return $this->hasMany(SchoolFacility::class);
    }
    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class);
    }
    public function cache()
    {
        return $this->hasMany(Cache::class);
    }
    public function cacheLock()
    {
        return $this->hasMany(CacheLock::class);
    }
    public function jobBatch()
    {
        return $this->hasMany(JobBatch::class);
    }
    public function jobBatchSystem()
    {
        return $this->hasMany(JobBatchSystem::class);
    }
    public function job()
    {
        return $this->hasMany(Job::class);
    }
    public function passwordResetToken()
    {
        return $this->hasMany(PasswordResetToken::class);
    }
    public function personalAccessToken()
    {
        return $this->hasMany(PersonalAccessToken::class);
    }
    public function session()
    {
        return $this->hasMany(Session::class);
    }
}
