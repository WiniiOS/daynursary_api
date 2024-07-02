<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;


    protected $fillable = [
        'name', 'logo', 'cover', 'address', 'lat', 'lng', 'slug', 'post_code',
        'country_id', 'state_id', 'city_id','rating',
    ];  

    

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    public function centerInfo()
    {
        return $this->hasOne(CenterInfo::class);
    }
    


  

    public function gallery()
    {
        return $this->hasMany(CenterGallery::class);
    } 



  

//     public function services()
// {
//     return $this->belongsToMany(Service::class, 'center_services');
// }


public function services()
{
    return $this->belongsToMany(Service::class, 'center_services')
                ->withPivot('age_group', 'price_per_day', 'status', 'id');
}



public function features()
{
    return $this->belongsToMany(Feature::class, 'center_features');
}

    public function hours(){

        return $this->hasMany(CenterSchedule::class);
    }

  
    public function reviews(){

        return $this->hasMany(CenterReview::class);
    }
    




    public function scopeSearch($query, $searchTerm, $searchType, $cityId, $ratingRange, $selectedServices)
{
    // Base query with common search conditions
    $baseQuery = $query;

    // Apply additional conditions based on search type
    switch ($searchType) {
        case 'city':
            $baseQuery->where('city_id', $cityId);

             
            break;

        case 'keyword':
            // Keyword search
            $baseQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%$searchTerm%")
                    ->orWhere('address', 'LIKE', "%$searchTerm%")
                    ->orWhereHas('centerInfo', function ($query) use ($searchTerm) {
                        $query->where('website', 'LIKE', "%$searchTerm%")
                            ->orWhere('facebook', 'LIKE', "%$searchTerm%")
                            ->orWhere('youtube', 'LIKE', "%$searchTerm%")
                            ->orWhere('description', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhereHas('services', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhereHas('country', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhereHas('state', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhereHas('city', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%$searchTerm%");
                    });

                    
            });
            break;

        // Add other cases for different search types if needed
    } 


    // filtering options to the querry  

    // Add rating filtering if present
    if ($ratingRange) {
        $baseQuery->whereBetween('rating', $ratingRange);
    }

    // Add selected services filtering
    if (!empty($selectedServices)) { 

        // $baseQuery->whereHas('services', function ($query) use ($selectedServices) {
        //     $query->whereIn('services.id', $selectedServices);
        // });
         $baseQuery->whereHas('primaryService', function ($query) use ($selectedServices) {
            $query->whereIn('service_id', $selectedServices);
        });

    }  

    

    return $baseQuery;
}

    



public function conversations()
{
    return $this->morphMany(Conversation::class, 'recipient');
} 





public function centerAdmin()
{
    return $this->belongsTo(CenterAdmin::class, 'center_admin_id');
}


public function primaryService()
{
    return $this->belongsTo(CenterService::class, 'service_id');
}



public function applications()
{
    return $this->hasMany(ChildCareApplication::class);
}


}
