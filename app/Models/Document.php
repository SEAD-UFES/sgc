<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\DocumentFilter;

class Document extends Model
{
    use HasFactory;
    use Sortable;
    use DocumentFilter, Filterable;

    protected $table = 'documents';

    protected $fillable = [
        'original_name',
        'document_type_id',
        'documentable_type',
        'documentable_id',
        'file_data',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    public $sortable = [
        'id',
        'original_name',
        'created_at',
        'updated_at'
    ];

    private static $whiteListFilter = ['*'];

    public function employeeModel()
    {
        return $this->documentable()->first()->employee()->first();
    }

    public function employeeDocument()
    {
        return $this->belongsTo(EmployeeDocument::class, 'documentable_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public static function employeeDocumentsByEmployeeId($employeeId)
    {
        return Document::whereHasMorph('documentable', 'App\Models\EmployeeDocument', function ($query) use ($employeeId) {
            $query->where('employee_documents.employee_id', $employeeId);
        });
    }

    public static function bondDocumentsByBondId($bondId)
    {
        return Document::whereHasMorph('documentable', 'App\Models\BondDocument', function ($query) use ($bondId) {
            $query->where('bond_documents.bond_id', $bondId);
        });
    }

    public static function rightsDocumentsByBondId($bondId)
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return Document::where('documents.document_type_id', $documentType->id)
            ->whereHasMorph('documentable', 'App\Models\BondDocument', function ($query) use ($bondId) {
                $query->where('bond_documents.bond_id', $bondId);
            });
    }

    public static function rightsWithBond()
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return Document::where('documents.document_type_id', $documentType->id)
            ->whereHasMorph('documentable', 'App\Models\BondDocument', function ($query) {
                $query->whereHas('bond', function ($bondQuery) {
                    $bondQuery->whereNotNull('uaba_checked_at')->where('impediment', false);
                });
            })->with('documentable.bond');
    }

    /** @return bool  */
    public function isRights()
    {
        $rightsTypeId = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first()?->id;

        return $this->document_type_id == $rightsTypeId;
    }

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logFetched()
    {
        $this->fireModelEvent('fetched', false);
    }
}
