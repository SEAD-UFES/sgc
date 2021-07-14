<?php

namespace App\Imports;

use App\Models\Approved;
use App\Models\ApprovedState;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
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
        $myCollection = collect();

        foreach ($rows as $row) {
            $tempPhone = '';
            $tempMobile = '';

            $phones = explode("\n", $row['telefone']);

            foreach ($phones as &$phone) {
                $phone = ApprovedsImport::ensureAreaCode(ApprovedsImport::clearNumber($phone), '27');
            }

            foreach (array_reverse($phones) as $phone) {

                if (substr($phone, 2, 1) == "9") {
                    $tempMobile = $phone;
                } else {
                    $tempPhone =  $phone;
                }
            }

            $data = [
                'name'        => ApprovedsImport::titleCase(mb_strtolower($row['nome'], 'UTF-8')),
                'email'       => mb_strtolower($row['e_mail'], 'UTF-8'),
                'area_code'   => ApprovedsImport::getFirstAreaCode($tempPhone, $tempMobile),
                'phone'       => $tempPhone,
                'mobile'      => $tempMobile,
                'announcement' => $row['edital'],
            ];

            $approved = new Approved();

            $approved->name = $data['name'];
            $approved->email = $data['email'];
            $approved->area_code = $data['area_code'];
            $approved->phone = $data['phone'];
            $approved->mobile = $data['mobile'];
            $approved->announcement = $data['announcement'];
            $approved->approved_state_id = ApprovedState::where('name', 'Não contatado')->first()->id;

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
        if ((strlen($str) < 10) and (strlen($str) > 3))
            return $code . $str;

        return $str;
    }

    protected static function getFirstAreaCode($phone, $mobile)
    {
        $str = '';

        if (!$mobile == '')
            $str = substr($mobile, 0, 2);

        if (!$phone == '')
            $str = substr($phone, 0, 2);

        return $str;
    }

    protected static function titleCase($string, $delimiters = array(" "/* , "-", ".", "'", "O'", "Mc" */), $exceptions = array("da", "de", "do", "das", "dos", /* "út", "u", "s", "és", "utca", "tér", "krt", "körút", "sétány", */ "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII", "XIII", "XIV", "XV", "XVI", "XVII", "XVIII", "XIX", "XX", "XXI", "XXII", "XXIII", "XXIV", "XXV", "XXVI", "XXVII", "XXVIII", "XXIX", "XXX"))
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");

        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $wordnr => $word) {

                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)

                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        } //foreach
        return $string;
    }
}
