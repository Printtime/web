<?php

namespace App;

#use App\Model\Contact;
use Illuminate\Foundation\Auth\User as Authenticatable;
use KodiComponents\Support\Upload;
use Illuminate\Http\UploadedFile;

class User extends Authenticatable
{
    use HasRoles, Upload;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'balance',
        'discount',
        'credit',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'avatar' => 'image',
    ];
    


    // public function setpasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

    /**
     * @return array
     */
    public function getUploadSettings()
    {
        return [
            'avatar' => [
                'fit' => [300, 300, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                }],
            ],
        ];
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function getUploadFilename(UploadedFile $file)
    {
        return md5($this->id).'.'.$file->getClientOriginalExtension();
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isManager()
    {
        return $this->hasRole('manager');
    }

    /**
     * @return bool
     */
    public function isDesigner()
    {
        return $this->hasRole('designer');
    }

    /**
     * @return bool
     */
    public function isPrinter()
    {
        return $this->hasRole('printer');
    }

    /**
     * @return bool
     */
    public function isStorekeeper()
    {
        return $this->hasRole('storekeeper');
    }


    public function isUser()
    {
        return $this->hasRole('user');
    }


    /**
     * @param string $password
     */
    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }

    /**
     * @return string
     */
    public function getAvatarUrlOrBlankAttribute()
    {
        if (empty($url = $this->avatar_url)) {
            return asset('images/blank.png');
        }

        return $url;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function contacts()
    // {
    //     return $this->belongsToMany(Contact::class, 'contact_id');
    // }
}
