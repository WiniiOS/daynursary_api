<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CountryStateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =[
            
               "England"=>[
                  "states"=>[
                     "East Midlands"=>[
                        "Derbyshire",
                        "Leicestershire",
                        "Lincolnshire",
                        "Northamptonshire",
                        "Nottinghamshire",
                        "Rutland"
                     ],
                     "East of England"=>[
                        "Bedfordshire",
                        "Cambridgeshire",
                        "Essex",
                        "Hertfordshire",
                        "Norfolk",
                        "Suffolk"
                     ],
                     "London"=>[
                        "Greater London"
                     ],
                     "North East England"=>[
                        "County Durham",
                        "Northumberland",
                        "Tyne and Wear"
                     ],
                     "North West England"=>[
                        "Cheshire",
                        "Cumbria",
                        "Greater Manchester",
                        "Lancashire",
                        "Merseyside"
                     ],
                     "South East England"=>[
                        "Berkshire",
                        "Buckinghamshire",
                        "East Sussex",
                        "Hampshire",
                        "Isle of Wight",
                        "Kent",
                        "Oxfordshire",
                        "Surrey",
                        "West Sussex"
                     ],
                     "South West England"=>[
                        "Bristol",
                        "Cornwall",
                        "Devon",
                        "Dorset",
                        "Gloucestershire",
                        "Somerset",
                        "Wiltshire"
                     ],
                     "West Midlands"=>[
                        "Herefordshire",
                        "Shropshire",
                        "Staffordshire",
                        "Warwickshire",
                        "West Midlands",
                        "Worcestershire"
                     ],
                     "Yorkshire and the Humber"=>[
                        "East Riding of Yorkshire",
                        "North Yorkshire",
                        "South Yorkshire",
                        "West Yorkshire"
                     ],
                     "major cities"=>[
                        "London",
                        "Birmingham",
                        "Manchester",
                        "Leeds",
                        "Liverpool",
                        "Newcastle",
                        "Bristol",
                        "Sheffield",
                        "Nottingham",
                        "Southampton"
                     ]
                  ]
                ],
               "Scotland"=>[
                  "states"=>[
                     "Aberdeen City"=>[
                        "Aberdeen City"
                     ],
                     "Aberdeenshire"=>[
                        "Aberdeenshire"
                     ],
                     "Angus"=>[
                        "Angus"
                     ],
                     "Argyll and Bute"=>[
                        "Argyll and Bute"
                     ],
                     "Clackmannanshire"=>[
                        "Clackmannanshire"
                     ],
                     "Dumfries and Galloway"=>[
                        "Dumfries and Galloway"
                     ],
                     "Dundee City"=>[
                        "Dundee City"
                     ],
                     "East Ayrshire"=>[
                        "East Ayrshire"
                     ],
                     "East Dunbartonshire"=>[
                        "East Dunbartonshire"
                     ],
                     "East Lothian"=>[
                        "East Lothian"
                     ],
                     "East Renfrewshire"=>[
                        "East Renfrewshire"
                     ],
                     "Edinburgh City"=>[
                        "Edinburgh City"
                     ],
                     "Falkirk"=>[
                        "Falkirk"
                     ],
                     "Fife"=>[
                        "Fife"
                     ],
                     "Glasgow City"=>[
                        "Glasgow City"
                     ],
                     "Highland"=>[
                        "Highland"
                     ],
                     "Inverclyde"=>[
                        "Inverclyde"
                     ],
                     "Midlothian"=>[
                        "Midlothian"
                     ],
                     "Moray"=>[
                        "Moray"
                     ],
                     "Na h-Eileanan Siar (Western Isles)"=>[
                        "Na h-Eileanan Siar (Western Isles)"
                     ],
                     "North Ayrshire"=>[
                        "North Ayrshire"
                     ],
                     "North Lanarkshire"=>[
                        "North Lanarkshire"
                     ],
                     "Orkney Islands"=>[
                        "Orkney Islands"
                     ],
                     "Perth and Kinross"=>[
                        "Perth and Kinross"
                     ],
                     "Renfrewshire"=>[
                        "Renfrewshire"
                     ],
                     "Scottish Borders"=>[
                        "Scottish Borders"
                     ],
                     "Shetland Islands"=>[
                        "Shetland Islands"
                     ],
                     "South Ayrshire"=>[
                        "South Ayrshire"
                     ],
                     "South Lanarkshire"=>[
                        "South Lanarkshire"
                     ],
                     "Stirling"=>[
                        "Stirling"
                     ],
                     "West Dunbartonshire"=>[
                        "West Dunbartonshire"
                     ],
                     "West Lothian"=>[
                        "West Lothian"
                     ],
                     "major_cities"=>[
                        "Glasgow",
                        "Edinburgh",
                        "Aberdeen",
                        "Dundee",
                        "Inverness"
                     ]
                  ]
             ],
               "Wales"=>[
                  "states"=>[
                     "Blaenau Gwent"=>[
                        "Blaenau Gwent"
                     ],
                     "Bridgend"=>[
                        "Bridgend"
                     ],
                     "Caerphilly"=>[
                        "Caerphilly"
                     ],
                     "Cardiff"=>[
                        "Cardiff"
                     ],
                     "Carmarthenshire"=>[
                        "Carmarthenshire"
                     ],
                     "Ceredigion"=>[
                        "Ceredigion"
                     ],
                     "Conwy"=>[
                        "Conwy"
                     ],
                     "Denbighshire"=>[
                        "Denbighshire"
                     ],
                     "Flintshire"=>[
                        "Flintshire"
                     ],
                     "Gwynedd"=>[
                        "Gwynedd"
                     ],
                     "Isle of Anglesey"=>[
                        "Isle of Anglesey"
                     ],
                     "Merthyr Tydfil"=>[
                        "Merthyr Tydfil"
                     ],
                     "Monmouthshire"=>[
                        "Monmouthshire"
                     ],
                     "Neath Port Talbot"=>[
                        "Neath Port Talbot"
                     ],
                     "Newport"=>[
                        "Newport"
                     ],
                     "Pembrokeshire"=>[
                        "Pembrokeshire"
                     ],
                     "Powys"=>[
                        "Powys"
                     ],
                     "Rhondda Cynon Taf"=>[
                        "Rhondda Cynon Taf"
                     ],
                     "Swansea"=>[
                        "Swansea"
                     ],
                     "Torfaen"=>[
                        "Torfaen"
                     ],
                     "Vale of Glamorgan"=>[
                        "Vale of Glamorgan"
                     ],
                     "Wrexham"=>[
                        "Wrexham"
                     ],
                     "major_cities"=>[
                        "Cardiff",
                        "Swansea",
                        "Newport"
                     ]
                  ]
                ],
               "Northern Ireland"=>[
                  "states"=>[
                     "Antrim"=>[
                        "Antrim"
                     ],
                     "Armagh"=>[
                        "Armagh"
                     ],
                     "Down"=>[
                        "Down"
                     ],
                     "Fermanagh"=>[
                        "Fermanagh"
                     ],
                     "Londonderry"=>[
                        "Londonderry"
                     ],
                     "Tyrone"=>[
                        "Tyrone"
                     ],
                     "major cities"=>[
                        "Belfast",
                        "Derry/Londonderry"
                     ]
                  ]
               ]
         ];
      
      //var_dump($data);

      foreach ($data as $countryName => $countryData) {
          $country = Country::create(['name' => $countryName]);

          foreach ($countryData['states'] as $stateName => $cities) {
              $state = State::create(['name' => $stateName, 'country_id' => $country->id]);

              foreach ($cities as $cityName) {
                  City::create(['name' => $cityName, 'state_id' => $state->id]);
              }
          }
      }

        
    }
}
