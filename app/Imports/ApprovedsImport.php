<?php

namespace App\Imports;

use App\Helpers\PhoneHelper;
use App\Helpers\TextHelper;
use App\Models\Approved;
use App\Models\ApprovedState;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;

class ApprovedsImport implements ToCollection, WithHeadingRow, WithColumnLimit, WithLimit
{
    /**
     * @var Collection<Approved> $myApproveds
     */
    private $myApproveds;

    /**
     * @param Collection<Approved> $approvedsVar
     */
    public function __construct(&$approvedsVar)
    {
        $this->myApproveds = $approvedsVar;
    }

    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            /**
             * @var string $tempPhone
             */
            $tempPhone = '';

            /**
             * @var string $tempMobile
             */
            $tempMobile = '';

            /**
             * @var array<string> $phones
             */
            $phones = explode("\n", $row['telefone']);

            /**
             * @var string $phone
             */
            foreach ($phones as &$phone) {
                $phone = str_replace('_x000D_', '', $phone); // remove carriage return on Excel multi-line cell text
                $phone = PhoneHelper::ensureAreaCode(TextHelper::removeNonDigits($phone), '27');
            }

            foreach (array_reverse($phones) as $phone) {
                if (PhoneHelper::analysePhone($phone)['type'] === 'mobile') {
                    $tempMobile = $phone;
                } else {
                    $tempPhone = $phone;
                }
            }
            /**
             * @var Approved $approved
             */
            $approved = new Approved(
                [
                    'name' => TextHelper::titleCase(mb_strtolower($row['nome'], 'UTF-8')),
                    'email' => mb_strtolower($row['e_mail'], 'UTF-8'),
                    'area_code' => PhoneHelper::firstAreaCode($tempPhone, $tempMobile, '27'),
                    'phone' => $tempPhone,
                    'mobile' => $tempMobile,
                    'announcement' => $row['edital'],
                    'approved_state_id' => ApprovedState::where('name', 'Não contatado')->first()->id,
                ]
            );

            $this->myApproveds->push($approved);
        }
    }

    /**
     * @return string
     */
    public function endColumn(): string
    {
        return 'Z';
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return 100;
    }
}
