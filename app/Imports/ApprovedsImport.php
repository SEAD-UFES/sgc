<?php

namespace App\Imports;

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
    public $myApproveds;

    public function __construct(&$approvedsVar)
    {
        $this->myApproveds = $approvedsVar;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $tempPhone = '';
            $tempMobile = '';

            $phones = explode("\n", $row['telefone']);

            foreach ($phones as &$phone) {
                $phone = str_replace('_x000D_', '', $phone); // remove carriage return on Excel multi-line cell text
                $phone = self::ensureAreaCode(self::clearNumber($phone), '27');
            }

            foreach (array_reverse($phones) as $phone) {
                if (substr($phone, 2, 1) === '9') {
                    $tempMobile = $phone;
                } else {
                    $tempPhone = $phone;
                }
            }

            $approved = new Approved(
                [
                    'name' => TextHelper::titleCase(mb_strtolower($row['nome'], 'UTF-8')),
                    'email' => mb_strtolower($row['e_mail'], 'UTF-8'),
                    'area_code' => self::getFirstAreaCode($tempPhone, $tempMobile),
                    'phone' => $tempPhone,
                    'mobile' => $tempMobile,
                    'announcement' => $row['edital'],
                    'approved_state_id' => ApprovedState::where('name', 'Não contatado')->first()->id,
                ]
            );

            $this->myApproveds->push($approved);
        }
    }

    public function endColumn(): string
    {
        return 'Z';
    }

    public function limit(): int
    {
        return 100;
    }

    protected static function clearNumber($c)
    {
        return preg_replace('/\D/', '', $c);
    }

    protected static function ensureAreaCode($str, $code)
    {
        if ((strlen($str) < 10) and (strlen($str) > 3)) {
            return $code . $str;
        }

        return $str;
    }

    protected static function getFirstAreaCode($phone, $mobile)
    {
        $str = '';

        if (! $mobile === '') {
            $str = substr($mobile, 0, 2);
        }

        if (! $phone === '') {
            $str = substr($phone, 0, 2);
        }

        return $str;
    }
}
