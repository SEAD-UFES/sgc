<?php

namespace App\Services;

use App\Helpers\TextHelper;
use App\Models\Approved;
use App\Models\ApprovedState;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ApprovedService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new Approved())->logListed();

        $query = Approved::with(['approvedState', 'course', 'pole', 'role']);
        $query = $query->AcceptRequest(Approved::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $approveds = $query->paginate(10);
        $approveds->withQueryString();

        return $approveds;
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     *
     * @return Approved
     */
    public function create(array $attributes): ?Approved
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $key !== 'email' ? TextHelper::titleCase($value) : mb_strtolower($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $approved = null;
        $attributes['approved_state_id'] = ApprovedState::where('name', 'Não contatado')->first()?->getAttribute('id');

        DB::transaction(static function () use ($attributes, &$approved) {
            $approved = Approved::create($attributes);
        });

        return $approved;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     *
     * @return Approved
     */
    public function read(Approved $approved): Approved
    {
        $approved->logFetched();

        return $approved;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     *
     * @return void
     */
    public function delete(Approved $approved): void
    {
        $approved->delete();
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     * @param Approved $approved
     *
     * @return void
     */
    public function changeState(array $attributes, Approved $approved): void
    {
        $new_state_id = $attributes['states'];
        $approved->approved_state_id = (int) $new_state_id;

        $approved->save();
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     *
     * @return void
     */
    public function batchStore(array $attributes): void
    {
        DB::transaction(function () use ($attributes) {
            foreach ($attributes['approveds'] as $approved) {
                if (Arr::exists($approved, 'check')) {
                    $this->create($approved);
                }
            }
        });
    }
}
