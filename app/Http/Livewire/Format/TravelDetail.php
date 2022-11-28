<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;

class TravelDetail extends Component
{
    public $columns = [];
    public $particulars = [];
    public $particularColumns = [];
    public $travelParticularColumns = [];
    public $travelParticularCancelColumns = [];
    public $hotelParticularColumns = [];
    public $facilityParticularColumns = [];
    public $columnCheckbox = [];
    public $columnName = [];
    public $activeSection = [];
    public $modal = 0;
    public $sectionTitle = 0;

    public function mount($columns, $particularColumns, $defaultParticular = '', $properties = '')
    {
        $this->columns = json_decode($columns, 1);
        $particularColumns = json_decode(json_encode($particularColumns), 1);
        $this->travelParticularColumns = $particularColumns['TB'];
        $this->travelParticularCancelColumns = $particularColumns['TB'];
        $this->hotelParticularColumns = $particularColumns['HB'];
        $this->facilityParticularColumns = $particularColumns['FS'];
        $this->activeSection = ['VD', 'VS', 'TB', 'HB', 'FS'];
        $this->sectionTitle = array('VD' => 'VEHICLE DETAILS', 'VS' => 'VEHICLE BOOKING DETAILS', 'TB' => 'TRAVEL BOOKING DETAILS', 'TC' => 'TRAVEL BOOKING CANCELLATION', 'HB' => 'HOTEL BOOKING DETAILS', 'FS' => 'FACILITIES DETAILS');
        if ($properties != '') {
            $this->activeSection = [];

            $properties = json_decode($properties, 1);
            if (isset($properties['vehicle_det_section'])) {
                $this->activeSection[] = 'VD';
               
            }
            if (isset($properties['vehicle_section'])) {
                $this->activeSection[] = 'VS';
                $this->sectionTitle['VS'] = $properties['vehicle_section']['title'];
            }
            if (isset($properties['travel_section']['column'])) {
                $this->activeSection[] = 'TB';
                $this->sectionTitle['TB'] = $properties['travel_section']['title'];
                $columns = $properties['travel_section']['column'];
                foreach ($this->travelParticularColumns as $k => $v) {
                    if (isset($columns[$v['system_col_name']])) {
                        $this->travelParticularColumns[$k]['is_default'] = 1;
                        $this->travelParticularColumns[$k]['column_name'] = $columns[$v['system_col_name']];
                    } else {
                        $this->travelParticularColumns[$k]['is_default'] = 0;
                    }
                }
            }

            if (isset($properties['travel_cancel_section']['column'])) {
                $columns = $properties['travel_cancel_section']['column'];
                $this->sectionTitle['TC'] = $properties['travel_cancel_section']['title'];
                foreach ($this->travelParticularCancelColumns as $k => $v) {
                    if (isset($columns[$v['system_col_name']])) {
                        $this->travelParticularCancelColumns[$k]['is_default'] = 1;
                        $this->travelParticularCancelColumns[$k]['column_name'] = $columns[$v['system_col_name']];
                    } else {
                        $this->travelParticularCancelColumns[$k]['is_default'] = 0;
                    }
                }
            }

            if (isset($properties['hotel_section']['column'])) {
                $this->activeSection[] = 'HB';
                $this->sectionTitle['HB'] = $properties['hotel_section']['title'];
                $columns = $properties['hotel_section']['column'];
                foreach ($this->hotelParticularColumns as $k => $v) {
                    if (isset($columns[$v['system_col_name']])) {
                        $this->hotelParticularColumns[$k]['is_default'] = 1;
                        $this->hotelParticularColumns[$k]['column_name'] = $columns[$v['system_col_name']];
                    } else {
                        $this->hotelParticularColumns[$k]['is_default'] = 0;
                    }
                }
            }
            if (isset($properties['facility_section']['column'])) {
                $this->activeSection[] = 'FS';
                $this->sectionTitle['FS'] = $properties['facility_section']['title'];
                $columns = $properties['facility_section']['column'];
                foreach ($this->facilityParticularColumns as $k => $v) {
                    if (isset($columns[$v['system_col_name']])) {
                        $this->facilityParticularColumns[$k]['is_default'] = 1;
                        $this->facilityParticularColumns[$k]['column_name'] = $columns[$v['system_col_name']];
                    } else {
                        $this->facilityParticularColumns[$k]['is_default'] = 0;
                    }
                }
            }
        }
        $particularColumns = $particularColumns['P'];
        $particularArray = [];
        foreach ($particularColumns as $key => $col) {
            if (isset($this->columns[$col['system_col_name']])) {
                $particularColumns[$key]['is_default'] = 1;
                $this->columnCheckbox[$col['system_col_name']] = true;
                $particularColumns[$key]['column_name'] = $this->columns[$col['system_col_name']];
            }
            $this->columnName[$col['system_col_name']] = $col['column_name'];
            $particularArray[$col['system_col_name']] = $particularColumns[$key];
        }
        $this->particularColumns = $particularArray;
        if ($defaultParticular != '') {
            $this->particulars = json_decode($defaultParticular, 1);
        }
    }

    public function modalShow($show)
    {
        $this->modal = $show;
    }

    public function submit($data)
    {
        $columns = [];
        foreach ($data as $row) {
            $col = json_decode($row, 1);
            $keys = array_keys($col);
            $columns[$keys[0]] = $col[$keys[0]];
        }

        $this->columns = [];
        foreach ($this->particularColumns as $k => $v) {
            if (isset($columns[$k])) {
                $this->particularColumns[$k]['is_default'] = 1;
                $this->columns[$k] = $this->columnName[$k];
            } else {
                $this->particularColumns[$k]['is_default'] = 0;
            }
        }
    }


    public function updated()
    {
    }

    public function remove($key)
    {
        unset($this->particulars[$key]);
    }
    public function setColumns()
    {
        $this->columns = [];
        foreach ($this->particularColumns as $k => $v) {
            if (isset($this->columnCheckbox[$k])) {
                if ($this->columnCheckbox[$k] == true) {
                    $this->columns[$k] = $this->columnName[$k];
                }
            }
        }

        $this->modal = 0;
    }

    public function addParticular()
    {
        $this->particulars[] = '';
    }

    public function render()
    {
        return view('livewire.format.travel-detail');
    }
}
