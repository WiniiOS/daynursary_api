<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'logo' => $this->logo, 
            'cover' => $this->cover, 
            'lat' => $this->lat,
            'lng' => $this->lng,
            'distance' => $this->calculateDistanceToUser($request->lat, $request->lng),  
            
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'featured' => $this->featured,
            'country' => $this->country,
            'post_code' => $this->post_code,
            'services' => $this->services, 
            'primary_service' => $this->primaryService? $this->primaryService->service: null, 
            'price'=>$this->primaryService? $this->primaryService->price_per_day: null,
            'gallery' => $this->gallery,
            'features' => $this->features,
            'ffeatures' => $this->organizeFeatures($this->features->toArray()),
            'hours' => $this->hours,
            'reviews_count' => $this->reviews->count(),
            'averge_rating' => number_format($this->reviews->avg('rating'), 1) ,
            'center_rating' => $this->rating,
            'info' => $this->centerInfo,
            
            


        ];  

        
    }


    public function organizeFeatures($features)
{
    $groupedFeatures = [];

    foreach ($features as $feature) {
        // Check if the feature is a parent
        if ($feature['type'] == 'parent') {
            $groupedFeature = [
                'icon' => $feature['image'],
                'title' => $feature['name'],
                'tab' => [],
            ];

            // Find children for the parent
            $children = array_filter($features, function ($child) use ($feature) {
                return $child['parent_feature_slug'] == $feature['slug'];
            });

            // Organize children data
            foreach ($children as $child) {
                $groupedFeature['tab'][] = [
                    'icon' => $child['image'],
                    'text' => $child['name'],
                ];
            }

            $groupedFeatures[] = $groupedFeature;
        }
    }

    return $groupedFeatures;
}



     public function calculateDistanceToUser($userLatitude, $userLongitude)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        $dLat = deg2rad($userLatitude - $this->lat);
        $dLon = deg2rad($userLongitude - $this->lng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($this->lat)) * cos(deg2rad($userLatitude)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in kilometers
          // Format the distance to 1 decimal place
          $distance = number_format($distance, 1);

        return $distance;
    }


}

