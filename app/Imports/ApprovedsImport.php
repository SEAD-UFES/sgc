<?php

namespace App\Imports;

use App\Helpers\Phone;
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
     * @var string $headerPhone
     */
    private string $headerPhone = 'telefone';

    /**
     * @var string $headerName
     */
    private string $headerName = 'nome';

    /**
     * @var string $headerEmail
     */
    private string $headerEmail = 'e_mail';

    /**
     * @var string $headerAnnouncement
     */
    private string $headerAnnouncement = 'edital';

    /**
     * @var Collection<int, Approved> $approveds
     */
    private Collection $approveds;

    /**
     * @var callable $fnStringToVo
     */
    private $fnStringToVo;

    /**
     * @param Collection<int, Approved> $targetApprovedsCollection
     */
    public function __construct(&$targetApprovedsCollection)
    {
        $this->approveds = $targetApprovedsCollection;
        $this->fnStringToVo = fn (string $phoneString) => $this->stringToVo($phoneString);
    }

    /**
     * @param Collection<int, Collection<string, string>> $rows
     *
     * @return void
     */
    public function collection(Collection $rows): void
    {
        /**
         * @var Collection<string, string> $row
         */
        foreach ($rows as $row) {
            /**
             * @var string $name
             */
            $name = $row[$this->headerName];
            /**
             * @var string $email
             */
            $email = $row[$this->headerEmail];
            /**
             * @var string $phones
             */
            $phones = $row[$this->headerPhone];
            /**
             * @var string $announcement
             */
            $announcement = $row[$this->headerAnnouncement];

            $name = TextHelper::titleCase(mb_strtolower($name, 'UTF-8'));
            $email = mb_strtolower($email, 'UTF-8');
            $approvedStateId = ApprovedState::where('name', 'Não contatado')
                ->first()
                ?->id;

            $phones = explode("\n", $phones);

            /**
             * @var array<Phone> $phonesVos
             */
            $phonesVos = array_map($this->fnStringToVo, $phones);

            /**
             * @var array<Phone> $landlinesVos
             */
            $landlinesVos = array_filter(
                $phonesVos,
                static fn (Phone $phoneVo) => $phoneVo->isLandline()
            );
            $landlinesVos = array_values($landlinesVos);

            /**
             * @var array<Phone> $mobilesVos
             */
            $mobilesVos = array_filter(
                $phonesVos,
                static fn (Phone $phoneVo) => $phoneVo->isMobile()
            );
            $mobilesVos = array_values($mobilesVos);

            /**
             * @var string $areaCode
             */
            $areaCode = '27';
            /**
             * @var string $mobile
             */
            $mobile = '';
            /**
             * @var string $landline
             */
            $landline = '';

            if ($mobilesVos !== []) {
                $mobile = $mobilesVos[0]->getAreaCode() .
                    $mobilesVos[0]->getNumber();
                $areaCode = $mobilesVos[0]->getAreaCode();
            }

            if ($landlinesVos !== []) {
                $landline = $landlinesVos[0]->getAreaCode() .
                    $landlinesVos[0]->getNumber();
                $areaCode = $landlinesVos[0]->getAreaCode();
            }

            $this->approveds->push(new Approved(
                [
                    'name' => $name,
                    'email' => $email,
                    'area_code' => $areaCode,
                    'phone' => $landline,
                    'mobile' => $mobile,
                    'announcement' => $announcement,
                    'approved_state_id' => $approvedStateId,
                ]
            ));
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

    /**
     * @param string $phoneString
     *
     * @return string
     */
    private function phoneSheetStringRectify(string $phoneString): string
    {
        // remove carriage return on Excel multi-line cell text
        $phoneString = str_replace('_x000D_', '', $phoneString);
        $phoneString = TextHelper::removeNonDigits($phoneString);
        return str_replace(' ', '', $phoneString);
    }

    /**
     * @param string $phoneString
     *
     * @return Phone
     */
    private function stringToVo(string $phoneString): Phone
    {
        $phoneString = $this->phoneSheetStringRectify($phoneString);
        $phoneVo = new Phone($phoneString);
        $phoneVo->overwriteEmptyAreaCodeWithDefault();
        return $phoneVo;
    }
}
