<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class DocumentModel extends ParentModel
{
    public const CATEGORY_DRIVER = 'driver';
    public const CATEGORY_VEHICLE = 'vehicle';
    public const CATEGORY_OTHER = 'other';

    /** Top-level type: Driver, Vehicle, Other */
    public static function categoryLabels(): array
    {
        return [
            self::CATEGORY_DRIVER => 'Driver',
            self::CATEGORY_VEHICLE => 'Vehicle',
            self::CATEGORY_OTHER => 'Other',
        ];
    }

    /** Driver documents */
    public static function driverSubtypes(): array
    {
        return [
            'driving_licence' => 'Driving licence',
            'aadhar' => 'Aadhar',
            'pcc' => 'PCC',
            'pan_card' => 'Pan card',
            'other' => 'Other',
        ];
    }

    /** Vehicle documents */
    public static function vehicleSubtypes(): array
    {
        return [
            'vehicle_rc' => 'Vehicle RC',
            'road_tax' => 'Road tax',
            'insurance' => 'Insurance',
            'puc' => 'PUC',
            'permit' => 'Permit',
            'fitness' => 'Fitness',
            'other' => 'Other',
        ];
    }

    /** Other category — generic */
    public static function otherSubtypes(): array
    {
        return [
            'other' => 'Other',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function subtypesForCategory(string $category): array
    {
        switch ($category) {
            case self::CATEGORY_DRIVER:
                return self::driverSubtypes();
            case self::CATEGORY_VEHICLE:
                return self::vehicleSubtypes();
            case self::CATEGORY_OTHER:
                return self::otherSubtypes();
            default:
                return [];
        }
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function subtypesByCategory(): array
    {
        return [
            self::CATEGORY_DRIVER => self::driverSubtypes(),
            self::CATEGORY_VEHICLE => self::vehicleSubtypes(),
            self::CATEGORY_OTHER => self::otherSubtypes(),
        ];
    }

    public static function subtypeLabel(?string $category, ?string $subtype): string
    {
        if ($subtype === null || $subtype === '') {
            return '—';
        }
        $map = self::subtypesForCategory((string) $category);
        if (isset($map[$subtype])) {
            return $map[$subtype];
        }
        foreach (self::subtypesByCategory() as $opts) {
            if (isset($opts[$subtype])) {
                return $opts[$subtype];
            }
        }

        return $subtype;
    }

    public static function categoryLabel(?string $category): string
    {
        if ($category === null || $category === '') {
            return '—';
        }
        $labels = self::categoryLabels();

        return $labels[$category] ?? $category;
    }

    /**
     * @param  array<int>  $project_ids
     * @return \Illuminate\Support\Collection
     */
    public function getDocumentList(array $project_ids = [])
    {
        $q = DB::table('ridetrack_compliance_document as d')
            ->leftJoin('project as p', 'p.project_id', '=', 'd.project_id')
            ->leftJoin('users as u', 'u.id', '=', 'd.assigned_user_id')
            ->leftJoin('driver as dr', 'dr.id', '=', 'd.driver_id')
            ->leftJoin('vehicle as ve', 've.vehicle_id', '=', 'd.vehicle_id')
            ->where('d.is_active', 1)
            ->select(DB::raw('d.*, p.name as project_name, u.name as assigned_user_name, dr.name as driver_name, ve.number as vehicle_number, ve.car_type as vehicle_car_type'))
            ->orderByDesc('d.expiry_date')
            ->orderByDesc('d.id');
        if (!empty($project_ids)) {
            $q->where(function ($w) use ($project_ids) {
                $w->whereNull('d.project_id')
                    ->orWhereIn('d.project_id', $project_ids);
            });
        }

        return $q->get();
    }

    /**
     * @param  array<int>  $project_ids
     * @return \Illuminate\Support\Collection
     */
    public function getExpiringBetween(string $startDate, string $endDate, array $project_ids = [])
    {
        $q = DB::table('ridetrack_compliance_document as d')
            ->leftJoin('project as p', 'p.project_id', '=', 'd.project_id')
            ->leftJoin('users as u', 'u.id', '=', 'd.assigned_user_id')
            ->leftJoin('driver as dr', 'dr.id', '=', 'd.driver_id')
            ->leftJoin('vehicle as ve', 've.vehicle_id', '=', 'd.vehicle_id')
            ->where('d.is_active', 1)
            ->whereNotNull('d.expiry_date')
            ->whereDate('d.expiry_date', '>=', $startDate)
            ->whereDate('d.expiry_date', '<=', $endDate)
            ->select(DB::raw('d.*, p.name as project_name, u.name as assigned_user_name, u.email as assigned_user_email, dr.name as driver_name, ve.number as vehicle_number, ve.car_type as vehicle_car_type'))
            ->orderBy('d.expiry_date');
        if (!empty($project_ids)) {
            $q->where(function ($w) use ($project_ids) {
                $w->whereNull('d.project_id')
                    ->orWhereIn('d.project_id', $project_ids);
            });
        }

        return $q->get();
    }

    /**
     * Documents whose expiry date is strictly before today (already expired).
     *
     * @param  array<int>  $project_ids
     * @return \Illuminate\Support\Collection
     */
    public function getExpiredDocuments(array $project_ids = [])
    {
        $today = date('Y-m-d');
        $q = DB::table('ridetrack_compliance_document as d')
            ->leftJoin('project as p', 'p.project_id', '=', 'd.project_id')
            ->leftJoin('users as u', 'u.id', '=', 'd.assigned_user_id')
            ->leftJoin('driver as dr', 'dr.id', '=', 'd.driver_id')
            ->leftJoin('vehicle as ve', 've.vehicle_id', '=', 'd.vehicle_id')
            ->where('d.is_active', 1)
            ->whereNotNull('d.expiry_date')
            ->whereDate('d.expiry_date', '<', $today)
            ->select(DB::raw('d.*, p.name as project_name, u.name as assigned_user_name, u.email as assigned_user_email, dr.name as driver_name, ve.number as vehicle_number, ve.car_type as vehicle_car_type'))
            ->orderBy('d.expiry_date');
        if (!empty($project_ids)) {
            $q->where(function ($w) use ($project_ids) {
                $w->whereNull('d.project_id')
                    ->orWhereIn('d.project_id', $project_ids);
            });
        }

        return $q->get();
    }

    public function getRow(int $id)
    {
        return DB::table('ridetrack_compliance_document')->where('id', $id)->where('is_active', 1)->first();
    }
}
