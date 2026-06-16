<?php

namespace App\Http\Livewire\Customer;

use App\Models\Airbnbcompanion;
use App\Models\Airbnbtraveler;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Termwind\Components\Dd;

class Travelerform extends Component
{

    public $link_id, $arrival_date, $arrival_hour="11:00", $departure_date, $departure_hour="10:00", $name, $department_info, $birth_date, $document_type;
    public $document_number, $city_of_origin, $marital_status, $address, $city, $country;
    public $email, $phone, $occupation, $luggage_count, $company, $travel_purpose;

    public $cname, $cbirth_date, $cdocument_type, $cdocument_number, $cnationality, $cluggage_count;

    public $companions = [];

    public function mount($link_id)
    {
        $this->link_id = $link_id;
    }

    public function addCompanion()
    {
        if ($this->cname != "" && $this->cbirth_date != "" && $this->cdocument_type != "" && $this->cdocument_number != "" && $this->cnationality != "" && $this->cluggage_count != "") {
            $this->companions[] = array($this->cname, $this->cbirth_date, $this->cdocument_type, $this->cdocument_number, $this->cnationality, $this->cluggage_count);
            $this->reset('cname', 'cbirth_date', 'cdocument_type', 'cdocument_number', 'cnationality', 'cluggage_count');
        }
    }

    public function delCompanion($id)
    {
        unset($this->companions[$id]);
        $this->companions = array_values($this->companions);
    }


    protected $rules = [
        'arrival_date' => 'required|date',
        'arrival_hour' => 'required',
        'departure_date' => 'required|date',
        'departure_hour' => 'required',
        'name' => 'required|string|max:255',
        'department_info' => 'required',
        'birth_date' => 'required|date',
        'document_type' => 'required|string',
        'document_number' => 'required',
    ];

    public function registrar()
    {
        // dd('Entro');
        $this->validate();

        DB::beginTransaction();

        try {
            $traveler = Airbnbtraveler::create([
                'airbnblink_id' => $this->link_id,
                'arrival_date' => $this->arrival_date . ' ' . $this->arrival_hour,
                'departure_date' => $this->departure_date . ' ' . $this->departure_hour,
                'name' => $this->name,
                'department_info' => $this->department_info,
                'birth_date' => $this->birth_date,
                'document_type' => $this->document_type,
                'document_number' => $this->document_number,
                'city_of_origin' => $this->city_of_origin,
                'marital_status' => $this->marital_status,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'email' => $this->email,
                'phone' => $this->phone,
                'occupation' => $this->occupation,
                'luggage_count' => $this->luggage_count,
                'company' => $this->company,
                'travel_purpose' => $this->travel_purpose,
            ]);

            foreach ($this->companions as $companion) {
                $companionairbnb = Airbnbcompanion::create([
                    "airbnbtraveler_id" => $traveler->id,
                    "name" => $companion[0],
                    "birth_date" => $companion[1],
                    "document_type" => $companion[2],
                    "document_number" => $companion[3],
                    "nationality" => $companion[4],
                    "luggage_count" => $companion[5],
                ]);
            }

            DB::commit();
            $encryptedId = Crypt::encrypt($traveler->id);
            return redirect()->route('regsuccess', [$encryptedId]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }





        // session()->flash('message', 'Registro completado exitosamente.');
        // return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.customer.travelerform');
    }
}
